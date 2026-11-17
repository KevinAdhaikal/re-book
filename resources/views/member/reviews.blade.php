@extends('layouts.member')

@section('title', 'Ulasan Saya - Re:Book')

@section('member_content')
<div style="margin-bottom: 25px;">
  <h2 style="color: var(--text-main); font-size: 1.4rem; margin-bottom: 6px; display: flex; align-items: center; gap: 10px;">
    <i class="fa-solid fa-pen-to-square" style="color: var(--member-accent);"></i> Ulasan Saya
    <span style="font-size: 0.85rem; color: var(--text-muted); font-weight: normal;">({{ $myReviews->count() }} Ulasan)</span>
  </h2>
  <p style="color: var(--text-muted); font-size: 0.85rem;">
    Kelola seluruh ulasan dan penilaian buku yang pernah Anda tulis di komunitas Re:Book.
  </p>
</div>

<div style="max-width: 950px; margin-bottom: 40px;">
  @if($myReviews->isEmpty())
    <div style="background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 45px 20px; text-align: center;">
      <i class="fa-regular fa-comment-dots" style="font-size: 2.2rem; color: #8b949e; margin-bottom: 12px;"></i>
      <h4 style="color: var(--text-main); margin-bottom: 6px;">Belum Ada Ulasan</h4>
      <p style="color: #8b949e; font-size: 0.9rem; margin-bottom: 18px;">
        Anda belum pernah menulis ulasan buku apa pun. Pilih buku dari katalog dan bagikan pendapat Anda!
      </p>
      <a href="{{ route('home') }}" style="background-color: var(--member-accent); color: white; padding: 8px 18px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: inline-block;">
        Jelajahi Katalog Buku
      </a>
    </div>
  @else
    <div style="display: flex; flex-direction: column; gap: 20px;">
      @foreach($myReviews as $r)
        <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 10px; padding: 22px; display: flex; flex-direction: column; gap: 16px;">
          
          {{-- Header Review: Cover, Judul Buku, Rating, Tanggal --}}
          <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 15px; flex-wrap: wrap;">
            <div style="display: flex; gap: 16px; align-items: center;">
              {{-- Cover Thumbnail --}}
              @if($r->book && $r->book->cover)
                <img src="{{ asset('storage/' . $r->book->cover) }}" alt="{{ $r->book->title }}"
                     style="width: 55px; height: 80px; object-fit: cover; border-radius: 6px; border: 1px solid var(--border-color);">
              @else
                <div style="width: 55px; height: 80px; background: #0d1117; border: 1px solid var(--border-color); border-radius: 6px; display: flex; align-items: center; justify-content: center; color: var(--text-muted);">
                  <i class="fa-solid fa-book"></i>
                </div>
              @endif

              <div>
                <a href="{{ route('books.show', $r->book_id) }}" style="color: var(--text-main); font-size: 1.05rem; font-weight: 600; text-decoration: none;">
                  {{ $r->book->title ?? 'Buku Tidak Dikenal' }}
                </a>
                <p style="color: var(--text-muted); font-size: 0.82rem; margin: 3px 0 0 0;">
                  Penulis: <strong>{{ $r->book->author ?? '-' }}</strong> &bull; Genre: {{ $r->book->genre ?? '-' }}
                </p>
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 5px;">
                  <i class="fa-regular fa-clock"></i> Ditulis pada {{ $r->created_at ? $r->created_at->format('d M Y, H:i') : '-' }}
                  @if($r->updated_at && $r->updated_at != $r->created_at)
                    &bull; <span style="font-style: italic;">Diedit pada {{ $r->updated_at->format('d M Y, H:i') }}</span>
                  @endif
                </div>
              </div>
            </div>

            {{-- Rating Bintang --}}
            <div style="background: rgba(227, 179, 65, 0.12); border: 1px solid var(--star-rating); padding: 5px 12px; border-radius: 20px; color: var(--star-rating); font-weight: bold; font-size: 0.9rem; display: flex; align-items: center; gap: 6px;">
              <i class="fa-solid fa-star"></i> {{ $r->rating }}.0 / 5.0
            </div>
          </div>

          {{-- Konten Ulasan --}}
          <div style="background: rgba(255, 255, 255, 0.02); border-left: 3px solid var(--member-accent); padding: 12px 16px; border-radius: 6px; color: var(--text-main); font-size: 0.93rem; line-height: 1.6;">
            "{{ $r->content }}"
          </div>

          {{-- Balasan Komentar Info --}}
          @if($r->comments->count() > 0)
            <div style="font-size: 0.8rem; color: var(--text-muted);">
              <i class="fa-regular fa-comments"></i> {{ $r->comments->count() }} komentar balasan dari anggota komunitas
            </div>
          @endif

          {{-- Tombol Aksi: Lihat Buku, Edit, Hapus --}}
          <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid var(--border-color); padding-top: 14px; margin-top: 4px;">
            <a href="{{ route('books.show', $r->book_id) }}" style="background: #21262d; border: 1px solid var(--border-color); color: #58a6ff; padding: 7px 14px; border-radius: 6px; text-decoration: none; font-size: 0.82rem; font-weight: 600; display: flex; align-items: center; gap: 6px;">
              <i class="fa-solid fa-eye"></i> Lihat Buku
            </a>
            <button type="button" class="btn-open-edit"
              data-id="{{ $r->id }}"
              data-book-title="{{ $r->book->title ?? '' }}"
              data-rating="{{ $r->rating }}"
              data-content="{{ $r->content }}"
              data-action="{{ route('member.reviews.update', $r->id) }}"
              style="background: #21262d; border: 1px solid var(--border-color); color: var(--star-rating); padding: 7px 14px; border-radius: 6px; font-size: 0.82rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px;">
              <i class="fa-solid fa-pen"></i> Edit Ulasan
            </button>
            <button type="button" class="btn-open-delete"
              data-id="{{ $r->id }}"
              data-book-title="{{ $r->book->title ?? '' }}"
              data-action="{{ route('member.reviews.destroy', $r->id) }}"
              style="background: #21262d; border: 1px solid var(--border-color); color: #f85149; padding: 7px 14px; border-radius: 6px; font-size: 0.82rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px;">
              <i class="fa-solid fa-trash-can"></i> Hapus
            </button>
          </div>

        </div>
      @endforeach
    </div>
  @endif
</div>

{{-- MODAL EDIT ULASAN --}}
<div id="modal-edit-review" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
  <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 10px; width: 100%; max-width: 520px; padding: 25px; box-shadow: 0 10px 25px rgba(0,0,0,0.5);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">
      <h3 style="color: var(--text-main); font-size: 1.1rem; margin: 0; display: flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-pen-to-square" style="color: var(--star-rating);"></i> Edit Ulasan
      </h3>
      <button type="button" class="btn-close-modal" style="background: none; border: none; color: var(--text-muted); font-size: 1.2rem; cursor: pointer;">&times;</button>
    </div>

    <form id="form-edit-review" method="POST" action="">
      @csrf
      @method('PUT')

      <p id="edit-book-title" style="color: var(--member-accent); font-weight: 600; font-size: 0.9rem; margin-bottom: 15px;"></p>

      <div style="margin-bottom: 15px;">
        <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 6px;">Beri Rating Bintang</label>
        <select name="rating" id="edit-rating" required style="width: 100%; padding: 9px 12px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none; font-size: 0.9rem;">
          <option value="5">⭐⭐⭐⭐⭐ (5.0)</option>
          <option value="4">⭐⭐⭐⭐ (4.0)</option>
          <option value="3">⭐⭐⭐ (3.0)</option>
          <option value="2">⭐⭐ (2.0)</option>
          <option value="1">⭐ (1.0)</option>
        </select>
      </div>

      <div style="margin-bottom: 20px;">
        <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 6px;">Isi Ulasan</label>
        <textarea name="content" id="edit-content" rows="4" required style="width: 100%; padding: 12px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none; resize: vertical; font-size: 0.9rem; box-sizing: border-box;"></textarea>
      </div>

      <div style="display: flex; justify-content: flex-end; gap: 10px;">
        <button type="button" class="btn-close-modal" style="background: #21262d; border: 1px solid var(--border-color); color: var(--text-muted); padding: 8px 16px; border-radius: 6px; font-weight: 600; font-size: 0.85rem; cursor: pointer;">
          Batal
        </button>
        <button type="submit" style="background: var(--member-accent); color: white; border: none; padding: 8px 18px; border-radius: 6px; font-weight: 600; font-size: 0.85rem; cursor: pointer;">
          Simpan Perubahan
        </button>
      </div>
    </form>
  </div>
</div>

{{-- MODAL KONFIRMASI HAPUS ULASAN --}}
<div id="modal-delete-review" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
  <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 10px; width: 100%; max-width: 440px; padding: 25px; box-shadow: 0 10px 25px rgba(0,0,0,0.5); text-align: center;">
    <div style="width: 50px; height: 50px; border-radius: 50%; background: rgba(248, 81, 73, 0.15); color: #f85149; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; margin: 0 auto 15px auto;">
      <i class="fa-solid fa-triangle-exclamation"></i>
    </div>
    <h3 style="color: var(--text-main); font-size: 1.15rem; margin-bottom: 8px;">Hapus Ulasan Ini?</h3>
    <p id="delete-book-prompt" style="color: var(--text-muted); font-size: 0.88rem; line-height: 1.5; margin-bottom: 22px;">
      Apakah Anda yakin ingin menghapus ulasan ini? Tindakan ini tidak dapat dibatalkan dan rating buku akan diperbarui.
    </p>

    <form id="form-delete-review" method="POST" action="" style="display: flex; justify-content: center; gap: 10px;">
      @csrf
      @method('DELETE')
      <button type="button" class="btn-close-modal" style="background: #21262d; border: 1px solid var(--border-color); color: var(--text-muted); padding: 9px 18px; border-radius: 6px; font-weight: 600; font-size: 0.85rem; cursor: pointer;">
        Batal
      </button>
      <button type="submit" style="background: #da3633; color: white; border: none; padding: 9px 20px; border-radius: 6px; font-weight: 600; font-size: 0.85rem; cursor: pointer;">
        Ya, Hapus Ulasan
      </button>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const modalEdit = document.getElementById('modal-edit-review');
  const modalDelete = document.getElementById('modal-delete-review');
  const formEdit = document.getElementById('form-edit-review');
  const formDelete = document.getElementById('form-delete-review');
  const editTitle = document.getElementById('edit-book-title');
  const editRating = document.getElementById('edit-rating');
  const editContent = document.getElementById('edit-content');
  const deletePrompt = document.getElementById('delete-book-prompt');

  // Buka Modal Edit
  document.querySelectorAll('.btn-open-edit').forEach(button => {
    button.addEventListener('click', function () {
      formEdit.action = this.dataset.action;
      editTitle.textContent = 'Buku: ' + this.dataset.bookTitle;
      editRating.value = this.dataset.rating;
      editContent.value = this.dataset.content;
      modalEdit.style.display = 'flex';
    });
  });

  // Buka Modal Hapus
  document.querySelectorAll('.btn-open-delete').forEach(button => {
    button.addEventListener('click', function () {
      formDelete.action = this.dataset.action;
      deletePrompt.textContent = 'Apakah Anda yakin ingin menghapus ulasan untuk buku "' + this.dataset.bookTitle + '"? Tindakan ini tidak dapat dibatalkan.';
      modalDelete.style.display = 'flex';
    });
  });

  // Tutup Modal
  document.querySelectorAll('.btn-close-modal').forEach(button => {
    button.addEventListener('click', function () {
      modalEdit.style.display = 'none';
      modalDelete.style.display = 'none';
    });
  });

  // Tutup jika klik backdrop luar modal
  window.addEventListener('click', function (e) {
    if (e.target === modalEdit) modalEdit.style.display = 'none';
    if (e.target === modalDelete) modalDelete.style.display = 'none';
  });
});
</script>
@endpush
