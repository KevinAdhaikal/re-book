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

        $members = $query->latest()->paginate(15)->withQueryString();

        return view('admin.users.index', compact('members', 'search', 'status'));
    }

    // Fitur Utama: Toggle Blokir / Aktifkan Member
    public function toggleBlock($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            return back()->with('error', 'Akun Admin tidak bisa diblokir!');
        }

        $user->status = ($user->status === 'Aktif') ? 'Diblokir' : 'Aktif';
        $user->save();

        $pesan = $user->status === 'Diblokir' ? 'Akun member berhasil diblokir!' : 'Akun member berhasil diaktifkan kembali!';
        return back()->with('success', $pesan);
    }
}
