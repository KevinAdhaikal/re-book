<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\ReadingShelf;
use App\Models\User;
use App\Services\ContentFilterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Daftar genre standar Re:Book
     */
    protected $defaultGenres = [
        'Novel',
        'Fiksi',
        'Non-Fiksi',
        'Sejarah',
        'Romance',
        'Thriller',
        'Mystery',
        'Fantasy',
        'Science Fiction',
        'Self-Development',
        'Psychology',
        'Biography',
        'Education',
        'Philosophy',
        'Business',
        'Technology',
        'Religion',
    ];

    /**
     * Halaman Pengaturan & Tampilan Profil Member Pribadi
     */
    public function index()
    {
        $user = Auth::user();

        // Mengambil maksimal 4 buku favorit untuk showcase profil
        $favoriteBooks = $user->favoriteBooks()->withAvg('reviews', 'rating')->take(4)->get();

        // Ulasan yang ditulis oleh member ini
        $myReviews = $user->reviews()->with('book')->latest()->get();

        // Daftar genre yang dapat dipilih
        $availableGenres = $this->defaultGenres;

        // Koleksi kandidat buku untuk modal picker 4 Buku Favorit
        $userBookIds = $user->favoriteBooks()->pluck('books.id')
            ->merge($user->likedBooks()->pluck('books.id'))
            ->merge($user->readingShelves()->pluck('book_id'))
            ->unique();

        $candidateBooks = Book::whereIn('id', $userBookIds)->withAvg('reviews', 'rating')->get();
        if ($candidateBooks->count() < 16) {
            $extra = Book::whereNotIn('id', $candidateBooks->pluck('id'))
                ->withAvg('reviews', 'rating')
                ->take(24)
                ->get();
            $candidateBooks = $candidateBooks->merge($extra);
        }

        $candidatesData = $candidateBooks->map(function ($b) {
            return [
                'id' => $b->id,
                'title' => $b->title,
                'author' => $b->author,
                'cover' => $b->cover ? asset('storage/'.$b->cover) : null,
                'genre' => $b->genre,
                'rating' => $b->reviews_avg_rating ? number_format($b->reviews_avg_rating, 1) : null,
            ];
        })->values();

        $favoriteBookIds = $favoriteBooks->pluck('id')->values()->toArray();

        return view('member.profile', compact('user', 'favoriteBooks', 'favoriteBookIds', 'candidatesData', 'myReviews', 'availableGenres'));
    }

    /**
     * Update Informasi Profil Member (Nama, Bio, Genre Favorit, Foto Profil)
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:300',
            'favorite_genres' => 'nullable|array',
            'favorite_genres.*' => 'string|max:50',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.max' => 'Nama lengkap maksimal 255 karakter.',
            'bio.max' => 'Bio maksimal 300 karakter.',
            'avatar.image' => 'File foto profil harus berupa gambar.',
            'avatar.mimes' => 'Format gambar yang diperbolehkan adalah JPG, JPEG, PNG, dan WEBP.',
            'avatar.max' => 'Ukuran foto profil maksimal 2 MB.',
        ]);

        $user->name = $request->name;
        // Sensor otomatis kata kasar pada Bio profil sebelum disimpan
        $user->bio = ContentFilterService::filter($request->bio);
        $user->favorite_genres = $request->favorite_genres ?? [];

        // Upload Foto Profil Baru
        if ($request->hasFile('avatar')) {
            // Hapus foto lama jika ada
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Simpan foto profil baru dengan nama unik di folder avatars
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        } elseif ($request->boolean('remove_avatar')) {
            // Opsi hapus foto profil dan kembali ke default avatar
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = null;
        }

        $user->save();

        session()->flash('active_tab', 'identity');

        return back()->with('success', 'Profil Anda berhasil diperbarui!');
    }

    /**
     * Ubah Password Akun Member
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        if (! Hash::check($request->current_password, $user->password)) {
            session()->flash('active_tab', 'security');

            return back()->withErrors(['current_password' => 'Password saat ini yang Anda masukkan salah.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        session()->flash('active_tab', 'security');

        return back()->with('success', 'Password Anda berhasil diperbarui!');
    }

    /**
     * Hapus Foto Profil Khusus (Kembali ke Avatar Default)
     */
    public function deleteAvatar()
    {
        $user = Auth::user();

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->avatar = null;
        $user->save();

        session()->flash('active_tab', 'identity');

        return back()->with('success', 'Foto profil berhasil dihapus dan kembali ke avatar bawaan.');
    }

    /**
     * Halaman Profil Publik (Dapat Dilihat Member Lain - Letterboxd Style)
     */
    public function show($id)
    {
        $member = User::withCount(['followers', 'following', 'reviews', 'readingShelves'])->findOrFail($id);

        // Maksimal 4 buku favorit untuk showcase profil
        $favoriteBooks = $member->favoriteBooks()->withAvg('reviews', 'rating')->take(4)->get();

        // Ulasan yang ditulis oleh member ini (terbaru di atas)
        $memberReviews = $member->reviews()
            ->with(['book', 'comments'])
            ->withCount('likedByUsers')
            ->latest('id')
            ->get();
        $reviewsCount = $memberReviews->count();

        // Buku yang sedang dibaca member saat ini (Currently Reading)
        $currentlyReading = $member->readingShelves()
            ->where('status', ReadingShelf::STATUS_CURRENTLY_READING)
            ->with('book.reviews')
            ->latest('updated_at')
            ->take(4)
            ->get();

        // Jumlah buku yang sudah selesai dibaca
        $readCount = $member->readingShelves()->where('status', ReadingShelf::STATUS_READ)->count();

        return view('member.public_profile', compact(
            'member',
            'favoriteBooks',
            'memberReviews',
            'reviewsCount',
            'currentlyReading',
            'readCount'
        ));
    }

    /**
     * Update 4 Buku Favorit di Profil
     */
    public function updateFavorites(Request $request)
    {
        $request->validate([
            'book_ids' => 'nullable|array|max:4', // Maksimal 4 buku
        ]);

        $user = Auth::user();
        $user->favoriteBooks()->sync($request->book_ids ?? []);

        session()->flash('active_tab', 'favorites');

        return back()->with('success', 'Pajangan 4 Buku Favorit berhasil diperbarui!');
    }
}
