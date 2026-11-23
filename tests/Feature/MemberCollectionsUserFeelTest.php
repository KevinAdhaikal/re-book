<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\ReadingShelf;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MemberCollectionsUserFeelTest extends TestCase
{
    protected $member;

    protected $books;

    protected function setUp(): void
    {
        parent::setUp();

        $this->member = User::firstOrCreate(
            ['email' => 'userfeel_member@rebook.com'],
            [
                'name' => 'Kolektor Buku',
                'password' => bcrypt('password123'),
                'role' => 'member',
                'status' => 'Aktif',
            ]
        );

        // Bersihkan state collections untuk member ini
        ReadingShelf::where('user_id', $this->member->id)->delete();
        DB::table('favorite_books')->where('user_id', $this->member->id)->delete();
        DB::table('book_likes')->where('user_id', $this->member->id)->delete();

        // Siapkan beberapa buku pengujian
        $this->books = collect([
            Book::firstOrCreate(
                ['title' => 'User Feel Book 1'],
                ['author' => 'Author A', 'genre' => 'Fiction', 'description' => 'Desc 1']
            ),
            Book::firstOrCreate(
                ['title' => 'User Feel Book 2'],
                ['author' => 'Author B', 'genre' => 'Non-Fiction', 'description' => 'Desc 2']
            ),
            Book::firstOrCreate(
                ['title' => 'User Feel Book 3'],
                ['author' => 'Author C', 'genre' => 'Self-Dev', 'description' => 'Desc 3']
            ),
            Book::firstOrCreate(
                ['title' => 'User Feel Book 4'],
                ['author' => 'Author D', 'genre' => 'Tech', 'description' => 'Desc 4']
            ),
            Book::firstOrCreate(
                ['title' => 'User Feel Book 5'],
                ['author' => 'Author E', 'genre' => 'Design', 'description' => 'Desc 5']
            ),
        ]);
    }

    /**
     * Test: Update status rak bacaan via AJAX mengembalikan data sukses beserta hitungan counts yang sinkron.
     */
    public function test_shelf_update_via_ajax_returns_synced_counts(): void
    {
        $book = $this->books[0];

        $response = $this->actingAs($this->member)
            ->postJson(route('member.shelf.update', $book->id), [
                'status' => ReadingShelf::STATUS_WANT_TO_READ,
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'status' => ReadingShelf::STATUS_WANT_TO_READ,
            'counts' => [
                'all' => 1,
                ReadingShelf::STATUS_WANT_TO_READ => 1,
                ReadingShelf::STATUS_CURRENTLY_READING => 0,
                ReadingShelf::STATUS_READ => 0,
            ],
        ]);

        $this->assertEquals(ReadingShelf::STATUS_WANT_TO_READ, $this->member->shelfStatusFor($book->id));

        // Ubah status ke currently_reading
        $response2 = $this->actingAs($this->member)
            ->postJson(route('member.shelf.update', $book->id), [
                'status' => ReadingShelf::STATUS_CURRENTLY_READING,
            ]);

        $response2->assertStatus(200);
        $response2->assertJson([
            'success' => true,
            'status' => ReadingShelf::STATUS_CURRENTLY_READING,
            'counts' => [
                'all' => 1,
                ReadingShelf::STATUS_WANT_TO_READ => 0,
                ReadingShelf::STATUS_CURRENTLY_READING => 1,
                ReadingShelf::STATUS_READ => 0,
            ],
        ]);
    }

    /**
     * Test: Hapus buku dari rak bacaan via AJAX mengembalikan data sukses beserta hitungan counts yang berkurang.
     */
    public function test_shelf_destroy_via_ajax_returns_decremented_counts(): void
    {
        $book1 = $this->books[0];
        $book2 = $this->books[1];

        ReadingShelf::create(['user_id' => $this->member->id, 'book_id' => $book1->id, 'status' => ReadingShelf::STATUS_READ]);
        ReadingShelf::create(['user_id' => $this->member->id, 'book_id' => $book2->id, 'status' => ReadingShelf::STATUS_READ]);

        $response = $this->actingAs($this->member)
            ->deleteJson(route('member.shelf.destroy', $book1->id));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'counts' => [
                'all' => 1,
                ReadingShelf::STATUS_WANT_TO_READ => 0,
                ReadingShelf::STATUS_CURRENTLY_READING => 0,
                ReadingShelf::STATUS_READ => 1,
            ],
        ]);

        $this->assertNull($this->member->shelfStatusFor($book1->id));
        $this->assertEquals(ReadingShelf::STATUS_READ, $this->member->shelfStatusFor($book2->id));
    }

    /**
     * Test: Tambah buku favorit via AJAX mengembalikan response JSON lengkap dengan detail buku dan count baru.
     */
    public function test_favorite_store_via_ajax_returns_book_details_and_count(): void
    {
        $book = $this->books[0];

        $response = $this->actingAs($this->member)
            ->postJson(route('member.fav.store'), [
                'book_id' => $book->id,
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'count' => 1,
            'book' => [
                'id' => $book->id,
                'title' => $book->title,
                'author' => $book->author,
            ],
        ]);

        $this->assertTrue($this->member->favoriteBooks()->where('book_id', $book->id)->exists());
    }

    /**
     * Test: Menambahkan buku favorit duplikat via AJAX mengembalikan status 422 dan pesan error.
     */
    public function test_favorite_duplicate_via_ajax_rejected_with_422(): void
    {
        $book = $this->books[0];
        $this->member->favoriteBooks()->attach($book->id);

        $response = $this->actingAs($this->member)
            ->postJson(route('member.fav.store'), [
                'book_id' => $book->id,
            ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Buku tersebut sudah ada di daftar Buku Favorit Anda.',
        ]);
    }

    /**
     * Test: Menambahkan buku favorit ke-5 ditolak dengan 422 karena batas maksimal 4 buku.
     */
    public function test_favorite_max_limit_via_ajax_rejected_with_422(): void
    {
        // Pasang 4 buku favorit terlebih dahulu
        for ($i = 0; $i < 4; $i++) {
            $this->member->favoriteBooks()->attach($this->books[$i]->id);
        }

        $this->assertEquals(4, $this->member->favoriteBooks()->count());

        // Coba tambahkan buku ke-5
        $response = $this->actingAs($this->member)
            ->postJson(route('member.fav.store'), [
                'book_id' => $this->books[4]->id,
            ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
    }

    /**
     * Test: Hapus buku favorit via AJAX mengembalikan count baru dan data buku yang dihapus.
     */
    public function test_favorite_destroy_via_ajax_returns_updated_count(): void
    {
        $book = $this->books[0];
        $this->member->favoriteBooks()->attach($book->id);

        $response = $this->actingAs($this->member)
            ->deleteJson(route('member.fav.destroy', $book->id));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'count' => 0,
            'book' => [
                'id' => $book->id,
                'title' => $book->title,
            ],
        ]);

        $this->assertFalse($this->member->favoriteBooks()->where('book_id', $book->id)->exists());
    }
}
