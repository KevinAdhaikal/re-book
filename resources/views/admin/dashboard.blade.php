@extends('layouts.admin')

@section('title', 'Dashboard Overview - Admin Re:Book')

@section('admin_content')
<h2 style="margin-bottom: 20px;">Overview Dashboard Admin</h2>

<!-- Grid Card Ringkasan Statistik -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 35px;">
  
  <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 20px;">
    <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 6px;">TOTAL KATALOG BUKU</p>
    <h3 style="font-size: 1.8rem; color: var(--member-accent);">{{ $totalBooks }}</h3>
  </div>

  <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 20px;">
    <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 6px;">TOTAL MEMBER AKTIF</p>
    <h3 style="font-size: 1.8rem; color: #3fb950;">{{ $totalMembers }}</h3>
  </div>

  <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 20px;">
    <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 6px;">TOTAL ULASAN</p>
    <h3 style="font-size: 1.8rem; color: var(--star-rating);">{{ $totalReviews }}</h3>
  </div>

  <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 20px;">
    <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 6px;">FILTER KATA KASAR</p>
    <h3 style="font-size: 1.8rem; color: var(--favorite-coral);">{{ $totalWords }}</h3>
  </div>

</div>

<!-- Pintasan Aksi Cepat Admin -->
<div style="display: flex; gap: 12px; margin-bottom: 30px; flex-wrap: wrap;">
  <a href="{{ route('admin.reviews.index') }}" style="background: #21262d; border: 1px solid var(--border-color); color: var(--text-main); padding: 9px 16px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
    <i class="fa-solid fa-comments" style="color: #58a6ff;"></i> Moderasi Seluruh Ulasan
  </a>
  <a href="{{ route('admin.exports.index') }}" style="background: #21262d; border: 1px solid var(--border-color); color: var(--text-main); padding: 9px 16px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
    <i class="fa-solid fa-file-arrow-down" style="color: #3fb950;"></i> Unduh Data / Export CSV
  </a>
  <a href="{{ route('admin.books.manage') }}" style="background: #21262d; border: 1px solid var(--border-color); color: var(--text-main); padding: 9px 16px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
    <i class="fa-solid fa-book" style="color: var(--member-accent);"></i> Kelola Katalog Buku
  </a>
</div>

<!-- Tabel Ulasan Terbaru -->
<h3 style="margin-bottom: 15px;">💬 Ulasan Terbaru dari Komunitas</h3>
<table class="custom-table">
  <thead>
    <tr>
      <th>Member</th>
      <th>Buku</th>
      <th>Rating</th>
      <th>Isi Ulasan</th>
      <th>Waktu</th>
    </tr>
  </thead>
  <tbody>
    @forelse ($recentReviews as $r)
      <tr>
        <td><strong>{{ $r->user->name ?? 'User' }}</strong></td>
        <td>{{ $r->book->title ?? 'Buku' }}</td>
        <td><i class="fa-solid fa-star" style="color: var(--star-rating);"></i> {{ $r->rating }}</td>
        <td>"{{ Str::limit($r->content, 50) }}"</td>
        <td>{{ $r->created_at->diffForHumans() }}</td>
      </tr>
    @empty
      <tr>
        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 20px;">Belum ada ulasan masuk.</td>
      </tr>
    @endforelse
  </tbody>
</table>
@endsection