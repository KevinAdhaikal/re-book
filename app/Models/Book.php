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

    // Relasi: Satu buku memiliki banyak Ulasan (Reviews, urutan terbaru di atas)
    public function reviews()
    {
        return $this->hasMany(Review::class)->latest('id');
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

    // Relasi ke Rak Bacaan Member
    public function readingShelves()
    {
        return $this->hasMany(ReadingShelf::class);
    }

    public function shelvedByUsers()
    {
        return $this->belongsToMany(User::class, 'reading_shelves')->withPivot('status')->withTimestamps();
    }
}
