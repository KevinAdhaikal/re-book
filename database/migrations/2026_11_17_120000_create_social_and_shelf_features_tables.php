<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Status Rak Bacaan (Want to Read, Currently Reading, Read)
        if (!Schema::hasTable('reading_shelves')) {
            Schema::create('reading_shelves', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('book_id')->constrained()->onDelete('cascade');
                $table->string('status', 30)->default('want_to_read'); // want_to_read, currently_reading, read
                $table->timestamps();

                $table->unique(['user_id', 'book_id']);
            });
        }

        // 2. Like / Reaksi pada Ulasan Member
        if (!Schema::hasTable('review_likes')) {
            Schema::create('review_likes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('review_id')->constrained()->onDelete('cascade');
                $table->timestamps();

                $table->unique(['user_id', 'review_id']);
            });
        }

        // 3. Sistem Follow Antar Member
        if (!Schema::hasTable('user_follows')) {
            Schema::create('user_follows', function (Blueprint $table) {
                $table->id();
                $table->foreignId('follower_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('following_id')->constrained('users')->onDelete('cascade');
                $table->timestamps();

                $table->unique(['follower_id', 'following_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_follows');
        Schema::dropIfExists('review_likes');
        Schema::dropIfExists('reading_shelves');
    }
};
