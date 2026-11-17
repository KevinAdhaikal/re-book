@extends('layouts.admin')

@section('title', 'Moderasi Ulasan - Admin Re:Book')

@section('admin_content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 15px;">
  <div>
    <h2 style="font-size: 1.6rem; color: var(--text-main); margin-bottom: 4px; display: flex; align-items: center; gap: 10px;">
      <i class="fa-solid fa-comments" style="color: #58a6ff;"></i> Moderasi Ulasan Global
    </h2>
    <p style="color: var(--text-muted); font-size: 0.9rem;">
      Pantau, saring, dan moderasi seluruh ulasan pembaca di seluruh buku dari satu tempat.
    </p>
  </div>
  <a href="{{ route('admin.exports.reviews') }}" style="background: #21262d; border: 1px solid var(--border-color); color: var(--text-main); padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
    <i class="fa-solid fa-file-csv" style="color: #3fb950;"></i> Export CSV Ulasan
  </a>
</div>

{{-- Filter & Search Form --}}
<div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 16px; margin-bottom: 25px;">
  <form action="{{ route('admin.reviews.index') }}" method="GET" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
    {{-- Search input --}}
    <div style="flex: 1; min-width: 220px;">
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari isi ulasan, nama member, atau judul buku..."
             style="width: 100%; padding: 8px 12px; background: #0d1117; border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; font-size: 0.85rem; outline: none;">
    </div>

    {{-- Filter Buku --}}
    <div style="min-width: 180px;">
      <select name="book_id" onchange="this.form.submit()" style="width: 100%; padding: 8px 12px; background: #0d1117; border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; font-size: 0.85rem; outline: none; cursor: pointer;">
        <option value="">Semua Buku</option>
        @foreach($books as $book)
          <option value="{{ $book->id }}" {{ request('book_id') == $book->id ? 'selected' : '' }}>
            {{ Str::limit($book->title, 28) }}
          </option>
        @endforeach
      </select>
    </div>

    {{-- Filter Rating --}}
    <div style="min-width: 140px;">
      <select name="rating" onchange="this.form.submit()" style="width: 100%; padding: 8px 12px; background: #0d1117; border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; font-size: 0.85rem; outline: none; cursor: pointer;">
        <option value="">Semua Rating</option>
        <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>⭐ 5 Bintang</option>
        <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>⭐ 4 Bintang</option>
        <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>⭐ 3 Bintang</option>
        <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>⭐ 2 Bintang</option>
        <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>⭐ 1 Bintang</option>
      </select>
    </div>

    {{-- Sort By --}}
    <div style="min-width: 160px;">
      <select name="sort" onchange="this.form.submit()" style="width: 100%; padding: 8px 12px; background: #0d1117; border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; font-size: 0.85rem; outline: none; cursor: pointer;">
        <option value="newest" {{ ($sort ?? request('sort')) === 'newest' ? 'selected' : '' }}>Terbaru</option>
        <option value="oldest" {{ ($sort ?? request('sort')) === 'oldest' ? 'selected' : '' }}>Terlama</option>
        <option value="rating_desc" {{ ($sort ?? request('sort')) === 'rating_desc' ? 'selected' : '' }}>Rating Tertinggi</option>
        <option value="rating_asc" {{ ($sort ?? request('sort')) === 'rating_asc' ? 'selected' : '' }}>Rating Terendah</option>
        <option value="likes" {{ ($sort ?? request('sort')) === 'likes' ? 'selected' : '' }}>Paling Banyak Suka</option>
      </select>
    </div>

    <button type="submit" style="background: var(--admin-accent); color: white; padding: 8px 16px; border-radius: 6px; border: none; font-size: 0.85rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
      <i class="fa-solid fa-filter"></i> Terapkan
    </button>

    @if(request()->anyFilled(['search', 'book_id', 'rating', 'sort']) && (request('book_id') || request('rating') || request('search') || (request('sort') && request('sort') !== 'newest')))
      <a href="{{ route('admin.reviews.index') }}" style="background: #21262d; border: 1px solid var(--border-color); color: var(--text-muted); padding: 8px 14px; border-radius: 6px; text-decoration: none; font-size: 0.85rem;">
        Reset
      </a>
    @endif
  </form>
</div>

{{-- Tabel Ulasan Global --}}
<div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; overflow-x: auto;">
  <table class="custom-table" style="margin: 0; border: none;">
    <thead>
      <tr>
        <th style="width: 60px;">ID</th>
        <th style="width: 220px;">Buku</th>
        <th style="width: 180px;">Member</th>
        <th style="width: 90px;">Rating</th>
        <th>Isi Ulasan</th>
        <th style="width: 120px;">Interaksi</th>
        <th style="width: 110px;">Tanggal</th>
        <th style="width: 80px; text-align: center;">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($reviews as $r)
        <tr>
          <td style="color: var(--text-muted); font-size: 0.85rem;">#{{ $r->id }}</td>
          <td>
            <div style="display: flex; align-items: center; gap: 10px;">
              @if($r->book && $r->book->cover)
                <img src="{{ asset('storage/' . $r->book->cover) }}" alt="{{ $r->book->title }}" style="width: 32px; height: 45px; object-fit: cover; border-radius: 3px; border: 1px solid var(--border-color);">
              @else
                <div style="width: 32px; height: 45px; background: #21262d; border-radius: 3px; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; color: var(--text-muted);">
                  <i class="fa-solid fa-book"></i>
                </div>
              @endif
              <div>
                <a href="{{ $r->book ? route('books.show', $r->book->id) : '#' }}" target="_blank" style="color: var(--text-main); font-weight: 600; text-decoration: none; font-size: 0.85rem; line-height: 1.3; display: block;">
                  {{ Str::limit($r->book->title ?? 'Buku Dihapus', 26) }}
                </a>
                <span style="color: var(--text-muted); font-size: 0.75rem;">{{ $r->book->genre ?? '-' }}</span>
              </div>
            </div>
          </td>
          <td>
            <div style="display: flex; align-items: center; gap: 8px;">
              <div style="width: 26px; height: 26px; border-radius: 50%; overflow: hidden; background: var(--member-accent); color: white; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: bold; flex-shrink: 0;">
                @if($r->user && $r->user->avatarUrl())
                  <img src="{{ $r->user->avatarUrl() }}" alt="{{ $r->user->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                  {{ strtoupper(substr($r->user->name ?? 'M', 0, 1)) }}
                @endif
              </div>
              <div style="overflow: hidden;">
                <a href="{{ $r->user ? route('users.profile', $r->user->id) : '#' }}" target="_blank" style="color: var(--text-main); text-decoration: none; font-weight: 500; font-size: 0.85rem; display: block; white-space: nowrap; text-overflow: ellipsis; overflow: hidden;">
                  {{ $r->user->name ?? 'User Dihapus' }}
                </a>
                <span style="color: var(--text-muted); font-size: 0.72rem;">{{ $r->user->email ?? '' }}</span>
              </div>
            </div>
          </td>
          <td>
            <span style="color: var(--star-rating); font-weight: 700; font-size: 0.85rem;">
              ⭐ {{ $r->rating }}.0
            </span>
          </td>
          <td>
            <div style="font-size: 0.85rem; color: var(--text-main); line-height: 1.4; max-width: 380px;">
              "{{ Str::limit($r->content, 140) }}"
            </div>
          </td>
          <td style="font-size: 0.8rem; color: var(--text-muted);">
            <div><i class="fa-solid fa-thumbs-up" style="color: #58a6ff;"></i> {{ $r->liked_by_users_count }} Like</div>
            <div><i class="fa-regular fa-comments"></i> {{ $r->comments->count() }} Komentar</div>
          </td>
          <td style="color: var(--text-muted); font-size: 0.8rem;">
            {{ $r->created_at ? $r->created_at->format('d/m/y H:i') : '-' }}
          </td>
          <td style="text-align: center;">
            <form action="{{ route('admin.reviews.destroy', $r->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ulasan ini secara permanen?');" style="margin: 0;">
              @csrf
              @method('DELETE')
              <button type="submit" title="Hapus Ulasan"
                      style="background: transparent; border: 1px solid rgba(218, 54, 51, 0.4); color: var(--admin-danger); padding: 5px 8px; border-radius: 4px; cursor: pointer; font-size: 0.8rem; transition: background 0.15s;">
                <i class="fa-solid fa-trash-can"></i>
              </button>
            </form>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="8" style="text-align: center; padding: 40px; color: var(--text-muted);">
            <i class="fa-solid fa-comment-slash" style="font-size: 2rem; color: #30363d; margin-bottom: 10px; display: block;"></i>
            Tidak ada ulasan yang ditemukan sesuai kriteria filter.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

<div style="margin-top: 20px;">
  {{ $reviews->links() }}
</div>
@endsection
