<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;
use App\Models\Review;
use App\Models\BadWord;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBooks     = Book::count();
        $totalMembers   = User::where('role', 'member')->count();
        $activeMembers  = User::where('role', 'member')->where('status', 'Aktif')->count();
        $blockedMembers = User::where('role', 'member')->where('status', 'Diblokir')->count();
        $totalReviews   = Review::count();
        $totalWords     = BadWord::count();

        $bookLikesCount   = DB::table('book_likes')->count();
        $reviewLikesCount = DB::table('review_likes')->count();
        $totalInteractions = $bookLikesCount + $reviewLikesCount;

        $recentReviews = Review::with(['user', 'book'])->latest()->take(5)->get();

        $popularBooks = Book::withAvg('reviews', 'rating')
                            ->withCount(['reviews', 'likedByUsers'])
                            ->orderByDesc('liked_by_users_count')
                            ->orderByDesc('reviews_avg_rating')
                            ->take(5)
                            ->get();

        return view('admin.dashboard', compact(
            'totalBooks', 
            'totalMembers', 
            'activeMembers',
            'blockedMembers',
            'totalReviews', 
            'totalWords', 
            'totalInteractions',
            'recentReviews',
            'popularBooks'
        ));
    }
}
