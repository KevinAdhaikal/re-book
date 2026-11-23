@extends('layouts.member')

@section('title', 'Pengaturan Profil Member - Re:Book')

@push('styles')
<style>
  /* ==========================================
     MEMBER PROFILE SETTINGS & SHOWCASE STYLES
     ========================================== */
  .settings-container {
    max-width: 1040px;
    margin: 0 auto 40px auto;
  }

  /* Header Section */
  .settings-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 16px;
  }
  .settings-title {
    color: #fff;
    font-size: 1.5rem;
    font-weight: 800;
    margin: 0 0 4px 0;
    letter-spacing: -0.02em;
  }
  .settings-subtitle {
    color: var(--text-muted);
    font-size: 0.88rem;
    margin: 0;
  }
  .view-public-btn {
    background: #21262d;
    border: 1px solid var(--border-color);
    color: #58a6ff;
    font-size: 0.85rem;
    font-weight: 600;
    padding: 8px 18px;
    border-radius: 8px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
  }
  .view-public-btn:hover {
    background: #30363d;
    color: #fff;
    border-color: #8b949e;
    transform: translateY(-1px);
  }

  /* Modern Tab Navigation */
  .profile-tabs-nav {
    display: flex;
    gap: 8px;
    background: #161b22;
    padding: 6px;
    border-radius: 12px;
    border: 1px solid var(--border-color);
    margin-bottom: 28px;
    overflow-x: auto;
  }
  .profile-tab-btn {
    flex: 1;
    min-width: 160px;
    background: transparent;
    border: none;
    color: var(--text-muted);
    padding: 10px 18px;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.2s ease;
    white-space: nowrap;
  }
  .profile-tab-btn:hover {
    color: var(--text-main);
    background: rgba(255, 255, 255, 0.04);
  }
  .profile-tab-btn.active {
    background: var(--member-accent);
    color: #fff;
    box-shadow: 0 4px 14px rgba(31, 111, 235, 0.35);
  }
  .tab-badge {
    background: rgba(0, 0, 0, 0.25);
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 0.75rem;
    font-weight: 700;
  }
  .tab-error-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: var(--admin-danger);
  }

  /* Tab Content Panes */
  .tab-pane {
    display: none;
    animation: fadeIn 0.25s ease forwards;
  }
  .tab-pane.active {
    display: block;
  }
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(6px); }
    to { opacity: 1; transform: translateY(0); }
  }

  /* Tab 1 Layout (Form + Live Mini Preview) */
  .identity-layout {
    display: grid;
    grid-template-columns: minmax(0, 1.45fr) minmax(320px, 1fr);
    gap: 28px;
    align-items: start;
  }
  @media (max-width: 900px) {
    .identity-layout {
      grid-template-columns: 1fr;
    }
    .preview-sticky-wrap {
      order: 2;
    }
  }

  /* Card Containers */
  .settings-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 26px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
  }

  /* Avatar Dropzone */
  .avatar-dropzone-box {
    display: flex;
    align-items: center;
    gap: 22px;
    padding-bottom: 24px;
    border-bottom: 1px solid var(--border-color);
    margin-bottom: 24px;
    flex-wrap: wrap;
  }
  .avatar-circle-wrap {
    position: relative;
    width: 96px;
    height: 96px;
    border-radius: 50%;
    overflow: hidden;
    background: #0d1117;
    border: 2px solid var(--member-accent);
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.5);
    cursor: pointer;
    flex-shrink: 0;
  }
  .avatar-circle-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }
  .avatar-initial-fallback {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.2rem;
    font-weight: 800;
    color: #58a6ff;
    background: #161b22;
  }
  .avatar-circle-hover {
    position: absolute;
    inset: 0;
    background: rgba(13, 17, 23, 0.7);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 0.75rem;
    font-weight: 600;
    opacity: 0;
    transition: opacity 0.2s ease;
  }
  .avatar-circle-wrap:hover .avatar-circle-hover {
    opacity: 1;
  }

  /* Live Mini Preview Card */
  .live-preview-card {
    background: #161b22;
    border: 1px solid var(--border-color);
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.45);
    position: sticky;
    top: 24px;
  }
  .preview-hero-bg {
    height: 85px;
    background: linear-gradient(135deg, rgba(31, 111, 235, 0.3) 0%, rgba(227, 179, 65, 0.2) 100%), #0d1117;
    position: relative;
  }
  .preview-hero-badge {
    position: absolute;
    top: 10px;
    right: 12px;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: #58a6ff;
    font-size: 0.72rem;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
  }
  .preview-pulse-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #3fb950;
    box-shadow: 0 0 6px #3fb950;
  }
  .preview-body {
    padding: 0 20px 20px 20px;
    position: relative;
    margin-top: -36px;
  }
  .preview-avatar-ring {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid #161b22;
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.5);
    background: #0d1117;
    margin-bottom: 12px;
  }
  .preview-avatar-ring img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .preview-name {
    color: #fff;
    font-size: 1.25rem;
    font-weight: 800;
    margin: 0 0 4px 0;
    word-break: break-word;
  }
  .preview-role {
    display: inline-block;
    background: rgba(31, 111, 235, 0.15);
    border: 1px solid rgba(88, 166, 255, 0.4);
    color: #58a6ff;
    font-size: 0.7rem;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 12px;
    text-transform: uppercase;
    margin-bottom: 12px;
  }
  .preview-bio {
    color: var(--text-main);
    font-size: 0.88rem;
    line-height: 1.5;
    margin: 0 0 14px 0;
    word-break: break-word;
  }
  .preview-bio.is-empty {
    color: var(--text-muted);
    font-style: italic;
  }
  .preview-genres-wrap {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 14px;
  }
  .preview-genre-pill {
    background: rgba(31, 111, 235, 0.12);
    border: 1px solid rgba(88, 166, 255, 0.3);
    color: #58a6ff;
    font-size: 0.72rem;
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 10px;
  }
  .preview-footer-tip {
    font-size: 0.75rem;
    color: var(--text-muted);
    border-top: 1px solid var(--border-color);
    padding-top: 12px;
    margin-top: 12px;
    display: flex;
    align-items: center;
    gap: 6px;
  }

  /* Form Elements */
  .form-group {
    margin-bottom: 20px;
  }
  .form-label {
    display: block;
    color: var(--text-main);
    font-size: 0.88rem;
    font-weight: 600;
    margin-bottom: 8px;
  }
  .form-input {
    width: 100%;
    padding: 10px 14px;
    background: #0d1117;
    border: 1px solid var(--border-color);
    color: white;
    border-radius: 8px;
    font-size: 0.9rem;
    outline: none;
    transition: all 0.2s ease;
  }
  .form-input:focus {
    border-color: var(--member-accent);
    box-shadow: 0 0 0 3px rgba(31, 111, 235, 0.2);
  }
  .form-input:disabled {
    background: rgba(22, 27, 34, 0.6);
    color: #8b949e;
    cursor: not-allowed;
  }
  .form-hint {
    color: var(--text-muted);
    font-size: 0.78rem;
    margin-top: 6px;
    display: block;
  }

  /* Checkbox Genre Pills */
  .genre-pill-item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 14px;
    border-radius: 20px;
    font-size: 0.82rem;
    font-weight: 500;
    cursor: pointer;
    user-select: none;
    transition: all 0.2s;
    border: 1px solid var(--border-color);
    background: #0d1117;
    color: var(--text-muted);
  }
  .genre-pill-item.active {
    border-color: var(--member-accent);
    background: rgba(31, 111, 235, 0.18);
    color: #58a6ff;
  }

  /* ==========================================
     TAB 2: 4 FAVORITE BOOKS SHOWCASE CURATOR
     ========================================== */
  .fav-slots-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
  }
  @media (max-width: 680px) {
    .fav-slots-grid {
      grid-template-columns: repeat(2, 1fr);
      gap: 12px;
    }
  }

  .fav-slot-card {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    background: #0d1117;
    aspect-ratio: 2 / 3;
    border: 2px solid var(--border-color);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.45);
    transition: all 0.2s ease;
  }
  .fav-slot-card.is-filled {
    border-color: var(--member-accent);
  }
  .fav-slot-card.is-empty {
    border: 2px dashed rgba(255, 255, 255, 0.15);
    background: rgba(22, 27, 34, 0.4);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 14px;
    cursor: pointer;
  }
  .fav-slot-card.is-empty:hover {
    border-color: var(--member-accent);
    background: rgba(31, 111, 235, 0.05);
    color: #58a6ff;
    transform: translateY(-2px);
  }
  .fav-slot-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }
  .fav-slot-spine {
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    width: 8px;
    background: linear-gradient(to right, rgba(255, 255, 255, 0.18) 0%, rgba(0, 0, 0, 0.45) 50%, transparent 100%);
    pointer-events: none;
    z-index: 2;
  }
  .fav-slot-badge {
    position: absolute;
    top: 8px;
    left: 8px;
    background: rgba(0, 0, 0, 0.75);
    color: #e3b341;
    font-size: 0.7rem;
    font-weight: 700;
    padding: 3px 7px;
    border-radius: 4px;
    z-index: 3;
  }
  .fav-slot-remove-btn {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 26px;
    height: 26px;
    border-radius: 50%;
    background: rgba(218, 54, 51, 0.85);
    color: white;
    border: none;
    font-size: 0.75rem;
    cursor: pointer;
    z-index: 3;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.15s ease, background 0.15s ease;
  }
  .fav-slot-remove-btn:hover {
    background: #da3633;
    transform: scale(1.1);
  }
  .fav-slot-caption {
    position: absolute;
    bottom: 0;
    inset-inline: 0;
    background: linear-gradient(0deg, rgba(9, 12, 16, 0.95) 0%, rgba(9, 12, 16, 0.5) 70%, transparent 100%);
    padding: 24px 10px 8px 10px;
    z-index: 2;
  }
  .fav-slot-title {
    color: #fff;
    font-size: 0.78rem;
    font-weight: 700;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .fav-slot-author {
    color: var(--text-muted);
    font-size: 0.68rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  /* Catalog Shelf & Search */
  .catalog-search-wrap {
    position: relative;
    margin-bottom: 16px;
  }
  .catalog-search-input {
    width: 100%;
    padding: 11px 14px 11px 40px;
    background: #0d1117;
    border: 1px solid var(--border-color);
    color: white;
    border-radius: 8px;
    font-size: 0.9rem;
    outline: none;
    transition: all 0.2s ease;
  }
  .catalog-search-input:focus {
    border-color: var(--member-accent);
    box-shadow: 0 0 0 3px rgba(31, 111, 235, 0.2);
  }
  .catalog-search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 0.88rem;
  }

  .catalog-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(135px, 1fr));
    gap: 14px;
    max-height: 380px;
    overflow-y: auto;
    padding: 4px;
  }
  .catalog-card {
    background: #0d1117;
    border: 1.5px solid #21262d;
    border-radius: 8px;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    flex-direction: column;
    position: relative;
  }
  .catalog-card:hover {
    border-color: var(--member-accent);
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
  }
  .catalog-card.is-selected {
    border-color: #3fb950;
    background: rgba(63, 185, 80, 0.05);
  }
  .catalog-card-cover {
    height: 160px;
    background: #161b22;
    position: relative;
    overflow: hidden;
  }
  .catalog-card-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .catalog-card-details {
    padding: 8px 10px;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }
  .catalog-card-title {
    color: #fff;
    font-size: 0.78rem;
    font-weight: 700;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 2px;
  }
  .catalog-card-author {
    color: var(--text-muted);
    font-size: 0.7rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .catalog-card-badge {
    position: absolute;
    top: 6px;
    right: 6px;
    background: #3fb950;
    color: #fff;
    font-size: 0.68rem;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 12px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.4);
    z-index: 2;
  }
  .catalog-card-action {
    margin-top: 6px;
    font-size: 0.72rem;
    font-weight: 600;
  }

  /* Password Toggle */
  .password-input-wrap {
    position: relative;
  }
  .password-toggle-btn {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    font-size: 0.9rem;
    padding: 4px;
  }
  .password-toggle-btn:hover {
    color: #fff;
  }
</style>
@endpush

@section('member_content')
<div class="settings-container">

  <!-- Header & Navigasi Publik -->
  <div class="settings-header">
    <div>
      <h1 class="settings-title">Pengaturan Profil</h1>
      <p class="settings-subtitle">Kelola foto profil, identitas, kurasi 4 buku favorit Letterboxd, dan keamanan akun Anda.</p>
    </div>
    <a href="{{ route('users.profile', $user->id) }}" class="view-public-btn">
      <i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat Profil Publik
    </a>
  </div>

  {{-- Error Banner Alert --}}
  @if ($errors->any())
    <div style="background: rgba(218, 54, 51, 0.15); border: 1px solid var(--admin-danger); color: #f85149; padding: 14px 18px; border-radius: 8px; margin-bottom: 24px; font-size: 0.88rem;">
      <div style="font-weight: 600; margin-bottom: 6px;"><i class="fa-solid fa-triangle-exclamation"></i> Terjadi kesalahan:</div>
      <ul style="margin: 0; padding-left: 20px;">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <!-- Tab Navigation Bar (Letterboxd Style) -->
  <div class="profile-tabs-nav">
    <button type="button" class="profile-tab-btn active" onclick="switchTab('identity')" data-tab="identity">
      <i class="fa-solid fa-user-pen"></i> Profil & Identitas
    </button>
    <button type="button" class="profile-tab-btn" onclick="switchTab('favorites')" data-tab="favorites">
      <i class="fa-solid fa-star" style="color: var(--star-rating);"></i> 4 Buku Favorit
      <span id="navFavBadge" class="tab-badge">{{ $favoriteBooks->count() }}/4</span>
    </button>
    <button type="button" class="profile-tab-btn" onclick="switchTab('security')" data-tab="security">
      <i class="fa-solid fa-shield-halved"></i> Keamanan Akun
      @if($errors->has('current_password') || $errors->has('password') || $errors->has('password_confirmation'))
        <span class="tab-error-dot" title="Terdapat error di tab ini"></span>
      @endif
    </button>
  </div>

  {{-- ========================================================= --}}
  {{-- TAB 1: PROFIL & IDENTITAS (FORM + LIVE MINI-CARD PREVIEW)  --}}
  {{-- ========================================================= --}}
  <div id="tab-identity" class="tab-pane active">
    <div class="identity-layout">

      <!-- Form Edit Profil di Kiri -->
      <div class="settings-card">
        <form action="{{ route('member.profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
          @csrf
          @method('PUT')

          <!-- Upload Avatar Drag & Drop Zone -->
          <div class="avatar-dropzone-box" id="avatarDropzone">
            <div class="avatar-circle-wrap" onclick="document.getElementById('avatarInput').click();" title="Klik untuk ganti foto profil">
              <img id="avatarPreview" src="{{ $user->avatarUrl() ?: '' }}" alt="{{ $user->name }}" style="{{ $user->avatarUrl() ? 'display: block;' : 'display: none;' }}">
              <div id="avatarInitials" class="avatar-initial-fallback" style="{{ $user->avatarUrl() ? 'display: none;' : 'display: flex;' }}">
                {{ strtoupper(substr($user->name, 0, 1)) }}
              </div>
              <div class="avatar-circle-hover">
                <i class="fa-solid fa-camera" style="font-size: 1.2rem; margin-bottom: 4px;"></i>
                <span>Ganti</span>
              </div>
            </div>

            <div style="flex: 1; min-width: 220px;">
              <h4 style="color: white; margin: 0 0 4px 0; font-size: 0.95rem; font-weight: 700;">Foto Profil</h4>
              <p style="color: var(--text-muted); font-size: 0.8rem; margin: 0 0 10px 0;">
                Format: JPG, PNG, WEBP. Maksimal ukuran 2 MB.
              </p>

              <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                <input type="file" name="avatar" id="avatarInput" accept="image/jpeg,image/png,image/jpg,image/webp" style="display: none;">
                <button type="button" onclick="document.getElementById('avatarInput').click();" class="view-public-btn" style="padding: 6px 14px; font-size: 0.82rem; cursor: pointer;">
                  <i class="fa-solid fa-camera"></i> Pilih Foto Baru
                </button>

                @if($user->avatar)
                  <button type="button" onclick="document.getElementById('deleteAvatarForm').submit();" style="background: rgba(218, 54, 51, 0.15); border: 1px solid var(--admin-danger); color: #f85149; font-size: 0.82rem; font-weight: 600; padding: 6px 14px; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-trash-can"></i> Hapus Foto
                  </button>
                @endif
              </div>
              <div id="fileSelectedName" style="color: #58a6ff; font-size: 0.78rem; margin-top: 6px; display: none;"></div>
            </div>
          </div>

          <!-- Input Nama Lengkap -->
          <div class="form-group">
            <label for="name" class="form-label">
              Nama Lengkap <span style="color: var(--admin-danger);">*</span>
            </label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required maxlength="255" class="form-input" oninput="updateLivePreview();">
            <small class="form-hint">Nama ini akan ditampilkan pada profil publik, ulasan buku, dan komentar.</small>
          </div>

          <!-- Alamat Email (Readonly) -->
          <div class="form-group">
            <label class="form-label" style="color: var(--text-muted);">
              Alamat Email (Akun) <i class="fa-solid fa-lock" style="font-size: 0.75rem; margin-left: 4px;"></i>
            </label>
            <input type="email" value="{{ $user->email }}" disabled class="form-input">
            <small class="form-hint">Email bersifat privat, tidak tampil di profil publik, dan digunakan untuk login.</small>
          </div>

          <!-- Bio Profil -->
          <div class="form-group">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
              <label for="bio" class="form-label" style="margin: 0;">Bio Profil</label>
              <span style="font-size: 0.78rem; color: var(--text-muted);">
                <span id="charCount">{{ strlen(old('bio', $user->bio ?? '')) }}</span> / 300 karakter
              </span>
            </div>
            <textarea id="bio" name="bio" rows="3" maxlength="300" placeholder="Ceritakan sedikit tentang dirimu atau minat membaca Anda..."
                      class="form-input" style="resize: vertical; min-height: 85px;"
                      oninput="document.getElementById('charCount').textContent = this.value.length; updateLivePreview();">{{ old('bio', $user->bio) }}</textarea>
            <small class="form-hint">Deskripsikan selera buku atau diri Anda. Tampil di kartu profil publik.</small>
          </div>

          <!-- Checkbox Genre Favorit -->
          <div class="form-group">
            <label class="form-label">Genre Buku Favorit</label>
            <p style="color: var(--text-muted); font-size: 0.8rem; margin: 0 0 10px 0;">
              Pilih satu atau beberapa genre yang Anda sukai untuk menghias profil Anda.
            </p>

            @php
              $currentGenres = (array) (old('favorite_genres', $user->favorite_genres ?? []));
            @endphp

            <div style="display: flex; flex-wrap: wrap; gap: 8px;" id="genrePillsWrap">
              @foreach ($availableGenres as $genre)
                @php
                  $isChecked = in_array($genre, $currentGenres);
                @endphp
                <label class="genre-pill-item {{ $isChecked ? 'active' : '' }}">
                  <input type="checkbox" name="favorite_genres[]" value="{{ $genre }}" {{ $isChecked ? 'checked' : '' }} style="display: none;" onchange="toggleGenreBadge(this)">
                  <i class="fa-solid fa-check check-icon" style="font-size: 0.72rem; display: {{ $isChecked ? 'inline' : 'none' }};"></i>
                  <span>{{ $genre }}</span>
                </label>
              @endforeach
            </div>
          </div>

          <!-- Tombol Aksi Simpan Profil -->
          <div style="display: flex; justify-content: flex-end; align-items: center; gap: 12px; margin-top: 26px; padding-top: 18px; border-top: 1px solid var(--border-color);">
            <button type="reset" onclick="setTimeout(resetFormStyles, 10);" class="view-public-btn" style="cursor: pointer; color: var(--text-muted);">
              Reset
            </button>
            <button type="submit" style="background: var(--member-accent); color: white; border: none; padding: 10px 24px; border-radius: 8px; font-size: 0.88rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(31, 111, 235, 0.35);">
              <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
            </button>
          </div>
        </form>
      </div>

      <!-- Live Mini-Card Preview di Kanan -->
      <div class="preview-sticky-wrap">
        <div class="live-preview-card">
          <div class="preview-hero-bg">
            <span class="preview-hero-badge">
              <span class="preview-pulse-dot"></span> Live Preview
            </span>
          </div>
          <div class="preview-body">
            <div class="preview-avatar-ring">
              <img id="miniPreviewAvatar" src="{{ $user->avatarUrl() ?: '' }}" alt="Avatar" style="{{ $user->avatarUrl() ? 'display: block;' : 'display: none;' }}">
              <div id="miniPreviewInitial" class="avatar-initial-fallback" style="font-size: 1.8rem; {{ $user->avatarUrl() ? 'display: none;' : 'display: flex;' }}">
                {{ strtoupper(substr($user->name, 0, 1)) }}
              </div>
            </div>

            <h3 class="preview-name" id="miniPreviewName">{{ $user->name ?: 'Nama Member' }}</h3>
            <span class="preview-role">Member Komunitas</span>

            <p class="preview-bio {{ empty($user->bio) ? 'is-empty' : '' }}" id="miniPreviewBio">
              {{ !empty($user->bio) ? $user->bio : 'Belum ada bio.' }}
            </p>

            <div class="preview-genres-wrap" id="miniPreviewGenres">
              @if(!empty($user->favorite_genres))
                @foreach($user->favorite_genres as $genre)
                  <span class="preview-genre-pill">{{ $genre }}</span>
                @endforeach
              @endif
            </div>

            <div class="preview-footer-tip">
              <i class="fa-solid fa-circle-info" style="color: var(--member-accent);"></i>
              <span>Perubahan otomatis terlihat saat Anda mengetik atau memilih opsi.</span>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  {{-- ========================================================= --}}
  {{-- TAB 2: KURASI 4 BUKU FAVORIT (INTERACTIVE SHOWCASE STUDIO) --}}
  {{-- ========================================================= --}}
  <div id="tab-favorites" class="tab-pane">
    <div class="settings-card">
      <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
        <div>
          <h3 style="color: white; font-size: 1.2rem; font-weight: 700; margin: 0 0 4px 0; display: flex; align-items: center; gap: 8px;">
            <span style="color: var(--star-rating);">⭐</span> Showcase 4 Buku Favorit Pilihan Saya
          </h3>
          <p style="color: var(--text-muted); font-size: 0.85rem; margin: 0;">
            Pilih hingga 4 buku favorit untuk dipajang di deretan etalase utama profil publik Letterboxd Anda.
          </p>
        </div>
        <div style="display: flex; align-items: center; gap: 10px;">
          <span id="favSlotsCounter" style="background: rgba(31, 111, 235, 0.15); border: 1px solid rgba(88, 166, 255, 0.4); color: #58a6ff; font-size: 0.82rem; font-weight: 700; padding: 4px 14px; border-radius: 20px;">
            {{ $favoriteBooks->count() }}/4 Terpilih
          </span>
          <a href="{{ route('member.fav') }}" class="view-public-btn" style="padding: 5px 12px; font-size: 0.8rem;">
            Lihat Semua <i class="fa-solid fa-arrow-right"></i>
          </a>
        </div>
      </div>

      <!-- 4 Visual Slots Showcase -->
      <div class="fav-slots-grid" id="favSlotsGrid">
        @for ($i = 0; $i < 4; $i++)
          @php $b = $favoriteBooks->get($i); @endphp
          @if ($b)
            <div class="fav-slot-card is-filled">
              <span class="fav-slot-badge">★ Slot {{ $i + 1 }}</span>
              <button type="button" class="fav-slot-remove-btn" onclick="removeFavoriteSlot({{ $i }})" title="Hapus dari slot">
                <i class="fa-solid fa-xmark"></i>
              </button>
              @if ($b->cover)
                <img src="{{ asset('storage/' . $b->cover) }}" alt="{{ $b->title }}" class="fav-slot-img" loading="lazy">
              @else
                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 2rem;">
                  <i class="fa-solid fa-book"></i>
                </div>
              @endif
              <div class="fav-slot-spine"></div>
              <div class="fav-slot-caption">
                <div class="fav-slot-title">{{ $b->title }}</div>
                <div class="fav-slot-author">{{ $b->author }}</div>
              </div>
            </div>
          @else
            <div class="fav-slot-card is-empty" onclick="focusSearchInput()" title="Klik untuk mencari dan mengisi slot ini">
              <i class="fa-solid fa-plus" style="font-size: 1.6rem; color: #484f58; margin-bottom: 8px;"></i>
              <span style="font-size: 0.82rem; font-weight: 700; color: #c9d1d9;">Slot #{{ $i + 1 }}</span>
              <span style="font-size: 0.7rem; color: var(--text-muted); margin-top: 2px;">(Kosong)</span>
            </div>
          @endif
        @endfor
      </div>

      <!-- Form Simpan Showcase 4 Buku Favorit -->
      <form id="saveFavoritesForm" action="{{ route('member.favorites.update') }}" method="POST" style="margin-bottom: 30px;">
        @csrf
        <div id="hiddenFavInputs">
          {{-- Diisi input hidden book_ids[] oleh JavaScript --}}
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; padding: 14px 18px; background: #0d1117; border: 1px solid var(--border-color); border-radius: 8px; flex-wrap: wrap; gap: 12px;">
          <span style="color: var(--text-muted); font-size: 0.85rem;">
            <i class="fa-solid fa-arrows-rotate" style="color: var(--member-accent); margin-right: 6px;"></i>
            Klik slot kosong atau klik buku pada katalog di bawah untuk menambahkan.
          </span>
          <button type="submit" style="background: var(--member-accent); color: white; border: none; padding: 9px 24px; border-radius: 6px; font-size: 0.88rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(31, 111, 235, 0.3);">
            <i class="fa-solid fa-check"></i> Simpan Pajangan 4 Buku
          </button>
        </div>
      </form>

      <!-- Katalog Buku & Live Search Shelf -->
      <div style="border-top: 1px solid var(--border-color); padding-top: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; flex-wrap: wrap; gap: 10px;">
          <label style="font-size: 0.88rem; font-weight: 700; color: white; text-transform: uppercase; letter-spacing: 0.5px;">
            Pilih dari Katalog & Koleksi Buku:
          </label>
          <span style="font-size: 0.78rem; color: var(--text-muted);" id="catalogSearchStatus">
            Menampilkan buku rekomendasi
          </span>
        </div>

        <!-- Search Input Instan -->
        <div class="catalog-search-wrap">
          <i class="fa-solid fa-magnifying-glass catalog-search-icon"></i>
          <input type="text" id="favSearchInput" placeholder="Cari judul buku atau penulis..." class="catalog-search-input" oninput="handleCatalogSearch(this.value);">
        </div>

        <!-- Grid Katalog Buku Interaktif -->
        <div class="catalog-grid" id="catalogGrid">
          {{-- Diisi secara dinamis oleh JavaScript renderCatalogGrid() --}}
        </div>
      </div>

    </div>
  </div>

  {{-- ========================================================= --}}
  {{-- TAB 3: KEAMANAN AKUN & UBAH PASSWORD                      --}}
  {{-- ========================================================= --}}
  <div id="tab-security" class="tab-pane">
    <div class="settings-card" style="max-width: 650px;">
      <div style="margin-bottom: 22px; border-bottom: 1px solid var(--border-color); padding-bottom: 16px;">
        <h3 style="color: white; font-size: 1.15rem; font-weight: 700; margin: 0 0 6px 0; display: flex; align-items: center; gap: 8px;">
          <i class="fa-solid fa-lock" style="color: var(--member-accent);"></i> Keamanan Akun & Ubah Password
        </h3>
        <p style="color: var(--text-muted); font-size: 0.85rem; margin: 0;">
          Perbarui password akun Anda secara berkala untuk menjaga keamanan akun (minimal 8 karakter).
        </p>
      </div>

      <form action="{{ route('member.profile.password.update') }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Password Saat Ini -->
        <div class="form-group">
          <label class="form-label">
            Password Saat Ini <span style="color: var(--admin-danger);">*</span>
          </label>
          <div class="password-input-wrap">
            <input type="password" id="current_password" name="current_password" required placeholder="Masukkan password saat ini" class="form-input" style="padding-right: 40px;">
            <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('current_password', this)">
              <i class="fa-regular fa-eye"></i>
            </button>
          </div>
        </div>

        <!-- Password Baru -->
        <div class="form-group">
          <label class="form-label">
            Password Baru <span style="color: var(--admin-danger);">*</span>
          </label>
          <div class="password-input-wrap">
            <input type="password" id="password" name="password" required placeholder="Minimal 8 karakter" class="form-input" style="padding-right: 40px;">
            <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('password', this)">
              <i class="fa-regular fa-eye"></i>
            </button>
          </div>
        </div>

        <!-- Konfirmasi Password Baru -->
        <div class="form-group">
          <label class="form-label">
            Konfirmasi Password Baru <span style="color: var(--admin-danger);">*</span>
          </label>
          <div class="password-input-wrap">
            <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi password baru" class="form-input" style="padding-right: 40px;">
            <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('password_confirmation', this)">
              <i class="fa-regular fa-eye"></i>
            </button>
          </div>
        </div>

        <div style="display: flex; justify-content: flex-end; margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--border-color);">
          <button type="submit" style="background: var(--member-accent); color: white; border: none; padding: 10px 24px; border-radius: 8px; font-size: 0.88rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(31, 111, 235, 0.35);">
            <i class="fa-solid fa-key"></i> Perbarui Password
          </button>
        </div>
      </form>
    </div>
  </div>

</div>

<!-- Form Hapus Avatar (Hidden) -->
<form id="deleteAvatarForm" action="{{ route('member.profile.avatar.destroy') }}" method="POST" style="display: none;">
  @csrf
  @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

  // ==========================================
  // 1. TAB SWITCHING LOGIC WITH URL HASH
  // ==========================================
  window.switchTab = function (tabName) {
    document.querySelectorAll('.tab-pane').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.profile-tab-btn').forEach(el => el.classList.remove('active'));

    const targetPane = document.getElementById('tab-' + tabName);
    const targetBtn = document.querySelector(`.profile-tab-btn[data-tab="${tabName}"]`);

    if (targetPane && targetBtn) {
      targetPane.classList.add('active');
      targetBtn.classList.add('active');
      window.history.replaceState(null, null, '#' + tabName);
    }
  };

  // Cek inisialisasi tab awal dari session flash, hash, atau error
  let initialTab = @json(session('active_tab')) || window.location.hash.replace('#', '') || 'identity';

  @if($errors->has('current_password') || $errors->has('password') || $errors->has('password_confirmation'))
    initialTab = 'security';
  @elseif($errors->any() && !session('active_tab'))
    initialTab = 'identity';
  @endif

  switchTab(initialTab);

  // ==========================================
  // 2. LIVE MINI PREVIEW & AVATAR HANDLER
  // ==========================================
  const nameInput = document.getElementById('name');
  const bioInput = document.getElementById('bio');
  const miniPreviewName = document.getElementById('miniPreviewName');
  const miniPreviewBio = document.getElementById('miniPreviewBio');
  const miniPreviewGenres = document.getElementById('miniPreviewGenres');

  window.updateLivePreview = function () {
    if (miniPreviewName && nameInput) {
      miniPreviewName.textContent = nameInput.value.trim() || 'Nama Member';
    }
    if (miniPreviewBio && bioInput) {
      const bioVal = bioInput.value.trim();
      if (bioVal) {
        miniPreviewBio.textContent = bioVal;
        miniPreviewBio.classList.remove('is-empty');
      } else {
        miniPreviewBio.textContent = 'Belum ada bio.';
        miniPreviewBio.classList.add('is-empty');
      }
    }
  };

  window.toggleGenreBadge = function (checkbox) {
    const label = checkbox.closest('label');
    const icon = label.querySelector('.check-icon');
    if (checkbox.checked) {
      label.classList.add('active');
      if (icon) icon.style.display = 'inline';
    } else {
      label.classList.remove('active');
      if (icon) icon.style.display = 'none';
    }
    updateLiveGenresPreview();
  };

  function updateLiveGenresPreview() {
    if (!miniPreviewGenres) return;
    const checked = Array.from(document.querySelectorAll('#genrePillsWrap input[type="checkbox"]:checked')).map(cb => cb.value);
    miniPreviewGenres.innerHTML = checked.map(g => `<span class="preview-genre-pill">${g}</span>`).join('');
  }

  window.resetFormStyles = function () {
    document.querySelectorAll('.genre-pill-item input[type="checkbox"]').forEach(cb => {
      toggleGenreBadge(cb);
    });
    updateLivePreview();
  };

  // Avatar Drag & Drop and Preview
  const avatarInput = document.getElementById('avatarInput');
  const avatarPreview = document.getElementById('avatarPreview');
  const avatarInitials = document.getElementById('avatarInitials');
  const miniPreviewAvatar = document.getElementById('miniPreviewAvatar');
  const miniPreviewInitial = document.getElementById('miniPreviewInitial');
  const fileSelectedName = document.getElementById('fileSelectedName');
  const avatarDropzone = document.getElementById('avatarDropzone');

  function handleAvatarFile(file) {
    if (!file) return;
    if (file.size > 2 * 1024 * 1024) {
      alert('Ukuran foto profil maksimal 2 MB.');
      if (avatarInput) avatarInput.value = '';
      return;
    }

    const reader = new FileReader();
    reader.onload = function (e) {
      const dataUrl = e.target.result;
      if (avatarPreview) {
        avatarPreview.src = dataUrl;
        avatarPreview.style.display = 'block';
      }
      if (avatarInitials) avatarInitials.style.display = 'none';
      if (miniPreviewAvatar) {
        miniPreviewAvatar.src = dataUrl;
        miniPreviewAvatar.style.display = 'block';
      }
      if (miniPreviewInitial) miniPreviewInitial.style.display = 'none';
      if (fileSelectedName) {
        fileSelectedName.textContent = 'Foto dipilih: ' + file.name;
        fileSelectedName.style.display = 'block';
      }
    };
    reader.readAsDataURL(file);
  }

  if (avatarInput) {
    avatarInput.addEventListener('change', function (e) {
      handleAvatarFile(e.target.files[0]);
    });
  }

  if (avatarDropzone) {
    ['dragenter', 'dragover'].forEach(eventName => {
      avatarDropzone.addEventListener(eventName, function (e) {
        e.preventDefault();
        e.stopPropagation();
        avatarDropzone.style.borderColor = 'var(--member-accent)';
      }, false);
    });
    ['dragleave', 'drop'].forEach(eventName => {
      avatarDropzone.addEventListener(eventName, function (e) {
        e.preventDefault();
        e.stopPropagation();
        avatarDropzone.style.borderColor = 'var(--border-color)';
      }, false);
    });
    avatarDropzone.addEventListener('drop', function (e) {
      const dt = e.dataTransfer;
      const files = dt.files;
      if (files.length && avatarInput) {
        avatarInput.files = files;
        handleAvatarFile(files[0]);
      }
    }, false);
  }

  // ==========================================
  // 3. TAB 2: 4 FAVORITE BOOKS SHOWCASE
  // ==========================================
  let candidateBooks = @json($candidatesData ?? []);
  let selectedFavIds = @json($favoriteBookIds ?? []);
  let searchDebounceTimer = null;

  window.renderFavoriteSlots = function () {
    const slotsGrid = document.getElementById('favSlotsGrid');
    const counterBadge = document.getElementById('favSlotsCounter');
    const navFavBadge = document.getElementById('navFavBadge');
    const hiddenInputs = document.getElementById('hiddenFavInputs');

    if (!slotsGrid) return;

    let slotsHtml = '';
    for (let i = 0; i < 4; i++) {
      const bookId = selectedFavIds[i];
      const book = candidateBooks.find(b => b.id === bookId);

      if (book) {
        slotsHtml += `
          <div class="fav-slot-card is-filled">
            <span class="fav-slot-badge">★ Slot ${i + 1}</span>
            <button type="button" class="fav-slot-remove-btn" onclick="removeFavoriteSlot(${i})" title="Hapus dari slot">
              <i class="fa-solid fa-xmark"></i>
            </button>
            ${book.cover ? `
              <img src="${book.cover}" alt="${book.title}" class="fav-slot-img" loading="lazy">
            ` : `
              <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 2rem;">
                <i class="fa-solid fa-book"></i>
              </div>
            `}
            <div class="fav-slot-spine"></div>
            <div class="fav-slot-caption">
              <div class="fav-slot-title">${book.title}</div>
              <div class="fav-slot-author">${book.author}</div>
            </div>
          </div>
        `;
      } else {
        slotsHtml += `
          <div class="fav-slot-card is-empty" onclick="focusSearchInput()" title="Klik untuk mencari dan mengisi slot ini">
            <i class="fa-solid fa-plus" style="font-size: 1.6rem; color: #484f58; margin-bottom: 8px;"></i>
            <span style="font-size: 0.82rem; font-weight: 700; color: #c9d1d9;">Slot #${i + 1}</span>
            <span style="font-size: 0.7rem; color: var(--text-muted); margin-top: 2px;">(Kosong)</span>
          </div>
        `;
      }
    }

    slotsGrid.innerHTML = slotsHtml;

    if (counterBadge) counterBadge.textContent = `${selectedFavIds.length}/4 Terpilih`;
    if (navFavBadge) navFavBadge.textContent = `${selectedFavIds.length}/4`;

    // Update hidden inputs for saving
    if (hiddenInputs) {
      hiddenInputs.innerHTML = selectedFavIds.map(id => `<input type="hidden" name="book_ids[]" value="${id}">`).join('');
    }

    renderCatalogGrid();
  };

  window.removeFavoriteSlot = function (index) {
    if (typeof selectedFavIds[index] !== 'undefined') {
      selectedFavIds.splice(index, 1);
      renderFavoriteSlots();
    }
  };

  window.toggleFavoriteBook = function (bookId) {
    const idx = selectedFavIds.indexOf(bookId);
    if (idx > -1) {
      selectedFavIds.splice(idx, 1);
    } else {
      if (selectedFavIds.length >= 4) {
        if (window.showToast) {
          window.showToast({
            title: 'Batas Tercapai',
            message: 'Maksimal 4 buku favorit. Hapus salah satu slot terlebih dahulu untuk menggantinya.',
            type: 'error'
          });
        } else {
          alert('Anda hanya dapat memajang maksimal 4 buku favorit.');
        }
        return;
      }
      selectedFavIds.push(bookId);
    }
    renderFavoriteSlots();
  };

  window.focusSearchInput = function () {
    const input = document.getElementById('favSearchInput');
    if (input) {
      input.focus();
      input.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  };

  window.renderCatalogGrid = function () {
    const grid = document.getElementById('catalogGrid');
    if (!grid) return;

    if (candidateBooks.length === 0) {
      grid.innerHTML = '<div style="grid-column: 1/-1; text-align: center; color: var(--text-muted); padding: 30px; font-size: 0.85rem;">Tidak ada buku yang ditemukan.</div>';
      return;
    }

    grid.innerHTML = candidateBooks.map(book => {
      const isSelected = selectedFavIds.includes(book.id);
      const slotIndex = selectedFavIds.indexOf(book.id) + 1;

      return `
        <div class="catalog-card ${isSelected ? 'is-selected' : ''}" onclick="toggleFavoriteBook(${book.id})">
          ${isSelected ? `
            <span class="catalog-card-badge">★ Slot #${slotIndex}</span>
          ` : ''}

          <div class="catalog-card-cover">
            ${book.cover ? `
              <img src="${book.cover}" alt="${book.title}" loading="lazy">
            ` : `
              <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #484f58; font-size: 1.6rem;">
                <i class="fa-solid fa-book"></i>
              </div>
            `}
            <div class="fav-slot-spine"></div>
          </div>

          <div class="catalog-card-details">
            <div>
              <div class="catalog-card-title" title="${book.title}">${book.title}</div>
              <div class="catalog-card-author">${book.author}</div>
            </div>
            <div class="catalog-card-action" style="color: ${isSelected ? '#3fb950' : 'var(--member-accent)'};">
              ${isSelected ? '<i class="fa-solid fa-check"></i> Terpilih' : '+ Tambah'}
            </div>
          </div>
        </div>
      `;
    }).join('');
  };

  window.handleCatalogSearch = function (query) {
    clearTimeout(searchDebounceTimer);
    const trimmed = query.trim();
    const statusEl = document.getElementById('catalogSearchStatus');

    if (!trimmed) {
      if (statusEl) statusEl.textContent = 'Menampilkan buku rekomendasi';
      renderFavoriteSlots();
      return;
    }

    if (statusEl) statusEl.textContent = 'Mencari di katalog...';

    searchDebounceTimer = setTimeout(async function () {
      try {
        const res = await fetch(`{{ route('books.quickSearch') }}?search=${encodeURIComponent(trimmed)}`);
        const data = await res.json();
        if (data.success && data.books) {
          const formatted = data.books.map(b => ({
            id: b.id,
            title: b.title,
            author: b.author,
            cover: b.cover_url || (b.cover ? `/storage/${b.cover}` : null),
            genre: b.genre,
            rating: b.rating
          }));

          // Merge any already selected favorite books so they don't disappear from catalog list
          selectedFavIds.forEach(favId => {
            if (!formatted.some(b => b.id === favId)) {
              const existing = candidateBooks.find(b => b.id === favId);
              if (existing) formatted.unshift(existing);
            }
          });

          candidateBooks = formatted;
          if (statusEl) statusEl.textContent = `Ditemukan ${data.books.length} buku`;
          renderCatalogGrid();
        }
      } catch (err) {
        if (statusEl) statusEl.textContent = 'Gagal memuat hasil pencarian';
      }
    }, 280);
  };

  // Render awal slot favorit
  renderFavoriteSlots();

  // ==========================================
  // 4. PASSWORD VISIBILITY TOGGLE
  // ==========================================
  window.togglePasswordVisibility = function (inputId, btn) {
    const input = document.getElementById(inputId);
    if (!input) return;
    const isPassword = input.type === 'password';
    input.type = isPassword ? 'text' : 'password';
    const icon = btn.querySelector('i');
    if (icon) {
      icon.className = isPassword ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye';
    }
  };

});
</script>
@endpush
