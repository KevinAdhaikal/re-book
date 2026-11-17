<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    // Halaman Landing Page (Public View)
    public function index()
    {
        $query = Book::withAvg('reviews', 'rating')->withCount(['reviews', 'likedByUsers']);

        // Mengambil inputan search & filter_type langsung pakai helper request()
        if (request()->filled('search')) {
            $search = request('search');
            $type   = request('filter_type');

            if ($type === 'title') {
                $query->where('title', 'like', "%{$search}%");
            } elseif ($type === 'author') {
                $query->where('author', 'like', "%{$search}%");
            } elseif ($type === 'genre') {
                $query->where('genre', 'like', "%{$search}%");
            } else {
                // Jika memilih 'Semua'
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('author', 'like', "%{$search}%")
                      ->orWhere('genre', 'like', "%{$search}%");
                });                      
            }
        }

        // Urutkan A-Z berdasarkan Judul Buku
        $books = $query->orderBy('title', 'asc')
                       ->paginate(50)
                       ->withQueryString();

        // 2. Ambil data ulasan populer + komentar
        $popularReviews = Review::with(['user', 'book', 'comments.user'])
                                ->latest()
                                ->take(5)
                                ->get();

        // 3. Kirim KEDUANYA ke view landing
        return view('landing', compact('books', 'popularReviews'));
    }

    // Detail Buku & Ulasannya
    public function show($id)
    {
        $book = Book::with(['reviews.user', 'reviews.comments.user'])->findOrFail($id);
        return view('books.show', compact('book'));
    }

    // [API/AJAX] Pencarian Cepat Live Dropdown Search
    public function quickSearch(\Illuminate\Http\Request $request)
    {
        $search = trim($request->input('search', ''));
        $type = $request->input('filter_type', 'all');

        if (mb_strlen($search) < 2) {
            return response()->json([
                'success' => true,
                'books' => [],
                'total' => 0,
            ]);
        }

        $query = Book::withAvg('reviews', 'rating')->withCount(['reviews', 'likedByUsers']);

        if ($type === 'title') {
            $query->where('title', 'like', "%{$search}%");
        } elseif ($type === 'author') {
            $query->where('author', 'like', "%{$search}%");
        } elseif ($type === 'genre') {
            $query->where('genre', 'like', "%{$search}%");
        } else {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('genre', 'like', "%{$search}%");
            });
        }

        $totalMatches = (clone $query)->count();
        $books = $query->orderBy('title', 'asc')
                       ->take(5)
                       ->get()
                       ->map(function ($b) {
                           return [
                               'id' => $b->id,
                               'title' => $b->title,
                               'author' => $b->author,
                               'genre' => $b->genre,
                               'cover_url' => $b->cover ? asset('storage/' . $b->cover) : null,
                               'rating' => round($b->reviews_avg_rating ?? 0, 1),
                               'reviews_count' => $b->reviews_count ?? 0,
                               'likes_count' => $b->liked_by_users_count ?? 0,
                               'url' => route('books.show', $b->id),
                           ];
                       });

        return response()->json([
            'success' => true,
            'books' => $books,
            'total' => $totalMatches,
            'query' => $search,
        ]);
    }

    // [ADMIN] Halaman Kelola Buku
    public function manage()
    {
        $books = Book::withAvg('reviews', 'rating')
                      ->orderBy('title', 'asc')
                      ->paginate(10)
                      ->withQueryString();
        return view('admin.books.index', compact('books'));
    }

    // [ADMIN] Simpan Buku Baru
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'genre' => 'required|string',
            'description' => 'required|string',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $coverPath = null;
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('covers', 'public');
        }

        Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'genre' => $request->genre,
            'description' => $request->description,
            'cover' => $coverPath,
        ]);

        return back()->with('success', 'Buku berhasil ditambahkan!');
    }

    // Method untuk memproses perubahan data buku
    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $request->validate([
            'title'       => 'required|string|max:255',
            'author'      => 'required|string|max:255',
            'genre'       => 'required|string',
            'description' => 'required|string',
            'cover'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = [
            'title'       => $request->title,
            'author'      => $request->author,
            'genre'       => $request->genre,
            'description' => $request->description,
        ];

        // Jika admin mengunggah foto cover baru
        if ($request->hasFile('cover')) {
            // Hapus cover lama dari storage jika ada
            if ($book->cover && Storage::disk('public')->exists($book->cover)) {
                Storage::disk('public')->delete($book->cover);
            }

            // Simpan cover baru
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        $book->update($data);

        return back()->with('success', 'Data buku berhasil diperbarui!');
    }

    // [ADMIN] Hapus Buku
    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return back()->with('success', 'Buku berhasil dihapus!');
    }
}
