<?php

namespace Tests\Feature;

use App\Models\BadWord;
use App\Models\Book;
use App\Models\User;
use App\Services\ContentFilterService;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileSecurityAndFilterTest extends TestCase
{
    protected $member;

    protected function setUp(): void
    {
        parent::setUp();

        $this->member = User::firstOrCreate(
            ['email' => 'member_sec_test@rebook.com'],
            [
                'name' => 'Member Security Test',
                'password' => Hash::make('oldpassword123'),
                'role' => 'member',
                'status' => 'Aktif',
            ]
        );
    }

    public function test_member_can_update_password_with_correct_current_password()
    {
        // Reset password to oldpassword123
        $this->member->update(['password' => Hash::make('oldpassword123')]);

        $response = $this->actingAs($this->member)->put(route('member.profile.password.update'), [
            'current_password' => 'oldpassword123',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHas('success');
        $this->member->refresh();
        $this->assertTrue(Hash::check('newpassword123', $this->member->password));
    }

    public function test_password_update_fails_with_wrong_current_password()
    {
        $response = $this->actingAs($this->member)->put(route('member.profile.password.update'), [
            'current_password' => 'salahpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasErrors(['current_password']);
    }

    public function test_password_update_fails_with_unconfirmed_or_short_password()
    {
        $response = $this->actingAs($this->member)->put(route('member.profile.password.update'), [
            'current_password' => 'newpassword123',
            'password' => 'short',
            'password_confirmation' => 'mismatch',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_member_can_update_favorite_showcase_books_up_to_4()
    {
        $books = Book::take(4)->get();
        if ($books->count() < 4) {
            $this->markTestSkipped('Not enough books in database to test 4 favorites.');
        }

        $bookIds = $books->pluck('id')->toArray();

        $response = $this->actingAs($this->member)->post(route('member.favorites.update'), [
            'book_ids' => $bookIds,
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals(4, $this->member->favoriteBooks()->count());
    }

    public function test_member_favorite_showcase_fails_with_more_than_4_books()
    {
        $books = Book::take(5)->get();
        if ($books->count() < 5) {
            $this->markTestSkipped('Not enough books in database.');
        }

        $bookIds = $books->pluck('id')->toArray();

        $response = $this->actingAs($this->member)->post(route('member.favorites.update'), [
            'book_ids' => $bookIds,
        ]);

        $response->assertSessionHasErrors(['book_ids']);
    }

    public function test_bio_censors_bad_words_on_profile_update()
    {
        $badWordText = 'sensorunik_' . uniqid();
        BadWord::create(['word' => $badWordText]);

        try {
            $response = $this->actingAs($this->member)->put(route('member.profile.update'), [
                'name' => 'Member Security Test',
                'bio' => 'Halo ini bio dengan kata ' . $badWordText . ' yang kasar!',
                'favorite_genres' => ['Novel', 'Fiksi'],
            ]);

            $response->assertSessionHas('success');
            $this->member->refresh();

            $expectedCensored = str_repeat('*', strlen($badWordText));
            $this->assertStringContainsString($expectedCensored, $this->member->bio);
            $this->assertStringNotContainsString($badWordText, $this->member->bio);
        } finally {
            BadWord::where('word', $badWordText)->delete();
        }
    }

    public function test_content_filter_service_handles_suffixes_and_punctuation()
    {
        $testWord = 'kasar_' . uniqid();
        BadWord::create(['word' => $testWord]);

        try {
            $input = "Jangan bilang {$testWord}! Atau {$testWord}nya juga.";
            $output = ContentFilterService::filter($input);

            $this->assertStringNotContainsString($testWord, $output);
            $this->assertStringContainsString('!', $output);
            $this->assertStringContainsString('.', $output);
        } finally {
            BadWord::where('word', $testWord)->delete();
        }
    }
}
