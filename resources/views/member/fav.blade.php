@extends('layouts.member')

@section('title', 'Buku Favorit - Re:Book')

@push('styles')
<style>
  .fav-card-item {
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.3s ease;
  }
  .fav-card-item.fav-card-leaving {
    transform: scale(0.85);
    opacity: 0;
    pointer-events: none;
  }
  .fav-card-item.fav-card-entering {
    animation: favSlideDown 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
  }
  @keyframes favSlideDown {
    0% { opacity: 0; transform: translateY(-15px) scale(0.95); }
    100% { opacity: 1; transform: translateY(0) scale(1); }
  }
</style>
@endpush

@section('member_content')
<div style="margin-bottom: 25px;">
  <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: gap; gap: 15px;">
    <div>
      <h2 style="color: var(--text-main); font-size: 1.4rem; margin-bottom: 6px; display: flex; align-items: center; gap: 10px;">
        <i class="fa-solid fa-star" style="color: var(--star-rating);"></i> Showcase Buku Favorit Pilihan Saya
        <span id="fav-header-count" style="font-size: 0.85rem; color: var(--text-muted); font-weight: normal;">({{ $favoriteBooks->count() }}/4 Buku)</span>
      </h2>
      <p style="color: var(--text-muted); font-size: 0.85rem;">
        Pilih hingga 4 buku terbaik untuk ditampilkan sebagai koleksi unggulan di profil member Anda.
      </p>
    </div>
  </div>
</div>

{{-- Form Tambah Buku Favorit (Hanya jika belum mencapai batas 4 buku) --}}
<div id="fav-add-form-wrap" style="{{ $favoriteBooks->count() >= 4 ? 'display: none;' : '' }} background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 18px 20px; margin-bottom: 30px; max-width: 950px; transition: all 0.3s ease;">
  <h4 style="font-size: 0.95rem; margin-bottom: 12px; color: var(--text-main); display: flex; align-items: center; gap: 8px;">
    <i class="fa-solid fa-plus-circle" style="color: var(--member-accent);"></i> Tambah Buku ke Koleksi Favorit
  </h4>
  <form id="form-add-fav" action="{{ route('member.fav.store') }}" method="POST" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
    @csrf
    <select name="book_id" id="select-fav-book" required style="padding: 8px 12px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none; font-size: 0.9rem; flex: 1; min-width: 250px;">
      <option value="" disabled selected>-- Pilih Buku dari Katalog --</option>
      @foreach($availableBooks as $ab)
        @if(!$favoriteBooks->contains('id', $ab->id))
          <option value="{{ $ab->id }}" data-title="{{ $ab->title }}" data-author="{{ $ab->author }}" data-genre="{{ $ab->genre }}">{{ $ab->title }} — {{ $ab->author }} ({{ $ab->genre }})</option>
        @endif
      @endforeach
    </select>
    <button type="submit" id="btn-submit-fav" style="background: var(--member-accent); color: #fff; border: none; padding: 9px 18px; border-radius: 6px; font-weight: 600; font-size: 0.85rem; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s;">
      <i class="fa-solid fa-star"></i> <span>Simpan ke Favorit</span>
    </button>
  </form>
</div>

{{-- Banner Peringatan Kuota Penuh 4/4 --}}
<div id="fav-full-notice-wrap" style="{{ $favoriteBooks->count() < 4 ? 'display: none;' : '' }} background: rgba(230, 145, 56, 0.1); border: 1px solid var(--star-rating); border-radius: 8px; padding: 12px 18px; margin-bottom: 30px; max-width: 950px; font-size: 0.88rem; color: var(--star-rating); transition: all 0.3s ease;">
  <i class="fa-solid fa-circle-info"></i> Anda sudah memilih maksimal 4 buku favorit. Hapus salah satu buku di bawah jika ingin menggantinya dengan buku lain.
</div>

{{-- Grid 4 Buku Favorit --}}
<div style="max-width: 950px; margin-bottom: 40px;">
  <div id="fav-empty-state" style="{{ $favoriteBooks->isNotEmpty() ? 'display: none;' : '' }} background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 40px; text-align: center;">
    <i class="fa-regular fa-star" style="font-size: 2.2rem; color: #8b949e; margin-bottom: 12px;"></i>
    <h4 style="color: var(--text-main); margin-bottom: 6px;">Belum Ada Buku Favorit</h4>
    <p style="color: #8b949e; font-size: 0.9rem; margin-bottom: 15px;">
      Pilih hingga 4 buku favorit untuk ditampilkan pada showcase profil Anda.
    </p>
  </div>

  <div id="fav-books-grid" style="display: {{ $favoriteBooks->isEmpty() ? 'none' : 'grid' }}; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 20px;">
    @foreach($favoriteBooks as $book)
      <div id="fav-card-{{ $book->id }}" class="fav-card-item" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; position: relative;">

        {{-- Tombol Hapus dari Favorit (1-Klik AJAX) --}}
        <button type="button" class="btn-remove-fav" data-book-id="{{ $book->id }}" data-url="{{ route('member.fav.destroy', $book->id) }}" title="Hapus dari Buku Favorit"
          style="position: absolute; top: 10px; right: 10px; z-index: 99; background-color: #0d1117; border: 1px solid #30363d; width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--star-rating); font-size: 0.95rem; cursor: pointer; padding: 0; transition: transform 0.15s ease;">
          <i class="fa-solid fa-star"></i>
        </button>

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
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  const formAdd = document.getElementById('form-add-fav');
  const btnSubmit = document.getElementById('btn-submit-fav');
  const selectBook = document.getElementById('select-fav-book');
  const grid = document.getElementById('fav-books-grid');
  const emptyState = document.getElementById('fav-empty-state');
  const countBadge = document.getElementById('fav-header-count');
  const addFormWrap = document.getElementById('fav-add-form-wrap');
  const fullNoticeWrap = document.getElementById('fav-full-notice-wrap');

  // Helper untuk memasang listener tombol hapus favorit
  function bindRemoveButton(button) {
    button.onclick = async function () {
      const bookId = this.dataset.bookId;
      const url = this.dataset.url;
      const card = document.getElementById(`fav-card-${bookId}`);

      this.disabled = true;
      this.style.transform = 'scale(0.85)';

      try {
        const res = await fetch(url, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ _method: 'DELETE' })
        });

        const data = await res.json();
        if (res.ok && data.success) {
          window.showToast({ title: 'Favorit Dihapus', message: data.message, type: 'info' });

          if (countBadge) {
            countBadge.textContent = `(${data.count}/4 Buku)`;
          }

          // Tampilkan kembali form tambah jika kuota berkurang di bawah 4
          if (data.count < 4) {
            if (addFormWrap) addFormWrap.style.display = 'block';
            if (fullNoticeWrap) fullNoticeWrap.style.display = 'none';
          }

          // Kembalikan opsi buku ke dropdown select
          if (selectBook && data.book) {
            const opt = document.createElement('option');
            opt.value = data.book.id;
            opt.textContent = `${data.book.title} — ${data.book.author} (${data.book.genre})`;
            selectBook.appendChild(opt);
          }

          if (card) {
            card.classList.add('fav-card-leaving');
            setTimeout(() => {
              card.remove();
              if (data.count === 0) {
                if (grid) grid.style.display = 'none';
                if (emptyState) emptyState.style.display = 'block';
              }
            }, 300);
          }
        } else {
          throw new Error(data.message || 'Gagal menghapus dari Buku Favorit.');
        }
      } catch (err) {
        window.showToast({ title: 'Gagal', message: err.message, type: 'error' });
        this.disabled = false;
        this.style.transform = 'scale(1)';
      }
    };
  }

  // Pasang listener pada tombol hapus yang sudah ada
  document.querySelectorAll('.btn-remove-fav').forEach(bindRemoveButton);

  // Submit Form Tambah Favorit via AJAX
  if (formAdd) {
    formAdd.addEventListener('submit', async function (e) {
      e.preventDefault();

      const selectedOpt = selectBook.options[selectBook.selectedIndex];
      if (!selectedOpt || !selectedOpt.value) {
        window.showToast({ title: 'Peringatan', message: 'Silakan pilih buku terlebih dahulu.', type: 'error' });
        return;
      }

      btnSubmit.disabled = true;
      btnSubmit.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> <span>Menyimpan...</span>';

      try {
        const res = await fetch(formAdd.action, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ book_id: selectedOpt.value })
        });

        const data = await res.json();
        if (res.ok && data.success) {
          window.showToast({ title: 'Favorit Ditambahkan', message: data.message, type: 'success' });

          if (countBadge) {
            countBadge.textContent = `(${data.count}/4 Buku)`;
          }

          // Jika kuota penuh (4/4): sembunyikan form dan tampilkan banner kuota penuh
          if (data.count >= 4) {
            if (addFormWrap) addFormWrap.style.display = 'none';
            if (fullNoticeWrap) fullNoticeWrap.style.display = 'block';
          }

          // Hapus opsi buku dari dropdown
          selectedOpt.remove();
          selectBook.selectedIndex = 0;

          // Buat kartu buku baru di grid
          if (emptyState) emptyState.style.display = 'none';
          if (grid) {
            grid.style.display = 'grid';

            const newCard = document.createElement('div');
            newCard.id = `fav-card-${data.book.id}`;
            newCard.className = 'fav-card-item fav-card-entering';
            newCard.style.cssText = 'background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; position: relative;';

            newCard.innerHTML = `
              <button type="button" class="btn-remove-fav" data-book-id="${data.book.id}" data-url="${data.book.destroy_url}" title="Hapus dari Buku Favorit"
                style="position: absolute; top: 10px; right: 10px; z-index: 99; background-color: #0d1117; border: 1px solid #30363d; width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--star-rating); font-size: 0.95rem; cursor: pointer; padding: 0; transition: transform 0.15s ease;">
                <i class="fa-solid fa-star"></i>
              </button>

              <div>
                ${data.book.cover ? `
                  <img src="${data.book.cover}" alt="${data.book.title}" style="width: 100%; height: 250px; object-fit: cover; display: block;">
                ` : `
                  <div style="width: 100%; height: 250px; background: #161b22; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; color: var(--text-muted); border-bottom: 1px solid var(--border-color); text-align: center; padding: 15px;">
                    <i class="fa-solid fa-book" style="font-size: 2.2rem; color: #30363d;"></i>
                    <span style="font-size: 0.8rem; font-weight: 500; color: #8b949e;">${data.book.title.substring(0, 25)}</span>
                  </div>
                `}

                <div style="padding: 12px; display: flex; flex-direction: column;">
                  <div style="color: var(--star-rating); font-size: 0.9rem; margin-bottom: 6px;">
                    <i class="fa-solid fa-star"></i> ${data.book.rating.toFixed(1)}
                  </div>
                  <h4 style="color: var(--text-main); font-size: 0.95rem; font-weight: 600; margin: 0 0 4px 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="${data.book.title}">${data.book.title}</h4>
                  <p style="color: var(--text-muted); font-size: 0.8rem; margin: 0 0 12px 0;">${data.book.genre} &bull; ${data.book.author}</p>
                </div>
              </div>

              <div style="padding: 0 12px 12px 12px; display: flex; gap: 8px;">
                <a href="${data.book.url}" style="flex: 1; text-align: center; background: #21262D; color: var(--member-accent); font-size: 0.85rem; font-weight: 600; padding: 6px 0; border-radius: 6px; text-decoration: none;">
                  Lihat Ulasan
                </a>
              </div>
            `;

            grid.appendChild(newCard);
            const newBtn = newCard.querySelector('.btn-remove-fav');
            if (newBtn) bindRemoveButton(newBtn);
          }
        } else {
          throw new Error(data.message || 'Gagal menambahkan buku ke favorit.');
        }
      } catch (err) {
        window.showToast({ title: 'Gagal', message: err.message, type: 'error' });
      } finally {
        btnSubmit.disabled = false;
        btnSubmit.innerHTML = '<i class="fa-solid fa-star"></i> <span>Simpan ke Favorit</span>';
      }
    });
  }
});
</script>
@endpush
