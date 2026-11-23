<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Tests\TestCase;

class SearchIntegrationTest extends TestCase
{
    public function test_landing_search_returns_both_books_and_members()
    {
        $book = Book::first();
        $this->assertNotNull($book);

        // Search with a query matching book
        $response = $this->get('/?search='.urlencode(mb_substr($book->title, 0, 4)));
        $response->assertStatus(200);
        $response->assertSee($book->title);
    }

    public function test_landing_search_with_users_tab()
    {
        $member = User::where('role', 'member')->first();
        $this->assertNotNull($member);

        // Search member name with tab=users
        $response = $this->get('/?search='.urlencode(mb_substr($member->name, 0, 4)).'&tab=users');
        $response->assertStatus(200);
        $response->assertSee($member->name);
    }

    public function test_search_hub_renders_books_and_filters()
    {
        $book = Book::first();
        $this->assertNotNull($book);

        $response = $this->get(route('search', ['q' => mb_substr($book->title, 0, 4)]));
        $response->assertStatus(200);
        $response->assertSee('Hasil Pencarian untuk');
        $response->assertSeeText($book->title);
        $response->assertSee('name="genre"', false);
        $response->assertSee('name="min_rating"', false);
        $response->assertSee('name="sort"', false);
    }

    public function test_search_hub_users_tab()
    {
        $member = User::where('role', 'member')->first();
        $this->assertNotNull($member);

        $response = $this->get(route('search', [
            'q' => mb_substr($member->name, 0, 4),
            'tab' => 'users',
        ]));

        $response->assertStatus(200);
        $response->assertSeeText($member->name);
        $response->assertSee('Member Komunitas');
    }

    public function test_search_hub_filter_genre_and_rating()
    {
        $book = Book::whereNotNull('genre')->first();
        $this->assertNotNull($book);

        $response = $this->get(route('search', [
            'genre' => $book->genre,
            'min_rating' => 0,
            'sort' => 'popular',
        ]));

        $response->assertStatus(200);
        $response->assertSee($book->genre);
    }

    public function test_search_hub_view_toggle_list_and_grid()
    {
        $responseList = $this->get(route('search', ['view' => 'list']));
        $responseList->assertStatus(200);
        $responseList->assertSee('search-book-row', false);

        $responseGrid = $this->get(route('search', ['view' => 'grid']));
        $responseGrid->assertStatus(200);
        $responseGrid->assertSee('search-book-grid', false);
    }

    public function test_search_hub_empty_state_shows_fallback_recommendations()
    {
        $response = $this->get(route('search', ['q' => 'NonExistentTitleQueryX999XYZ']));
        $response->assertStatus(200);
        $response->assertSee('Tidak ada hasil untuk');
        $response->assertSee('Tips Pencarian:');
        $response->assertSee('Rekomendasi Populer di Re:Book');
    }
}
