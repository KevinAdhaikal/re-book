<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\ReadingShelf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShelfController extends Controller
{
    /**
     * Tampilkan halaman Rak Bacaan Member (Want to Read, Currently Reading, Read)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $statusFilter = $request->query('status', 'all');

        $query = $user->readingShelves()->with(['book.reviews']);

        if (in_array($statusFilter, [ReadingShelf::STATUS_WANT_TO_READ, ReadingShelf::STATUS_CURRENTLY_READING, ReadingShelf::STATUS_READ])) {
            $query->where('status', $statusFilter);
        }

        if ($request->filled('search')) {
            $search = trim($request->query('search'));
            $query->whereHas('book', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhere('genre', 'like', "%{$search}%");
            });
        }

        $shelvedItems = $query->latest('updated_at')->paginate(12)->withQueryString();

        // Hitung statistik rak
        $counts = [
            'all' => $user->readingShelves()->count(),
            ReadingShelf::STATUS_WANT_TO_READ => $user->readingShelves()->where('status', ReadingShelf::STATUS_WANT_TO_READ)->count(),
            ReadingShelf::STATUS_CURRENTLY_READING => $user->readingShelves()->where('status', ReadingShelf::STATUS_CURRENTLY_READING)->count(),
            ReadingShelf::STATUS_READ => $user->readingShelves()->where('status', ReadingShelf::STATUS_READ)->count(),
        ];

        return view('member.shelf', compact('user', 'shelvedItems', 'statusFilter', 'counts'));
    }

    /**
     * Tambahkan atau perbarui status buku di Rak Bacaan
     */
    public function update(Request $request, $bookId)
    {
        $request->validate([
            'status' => 'required|in:want_to_read,currently_reading,read',
        ]);

        $user = Auth::user();
        $book = Book::findOrFail($bookId);

        $shelf = ReadingShelf::updateOrCreate(
            ['user_id' => $user->id, 'book_id' => $book->id],
            ['status' => $request->status]
        );

        $label = $shelf->status_label;

        $counts = [
            'all' => $user->readingShelves()->count(),
            ReadingShelf::STATUS_WANT_TO_READ => $user->readingShelves()->where('status', ReadingShelf::STATUS_WANT_TO_READ)->count(),
            ReadingShelf::STATUS_CURRENTLY_READING => $user->readingShelves()->where('status', ReadingShelf::STATUS_CURRENTLY_READING)->count(),
            ReadingShelf::STATUS_READ => $user->readingShelves()->where('status', ReadingShelf::STATUS_READ)->count(),
        ];

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'status' => $shelf->status,
                'status_label' => $label,
                'counts' => $counts,
                'message' => "Buku berhasil dimasukkan ke rak: {$label}",
            ]);
        }

        return back()->with('success', "Status buku diperbarui ke rak '{$label}'!");
    }

    /**
     * Hapus buku dari Rak Bacaan
     */
    public function destroy(Request $request, $bookId)
    {
        $user = Auth::user();
        ReadingShelf::where('user_id', $user->id)->where('book_id', $bookId)->delete();

        $counts = [
            'all' => $user->readingShelves()->count(),
            ReadingShelf::STATUS_WANT_TO_READ => $user->readingShelves()->where('status', ReadingShelf::STATUS_WANT_TO_READ)->count(),
            ReadingShelf::STATUS_CURRENTLY_READING => $user->readingShelves()->where('status', ReadingShelf::STATUS_CURRENTLY_READING)->count(),
            ReadingShelf::STATUS_READ => $user->readingShelves()->where('status', ReadingShelf::STATUS_READ)->count(),
        ];

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'status' => null,
                'counts' => $counts,
                'message' => 'Buku berhasil dihapus dari rak bacaan.',
            ]);
        }

        return back()->with('success', 'Buku berhasil dihapus dari rak bacaan.');
    }
}
