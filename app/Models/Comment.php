<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'review_id',
        'comment',
    ];

    // Relasi ke User (Member yang berkomentar)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Ulasan yang dikomentari
    public function review()
    {
        return $this->belongsTo(Review::class);
    }
}
