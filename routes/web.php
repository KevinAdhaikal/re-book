<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BadWordController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Member\ProfileController;
use App\Http\Controllers\Member\LikeController;
use App\Http\Controllers\Member\FavController;

/*
|--------------------------------------------------------------------------
| Web Routes - Project Re:Book
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. PUBLIC / GUEST ROUTES (IMDb Style Landing)
// ==========================================
Route::get('/', [BookController::class, 'index'])->name('home');
Route::get('/book/{id}', [BookController::class, 'show'])->name('books.show');

// Profil Publik Member (Dapat Dilihat Pengguna Lain)
Route::get('/user/{id}', [ProfileController::class, 'show'])->name('users.profile');
Route::get('/profile/{id}', [ProfileController::class, 'show'])->name('users.profile.alias');

// Auth Routes (Login & Register)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


// ==========================================
// 2. MEMBER ROUTES (Sidebar Member & Authenticated Actions)
// ==========================================
Route::middleware(['auth'])->prefix('member')->as('member.')->group(function () {
    
    // Profil & Showcase 4 Buku Favorit
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.destroy');
    Route::post('/profile/favorites', [ProfileController::class, 'updateFavorites'])->name('favorites.update');

    // Buku Favorit (Dedicated Page & Actions)
    Route::get('/fav', [FavController::class, 'index'])->name('fav');
    Route::post('/fav', [FavController::class, 'store'])->name('fav.store');
    Route::delete('/fav/{id}', [FavController::class, 'destroy'])->name('fav.destroy');
    Route::post('/fav/{id}/toggle', [FavController::class, 'toggle'])->name('fav.toggle');

    // Buku Disukai (Likes)
    Route::get('/likes', [LikeController::class, 'index'])->name('likes.index');
    Route::post('/books/{id}/toggle-like', [LikeController::class, 'toggle'])->name('likes.toggle');
    Route::post('/books/{id}/like', [LikeController::class, 'toggle'])->name('books.like');

    // Fitur Ulasan (Review) Member (Daftar, Tambah, Edit, Hapus)
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{id}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Fitur Komentar (Tambah & Hapus Komentar Sendiri)
    Route::post('/reviews/{id}/comments', [ReviewController::class, 'storeComment'])->name('comments.store');
    Route::delete('/comments/{id}', [ReviewController::class, 'destroyComment'])->name('comments.destroy');
});


// ==========================================
// 3. ADMIN ROUTES (Sidebar Admin)
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->as('admin.')->group(function () {
    
    // Dashboard Overview
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Kelola Member & Fitur Blokir Akun
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::patch('/users/{id}/toggle-block', [UserController::class, 'toggleBlock'])->name('users.toggle-block');

    // Kelola Daftar Buku (CRUD: List, Tambah, Edit, Hapus)
    Route::resource('books', BookController::class)->except(['index', 'show']);
    Route::get('/books-manage', [BookController::class, 'manage'])->name('books.manage');
    Route::put('/books/{id}', [BookController::class, 'update'])->name('books.update');

    // Filter Kata Kasar (Sensitivitas Teks)
    Route::get('/filter-words', [BadWordController::class, 'index'])->name('words.index');
    Route::post('/filter-words', [BadWordController::class, 'store'])->name('words.store');
    Route::delete('/filter-words/{id}', [BadWordController::class, 'destroy'])->name('words.destroy');
});
