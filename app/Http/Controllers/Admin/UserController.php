<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));
        $status = $request->input('status', 'all');
        $sort = $request->input('sort', 'newest');

        $query = User::where('role', 'member')->withCount(['reviews', 'readingShelves']);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($status === 'Aktif' || $status === 'Diblokir') {
            $query->where('status', $status);
        }

        // Pengurutan (Sorting)
        if ($sort === 'oldest' || $sort === 'id_asc') {
            $query->oldest('id');
        } elseif ($sort === 'name_asc') {
            $query->orderBy('name', 'asc');
        } elseif ($sort === 'name_desc') {
            $query->orderBy('name', 'desc');
        } elseif ($sort === 'email_asc') {
            $query->orderBy('email', 'asc');
        } elseif ($sort === 'email_desc') {
            $query->orderBy('email', 'desc');
        } elseif ($sort === 'reviews' || $sort === 'reviews_desc') {
            $query->orderByDesc('reviews_count')->orderBy('name', 'asc');
        } elseif ($sort === 'reviews_asc') {
            $query->orderBy('reviews_count', 'asc')->orderBy('name', 'asc');
        } elseif ($sort === 'shelves' || $sort === 'shelves_desc') {
            $query->orderByDesc('reading_shelves_count')->orderBy('name', 'asc');
        } elseif ($sort === 'shelves_asc') {
            $query->orderBy('reading_shelves_count', 'asc')->orderBy('name', 'asc');
        } elseif ($sort === 'status_asc') {
            $query->orderBy('status', 'asc')->orderBy('name', 'asc');
        } elseif ($sort === 'status_desc') {
            $query->orderBy('status', 'desc')->orderBy('name', 'asc');
        } else {
            // Default: Terbaru Mendaftar
            $query->latest('id');
        }

        $members = $query->paginate(15)->withQueryString();

        return view('admin.users.index', compact('members', 'search', 'status', 'sort'));
    }

    // Fitur Utama: Toggle Blokir / Aktifkan Member
    public function toggleBlock($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id() || $user->role === 'admin') {
            return back()->with('error', 'Akun Admin tidak bisa diblokir!');
        }

        $user->status = ($user->status === 'Aktif') ? 'Diblokir' : 'Aktif';
        $user->save();

        $pesan = $user->status === 'Diblokir' ? 'Akun member berhasil diblokir!' : 'Akun member berhasil diaktifkan kembali!';

        return back()->with('success', $pesan);
    }
}
