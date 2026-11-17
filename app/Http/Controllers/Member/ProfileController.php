<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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

        return view('member.profile', compact('user', 'favoriteBooks', 'myReviews', 'availableGenres'));
    }

    /**
     * Update Informasi Profil Member (Nama, Bio, Genre Favorit, Foto Profil)
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'            => 'required|string|max:255',
            'bio'             => 'nullable|string|max:300',
            'favorite_genres' => 'nullable|array',
            'favorite_genres.*' => 'string|max:50',
            'avatar'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'name.required'   => 'Nama lengkap wajib diisi.',
            'name.max'        => 'Nama lengkap maksimal 255 karakter.',
            'bio.max'         => 'Bio maksimal 300 karakter.',
            'avatar.image'    => 'File foto profil harus berupa gambar.',
            'avatar.mimes'    => 'Format gambar yang diperbolehkan adalah JPG, JPEG, PNG, dan WEBP.',
            'avatar.max'      => 'Ukuran foto profil maksimal 2 MB.',
        ]);

        $user->name = $request->name;
        $user->bio = $request->bio;
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

        return back()->with('success', 'Profil Anda berhasil diperbarui!');
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

        return back()->with('success', 'Foto profil berhasil dihapus dan kembali ke avatar bawaan.');
    }

    /**
     * Halaman Profil Publik (Dapat Dilihat Member Lain)
     */
    public function show($id)
    {
        $member = User::findOrFail($id);

        // Maksimal 4 buku favorit untuk showcase profil
        $favoriteBooks = $member->favoriteBooks()->withAvg('reviews', 'rating')->take(4)->get();

        // Ulasan yang ditulis oleh member ini
        $memberReviews = $member->reviews()->with('book')->latest()->get();
        $reviewsCount = $memberReviews->count();

        return view('member.public_profile', compact('member', 'favoriteBooks', 'memberReviews', 'reviewsCount'));
    }

    /**
     * Update 4 Buku Favorit di Profil
     */
    public function updateFavorites(Request $request)
    {
        $request->validate([
            'book_ids' => 'required|array|max:4', // Maksimal 4 buku
        ]);

        $user = Auth::user();
        $user->favoriteBooks()->sync($request->book_ids);

        return back()->with('success', 'Pajangan 4 Buku Favorit berhasil diperbarui!');
    }
}
