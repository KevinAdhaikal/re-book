<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\ReadingShelf;
use App\Models\Review;
use App\Models\User;
use Tests\TestCase;

class SocialAndShelfTest extends TestCase
{
    protected $member1;

    protected $member2;

    protected $book;

    protected $review;

    protected function setUp(): void
    {
        parent::setUp();

        $this->member1 = User::firstOrCreate(
            ['email' => 'shelf_member1@rebook.com'],
            [
                'name' => 'Rizki Pembaca',
                'password' => bcrypt('password123'),
                'role' => 'member',
                'status' => 'Aktif',
            ]
        );

        $this->member2 = User::firstOrCreate(
            ['email' => 'shelf_member2@rebook.com'],
            [
                'name' => 'Fani Penulis',
                'password' => bcrypt('password123'),
                'role' => 'member',
                'status' => 'Aktif',
            ]
        );

        $this->book = Book::firstOrCreate(
            ['title' => 'Atomic Habits'],
            [
                'author' => 'James Clear',
                'genre' => 'Self-Dev',
                'description' => 'Perubahan kecil yang memberikan hasil luar biasa.',
            ]
        );

        $this->review = Review::firstOrCreate(
            [
                'user_id' => $this->member2->id,
                'book_id' => $this->book->id,
            ],
            [
                'rating' => 5,
                'content' => 'Buku yang sangat praktis dan mudah diterapkan!',
            ]
        );
    }

    /**
     * Test: Member dapat membuka halaman rak bacaan.
     */
    public function test_member_can_view_reading_shelf_page()
    {
        $response = $this->actingAs($this->member1)->get(route('member.shelf.index'));
        $response->assertStatus(200);
        $response->assertSee('Rak Bacaan Saya');
        $response->assertSee('Ingin Dibaca');
        $response->assertSee('Sedang Dibaca');
        $response->assertSee('Selesai Dibaca');
    }

    /**
     * Test: Member dapat menambahkan dan memperbarui status buku di rak bacaan.
     */
    public function test_member_can_add_and_update_book_in_reading_shelf()
    {
        // 1. Tambah buku ke "want_to_read"
        $response = $this->actingAs($this->member1)->post(route('member.shelf.update', $this->book->id), [
            'status' => ReadingShelf::STATUS_WANT_TO_READ,
        ]);

        $response->assertRedirect();
        $this->assertEquals(ReadingShelf::STATUS_WANT_TO_READ, $this->member1->shelfStatusFor($this->book->id));

        // 2. Ubah status ke "currently_reading" via AJAX
        $ajaxResponse = $this->actingAs($this->member1)->postJson(route('member.shelf.update', $this->book->id), [
            'status' => ReadingShelf::STATUS_CURRENTLY_READING,
        ]);

        $ajaxResponse->assertStatus(200);
        $ajaxResponse->assertJson([
            'success' => true,
            'status' => ReadingShelf::STATUS_CURRENTLY_READING,
        ]);
        $this->assertEquals(ReadingShelf::STATUS_CURRENTLY_READING, $this->member1->shelfStatusFor($this->book->id));
    }

    /**
     * Test: Member dapat menghapus buku dari rak bacaan.
     */
    public function test_member_can_remove_book_from_reading_shelf()
    {
        $this->actingAs($this->member1)->post(route('member.shelf.update', $this->book->id), [
            'status' => ReadingShelf::STATUS_READ,
        ]);

        $this->assertNotNull($this->member1->shelfStatusFor($this->book->id));

        $deleteResponse = $this->actingAs($this->member1)->delete(route('member.shelf.destroy', $this->book->id));
        $deleteResponse->assertRedirect();

        $this->assertNull($this->member1->shelfStatusFor($this->book->id));
    }

    /**
     * Test: Member dapat menyukai (like) dan batal menyukai ulasan member lain.
     */
    public function test_member_can_toggle_like_on_review()
    {
        // Like ulasan
        $likeResponse = $this->actingAs($this->member1)->postJson(route('member.reviews.like', $this->review->id));
        $likeResponse->assertStatus(200);
        $likeResponse->assertJson([
            'success' => true,
            'liked' => true,
        ]);

        $this->assertTrue($this->member1->hasLikedReview($this->review->id));

        // Unlike ulasan
        $unlikeResponse = $this->actingAs($this->member1)->postJson(route('member.reviews.like', $this->review->id));
        $unlikeResponse->assertStatus(200);
        $unlikeResponse->assertJson([
            'success' => true,
            'liked' => false,
        ]);

        $this->assertFalse($this->member1->hasLikedReview($this->review->id));
    }

    /**
     * Test: Member dapat follow dan unfollow member lain.
     */
    public function test_member_can_follow_and_unfollow_other_member()
    {
        // Follow member2
        $followResponse = $this->actingAs($this->member1)->postJson(route('member.users.follow', $this->member2->id));
        $followResponse->assertStatus(200);
        $followResponse->assertJson([
            'success' => true,
            'following' => true,
        ]);

        $this->assertTrue($this->member1->isFollowing($this->member2->id));

        // Unfollow member2
        $unfollowResponse = $this->actingAs($this->member1)->postJson(route('member.users.follow', $this->member2->id));
        $unfollowResponse->assertStatus(200);
        $unfollowResponse->assertJson([
            'success' => true,
            'following' => false,
        ]);

        $this->assertFalse($this->member1->isFollowing($this->member2->id));
    }

    /**
     * Test: Member tidak dapat mengikuti diri sendiri.
     */
    public function test_member_cannot_follow_self()
    {
        $response = $this->actingAs($this->member1)->postJson(route('member.users.follow', $this->member1->id));
        $response->assertStatus(422);
    }
}
