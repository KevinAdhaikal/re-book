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

        // Urutkan berdasarkan parameter sort (default: title A-Z)
        $sort = request('sort', 'title');
        if ($sort === 'rating') {
            $query->orderByDesc('reviews_avg_rating')->orderBy('title', 'asc');
        } elseif ($sort === 'popular') {
            $query->orderByDesc('liked_by_users_count')->orderBy('title', 'asc');
        } else {
            $query->orderBy('title', 'asc');
        }

        $books = $query->paginate(50)->withQueryString();

        // Cari data member komunitas jika sedang dalam mode pencarian
        $matchedUsers = null;
        if (request()->filled('search')) {
            $search = request('search');
            $matchedUsers = \App\Models\User::where('role', 'member')
                                            ->where(function ($q) use ($search) {
                                                $q->where('name', 'like', "%{$search}%")
                                                  ->orWhere('bio', 'like', "%{$search}%")
                                                  ->orWhere('favorite_genres', 'like', "%{$search}%");
                                            })
                                            ->paginate(12, ['*'], 'users_page')
                                            ->withQueryString();
        }

        // 2. Ambil 6 buku paling populer untuk Showcase Trending ala Letterboxd
        $trendingBooks = Book::withAvg('reviews', 'rating')
                             ->withCount(['reviews', 'likedByUsers'])
                             ->orderByDesc('liked_by_users_count')
                             ->orderByDesc('reviews_avg_rating')
                             ->take(6)
                             ->get();

        // 3. Ambil data ulasan populer + komentar
        $popularReviews = Review::with(['user', 'book', 'comments.user'])
                                ->latest()
                                ->take(6)
                                ->get();

        // 4. Kirim ke view landing
        return view('landing', compact('books', 'popularReviews', 'trendingBooks', 'matchedUsers'));
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

        $totalBookMatches = (clone $query)->count();
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

        // Cari juga member komunitas jika filter_type === 'all'
        $users = [];
        $totalUserMatches = 0;
        if ($type === 'all') {
            $userQuery = \App\Models\User::where('role', 'member')
                                         ->where(function ($q) use ($search) {
                                             $q->where('name', 'like', "%{$search}%")
                                               ->orWhere('bio', 'like', "%{$search}%")
                                               ->orWhere('favorite_genres', 'like', "%{$search}%");
                                         });
            $totalUserMatches = (clone $userQuery)->count();
            $users = $userQuery->take(4)
                               ->get()
                               ->map(function ($u) {
                                   return [
                                       'id' => $u->id,
                                       'name' => $u->name,
                                       'avatar_url' => $u->avatarUrl(),
                                       'bio' => $u->bio ? \Illuminate\Support\Str::limit($u->bio, 55) : 'Member Komunitas',
                                       'shelved_count' => $u->readingShelves()->count(),
                                       'reviews_count' => $u->reviews()->count(),
                                       'url' => route('users.profile', $u->id),
                                   ];
                               });
        }

        return response()->json([
            'success' => true,
            'books' => $books,
            'books_total' => $totalBookMatches,
            'users' => $users,
            'users_total' => $totalUserMatches,
            'total' => $totalBookMatches + $totalUserMatches,
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
