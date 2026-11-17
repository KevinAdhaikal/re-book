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

  <!-- Showcase 4 Buku Favorit Pilihan Saya -->
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
    <h3 style="color: white; font-weight: bold; margin: 0; font-size: 1.1rem; display: flex; align-items: center; gap: 8px;">
      <span style="color: var(--star-rating);">⭐</span> Showcase Buku Favorit Pilihan Saya
      <span style="font-size: 0.85rem; color: var(--text-muted); font-weight: normal;">({{ $favoriteBooks->count() }}/4)</span>
    </h3>
    <a href="{{ route('member.fav') }}" style="background: #21262d; border: 1px solid var(--border-color); color: var(--member-accent); font-size: 0.82rem; font-weight: 600; padding: 6px 14px; border-radius: 6px; text-decoration: none; display: flex; align-items: center; gap: 6px;">
      Lihat Semua <i class="fa-solid fa-arrow-right"></i>
    </a>
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
        // Validasi ukuran file di client side (2MB = 2 * 1024 * 1024 bytes)
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
</script>
@endpush