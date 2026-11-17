<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Tests\TestCase;

class AdminPanelRevampTest extends TestCase
{
    protected $admin;
    protected $member;

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

        $this->member = User::where('role', 'member')->first();
        if (!$this->member) {
            $this->member = User::create([
                'name' => 'Member Test Admin',
                'email' => 'member_test_admin@rebook.com',
                'password' => bcrypt('password123'),
                'role' => 'member',
                'status' => 'Aktif',
            ]);
        }
    }

    public function test_admin_dashboard_displays_enhanced_metrics_and_popular_books()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Overview Dashboard Admin');
        $response->assertSee('Total Katalog Buku');
        $response->assertSee('Member Komunitas');
        $response->assertSee('Interaksi');
        $response->assertSee('Top 5 Buku Terpopuler di Komunitas');
        $response->assertSee('Ulasan Terbaru dari Komunitas');
    }

    public function test_admin_users_search_and_status_filter()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.users.index', [
            'search' => mb_substr($this->member->name, 0, 4),
            'status' => 'all',
        ]));

        $response->assertStatus(200);
        $response->assertSeeText($this->member->name);
        $response->assertSee('Aksi Admin');
        $response->assertSee('name="search"', false);
        $response->assertSee('name="status"', false);
    }

    public function test_admin_books_manage_search_and_genre_filter()
    {
        $book = Book::first();
        $this->assertNotNull($book);

        $response = $this->actingAs($this->admin)->get(route('admin.books.manage', [
            'search' => mb_substr($book->title, 0, 4),
            'genre' => 'all',
        ]));

        $response->assertStatus(200);
        $response->assertSeeText($book->title);
        $response->assertSee('Tambah Buku Baru');
        $response->assertSee('name="genre"', false);
    }

    public function test_member_cannot_access_admin_dashboard()
    {
        $response = $this->actingAs($this->member)->get(route('admin.dashboard'));
        $response->assertStatus(403);
    }

    public function test_admin_books_manage_sorting_options()
    {
        foreach (['title_asc', 'title_desc', 'rating_desc', 'rating_asc', 'popular', 'reviews', 'newest'] as $sort) {
            $response = $this->actingAs($this->admin)->get(route('admin.books.manage', ['sort' => $sort]));
            $response->assertStatus(200);
            $response->assertSee('name="sort"', false);
        }
    }

    public function test_admin_users_sorting_options()
    {
        foreach (['newest', 'oldest', 'name_asc', 'name_desc', 'reviews', 'shelves'] as $sort) {
            $response = $this->actingAs($this->admin)->get(route('admin.users.index', ['sort' => $sort]));
            $response->assertStatus(200);
            $response->assertSee('name="sort"', false);
        }
    }

    public function test_admin_reviews_moderation_sorting_options()
    {
        foreach (['newest', 'oldest', 'rating_desc', 'rating_asc', 'likes'] as $sort) {
            $response = $this->actingAs($this->admin)->get(route('admin.reviews.index', ['sort' => $sort]));
            $response->assertStatus(200);
            $response->assertSee('name="sort"', false);
        }
    }

    public function test_admin_tables_have_sortable_column_headers()
    {
        // Books table
        $resBooks = $this->actingAs($this->admin)->get(route('admin.books.manage'));
        $resBooks->assertStatus(200);
        $resBooks->assertSee('sort=title_desc', false);
        $resBooks->assertSee('fa-sort', false);

        // Users table
        $resUsers = $this->actingAs($this->admin)->get(route('admin.users.index'));
        $resUsers->assertStatus(200);
        $resUsers->assertSee('sort=name_asc', false);
        $resUsers->assertSee('fa-sort', false);

        // Reviews table
        $resReviews = $this->actingAs($this->admin)->get(route('admin.reviews.index'));
        $resReviews->assertStatus(200);
        $resReviews->assertSee('sort=rating_desc', false);
        $resReviews->assertSee('fa-sort', false);
    }
}
