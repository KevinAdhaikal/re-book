<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    /**
     * Halaman Utama Hasil Pencarian Mandiri (/search)
     */
    public function index(Request $request)
    {
        $search = trim($request->input('q', $request->input('search', '')));
        $tab = $request->input('tab', 'books');
        $genre = $request->input('genre', 'all');
        $minRating = floatval($request->input('min_rating', 0));
        $sort = $request->input('sort', 'relevance');
        $view = $request->input('view', 'list'); // 'list' atau 'grid'

        // 1. Query Buku
        $bookQuery = Book::withAvg('reviews', 'rating')->withCount(['reviews', 'likedByUsers']);

        if ($search !== '') {
            $bookQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('genre', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($genre !== 'all' && !empty($genre)) {
            $bookQuery->where('genre', $genre);
        }

        if ($minRating > 0) {
            $bookQuery->whereRaw('(select avg(rating) from reviews where reviews.book_id = books.id) >= ' . floatval($minRating));
        }

        // Pengurutan (Sorting)
        if ($sort === 'rating') {
            $bookQuery->orderByDesc('reviews_avg_rating')->orderBy('title', 'asc');
        } elseif ($sort === 'popular') {
            $bookQuery->orderByDesc('liked_by_users_count')->orderBy('title', 'asc');
        } elseif ($sort === 'title') {
            $bookQuery->orderBy('title', 'asc');
        } else {
            // Relevansi default: paling banyak suka dan rating tinggi
            $bookQuery->orderByDesc('liked_by_users_count')->orderByDesc('reviews_avg_rating');
        }

        $books = $bookQuery->paginate(12)->withQueryString();

        // 2. Query Member Komunitas
        $userQuery = User::where('role', 'member')->withCount(['reviews', 'readingShelves']);
        if ($search !== '') {
            $userQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('bio', 'like', "%{$search}%")
                  ->orWhere('favorite_genres', 'like', "%{$search}%");
            });
        }
        $users = $userQuery->paginate(12, ['*'], 'users_page')->withQueryString();

        // 3. Fallback Rekomendasi (4 buku terpopuler jika hasil 0)
        $fallbackBooks = collect();
        if ($books->total() === 0 && $users->total() === 0) {
            $fallbackBooks = Book::withAvg('reviews', 'rating')
                                 ->withCount(['reviews', 'likedByUsers'])
                                 ->orderByDesc('liked_by_users_count')
                                 ->take(4)
                                 ->get();
        }

        // Daftar Genre Dinamis untuk Dropdown Filter
        $genres = Book::select('genre')->distinct()->pluck('genre')->filter()->values();

        return view('search.index', compact(
            'search',
            'books',
            'users',
            'fallbackBooks',
            'genres',
            'genre',
            'minRating',
            'sort',
            'tab',
            'view'
        ));
    }
}
