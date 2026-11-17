<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavController extends Controller
{
    /**
     * Tampilkan halaman Buku Favorit milik member yang sedang login.
     */
    public function index()
    {
        $user = Auth::user();

        // 4 Buku Favorit milik member
        $favoriteBooks = $user->favoriteBooks()
            ->withAvg('reviews', 'rating')
            ->get();

        // Seluruh katalog buku untuk dropdown/modal tambah buku favorit
        $availableBooks = Book::withAvg('reviews', 'rating')
            ->orderBy('title', 'asc')
            ->get();

        return view('member.fav', compact('user', 'favoriteBooks', 'availableBooks'));
    }

    /**
     * Tambahkan buku ke daftar Buku Favorit member.
     */
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        $user = Auth::user();
        $bookId = (int) $request->book_id;

        if ($user->favoriteBooks()->where('book_id', $bookId)->exists()) {
            return back()->with('error', 'Buku tersebut sudah ada di daftar Buku Favorit Anda.');
        }

        if ($user->favoriteBooks()->count() >= 4) {
            return back()->with('error', 'Maksimal 4 Buku Favorit yang dapat dipajang. Silakan hapus salah satu buku terlebih dahulu.');
        }

        $user->favoriteBooks()->attach($bookId);

        return back()->with('success', 'Buku berhasil ditambahkan ke Buku Favorit!');
    }

    /**
     * Hapus buku dari daftar Buku Favorit member.
     */
    public function destroy(Request $request, $id)
    {
        $user = Auth::user();
        $book = Book::findOrFail($id);

        $user->favoriteBooks()->detach($book->id);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Buku berhasil dihapus dari Buku Favorit.',
            ]);
        }

        return back()->with('success', 'Buku berhasil dihapus dari Buku Favorit!');
    }

    /**
     * Toggle status Buku Favorit.
     */
    public function toggle(Request $request, $id)
    {
        $user = Auth::user();
        $book = Book::findOrFail($id);

        $isFavorited = $user->favoriteBooks()->where('book_id', $book->id)->exists();

        if ($isFavorited) {
            $user->favoriteBooks()->detach($book->id);
            $status = false;
            $message = 'Buku dihapus dari Buku Favorit.';
        } else {
            if ($user->favoriteBooks()->count() >= 4) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Maksimal 4 buku favorit dapat dipajang.',
                    ], 422);
                }
                return back()->with('error', 'Maksimal 4 buku favorit dapat dipajang.');
            }

            $user->favoriteBooks()->attach($book->id);
            $status = true;
            $message = 'Buku berhasil ditambahkan ke Buku Favorit!';
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'favorited' => $status,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }
}
