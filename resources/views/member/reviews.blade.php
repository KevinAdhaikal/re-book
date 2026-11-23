@extends('layouts.member')

@section('title', 'Ulasan Saya - Re:Book')

@push('styles')
<style>
  .modal-star-item {
    color: #30363d;
    transition: transform 0.15s cubic-bezier(0.16, 1, 0.3, 1), color 0.15s ease, filter 0.15s ease;
    user-select: none;
  }
  .modal-star-item.active {
    color: var(--star-rating);
    filter: drop-shadow(0 0 7px rgba(227, 179, 65, 0.45));
  }
  .modal-star-item:hover {
    transform: scale(1.22);
  }
</style>
@endpush

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
    <div id="my-reviews-container" style="display: flex; flex-direction: column; gap: 20px;">
      @foreach($myReviews as $r)
        <div id="my-review-item-{{ $r->id }}" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 10px; padding: 22px; display: flex; flex-direction: column; gap: 16px; transition: all 0.3s ease;">
          
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
              <span class="review-stars-wrapper"><x-star-rating :rating="$r->rating" size="xs" :showScore="false" /></span>
              <span class="review-badge-score">{{ number_format($r->rating, 1) }} / 5.0</span>
            </div>
          </div>

          {{-- Konten Ulasan --}}
          <div class="review-content-text" style="background: rgba(255, 255, 255, 0.02); border-left: 3px solid var(--member-accent); padding: 12px 16px; border-radius: 6px; color: var(--text-main); font-size: 0.93rem; line-height: 1.6;">
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

      <div style="margin-bottom: 18px;">
        <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 8px; font-weight: 600;">
          Beri Rating Bintang (Arahkan atau Klik Bintang)
        </label>
        <div style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
          <div id="modal-interactive-star-group" style="display: flex; gap: 8px; font-size: 1.8rem; cursor: pointer;">
            <span class="modal-star-item active" data-star="1" title="1.0 atau 0.5 Bintang"><i class="fa-solid fa-star"></i></span>
            <span class="modal-star-item active" data-star="2" title="2.0 atau 1.5 Bintang"><i class="fa-solid fa-star"></i></span>
            <span class="modal-star-item active" data-star="3" title="3.0 atau 2.5 Bintang"><i class="fa-solid fa-star"></i></span>
            <span class="modal-star-item active" data-star="4" title="4.0 atau 3.5 Bintang"><i class="fa-solid fa-star"></i></span>
            <span class="modal-star-item active" data-star="5" title="5.0 atau 4.5 Bintang"><i class="fa-solid fa-star"></i></span>
          </div>
          <input type="hidden" name="rating" id="modal-edit-rating-input" value="5">
          <span id="modal-edit-rating-desc" style="font-size: 0.85rem; font-weight: 700; color: var(--star-rating); background: rgba(227, 179, 65, 0.12); border: 1px solid rgba(227, 179, 65, 0.3); padding: 5px 12px; border-radius: 6px; transition: all 0.2s;">
            5.0 — Mahakarya Sempurna
          </span>
        </div>
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
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  const modalEdit = document.getElementById('modal-edit-review');
  const modalDelete = document.getElementById('modal-delete-review');
  const formEdit = document.getElementById('form-edit-review');
  const formDelete = document.getElementById('form-delete-review');
  const editTitle = document.getElementById('edit-book-title');
  const editContent = document.getElementById('edit-content');
  const deletePrompt = document.getElementById('delete-book-prompt');

  const ratingLabels = {
    '0.5': '0.5 — Setengah Bintang / Sangat Buruk',
    '1.0': '1.0 — Buruk Sekali',
    '1.5': '1.5 — Kurang Memuaskan',
    '2.0': '2.0 — Cukup Kurang',
    '2.5': '2.5 — Biasa Saja / Rata-rata',
    '3.0': '3.0 — Lumayan Bagus',
    '3.5': '3.5 — Bagus & Menarik',
    '4.0': '4.0 — Sangat Bagus & Direkomendasikan',
    '4.5': '4.5 — Luar Biasa Keren',
    '5.0': '5.0 — Mahakarya Sempurna',
  };

  function setupStarPicker({ containerId, inputId, descId, initialScore = 5.0 }) {
    const container = document.getElementById(containerId);
    const input = document.getElementById(inputId);
    const desc = document.getElementById(descId);
    if (!container || !input) return null;

    const starElements = container.querySelectorAll('[data-star]');

    function renderStars(score) {
      score = Math.max(0.5, Math.min(5.0, Math.round(score * 2) / 2));
      const fullCount = Math.floor(score);
      const hasHalf = (score - fullCount) >= 0.5;

      starElements.forEach(star => {
        const starIndex = parseInt(star.dataset.star);
        const icon = star.querySelector('i');
        if (starIndex <= fullCount) {
          star.classList.add('active');
          if (icon) icon.className = 'fa-solid fa-star';
        } else if (starIndex === (fullCount + 1) && hasHalf) {
          star.classList.add('active');
          if (icon) icon.className = 'fa-solid fa-star-half-stroke';
        } else {
          star.classList.remove('active');
          if (icon) icon.className = 'fa-regular fa-star';
        }
      });

      const key = score.toFixed(1);
      if (desc && ratingLabels[key]) {
        desc.textContent = ratingLabels[key];
      }
    }

    function getScoreFromEvent(e, starEl) {
      const rect = starEl.getBoundingClientRect();
      const clickX = e.clientX - rect.left;
      const isLeft = clickX < (rect.width / 2);
      const starIndex = parseInt(starEl.dataset.star);
      return isLeft ? (starIndex - 0.5) : starIndex;
    }

    starElements.forEach(star => {
      star.addEventListener('mousemove', function (e) {
        const hoverScore = getScoreFromEvent(e, this);
        renderStars(hoverScore);
      });

      star.addEventListener('click', function (e) {
        const clickScore = getScoreFromEvent(e, this);
        input.value = clickScore.toFixed(1);
        renderStars(clickScore);
      });
    });

    container.addEventListener('mouseleave', function () {
      const currentVal = parseFloat(input.value) || 5.0;
      renderStars(currentVal);
    });

    renderStars(parseFloat(input.value) || initialScore);

    return {
      setScore(score) {
        input.value = parseFloat(score).toFixed(1);
        renderStars(score);
      }
    };
  }

  function generateStarIcons(score) {
    score = Math.max(0.5, Math.min(5.0, Math.round(score * 2) / 2));
    const fullCount = Math.floor(score);
    const hasHalf = (score - fullCount) >= 0.5;
    let html = '';
    for (let i = 1; i <= 5; i++) {
      if (i <= fullCount) {
        html += '<i class="fa-solid fa-star"></i> ';
      } else if (i === (fullCount + 1) && hasHalf) {
        html += '<i class="fa-solid fa-star-half-stroke"></i> ';
      } else {
        html += '<i class="fa-regular fa-star" style="color: #484f58;"></i> ';
      }
    }
    return html.trim();
  }

  const modalStarPicker = setupStarPicker({
    containerId: 'modal-interactive-star-group',
    inputId: 'modal-edit-rating-input',
    descId: 'modal-edit-rating-desc',
    initialScore: 5.0
  });

  // Buka Modal Edit
  document.querySelectorAll('.btn-open-edit').forEach(button => {
    button.addEventListener('click', function () {
      formEdit.action = this.dataset.action;
      formEdit.dataset.reviewId = this.dataset.id;
      editTitle.textContent = 'Buku: ' + this.dataset.bookTitle;
      if (modalStarPicker) {
        modalStarPicker.setScore(parseFloat(this.dataset.rating) || 5.0);
      }
      editContent.value = this.dataset.content;
      modalEdit.style.display = 'flex';
    });
  });

  // Submit Form Edit via AJAX
  if (formEdit) {
    formEdit.addEventListener('submit', async function (e) {
      e.preventDefault();
      const submitBtn = formEdit.querySelector('button[type="submit"]');
      const ratingInput = document.getElementById('modal-edit-rating-input');
      const reviewId = formEdit.dataset.reviewId;

      if (!editContent.value.trim() || editContent.value.trim().length < 5) {
        window.showToast({ title: 'Peringatan', message: 'Ulasan minimal 5 karakter.', type: 'error' });
        return;
      }

      submitBtn.disabled = true;
      submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';

      try {
        const res = await fetch(formEdit.action, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            _method: 'PUT',
            rating: ratingInput.value,
            content: editContent.value
          })
        });

        const data = await res.json();
        if (res.ok && data.success) {
          window.showToast({ title: 'Ulasan Diperbarui', message: data.message, type: 'success' });
          modalEdit.style.display = 'none';

          // Update card ulasan di halaman ini
          const itemCard = document.getElementById(`my-review-item-${reviewId}`);
          if (itemCard) {
            const starsWrapper = itemCard.querySelector('.review-stars-wrapper');
            const scoreBadge = itemCard.querySelector('.review-badge-score');
            const contentText = itemCard.querySelector('.review-content-text');
            const openEditBtn = itemCard.querySelector('.btn-open-edit');

            if (starsWrapper) starsWrapper.innerHTML = generateStarIcons(data.review.rating);
            if (scoreBadge) scoreBadge.textContent = `${parseFloat(data.review.rating).toFixed(1)} / 5.0`;
            if (contentText) contentText.textContent = `"${data.review.content}"`;
            if (openEditBtn) {
              openEditBtn.dataset.rating = data.review.rating;
              openEditBtn.dataset.content = data.review.content;
            }
          }
        } else {
          throw new Error(data.message || 'Gagal memperbarui ulasan.');
        }
      } catch (err) {
        window.showToast({ title: 'Gagal', message: err.message, type: 'error' });
      } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Simpan Perubahan';
      }
    });
  }

  // Buka Modal Hapus
  document.querySelectorAll('.btn-open-delete').forEach(button => {
    button.addEventListener('click', function () {
      formDelete.action = this.dataset.action;
      formDelete.dataset.reviewId = this.dataset.id;
      deletePrompt.textContent = 'Apakah Anda yakin ingin menghapus ulasan untuk buku "' + this.dataset.bookTitle + '"? Tindakan ini tidak dapat dibatalkan.';
      modalDelete.style.display = 'flex';
    });
  });

  // Submit Form Delete via AJAX
  if (formDelete) {
    formDelete.addEventListener('submit', async function (e) {
      e.preventDefault();
      const submitBtn = formDelete.querySelector('button[type="submit"]');
      const reviewId = formDelete.dataset.reviewId;

      submitBtn.disabled = true;
      submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menghapus...';

      try {
        const res = await fetch(formDelete.action, {
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
          window.showToast({ title: 'Ulasan Dihapus', message: data.message, type: 'info' });
          modalDelete.style.display = 'none';

          const itemCard = document.getElementById(`my-review-item-${reviewId}`);
          if (itemCard) {
            itemCard.style.transition = 'all 0.3s ease';
            itemCard.style.opacity = '0';
            itemCard.style.transform = 'translateY(10px)';
            setTimeout(() => {
              itemCard.remove();
              const container = document.getElementById('my-reviews-container');
              if (container && container.children.length === 0) {
                location.reload();
              }
            }, 300);
          }
        } else {
          throw new Error(data.message || 'Gagal menghapus ulasan.');
        }
      } catch (err) {
        window.showToast({ title: 'Gagal', message: err.message, type: 'error' });
      } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Ya, Hapus Ulasan';
      }
    });
  }

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
