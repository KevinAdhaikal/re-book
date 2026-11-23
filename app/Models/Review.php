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

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'rating' => 'float',
        ];
    }

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

    // Relasi Member yang menyukai ulasan ini
    public function likedByUsers()
    {
        return $this->belongsToMany(User::class, 'review_likes')->withTimestamps();
    }

    public function isLikedBy(?User $user = null): bool
    {
        if (! $user) {
            return false;
        }

        return $this->likedByUsers()->where('user_id', $user->id)->exists();
    }
}
