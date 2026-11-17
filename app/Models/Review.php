<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'rating',
        'content',
    ];

    // Relasi ke User (Member yang menulis ulasan)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Buku
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // Relasi ke Komentar di ulasan ini
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}