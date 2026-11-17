@extends('layouts.app')

@section('title', 'Daftar Member - Re:Book')

@section('content')
<div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 30px; max-width: 420px; margin: 30px auto;">
  <div style="text-align: center; margin-bottom: 25px;">
    <a href="{{ route('home') }}" style="background-color: var(--member-accent); color: #fff; font-weight: 800; font-size: 1.2rem; padding: 6px 14px; border-radius: 6px; text-decoration: none; display: inline-block; margin-bottom: 12px;">Re:Book</a>
    <h3 style="font-size: 1.4rem; font-weight: 600;">Buat Akun Member Baru</h3>
    <p style="color: var(--text-muted); font-size: 0.85rem; margin-top: 5px;">Gabung komunitas untuk mengulas & pamer 4 buku favoritmu!</p>
  </div>

  @if($errors->any())
    <div style="background: rgba(218, 54, 51, 0.2); border: 1px solid var(--admin-danger); color: #f85149; padding: 10px 14px; border-radius: 6px; font-size: 0.85rem; margin-bottom: 20px;">
      <ul style="margin-left: 15px;">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('register') }}" method="POST">
    @csrf
    
    <div style="margin-bottom: 15px;">
      <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 6px;">Nama Lengkap</label>
      <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Andi Wijaya" style="width: 100%; padding: 10px 12px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none; font-size: 0.9rem;">
    </div>

    <div style="margin-bottom: 15px;">
      <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 6px;">Alamat Email</label>
      <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com" style="width: 100%; padding: 10px 12px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none; font-size: 0.9rem;">
    </div>

    <div style="margin-bottom: 15px;">
      <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 6px;">Password</label>
      <input type="password" name="password" required placeholder="Minimal 8 karakter" style="width: 100%; padding: 10px 12px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none; font-size: 0.9rem;">
    </div>

    <div style="margin-bottom: 25px;">
      <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 6px;">Konfirmasi Password</label>
      <input type="password" name="password_confirmation" required placeholder="Ulangi password" style="width: 100%; padding: 10px 12px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none; font-size: 0.9rem;">
    </div>

    <button type="submit" style="width: 100%; padding: 11px; background: var(--member-accent); border: none; color: #fff; font-weight: 600; font-size: 0.95rem; border-radius: 6px; cursor: pointer; transition: background 0.2s;">Daftar Member</button>
  </form>

  <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid var(--border-color); text-align: center; font-size: 0.85rem; color: var(--text-muted);">
    Sudah punya akun? <a href="{{ route('login') }}" style="color: var(--member-accent); text-decoration: none; font-weight: 600;">Sign in</a>
  </div>
</div>
@endsection