@extends('layouts.admin')

@section('title', 'Dashboard Overview - Admin Re:Book')

@section('admin_content')
<h2 style="margin-bottom: 20px; font-weight: 700; color: var(--text-main);">Overview Dashboard Admin</h2>

<!-- Grid Card Ringkasan Statistik -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 30px;">
  
  <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 20px;">
    <p style="color: var(--text-muted); font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Total Katalog Buku</p>
    <h3 style="font-size: 1.8rem; font-weight: 800; color: #58a6ff;">{{ $totalBooks }}</h3>
    <span style="font-size: 0.75rem; color: var(--text-muted);">Judul terdaftar</span>
  </div>

  <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 20px;">
    <p style="color: var(--text-muted); font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Member Komunitas</p>
    <h3 style="font-size: 1.8rem; font-weight: 800; color: #3fb950;">{{ $totalMembers }}</h3>
    <span style="font-size: 0.75rem; color: #8b949e;">
      <strong style="color: #3fb950;">{{ $activeMembers }}</strong> Aktif &bull; <strong style="color: #f85149;">{{ $blockedMembers }}</strong> Diblokir
    </span>
  </div>

  <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 20px;">
    <p style="color: var(--text-muted); font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Total Ulasan</p>
    <h3 style="font-size: 1.8rem; font-weight: 800; color: var(--star-rating);">{{ $totalReviews }}</h3>
    <span style="font-size: 0.75rem; color: var(--text-muted);">Ulasan pembaca</span>
  </div>

  <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 20px;">
    <p style="color: var(--text-muted); font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Interaksi & Suka</p>
    <h3 style="font-size: 1.8rem; font-weight: 800; color: var(--favorite-coral);">{{ $totalInteractions }}</h3>
    <span style="font-size: 0.75rem; color: var(--text-muted);">Like buku & ulasan</span>
  </div>

  <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 20px;">
    <p style="color: var(--text-muted); font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Filter Kata Kasar</p>
    <h3 style="font-size: 1.8rem; font-weight: 800; color: #f85149;">{{ $totalWords }}</h3>
    <span style="font-size: 0.75rem; color: var(--text-muted);">Kata dalam daftar hitam</span>
  </div>

</div>

<!-- Pintasan Aksi Cepat Admin -->
<div style="display: flex; gap: 12px; margin-bottom: 30px; flex-wrap: wrap;">
  <a href="{{ route('admin.reviews.index') }}" style="background: #21262d; border: 1px solid var(--border-color); color: var(--text-main); padding: 9px 16px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; transition: border-color 0.15s;">
    <i class="fa-solid fa-comments" style="color: #58a6ff;"></i> Moderasi Seluruh Ulasan
  </a>
  <a href="{{ route('admin.users.index') }}" style="background: #21262d; border: 1px solid var(--border-color); color: var(--text-main); padding: 9px 16px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; transition: border-color 0.15s;">
    <i class="fa-solid fa-users" style="color: #3fb950;"></i> Kelola Member
  </a>
  <a href="{{ route('admin.books.manage') }}" style="background: #21262d; border: 1px solid var(--border-color); color: var(--text-main); padding: 9px 16px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; transition: border-color 0.15s;">
    <i class="fa-solid fa-book" style="color: var(--member-accent);"></i> Kelola Katalog Buku
  </a>
  <a href="{{ route('admin.exports.index') }}" style="background: #21262d; border: 1px solid var(--border-color); color: var(--text-main); padding: 9px 16px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; transition: border-color 0.15s;">
    <i class="fa-solid fa-file-arrow-down" style="color: #3fb950;"></i> Unduh Data / Export CSV
  </a>
</div>

<!-- Layout 2 Kolom: Top 5 Buku Terpopuler & Ulasan Terbaru -->
<div style="display: grid; grid-template-columns: 1fr; gap: 30px;">

  <!-- Tabel 1: Top 5 Buku Terpopuler -->
  <div>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
      <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--text-main); display: flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-fire" style="color: #F78166;"></i> Top 5 Buku Terpopuler di Komunitas
      </h3>
      <a href="{{ route('admin.books.manage') }}" style="font-size: 0.8rem; color: #58a6ff; text-decoration: none;">Lihat Semua &rarr;</a>
    </div>
    
    <table class="custom-table" style="margin-top: 0;">
      <thead>
        <tr>
          <th style="width: 50px;">Rank</th>
          <th style="width: 60px;">Cover</th>
          <th>Judul & Penulis</th>
          <th>Genre</th>
          <th>Rating</th>
          <th>Suka</th>
          <th style="width: 100px;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($popularBooks as $index => $pb)
          <tr>
            <td>
              <span style="font-weight: 700; color: {{ $index === 0 ? 'var(--star-rating)' : ($index === 1 ? '#c9d1d9' : ($index === 2 ? '#b08800' : 'var(--text-muted)')) }};">
                #{{ $index + 1 }}
              </span>
            </td>
            <td>
              @if($pb->cover)
                <img src="{{ asset('storage/' . $pb->cover) }}" alt="{{ $pb->title }}" style="width: 36px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border-color);">
              @else
                <div style="width: 36px; height: 50px; background: #21262d; border-radius: 4px; border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: center; color: #8b949e; font-size: 0.75rem;">
                  <i class="fa-solid fa-book"></i>
                </div>
              @endif
            </td>
            <td>
              <div style="font-weight: 700; color: var(--text-main); margin-bottom: 2px;">{{ $pb->title }}</div>
              <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $pb->author }}</div>
            </td>
            <td>
              <span style="background: #21262d; border: 1px solid var(--border-color); padding: 2px 7px; border-radius: 4px; font-size: 0.75rem; color: #79c0ff;">
                {{ $pb->genre }}
              </span>
            </td>
            <td>
              <span style="color: var(--star-rating); font-weight: 700; font-size: 0.85rem;">
                <i class="fa-solid fa-star"></i> {{ number_format($pb->reviews_avg_rating ?? 0, 1) }}
              </span>
              <span style="font-size: 0.75rem; color: var(--text-muted);">({{ $pb->reviews_count }})</span>
            </td>
            <td>
              <span style="color: var(--favorite-coral); font-size: 0.85rem; font-weight: 600;">
                <i class="fa-solid fa-heart"></i> {{ $pb->liked_by_users_count }}
              </span>
            </td>
            <td>
              <a href="{{ route('books.show', $pb->id) }}" target="_blank" style="color: #58a6ff; text-decoration: none; font-size: 0.8rem; font-weight: 600;">
                Detail <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 0.7rem;"></i>
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 20px;">Belum ada buku terdaftar.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <!-- Tabel 2: Ulasan Terbaru -->
  <div>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
      <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--text-main); display: flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-comments" style="color: #58a6ff;"></i> Ulasan Terbaru dari Komunitas
      </h3>
      <a href="{{ route('admin.reviews.index') }}" style="font-size: 0.8rem; color: #58a6ff; text-decoration: none;">Kelola Ulasan &rarr;</a>
    </div>

    <table class="custom-table" style="margin-top: 0;">
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
            <td>
              <div style="display: flex; align-items: center; gap: 8px;">
                @if($r->user)
                  <x-user-avatar :user="$r->user" size="sm" />
                  <strong>{{ $r->user->name }}</strong>
                @else
                  <span style="color: var(--text-muted);">Anonim</span>
                @endif
              </div>
            </td>
            <td>
              <span style="color: #c9d1d9; font-weight: 600;">{{ $r->book->title ?? 'Buku' }}</span>
            </td>
            <td>
              <span style="color: var(--star-rating); font-weight: 700; font-size: 0.85rem;">
                <i class="fa-solid fa-star"></i> {{ $r->rating }}
              </span>
            </td>
            <td>
              <span style="color: #8b949e; font-size: 0.85rem;">&ldquo;{{ Str::limit($r->content, 60) }}&rdquo;</span>
            </td>
            <td style="color: var(--text-muted); font-size: 0.8rem;">
              {{ $r->created_at->diffForHumans() }}
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 20px;">Belum ada ulasan masuk.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

</div>
@endsection
