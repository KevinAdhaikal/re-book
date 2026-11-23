<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Comment;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BookLikeAndFavoriteTest extends TestCase
{
    protected $member1;

    protected $member2;

    protected $book1;

    protected $book2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->member1 = User::firstOrCreate(
            ['email' => 'testmember1@rebook.com'],
            [
                'name' => 'Member Satu',
                'password' => bcrypt('password123'),
                'role' => 'member',
                'status' => 'Aktif',
            ]
        );

        $this->member2 = User::firstOrCreate(
            ['email' => 'testmember2@rebook.com'],
            [
                'name' => 'Member Dua',
                'password' => bcrypt('password123'),
                'role' => 'member',
                'status' => 'Aktif',
            ]
        );

        $this->book1 = Book::firstOrCreate(
            ['title' => 'Bumi Manusia'],
            [
                'author' => 'Pramoedya Ananta Toer',
                'genre' => 'Sejarah',
                'description' => 'Kisah sejarah Indonesia',
            ]
        );

        $this->book2 = Book::firstOrCreate(
            ['title' => 'Atomic Habits'],
            [
                'author' => 'James Clear',
                'genre' => 'Self-Dev',
                'description' => 'Buku pengembangan diri',
            ]
        );

        // Bersihkan likes, favorites, reviews & comments test members
        DB::table('book_likes')->whereIn('user_id', [$this->member1->id, $this->member2->id])->delete();
        DB::table('favorite_books')->whereIn('user_id', [$this->member1->id, $this->member2->id])->delete();
        Review::whereIn('user_id', [$this->member1->id, $this->member2->id])->delete();
        Comment::whereIn('user_id', [$this->member1->id, $this->member2->id])->delete();
    }

    /**
     * 1. Test Dashboard menampilkan seluruh katalog buku yang tersedia (10 buku).
     */
    public function test_dashboard_displays_all_books()
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        // Verifikasi buku-buku yang ada di database tampil di beranda
        $books = Book::all();
        $this->assertGreaterThanOrEqual(10, $books->count());

        foreach ($books as $b) {
            $response->assertSee($b->title);
        }
    }

    /**
     * 2. Test Halaman /member/likes tidak memiliki search bar atau dropdown ganda dalam konten.
     */
    public function test_likes_page_has_no_duplicate_search_in_content()
    {
        $response = $this->actingAs($this->member1)->get(route('member.likes.index'));
        $response->assertStatus(200);

        // Konten halaman tidak boleh memiliki form action member.likes.index kedua
        // Hanya navbar yang memiliki dropdown kategori
        $html = $response->getContent();
        // Pastikan hanya ada 1 tag <select name="filter_type" di seluruh halaman (yaitu di Navbar)
        $this->assertEquals(1, substr_count($html, 'name="filter_type"'));
    }

    /**
     * 3. Test Like dan Unlike via AJAX.
     */
    public function test_member_can_like_and_unlike_book_ajax()
    {
        // Like
        $response = $this->actingAs($this->member1)
            ->postJson(route('member.likes.toggle', $this->book1->id));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'liked' => true,
            'book_id' => $this->book1->id,
            'total_liked' => 1,
        ]);

        $this->assertDatabaseHas('book_likes', [
            'user_id' => $this->member1->id,
            'book_id' => $this->book1->id,
        ]);

        // Unlike
        $response2 = $this->actingAs($this->member1)
            ->postJson(route('member.likes.toggle', $this->book1->id));

        $response2->assertStatus(200);
        $response2->assertJson([
            'success' => true,
            'liked' => false,
            'book_id' => $this->book1->id,
            'total_liked' => 0,
        ]);

        $this->assertDatabaseMissing('book_likes', [
            'user_id' => $this->member1->id,
            'book_id' => $this->book1->id,
        ]);
    }

    /**
     * 4. Test CRUD Ulasan Member secara lengkap.
     */
    public function test_member_review_crud_and_rating_integration()
    {
        // A. CREATE REVIEW
        $createResponse = $this->actingAs($this->member1)->post(route('member.reviews.store'), [
            'book_id' => $this->book1->id,
            'rating' => 5,
            'content' => 'Buku yang sangat luar biasa dan inspiratif!',
        ]);
        $createResponse->assertSessionHas('success');

        $review = Review::where('user_id', $this->member1->id)
            ->where('book_id', $this->book1->id)
            ->first();
        $this->assertNotNull($review);
        $this->assertEquals(5, $review->rating);

        // Cegah review duplikat untuk buku yang sama oleh member yang sama
        $duplicateResponse = $this->actingAs($this->member1)->post(route('member.reviews.store'), [
            'book_id' => $this->book1->id,
            'rating' => 4,
            'content' => 'Mencoba membuat ulasan kedua...',
        ]);
        $duplicateResponse->assertSessionHas('error');

        // B. READ REVIEW DI HALAMAN ULASAN SAYA
        $myReviewsPage = $this->actingAs($this->member1)->get(route('member.reviews.index'));
        $myReviewsPage->assertStatus(200);
        $myReviewsPage->assertSee('Bumi Manusia');
        $myReviewsPage->assertSee('Buku yang sangat luar biasa dan inspiratif!');

        // Member lain tidak melihat ulasan member1 di halaman "Ulasan Saya" miliknya
        $member2ReviewsPage = $this->actingAs($this->member2)->get(route('member.reviews.index'));
        $member2ReviewsPage->assertStatus(200);
        $member2ReviewsPage->assertDontSee('Buku yang sangat luar biasa dan inspiratif!');

        // C. UPDATE REVIEW
        $updateResponse = $this->actingAs($this->member1)->put(route('member.reviews.update', $review->id), [
            'rating' => 4,
            'content' => 'Konten ulasan yang sudah diperbarui dengan baik.',
        ]);
        $updateResponse->assertSessionHas('success');

        $review->refresh();
        $this->assertEquals(4, $review->rating);
        $this->assertEquals('Konten ulasan yang sudah diperbarui dengan baik.', $review->content);

        // Member lain tidak boleh bisa mengupdate review milik member1 (403)
        $unauthorizedEdit = $this->actingAs($this->member2)->put(route('member.reviews.update', $review->id), [
            'rating' => 1,
            'content' => 'Mencoba merusak ulasan orang lain.',
        ]);
        $unauthorizedEdit->assertStatus(403);

        // D. INTEGRASI RATING: Cek rating pada detail buku
        $bookDetailPage = $this->get(route('books.show', $this->book1->id));
        $bookDetailPage->assertStatus(200);
        $bookDetailPage->assertSee('4.0'); // Rating telah diupdate menjadi 4.0

        // E. DELETE REVIEW
        // Member lain tidak boleh bisa menghapus review member1 (403)
        $unauthorizedDelete = $this->actingAs($this->member2)->delete(route('member.reviews.destroy', $review->id));
        $unauthorizedDelete->assertStatus(403);

        // Member pemilik menghapus review miliknya
        $deleteResponse = $this->actingAs($this->member1)->delete(route('member.reviews.destroy', $review->id));
        $deleteResponse->assertSessionHas('success');
        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
    }

    /**
     * 5. Test Fitur Komentar Antar-Member & Penghapusan Komentar Sendiri.
     */
    public function test_social_comments_feature()
    {
        // Buat ulasan oleh member1
        $review = Review::create([
            'user_id' => $this->member1->id,
            'book_id' => $this->book1->id,
            'rating' => 5,
            'content' => 'Ulasan awal member 1 untuk dikomentari.',
        ]);

        // Member2 memberikan komentar pada ulasan member1
        $commentResponse = $this->actingAs($this->member2)->post(route('member.comments.store', $review->id), [
            'comment' => 'Setuju sekali dengan ulasan Anda!',
        ]);
        $commentResponse->assertSessionHas('success');

        $comment = Comment::where('user_id', $this->member2->id)
            ->where('review_id', $review->id)
            ->first();
        $this->assertNotNull($comment);

        // Member1 tidak boleh menghapus komentar milik member2 (403)
        $unauthorizedCommentDelete = $this->actingAs($this->member1)->delete(route('member.comments.destroy', $comment->id));
        $unauthorizedCommentDelete->assertStatus(403);

        // Member2 dapat menghapus komentar miliknya sendiri
        $authorizedCommentDelete = $this->actingAs($this->member2)->delete(route('member.comments.destroy', $comment->id));
        $authorizedCommentDelete->assertSessionHas('success');
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);

        $review->delete();
    }

    /**
     * 6. Test Showcase Buku Favorit di Profil Member.
     */
    public function test_profile_displays_favorite_books_and_view_all_link()
    {
        // Tambahkan ke favorit member1
        $this->actingAs($this->member1)->post(route('member.fav.store'), ['book_id' => $this->book1->id]);

        $profileResponse = $this->actingAs($this->member1)->get(route('member.profile'));
        $profileResponse->assertStatus(200);
        $profileResponse->assertSee($this->book1->title);
        $profileResponse->assertSee(route('member.fav'));
        $profileResponse->assertSee('Lihat Semua');
    }
}
