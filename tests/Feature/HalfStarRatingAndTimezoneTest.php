<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use Tests\TestCase;

class HalfStarRatingAndTimezoneTest extends TestCase
{
    protected $member;

    protected $book;

    protected function setUp(): void
    {
        parent::setUp();

        $this->member = User::firstOrCreate(
            ['email' => 'halfstar_member@rebook.com'],
            [
                'name' => 'Member Half Star',
                'password' => bcrypt('password123'),
                'role' => 'member',
                'status' => 'Aktif',
            ]
        );

        $this->book = Book::firstOrCreate(
            ['title' => 'Buku Uji Bintang Pecahan'],
            [
                'author' => 'Penulis Uji',
                'genre' => 'Novel',
                'description' => 'Deskripsi buku uji bintang.',
            ]
        );

        Review::where('user_id', $this->member->id)->delete();
    }

    public function test_application_timezone_is_asia_jakarta()
    {
        $this->assertEquals('Asia/Jakarta', config('app.timezone'));
    }

    public function test_member_can_submit_half_star_rating()
    {
        $response = $this->actingAs($this->member)
            ->postJson(route('member.reviews.store'), [
                'book_id' => $this->book->id,
                'rating' => 3.5,
                'content' => 'Buku yang sangat menarik, saya beri rating 3.5 bintang!',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'review' => [
                'rating' => 3.5,
            ],
        ]);

        $this->assertDatabaseHas('reviews', [
            'user_id' => $this->member->id,
            'book_id' => $this->book->id,
            'rating' => 3.5,
        ]);

        $review = Review::where('user_id', $this->member->id)
            ->where('book_id', $this->book->id)
            ->first();

        $this->assertSame(3.5, (float) $review->rating);
    }

    public function test_member_can_update_review_with_half_star_rating()
    {
        $review = Review::create([
            'user_id' => $this->member->id,
            'book_id' => $this->book->id,
            'rating' => 4.0,
            'content' => 'Awalnya 4 bintang, setelah baca ulang mau ubah ke 4.5 bintang.',
        ]);

        $response = $this->actingAs($this->member)
            ->put(route('member.reviews.update', $review->id), [
                'rating' => 4.5,
                'content' => 'Diperbarui menjadi 4.5 bintang karena epilognya sangat menyentuh!',
            ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'rating' => 4.5,
        ]);
    }

    public function test_member_can_update_review_via_ajax_with_json_response()
    {
        $review = Review::create([
            'user_id' => $this->member->id,
            'book_id' => $this->book->id,
            'rating' => 4.0,
            'content' => 'Review awal sebelum diubah lewat AJAX.',
        ]);

        $response = $this->actingAs($this->member)
            ->putJson(route('member.reviews.update', $review->id), [
                'rating' => 4.5,
                'content' => 'Review berhasil diperbarui secara inline via AJAX!',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Ulasan berhasil diperbarui!',
            'review' => [
                'id' => $review->id,
                'rating' => 4.5,
                'content' => 'Review berhasil diperbarui secara inline via AJAX!',
            ],
        ]);

        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'rating' => 4.5,
            'content' => 'Review berhasil diperbarui secara inline via AJAX!',
        ]);
    }

    public function test_member_can_delete_review_via_ajax_with_json_response()
    {
        $review = Review::create([
            'user_id' => $this->member->id,
            'book_id' => $this->book->id,
            'rating' => 3.0,
            'content' => 'Review yang akan dihapus via AJAX.',
        ]);

        $response = $this->actingAs($this->member)
            ->deleteJson(route('member.reviews.destroy', $review->id));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Ulasan berhasil dihapus!',
        ]);

        $this->assertDatabaseMissing('reviews', [
            'id' => $review->id,
        ]);
    }

    public function test_invalid_half_star_increment_is_rejected()
    {
        // 3.7 bukan kelipatan 0.5
        $response = $this->actingAs($this->member)
            ->postJson(route('member.reviews.store'), [
                'book_id' => $this->book->id,
                'rating' => 3.7,
                'content' => 'Rating tidak valid coba-coba.',
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['rating']);
    }

    public function test_rating_below_minimum_or_above_maximum_is_rejected()
    {
        // 0.2 di bawah 0.5
        $resLow = $this->actingAs($this->member)
            ->postJson(route('member.reviews.store'), [
                'book_id' => $this->book->id,
                'rating' => 0.2,
                'content' => 'Rating di bawah minimum.',
            ]);
        $resLow->assertStatus(422);

        // 5.5 di atas 5.0
        $resHigh = $this->actingAs($this->member)
            ->postJson(route('member.reviews.store'), [
                'book_id' => $this->book->id,
                'rating' => 5.5,
                'content' => 'Rating di atas maksimum.',
            ]);
        $resHigh->assertStatus(422);
    }
}
