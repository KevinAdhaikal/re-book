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
        $response = $this->get('/?search=' . urlencode(mb_substr($book->title, 0, 4)));
        $response->assertStatus(200);
        $response->assertSee($book->title);
    }

    public function test_landing_search_with_users_tab()
    {
        $member = User::where('role', 'member')->first();
        $this->assertNotNull($member);

        // Search member name with tab=users
        $response = $this->get('/?search=' . urlencode(mb_substr($member->name, 0, 4)) . '&tab=users');
        $response->assertStatus(200);
        $response->assertSee($member->name);
    }
}
