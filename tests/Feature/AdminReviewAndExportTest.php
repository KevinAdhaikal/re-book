<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use Tests\TestCase;

class AdminReviewAndExportTest extends TestCase
{
    protected $admin;

    protected $member;

    protected $book;

    protected $review;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::firstOrCreate(
            ['email' => 'admin_test@rebook.com'],
            [
                'name' => 'Admin Utama',
                'password' => bcrypt('password123'),
                'role' => 'admin',
                'status' => 'Aktif',
            ]
        );

        $this->member = User::firstOrCreate(
            ['email' => 'member_export_test@rebook.com'],
            [
                'name' => 'Member Test',
                'password' => bcrypt('password123'),
                'role' => 'member',
                'status' => 'Aktif',
            ]
        );

        $this->book = Book::firstOrCreate(
            ['title' => 'Sapiens'],
            [
                'author' => 'Yuval Noah Harari',
                'genre' => 'Sejarah',
                'description' => 'Riwayat singkat umat manusia.',
            ]
        );

        $this->review = Review::firstOrCreate(
            [
                'user_id' => $this->member->id,
                'book_id' => $this->book->id,
            ],
            [
                'rating' => 5,
                'content' => 'Buku sejarah yang membuka cakrawala berpikir manusia!',
            ]
        );
    }

    /**
     * Test: Admin dapat mengakses halaman moderasi ulasan global.
     */
    public function test_admin_can_view_review_moderation_page()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.reviews.index', ['book_id' => $this->book->id]));
        $response->assertStatus(200);
        $response->assertSee('Moderasi Ulasan Global');
        $response->assertSee($this->review->content);
    }

    /**
     * Test: Member non-admin tidak dapat mengakses halaman moderasi ulasan (403).
     */
    public function test_member_cannot_access_review_moderation_page()
    {
        $response = $this->actingAs($this->member)->get(route('admin.reviews.index'));
        $response->assertStatus(403);
    }

    /**
     * Test: Admin dapat menghapus ulasan member dari halaman moderasi.
     */
    public function test_admin_can_delete_review()
    {
        $reviewToDelete = Review::create([
            'user_id' => $this->member->id,
            'book_id' => $this->book->id,
            'rating' => 1,
            'content' => 'Ulasan spam yang akan dihapus admin.',
        ]);

        $deleteResponse = $this->actingAs($this->admin)->delete(route('admin.reviews.destroy', $reviewToDelete->id));
        $deleteResponse->assertRedirect();
        $this->assertDatabaseMissing('reviews', ['id' => $reviewToDelete->id]);
    }

    /**
     * Test: Admin dapat mengakses halaman menu export data.
     */
    public function test_admin_can_view_exports_page()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.exports.index'));
        $response->assertStatus(200);
        $response->assertSee('Export Data Re:Book');
        $response->assertSee('Unduh CSV Buku');
        $response->assertSee('Unduh CSV Member');
        $response->assertSee('Unduh CSV Ulasan');
    }

    /**
     * Test: Admin dapat mengunduh CSV katalog buku.
     */
    public function test_admin_can_download_books_csv()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.exports.books'));
        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('rebook_katalog_buku_', $response->headers->get('Content-Disposition'));
    }

    /**
     * Test: Admin dapat mengunduh CSV data member.
     */
    public function test_admin_can_download_users_csv()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.exports.users'));
        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('rebook_daftar_member_', $response->headers->get('Content-Disposition'));
    }

    /**
     * Test: Admin dapat mengunduh CSV seluruh ulasan.
     */
    public function test_admin_can_download_reviews_csv()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.exports.reviews'));
        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('rebook_seluruh_ulasan_', $response->headers->get('Content-Disposition'));
    }
}
