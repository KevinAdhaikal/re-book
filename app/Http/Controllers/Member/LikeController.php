<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LikeController extends Controller
{
    /**
     * Tampilkan daftar buku yang disukai oleh member yang sedang login.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Query buku yang disukai oleh user login
        $query = Book::whereHas('likedByUsers', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->withAvg('reviews', 'rating');

        // Total seluruh buku yang disukai (sebelum filter)
        $totalLiked = DB::table('book_likes')
            ->where('user_id', $userId)
            ->count();

        // Fitur Pencarian & Filter Kategori
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $filterType = $request->input('filter_type', 'all');

            if ($filterType === 'title') {
                $query->where('title', 'like', "%{$search}%");
            } elseif ($filterType === 'author') {
                $query->where('author', 'like', "%{$search}%");
            } elseif ($filterType === 'genre') {
                $query->where('genre', 'like', "%{$search}%");
            } else {
                // 'all' / Semua: mencari berdasarkan judul, penulis, dan genre
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('author', 'like', "%{$search}%")
                      ->orWhere('genre', 'like', "%{$search}%");
                });
            }
        }

        // Urutkan berdasarkan waktu like terbaru
        $likedBookIds = DB::table('book_likes')
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->pluck('book_id')
            ->toArray();

        // Jika ada id buku, jaga urutan sesuai created_at desc
        if (!empty($likedBookIds)) {
            $likedBooks = $query->get()->sortBy(function ($book) use ($likedBookIds) {
                return array_search($book->id, $likedBookIds);
            })->values();
        } else {
            $likedBooks = collect();
        }

        return view('member.likes', compact('likedBooks', 'totalLiked'));
    }

    /**
     * Toggle status Like / Unlike buku untuk member yang sedang login.
     */
    public function toggle(Request $request, $id)
    {
        if (!Auth::check()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan login terlebih dahulu untuk menyukai buku.',
                    'redirect' => route('login'),
                ], 401);
            }
            return redirect()->route('login');
        }

        $userId = Auth::id();
        $book = Book::findOrFail($id);

        $existingLike = DB::table('book_likes')
            ->where('user_id', $userId)
            ->where('book_id', $book->id)
            ->first();

        if ($existingLike) {
            DB::table('book_likes')
                ->where('user_id', $userId)
                ->where('book_id', $book->id)
                ->delete();

            $liked = false;
            $message = 'Buku dihapus dari daftar disukai.';
        } else {
            DB::table('book_likes')->updateOrInsert(
                ['user_id' => $userId, 'book_id' => $book->id],
                ['created_at' => now(), 'updated_at' => now()]
            );

            $liked = true;
            $message = 'Buku berhasil disukai!';
        }

        $totalLiked = DB::table('book_likes')
            ->where('user_id', $userId)
            ->count();

        $bookLikesCount = DB::table('book_likes')
            ->where('book_id', $book->id)
            ->count();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'liked' => $liked,
                'book_id' => (int) $book->id,
                'total_liked' => $totalLiked,
                'book_likes_count' => $bookLikesCount,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }
}
