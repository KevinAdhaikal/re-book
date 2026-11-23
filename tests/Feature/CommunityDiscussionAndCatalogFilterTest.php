<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Comment;
use App\Models\Review;
use App\Models\User;
use Tests\TestCase;

class CommunityDiscussionAndCatalogFilterTest extends TestCase
{
    protected $member1;

    protected $member2;

    protected $book;

    protected $review;

    protected function setUp(): void
    {
        parent::setUp();

        $this->member1 = User::firstOrCreate(
            ['email' => 'discussion_user1@rebook.com'],
            [
                'name' => 'Komentator Satu',
                'password' => bcrypt('password123'),
                'role' => 'member',
                'status' => 'Aktif',
            ]
        );

        $this->member2 = User::firstOrCreate(
            ['email' => 'discussion_user2@rebook.com'],
            [
                'name' => 'Komentator Dua',
                'password' => bcrypt('password123'),
                'role' => 'member',
                'status' => 'Aktif',
            ]
        );

        $this->book = Book::firstOrCreate(
            ['title' => 'Bumi Cinta'],
            [
                'author' => 'Habiburrahman El Shirazy',
                'genre' => 'Novel',
                'description' => 'Kisah petualangan di Rusia.',
            ]
        );

        // Buat atau bersihkan ulasan untuk pengujian
        Comment::where('user_id', $this->member1->id)->orWhere('user_id', $this->member2->id)->delete();

        $this->review = Review::firstOrCreate(
            ['user_id' => $this->member1->id, 'book_id' => $this->book->id],
            ['rating' => 4.5, 'content' => 'Ulasan buku Bumi Cinta yang mendalam.']
        );
    }

    /**
     * Test: Katalog beranda via AJAX mengembalikan response JSON dengan html grid dan total buku.
     */
    public function test_catalog_index_ajax_returns_rendered_html_and_metadata(): void
    {
        $response = $this->getJson('/?ajax=1');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'html',
            'total',
            'has_pages',
        ]);
        $response->assertJson([
            'success' => true,
        ]);
        $this->assertStringContainsString('book-catalog-grid', $response->json('html'));
    }

    /**
     * Test: Filter genre katalog via AJAX mengembalikan hasil yang terfilter.
     */
    public function test_catalog_filter_genre_ajax_works(): void
    {
        $response = $this->getJson('/?filter_type=genre&search=Novel&ajax=1');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
        $this->assertStringContainsString('Bumi Cinta', $response->json('html'));
    }

    /**
     * Test: Sortir katalog via AJAX mengembalikan data sukses.
     */
    public function test_catalog_sort_ajax_works(): void
    {
        $response = $this->getJson('/?sort=rating&ajax=1');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
    }

    /**
     * Test: Tambah komentar balasan via AJAX mengembalikan response JSON lengkap.
     */
    public function test_member_can_store_comment_via_ajax(): void
    {
        $response = $this->actingAs($this->member2)
            ->postJson(route('member.comments.store', $this->review->id), [
                'comment' => 'Komentar balasan yang sangat inspiratif!',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Komentar berhasil dikirim!',
            'comment' => [
                'comment' => 'Komentar balasan yang sangat inspiratif!',
                'user_id' => $this->member2->id,
                'user_name' => $this->member2->name,
            ],
        ]);

        $this->assertDatabaseHas('comments', [
            'user_id' => $this->member2->id,
            'review_id' => $this->review->id,
            'comment' => 'Komentar balasan yang sangat inspiratif!',
        ]);
    }

    /**
     * Test: Hapus komentar sendiri via AJAX berhasil dan mengembalikan JSON.
     */
    public function test_member_can_delete_own_comment_via_ajax(): void
    {
        $comment = Comment::create([
            'user_id' => $this->member2->id,
            'review_id' => $this->review->id,
            'comment' => 'Komentar sementara untuk dihapus.',
        ]);

        $response = $this->actingAs($this->member2)
            ->deleteJson(route('member.comments.destroy', $comment->id));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Komentar berhasil dihapus!',
        ]);

        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id,
        ]);
    }

    /**
     * Test: Member tidak dapat menghapus komentar milik orang lain via AJAX (403).
     */
    public function test_member_cannot_delete_other_member_comment_via_ajax(): void
    {
        $comment = Comment::create([
            'user_id' => $this->member1->id,
            'review_id' => $this->review->id,
            'comment' => 'Komentar milik member satu.',
        ]);

        $response = $this->actingAs($this->member2)
            ->deleteJson(route('member.comments.destroy', $comment->id));

        $response->assertStatus(403);
        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
        ]);

        $comment->delete();
    }
}
