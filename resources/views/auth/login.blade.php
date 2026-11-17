@extends('layouts.app')

@section('title', 'Masuk - Re:Book')

@section('content')
<div style="max-width: 380px; margin: 40px auto 20px;">
  <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 30px;">
    <div style="text-align: center; margin-bottom: 24px;">
      <a href="{{ route('home') }}" style="background-color: var(--member-accent); color: #fff; font-weight: 800; font-size: 1.2rem; padding: 4px 12px; border-radius: 4px; text-decoration: none; display: inline-block; margin-bottom: 12px;">Re:Book</a>
      <h3 style="font-size: 1.3rem; font-weight: 600; margin: 0;">Masuk ke Akun</h3>
      <p style="color: var(--text-muted); font-size: 0.85rem; margin-top: 5px;">Masuk untuk mengelola ulasan & rak bacaanmu</p>
    </div>

    @if($errors->any())
      <div style="background: rgba(218, 54, 51, 0.2); border: 1px solid var(--admin-danger); color: #f85149; padding: 10px 14px; border-radius: 6px; font-size: 0.85rem; margin-bottom: 20px;">
        {{ $errors->first() }}
      </div>
    @endif

    <form action="{{ route('login') }}" method="POST">
      @csrf
      <div style="margin-bottom: 16px;">
        <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 6px;">Alamat Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com" style="width: 100%; padding: 10px 12px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none; font-size: 0.9rem;">
      </div>

      <div style="margin-bottom: 22px;">
        <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 6px;">Password</label>
        <input type="password" name="password" required placeholder="••••••••" style="width: 100%; padding: 10px 12px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none; font-size: 0.9rem;">
      </div>

      <button type="submit" style="width: 100%; padding: 11px; background: var(--member-accent); border: none; color: #fff; font-weight: 600; font-size: 0.95rem; border-radius: 6px; cursor: pointer; transition: opacity 0.2s;">Masuk</button>
    </form>
  </div>

  {{-- Box Tautan Registrasi --}}
  <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 16px 20px; margin-top: 16px; text-align: center; font-size: 0.88rem; color: var(--text-muted);">
    Belum punya akun? <a href="{{ route('register') }}" style="color: #58a6ff; text-decoration: none; font-weight: 600;">Daftar akun baru</a>
  </div>
</div>
@endsection