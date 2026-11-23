<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Review;
use App\Services\ContentFilterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'rating' => ['required', 'numeric', 'min:0.5', 'max:5', function ($attribute, $value, $fail) {
                if (fmod((float) $value * 2, 1) !== 0.0) {
                    $fail('Rating harus dalam kelipatan 0.5 (contoh: 3.5, 4.0, 4.5).');
                }
            }],
            'content' => 'required|string|min:5|max:1000',
        ]);

        $userId = Auth::id();

        // Validasi aturan: satu member hanya boleh memberikan satu ulasan per buku
        $existing = Review::where('user_id', $userId)
            ->where('book_id', $request->book_id)
            ->first();

        if ($existing) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah pernah memberikan ulasan untuk buku ini. Anda dapat mengedit ulasan Anda pada menu Ulasan Saya.',
                ], 422);
            }

            return back()->with('error', 'Anda sudah pernah memberikan ulasan untuk buku ini. Anda dapat mengedit ulasan Anda pada menu Ulasan Saya.');
        }

        // Sensor kata kasar sebelum disimpan
        $cleanContent = $this->filterBadWords($request->content);

        $review = Review::create([
            'user_id' => $userId,
            'book_id' => $request->book_id,
            'rating' => $request->rating,
            'content' => $cleanContent,
        ]);

        $review->load('user');

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Ulasan berhasil diterbitkan!',
                'review' => [
                    'id' => $review->id,
                    'rating' => (float) $review->rating,
                    'content' => $review->content,
                    'user_id' => $review->user_id,
                    'user_name' => $review->user ? $review->user->name : 'Member',
                    'user_avatar' => $review->user ? $review->user->avatarUrl() : null,
                    'user_profile_url' => $review->user ? route('users.profile', $review->user->id) : '#',
                    'created_at_human' => $review->created_at ? $review->created_at->format('d M Y, H:i') : 'Baru saja',
                ],
            ]);
        }

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
            'rating' => ['required', 'numeric', 'min:0.5', 'max:5', function ($attribute, $value, $fail) {
                if (fmod((float) $value * 2, 1) !== 0.0) {
                    $fail('Rating harus dalam kelipatan 0.5 (contoh: 3.5, 4.0, 4.5).');
                }
            }],
            'content' => 'required|string|min:5|max:1000',
        ]);

        $cleanContent = $this->filterBadWords($request->content);

        $review->update([
            'rating' => $request->rating,
            'content' => $cleanContent,
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Ulasan berhasil diperbarui!',
                'review' => [
                    'id' => $review->id,
                    'rating' => (float) $review->rating,
                    'content' => $review->content,
                    'updated_at_human' => $review->updated_at ? $review->updated_at->format('d M Y, H:i') : 'Baru saja',
                ],
            ]);
        }

        return back()->with('success', 'Ulasan berhasil diperbarui!');
    }

    /**
     * 3. Hapus Ulasan (Hanya oleh Member Pemilik atau Admin)
     */
    public function destroy(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        if ($review->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki izin untuk menghapus ulasan ini.');
        }

        $review->delete();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Ulasan berhasil dihapus!',
            ]);
        }

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

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'review_id' => $reviewId,
            'comment' => $cleanComment,
        ]);

        $comment->load('user');

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Komentar berhasil dikirim!',
                'comment' => [
                    'id' => $comment->id,
                    'comment' => $comment->comment,
                    'user_id' => $comment->user_id,
                    'user_name' => $comment->user ? $comment->user->name : 'Member',
                    'user_avatar' => $comment->user ? $comment->user->avatarUrl() : null,
                    'user_profile_url' => $comment->user ? route('users.profile', $comment->user->id) : '#',
                    'created_at_human' => $comment->created_at ? $comment->created_at->diffForHumans() : 'Baru saja',
                ],
            ]);
        }

        return back()->with('success', 'Komentar berhasil dikirim!');
    }

    /**
     * 5. Hapus Komentar Sendiri
     */
    public function destroyComment(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        if ($comment->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki izin untuk menghapus komentar ini.');
        }

        $comment->delete();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Komentar berhasil dihapus!',
            ]);
        }

        return back()->with('success', 'Komentar berhasil dihapus!');
    }

    /**
     * Helper Function: Logika Filter Kata Kasar
     */
    private function filterBadWords($text)
    {
        return ContentFilterService::filter($text);
    }
}
