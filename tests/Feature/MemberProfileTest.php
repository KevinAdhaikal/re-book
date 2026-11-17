<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Comment;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MemberProfileTest extends TestCase
{
    protected $member1;
    protected $member2;
    protected $book;

    protected function setUp(): void
    {
        parent::setUp();

        $this->member1 = User::firstOrCreate(
            ['email' => 'profile_member1@rebook.com'],
            [
                'name' => 'Budi Santoso',
                'password' => bcrypt('password123'),
                'role' => 'member',
                'status' => 'Aktif',
            ]
        );

        $this->member2 = User::firstOrCreate(
            ['email' => 'profile_member2@rebook.com'],
            [
                'name' => 'Siti Rahma',
                'password' => bcrypt('password123'),
                'role' => 'member',
                'status' => 'Aktif',
            ]
        );

        $this->book = Book::firstOrCreate(
            ['title' => 'Laskar Pelangi'],
            [
                'author' => 'Andrea Hirata',
                'genre' => 'Novel',
                'description' => 'Kisah inspiratif tentang pendidikan di Belitung',
            ]
        );
    }

    /**
     * Test: Member dapat mengakses halaman profil sendiri.
     */
    public function test_member_can_view_profile_page()
    {
        $response = $this->actingAs($this->member1)->get(route('member.profile'));
        $response->assertStatus(200);
        $response->assertSee('Pengaturan Profil');
        $response->assertSee($this->member1->name);
    }

    /**
     * Test: Member dapat mengedit nama lengkap, bio, dan genre buku favorit.
     */
    public function test_member_can_update_name_bio_and_favorite_genres()
    {
        $response = $this->actingAs($this->member1)->put(route('member.profile.update'), [
            'name' => 'Budi Santoso Pembaca',
            'bio' => 'Suka membaca novel sejarah dan sastra klasik.',
            'favorite_genres' => ['Novel', 'Sejarah', 'Philosophy'],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->member1->refresh();
        $this->assertEquals('Budi Santoso Pembaca', $this->member1->name);
        $this->assertEquals('Suka membaca novel sejarah dan sastra klasik.', $this->member1->bio);
        $this->assertEquals(['Novel', 'Sejarah', 'Philosophy'], $this->member1->favorite_genres);
        // Pastikan email tidak berubah
        $this->assertEquals('profile_member1@rebook.com', $this->member1->email);
    }

    /**
     * Test: Validasi nama tidak boleh kosong dan bio maksimal 300 karakter.
     */
    public function test_profile_update_validation()
    {
        // Nama kosong
        $response1 = $this->actingAs($this->member1)->put(route('member.profile.update'), [
            'name' => '',
            'bio' => 'Bio singkat',
        ]);
        $response1->assertSessionHasErrors(['name']);

        // Bio lebih dari 300 karakter
        $response2 = $this->actingAs($this->member1)->put(route('member.profile.update'), [
            'name' => 'Budi Santoso',
            'bio' => str_repeat('A', 301),
        ]);
        $response2->assertSessionHasErrors(['bio']);
    }

    protected function fakeImage(string $name = 'avatar.png', int $sizeInKb = 10): UploadedFile
    {
        $png = base64_decode("iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==");
        if ($sizeInKb * 1024 > strlen($png)) {
            $png .= str_repeat(' ', ($sizeInKb * 1024) - strlen($png));
        }
        return UploadedFile::fake()->createWithContent($name, $png);
    }

    /**
     * Test: Member dapat mengunggah foto profil (JPG/PNG/WEBP, max 2MB).
     */
    public function test_member_can_upload_and_replace_avatar()
    {
        Storage::fake('public');

        $file = $this->fakeImage('my_avatar.png', 1500); // 1.5 MB

        $response = $this->actingAs($this->member1)->put(route('member.profile.update'), [
            'name' => 'Budi Santoso',
            'avatar' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->member1->refresh();
        $this->assertNotNull($this->member1->avatar);
        Storage::disk('public')->assertExists($this->member1->avatar);

        // Upload avatar kedua, avatar lama harus terhapus
        $oldAvatarPath = $this->member1->avatar;
        $file2 = $this->fakeImage('my_new_avatar.png', 800);

        $response2 = $this->actingAs($this->member1)->put(route('member.profile.update'), [
            'name' => 'Budi Santoso',
            'avatar' => $file2,
        ]);

        $response2->assertRedirect();
        $this->member1->refresh();
        Storage::disk('public')->assertMissing($oldAvatarPath);
        Storage::disk('public')->assertExists($this->member1->avatar);
    }

    /**
     * Test: Upload file avatar lebih dari 2MB gagal validasi.
     */
    public function test_avatar_upload_exceeding_max_size_fails()
    {
        Storage::fake('public');

        $fileLarge = $this->fakeImage('heavy_avatar.png', 2500); // 2.5 MB

        $response = $this->actingAs($this->member1)->put(route('member.profile.update'), [
            'name' => 'Budi Santoso',
            'avatar' => $fileLarge,
        ]);

        $response->assertSessionHasErrors(['avatar']);
    }

    /**
     * Test: Member dapat menghapus foto profil dan kembali ke default avatar.
     */
    public function test_member_can_delete_avatar()
    {
        Storage::fake('public');

        $file = $this->fakeImage('avatar_to_delete.png', 10);
        $this->actingAs($this->member1)->put(route('member.profile.update'), [
            'name' => 'Budi Santoso',
            'avatar' => $file,
        ]);

        $this->member1->refresh();
        $avatarPath = $this->member1->avatar;
        Storage::disk('public')->assertExists($avatarPath);

        // Hapus avatar
        $response = $this->actingAs($this->member1)->delete(route('member.profile.avatar.destroy'));
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->member1->refresh();
        $this->assertNull($this->member1->avatar);
        Storage::disk('public')->assertMissing($avatarPath);
    }

    /**
     * Test: Halaman profil publik menampilkan info profil, bio, genre, ulasan, dan menyembunyikan data sensitif.
     */
    public function test_public_profile_displays_correct_information_and_protects_privacy()
    {
        $this->member2->name = 'Siti Penikmat Buku';
        $this->member2->bio = 'Penggemar fiksi ilmiah dan teknologi masa depan.';
        $this->member2->favorite_genres = ['Science Fiction', 'Technology'];
        $this->member2->save();

        // Buat ulasan oleh member2
        Review::firstOrCreate(
            ['user_id' => $this->member2->id, 'book_id' => $this->book->id],
            ['rating' => 5, 'content' => 'Buku yang sangat menginspirasi semangat belajar!']
        );

        // Akses via /user/{id}
        $response = $this->actingAs($this->member1)->get(route('users.profile', $this->member2->id));
        $response->assertStatus(200);
        $response->assertSee('Siti Penikmat Buku');
        $response->assertSee('Penggemar fiksi ilmiah dan teknologi masa depan.');
        $response->assertSee('Science Fiction');
        $response->assertSee('Technology');
        $response->assertSee('Buku yang sangat menginspirasi semangat belajar!');

        // PRIVACY CHECK: Jangan tampilkan email member pada profil publik
        $response->assertDontSee('profile_member2@rebook.com');

        // Karena yang login adalah member1, tombol "Edit Profil Saya" tidak boleh muncul
        $response->assertDontSee('Edit Profil Saya');

        // Akses via alias /profile/{id}
        $responseAlias = $this->actingAs($this->member1)->get(route('users.profile.alias', $this->member2->id));
        $responseAlias->assertStatus(200);
        $responseAlias->assertSee('Siti Penikmat Buku');

        // Jika member2 melihat profilnya sendiri di halaman publik, tombol "Edit Profil Saya" harus muncul
        $responseOwn = $this->actingAs($this->member2)->get(route('users.profile', $this->member2->id));
        $responseOwn->assertStatus(200);
        $responseOwn->assertSee('Edit Profil Saya');
    }

    /**
     * Test: Profil publik menampilkan "Belum ada bio" jika bio belum diisi.
     */
    public function test_public_profile_fallback_when_bio_is_empty()
    {
        $this->member1->bio = null;
        $this->member1->save();

        $response = $this->get(route('users.profile', $this->member1->id));
        $response->assertStatus(200);
        $response->assertSee('Belum ada bio.');
    }

    /**
     * Test: Komentar ulasan buku menampilkan avatar dan link menuju profil member.
     */
    public function test_comments_link_to_member_public_profile()
    {
        $review = Review::firstOrCreate(
            ['user_id' => $this->member1->id, 'book_id' => $this->book->id],
            ['rating' => 4, 'content' => 'Ulasan utama oleh Budi.']
        );

        $comment = Comment::firstOrCreate(
            ['review_id' => $review->id, 'user_id' => $this->member2->id],
            ['comment' => 'Saya sangat setuju dengan ulasan ini!']
        );

        $response = $this->get(route('books.show', $this->book->id));
        $response->assertStatus(200);
        $response->assertSee(route('users.profile', $this->member2->id));
        $response->assertSee('Saya sangat setuju dengan ulasan ini!');
    }
}
