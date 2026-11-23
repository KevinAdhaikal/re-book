@extends('layouts.app')

@section('title', $book->title . ' - Re:Book')

@push('styles')
<style>
  /* ==========================================
     LETTERBOXD CINEMATIC DETAIL STYLES
     ========================================== */
  .letterboxd-backdrop-hero {
    position: relative;
    width: 100%;
    height: 280px;
    overflow: hidden;
    margin: -30px -40px 0 -40px;
    width: calc(100% + 80px);
    background: #090c10;
  }
  .letterboxd-backdrop-img {
    position: absolute;
    inset: -20px;
    width: calc(100% + 40px);
    height: calc(100% + 40px);
    object-fit: cover;
    filter: blur(40px) brightness(0.24) saturate(1.4);
    transform: scale(1.15);
    pointer-events: none;
  }
  .letterboxd-backdrop-vignette {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(13, 17, 23, 0.2) 0%, rgba(13, 17, 23, 0.75) 65%, var(--bg-main) 100%),
                radial-gradient(circle at 50% 30%, transparent 20%, rgba(13, 17, 23, 0.85) 90%);
    pointer-events: none;
  }

  /* 3-Column Grid Layout */
  .letterboxd-layout {
    display: grid;
    grid-template-columns: 220px minmax(0, 1fr) 280px;
    gap: 32px;
    align-items: start;
    position: relative;
    margin-top: -140px;
    z-index: 10;
  }
  @media (max-width: 1024px) {
    .letterboxd-layout {
      grid-template-columns: 200px minmax(0, 1fr);
    }
    .letterboxd-action-column {
      grid-column: 1 / -1;
      order: 2;
    }
    .letterboxd-main-column {
      order: 3;
      grid-column: 1 / -1;
    }
  }
  @media (max-width: 768px) {
    .letterboxd-backdrop-hero {
      margin: -20px -20px 0 -20px;
      width: calc(100% + 40px);
      height: 200px;
    }
    .letterboxd-layout {
      grid-template-columns: 1fr;
      margin-top: -90px;
      gap: 24px;
    }
  }

  /* Left Column: Poster & Book Spine Crease */
  .letterboxd-poster-box {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    background: #161b22;
    box-shadow: 0 16px 36px rgba(0, 0, 0, 0.7), 0 0 0 1px rgba(255, 255, 255, 0.08);
    transition: transform 0.25s ease, box-shadow 0.25s ease;
  }
  .letterboxd-poster-box:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 42px rgba(0, 0, 0, 0.8), 0 0 0 1px rgba(88, 166, 255, 0.3);
  }
  .letterboxd-poster-box::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    width: 8px;
    background: linear-gradient(to right, rgba(255, 255, 255, 0.18) 0%, rgba(0, 0, 0, 0.45) 45%, transparent 100%);
    pointer-events: none;
  }
  .letterboxd-poster-img {
    width: 100%;
    height: 310px;
    object-fit: cover;
    display: block;
  }

  /* Quick Reader Stats Card below poster */
  .reader-stats-box {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 14px 16px;
    margin-top: 16px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    font-size: 0.85rem;
  }
  .stat-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: var(--text-muted);
  }
  .stat-row strong {
    color: var(--text-main);
  }

  /* Center Column Typography & Content */
  .book-header-title {
    font-size: 2.2rem;
    font-weight: 800;
    line-height: 1.15;
    margin: 0 0 8px 0;
    color: var(--text-main);
    letter-spacing: -0.5px;
  }
  .book-header-author {
    font-size: 1.05rem;
    color: var(--text-muted);
    margin-bottom: 16px;
  }
  .book-header-author strong {
    color: var(--text-main);
  }

  /* Synopsis / Narrative Typography */
  .book-synopsis-box {
    background: rgba(22, 27, 34, 0.5);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 20px 24px;
    margin-bottom: 28px;
    line-height: 1.7;
    font-size: 0.96rem;
    color: #e6edf3;
  }

  /* LETTERBOXD RATINGS HISTOGRAM */
  .histogram-section {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 20px 24px;
    margin-bottom: 32px;
  }
  .histogram-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
    flex-wrap: wrap;
    gap: 12px;
  }
  .histogram-bars-wrap {
    display: flex;
    align-items: flex-end;
    gap: 6px;
    height: 75px;
    padding-bottom: 4px;
    border-bottom: 1px solid var(--border-color);
  }
  .histogram-bar-col {
    flex: 1;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    position: relative;
    cursor: pointer;
  }
  .histogram-bar-col:hover .histogram-bar-fill {
    background: #f1c762;
    filter: drop-shadow(0 0 6px rgba(227, 179, 65, 0.6));
  }
  .histogram-bar-fill {
    background: var(--star-rating);
    border-radius: 3px 3px 0 0;
    min-height: 3px;
    transition: height 0.3s cubic-bezier(0.16, 1, 0.3, 1), background 0.15s ease;
  }
  .histogram-axis-labels {
    display: flex;
    justify-content: space-between;
    font-size: 0.72rem;
    color: var(--text-muted);
    margin-top: 6px;
  }

  /* Letterboxd Tooltip for Bars */
  .histogram-bar-col .hist-tooltip {
    visibility: hidden;
    opacity: 0;
    position: absolute;
    bottom: calc(100% + 6px);
    left: 50%;
    transform: translateX(-50%);
    background: #0d1117;
    color: #fff;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.72rem;
    white-space: nowrap;
    border: 1px solid var(--border-color);
    box-shadow: 0 4px 12px rgba(0,0,0,0.5);
    z-index: 50;
    pointer-events: none;
    transition: opacity 0.15s ease, transform 0.15s ease;
  }
  .histogram-bar-col:hover .hist-tooltip {
    visibility: visible;
    opacity: 1;
    transform: translateX(-50%) translateY(-2px);
  }

  /* Right Column: Sticky Action Box */
  .letterboxd-action-box {
    position: sticky;
    top: 24px;
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 16px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
  }
  .action-box-title {
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--text-muted);
    font-weight: 700;
  }
  .btn-log-review-cta {
    width: 100%;
    background: var(--member-accent);
    color: #fff;
    border: none;
    padding: 12px 16px;
    border-radius: 6px;
    font-weight: 700;
    font-size: 0.95rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: background 0.18s ease, transform 0.15s ease, box-shadow 0.18s ease;
    box-shadow: 0 4px 14px rgba(31, 111, 235, 0.35);
  }
  .btn-log-review-cta:hover {
    background: var(--member-accent-hover);
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(31, 111, 235, 0.45);
  }

  /* Action Buttons Row (Like & Favorite) */
  .action-grid-buttons {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
  }
  .btn-action-side {
    background: #21262d;
    border: 1px solid var(--border-color);
    color: var(--text-main);
    padding: 9px 12px;
    border-radius: 6px;
    font-size: 0.82rem;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.18s ease;
  }
  .btn-action-side:hover {
    background: #2b313a;
    border-color: #484f58;
  }

  /* Live Rate Widget inside Action Box */
  .action-rate-widget {
    background: rgba(13, 17, 23, 0.6);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 12px 14px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
  }
  .action-stars-row {
    display: flex;
    gap: 6px;
    font-size: 1.45rem;
    cursor: pointer;
  }
  .action-star-item {
    color: #484f58;
    transition: transform 0.15s cubic-bezier(0.16, 1, 0.3, 1), color 0.15s ease, filter 0.15s ease;
  }
  .action-star-item.active {
    color: var(--star-rating);
    filter: drop-shadow(0 0 6px rgba(227, 179, 65, 0.4));
  }
  .action-star-item:hover {
    transform: scale(1.25);
  }

  /* Popover Menu Rak Bacaan */
  .shelf-menu-item {
    width: 100%;
    text-align: left;
    background: transparent;
    border: none;
    padding: 9px 12px;
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

  /* ==========================================
     LETTERBOXD LOG / REVIEW MODAL
     ========================================== */
  .letterboxd-modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.75);
    backdrop-filter: blur(8px);
    z-index: 1000;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
    opacity: 0;
    transition: opacity 0.2s ease;
  }
  .letterboxd-modal-backdrop.show {
    display: flex;
    opacity: 1;
  }
  .letterboxd-modal-card {
    background: #161b22;
    border: 1px solid var(--border-color);
    border-radius: 12px;
    width: 100%;
    max-width: 580px;
    box-shadow: 0 24px 48px rgba(0, 0, 0, 0.8), 0 0 0 1px rgba(255, 255, 255, 0.06);
    overflow: hidden;
    transform: scale(0.95) translateY(10px);
    transition: transform 0.22s cubic-bezier(0.16, 1, 0.3, 1);
  }
  .letterboxd-modal-backdrop.show .letterboxd-modal-card {
    transform: scale(1) translateY(0);
  }
  .modal-header-hero {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    border-bottom: 1px solid var(--border-color);
    background: rgba(255, 255, 255, 0.02);
  }
  .modal-close-btn {
    background: transparent;
    border: none;
    color: var(--text-muted);
    font-size: 1.3rem;
    cursor: pointer;
    line-height: 1;
    padding: 4px;
    transition: color 0.15s ease;
  }
  .modal-close-btn:hover {
    color: var(--text-main);
  }

  /* Animasi Review Baru */
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
</style>
@endpush

@section('content')
@php
  $user = auth()->user();
  $isAuthMember = auth()->check() && $user->role === 'member';
  $isLiked = $user ? $user->hasLiked($book->id) : false;
  $isFavorited = $user ? $user->hasFavorited($book->id) : false;
  $shelfStatus = $user ? $user->shelfStatusFor($book->id) : null;
  $myReview = $book->reviews->where('user_id', auth()->id())->first();
  $avgRating = $book->reviews_avg_rating ?? ($book->reviews->avg('rating') ?: 0);
@endphp

{{-- 1. AMBIENT CINEMATIC COVER BACKDROP --}}
<div class="letterboxd-backdrop-hero">
  @if($book->cover)
    <img src="{{ asset('storage/' . $book->cover) }}" alt="" class="letterboxd-backdrop-img">
  @else
    <div class="letterboxd-backdrop-img" style="background: linear-gradient(135deg, #1f6feb 0%, #161b22 100%);"></div>
  @endif
  <div class="letterboxd-backdrop-vignette"></div>
</div>

{{-- 2. 3-COLUMN LETTERBOXD GRID --}}
<div class="letterboxd-layout">

  {{-- ========================================================
       KOLOM 1: POSTER BUKU & STATISTIK PEMBACA
       ======================================================== --}}
  <div class="letterboxd-poster-column">
    <div class="letterboxd-poster-box">
      @if($book->cover)
        <img src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->title }}" class="letterboxd-poster-img"
             onerror="this.onerror=null; this.parentElement.querySelector('.cover-fallback').style.display='flex'; this.style.display='none';">
        <div class="cover-fallback" style="display: none; width: 100%; height: 310px; background: #161b22; flex-direction: column; align-items: center; justify-content: center; gap: 8px; color: var(--text-muted); text-align: center; padding: 15px;">
          <i class="fa-solid fa-book" style="font-size: 2.5rem; color: #30363d;"></i>
          <span style="font-size: 0.85rem; font-weight: 600; color: #8b949e;">{{ Str::limit($book->title, 25) }}</span>
        </div>
      @else
        <div style="width: 100%; height: 310px; background: #161b22; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; color: var(--text-muted); text-align: center; padding: 15px;">
          <i class="fa-solid fa-book" style="font-size: 2.5rem; color: #30363d;"></i>
          <span style="font-size: 0.85rem; font-weight: 600; color: #8b949e;">{{ Str::limit($book->title, 25) }}</span>
        </div>
      @endif
    </div>

    {{-- Genre Tag di bawah Poster --}}
    <div style="margin-top: 14px; text-align: center;">
      <span style="background: rgba(33, 38, 45, 0.9); border: 1px solid var(--border-color); color: #58a6ff; padding: 5px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; display: inline-block;">
        <i class="fa-solid fa-bookmark" style="font-size: 0.72rem; margin-right: 4px;"></i> {{ $book->genre }}
      </span>
    </div>

    {{-- Statistik Pembaca ala Letterboxd --}}
    <div class="reader-stats-box">
      <div class="stat-row" title="{{ $shelfStats['read'] }} Selesai, {{ $shelfStats['currently_reading'] }} Sedang Dibaca, {{ $shelfStats['want_to_read'] }} Ingin Dibaca">
        <span><i class="fa-solid fa-eye" style="margin-right: 6px; color: #58a6ff;"></i> Pembaca</span>
        <strong id="stat-readers-count">{{ $shelfStats['total'] ?? 0 }}</strong>
      </div>
      <div class="stat-row">
        <span><i class="fa-solid fa-heart" style="margin-right: 6px; color: var(--favorite-coral);"></i> Disukai</span>
        <strong id="stat-likes-count">{{ $book->liked_by_users_count ?? 0 }}</strong>
      </div>
      <div class="stat-row">
        <span><i class="fa-solid fa-star" style="margin-right: 6px; color: var(--star-rating);"></i> Ulasan</span>
        <strong id="stat-reviews-count">{{ $book->reviews_count ?? $book->reviews->count() }}</strong>
      </div>
      <div class="stat-row" style="border-top: 1px solid var(--border-color); padding-top: 8px; margin-top: 2px;">
        <span><i class="fa-solid fa-chart-line" style="margin-right: 6px; color: #3fb950;"></i> Rata-rata</span>
        <strong style="color: var(--star-rating);">★ <span id="stat-avg-rating">{{ number_format($avgRating, 1) }}</span></strong>
      </div>
    </div>
  </div>

  {{-- ========================================================
       KOLOM 2: HEADER, SINOPSIS, HISTOGRAM & ULASAN
       ======================================================== --}}
  <div class="letterboxd-main-column">
    {{-- Header Info Buku --}}
    <div style="margin-bottom: 24px;">
      <h1 class="book-header-title">{{ $book->title }}</h1>
      <div class="book-header-author">
        Penulis: <strong>{{ $book->author }}</strong>
        <span style="color: var(--border-color); margin: 0 8px;">•</span>
        <span style="color: var(--text-muted); font-size: 0.9rem;">Koleksi Re:Book #{{ $book->id }}</span>
      </div>
    </div>

    {{-- Sinopsis Naratif --}}
    <div class="book-synopsis-box">
      <h4 style="font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted); margin-bottom: 8px; font-weight: 700;">
        <i class="fa-solid fa-align-left" style="margin-right: 6px;"></i> Sinopsis Buku
      </h4>
      <p style="margin: 0;">{{ $book->description }}</p>
    </div>

    {{-- ========================================================
         LETTERBOXD RATINGS HISTOGRAM BOX
         ======================================================== --}}
    <div class="histogram-section" id="histogram-section">
      <div class="histogram-header">
        <div>
          <h3 style="font-size: 1.05rem; font-weight: 700; margin: 0 0 4px 0; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-chart-simple" style="color: var(--star-rating);"></i> Distribusi Rating
          </h3>
          <p style="font-size: 0.8rem; color: var(--text-muted); margin: 0;">
            Berdasarkan sebaran skor dari pembaca komunitas
          </p>
        </div>
        <div style="display: flex; align-items: center; gap: 12px;">
          <div style="font-size: 1.6rem; font-weight: 800; color: var(--star-rating); line-height: 1;">
            {{ number_format($avgRating, 1) }}
          </div>
          <div>
            <div><x-star-rating :rating="$avgRating" size="sm" :showScore="false" /></div>
            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 2px;">
              {{ $book->reviews->count() }} peringkat
            </div>
          </div>
        </div>
      </div>

      {{-- 10 Bar Histogram Sebaran 0.5 s/d 5.0 --}}
      @php
        $scores = ['0.5', '1.0', '1.5', '2.0', '2.5', '3.0', '3.5', '4.0', '4.5', '5.0'];
        $totalReviewsCount = $book->reviews->count();
      @endphp
      <div class="histogram-bars-wrap">
        @foreach($scores as $s)
          @php
            $count = $ratingDistribution[$s] ?? 0;
            $heightPct = $maxRatingCount > 0 ? max(4, round(($count / $maxRatingCount) * 100)) : 4;
            $pct = $totalReviewsCount > 0 ? round(($count / $totalReviewsCount) * 100) : 0;
          @endphp
          <div class="histogram-bar-col" data-score="{{ $s }}" data-count="{{ $count }}">
            <div class="hist-tooltip">
              <strong>★ {{ $s }}</strong>: {{ $count }} ulasan ({{ $pct }}%)
            </div>
            <div class="histogram-bar-fill" style="height: {{ $heightPct }}%;"></div>
          </div>
        @endforeach
      </div>
      <div class="histogram-axis-labels">
        <span>½★</span>
        <span style="opacity: 0.5;">•</span>
        <span>2★</span>
        <span style="opacity: 0.5;">•</span>
        <span>3★</span>
        <span style="opacity: 0.5;">•</span>
        <span>4★</span>
        <span style="opacity: 0.5;">•</span>
        <span>5★</span>
      </div>
    </div>

    {{-- ========================================================
         STATUS ULASAN SAYA (EDIT INLINE / COMPATIBILITY)
         ======================================================== --}}
    @auth
      @if(auth()->user()->role === 'member')
        @if($myReview)
          <div id="my-review-card" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 20px; margin-bottom: 30px; transition: all 0.3s ease;">
            {{-- Mode Normal --}}
            <div id="my-review-view-box" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
              <div>
                <h4 style="color: var(--text-main); font-size: 0.95rem; margin-bottom: 4px; display: flex; align-items: center; gap: 6px;">
                  <i class="fa-solid fa-circle-check" style="color: #3fb950;"></i> Anda sudah mengulas buku ini
                </h4>
                <p style="color: var(--text-muted); font-size: 0.85rem; margin: 0;">
                  Nilai Anda: <strong style="color: var(--star-rating);">⭐ <span id="my-review-score-badge">{{ number_format($myReview->rating, 1) }}</span></strong> — "<span id="my-review-content-snippet">{{ Str::limit($myReview->content, 60) }}</span>"
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

            {{-- Form Edit Inline --}}
            <div id="my-review-edit-box" style="display: none; padding-top: 5px;">
              <h4 style="color: var(--text-main); font-size: 0.95rem; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-pen-to-square" style="color: var(--star-rating);"></i> Edit Ulasan Anda
              </h4>
              <form id="form-edit-my-review" action="{{ route('member.reviews.update', $myReview->id) }}" method="POST">
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
                    <input type="hidden" name="rating" id="edit-rating-input" value="{{ $myReview->rating }}">
                    <span id="edit-rating-desc" style="font-size: 0.85rem; font-weight: 700; color: var(--star-rating); background: rgba(227, 179, 65, 0.12); border: 1px solid rgba(227, 179, 65, 0.3); padding: 5px 12px; border-radius: 6px;">
                      {{ number_format($myReview->rating, 1) }}
                    </span>
                  </div>
                </div>

                <div style="margin-bottom: 15px;">
                  <textarea name="content" id="edit-review-content-input" rows="3" required style="width: 100%; padding: 12px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none; resize: vertical; font-size: 0.9rem; box-sizing: border-box;">{{ $myReview->content }}</textarea>
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
          {{-- Fallback Card jika user mau menulis langsung via form inline --}}
          <div id="review-form-wrapper" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 22px; margin-bottom: 30px; transition: all 0.3s ease;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
              <h3 style="margin: 0; font-size: 1.05rem;">✍️ Tulis Ulasan Kamu</h3>
              <button type="button" class="btn-open-letterboxd-modal" style="background: none; border: none; color: var(--member-accent); font-size: 0.82rem; font-weight: 600; cursor: pointer;">
                Buka Mode Pop-up <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 0.75rem;"></i>
              </button>
            </div>
            <form id="form-submit-review" action="{{ route('member.reviews.store') }}" method="POST">
              @csrf
              <input type="hidden" name="book_id" value="{{ $book->id }}">
              
              <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 8px; font-weight: 600;">
                  Beri Rating Bintang (0.5 – 5.0)
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

    {{-- ========================================================
         DAFTAR ULASAN KOMUNITAS
         ======================================================== --}}
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px;">
      <h3 style="margin: 0; font-size: 1.15rem; display: flex; align-items: center; gap: 8px;">
        <i class="fa-regular fa-comments" style="color: var(--member-accent);"></i> Ulasan Komunitas
      </h3>
      <span style="font-size: 0.85rem; color: var(--text-muted);">
        Menampilkan <strong>{{ $book->reviews->count() }}</strong> ulasan
      </span>
    </div>

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

              {{-- Aksi Edit jika pemilik ulasan --}}
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

          {{-- Like Ulasan / Membantu --}}
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
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
              <h5 style="color: var(--text-muted); margin: 0; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.5px;">
                <i class="fa-regular fa-comments"></i> <span id="comments-count-{{ $r->id }}">{{ $r->comments->count() }}</span> Komentar
              </h5>
            </div>

            <!-- Form Komentar (AJAX) - Ditaruh di atas daftar komentar -->
            @auth
              <form class="form-comment-ajax" action="{{ route('member.comments.store', $r->id) }}" method="POST" data-review-id="{{ $r->id }}" style="display: flex; gap: 8px; margin-bottom: 14px;">
                @csrf
                <input type="text" name="comment" placeholder="Tulis komentar balasan..." required style="flex: 1; padding: 7px 12px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; font-size: 0.85rem; outline: none;">
                <button type="submit" style="background: var(--member-accent); color: white; border: none; padding: 7px 14px; border-radius: 6px; font-weight: 600; font-size: 0.82rem; cursor: pointer; transition: opacity 0.2s;">Kirim</button>
              </form>
            @else
              <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 12px;">
                <a href="{{ route('login') }}" style="color: var(--member-accent); text-decoration: underline;">Login</a> untuk membalas ulasan ini.
              </p>
            @endauth

            <!-- List Komentar (Urutan Terbaru di Atas) -->
            <div id="comments-list-{{ $r->id }}" style="display: flex; flex-direction: column; gap: 8px; padding-left: 12px; border-left: 2px solid var(--border-color);">
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
                      <a href="{{ $c->user ? route('users.profile', $c->user->id) : '#' }}" style="font-weight: 600; font-size: 0.85rem; color: var(--member-accent); text-decoration: none;">
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
          </div>

        </div>
      @empty
        <p id="empty-reviews-notice" style="color: var(--text-muted); font-style: italic;">Belum ada ulasan untuk buku ini. Jadilah yang pertama mengulas!</p>
      @endforelse
    </div>
  </div>

  {{-- ========================================================
       KOLOM 3: THE LETTERBOXD ACTION BOX (STICKY)
       ======================================================== --}}
  <div class="letterboxd-action-column">
    <div class="letterboxd-action-box">
      <div class="action-box-title">Panel Aksi Buku</div>

      @auth
        {{-- Status / CTA Tombol Log Ulasan --}}
        @if($myReview)
          <div style="background: rgba(63, 185, 80, 0.12); border: 1px solid rgba(63, 185, 80, 0.3); border-radius: 6px; padding: 10px 12px; display: flex; align-items: center; justify-content: space-between;">
            <div>
              <div style="font-size: 0.75rem; color: #3fb950; font-weight: 700;">✓ SUDAH DIULAS</div>
              <div style="font-size: 0.9rem; font-weight: 800; color: var(--star-rating); margin-top: 2px;">
                ⭐ {{ number_format($myReview->rating, 1) }}
              </div>
            </div>
            <button type="button" class="btn-open-letterboxd-modal" style="background: #21262d; border: 1px solid var(--border-color); color: var(--text-main); font-size: 0.8rem; padding: 6px 10px; border-radius: 6px; cursor: pointer; font-weight: 600;">
              Ubah
            </button>
          </div>
        @else
          <button type="button" class="btn-log-review-cta btn-open-letterboxd-modal">
            <i class="fa-solid fa-feather-pointed"></i> + Log / Review
          </button>
        @endif

        {{-- Dropdown Rak Bacaan (Want to Read / Currently Reading / Read) --}}
        @php
          $shelfLabels = [
            'want_to_read' => ['text' => 'Ingin Dibaca', 'icon' => '⏳', 'color' => '#58a6ff'],
            'currently_reading' => ['text' => 'Sedang Dibaca', 'icon' => '📖', 'color' => '#3fb950'],
            'read' => ['text' => 'Selesai Dibaca', 'icon' => '✅', 'color' => '#bc8cff'],
          ];
          $currentShelf = $shelfStatus && isset($shelfLabels[$shelfStatus]) ? $shelfLabels[$shelfStatus] : null;
        @endphp
        <div class="rebook-shelf-popover-wrap" style="position: relative; width: 100%;">
          <button type="button" id="shelf-pill-btn"
                  data-status="{{ $shelfStatus ?? '' }}"
                  data-url-update="{{ route('member.shelf.update', $book->id) }}"
                  data-url-destroy="{{ route('member.shelf.destroy', $book->id) }}"
                  style="width: 100%; justify-content: space-between; background: {{ $currentShelf ? 'rgba(88, 166, 255, 0.15)' : '#21262d' }};
                         border: 1px solid {{ $currentShelf ? ($currentShelf['color'] ?? '#58a6ff') : 'var(--border-color)' }};
                         color: {{ $currentShelf ? ($currentShelf['color'] ?? '#58a6ff') : 'var(--text-main)' }};
                         padding: 10px 14px; border-radius: 6px; font-size: 0.85rem; font-weight: 600;
                         cursor: pointer; display: flex; align-items: center; transition: all 0.18s ease;">
            <span style="display: flex; align-items: center; gap: 8px;">
              <span id="shelf-pill-icon">{{ $currentShelf ? $currentShelf['icon'] : '🔖' }}</span>
              <span id="shelf-pill-text">{{ $currentShelf ? $currentShelf['text'] : '+ Masukkan Rak' }}</span>
            </span>
            <i class="fa-solid fa-chevron-down" style="font-size: 0.7rem; opacity: 0.7;"></i>
          </button>

          {{-- Popover Menu --}}
          <div id="shelf-popover-menu"
               style="display: none; position: absolute; top: calc(100% + 6px); left: 0; width: 100%;
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

        {{-- Tombol Aksi Grid (Like & Favorite) --}}
        <div class="action-grid-buttons">
          {{-- Sukai Buku --}}
          <button type="button" id="btn-toggle-like" class="btn-action-side"
                  data-book-id="{{ $book->id }}"
                  data-liked="{{ $isLiked ? 'true' : 'false' }}"
                  data-url="{{ route('member.likes.toggle', $book->id) }}"
                  style="{{ $isLiked ? 'background: rgba(247, 129, 102, 0.15); border-color: #F78166; color: #F78166;' : '' }}">
            <i class="{{ $isLiked ? 'fa-solid' : 'fa-regular' }} fa-heart" id="icon-toggle-like"></i>
            <span id="text-toggle-like">{{ $isLiked ? 'Disukai' : 'Sukai' }}</span>
          </button>

          {{-- Favorit Saya --}}
          <button type="button" id="btn-toggle-fav" class="btn-action-side"
                  data-book-id="{{ $book->id }}"
                  data-favorited="{{ $isFavorited ? 'true' : 'false' }}"
                  data-url="{{ route('member.fav.toggle', $book->id) }}"
                  style="{{ $isFavorited ? 'background: rgba(227, 179, 65, 0.15); border-color: var(--star-rating); color: var(--star-rating);' : '' }}">
            <i class="{{ $isFavorited ? 'fa-solid' : 'fa-regular' }} fa-star" id="icon-toggle-fav"></i>
            <span id="text-toggle-fav">{{ $isFavorited ? 'Favorit' : 'Favorit' }}</span>
          </button>
        </div>

        {{-- Live Star Rater Widget --}}
        <div class="action-rate-widget">
          <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">
            Beri Rating Cepat
          </div>
          <div class="action-stars-row" id="action-box-stars">
            <span class="action-star-item {{ ($myReview && $myReview->rating >= 1) ? 'active' : '' }}" data-star="1"><i class="fa-solid fa-star"></i></span>
            <span class="action-star-item {{ ($myReview && $myReview->rating >= 2) ? 'active' : '' }}" data-star="2"><i class="fa-solid fa-star"></i></span>
            <span class="action-star-item {{ ($myReview && $myReview->rating >= 3) ? 'active' : '' }}" data-star="3"><i class="fa-solid fa-star"></i></span>
            <span class="action-star-item {{ ($myReview && $myReview->rating >= 4) ? 'active' : '' }}" data-star="4"><i class="fa-solid fa-star"></i></span>
            <span class="action-star-item {{ ($myReview && $myReview->rating >= 5) ? 'active' : '' }}" data-star="5"><i class="fa-solid fa-star"></i></span>
          </div>
          <div id="action-stars-score-label" style="font-size: 0.78rem; font-weight: 700; color: var(--star-rating);">
            {{ $myReview ? number_format($myReview->rating, 1) . ' Bintang' : 'Klik bintang untuk mengulas' }}
          </div>
        </div>

      @else
        {{-- Pengunjung / Tamu --}}
        <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0; line-height: 1.5;">
          Masuk ke akun Anda untuk mencatat buku ini ke rak, memberikan bintang, dan menulis ulasan.
        </p>
        <a href="{{ route('login') }}" class="btn-log-review-cta" style="text-decoration: none;">
          <i class="fa-solid fa-right-to-bracket"></i> Masuk untuk Log
        </a>
      @endauth
    </div>
  </div>

</div>

{{-- ========================================================
     LETTERBOXD "LOG / REVIEW BOOK" POPUP MODAL
     ======================================================== --}}
@auth
  <div class="letterboxd-modal-backdrop" id="letterboxd-review-modal">
    <div class="letterboxd-modal-card">
      <div class="modal-header-hero">
        <div style="display: flex; align-items: center; gap: 12px;">
          <div style="width: 34px; height: 48px; border-radius: 4px; overflow: hidden; background: #0d1117; flex-shrink: 0; border: 1px solid var(--border-color);">
            @if($book->cover)
              <img src="{{ asset('storage/' . $book->cover) }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
            @endif
          </div>
          <div>
            <div style="font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); font-weight: 700; letter-spacing: 0.5px;">
              {{ $myReview ? 'Perbarui Ulasan Buku' : 'Saya Sudah Membaca...' }}
            </div>
            <h3 style="margin: 0; font-size: 1.05rem; color: #fff;">{{ $book->title }}</h3>
          </div>
        </div>
        <button type="button" class="modal-close-btn" id="btn-close-letterboxd-modal" title="Tutup (Esc)">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>

      <form id="modal-review-form" action="{{ $myReview ? route('member.reviews.update', $myReview->id) : route('member.reviews.store') }}" method="POST" style="padding: 20px;">
        @csrf
        @if($myReview)
          @method('PUT')
        @endif
        <input type="hidden" name="book_id" value="{{ $book->id }}">

        {{-- 1. Rating Selector Setengah Bintang --}}
        <div style="margin-bottom: 20px; text-align: center;">
          <label style="display: block; font-size: 0.82rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
            Beri Rating (Klik kiri/kanan bintang untuk setengah bintang)
          </label>
          <div style="display: inline-flex; flex-direction: column; align-items: center; gap: 8px;">
            <div id="modal-star-group" style="display: flex; gap: 10px; font-size: 2.2rem; cursor: pointer;">
              <span class="modal-star-item active" data-star="1"><i class="fa-solid fa-star"></i></span>
              <span class="modal-star-item active" data-star="2"><i class="fa-solid fa-star"></i></span>
              <span class="modal-star-item active" data-star="3"><i class="fa-solid fa-star"></i></span>
              <span class="modal-star-item active" data-star="4"><i class="fa-solid fa-star"></i></span>
              <span class="modal-star-item active" data-star="5"><i class="fa-solid fa-star"></i></span>
            </div>
            <input type="hidden" name="rating" id="modal-rating-input" value="{{ $myReview ? $myReview->rating : '5.0' }}">
            <span id="modal-rating-desc" style="font-size: 0.85rem; font-weight: 700; color: var(--star-rating); background: rgba(227, 179, 65, 0.12); border: 1px solid rgba(227, 179, 65, 0.3); padding: 4px 14px; border-radius: 20px;">
              {{ $myReview ? number_format($myReview->rating, 1) . ' Bintang' : '5.0 — Mahakarya Sempurna' }}
            </span>
          </div>
        </div>

        {{-- 2. Review Textarea --}}
        <div style="margin-bottom: 16px;">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
            <label style="font-size: 0.82rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
              Ulasan Anda
            </label>
            <span id="modal-char-count" style="font-size: 0.75rem; color: var(--text-muted);">0 / 1000</span>
          </div>
          <textarea name="content" id="modal-review-content" rows="4" maxlength="1000" placeholder="Tuliskan ulasan jujur tentang alur cerita, karakter, atau kesan setelah membaca buku ini..." required style="width: 100%; padding: 12px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none; resize: vertical; font-size: 0.92rem; box-sizing: border-box; line-height: 1.5;">{{ $myReview ? $myReview->content : '' }}</textarea>
        </div>

        {{-- 3. Modal Footer Actions --}}
        <div style="display: flex; justify-content: flex-end; align-items: center; gap: 12px; padding-top: 10px; border-top: 1px solid var(--border-color);">
          <button type="button" class="btn-action-side" id="btn-cancel-modal">Batal</button>
          <button type="submit" id="btn-submit-modal-review" style="background: var(--member-accent); color: #fff; border: none; padding: 10px 22px; border-radius: 6px; font-weight: 700; font-size: 0.9rem; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-check" id="icon-submit-modal"></i>
            <span id="text-submit-modal">{{ $myReview ? 'Simpan Perubahan' : 'Terbitkan Ulasan' }}</span>
          </button>
        </div>
      </form>
    </div>
  </div>
@endauth

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

  // ==========================================
  // RATING LABELS 0.5 - 5.0
  // ==========================================
  const ratingLabels = {
    '0.5': '0.5 — Setengah Bintang / Sangat Buruk',
    '1.0': '1.0 — Buruk Sekali',
    '1.5': '1.5 — Kurang Memuaskan',
    '2.0': '2.0 — Cukup Kurang',
    '2.5': '2.5 — Rata-rata / Biasa Saja',
    '3.0': '3.0 — Lumayan Bagus',
    '3.5': '3.5 — Bagus & Menarik',
    '4.0': '4.0 — Sangat Bagus & Direkomendasikan',
    '4.5': '4.5 — Luar Biasa Keren',
    '5.0': '5.0 — Mahakarya Sempurna',
  };

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

  function setupStarPicker({ containerId, inputId, descId, initialScore = 5.0, onItemClick = null }) {
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
        if (typeof onItemClick === 'function') {
          onItemClick(clickScore);
        }
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

  // ==========================================
  // LETTERBOXD MODAL POPUP LOGIC
  // ==========================================
  const modalEl = document.getElementById('letterboxd-review-modal');
  const btnCloseModal = document.getElementById('btn-close-letterboxd-modal');
  const btnCancelModal = document.getElementById('btn-cancel-modal');
  const modalForm = document.getElementById('modal-review-form');
  const modalContentInput = document.getElementById('modal-review-content');
  const modalCharCount = document.getElementById('modal-char-count');

  let modalStarPicker = null;
  if (modalEl) {
    modalStarPicker = setupStarPicker({
      containerId: 'modal-star-group',
      inputId: 'modal-rating-input',
      descId: 'modal-rating-desc',
      initialScore: parseFloat(document.getElementById('modal-rating-input')?.value || 5.0)
    });

    if (modalContentInput && modalCharCount) {
      modalContentInput.addEventListener('input', function () {
        modalCharCount.textContent = `${this.value.length} / 1000`;
      });
      modalCharCount.textContent = `${modalContentInput.value.length} / 1000`;
    }
  }

  function openLetterboxdModal(presetScore = null) {
    if (!modalEl) return;
    if (presetScore && modalStarPicker) {
      modalStarPicker.setScore(presetScore);
    }
    modalEl.classList.add('show');
    document.body.style.overflow = 'hidden';
    if (modalContentInput) {
      setTimeout(() => modalContentInput.focus(), 150);
    }
  }

  function closeLetterboxdModal() {
    if (!modalEl) return;
    modalEl.classList.remove('show');
    document.body.style.overflow = '';
  }

  document.querySelectorAll('.btn-open-letterboxd-modal').forEach(btn => {
    btn.addEventListener('click', () => openLetterboxdModal());
  });

  if (btnCloseModal) btnCloseModal.addEventListener('click', closeLetterboxdModal);
  if (btnCancelModal) btnCancelModal.addEventListener('click', closeLetterboxdModal);

  if (modalEl) {
    modalEl.addEventListener('click', function (e) {
      if (e.target === modalEl) closeLetterboxdModal();
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && modalEl.classList.contains('show')) {
        closeLetterboxdModal();
      }
    });
  }

  // Live Star Rater di Action Box Kanan
  const actionStarsBox = document.getElementById('action-box-stars');
  if (actionStarsBox) {
    const actionStarItems = actionStarsBox.querySelectorAll('.action-star-item');
    const actionLabel = document.getElementById('action-stars-score-label');

    function renderActionStars(score) {
      const fullCount = Math.floor(score);
      const hasHalf = (score - fullCount) >= 0.5;
      actionStarItems.forEach(star => {
        const starIdx = parseInt(star.dataset.star);
        const icon = star.querySelector('i');
        if (starIdx <= fullCount) {
          star.classList.add('active');
          if (icon) icon.className = 'fa-solid fa-star';
        } else if (starIdx === (fullCount + 1) && hasHalf) {
          star.classList.add('active');
          if (icon) icon.className = 'fa-solid fa-star-half-stroke';
        } else {
          star.classList.remove('active');
          if (icon) icon.className = 'fa-regular fa-star';
        }
      });
      if (actionLabel) {
        actionLabel.textContent = `${score.toFixed(1)} Bintang (Klik untuk simpan)`;
      }
    }

    actionStarItems.forEach(star => {
      star.addEventListener('mousemove', function (e) {
        const rect = this.getBoundingClientRect();
        const isLeft = (e.clientX - rect.left) < (rect.width / 2);
        const starIndex = parseInt(this.dataset.star);
        const score = isLeft ? (starIndex - 0.5) : starIndex;
        renderActionStars(score);
      });

      star.addEventListener('click', function (e) {
        const rect = this.getBoundingClientRect();
        const isLeft = (e.clientX - rect.left) < (rect.width / 2);
        const starIndex = parseInt(this.dataset.star);
        const score = isLeft ? (starIndex - 0.5) : starIndex;
        openLetterboxdModal(score);
      });
    });
  }

  // Inisialisasi Star Picker Create Review (Fallback inline)
  const createStarPicker = setupStarPicker({
    containerId: 'interactive-star-group',
    inputId: 'review-rating-input',
    descId: 'review-rating-desc',
    initialScore: 5.0
  });

  // Inisialisasi Star Picker Edit Review (Inline)
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

    document.querySelectorAll('.btn-trigger-inline-edit').forEach(btn => {
      btn.onclick = function () {
        const myReviewCard = document.getElementById('my-review-card');
        if (myReviewCard) {
          myReviewCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
          if (btnToggleEdit) btnToggleEdit.click();
        } else {
          openLetterboxdModal();
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

            const scoreBadge = document.getElementById('my-review-score-badge');
            const contentSnippet = document.getElementById('my-review-content-snippet');
            if (scoreBadge) scoreBadge.textContent = parseFloat(data.review.rating).toFixed(1);
            if (contentSnippet) contentSnippet.textContent = data.review.content.substring(0, 60);

            const reviewCard = document.getElementById(`review-card-${data.review.id}`);
            if (reviewCard) {
              const starsDisplay = reviewCard.querySelector('.review-stars-display');
              const scoreText = reviewCard.querySelector('.review-score-text');
              const contentText = reviewCard.querySelector('.review-content-text');

              if (starsDisplay) starsDisplay.innerHTML = generateStarIcons(data.review.rating);
              if (scoreText) scoreText.textContent = `${parseFloat(data.review.rating).toFixed(1)} / 5.0`;
              if (contentText) contentText.textContent = `"${data.review.content}"`;
            }

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
  // AJAX SUBMIT LETTERBOXD MODAL REVIEW
  // ==========================================
  if (modalForm) {
    modalForm.addEventListener('submit', async function (e) {
      e.preventDefault();
      const btnSubmit = document.getElementById('btn-submit-modal-review');
      const iconSubmit = document.getElementById('icon-submit-modal');
      const textSubmit = document.getElementById('text-submit-modal');
      const content = modalContentInput.value.trim();

      if (content.length < 5) {
        window.showToast({ title: 'Peringatan', message: 'Ulasan minimal 5 karakter.', type: 'error' });
        return;
      }

      btnSubmit.disabled = true;
      btnSubmit.style.opacity = '0.7';
      if (iconSubmit) iconSubmit.className = 'fa-solid fa-spinner fa-spin';
      if (textSubmit) textSubmit.textContent = 'Menyimpan...';

      try {
        const formData = new FormData(modalForm);
        const res = await fetch(modalForm.action, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          },
          body: formData
        });

        const data = await res.json();
        if (res.ok && data.success) {
          window.showToast({ title: 'Berhasil', message: data.message, type: 'success' });
          closeLetterboxdModal();
          setTimeout(() => window.location.reload(), 400);
        } else {
          throw new Error(data.message || 'Gagal menyimpan ulasan.');
        }
      } catch (err) {
        window.showToast({ title: 'Gagal', message: err.message, type: 'error' });
        btnSubmit.disabled = false;
        btnSubmit.style.opacity = '1';
        if (iconSubmit) iconSubmit.className = 'fa-solid fa-check';
        if (textSubmit) textSubmit.textContent = 'Terbitkan Ulasan';
      }
    });
  }

  // ==========================================
  // OPTIMISTIC UI: LIKE BUKU
  // ==========================================
  const btnLike = document.getElementById('btn-toggle-like');
  if (btnLike) {
    btnLike.addEventListener('click', async function () {
      const isCurrentlyLiked = btnLike.dataset.liked === 'true';
      const newLikedState = !isCurrentlyLiked;
      const url = btnLike.dataset.url;

      btnLike.dataset.liked = newLikedState ? 'true' : 'false';
      const iconLike = document.getElementById('icon-toggle-like');
      const textLike = document.getElementById('text-toggle-like');
      const statLikes = document.getElementById('stat-likes-count');

      if (statLikes) {
        let count = parseInt(statLikes.textContent) || 0;
        statLikes.textContent = newLikedState ? count + 1 : Math.max(0, count - 1);
      }

      if (newLikedState) {
        btnLike.style.background = 'rgba(247, 129, 102, 0.15)';
        btnLike.style.borderColor = '#F78166';
        btnLike.style.color = '#F78166';
        if (iconLike) iconLike.className = 'fa-solid fa-heart';
        if (textLike) textLike.textContent = 'Disukai';
      } else {
        btnLike.style.background = '#21262d';
        btnLike.style.borderColor = 'var(--border-color)';
        btnLike.style.color = 'var(--text-main)';
        if (iconLike) iconLike.className = 'fa-regular fa-heart';
        if (textLike) textLike.textContent = 'Sukai';
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
        }
      } catch (err) {
        window.showToast({ title: 'Gagal', message: err.message, type: 'error' });
      }
    });
  }

  // ==========================================
  // OPTIMISTIC UI: FAVORIT BUKU
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
        if (iconFav) iconFav.className = 'fa-solid fa-star';
        if (textFav) textFav.textContent = 'Favorit';
      } else {
        btnFav.style.background = '#21262d';
        btnFav.style.borderColor = 'var(--border-color)';
        btnFav.style.color = 'var(--text-main)';
        if (iconFav) iconFav.className = 'fa-regular fa-star';
        if (textFav) textFav.textContent = 'Favorit';
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
        }
      } catch (err) {
        window.showToast({ title: 'Gagal', message: err.message, type: 'error' });
      }
    });
  }

  // ==========================================
  // PILL BADGE POPOVER RAK BACAAN
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
            }
          } catch (err) {
            window.showToast({ title: 'Gagal', message: err.message || 'Gagal memperbarui rak.', type: 'error' });
          }
        }
      });
    });
  }

  // ==========================================
  // AJAX FORM PENGIRIMAN ULASAN INLINE
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

      if (btnSubmit) {
        btnSubmit.disabled = true;
        btnSubmit.style.opacity = '0.7';
      }
      if (btnIcon) btnIcon.className = 'fa-solid fa-spinner fa-spin';
      if (btnText) btnText.textContent = 'Menerbitkan...';

      try {
        const formData = new FormData(reviewForm);
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
          window.showToast({ title: 'Ulasan Terbit', message: data.message, type: 'success' });
          setTimeout(() => window.location.reload(), 400);
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
  // OPTIMISTIC UI: LIKE ULASAN (HELPFUL)
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
        if (data.success && badgeCount && typeof data.likes_count !== 'undefined') {
          badgeCount.textContent = data.likes_count;
        }
      } catch (err) {
        this.dataset.liked = isLiked ? 'true' : 'false';
        if (badgeCount) badgeCount.textContent = currentCount;
      }
    });
  });

  // ==========================================
  // AJAX KOMENTAR BALASAN
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
          if (countEl) countEl.textContent = (parseInt(countEl.textContent) || 0) + 1;

          if (listEl) {
            const emptyNotice = listEl.querySelector('.empty-comments-text');
            if (emptyNotice) emptyNotice.remove();

            const newComment = document.createElement('div');
            newComment.id = `detail-comment-item-${data.comment.id}`;
            newComment.className = 'comment-item-box';
            newComment.style.cssText = 'background: rgba(255, 255, 255, 0.03); padding: 8px 12px; border-radius: 6px; display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; margin-top: 6px;';
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
            listEl.prepend(newComment);
          }
        }
      } catch (err) {
        window.showToast({ title: 'Gagal', message: err.message, type: 'error' });
      } finally {
        submitBtn.disabled = false;
        submitBtn.style.opacity = '1';
      }
    });
  });

  // Hapus Komentar
  document.addEventListener('click', async function (e) {
    const btn = e.target.closest('.btn-delete-comment-ajax');
    if (!btn) return;
    e.preventDefault();

    const commentId = btn.dataset.id;
    const url = btn.dataset.url;
    const reviewId = btn.dataset.reviewId;
    const commentItem = document.getElementById(`detail-comment-item-${commentId}`);

    btn.disabled = true;
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
        if (commentItem) commentItem.remove();
        const countEl = document.getElementById(`comments-count-${reviewId}`);
        if (countEl) {
          const current = parseInt(countEl.textContent) || 0;
          countEl.textContent = Math.max(0, current - 1);
        }
      }
    } catch (err) {
      btn.disabled = false;
      window.showToast({ title: 'Gagal', message: err.message, type: 'error' });
    }
  });
});
</script>
@endpush
