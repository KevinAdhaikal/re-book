@extends('layouts.member')

@section('title', 'Profil Member - Re:Book')

@section('member_content')
<div style="max-width: 950px; margin-bottom: 30px;">
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
    <div>
      <h2 style="color: white; font-size: 1.4rem; font-weight: 700; margin: 0 0 4px 0;">Pengaturan Profil</h2>
      <p style="color: var(--text-muted); font-size: 0.88rem; margin: 0;">Kelola foto profil, identitas, bio, dan genre buku favorit Anda.</p>
    </div>
    <a href="{{ route('users.profile', $user->id) }}" style="background: #21262d; border: 1px solid var(--border-color); color: var(--member-accent); font-size: 0.85rem; font-weight: 600; padding: 8px 16px; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;">
      <i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat Profil Publik
    </a>
  </div>

  @if ($errors->any())
    <div style="background: rgba(218, 54, 51, 0.15); border: 1px solid var(--admin-danger); color: #f85149; padding: 14px 18px; border-radius: 6px; margin-bottom: 20px; font-size: 0.88rem;">
      <div style="font-weight: 600; margin-bottom: 6px;"><i class="fa-solid fa-triangle-exclamation"></i> Gagal memperbarui profil:</div>
      <ul style="margin: 0; padding-left: 20px;">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <!-- Form Edit Profil -->
  <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 10px; padding: 25px; margin-bottom: 35px;">
    <form action="{{ route('member.profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
      @csrf
      @method('PUT')

      <!-- Section Avatar -->
      <div style="display: flex; align-items: center; gap: 24px; padding-bottom: 24px; border-bottom: 1px solid var(--border-color); margin-bottom: 24px; flex-wrap: wrap;">
        <div style="position: relative;">
          <div id="avatarContainer" style="width: 90px; height: 90px; border-radius: 50%; overflow: hidden; background: #21262d; border: 2px solid var(--border-color); display: flex; align-items: center; justify-content: center;">
            @if($user->avatarUrl())
              <img id="avatarPreview" src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; object-fit: cover;">
            @else
              <img id="avatarPreview" src="" alt="Preview" style="width: 100%; height: 100%; object-fit: cover; display: none;">
              <div id="avatarInitials" style="font-size: 2.2rem; font-weight: bold; color: var(--member-accent);">
                {{ strtoupper(substr($user->name, 0, 1)) }}
              </div>
            @endif
          </div>
        </div>

        <div style="flex: 1; min-width: 250px;">
          <h4 style="color: white; margin: 0 0 6px 0; font-size: 1rem;">Foto Profil</h4>
          <p style="color: var(--text-muted); font-size: 0.82rem; margin: 0 0 12px 0;">
            Format yang didukung: JPG, JPEG, PNG, WEBP. Maksimal ukuran 2 MB.
          </p>

          <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
            <input type="file" name="avatar" id="avatarInput" accept="image/jpeg,image/png,image/jpg,image/webp" style="display: none;">
            <button type="button" onclick="document.getElementById('avatarInput').click();" style="background: #21262d; border: 1px solid var(--border-color); color: var(--text-main); font-size: 0.85rem; font-weight: 600; padding: 7px 14px; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
              <i class="fa-solid fa-camera"></i> Pilih Foto Profil
            </button>

            @if($user->avatar)
              <button type="button" onclick="document.getElementById('deleteAvatarForm').submit();" style="background: rgba(218, 54, 51, 0.15); border: 1px solid var(--admin-danger); color: #f85149; font-size: 0.85rem; font-weight: 600; padding: 7px 14px; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-trash-can"></i> Hapus Foto
              </button>
            @endif
          </div>
          <div id="fileSelectedName" style="color: var(--member-accent); font-size: 0.78rem; margin-top: 6px; display: none;"></div>
        </div>
      </div>

      <!-- Section Form Fields -->
      <div style="display: flex; flex-direction: column; gap: 20px;">
        
        <!-- Input Nama Lengkap -->
        <div>
          <label for="name" style="display: block; color: var(--text-main); font-size: 0.9rem; font-weight: 600; margin-bottom: 8px;">
            Nama Lengkap <span style="color: var(--admin-danger);">*</span>
          </label>
          <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required maxlength="255"
                 style="width: 100%; padding: 10px 14px; background: #0d1117; border: 1px solid var(--border-color); color: white; border-radius: 6px; font-size: 0.9rem; outline: none; transition: border-color 0.2s;"
                 onfocus="this.style.borderColor='var(--member-accent)';" onblur="this.style.borderColor='var(--border-color)';">
          <small style="color: var(--text-muted); font-size: 0.8rem; margin-top: 4px; display: block;">Nama ini akan ditampilkan pada profil, ulasan, dan komentar Anda.</small>
        </div>

        <!-- Input Email (Readonly) -->
        <div>
          <label style="display: block; color: var(--text-muted); font-size: 0.9rem; font-weight: 600; margin-bottom: 8px;">
            Alamat Email (Akun)
          </label>
          <input type="email" value="{{ $user->email }}" disabled
                 style="width: 100%; padding: 10px 14px; background: rgba(22, 27, 34, 0.6); border: 1px solid #21262d; color: #8b949e; border-radius: 6px; font-size: 0.9rem; cursor: not-allowed;">
          <small style="color: var(--text-muted); font-size: 0.78rem; margin-top: 4px; display: block;">Email digunakan untuk masuk dan tidak dapat diubah di halaman ini.</small>
        </div>

        <!-- Textarea Bio Profile -->
        <div>
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
            <label for="bio" style="color: var(--text-main); font-size: 0.9rem; font-weight: 600; margin: 0;">
              Bio Profil
            </label>
            <span style="font-size: 0.8rem; color: var(--text-muted);">
              <span id="charCount">{{ strlen(old('bio', $user->bio ?? '')) }}</span> / 300 karakter
            </span>
          </div>
          <textarea id="bio" name="bio" rows="3" maxlength="300" placeholder="Ceritakan sedikit tentang dirimu atau minat membaca Anda..."
                    style="width: 100%; padding: 10px 14px; background: #0d1117; border: 1px solid var(--border-color); color: white; border-radius: 6px; font-size: 0.9rem; outline: none; resize: vertical; min-height: 80px; transition: border-color 0.2s;"
                    onfocus="this.style.borderColor='var(--member-accent)';" onblur="this.style.borderColor='var(--border-color)';"
                    oninput="document.getElementById('charCount').textContent = this.value.length;">{{ old('bio', $user->bio) }}</textarea>
          <small style="color: var(--text-muted); font-size: 0.8rem; margin-top: 4px; display: block;">Deskripsikan selera buku atau diri Anda. Tampil pada halaman profil publik.</small>
        </div>

        <!-- Checkbox Genre Favorit -->
        <div>
          <label style="display: block; color: var(--text-main); font-size: 0.9rem; font-weight: 600; margin-bottom: 6px;">
            Genre Buku Favorit
          </label>
          <p style="color: var(--text-muted); font-size: 0.82rem; margin: 0 0 12px 0;">
            Pilih genre yang Anda minati. Anda dapat memilih lebih dari satu genre untuk menghias profil Anda.
          </p>

          @php
            $currentGenres = (array) (old('favorite_genres', $user->favorite_genres ?? []));
          @endphp

          <div style="display: flex; flex-wrap: wrap; gap: 8px;">
            @foreach ($availableGenres as $genre)
              @php
                $isChecked = in_array($genre, $currentGenres);
              @endphp
              <label class="genre-pill" style="display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; border-radius: 20px; font-size: 0.82rem; font-weight: 500; cursor: pointer; user-select: none; transition: all 0.2s; border: 1px solid {{ $isChecked ? 'var(--member-accent)' : 'var(--border-color)' }}; background: {{ $isChecked ? 'rgba(31, 111, 235, 0.18)' : '#0d1117' }}; color: {{ $isChecked ? '#58a6ff' : 'var(--text-muted)' }};">
                <input type="checkbox" name="favorite_genres[]" value="{{ $genre }}" {{ $isChecked ? 'checked' : '' }} style="display: none;" onchange="toggleGenreBadge(this)">
                <i class="fa-solid fa-check check-icon" style="font-size: 0.75rem; display: {{ $isChecked ? 'inline' : 'none' }};"></i>
                <span>{{ $genre }}</span>
              </label>
            @endforeach
          </div>
        </div>

      </div>

      <!-- Tombol Aksi Simpan -->
      <div style="display: flex; justify-content: flex-end; align-items: center; gap: 12px; margin-top: 30px; padding-top: 20px; border-top: 1px solid var(--border-color);">
        <button type="reset" onclick="setTimeout(resetFormStyles, 10);" style="background: transparent; border: 1px solid var(--border-color); color: var(--text-muted); padding: 8px 18px; border-radius: 6px; font-size: 0.88rem; font-weight: 600; cursor: pointer;">
          Reset
        </button>
        <button type="submit" style="background: var(--member-accent); color: white; border: none; padding: 9px 24px; border-radius: 6px; font-size: 0.88rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: background 0.2s;">
          <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
        </button>
      </div>
    </form>
  </div>

  <!-- Form Hapus Avatar (Hidden) -->
  <form id="deleteAvatarForm" action="{{ route('member.profile.avatar.destroy') }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
  </form>

  <!-- Form Keamanan Akun / Ubah Password -->
  <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 10px; padding: 25px; margin-bottom: 35px;">
    <div style="margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 15px;">
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

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 18px; margin-bottom: 20px;">
        <div>
          <label style="display: block; color: var(--text-main); font-size: 0.88rem; font-weight: 600; margin-bottom: 6px;">
            Password Saat Ini <span style="color: var(--admin-danger);">*</span>
          </label>
          <input type="password" name="current_password" required placeholder="Masukkan password saat ini"
                 style="width: 100%; padding: 10px 14px; background: #0d1117; border: 1px solid var(--border-color); color: white; border-radius: 6px; font-size: 0.9rem; outline: none; transition: border-color 0.2s;">
        </div>

        <div>
          <label style="display: block; color: var(--text-main); font-size: 0.88rem; font-weight: 600; margin-bottom: 6px;">
            Password Baru <span style="color: var(--admin-danger);">*</span>
          </label>
          <input type="password" name="password" required placeholder="Minimal 8 karakter"
                 style="width: 100%; padding: 10px 14px; background: #0d1117; border: 1px solid var(--border-color); color: white; border-radius: 6px; font-size: 0.9rem; outline: none; transition: border-color 0.2s;">
        </div>

        <div>
          <label style="display: block; color: var(--text-main); font-size: 0.88rem; font-weight: 600; margin-bottom: 6px;">
            Konfirmasi Password Baru <span style="color: var(--admin-danger);">*</span>
          </label>
          <input type="password" name="password_confirmation" required placeholder="Ulangi password baru"
                 style="width: 100%; padding: 10px 14px; background: #0d1117; border: 1px solid var(--border-color); color: white; border-radius: 6px; font-size: 0.9rem; outline: none; transition: border-color 0.2s;">
        </div>
      </div>

      <div style="display: flex; justify-content: flex-end;">
        <button type="submit" style="background: var(--member-accent); color: white; border: none; padding: 9px 22px; border-radius: 6px; font-size: 0.88rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
          <i class="fa-solid fa-key"></i> Perbarui Password
        </button>
      </div>
    </form>
  </div>

  <!-- Showcase 4 Buku Favorit Pilihan Saya -->
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 10px;">
    <h3 style="color: white; font-weight: bold; margin: 0; font-size: 1.1rem; display: flex; align-items: center; gap: 8px;">
      <span style="color: var(--star-rating);">⭐</span> Showcase 4 Buku Favorit Pilihan Saya
      <span style="font-size: 0.85rem; color: var(--text-muted); font-weight: normal;">({{ $favoriteBooks->count() }}/4)</span>
    </h3>
    <div style="display: flex; align-items: center; gap: 10px;">
      <button type="button" onclick="openFavoritesModal()" style="background: var(--member-accent); color: white; border: none; font-size: 0.82rem; font-weight: 600; padding: 7px 16px; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
        <i class="fa-solid fa-wand-magic-sparkles"></i> Atur 4 Buku Favorit
      </button>
      <a href="{{ route('member.fav') }}" style="background: #21262d; border: 1px solid var(--border-color); color: var(--member-accent); font-size: 0.82rem; font-weight: 600; padding: 6px 14px; border-radius: 6px; text-decoration: none; display: flex; align-items: center; gap: 6px;">
        Lihat Semua <i class="fa-solid fa-arrow-right"></i>
      </a>
    </div>
  </div>

  @if($favoriteBooks->isEmpty())
    <div style="background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 35px 20px; text-align: center;">
      <i class="fa-regular fa-star" style="font-size: 2rem; color: #8b949e; margin-bottom: 10px;"></i>
      <h4 style="color: var(--text-main); margin-bottom: 6px; font-size: 1rem;">Belum Ada Buku Favorit</h4>
      <p style="color: #8b949e; font-size: 0.85rem; margin-bottom: 15px;">Pilih hingga 4 buku favorit Anda untuk dipajang di showcase profil ini.</p>
      <a href="{{ route('member.fav') }}" style="background: var(--member-accent); color: white; padding: 7px 16px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: inline-block;">
        Pilih Buku Favorit Sekarang
      </a>
    </div>
  @else
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 16px;">
      @foreach ($favoriteBooks as $b)
        <div style="background-color: #161b22; border: 1px solid #30363d; border-radius: 8px; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between;">
          <div>
            @if(isset($b->cover) && $b->cover)
              <img src="{{ asset('storage/' . $b->cover) }}" alt="{{ $b->title }}"
                   onerror="this.onerror=null; this.parentElement.querySelector('.cover-fallback').style.display='flex'; this.style.display='none';"
                   style="width: 100%; height: 230px; object-fit: cover; display: block;">
              <div class="cover-fallback" style="display: none; width: 100%; height: 230px; background-color: #0d1117; flex-direction: column; align-items: center; justify-content: center; color: #8b949e; font-size: 0.75rem; padding: 15px; text-align: center;">
                <i class="fa-solid fa-book" style="font-size: 2rem; color: #30363d; margin-bottom: 6px;"></i>
                <span>{{ Str::limit($b->title, 25) }}</span>
              </div>
            @else
              <div style="width: 100%; height: 230px; background-color: #0d1117; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #8b949e; font-size: 0.75rem; padding: 15px; text-align: center;">
                <i class="fa-solid fa-book" style="font-size: 2rem; color: #30363d; margin-bottom: 6px;"></i>
                <span>{{ Str::limit($b->title, 25) }}</span>
              </div>
            @endif
            <div style="padding: 12px;">
              <div style="display: flex; align-items: center; gap: 4px; font-size: 0.7rem; margin-bottom: 4px;">
                <span style="color: #e3b341;">★</span>
                <span style="color: #8b949e;">{{ number_format($b->reviews_avg_rating ?? ($b->reviews ? $b->reviews->avg('rating') : 0), 1) }}</span>
              </div>
              <h4 style="color: white; font-size: 0.85rem; font-weight: 600; margin: 0 0 4px 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $b->title }}">{{ $b->title }}</h4>
              <p style="color: #8b949e; font-size: 0.7rem; margin: 0;">{{ $b->genre ?? 'Novel' }} &bull; {{ $b->author }}</p>
            </div>
          </div>
          <div style="padding: 0 12px 12px 12px;">
            <a href="{{ route('books.show', $b->id) }}" style="display: block; width: 100%; text-align: center; background-color: #21262d; color: #58a6ff; font-size: 0.75rem; font-weight: 600; padding: 6px 0; border-radius: 6px; text-decoration: none;">
              Lihat Ulasan
            </a>
          </div>
        </div>
      @endforeach
    </div>
  @endif

</div>

{{-- ================= MODAL PICKER 4 BUKU FAVORIT (LETTERBOXD STYLE) ================= --}}
<div id="favModalBackdrop" style="display: none; position: fixed; inset: 0; background: rgba(13, 17, 23, 0.85); backdrop-filter: blur(6px); z-index: 99999; align-items: center; justify-content: center; padding: 20px;">
  <div id="favModalDialog" style="background: #161b22; border: 1px solid var(--border-color); border-radius: 12px; width: 780px; max-width: 95vw; max-height: 90vh; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 25px 60px rgba(0,0,0,0.75);">
    
    {{-- Modal Header --}}
    <div style="padding: 18px 24px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; background: #0d1117;">
      <div>
        <h3 style="color: white; font-size: 1.15rem; font-weight: 700; margin: 0 0 4px 0; display: flex; align-items: center; gap: 8px;">
          <span style="color: var(--star-rating);">⭐</span> Atur Showcase 4 Buku Favorit
        </h3>
        <p style="color: var(--text-muted); font-size: 0.8rem; margin: 0;">Pilih hingga 4 buku favorit untuk dipajang di etalase profil Anda.</p>
      </div>
      <button type="button" onclick="closeFavoritesModal()" style="background: none; border: none; color: var(--text-muted); font-size: 1.2rem; cursor: pointer; padding: 4px;">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    {{-- Modal Body (Scrollable) --}}
    <div style="padding: 20px 24px; overflow-y: auto; flex: 1;">
      
      {{-- 4 Slot Preview Visual di Bagian Atas --}}
      <div style="margin-bottom: 22px;">
        <div style="font-size: 0.82rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px;">
          4 Slot Pajangan Profil:
        </div>
        <div id="favSlotContainer" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px;">
          {{-- Diinjeksi dinamis oleh renderFavoritesUI() --}}
        </div>
      </div>

      {{-- Search bar katalog / koleksi --}}
      <div style="margin-bottom: 14px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
          <label style="font-size: 0.82rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">
            Pilih dari Koleksi & Katalog Buku:
          </label>
          <span id="favCountBadge" style="font-size: 0.8rem; font-weight: 600; color: var(--member-accent);">0/4 Terpilih</span>
        </div>
        <div style="position: relative;">
          <input type="text" id="favSearchInput" placeholder="Cari judul buku atau penulis..." oninput="filterCandidateBooks(this.value)"
                 style="width: 100%; padding: 8px 12px 8px 34px; background: #0d1117; border: 1px solid var(--border-color); color: white; border-radius: 6px; font-size: 0.85rem; outline: none;">
          <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 12px; top: 11px; color: var(--text-muted); font-size: 0.8rem;"></i>
        </div>
      </div>

      {{-- Grid Koleksi Buku --}}
      <div id="candidateBooksGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 12px; max-height: 250px; overflow-y: auto; padding: 4px;">
        {{-- Diinjeksi dinamis oleh renderCandidateGrid() --}}
      </div>

    </div>

    {{-- Modal Footer --}}
    <div style="padding: 14px 24px; border-top: 1px solid var(--border-color); background: #0d1117; display: flex; justify-content: space-between; align-items: center;">
      <button type="button" onclick="closeFavoritesModal()" style="background: transparent; border: 1px solid var(--border-color); color: var(--text-muted); padding: 8px 18px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; cursor: pointer;">
        Batal
      </button>

      <form id="saveFavoritesForm" action="{{ route('member.favorites.update') }}" method="POST" style="margin: 0;">
        @csrf
        <div id="hiddenFavInputs"></div>
        <button type="submit" style="background: var(--member-accent); color: white; border: none; padding: 9px 24px; border-radius: 6px; font-size: 0.88rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
          <i class="fa-solid fa-check"></i> Simpan Showcase
        </button>
      </form>
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script>
  // Live Client-side Image Preview
  const avatarInput = document.getElementById('avatarInput');
  const avatarPreview = document.getElementById('avatarPreview');
  const avatarInitials = document.getElementById('avatarInitials');
  const fileSelectedName = document.getElementById('fileSelectedName');

  if (avatarInput) {
    avatarInput.addEventListener('change', function (e) {
      const file = e.target.files[0];
      if (file) {
        if (file.size > 2 * 1024 * 1024) {
          alert('Ukuran foto profil maksimal 2 MB.');
          this.value = '';
          return;
        }

        const reader = new FileReader();
        reader.onload = function (event) {
          avatarPreview.src = event.target.result;
          avatarPreview.style.display = 'block';
          if (avatarInitials) {
            avatarInitials.style.display = 'none';
          }
        };
        reader.readAsDataURL(file);

        if (fileSelectedName) {
          fileSelectedName.textContent = 'File dipilih: ' + file.name;
          fileSelectedName.style.display = 'block';
        }
      }
    });
  }

  // Toggle Genre Badge Styling
  function toggleGenreBadge(checkbox) {
    const label = checkbox.closest('label');
    const icon = label.querySelector('.check-icon');
    if (checkbox.checked) {
      label.style.borderColor = 'var(--member-accent)';
      label.style.background = 'rgba(31, 111, 235, 0.18)';
      label.style.color = '#58a6ff';
      if (icon) icon.style.display = 'inline';
    } else {
      label.style.borderColor = 'var(--border-color)';
      label.style.background = '#0d1117';
      label.style.color = 'var(--text-muted)';
      if (icon) icon.style.display = 'none';
    }
  }

  function resetFormStyles() {
    document.querySelectorAll('.genre-pill input[type="checkbox"]').forEach(cb => {
      toggleGenreBadge(cb);
    });
    const bio = document.getElementById('bio');
    if (bio) {
      document.getElementById('charCount').textContent = bio.value.length;
    }
  }

  // ================= MODAL PICKER 4 BUKU FAVORIT SCRIPT =================
  const rawCandidates = @json($candidatesData ?? []);
  let selectedFavIds = @json($favoriteBookIds ?? []);
  let candidateFilterQuery = '';

  function openFavoritesModal() {
    const backdrop = document.getElementById('favModalBackdrop');
    if (backdrop) {
      backdrop.style.display = 'flex';
      renderFavoritesUI();
    }
  }

  function closeFavoritesModal() {
    const backdrop = document.getElementById('favModalBackdrop');
    if (backdrop) {
      backdrop.style.display = 'none';
    }
  }

  // Close on ESC
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeFavoritesModal();
    }
  });

  function toggleSelectBook(bookId) {
    const idx = selectedFavIds.indexOf(bookId);
    if (idx > -1) {
      selectedFavIds.splice(idx, 1);
    } else {
      if (selectedFavIds.length >= 4) {
        alert('Anda hanya dapat memilih maksimal 4 buku favorit untuk showcase profil.');
        return;
      }
      selectedFavIds.push(bookId);
    }
    renderFavoritesUI();
  }

  function removeSlot(slotIndex) {
    if (selectedFavIds[slotIndex]) {
      selectedFavIds.splice(slotIndex, 1);
      renderFavoritesUI();
    }
  }

  function filterCandidateBooks(query) {
    candidateFilterQuery = query.toLowerCase().trim();
    renderCandidateGrid();
  }

  function renderFavoritesUI() {
    // 1. Update Counter
    const countBadge = document.getElementById('favCountBadge');
    if (countBadge) {
      countBadge.textContent = selectedFavIds.length + '/4 Terpilih';
    }

    // 2. Render 4 Visual Slots
    const slotContainer = document.getElementById('favSlotContainer');
    if (slotContainer) {
      let slotsHtml = '';
      for (let i = 0; i < 4; i++) {
        const bookId = selectedFavIds[i];
        const book = rawCandidates.find(b => b.id === bookId);

        if (book) {
          slotsHtml += `
            <div style="position: relative; height: 160px; border-radius: 8px; overflow: hidden; background: #0d1117; border: 2px solid var(--member-accent); display: flex; flex-direction: column;">
              <span style="position: absolute; top: 6px; left: 6px; background: rgba(0,0,0,0.75); color: #e3b341; font-size: 0.7rem; font-weight: 700; padding: 2px 6px; border-radius: 4px; z-index: 2;">
                ★ Slot ${i + 1}
              </span>
              <button type="button" onclick="removeSlot(${i})" style="position: absolute; top: 6px; right: 6px; width: 22px; height: 22px; border-radius: 50%; background: rgba(218,54,51,0.85); color: white; border: none; font-size: 0.7rem; cursor: pointer; z-index: 2; display: flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-xmark"></i>
              </button>
              ${book.cover ? `
                <img src="${book.cover}" alt="${book.title}" style="width: 100%; height: 100%; object-fit: cover;">
              ` : `
                <div style="flex: 1; display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 1.5rem;">
                  <i class="fa-solid fa-book"></i>
                </div>
              `}
              <div style="position: absolute; bottom: 0; inset-inline: 0; background: linear-gradient(to top, rgba(0,0,0,0.9), transparent); padding: 18px 8px 6px; font-size: 0.72rem; font-weight: 600; color: white; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                ${book.title}
              </div>
            </div>
          `;
        } else {
          slotsHtml += `
            <div style="height: 160px; border-radius: 8px; border: 2px dashed #30363d; background: rgba(255,255,255,0.01); display: flex; flex-direction: column; align-items: center; justify-content: center; color: var(--text-muted); text-align: center; padding: 8px;">
              <i class="fa-regular fa-square-plus" style="font-size: 1.6rem; color: #484f58; margin-bottom: 6px;"></i>
              <span style="font-size: 0.75rem; font-weight: 600; color: #8b949e;">Slot ${i + 1}</span>
              <span style="font-size: 0.68rem; color: #484f58;">(Kosong)</span>
            </div>
          `;
        }
      }
      slotContainer.innerHTML = slotsHtml;
    }

    // 3. Render Candidate Grid
    renderCandidateGrid();

    // 4. Update Hidden Inputs
    const hiddenContainer = document.getElementById('hiddenFavInputs');
    if (hiddenContainer) {
      let inputsHtml = '';
      selectedFavIds.forEach(id => {
        inputsHtml += `<input type="hidden" name="book_ids[]" value="${id}">`;
      });
      hiddenContainer.innerHTML = inputsHtml;
    }
  }

  function renderCandidateGrid() {
    const grid = document.getElementById('candidateBooksGrid');
    if (!grid) return;

    const filtered = rawCandidates.filter(b => {
      if (!candidateFilterQuery) return true;
      return (b.title && b.title.toLowerCase().includes(candidateFilterQuery)) ||
             (b.author && b.author.toLowerCase().includes(candidateFilterQuery));
    });

    if (filtered.length === 0) {
      grid.innerHTML = '<div style="grid-column: 1/-1; text-align: center; color: var(--text-muted); padding: 25px; font-size: 0.85rem;">Tidak ada buku yang sesuai kata kunci.</div>';
      return;
    }

    let gridHtml = '';
    filtered.forEach(book => {
      const isSelected = selectedFavIds.includes(book.id);
      const slotNum = selectedFavIds.indexOf(book.id) + 1;

      gridHtml += `
        <div onclick="toggleSelectBook(${book.id})"
             style="cursor: pointer; position: relative; border-radius: 8px; overflow: hidden; background: #0d1117; border: 2px solid ${isSelected ? 'var(--member-accent)' : '#21262d'}; transition: all 0.15s ease; display: flex; flex-direction: column;">
          
          ${isSelected ? `
            <div style="position: absolute; top: 6px; right: 6px; background: var(--member-accent); color: white; font-size: 0.68rem; font-weight: 700; padding: 2px 7px; border-radius: 12px; z-index: 2; box-shadow: 0 2px 6px rgba(0,0,0,0.5);">
              Slot #${slotNum}
            </div>
          ` : ''}

          <div style="height: 150px; background: #161b22; position: relative;">
            ${book.cover ? `
              <img src="${book.cover}" alt="${book.title}" style="width: 100%; height: 100%; object-fit: cover;">
            ` : `
              <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 1.5rem;">
                <i class="fa-solid fa-book"></i>
              </div>
            `}
          </div>

          <div style="padding: 8px 10px; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
              <div style="font-size: 0.78rem; font-weight: 600; color: ${isSelected ? '#58a6ff' : 'white'}; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="${book.title}">
                ${book.title}
              </div>
              <div style="font-size: 0.7rem; color: var(--text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                ${book.author}
              </div>
            </div>
            <div style="margin-top: 6px; font-size: 0.7rem; color: ${isSelected ? '#3fb950' : 'var(--text-muted)'}; font-weight: 600;">
              ${isSelected ? '<i class=\"fa-solid fa-check\"></i> Terpilih' : '+ Pilih'}
            </div>
          </div>
        </div>
      `;
    });

    grid.innerHTML = gridHtml;
  }
</script>
@endpush