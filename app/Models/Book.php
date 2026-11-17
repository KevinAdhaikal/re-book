<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'genre',
        'description',
        'cover',
    ];

    // Relasi: Satu buku memiliki banyak Ulasan (Reviews)
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Relasi: User Member yang menyukai buku ini (Like system)
    public function likedByUsers()
    {
        return $this->belongsToMany(User::class, 'book_likes')->withTimestamps();
    }

    // Relasi: Member yang menjadikan buku ini sebagai 4 Buku Favorit di profil
    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorite_books')->withTimestamps();
    }
}