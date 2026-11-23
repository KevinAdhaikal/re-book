@extends('layouts.app')

@section('title', 'Profil ' . $member->name . ' - Re:Book')

@push('styles')
<style>
  /* ==========================================
     LETTERBOXD MEMBER PROFILE HERO & AMBIENT
     ========================================== */
  .letterboxd-profile-hero {
    position: relative;
    width: calc(100% + 80px);
    margin: -30px -40px 0 -40px;
    height: 250px;
    overflow: hidden;
    background: #090c10;
  }
  .letterboxd-profile-hero-bg {
    position: absolute;
    inset: -30px;
    width: calc(100% + 60px);
    height: calc(100% + 60px);
    object-fit: cover;
    filter: blur(55px) brightness(0.22) saturate(1.4);
    transform: scale(1.18);
    pointer-events: none;
  }
  .letterboxd-profile-hero-vignette {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(13, 17, 23, 0.25) 0%, rgba(13, 17, 23, 0.75) 60%, var(--bg-main) 100%),
                radial-gradient(circle at 50% 30%, transparent 20%, rgba(13, 17, 23, 0.88) 95%);
    pointer-events: none;
  }
  .letterboxd-hero-nav {
    position: absolute;
    top: 20px;
    left: 40px;
    right: 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 10;
  }
  .letterboxd-back-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: rgba(240, 246, 252, 0.75);
    background: rgba(13, 17, 23, 0.6);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    padding: 7px 14px;
    border-radius: 20px;
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 500;
    transition: all 0.2s ease;
  }
  .letterboxd-back-btn:hover {
    color: #fff;
    background: rgba(22, 27, 34, 0.9);
    border-color: rgba(255, 255, 255, 0.25);
    transform: translateX(-2px);
  }
  .letterboxd-edit-profile-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--member-accent);
    color: #fff;
    padding: 7px 16px;
    border-radius: 20px;
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 600;
    transition: all 0.2s ease;
    box-shadow: 0 4px 14px rgba(31, 111, 235, 0.35);
  }
  .letterboxd-edit-profile-btn:hover {
    background: var(--member-accent-hover);
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(31, 111, 235, 0.45);
  }

  /* Profile Card Container */
  .profile-header-card {
    background: rgba(22, 27, 34, 0.85);
    backdrop-filter: blur(14px);
    border: 1px solid var(--border-color);
    border-radius: 14px;
    padding: 28px 32px;
    margin-top: -85px;
    margin-bottom: 35px;
    box-shadow: 0 16px 36px rgba(0, 0, 0, 0.45);
    position: relative;
    z-index: 10;
  }
  .profile-header-flex {
    display: flex;
    align-items: flex-start;
    gap: 28px;
    flex-wrap: wrap;
  }
  .profile-avatar-outer {
    position: relative;
    border-radius: 50%;
    padding: 4px;
    background: linear-gradient(135deg, rgba(88, 166, 255, 0.4) 0%, rgba(255, 255, 255, 0.1) 100%);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.5);
  }
  .profile-info-main {
    flex: 1;
    min-width: 290px;
  }
  .profile-name-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 12px;
  }
  .profile-name-group {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
  }
  .profile-name-title {
    color: #fff;
    font-size: 1.75rem;
    font-weight: 800;
    margin: 0;
    letter-spacing: -0.02em;
  }
  .profile-role-badge {
    background: rgba(31, 111, 235, 0.15);
    border: 1px solid rgba(88, 166, 255, 0.45);
    color: #58a6ff;
    font-size: 0.75rem;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .letterboxd-follow-btn {
    background: var(--member-accent);
    border: 1px solid var(--member-accent);
    color: white;
    padding: 7px 18px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    transition: all 0.2s ease;
    text-decoration: none;
    box-shadow: 0 4px 12px rgba(31, 111, 235, 0.25);
  }
  .letterboxd-follow-btn:hover {
    background: var(--member-accent-hover);
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(31, 111, 235, 0.4);
  }
  .letterboxd-follow-btn.is-following {
    background: #21262d;
    border-color: var(--border-color);
    box-shadow: none;
    color: var(--text-main);
  }
  .letterboxd-follow-btn.is-following:hover {
    border-color: #8b949e;
    background: #2a313c;
  }

  .profile-bio {
    font-size: 0.95rem;
    line-height: 1.65;
    margin: 0 0 16px 0;
    color: var(--text-main);
  }
  .profile-bio.is-empty {
    color: var(--text-muted);
    font-style: italic;
  }

  /* Genres */
  .profile-genres-wrap {
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px 12px;
  }
  .profile-genres-label {
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.6px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
  }
  .profile-genres-list {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
  }
  .profile-genre-pill {
    background: rgba(31, 111, 235, 0.1);
    border: 1px solid rgba(88, 166, 255, 0.25);
    color: #58a6ff;
    font-size: 0.78rem;
    font-weight: 500;
    padding: 3px 10px;
    border-radius: 14px;
    transition: border-color 0.15s;
  }
  .profile-genre-pill:hover {
    border-color: #58a6ff;
  }

  /* Letterboxd Stats Ribbon */
  .letterboxd-stats-bar {
    display: flex;
    align-items: center;
    gap: 20px;
    padding-top: 16px;
    border-top: 1px solid var(--border-color);
    flex-wrap: wrap;
  }
  .stat-item {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
  }
  .stat-number {
    font-size: 1.25rem;
    font-weight: 800;
    color: #fff;
    line-height: 1.1;
  }
  .stat-label {
    font-size: 0.72rem;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 2px;
  }
  .stat-divider {
    width: 1px;
    height: 28px;
    background: var(--border-color);
  }
  .stat-join-date {
    margin-left: auto;
    font-size: 0.8rem;
    color: var(--text-muted);
  }

  /* ==========================================
     4 FAVORITE BOOKS SHOWCASE (LETTERBOXD ROW)
     ========================================== */
  .section-header-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
  }
  .section-title {
    color: #fff;
    font-size: 1.15rem;
    font-weight: 700;
    letter-spacing: 0.3px;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .section-action-link {
    color: var(--text-muted);
    font-size: 0.82rem;
    font-weight: 500;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: color 0.15s;
  }
  .section-action-link:hover {
    color: var(--member-accent);
  }
  .section-badge-counter {
    font-size: 0.82rem;
    color: var(--text-muted);
    font-weight: 500;
    background: rgba(255, 255, 255, 0.06);
    padding: 2px 8px;
    border-radius: 12px;
  }

  .letterboxd-favorites-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 42px;
  }
  .letterboxd-poster-slot {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    background: #161b22;
    aspect-ratio: 2 / 3;
    box-shadow: 0 10px 24px rgba(0, 0, 0, 0.55), 0 0 0 1px rgba(255, 255, 255, 0.08);
    transition: transform 0.22s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.22s ease, border-color 0.2s ease;
  }
  .letterboxd-poster-slot:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 36px rgba(0, 0, 0, 0.75), 0 0 0 1.5px rgba(88, 166, 255, 0.6);
  }
  .letterboxd-poster-link {
    display: block;
    width: 100%;
    height: 100%;
    position: relative;
    text-decoration: none;
  }
  .letterboxd-poster-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.3s ease;
  }
  .letterboxd-poster-slot:hover .letterboxd-poster-img {
    transform: scale(1.04);
  }
  .letterboxd-poster-spine {
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    width: 8px;
    background: linear-gradient(to right, rgba(255, 255, 255, 0.16) 0%, rgba(0, 0, 0, 0.45) 50%, transparent 100%);
    pointer-events: none;
    z-index: 2;
  }
  .letterboxd-poster-fallback {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 16px;
    text-align: center;
    background: #141820;
    color: var(--text-muted);
  }
  .letterboxd-poster-fallback i {
    font-size: 2rem;
    margin-bottom: 8px;
    color: #484f58;
  }
  .letterboxd-poster-fallback span {
    font-size: 0.8rem;
    font-weight: 600;
    color: #c9d1d9;
    line-height: 1.3;
  }

  /* Overlay on Hover */
  .letterboxd-poster-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(0deg, rgba(9, 12, 16, 0.95) 0%, rgba(9, 12, 16, 0.5) 45%, transparent 100%);
    opacity: 0;
    transition: opacity 0.2s ease;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 12px;
    z-index: 3;
    pointer-events: none;
  }
  .letterboxd-poster-slot:hover .letterboxd-poster-overlay {
    opacity: 1;
  }
  .poster-overlay-title {
    color: #fff;
    font-size: 0.85rem;
    font-weight: 700;
    line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
  .poster-overlay-author {
    color: var(--text-muted);
    font-size: 0.75rem;
    margin-top: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .poster-overlay-rating {
    margin-top: 6px;
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.78rem;
    font-weight: 700;
    color: var(--star-rating);
  }

  /* Empty Favorite Slot Placeholder */
  .letterboxd-poster-placeholder {
    aspect-ratio: 2 / 3;
    border: 2px dashed rgba(255, 255, 255, 0.12);
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    color: var(--text-muted);
    background: rgba(22, 27, 34, 0.3);
    transition: all 0.2s ease;
    padding: 16px;
    text-align: center;
  }
  .letterboxd-poster-placeholder:hover {
    border-color: var(--member-accent);
    background: rgba(31, 111, 235, 0.05);
    color: #58a6ff;
    transform: translateY(-3px);
  }
  .placeholder-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.05);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.95rem;
    margin-bottom: 8px;
    transition: background 0.2s;
  }
  .letterboxd-poster-placeholder:hover .placeholder-icon {
    background: rgba(31, 111, 235, 0.2);
  }
  .placeholder-text {
    font-size: 0.78rem;
    font-weight: 600;
  }

  /* Empty State Generic */
  .profile-empty-state {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    padding: 32px 20px;
    text-align: center;
    color: var(--text-muted);
  }
  .profile-empty-state i {
    font-size: 2rem;
    color: #484f58;
    margin-bottom: 10px;
  }
  .profile-empty-state p {
    font-size: 0.9rem;
    margin: 0;
  }

  /* ==========================================
     SEDANG DIBACA (CURRENTLY READING)
     ========================================== */
  .currently-reading-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #3fb950;
    box-shadow: 0 0 8px #3fb950;
    display: inline-block;
  }
  .currently-reading-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 14px;
    margin-bottom: 42px;
  }
  .currently-reading-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    padding: 12px;
    display: flex;
    gap: 14px;
    align-items: center;
    text-decoration: none;
    transition: all 0.2s ease;
  }
  .currently-reading-card:hover {
    border-color: var(--member-accent);
    background: var(--bg-card-hover);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.35);
  }
  .currently-reading-cover {
    width: 54px;
    height: 78px;
    border-radius: 6px;
    overflow: hidden;
    background: #090c10;
    flex-shrink: 0;
    position: relative;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
  }
  .currently-reading-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .currently-reading-cover-fallback {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #484f58;
    font-size: 1.2rem;
  }
  .currently-reading-info {
    flex: 1;
    min-width: 0;
  }
  .currently-reading-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 0.7rem;
    font-weight: 700;
    color: #3fb950;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    margin-bottom: 4px;
  }
  .currently-reading-title {
    color: #fff;
    font-size: 0.92rem;
    font-weight: 700;
    margin: 0 0 2px 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .currently-reading-author {
    color: var(--text-muted);
    font-size: 0.78rem;
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  /* ==========================================
     DIARY & ULASAN BUKU (LETTERBOXD FEED)
     ========================================== */
  .diary-reviews-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
    margin-bottom: 40px;
  }
  .diary-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 20px 22px;
    display: flex;
    gap: 20px;
    align-items: flex-start;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
  }
  .diary-card:hover {
    border-color: rgba(88, 166, 255, 0.35);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
  }
  .diary-poster-wrap {
    width: 72px;
    height: 108px;
    border-radius: 6px;
    overflow: hidden;
    background: #090c10;
    flex-shrink: 0;
    position: relative;
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.5);
    display: block;
    transition: transform 0.2s ease;
  }
  .diary-poster-wrap:hover {
    transform: scale(1.04);
  }
  .diary-poster-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .diary-poster-fallback {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #484f58;
    font-size: 1.4rem;
  }
  .diary-content {
    flex: 1;
    min-width: 0;
  }
  .diary-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 10px;
  }
  .diary-book-title {
    margin: 0 0 4px 0;
    font-size: 1.1rem;
    font-weight: 700;
  }
  .diary-book-title a {
    color: #fff;
    text-decoration: none;
    transition: color 0.15s;
  }
  .diary-book-title a:hover {
    color: var(--member-accent);
  }
  .diary-meta {
    font-size: 0.8rem;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
  }
  .meta-dot {
    color: #484f58;
  }
  .diary-rating-pill {
    background: rgba(227, 179, 65, 0.12);
    border: 1px solid rgba(227, 179, 65, 0.35);
    padding: 3px 10px;
    border-radius: 16px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }
  .diary-rating-score {
    color: var(--star-rating);
    font-weight: 700;
    font-size: 0.82rem;
  }
  .diary-quote-box {
    background: rgba(255, 255, 255, 0.02);
    border-left: 3px solid var(--member-accent);
    border-radius: 4px;
    padding: 12px 16px;
    margin-bottom: 14px;
  }
  .diary-quote-text {
    font-size: 0.95rem;
    line-height: 1.65;
    color: #e6edf3;
    margin: 0;
  }
  .diary-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
  }
  .diary-stats {
    display: flex;
    align-items: center;
    gap: 16px;
    font-size: 0.8rem;
    color: var(--text-muted);
  }
  .diary-stat-item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }
  .diary-open-link {
    color: var(--member-accent);
    font-size: 0.82rem;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: gap 0.15s ease;
  }
  .diary-open-link:hover {
    color: var(--member-accent-hover);
    gap: 8px;
  }

  /* Responsive Adjustments */
  @media (max-width: 768px) {
    .letterboxd-profile-hero {
      margin: -20px -16px 0 -16px;
      width: calc(100% + 32px);
      height: 180px;
    }
    .letterboxd-hero-nav {
      left: 16px;
      right: 16px;
      top: 16px;
    }
    .profile-header-card {
      margin-top: -60px;
      padding: 20px 18px;
    }
    .letterboxd-favorites-grid {
      grid-template-columns: repeat(2, 1fr);
      gap: 12px;
    }
    .stat-divider {
      display: none;
    }
    .stat-join-date {
      margin-left: 0;
      width: 100%;
    }
  }
</style>
@endpush

@section('content')
@php
  // Ambil cover untuk ambient backdrop blur Letterboxd
  $heroCover = null;
  if ($favoriteBooks->isNotEmpty() && $favoriteBooks->first()->cover) {
      $heroCover = asset('storage/' . $favoriteBooks->first()->cover);
  } elseif ($memberReviews->isNotEmpty() && optional($memberReviews->first()->book)->cover) {
      $heroCover = asset('storage/' . $memberReviews->first()->book->cover);
  }
@endphp

<!-- Ambient Hero Backdrop Letterboxd -->
<div class="letterboxd-profile-hero">
  @if($heroCover)
    <img src="{{ $heroCover }}" alt="" class="letterboxd-profile-hero-bg">
  @endif
  <div class="letterboxd-profile-hero-vignette"></div>

  <!-- Top Hero Navigation -->
  <div class="letterboxd-hero-nav">
    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('home') }}" class="letterboxd-back-btn">
      <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>

    @if(auth()->check() && auth()->id() === $member->id)
      <a href="{{ route('member.profile') }}" class="letterboxd-edit-profile-btn">
        <i class="fa-solid fa-pen-to-square"></i> Edit Profil Saya
      </a>
    @endif
  </div>
</div>

<div style="max-width: 1040px; margin: 0 auto; position: relative;">

  <!-- Header Profil Card (Overlapping Backdrop) -->
  <div class="profile-header-card">
    <div class="profile-header-flex">
      
      <!-- Avatar Profil -->
      <div class="profile-avatar-outer">
        <x-user-avatar :user="$member" size="xl" :border="false" />
      </div>

      <!-- Detail Info Member -->
      <div class="profile-info-main">
        <div class="profile-name-bar">
          <div class="profile-name-group">
            <h1 class="profile-name-title">{{ $member->name }}</h1>
            <span class="profile-role-badge">
              {{ $member->role === 'admin' ? 'Administrator' : 'Member Komunitas' }}
            </span>
          </div>

          {{-- Tombol Follow / Unfollow (Optimistic UI) --}}
          @auth
            @if(auth()->id() !== $member->id)
              @php
                $isFollowing = auth()->user()->isFollowing($member->id);
              @endphp
              <button type="button" id="btn-toggle-follow"
                      data-user-id="{{ $member->id }}"
                      data-following="{{ $isFollowing ? 'true' : 'false' }}"
                      data-url="{{ route('member.users.follow', $member->id) }}"
                      class="letterboxd-follow-btn {{ $isFollowing ? 'is-following' : '' }}">
                <i class="fa-solid {{ $isFollowing ? 'fa-user-check' : 'fa-user-plus' }}" id="icon-toggle-follow"></i>
                <span id="text-toggle-follow">{{ $isFollowing ? 'Mengikuti' : 'Ikuti Member' }}</span>
              </button>
            @endif
          @else
            <a href="{{ route('login') }}" class="letterboxd-follow-btn">
              <i class="fa-solid fa-user-plus"></i> Ikuti Member
            </a>
          @endauth
        </div>

        <!-- Bio Profil (Fallback 'Belum ada bio.' jika kosong) -->
        <p class="profile-bio {{ empty($member->bio) ? 'is-empty' : '' }}">
          {{ !empty($member->bio) ? $member->bio : 'Belum ada bio.' }}
        </p>

        <!-- Genre Favorit Badges -->
        @if(!empty($member->favorite_genres) && count($member->favorite_genres) > 0)
          <div class="profile-genres-wrap">
            <span class="profile-genres-label"><i class="fa-solid fa-bookmark" style="color: var(--member-accent);"></i> Genre:</span>
            <div class="profile-genres-list">
              @foreach($member->favorite_genres as $genre)
                <span class="profile-genre-pill">{{ $genre }}</span>
              @endforeach
            </div>
          </div>
        @endif

        <!-- Statistik Profil (Letterboxd Metrics Ribbon) -->
        <div class="letterboxd-stats-bar">
          <div class="stat-item">
            <span class="stat-number">{{ $readCount }}</span>
            <span class="stat-label">Buku Dibaca</span>
          </div>
          <div class="stat-divider"></div>
          <div class="stat-item">
            <span class="stat-number">{{ $member->reading_shelves_count ?? $member->readingShelves()->count() }}</span>
            <span class="stat-label">Di Rak</span>
          </div>
          <div class="stat-divider"></div>
          <div class="stat-item">
            <span class="stat-number">{{ $reviewsCount }}</span>
            <span class="stat-label">Ulasan</span>
          </div>
          <div class="stat-divider"></div>
          <div class="stat-item">
            <span class="stat-number" id="member-followers-count">{{ $member->followers_count ?? $member->followers()->count() }}</span>
            <span class="stat-label">Pengikut</span>
          </div>
          <div class="stat-divider"></div>
          <div class="stat-item">
            <span class="stat-number">{{ $member->following_count ?? $member->following()->count() }}</span>
            <span class="stat-label">Mengikuti</span>
          </div>
          <div class="stat-divider"></div>
          <div class="stat-join-date">
            Bergabung sejak <strong style="color: #fff;">{{ $member->created_at ? $member->created_at->format('M Y') : '-' }}</strong>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Showcase 4 Buku Favorit Member (Letterboxd 4 Favorites Grid) -->
  <div style="margin-bottom: 40px;">
    <div class="section-header-row">
      <h3 class="section-title">
        <span style="color: var(--star-rating);">⭐</span> 4 Buku Favorit Pilihan {{ $member->name }}
      </h3>
      @if(auth()->check() && auth()->id() === $member->id)
        <a href="{{ route('member.profile') }}" class="section-action-link">
          <i class="fa-solid fa-sliders"></i> Atur Buku Favorit
        </a>
      @endif
    </div>

    @if($favoriteBooks->isEmpty() && (!auth()->check() || auth()->id() !== $member->id))
      <div class="profile-empty-state">
        <i class="fa-regular fa-star"></i>
        <p>Member ini belum memajang buku favorit.</p>
      </div>
    @else
      <div class="letterboxd-favorites-grid">
        @foreach ($favoriteBooks as $b)
          <div class="letterboxd-poster-slot">
            <a href="{{ route('books.show', $b->id) }}" class="letterboxd-poster-link">
              @if($b->cover)
                <img src="{{ asset('storage/' . $b->cover) }}" alt="{{ $b->title }}" class="letterboxd-poster-img" loading="lazy">
              @else
                <div class="letterboxd-poster-fallback">
                  <i class="fa-solid fa-book"></i>
                  <span>{{ Str::limit($b->title, 35) }}</span>
                </div>
              @endif
              <div class="letterboxd-poster-spine"></div>
              
              <!-- Hover Overlay with Title & Rating -->
              <div class="letterboxd-poster-overlay">
                <span class="poster-overlay-title">{{ $b->title }}</span>
                <span class="poster-overlay-author">{{ $b->author }}</span>
                @if($b->reviews_avg_rating)
                  <div class="poster-overlay-rating">
                    <i class="fa-solid fa-star"></i>
                    <span>{{ number_format($b->reviews_avg_rating, 1) }}</span>
                  </div>
                @endif
              </div>
            </a>
          </div>
        @endforeach

        {{-- Slot Kosong Tambahan Jika Melihat Profil Sendiri & Belum Genap 4 --}}
        @if(auth()->check() && auth()->id() === $member->id && $favoriteBooks->count() < 4)
          @for($i = $favoriteBooks->count(); $i < 4; $i++)
            <a href="{{ route('member.profile') }}" class="letterboxd-poster-placeholder">
              <div class="placeholder-icon"><i class="fa-solid fa-plus"></i></div>
              <span class="placeholder-text">Pilih Buku Favorit #{{ $i + 1 }}</span>
            </a>
          @endfor
        @endif
      </div>
    @endif
  </div>

  <!-- Buku yang Sedang Dibaca (Currently Reading) -->
  @if($currentlyReading->isNotEmpty())
    <div style="margin-bottom: 40px;">
      <div class="section-header-row">
        <h3 class="section-title">
          <span class="currently-reading-dot"></span> Sedang Dibaca
          <span class="section-badge-counter">{{ $currentlyReading->count() }}</span>
        </h3>
      </div>

      <div class="currently-reading-grid">
        @foreach($currentlyReading as $shelfItem)
          @php $cBook = $shelfItem->book; @endphp
          @if($cBook)
            <a href="{{ route('books.show', $cBook->id) }}" class="currently-reading-card">
              <div class="currently-reading-cover">
                @if($cBook->cover)
                  <img src="{{ asset('storage/' . $cBook->cover) }}" alt="{{ $cBook->title }}">
                @else
                  <div class="currently-reading-cover-fallback"><i class="fa-solid fa-book"></i></div>
                @endif
                <div class="letterboxd-poster-spine"></div>
              </div>
              <div class="currently-reading-info">
                <span class="currently-reading-badge">
                  <i class="fa-solid fa-book-open-reader"></i> Membaca
                </span>
                <h4 class="currently-reading-title">{{ $cBook->title }}</h4>
                <p class="currently-reading-author">{{ $cBook->author }}</p>
              </div>
            </a>
          @endif
        @endforeach
      </div>
    </div>
  @endif

  <!-- Riwayat Ulasan Buku yang Ditulis Member (Letterboxd Diary Feed) -->
  <div>
    <div class="section-header-row">
      <h3 class="section-title">
        <i class="fa-solid fa-pen-nib" style="color: var(--member-accent);"></i> Ulasan Buku oleh {{ $member->name }}
        <span class="section-badge-counter">{{ $reviewsCount }}</span>
      </h3>
    </div>

    @if($memberReviews->isEmpty())
      <div class="profile-empty-state">
        <i class="fa-regular fa-comment-dots"></i>
        <p>Member ini belum menulis ulasan buku.</p>
      </div>
    @else
      <div class="diary-reviews-list">
        @foreach ($memberReviews as $rev)
          <div class="diary-card">
            
            <!-- Cover Poster Buku -->
            <a href="{{ route('books.show', $rev->book_id) }}" class="diary-poster-wrap">
              @if(isset($rev->book->cover) && $rev->book->cover)
                <img src="{{ asset('storage/' . $rev->book->cover) }}" alt="{{ $rev->book->title ?? 'Buku' }}" class="diary-poster-img" loading="lazy">
              @else
                <div class="diary-poster-fallback">
                  <i class="fa-solid fa-book"></i>
                </div>
              @endif
              <div class="letterboxd-poster-spine"></div>
            </a>

            <!-- Konten Diary Ulasan -->
            <div class="diary-content">
              <div class="diary-header">
                <div>
                  <h4 class="diary-book-title">
                    <a href="{{ route('books.show', $rev->book_id) }}">
                      {{ $rev->book->title ?? 'Buku' }}
                    </a>
                  </h4>
                  <div class="diary-meta">
                    @if(isset($rev->book->author))
                      <span>{{ $rev->book->author }}</span>
                      <span class="meta-dot">&bull;</span>
                    @endif
                    <span>{{ $rev->book->genre ?? 'Novel' }}</span>
                    <span class="meta-dot">&bull;</span>
                    <span>Ditulis pada {{ $rev->created_at ? $rev->created_at->format('d M Y') : '' }}</span>
                  </div>
                </div>

                <!-- Star Rating Badge -->
                <div class="diary-rating-pill">
                  <x-star-rating :rating="$rev->rating" size="xs" :showScore="false" />
                  <span class="diary-rating-score">{{ number_format($rev->rating, 1) }}</span>
                </div>
              </div>

              <!-- Quote Teks Ulasan -->
              <div class="diary-quote-box">
                <p class="diary-quote-text font-serif">
                  "{{ $rev->content }}"
                </p>
              </div>

              <!-- Social Footer -->
              <div class="diary-footer">
                <div class="diary-stats">
                  <span class="diary-stat-item">
                    <i class="fa-solid fa-heart" style="color: var(--favorite-coral);"></i>
                    <span>{{ $rev->liked_by_users_count ?? $rev->likedByUsers->count() }} Menyukai</span>
                  </span>
                  <span class="diary-stat-item">
                    <i class="fa-regular fa-comments"></i>
                    <span>{{ $rev->comments->count() }} Komentar Balasan</span>
                  </span>
                </div>
                <a href="{{ route('books.show', $rev->book_id) }}" class="diary-open-link">
                  Buka Halaman Buku <i class="fa-solid fa-arrow-right"></i>
                </a>
              </div>
            </div>

          </div>
        @endforeach
      </div>
    @endif
  </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const btnFollow = document.getElementById('btn-toggle-follow');
  if (btnFollow) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    btnFollow.addEventListener('click', async function () {
      const isCurrentlyFollowing = btnFollow.dataset.following === 'true';
      const newFollowingState = !isCurrentlyFollowing;
      const url = btnFollow.dataset.url;
      const iconEl = document.getElementById('icon-toggle-follow');
      const textEl = document.getElementById('text-toggle-follow');
      const countEl = document.getElementById('member-followers-count');

      let currentCount = parseInt(countEl ? countEl.textContent : '0') || 0;
      let nextCount = newFollowingState ? currentCount + 1 : Math.max(0, currentCount - 1);

      // Optimistic visual update
      btnFollow.dataset.following = newFollowingState ? 'true' : 'false';
      if (countEl) countEl.textContent = nextCount;

      if (newFollowingState) {
        btnFollow.classList.add('is-following');
        if (iconEl) iconEl.className = 'fa-solid fa-user-check';
        if (textEl) textEl.textContent = 'Mengikuti';
      } else {
        btnFollow.classList.remove('is-following');
        if (iconEl) iconEl.className = 'fa-solid fa-user-plus';
        if (textEl) textEl.textContent = 'Ikuti Member';
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
          if (countEl && typeof data.followers_count !== 'undefined') {
            countEl.textContent = data.followers_count;
          }
          if (window.showToast) {
            window.showToast({
              title: newFollowingState ? 'Mengikuti' : 'Berhenti Mengikuti',
              message: data.message,
              type: 'success'
            });
          }
        } else {
          throw new Error(data.message);
        }
      } catch (err) {
        // Rollback
        btnFollow.dataset.following = isCurrentlyFollowing ? 'true' : 'false';
        if (countEl) countEl.textContent = currentCount;
        if (isCurrentlyFollowing) {
          btnFollow.classList.add('is-following');
          if (iconEl) iconEl.className = 'fa-solid fa-user-check';
          if (textEl) textEl.textContent = 'Mengikuti';
        } else {
          btnFollow.classList.remove('is-following');
          if (iconEl) iconEl.className = 'fa-solid fa-user-plus';
          if (textEl) textEl.textContent = 'Ikuti Member';
        }
        if (window.showToast) {
          window.showToast({ title: 'Gagal', message: err.message || 'Koneksi bermasalah.', type: 'error' });
        }
      }
    });
  }
});
</script>
@endpush
