<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'avatar',
        'bio',
        'favorite_genres',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'favorite_genres' => 'array',
        ];
    }

    // ==========================================
    // TAMBAHKAN DUA METHOD RELASI DI BAWAH INI:
    // ==========================================

    // Relasi ke 4 Buku Favorit Member
    public function favoriteBooks()
    {
        return $this->belongsToMany(Book::class, 'favorite_books')->withTimestamps();
    }

    // Relasi ke Ulasan yang ditulis oleh Member ini
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Relasi ke Buku yang Disukai oleh Member ini
    public function likedBooks()
    {
        return $this->belongsToMany(Book::class, 'book_likes')->withTimestamps();
    }

    // Relasi ke Komentar yang ditulis oleh Member ini
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function hasLiked($bookId): bool
    {
        return $this->likedBooks()->where('book_id', $bookId)->exists();
    }

    public function hasFavorited($bookId): bool
    {
        return $this->favoriteBooks()->where('book_id', $bookId)->exists();
    }

    public function avatarUrl(): ?string
    {
        if (!$this->avatar) {
            return null;
        }
        if (str_starts_with($this->avatar, 'http://') || str_starts_with($this->avatar, 'https://')) {
            return $this->avatar;
        }
        return asset('storage/' . $this->avatar);
    }
}

