<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;
use App\Models\Review;
use App\Models\BadWord;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBooks   = Book::count();
        $totalMembers = User::where('role', 'member')->count();
        $totalReviews = Review::count();
        $totalWords   = BadWord::count();

        $recentReviews = Review::with(['user', 'book'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalBooks', 
            'totalMembers', 
            'totalReviews', 
            'totalWords', 
            'recentReviews'
        ));
    }
}