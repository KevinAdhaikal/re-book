<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Comment;
use App\Models\BadWord;

class ReviewController extends Controller
{
    /**
     * Halaman "Ulasan Saya" khusus untuk member yang sedang login.
     */
    public function index()
    {
        $user = Auth::user();
        $myReviews = Review::with(['book', 'comments.user'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('member.reviews', compact('myReviews'));
    }

    /**
     * 1. Simpan Ulasan Baru dari Member
     */
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'rating'  => 'required|integer|min:1|max:5',
            'content' => 'required|string|min:5|max:1000',
        ]);

        $userId = Auth::id();

        // Validasi aturan: satu member hanya boleh memberikan satu ulasan per buku
        $existing = Review::where('user_id', $userId)
            ->where('book_id', $request->book_id)
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah pernah memberikan ulasan untuk buku ini. Anda dapat mengedit ulasan Anda pada menu Ulasan Saya.');
        }

        // Sensor kata kasar sebelum disimpan
        $cleanContent = $this->filterBadWords($request->content);

        Review::create([
            'user_id' => $userId,
            'book_id' => $request->book_id,
            'rating'  => $request->rating,
            'content' => $cleanContent,
        ]);

        return back()->with('success', 'Ulasan berhasil diterbitkan!');
    }

    /**
     * 2. Update / Edit Ulasan Milik Member
     */
    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        // Pastikan hanya pemilik ulasan yang bisa mengedit
        if ($review->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengubah ulasan ini.');
        }

        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'content' => 'required|string|min:5|max:1000',
        ]);

        $cleanContent = $this->filterBadWords($request->content);

        $review->update([
            'rating'  => $request->rating,
            'content' => $cleanContent,
        ]);

        return back()->with('success', 'Ulasan berhasil diperbarui!');
    }

    /**
     * 3. Hapus Ulasan (Hanya oleh Member Pemilik atau Admin)
     */
    public function destroy($id)
    {
        $review = Review::findOrFail($id);

        if ($review->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki izin untuk menghapus ulasan ini.');
        }

        $review->delete();

        return back()->with('success', 'Ulasan berhasil dihapus!');
    }

    /**
     * 4. Tambah Komentar di Ulasan Member Lain
     */
    public function storeComment(Request $request, $reviewId)
    {
        $request->validate([
            'comment' => 'required|string|min:1|max:500',
        ]);

        $cleanComment = $this->filterBadWords($request->comment);

        Comment::create([
            'user_id'   => Auth::id(),
            'review_id' => $reviewId,
            'comment'   => $cleanComment,
        ]);

        return back()->with('success', 'Komentar berhasil dikirim!');
    }

    /**
     * 5. Hapus Komentar Sendiri
     */
    public function destroyComment($id)
    {
        $comment = Comment::findOrFail($id);

        if ($comment->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki izin untuk menghapus komentar ini.');
        }

        $comment->delete();

        return back()->with('success', 'Komentar berhasil dihapus!');
    }

    /**
     * Helper Function: Logika Filter Kata Kasar
     */
    private function filterBadWords($text)
    {
        $badWords = BadWord::pluck('word')->toArray();

        foreach ($badWords as $word) {
            $replacement = str_repeat('*', strlen($word));
            $text = preg_replace('/\b' . preg_quote($word, '/') . '\b/i', $replacement, $text);
        }

        return $text;
    }
}
