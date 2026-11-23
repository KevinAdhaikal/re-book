@extends('layouts.app')

@section('title', $book->title . ' - Re:Book')

@section('content')
<div style="display: flex; gap: 30px; margin-bottom: 40px; flex-wrap: wrap;">
  <!-- Cover Buku -->
  @if($book->cover)
    <img src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->title }}"
         onerror="this.onerror=null; this.parentElement.querySelector('.cover-fallback').style.display='flex'; this.style.display='none';"
         style="width: 180px; height: 250px; object-fit: cover; border-radius: 6px;">
    <div class="cover-fallback" style="display: none; width: 180px; height: 250px; background: #161b22; border: 1px solid var(--border-color); border-radius: 6px; flex-direction: column; align-items: center; justify-content: center; gap: 8px; color: var(--text-muted); text-align: center; padding: 15px;">
      <i class="fa-solid fa-book" style="font-size: 2.2rem; color: #30363d;"></i>
      <span style="font-size: 0.8rem; font-weight: 500; color: #8b949e;">{{ Str::limit($book->title, 25) }}</span>
    </div>
  @else
    <div style="width: 180px; height: 250px; background: #161b22; border: 1px solid var(--border-color); border-radius: 6px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; color: var(--text-muted); text-align: center; padding: 15px;">
      <i class="fa-solid fa-book" style="font-size: 2.2rem; color: #30363d;"></i>
      <span style="font-size: 0.8rem; font-weight: 500; color: #8b949e;">{{ Str::limit($book->title, 25) }}</span>
    </div>
  @endif

  <!-- Info Detail Buku -->
  <div style="flex: 1; min-width: 300px;">
    <span style="background: #21262d; border: 1px solid var(--border-color); padding: 4px 10px; border-radius: 4px; font-size: 0.85rem;">{{ $book->genre }}</span>
    <h1 style="margin: 10px 0 6px 0;">{{ $book->title }}</h1>
    <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 15px;">Penulis: <strong>{{ $book->author }}</strong></p>
    
    <div style="color: var(--star-rating); font-size: 1.1rem; margin-bottom: 20px;">
      <i class="fa-solid fa-star"></i> {{ number_format($book->reviews->avg('rating') ?? 0, 1) }} / 5.0
      <span style="color: var(--text-muted); font-size: 0.85rem; margin-left: 8px;">({{ $book->reviews->count() }} ulasan)</span>
    </div>

    <p style="line-height: 1.6; color: var(--text-main); font-size: 0.95rem; margin-bottom: 20px;">{{ $book->description }}</p>

    @auth
      <div style="display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; align-items: center;">
        @php
          $isLiked = auth()->user()->hasLiked($book->id);
          $isFavorited = auth()->user()->hasFavorited($book->id);
          $shelfStatus = auth()->user()->shelfStatusFor($book->id);
        @endphp

        {{-- Tombol Sukai Buku (Optimistic UI) --}}
        <button type="button" id="btn-toggle-like"
                data-book-id="{{ $book->id }}"
                data-liked="{{ $isLiked ? 'true' : 'false' }}"
                data-url="{{ route('member.likes.toggle', $book->id) }}"
                style="background: {{ $isLiked ? 'rgba(247, 129, 102, 0.15)' : '#21262d' }};
                       border: 1px solid {{ $isLiked ? '#F78166' : 'var(--border-color)' }};
                       color: {{ $isLiked ? '#F78166' : 'var(--text-main)' }};
                       padding: 8px 14px; border-radius: 6px; font-size: 0.85rem; font-weight: 600;
                       cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.18s ease;">
          <i class="{{ $isLiked ? 'fa-solid' : 'fa-regular' }} fa-heart" id="icon-toggle-like"></i>
          <span id="text-toggle-like">{{ $isLiked ? 'Disukai' : 'Sukai Buku' }}</span>
        </button>

        {{-- Tombol Favorit Saya (Optimistic UI) --}}
        <button type="button" id="btn-toggle-fav"
                data-book-id="{{ $book->id }}"
                data-favorited="{{ $isFavorited ? 'true' : 'false' }}"
                data-url="{{ route('member.fav.toggle', $book->id) }}"
                style="background: {{ $isFavorited ? 'rgba(227, 179, 65, 0.15)' : '#21262d' }};
                       border: 1px solid {{ $isFavorited ? 'var(--star-rating)' : 'var(--border-color)' }};
                       color: {{ $isFavorited ? 'var(--star-rating)' : 'var(--text-main)' }};
                       padding: 8px 14px; border-radius: 6px; font-size: 0.85rem; font-weight: 600;
                       cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.18s ease;">
          <i class="{{ $isFavorited ? 'fa-solid' : 'fa-regular' }} fa-star" id="icon-toggle-fav"></i>
          <span id="text-toggle-fav">{{ $isFavorited ? 'Favorit Saya' : 'Tambah ke Favorit' }}</span>
        </button>

        {{-- Pill Popover Rak Bacaan (Want to Read / Currently Reading / Read) --}}
        @php
          $shelfLabels = [
            'want_to_read' => ['text' => 'Ingin Dibaca', 'icon' => '⏳', 'color' => '#58a6ff'],
            'currently_reading' => ['text' => 'Sedang Dibaca', 'icon' => '📖', 'color' => '#3fb950'],
            'read' => ['text' => 'Selesai Dibaca', 'icon' => '✅', 'color' => '#bc8cff'],
          ];
          $currentShelf = $shelfStatus && isset($shelfLabels[$shelfStatus]) ? $shelfLabels[$shelfStatus] : null;
        @endphp
        <div class="rebook-shelf-popover-wrap" style="position: relative; display: inline-block;">
          <button type="button" id="shelf-pill-btn"
                  data-status="{{ $shelfStatus ?? '' }}"
                  data-url-update="{{ route('member.shelf.update', $book->id) }}"
                  data-url-destroy="{{ route('member.shelf.destroy', $book->id) }}"
                  style="background: {{ $currentShelf ? 'rgba(88, 166, 255, 0.15)' : '#21262d' }};
                         border: 1px solid {{ $currentShelf ? ($currentShelf['color'] ?? '#58a6ff') : 'var(--border-color)' }};
                         color: {{ $currentShelf ? ($currentShelf['color'] ?? '#58a6ff') : 'var(--text-main)' }};
                         padding: 8px 14px; border-radius: 6px; font-size: 0.85rem; font-weight: 600;
                         cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.18s ease;">
            <span id="shelf-pill-icon">{{ $currentShelf ? $currentShelf['icon'] : '🔖' }}</span>
            <span id="shelf-pill-text">{{ $currentShelf ? $currentShelf['text'] : '+ Masukkan Rak' }}</span>
            <i class="fa-solid fa-chevron-down" style="font-size: 0.7rem; opacity: 0.7; margin-left: 2px;"></i>
          </button>

          {{-- Popover Menu --}}
          <div id="shelf-popover-menu"
               style="display: none; position: absolute; top: calc(100% + 6px); left: 0; min-width: 190px;
                      background: #161b22; border: 1px solid var(--border-color); border-radius: 8px;
                      box-shadow: 0 12px 28px rgba(0,0,0,0.6); z-index: 200; padding: 6px; flex-direction: column; gap: 2px;">
            <button type="button" class="shelf-menu-item {{ $shelfStatus === 'want_to_read' ? 'active' : '' }}" data-val="want_to_read">
              <span>⏳</span> Ingin Dibaca
            </button>
            <button type="button" class="shelf-menu-item {{ $shelfStatus === 'currently_reading' ? 'active' : '' }}" data-val="currently_reading">
              <span>📖</span> Sedang Dibaca
            </button>
            <button type="button" class="shelf-menu-item {{ $shelfStatus === 'read' ? 'active' : '' }}" data-val="read">
              <span>✅</span> Selesai Dibaca
            </button>
            <div id="shelf-menu-divider" style="height: 1px; background: var(--border-color); margin: 4px 0; {{ !$shelfStatus ? 'display: none;' : '' }}"></div>
            <button type="button" id="shelf-menu-remove" class="shelf-menu-item text-danger" data-val="remove" style="{{ !$shelfStatus ? 'display: none;' : '' }}">
              <span>❌</span> Hapus dari Rak
            </button>
          </div>
        </div>
      </div>
    @endauth
  </div>
</div>

<hr style="border-color: var(--border-color); margin-bottom: 30px;">

<!-- Form Tulis Ulasan (Khusus Member Login) -->
@auth
  @if(auth()->user()->role === 'member')
    @php
      $myReviewForThisBook = $book->reviews->where('user_id', auth()->id())->first();
    @endphp

    @if($myReviewForThisBook)
      <div id="my-review-card" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 20px; margin-bottom: 40px; transition: all 0.3s ease;">
        {{-- Mode Tampilan Normal --}}
        <div id="my-review-view-box" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
          <div>
            <h4 style="color: var(--text-main); font-size: 0.95rem; margin-bottom: 4px; display: flex; align-items: center; gap: 6px;">
              <i class="fa-solid fa-circle-check" style="color: #3fb950;"></i> Anda sudah mengulas buku ini
            </h4>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin: 0;">
              Nilai Anda: <strong style="color: var(--star-rating);">⭐ <span id="my-review-score-badge">{{ number_format($myReviewForThisBook->rating, 1) }}</span></strong> — "<span id="my-review-content-snippet">{{ Str::limit($myReviewForThisBook->content, 60) }}</span>"
            </p>
          </div>
          <div style="display: flex; align-items: center; gap: 10px;">
            <button type="button" id="btn-toggle-edit-my-review" style="background: #21262d; border: 1px solid var(--border-color); color: var(--star-rating); padding: 7px 14px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s;">
              <i class="fa-solid fa-pen-to-square"></i> Edit Ulasan
            </button>
            <a href="{{ route('member.reviews.index') }}" style="background: #21262d; border: 1px solid var(--border-color); color: var(--member-accent); padding: 7px 14px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: flex; align-items: center; gap: 6px;">
              <i class="fa-solid fa-gear"></i> Kelola di Ulasan Saya
            </a>
          </div>
        </div>

        {{-- Form Edit Inline (Default Hidden) --}}
        <div id="my-review-edit-box" style="display: none; padding-top: 5px;">
          <h4 style="color: var(--text-main); font-size: 0.95rem; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-pen-to-square" style="color: var(--star-rating);"></i> Edit Ulasan Anda
          </h4>
          <form id="form-edit-my-review" action="{{ route('member.reviews.update', $myReviewForThisBook->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div style="margin-bottom: 16px;">
              <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 8px; font-weight: 600;">
                Beri Rating Bintang (Arahkan atau Klik Bintang)
              </label>
              <div style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
                <div id="edit-star-group" style="display: flex; gap: 8px; font-size: 1.8rem; cursor: pointer;">
                  <span class="edit-star-item" data-star="1"><i class="fa-solid fa-star"></i></span>
                  <span class="edit-star-item" data-star="2"><i class="fa-solid fa-star"></i></span>
                  <span class="edit-star-item" data-star="3"><i class="fa-solid fa-star"></i></span>
                  <span class="edit-star-item" data-star="4"><i class="fa-solid fa-star"></i></span>
                  <span class="edit-star-item" data-star="5"><i class="fa-solid fa-star"></i></span>
                </div>
                <input type="hidden" name="rating" id="edit-rating-input" value="{{ $myReviewForThisBook->rating }}">
                <span id="edit-rating-desc" style="font-size: 0.85rem; font-weight: 700; color: var(--star-rating); background: rgba(227, 179, 65, 0.12); border: 1px solid rgba(227, 179, 65, 0.3); padding: 5px 12px; border-radius: 6px;">
                  {{ number_format($myReviewForThisBook->rating, 1) }}
                </span>
              </div>
            </div>

            <div style="margin-bottom: 15px;">
              <textarea name="content" id="edit-review-content-input" rows="3" required style="width: 100%; padding: 12px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none; resize: vertical; font-size: 0.9rem; box-sizing: border-box;">{{ $myReviewForThisBook->content }}</textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
              <button type="button" id="btn-cancel-edit-my-review" style="background: #21262d; border: 1px solid var(--border-color); color: var(--text-muted); padding: 8px 16px; border-radius: 6px; font-weight: 600; font-size: 0.85rem; cursor: pointer;">
                Batal
              </button>
              <button type="submit" id="btn-save-edit-my-review" style="background: var(--member-accent); color: #fff; border: none; padding: 8px 20px; border-radius: 6px; font-weight: 600; font-size: 0.85rem; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-check" id="icon-save-edit-my-review"></i>
                <span id="text-save-edit-my-review">Simpan Perubahan</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    @else
      <div id="review-form-wrapper" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 25px; margin-bottom: 40px; transition: all 0.3s ease;">
        <h3 style="margin-bottom: 15px;">✍️ Tulis Ulasan Kamu</h3>
        <form id="form-submit-review" action="{{ route('member.reviews.store') }}" method="POST">
          @csrf
          <input type="hidden" name="book_id" value="{{ $book->id }}">
          
          <div style="margin-bottom: 18px;">
            <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 8px; font-weight: 600;">
              Beri Rating Bintang (Arahkan atau Klik Bintang)
            </label>
            <div style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
              <div id="interactive-star-group" style="display: flex; gap: 8px; font-size: 1.8rem; cursor: pointer;">
                <span class="star-rating-item active" data-star="1" title="1.0 atau 0.5 Bintang"><i class="fa-solid fa-star"></i></span>
                <span class="star-rating-item active" data-star="2" title="2.0 atau 1.5 Bintang"><i class="fa-solid fa-star"></i></span>
                <span class="star-rating-item active" data-star="3" title="3.0 atau 2.5 Bintang"><i class="fa-solid fa-star"></i></span>
                <span class="star-rating-item active" data-star="4" title="4.0 atau 3.5 Bintang"><i class="fa-solid fa-star"></i></span>
                <span class="star-rating-item active" data-star="5" title="5.0 atau 4.5 Bintang"><i class="fa-solid fa-star"></i></span>
              </div>
              <input type="hidden" name="rating" id="review-rating-input" value="5">
              <span id="review-rating-desc" style="font-size: 0.85rem; font-weight: 700; color: var(--star-rating); background: rgba(227, 179, 65, 0.12); border: 1px solid rgba(227, 179, 65, 0.3); padding: 5px 12px; border-radius: 6px; transition: all 0.2s;">
                5.0 — Mahakarya Sempurna
              </span>
            </div>
          </div>

          <div style="margin-bottom: 15px;">
            <textarea name="content" id="review-content-input" rows="3" placeholder="Bagikan pendapatmu tentang alur cerita, karakter, atau gaya penulisan buku ini..." required style="width: 100%; padding: 12px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none; resize: vertical; font-size: 0.9rem; box-sizing: border-box;"></textarea>
          </div>

          <button type="submit" id="btn-submit-review" style="background: var(--member-accent); color: #fff; border: none; padding: 10px 22px; border-radius: 6px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;">
            <i class="fa-solid fa-paper-plane" id="icon-submit-review"></i>
            <span id="text-submit-review">Kirim Ulasan</span>
          </button>
        </form>
      </div>
    @endif
  @endif
@endauth

<!-- Daftar Ulasan Komunitas -->
<h3 style="margin-bottom: 20px;">💬 Ulasan Pembaca</h3>
<div id="reviews-list-container">
@forelse ($book->reviews as $r)
  <div class="review-item-card" id="review-card-{{ $r->id }}" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 22px; margin-bottom: 20px; transition: all 0.3s ease;">
    
    {{-- Header Ulasan --}}
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px; flex-wrap: wrap; gap: 10px;">
      <div style="display: flex; align-items: center; gap: 10px;">
        <a href="{{ $r->user ? route('users.profile', $r->user->id) : '#' }}" style="text-decoration: none; display: flex; align-items: center; gap: 10px;">
          <div style="width: 38px; height: 38px; background: var(--member-accent); color: #fff; border-radius: 50%; overflow: hidden; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1rem; border: 1px solid var(--border-color); flex-shrink: 0;">
            @if($r->user && $r->user->avatarUrl())
              <img src="{{ $r->user->avatarUrl() }}" alt="{{ $r->user->name }}" style="width: 100%; height: 100%; object-fit: cover;">
            @else
              {{ strtoupper(substr($r->user->name ?? 'M', 0, 1)) }}
            @endif
          </div>
          <div>
            <strong style="color: var(--text-main); font-size: 0.95rem; transition: color 0.2s;">{{ $r->user->name ?? 'Member' }}</strong>
            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 2px;">
              {{ $r->created_at ? $r->created_at->format('d M Y, H:i') : '' }}
            </div>
          </div>
        </a>
      </div>

      <div style="display: flex; align-items: center; gap: 10px;">
        <span class="review-rating-badge" style="color: var(--star-rating); font-weight: bold; font-size: 0.9rem; background: rgba(227, 179, 65, 0.12); padding: 4px 10px; border-radius: 15px; border: 1px solid var(--star-rating); display: inline-flex; align-items: center; gap: 6px;">
          <span class="review-stars-display"><x-star-rating :rating="$r->rating" size="xs" :showScore="false" /></span>
          <span class="review-score-text">{{ number_format($r->rating, 1) }} / 5.0</span>
        </span>

        {{-- Aksi Edit / Hapus jika ulasan milik sendiri --}}
        @if(auth()->check() && auth()->id() === $r->user_id)
          <button type="button" class="btn-trigger-inline-edit" title="Edit Ulasan Ini" style="background: #21262d; color: var(--star-rating); font-size: 0.8rem; border: 1px solid var(--border-color); border-radius: 4px; padding: 4px 8px; cursor: pointer; display: inline-flex; align-items: center; gap: 5px;">
            <i class="fa-solid fa-pen-to-square"></i> Edit
          </button>
        @elseif(auth()->check() && auth()->user()->role === 'admin')
          <a href="{{ route('admin.reviews.index') }}" title="Kelola di Admin" style="color: var(--text-muted); font-size: 0.8rem; text-decoration: none; padding: 4px 8px; border: 1px solid var(--border-color); border-radius: 4px;">
            <i class="fa-solid fa-gear"></i> Kelola
          </a>
        @endif
      </div>
    </div>

    {{-- Isi Ulasan --}}
    <p class="review-content-text" style="color: var(--text-main); font-size: 0.95rem; line-height: 1.6; margin-bottom: 12px; background: rgba(255, 255, 255, 0.02); padding: 12px 15px; border-radius: 6px; border-left: 3px solid var(--member-accent);">
      "{{ $r->content }}"
    </p>

    {{-- Like Ulasan / Membantu (Optimistic UI) --}}
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px; font-size: 0.85rem;">
      <div style="display: flex; align-items: center; gap: 10px;">
        @php
          $reviewLiked = auth()->check() ? auth()->user()->hasLikedReview($r->id) : false;
          $reviewLikesCount = $r->likedByUsers()->count();
        @endphp
        @auth
          <button type="button" class="btn-review-like-ajax"
                  data-review-id="{{ $r->id }}"
                  data-liked="{{ $reviewLiked ? 'true' : 'false' }}"
                  data-url="{{ route('member.reviews.like', $r->id) }}"
                  style="background: {{ $reviewLiked ? 'rgba(88, 166, 255, 0.15)' : '#21262d' }};
                         border: 1px solid {{ $reviewLiked ? '#58a6ff' : 'var(--border-color)' }};
                         color: {{ $reviewLiked ? '#58a6ff' : 'var(--text-muted)' }};
                         padding: 4px 12px; border-radius: 15px; font-size: 0.8rem; font-weight: 600;
                         cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.18s ease;">
            <i class="{{ $reviewLiked ? 'fa-solid' : 'fa-regular' }} fa-thumbs-up icon-like"></i>
            <span class="text-like">{{ $reviewLiked ? 'Membantu' : 'Ulasan Membantu?' }}</span>
            <span class="badge-like-count" style="background: rgba(255,255,255,0.12); padding: 1px 6px; border-radius: 10px; font-size: 0.75rem;">{{ $reviewLikesCount }}</span>
          </button>
        @else
          <a href="{{ route('login') }}" style="background: #21262d; border: 1px solid var(--border-color); color: var(--text-muted); padding: 4px 12px; border-radius: 15px; font-size: 0.8rem; text-decoration: none; display: flex; align-items: center; gap: 6px;">
            <i class="fa-regular fa-thumbs-up"></i>
            <span>Membantu</span>
            <span style="background: rgba(255,255,255,0.12); padding: 1px 6px; border-radius: 10px; font-size: 0.75rem;">{{ $reviewLikesCount }}</span>
          </a>
        @endauth
      </div>
    </div>

    {{-- Komentar Balasan pada Ulasan --}}
    <div style="border-top: 1px solid var(--border-color); padding-top: 15px;">
      <h5 style="color: var(--text-muted); margin-bottom: 12px; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.5px;">
        <i class="fa-regular fa-comments"></i> <span id="comments-count-{{ $r->id }}">{{ $r->comments->count() }}</span> Komentar
      </h5>

      <!-- List Komentar -->
      <div id="comments-list-{{ $r->id }}" style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 14px; padding-left: 12px; border-left: 2px solid var(--border-color);">
        @forelse ($r->comments as $c)
          <div id="detail-comment-item-{{ $c->id }}" class="comment-item-box" style="background: rgba(255, 255, 255, 0.03); padding: 8px 12px; border-radius: 6px; display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; transition: all 0.25s ease;">
            <div style="display: flex; align-items: flex-start; gap: 8px; flex: 1;">
              <a href="{{ $c->user ? route('users.profile', $c->user->id) : '#' }}" style="text-decoration: none; flex-shrink: 0;">
                <div style="width: 22px; height: 22px; border-radius: 50%; overflow: hidden; background: var(--member-accent); color: white; display: flex; align-items: center; justify-content: center; font-size: 0.65rem; font-weight: bold; margin-top: 2px; border: 1px solid var(--border-color);">
                  @if($c->user && $c->user->avatarUrl())
                    <img src="{{ $c->user->avatarUrl() }}" alt="{{ $c->user->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                  @else
                    {{ strtoupper(substr($c->user->name ?? 'M', 0, 1)) }}
                  @endif
                </div>
              </a>
              <div>
                <a href="{{ $c->user ? route('users.profile', $c->user->id) : '#' }}" style="font-weight: 600; font-size: 0.85rem; color: var(--member-accent); text-decoration: none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                  {{ $c->user->name ?? 'Member' }}:
                </a>
                <span style="font-size: 0.88rem; color: var(--text-main); margin-left: 5px;">{{ $c->comment }}</span>
                <div style="font-size: 0.72rem; color: var(--text-muted); margin-top: 2px;">
                  {{ $c->created_at ? $c->created_at->diffForHumans() : '' }}
                </div>
              </div>
            </div>

            @if(auth()->check() && (auth()->id() === $c->user_id || auth()->user()->role === 'admin'))
              <button type="button" class="btn-delete-comment-ajax" data-id="{{ $c->id }}" data-url="{{ route('member.comments.destroy', $c->id) }}" data-review-id="{{ $r->id }}" title="Hapus Komentar" style="background: none; border: none; color: #8b949e; font-size: 0.75rem; cursor: pointer; padding: 2px 4px; transition: color 0.15s ease;" onmouseover="this.style.color='#f85149'" onmouseout="this.style.color='#8b949e'">
                <i class="fa-solid fa-trash-can"></i>
              </button>
            @endif
          </div>
        @empty
          <p class="empty-comments-text" style="font-size: 0.82rem; color: var(--text-muted); font-style: italic; margin: 0;">Belum ada komentar balasan.</p>
        @endforelse
      </div>

      <!-- Form Komentar (AJAX) -->
      @auth
        <form class="form-comment-ajax" action="{{ route('member.comments.store', $r->id) }}" method="POST" data-review-id="{{ $r->id }}" style="display: flex; gap: 8px; margin-top: 8px;">
          @csrf
          <input type="text" name="comment" placeholder="Tulis komentar balasan..." required style="flex: 1; padding: 7px 12px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; font-size: 0.85rem; outline: none;">
          <button type="submit" style="background: var(--member-accent); color: white; border: none; padding: 7px 14px; border-radius: 6px; font-weight: 600; font-size: 0.82rem; cursor: pointer; transition: opacity 0.2s;">Kirim</button>
        </form>
      @else
        <p style="font-size: 0.8rem; color: var(--text-muted); margin: 0;">
          <a href="{{ route('login') }}" style="color: var(--member-accent); text-decoration: underline;">Login</a> untuk membalas ulasan ini.
        </p>
      @endauth
    </div>

  </div>
@empty
  <p id="empty-reviews-notice" style="color: var(--text-muted);">Belum ada ulasan untuk buku ini. Jadilah yang pertama mengulas!</p>
@endforelse
</div>
@endsection

@push('styles')
<style>
  /* Star Rating Picker */
  .star-rating-item, .edit-star-item {
    color: #30363d;
    transition: transform 0.15s cubic-bezier(0.16, 1, 0.3, 1), color 0.15s ease, filter 0.15s ease;
    user-select: none;
  }
  .star-rating-item.active, .edit-star-item.active {
    color: var(--star-rating);
    filter: drop-shadow(0 0 7px rgba(227, 179, 65, 0.45));
  }
  .star-rating-item:hover, .edit-star-item:hover {
    transform: scale(1.22);
  }

  /* Shelf Popover Menu */
  .shelf-menu-item {
    width: 100%;
    text-align: left;
    background: transparent;
    border: none;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 0.85rem;
    color: var(--text-main);
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
    transition: background 0.15s ease, color 0.15s ease;
  }
  .shelf-menu-item:hover {
    background: rgba(255, 255, 255, 0.08);
  }
  .shelf-menu-item.active {
    background: rgba(88, 166, 255, 0.15);
    color: #58a6ff;
    font-weight: 600;
  }
  .shelf-menu-item.text-danger {
    color: #f85149;
  }
  .shelf-menu-item.text-danger:hover {
    background: rgba(248, 81, 73, 0.12);
  }

  .shelf-popover-open {
    display: flex !important;
    animation: shelfPopoverFade 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
  }
  @keyframes shelfPopoverFade {
    0% { opacity: 0; transform: translateY(-8px) scale(0.96); }
    100% { opacity: 1; transform: translateY(0) scale(1); }
  }

  /* Animasi Insert Review Baru */
  @keyframes reviewSlideDown {
    0% {
      opacity: 0;
      transform: translateY(-16px);
      box-shadow: 0 0 0 2px var(--member-accent);
    }
    100% {
      opacity: 1;
      transform: translateY(0);
      box-shadow: none;
    }
  }
  .review-newly-added {
    animation: reviewSlideDown 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    border-color: #388bfd !important;
  }

  /* Animasi Komentar Balasan */
  @keyframes commentSlideIn {
    from {
      opacity: 0;
      transform: translateY(-8px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  .comment-item-entering {
    animation: commentSlideIn 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
  }
  .comment-item-leaving {
    opacity: 0 !important;
    transform: translateX(-15px) !important;
    max-height: 0 !important;
    padding-top: 0 !important;
    padding-bottom: 0 !important;
    margin-top: 0 !important;
    margin-bottom: 0 !important;
    overflow: hidden !important;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
  }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

  // ==========================================
  // 1. STAR RATING PICKER INTERAKTIF (HALF-STAR: 0.5 - 5.0) & INLINE EDIT
  // ==========================================
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

    // Initial render
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

  // Inisialisasi Star Picker Create Review
  const createStarPicker = setupStarPicker({
    containerId: 'interactive-star-group',
    inputId: 'review-rating-input',
    descId: 'review-rating-desc',
    initialScore: 5.0
  });

  // Inisialisasi Star Picker Edit Review
  let editStarPicker = setupStarPicker({
    containerId: 'edit-star-group',
    inputId: 'edit-rating-input',
    descId: 'edit-rating-desc',
    initialScore: parseFloat(document.getElementById('edit-rating-input')?.value || 5.0)
  });

  // Logic Toggle & AJAX Edit Ulasan Inline
  function setupInlineEditHandlers() {
    const btnToggleEdit = document.getElementById('btn-toggle-edit-my-review');
    const btnCancelEdit = document.getElementById('btn-cancel-edit-my-review');
    const viewBox = document.getElementById('my-review-view-box');
    const editBox = document.getElementById('my-review-edit-box');
    const formEdit = document.getElementById('form-edit-my-review');

    if (btnToggleEdit && viewBox && editBox) {
      btnToggleEdit.onclick = function () {
        viewBox.style.display = 'none';
        editBox.style.display = 'block';
        const ratingInput = document.getElementById('edit-rating-input');
        if (ratingInput && editStarPicker) {
          editStarPicker.setScore(parseFloat(ratingInput.value) || 5.0);
        }
      };
    }

    if (btnCancelEdit && viewBox && editBox) {
      btnCancelEdit.onclick = function () {
        editBox.style.display = 'none';
        viewBox.style.display = 'flex';
      };
    }

    // Tombol Edit di kartu ulasan sendiri pada daftar di bawah
    document.querySelectorAll('.btn-trigger-inline-edit').forEach(btn => {
      btn.onclick = function () {
        const myReviewCard = document.getElementById('my-review-card');
        if (myReviewCard) {
          myReviewCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
          if (btnToggleEdit) btnToggleEdit.click();
        }
      };
    });

    if (formEdit) {
      formEdit.onsubmit = async function (e) {
        e.preventDefault();
        const btnSave = document.getElementById('btn-save-edit-my-review');
        const iconSave = document.getElementById('icon-save-edit-my-review');
        const textSave = document.getElementById('text-save-edit-my-review');
        const contentInput = document.getElementById('edit-review-content-input');
        const ratingInput = document.getElementById('edit-rating-input');

        if (!contentInput.value.trim() || contentInput.value.trim().length < 5) {
          window.showToast({ title: 'Peringatan', message: 'Ulasan minimal 5 karakter.', type: 'error' });
          return;
        }

        btnSave.disabled = true;
        btnSave.style.opacity = '0.7';
        if (iconSave) iconSave.className = 'fa-solid fa-spinner fa-spin';
        if (textSave) textSave.textContent = 'Menyimpan...';

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
              content: contentInput.value
            })
          });

          const data = await res.json();
          if (res.ok && data.success) {
            window.showToast({ title: 'Ulasan Diperbarui', message: data.message, type: 'success' });

            // Update mode tampilan normal di atas
            const scoreBadge = document.getElementById('my-review-score-badge');
            const contentSnippet = document.getElementById('my-review-content-snippet');
            if (scoreBadge) scoreBadge.textContent = parseFloat(data.review.rating).toFixed(1);
            if (contentSnippet) contentSnippet.textContent = data.review.content.substring(0, 60);

            // Update kartu ulasan di daftar ulasan pembaca bawah
            const reviewCard = document.getElementById(`review-card-${data.review.id}`);
            if (reviewCard) {
              const starsDisplay = reviewCard.querySelector('.review-stars-display');
              const scoreText = reviewCard.querySelector('.review-score-text');
              const contentText = reviewCard.querySelector('.review-content-text');

              if (starsDisplay) starsDisplay.innerHTML = generateStarIcons(data.review.rating);
              if (scoreText) scoreText.textContent = `${parseFloat(data.review.rating).toFixed(1)} / 5.0`;
              if (contentText) contentText.textContent = `"${data.review.content}"`;
            }

            // Kembalikan ke mode normal
            editBox.style.display = 'none';
            viewBox.style.display = 'flex';
          } else {
            throw new Error(data.message || 'Gagal menyimpan ulasan.');
          }
        } catch (err) {
          window.showToast({ title: 'Gagal', message: err.message, type: 'error' });
        } finally {
          btnSave.disabled = false;
          btnSave.style.opacity = '1';
          if (iconSave) iconSave.className = 'fa-solid fa-check';
          if (textSave) textSave.textContent = 'Simpan Perubahan';
        }
      };
    }
  }

  setupInlineEditHandlers();

  // ==========================================
  // 2. OPTIMISTIC UI: LIKE BUKU
  // ==========================================
  const btnLike = document.getElementById('btn-toggle-like');
  if (btnLike) {
    btnLike.addEventListener('click', async function () {
      const isCurrentlyLiked = btnLike.dataset.liked === 'true';
      const newLikedState = !isCurrentlyLiked;
      const url = btnLike.dataset.url;

      // Optimistic visual change
      btnLike.dataset.liked = newLikedState ? 'true' : 'false';
      const iconLike = document.getElementById('icon-toggle-like');
      const textLike = document.getElementById('text-toggle-like');

      if (newLikedState) {
        btnLike.style.background = 'rgba(247, 129, 102, 0.15)';
        btnLike.style.borderColor = '#F78166';
        btnLike.style.color = '#F78166';
        if (iconLike) { iconLike.className = 'fa-solid fa-heart'; }
        if (textLike) { textLike.textContent = 'Disukai'; }
      } else {
        btnLike.style.background = '#21262d';
        btnLike.style.borderColor = 'var(--border-color)';
        btnLike.style.color = 'var(--text-main)';
        if (iconLike) { iconLike.className = 'fa-regular fa-heart'; }
        if (textLike) { textLike.textContent = 'Sukai Buku'; }
      }

      try {
        const res = await fetch(url, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
          }
        });
        const data = await res.json();
        if (data.success) {
          window.showToast({
            title: newLikedState ? 'Disukai' : 'Batal Suka',
            message: data.message,
            type: 'success'
          });
        } else {
          throw new Error(data.message || 'Gagal mengubah status like.');
        }
      } catch (err) {
        // Rollback
        btnLike.dataset.liked = isCurrentlyLiked ? 'true' : 'false';
        if (isCurrentlyLiked) {
          btnLike.style.background = 'rgba(247, 129, 102, 0.15)';
          btnLike.style.borderColor = '#F78166';
          btnLike.style.color = '#F78166';
          if (iconLike) { iconLike.className = 'fa-solid fa-heart'; }
          if (textLike) { textLike.textContent = 'Disukai'; }
        } else {
          btnLike.style.background = '#21262d';
          btnLike.style.borderColor = 'var(--border-color)';
          btnLike.style.color = 'var(--text-main)';
          if (iconLike) { iconLike.className = 'fa-regular fa-heart'; }
          if (textLike) { textLike.textContent = 'Sukai Buku'; }
        }
        window.showToast({ title: 'Gagal', message: err.message, type: 'error' });
      }
    });
  }

  // ==========================================
  // 3. OPTIMISTIC UI: FAVORIT BUKU
  // ==========================================
  const btnFav = document.getElementById('btn-toggle-fav');
  if (btnFav) {
    btnFav.addEventListener('click', async function () {
      const isCurrentlyFav = btnFav.dataset.favorited === 'true';
      const newFavState = !isCurrentlyFav;
      const url = btnFav.dataset.url;

      btnFav.dataset.favorited = newFavState ? 'true' : 'false';
      const iconFav = document.getElementById('icon-toggle-fav');
      const textFav = document.getElementById('text-toggle-fav');

      if (newFavState) {
        btnFav.style.background = 'rgba(227, 179, 65, 0.15)';
        btnFav.style.borderColor = 'var(--star-rating)';
        btnFav.style.color = 'var(--star-rating)';
        if (iconFav) { iconFav.className = 'fa-solid fa-star'; }
        if (textFav) { textFav.textContent = 'Favorit Saya'; }
      } else {
        btnFav.style.background = '#21262d';
        btnFav.style.borderColor = 'var(--border-color)';
        btnFav.style.color = 'var(--text-main)';
        if (iconFav) { iconFav.className = 'fa-regular fa-star'; }
        if (textFav) { textFav.textContent = 'Tambah ke Favorit'; }
      }

      try {
        const res = await fetch(url, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
          }
        });
        const data = await res.json();
        if (data.success) {
          window.showToast({
            title: newFavState ? 'Favorit Ditambahkan' : 'Dihapus dari Favorit',
            message: data.message,
            type: 'success'
          });
        } else {
          throw new Error(data.message || 'Gagal mengubah favorit.');
        }
      } catch (err) {
        btnFav.dataset.favorited = isCurrentlyFav ? 'true' : 'false';
        if (isCurrentlyFav) {
          btnFav.style.background = 'rgba(227, 179, 65, 0.15)';
          btnFav.style.borderColor = 'var(--star-rating)';
          btnFav.style.color = 'var(--star-rating)';
          if (iconFav) { iconFav.className = 'fa-solid fa-star'; }
          if (textFav) { textFav.textContent = 'Favorit Saya'; }
        } else {
          btnFav.style.background = '#21262d';
          btnFav.style.borderColor = 'var(--border-color)';
          btnFav.style.color = 'var(--text-main)';
          if (iconFav) { iconFav.className = 'fa-regular fa-star'; }
          if (textFav) { textFav.textContent = 'Tambah ke Favorit'; }
        }
        window.showToast({ title: 'Gagal', message: err.message, type: 'error' });
      }
    });
  }

  // ==========================================
  // 4. PILL BADGE POPOVER RAK BACAAN
  // ==========================================
  const shelfBtn = document.getElementById('shelf-pill-btn');
  const shelfPopover = document.getElementById('shelf-popover-menu');
  const shelfDivider = document.getElementById('shelf-menu-divider');
  const shelfRemoveBtn = document.getElementById('shelf-menu-remove');

  if (shelfBtn && shelfPopover) {
    shelfBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      shelfPopover.classList.toggle('shelf-popover-open');
    });

    document.addEventListener('click', function (e) {
      if (!shelfPopover.contains(e.target) && e.target !== shelfBtn) {
        shelfPopover.classList.remove('shelf-popover-open');
      }
    });

    const shelfItemBtns = shelfPopover.querySelectorAll('.shelf-menu-item');
    shelfItemBtns.forEach(item => {
      item.addEventListener('click', async function () {
        const selectedVal = this.dataset.val;
        shelfPopover.classList.remove('shelf-popover-open');

        const prevStatus = shelfBtn.dataset.status;
        const urlUpdate = shelfBtn.dataset.urlUpdate;
        const urlDestroy = shelfBtn.dataset.urlDestroy;

        const shelfConfig = {
          want_to_read: { icon: '⏳', text: 'Ingin Dibaca', color: '#58a6ff' },
          currently_reading: { icon: '📖', text: 'Sedang Dibaca', color: '#3fb950' },
          read: { icon: '✅', text: 'Selesai Dibaca', color: '#bc8cff' }
        };

        const iconEl = document.getElementById('shelf-pill-icon');
        const textEl = document.getElementById('shelf-pill-text');

        if (selectedVal === 'remove') {
          // Optimistic remove
          shelfBtn.dataset.status = '';
          shelfBtn.style.background = '#21262d';
          shelfBtn.style.borderColor = 'var(--border-color)';
          shelfBtn.style.color = 'var(--text-main)';
          if (iconEl) iconEl.textContent = '🔖';
          if (textEl) textEl.textContent = '+ Masukkan Rak';
          if (shelfDivider) shelfDivider.style.display = 'none';
          if (shelfRemoveBtn) shelfRemoveBtn.style.display = 'none';
          shelfItemBtns.forEach(b => b.classList.remove('active'));

          try {
            const res = await fetch(urlDestroy, {
              method: 'DELETE',
              headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
              }
            });
            const data = await res.json();
            window.showToast({ title: 'Rak Diperbarui', message: data.message, type: 'info' });
          } catch (err) {
            window.showToast({ title: 'Gagal', message: 'Gagal menghapus dari rak.', type: 'error' });
          }
        } else {
          // Optimistic update
          const cfg = shelfConfig[selectedVal];
          shelfBtn.dataset.status = selectedVal;
          shelfBtn.style.background = 'rgba(88, 166, 255, 0.15)';
          shelfBtn.style.borderColor = cfg.color;
          shelfBtn.style.color = cfg.color;
          if (iconEl) iconEl.textContent = cfg.icon;
          if (textEl) textEl.textContent = cfg.text;
          if (shelfDivider) shelfDivider.style.display = 'block';
          if (shelfRemoveBtn) shelfRemoveBtn.style.display = 'flex';

          shelfItemBtns.forEach(b => {
            if (b.dataset.val === selectedVal) {
              b.classList.add('active');
            } else {
              b.classList.remove('active');
            }
          });

          try {
            const res = await fetch(urlUpdate, {
              method: 'POST',
              headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
              },
              body: JSON.stringify({ status: selectedVal })
            });
            const data = await res.json();
            if (data.success) {
              window.showToast({ title: 'Rak Diperbarui', message: data.message, type: 'success' });
            } else {
              throw new Error(data.message);
            }
          } catch (err) {
            window.showToast({ title: 'Gagal', message: err.message || 'Gagal memperbarui rak.', type: 'error' });
          }
        }
      });
    });
  }

  // ==========================================
  // 5. AJAX FORM PENGIRIMAN ULASAN
  // ==========================================
  const reviewForm = document.getElementById('form-submit-review');
  if (reviewForm) {
    reviewForm.addEventListener('submit', async function (e) {
      e.preventDefault();

      const btnSubmit = document.getElementById('btn-submit-review');
      const btnText = document.getElementById('text-submit-review');
      const btnIcon = document.getElementById('icon-submit-review');
      const contentInput = document.getElementById('review-content-input');
      const ratingInput = document.getElementById('review-rating-input');

      if (!contentInput || !contentInput.value.trim() || contentInput.value.trim().length < 5) {
        window.showToast({ title: 'Peringatan', message: 'Ulasan minimal 5 karakter.', type: 'error' });
        return;
      }

      // Disable state
      if (btnSubmit) {
        btnSubmit.disabled = true;
        btnSubmit.style.opacity = '0.7';
      }
      if (btnIcon) btnIcon.className = 'fa-solid fa-spinner fa-spin';
      if (btnText) btnText.textContent = 'Menerbitkan...';

      try {
        const formData = new FormData(reviewForm);
        // Pastikan nilai rating terkirim dari input
        if (ratingInput && ratingInput.value) {
          formData.set('rating', ratingInput.value);
        }
        const res = await fetch(reviewForm.action, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          },
          body: formData
        });

        const data = await res.json();

        if (res.ok && data.success) {
          window.showToast({
            title: 'Ulasan Terbit',
            message: data.message,
            type: 'success'
          });

          // Ubah form ulasan menjadi card alert yang mendukung mode view & edit inline
          const formWrapper = document.getElementById('review-form-wrapper');
          if (formWrapper) {
            formWrapper.outerHTML = `
              <div id="my-review-card" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 20px; margin-bottom: 40px; transition: all 0.3s ease;">
                <div id="my-review-view-box" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                  <div>
                    <h4 style="color: var(--text-main); font-size: 0.95rem; margin-bottom: 4px; display: flex; align-items: center; gap: 6px;">
                      <i class="fa-solid fa-circle-check" style="color: #3fb950;"></i> Anda sudah mengulas buku ini
                    </h4>
                    <p style="color: var(--text-muted); font-size: 0.85rem; margin: 0;">
                      Nilai Anda: <strong style="color: var(--star-rating);">⭐ <span id="my-review-score-badge">${parseFloat(data.review.rating).toFixed(1)}</span></strong> — "<span id="my-review-content-snippet">${data.review.content.substring(0, 60)}</span>"
                    </p>
                  </div>
                  <div style="display: flex; align-items: center; gap: 10px;">
                    <button type="button" id="btn-toggle-edit-my-review" style="background: #21262d; border: 1px solid var(--border-color); color: var(--star-rating); padding: 7px 14px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s;">
                      <i class="fa-solid fa-pen-to-square"></i> Edit Ulasan
                    </button>
                    <a href="{{ route('member.reviews.index') }}" style="background: #21262d; border: 1px solid var(--border-color); color: var(--member-accent); padding: 7px 14px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: flex; align-items: center; gap: 6px;">
                      <i class="fa-solid fa-gear"></i> Kelola di Ulasan Saya
                    </a>
                  </div>
                </div>

                <div id="my-review-edit-box" style="display: none; padding-top: 5px;">
                  <h4 style="color: var(--text-main); font-size: 0.95rem; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-pen-to-square" style="color: var(--star-rating);"></i> Edit Ulasan Anda
                  </h4>
                  <form id="form-edit-my-review" action="/member/reviews/${data.review.id}" method="POST">
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <input type="hidden" name="_method" value="PUT">
                    <div style="margin-bottom: 16px;">
                      <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 8px; font-weight: 600;">
                        Beri Rating Bintang (Arahkan atau Klik Bintang)
                      </label>
                      <div style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
                        <div id="edit-star-group" style="display: flex; gap: 8px; font-size: 1.8rem; cursor: pointer;">
                          <span class="edit-star-item" data-star="1"><i class="fa-solid fa-star"></i></span>
                          <span class="edit-star-item" data-star="2"><i class="fa-solid fa-star"></i></span>
                          <span class="edit-star-item" data-star="3"><i class="fa-solid fa-star"></i></span>
                          <span class="edit-star-item" data-star="4"><i class="fa-solid fa-star"></i></span>
                          <span class="edit-star-item" data-star="5"><i class="fa-solid fa-star"></i></span>
                        </div>
                        <input type="hidden" name="rating" id="edit-rating-input" value="${data.review.rating}">
                        <span id="edit-rating-desc" style="font-size: 0.85rem; font-weight: 700; color: var(--star-rating); background: rgba(227, 179, 65, 0.12); border: 1px solid rgba(227, 179, 65, 0.3); padding: 5px 12px; border-radius: 6px;">
                          ${parseFloat(data.review.rating).toFixed(1)}
                        </span>
                      </div>
                    </div>

                    <div style="margin-bottom: 15px;">
                      <textarea name="content" id="edit-review-content-input" rows="3" required style="width: 100%; padding: 12px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none; resize: vertical; font-size: 0.9rem; box-sizing: border-box;">${data.review.content}</textarea>
                    </div>

                    <div style="display: flex; justify-content: flex-end; gap: 10px;">
                      <button type="button" id="btn-cancel-edit-my-review" style="background: #21262d; border: 1px solid var(--border-color); color: var(--text-muted); padding: 8px 16px; border-radius: 6px; font-weight: 600; font-size: 0.85rem; cursor: pointer;">
                        Batal
                      </button>
                      <button type="submit" id="btn-save-edit-my-review" style="background: var(--member-accent); color: #fff; border: none; padding: 8px 20px; border-radius: 6px; font-weight: 600; font-size: 0.85rem; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-check" id="icon-save-edit-my-review"></i>
                        <span id="text-save-edit-my-review">Simpan Perubahan</span>
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            `;

            editStarPicker = setupStarPicker({
              containerId: 'edit-star-group',
              inputId: 'edit-rating-input',
              descId: 'edit-rating-desc',
              initialScore: parseFloat(data.review.rating)
            });
            setupInlineEditHandlers();
          }

          // Prepend card ulasan baru ke review container
          const listContainer = document.getElementById('reviews-list-container');
          const emptyNotice = document.getElementById('empty-reviews-notice');
          if (emptyNotice) emptyNotice.remove();

          if (listContainer) {
            const newCard = document.createElement('div');
            newCard.className = 'review-item-card review-newly-added';
            newCard.id = `review-card-${data.review.id}`;
            newCard.style.cssText = 'background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 22px; margin-bottom: 20px;';

            newCard.innerHTML = `
              <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px; flex-wrap: wrap; gap: 10px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                  <a href="${data.review.user_profile_url}" style="text-decoration: none; display: flex; align-items: center; gap: 10px;">
                    <div style="width: 38px; height: 38px; background: var(--member-accent); color: #fff; border-radius: 50%; overflow: hidden; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1rem; border: 1px solid var(--border-color); flex-shrink: 0;">
                      ${data.review.user_avatar ? `<img src="${data.review.user_avatar}" style="width: 100%; height: 100%; object-fit: cover;">` : data.review.user_name.substring(0, 1).toUpperCase()}
                    </div>
                    <div>
                      <strong style="color: var(--text-main); font-size: 0.95rem;">${data.review.user_name}</strong>
                      <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 2px;">
                        ${data.review.created_at_human} (Baru Saja)
                      </div>
                    </div>
                  </a>
                </div>
                <div style="display: flex; align-items: center; gap: 10px;">
                  <span class="review-rating-badge" style="color: var(--star-rating); font-weight: bold; font-size: 0.9rem; background: rgba(227, 179, 65, 0.12); padding: 4px 10px; border-radius: 15px; border: 1px solid var(--star-rating); display: inline-flex; align-items: center; gap: 6px;">
                    <span class="review-stars-display">${generateStarIcons(data.review.rating)}</span>
                    <span class="review-score-text">${parseFloat(data.review.rating).toFixed(1)} / 5.0</span>
                  </span>
                  <button type="button" class="btn-trigger-inline-edit" title="Edit Ulasan Ini" style="background: #21262d; color: var(--star-rating); font-size: 0.8rem; border: 1px solid var(--border-color); border-radius: 4px; padding: 4px 8px; cursor: pointer; display: inline-flex; align-items: center; gap: 5px;">
                    <i class="fa-solid fa-pen-to-square"></i> Edit
                  </button>
                </div>
              </div>
              <p class="review-content-text" style="color: var(--text-main); font-size: 0.95rem; line-height: 1.6; margin-bottom: 12px; background: rgba(255, 255, 255, 0.02); padding: 12px 15px; border-radius: 6px; border-left: 3px solid var(--member-accent);">
                "${data.review.content}"
              </p>
            `;
            listContainer.prepend(newCard);
            setupInlineEditHandlers();
          }
        } else {
          throw new Error(data.message || 'Gagal menerbitkan ulasan.');
        }
      } catch (err) {
        window.showToast({ title: 'Gagal', message: err.message, type: 'error' });
        if (btnSubmit) {
          btnSubmit.disabled = false;
          btnSubmit.style.opacity = '1';
        }
        if (btnIcon) btnIcon.className = 'fa-solid fa-paper-plane';
        if (btnText) btnText.textContent = 'Kirim Ulasan';
      }
    });
  }

  // ==========================================
  // 6. OPTIMISTIC UI: LIKE ULASAN (HELPFUL)
  // ==========================================
  document.querySelectorAll('.btn-review-like-ajax').forEach(btn => {
    btn.addEventListener('click', async function () {
      const isLiked = this.dataset.liked === 'true';
      const newLikedState = !isLiked;
      const url = this.dataset.url;
      const badgeCount = this.querySelector('.badge-like-count');
      const textLike = this.querySelector('.text-like');
      const iconLike = this.querySelector('.icon-like');

      let currentCount = parseInt(badgeCount ? badgeCount.textContent : '0') || 0;
      let nextCount = newLikedState ? currentCount + 1 : Math.max(0, currentCount - 1);

      // Optimistic visual
      this.dataset.liked = newLikedState ? 'true' : 'false';
      if (badgeCount) badgeCount.textContent = nextCount;

      if (newLikedState) {
        this.style.background = 'rgba(88, 166, 255, 0.15)';
        this.style.borderColor = '#58a6ff';
        this.style.color = '#58a6ff';
        if (iconLike) iconLike.className = 'fa-solid fa-thumbs-up icon-like';
        if (textLike) textLike.textContent = 'Membantu';
      } else {
        this.style.background = '#21262d';
        this.style.borderColor = 'var(--border-color)';
        this.style.color = 'var(--text-muted)';
        if (iconLike) iconLike.className = 'fa-regular fa-thumbs-up icon-like';
        if (textLike) textLike.textContent = 'Ulasan Membantu?';
      }

      try {
        const res = await fetch(url, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
          }
        });
        const data = await res.json();
        if (data.success) {
          if (badgeCount && typeof data.likes_count !== 'undefined') {
            badgeCount.textContent = data.likes_count;
          }
          window.showToast({
            title: newLikedState ? 'Membantu' : 'Batal',
            message: data.message,
            type: 'success'
          });
        }
      } catch (err) {
        // Rollback
        this.dataset.liked = isLiked ? 'true' : 'false';
        if (badgeCount) badgeCount.textContent = currentCount;
        window.showToast({ title: 'Gagal', message: 'Koneksi bermasalah.', type: 'error' });
      }
    });
  });

  // ==========================================
  // 7. AJAX KOMENTAR BALASAN
  // ==========================================
  document.querySelectorAll('.form-comment-ajax').forEach(form => {
    form.addEventListener('submit', async function (e) {
      e.preventDefault();
      const reviewId = this.dataset.reviewId;
      const inputEl = this.querySelector('input[name="comment"]');
      const submitBtn = this.querySelector('button[type="submit"]');
      const commentText = inputEl ? inputEl.value.trim() : '';

      if (!commentText) return;

      submitBtn.disabled = true;
      submitBtn.style.opacity = '0.6';

      try {
        const formData = new FormData(this);
        const res = await fetch(this.action, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          },
          body: formData
        });

        const data = await res.json();

        if (res.ok && data.success) {
          window.showToast({ title: 'Terkirim', message: data.message, type: 'success' });
          inputEl.value = '';

          const listEl = document.getElementById(`comments-list-${reviewId}`);
          const countEl = document.getElementById(`comments-count-${reviewId}`);
          if (countEl) {
            countEl.textContent = (parseInt(countEl.textContent) || 0) + 1;
          }

          if (listEl) {
            const emptyNotice = listEl.querySelector('.empty-comments-text');
            if (emptyNotice) emptyNotice.remove();

            const newComment = document.createElement('div');
            newComment.id = `detail-comment-item-${data.comment.id}`;
            newComment.className = 'comment-item-box comment-item-entering';
            newComment.style.cssText = 'background: rgba(255, 255, 255, 0.03); padding: 8px 12px; border-radius: 6px; display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; transition: all 0.25s ease;';
            newComment.innerHTML = `
              <div style="display: flex; align-items: flex-start; gap: 8px; flex: 1;">
                <a href="${data.comment.user_profile_url}" style="text-decoration: none; flex-shrink: 0;">
                  <div style="width: 22px; height: 22px; border-radius: 50%; overflow: hidden; background: var(--member-accent); color: white; display: flex; align-items: center; justify-content: center; font-size: 0.65rem; font-weight: bold; margin-top: 2px; border: 1px solid var(--border-color);">
                    ${data.comment.user_avatar ? `<img src="${data.comment.user_avatar}" style="width: 100%; height: 100%; object-fit: cover;">` : data.comment.user_name.substring(0, 1).toUpperCase()}
                  </div>
                </a>
                <div>
                  <a href="${data.comment.user_profile_url}" style="font-weight: 600; font-size: 0.85rem; color: var(--member-accent); text-decoration: none;">
                    ${data.comment.user_name}:
                  </a>
                  <span style="font-size: 0.88rem; color: var(--text-main); margin-left: 5px;">${data.comment.comment}</span>
                  <div style="font-size: 0.72rem; color: var(--text-muted); margin-top: 2px;">
                    Baru saja
                  </div>
                </div>
              </div>
              <button type="button" class="btn-delete-comment-ajax" data-id="${data.comment.id}" data-url="/member/comments/${data.comment.id}" data-review-id="${reviewId}" title="Hapus Komentar" style="background: none; border: none; color: #8b949e; font-size: 0.75rem; cursor: pointer; padding: 2px 4px; transition: color 0.15s ease;" onmouseover="this.style.color='#f85149'" onmouseout="this.style.color='#8b949e'">
                <i class="fa-solid fa-trash-can"></i>
              </button>
            `;
            listEl.appendChild(newComment);
          }
        } else {
          throw new Error(data.message || 'Gagal mengirim komentar.');
        }
      } catch (err) {
        window.showToast({ title: 'Gagal', message: err.message, type: 'error' });
      } finally {
        submitBtn.disabled = false;
        submitBtn.style.opacity = '1';
      }
    });
  });

  // ==========================================
  // 8. HAPUS KOMENTAR 1-KLIK INSTAN (DETAIL BUKU)
  // ==========================================
  document.addEventListener('click', async function (e) {
    const btn = e.target.closest('.btn-delete-comment-ajax');
    if (!btn) return;
    e.preventDefault();

    const commentId = btn.dataset.id;
    const url = btn.dataset.url;
    const reviewId = btn.dataset.reviewId;
    const commentItem = document.getElementById(`detail-comment-item-${commentId}`);

    btn.disabled = true;
    if (commentItem) {
      commentItem.classList.add('comment-item-leaving');
    }

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
        window.showToast({ title: 'Dihapus', message: data.message, type: 'info' });
        setTimeout(() => {
          if (commentItem) commentItem.remove();
          const countEl = document.getElementById(`comments-count-${reviewId}`);
          if (countEl) {
            const current = parseInt(countEl.textContent) || 0;
            countEl.textContent = Math.max(0, current - 1);
          }
        }, 250);
      } else {
        throw new Error(data.message || 'Gagal menghapus komentar.');
      }
    } catch (err) {
      if (commentItem) commentItem.classList.remove('comment-item-leaving');
      btn.disabled = false;
      window.showToast({ title: 'Gagal', message: err.message, type: 'error' });
    }
  });
});
</script>
@endpush
