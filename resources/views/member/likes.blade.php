@extends('layouts.member')

@section('title', 'Buku Disukai - Re:Book')

@section('member_content')
<div style="margin-bottom: 20px;">
  <h2 style="color: var(--text-main); font-size: 1.4rem; margin-bottom: 6px; display: flex; align-items: center; gap: 10px;">
    <i class="fa-solid fa-heart" style="color: #F78166;"></i> Buku yang Saya Sukai
    <span id="liked-count-badge" style="font-size: 0.85rem; color: var(--text-muted); font-weight: normal;">({{ $likedBooks->count() }} Buku)</span>
  </h2>
  <p style="color: var(--text-muted); font-size: 0.85rem;">
    Daftar koleksi buku yang telah Anda beri tanda suka. Klik ikon hati untuk menghapus dari daftar.
  </p>
</div>

<div style="max-width: 950px; margin-bottom: 40px;">
  @if(request()->filled('search'))
    <div style="margin-bottom: 15px; font-size: 0.85rem; color: var(--text-muted);">
      Hasil pencarian untuk "<strong>{{ request('search') }}</strong>" (kategori: {{ request('filter_type', 'Semua') }}) &bull;
      <a href="{{ route('member.likes.index') }}" style="color: var(--member-accent); text-decoration: none; margin-left: 5px;">
        <i class="fa-solid fa-xmark"></i> Hapus Filter
      </a>
    </div>
  @endif

  @if($likedBooks->isEmpty())
    @if(request()->filled('search'))
      {{-- Empty state pencarian tidak ditemukan --}}
      <div id="empty-state-container" style="background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 40px; text-align: center;">
        <i class="fa-solid fa-magnifying-glass" style="font-size: 2rem; color: #8b949e; margin-bottom: 12px;"></i>
        <h4 style="color: var(--text-main); margin-bottom: 6px;">Tidak ada hasil ditemukan</h4>
        <p style="color: #8b949e; font-size: 0.9rem; margin-bottom: 15px;">
          Tidak ada buku disukai yang cocok dengan kata kunci "{{ request('search') }}".
        </p>
        <a href="{{ route('member.likes.index') }}" style="background-color: var(--member-accent); color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 500; display: inline-block;">
          Tampilkan Semua Buku Disukai
        </a>
      </div>
    @else
      {{-- Empty state belum ada like sama sekali --}}
      <div id="empty-state-container" style="background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 40px; text-align: center;">
        <i class="fa-regular fa-heart" style="font-size: 2rem; color: #8b949e; margin-bottom: 12px;"></i>
        <h4 style="color: var(--text-main); margin-bottom: 6px;">Belum Ada Buku Disukai</h4>
        <p style="color: #8b949e; font-size: 0.9rem; margin-bottom: 15px;">Kamu belum menyukai buku apa pun.</p>
        <a href="{{ route('home') }}" style="background-color: var(--member-accent); color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 500; display: inline-block;">
          Jelajahi Buku di Beranda
        </a>
      </div>
    @endif
  @else
    <div id="liked-books-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 20px;">
      @foreach($likedBooks as $book)
        <div id="liked-card-{{ $book->id }}" class="liked-book-card" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; position: relative; transition: opacity 0.25s ease, transform 0.25s ease;">

          {{-- Tombol Unlike di Pojok Kanan Atas Cover --}}
          <form action="{{ route('member.likes.toggle', $book->id) }}" method="POST" class="form-like-toggle" data-book-id="{{ $book->id }}" style="position: absolute; top: 10px; right: 10px; z-index: 99; margin: 0;">
            @csrf
            <button type="button" class="btn-unlike-card" data-book-id="{{ $book->id }}" data-action="{{ route('member.likes.toggle', $book->id) }}" title="Hapus dari Buku Disukai"
              style="background-color: #0d1117; border: 1px solid #30363d; width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #F78166; font-size: 0.95rem; cursor: pointer; padding: 0; transition: transform 0.15s ease;">
              <i class="fa-solid fa-heart"></i>
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

          <div style="padding: 0 12px 12px 12px;">
            <a href="{{ route('books.show', $book->id) }}" style="display: block; width: 100%; text-align: center; background: #21262D; color: var(--member-accent); font-size: 0.85rem; font-weight: 600; padding: 6px 0; border-radius: 6px; text-decoration: none;">
              Lihat Ulasan
            </a>
          </div>
        </div>
      @endforeach
    </div>
  @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const csrfToken = '{{ csrf_token() }}';
  const countBadge = document.getElementById('liked-count-badge');
  const grid = document.getElementById('liked-books-grid');

  document.querySelectorAll('.btn-unlike-card').forEach(button => {
    button.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();

      const bookId = this.dataset.bookId;
      const actionUrl = this.dataset.action;
      const card = document.getElementById('liked-card-' + bookId);

      this.style.pointerEvents = 'none';
      this.style.transform = 'scale(0.85)';

      fetch(actionUrl, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'Content-Type': 'application/json'
        }
      })
      .then(response => {
        if (response.status === 401) {
          window.location.href = '{{ route('login') }}';
          return;
        }
        return response.json();
      })
      .then(data => {
        if (data && data.success && !data.liked) {
          // Buku berhasil di-unlike, hilangkan card dengan animasi halus
          if (card) {
            card.style.opacity = '0';
            card.style.transform = 'scale(0.9)';
            setTimeout(() => {
              card.remove();

              // Perbarui badge jumlah buku
              const remainingCards = document.querySelectorAll('.liked-book-card').length;
              if (countBadge) {
                countBadge.textContent = '(' + remainingCards + ' Buku)';
              }

              // Jika sudah tidak ada card tersisa, tampilkan empty state
              if (remainingCards === 0 && grid) {
                grid.outerHTML = `
                  <div style="background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 40px; text-align: center;">
                    <i class="fa-regular fa-heart" style="font-size: 2rem; color: #8b949e; margin-bottom: 12px;"></i>
                    <h4 style="color: var(--text-main); margin-bottom: 6px;">Belum Ada Buku Disukai</h4>
                    <p style="color: #8b949e; font-size: 0.9rem; margin-bottom: 15px;">Kamu belum menyukai buku apa pun.</p>
                    <a href="{{ route('home') }}" style="background-color: var(--member-accent); color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 500; display: inline-block;">
                      Jelajahi Buku di Beranda
                    </a>
                  </div>
                `;
              }
            }, 250);
          }
        } else {
          this.style.pointerEvents = 'auto';
          this.style.transform = 'scale(1)';
        }
      })
      .catch(err => {
        console.error('Unlike error:', err);
        this.style.pointerEvents = 'auto';
        this.style.transform = 'scale(1)';
      });
    });
  });
});
</script>
@endpush
