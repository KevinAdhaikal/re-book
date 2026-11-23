<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReadingShelf extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'status',
    ];

    public const STATUS_WANT_TO_READ = 'want_to_read';

    public const STATUS_CURRENTLY_READING = 'currently_reading';

    public const STATUS_READ = 'read';

    public static function statusLabels(): array
    {
        return [
            self::STATUS_WANT_TO_READ => 'Ingin Dibaca',
            self::STATUS_CURRENTLY_READING => 'Sedang Dibaca',
            self::STATUS_READ => 'Selesai Dibaca',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statusLabels()[$this->status] ?? ucfirst(str_replace('_', ' ', $this->status));
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
