<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewModerationController extends Controller
{
    /**
     * Tabel Moderasi Ulasan Global untuk Admin
     */
    public function index(Request $request)
    {
        $query = Review::with(['user', 'book', 'comments'])->withCount('likedByUsers');

        // Filter Buku
        if ($request->filled('book_id')) {
            $query->where('book_id', $request->book_id);
        }

        // Filter Rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Filter Pencarian Teks Ulasan / Nama User
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('content', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('book', function ($bq) use ($search) {
                      $bq->where('title', 'like', "%{$search}%");
                  });
            });
        }

        // Pengurutan (Sorting)
        $sort = $request->input('sort', 'newest');
        if ($sort === 'oldest') {
            $query->oldest('id');
        } elseif ($sort === 'rating_desc') {
            $query->orderByDesc('rating')->latest('id');
        } elseif ($sort === 'rating_asc') {
            $query->orderBy('rating', 'asc')->latest('id');
        } elseif ($sort === 'likes') {
            $query->orderByDesc('liked_by_users_count')->latest('id');
        } else {
            // Default: Terbaru
            $query->latest('id');
        }

        $reviews = $query->paginate(15)->withQueryString();
        $books = Book::orderBy('title', 'asc')->get();

        return view('admin.reviews.index', compact('reviews', 'books', 'sort'));
    }

    /**
     * Hapus ulasan dari panel admin
     */
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $bookTitle = $review->book->title ?? 'Buku';
        $userName = $review->user->name ?? 'User';

        $review->delete();

        return back()->with('success', "Ulasan dari {$userName} pada buku '{$bookTitle}' berhasil dihapus oleh admin.");
    }
}
