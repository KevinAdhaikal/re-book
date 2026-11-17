<?php

namespace Tests\Feature;

use App\Models\Book;
use Tests\TestCase;

class QuickSearchTest extends TestCase
{
    public function test_quick_search_returns_empty_for_short_query()
    {
        $response = $this->getJson(route('books.quickSearch', ['search' => 'a']));

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'books' => [],
                     'total' => 0,
                 ]);
    }

    public function test_quick_search_returns_matching_books()
    {
        $book = Book::first();
        $this->assertNotNull($book);

        // Search by first 4 characters of title
        $term = mb_substr($book->title, 0, 4);
        $response = $this->getJson(route('books.quickSearch', ['search' => $term]));

        $response->assertStatus(200)
                 ->assertJson(['success' => true])
                 ->assertJsonStructure([
                     'success',
                     'books' => [
                         '*' => [
                             'id',
                             'title',
                             'author',
                             'genre',
                             'cover_url',
                             'rating',
                             'reviews_count',
                             'likes_count',
                             'url',
                         ]
                     ],
                     'total',
                     'query',
                 ]);

        $this->assertGreaterThan(0, $response->json('total'));
    }

    public function test_quick_search_with_filter_type()
    {
        $book = Book::first();

        // Search by author with filter_type=author
        $authorTerm = mb_substr($book->author, 0, 4);
        $response = $this->getJson(route('books.quickSearch', [
            'search' => $authorTerm,
            'filter_type' => 'author',
        ]));

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertGreaterThan(0, $response->json('total'));
    }

    public function test_quick_search_returns_matching_members()
    {
        $member = \App\Models\User::where('role', 'member')->first();
        $this->assertNotNull($member);

        $term = mb_substr($member->name, 0, 4);
        $response = $this->getJson(route('books.quickSearch', ['search' => $term]));

        $response->assertStatus(200)
                 ->assertJson(['success' => true])
                 ->assertJsonStructure([
                     'success',
                     'books',
                     'users' => [
                         '*' => [
                             'id',
                             'name',
                             'avatar_url',
                             'bio',
                             'shelved_count',
                             'reviews_count',
                             'url',
                         ]
                     ],
                     'total',
                 ]);
    }
}
