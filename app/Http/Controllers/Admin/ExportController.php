<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    /**
     * Halaman Menu Export Data Admin
     */
    public function index()
    {
        $totalBooks = Book::count();
        $totalUsers = User::where('role', 'member')->count();
        $totalReviews = Review::count();

        return view('admin.exports.index', compact('totalBooks', 'totalUsers', 'totalReviews'));
    }

    /**
     * Export Seluruh Data Buku ke Format CSV
     */
    public function exportBooks(): StreamedResponse
    {
        $filename = 'rebook_katalog_buku_' . date('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            // Tambahkan UTF-8 BOM untuk kompatibilitas Excel
            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['ID', 'Judul Buku', 'Penulis', 'Genre', 'Rating Rata-rata', 'Total Ulasan', 'Total Disukai', 'Tanggal Ditambahkan']);

            Book::withAvg('reviews', 'rating')
                ->withCount(['reviews', 'likedByUsers'])
                ->orderBy('title', 'asc')
                ->chunk(100, function ($books) use ($handle) {
                    foreach ($books as $b) {
                        fputcsv($handle, [
                            $b->id,
                            $b->title,
                            $b->author,
                            $b->genre,
                            number_format($b->reviews_avg_rating ?? 0, 1),
                            $b->reviews_count,
                            $b->liked_by_users_count,
                            $b->created_at ? $b->created_at->format('Y-m-d H:i:s') : '-',
                        ]);
                    }
                });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Export Seluruh Data Member ke Format CSV
     */
    public function exportUsers(): StreamedResponse
    {
        $filename = 'rebook_daftar_member_' . date('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['ID', 'Nama Lengkap', 'Email', 'Role', 'Status', 'Bio', 'Genre Favorit', 'Total Ulasan', 'Total Buku Disukai', 'Total Pengikut', 'Tanggal Bergabung']);

            User::withCount(['reviews', 'likedBooks', 'followers'])
                ->orderBy('id', 'asc')
                ->chunk(100, function ($users) use ($handle) {
                    foreach ($users as $u) {
                        $genres = is_array($u->favorite_genres) ? implode(', ', $u->favorite_genres) : '';
                        fputcsv($handle, [
                            $u->id,
                            $u->name,
                            $u->email,
                            $u->role,
                            $u->status,
                            $u->bio ?? '-',
                            $genres ?: '-',
                            $u->reviews_count,
                            $u->liked_books_count,
                            $u->followers_count,
                            $u->created_at ? $u->created_at->format('Y-m-d H:i:s') : '-',
                        ]);
                    }
                });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Export Seluruh Data Ulasan ke Format CSV
     */
    public function exportReviews(): StreamedResponse
    {
        $filename = 'rebook_seluruh_ulasan_' . date('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['ID Ulasan', 'Judul Buku', 'Nama Member', 'Email Member', 'Rating', 'Isi Ulasan', 'Total Like Ulasan', 'Total Komentar', 'Tanggal Ulasan']);

            Review::with(['user', 'book'])
                ->withCount(['likedByUsers', 'comments'])
                ->latest()
                ->chunk(100, function ($reviews) use ($handle) {
                    foreach ($reviews as $r) {
                        fputcsv($handle, [
                            $r->id,
                            $r->book->title ?? '-',
                            $r->user->name ?? '-',
                            $r->user->email ?? '-',
                            $r->rating,
                            $r->content,
                            $r->liked_by_users_count,
                            $r->comments_count,
                            $r->created_at ? $r->created_at->format('Y-m-d H:i:s') : '-',
                        ]);
                    }
                });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
