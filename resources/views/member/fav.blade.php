@extends('layouts.member')

@section('title', 'Buku Favorit - Re:Book')

@section('member_content')
<div style="margin-bottom: 25px;">
  <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: gap; gap: 15px;">
    <div>
      <h2 style="color: var(--text-main); font-size: 1.4rem; margin-bottom: 6px; display: flex; align-items: center; gap: 10px;">
        <i class="fa-solid fa-star" style="color: var(--star-rating);"></i> Showcase Buku Favorit Pilihan Saya
        <span style="font-size: 0.85rem; color: var(--text-muted); font-weight: normal;">({{ $favoriteBooks->count() }}/4 Buku)</span>
      </h2>
      <p style="color: var(--text-muted); font-size: 0.85rem;">
        Pilih hingga 4 buku terbaik untuk ditampilkan sebagai koleksi unggulan di profil member Anda.
      </p>
    </div>
  </div>
</div>

{{-- Form Tambah Buku Favorit (Hanya jika belum mencapai batas 4 buku) --}}
@if($favoriteBooks->count() < 4)
  <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 18px 20px; margin-bottom: 30px; max-width: 950px;">
    <h4 style="font-size: 0.95rem; margin-bottom: 12px; color: var(--text-main); display: flex; align-items: center; gap: 8px;">
      <i class="fa-solid fa-plus-circle" style="color: var(--member-accent);"></i> Tambah Buku ke Koleksi Favorit
    </h4>
    <form action="{{ route('member.fav.store') }}" method="POST" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
      @csrf
      <select name="book_id" required style="padding: 8px 12px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none; font-size: 0.9rem; flex: 1; min-width: 250px;">
        <option value="" disabled selected>-- Pilih Buku dari Katalog --</option>
        @foreach($availableBooks as $ab)
          @if(!$favoriteBooks->contains('id', $ab->id))
            <option value="{{ $ab->id }}">{{ $ab->title }} — {{ $ab->author }} ({{ $ab->genre }})</option>
          @endif
        @endforeach
      </select>
      <button type="submit" style="background: var(--member-accent); color: #fff; border: none; padding: 9px 18px; border-radius: 6px; font-weight: 600; font-size: 0.85rem; cursor: pointer; display: flex; align-items: center; gap: 6px;">
        <i class="fa-solid fa-star"></i> Simpan ke Favorit
      </button>
    </form>
  </div>
@else
  <div style="background: rgba(230, 145, 56, 0.1); border: 1px solid var(--star-rating); border-radius: 8px; padding: 12px 18px; margin-bottom: 30px; max-width: 950px; font-size: 0.88rem; color: var(--star-rating);">
    <i class="fa-solid fa-circle-info"></i> Anda sudah memilih maksimal 4 buku favorit. Hapus salah satu buku di bawah jika ingin menggantinya dengan buku lain.
  </div>
@endif

{{-- Grid 4 Buku Favorit --}}
<div style="max-width: 950px; margin-bottom: 40px;">
  @if($favoriteBooks->isEmpty())
    <div style="background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 40px; text-align: center;">
      <i class="fa-regular fa-star" style="font-size: 2.2rem; color: #8b949e; margin-bottom: 12px;"></i>
      <h4 style="color: var(--text-main); margin-bottom: 6px;">Belum Ada Buku Favorit</h4>
      <p style="color: #8b949e; font-size: 0.9rem; margin-bottom: 15px;">
        Pilih hingga 4 buku favorit untuk ditampilkan pada showcase profil Anda.
      </p>
    </div>
  @else
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 20px;">
      @foreach($favoriteBooks as $book)
        <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; position: relative;">

          {{-- Tombol Hapus dari Favorit --}}
          <form action="{{ route('member.fav.destroy', $book->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini dari daftar Buku Favorit?');" style="position: absolute; top: 10px; right: 10px; z-index: 99; margin: 0;">
            @csrf
            @method('DELETE')
            <button type="submit" title="Hapus dari Buku Favorit"
              style="background-color: #0d1117; border: 1px solid #30363d; width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--star-rating); font-size: 0.95rem; cursor: pointer; padding: 0;">
              <i class="fa-solid fa-star"></i>
            </button>
          </form>

          <div>
            @if($book->cover)
              <img src="{{ asset('storage/' . $book->cover) }}"
                   alt="{{ $book->title }}"
                   onerror="this.onerror=null; this.parentElement.querySelector('.cover-fallback').style.display='flex'; this.style.display='none';"
                   style="width: 100%; height: 250px; object-fit: cover; display: block;">
              <div class="cover-fallback" style="display: none; width: 100%; height: 250px; background: #161b22; flex-direction: column; align-items: center; justify-content: center; gap: 8px; color: var(--text-muted); border-bottom: 1px solid var(--border-color); text-align: center; padding: 15px;">
                <i class="fa-solid fa-book" style="font-size: 2.2rem; color: #30363d;"></i>
                <span style="font-size: 0.8rem; font-weight: 500; color: #8b949e;">{{ Str::limit($book->title, 25) }}</span>
              </div>
            @else
              <div style="width: 100%; height: 250px; background: #161b22; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; color: var(--text-muted); border-bottom: 1px solid var(--border-color); text-align: center; padding: 15px;">
                <i class="fa-solid fa-book" style="font-size: 2.2rem; color: #30363d;"></i>
                <span style="font-size: 0.8rem; font-weight: 500; color: #8b949e;">{{ Str::limit($book->title, 25) }}</span>
              </div>
            @endif

            <div style="padding: 12px; display: flex; flex-direction: column;">
              <div style="color: var(--star-rating); font-size: 0.9rem; margin-bottom: 6px;">
                <i class="fa-solid fa-star"></i> {{ number_format($book->reviews_avg_rating ?? 0, 1) }}
              </div>
              <h4 style="color: var(--text-main); font-size: 0.95rem; font-weight: 600; margin: 0 0 4px 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $book->title }}">{{ $book->title }}</h4>
              <p style="color: var(--text-muted); font-size: 0.8rem; margin: 0 0 12px 0;">{{ $book->genre }} &bull; {{ $book->author }}</p>
            </div>
          </div>

          <div style="padding: 0 12px 12px 12px; display: flex; gap: 8px;">
            <a href="{{ route('books.show', $book->id) }}" style="flex: 1; text-align: center; background: #21262D; color: var(--member-accent); font-size: 0.85rem; font-weight: 600; padding: 6px 0; border-radius: 6px; text-decoration: none;">
              Lihat Ulasan
            </a>
          </div>
        </div>
      @endforeach
    </div>
  @endif
</div>
@endsection
