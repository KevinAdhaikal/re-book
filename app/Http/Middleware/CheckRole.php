<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Cek apakah user sudah login dan role-nya sesuai
        if (! Auth::check() || Auth::user()->role !== $role) {
            abort(403, 'Kamu tidak memiliki akses ke halaman ini.');
        }

        // Cek jika akun member diblokir oleh admin saat sedang aktif
        if (Auth::user()->status === 'Diblokir') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors(['email' => 'Akun Anda telah diblokir oleh admin.']);
        }

        return $next($request);
    }
}
