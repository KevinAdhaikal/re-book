<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $members = User::where('role', 'member')->latest()->get();
        return view('admin.users.index', compact('members'));
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
