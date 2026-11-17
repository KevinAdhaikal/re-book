@extends('layouts.admin')

@section('title', 'Kelola Buku - Admin Re:Book')

@section('admin_content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
  <div>
    <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin-bottom: 4px; display: flex; align-items: center; gap: 10px;">
      <i class="fa-solid fa-book" style="color: var(--member-accent);"></i> Kelola Daftar Buku
    </h2>
    <p style="color: var(--text-muted); font-size: 0.9rem;">
      Tambah, cari, ubah data, dan hapus katalog buku di Re:Book.
    </p>
  </div>
  <div style="display: flex; gap: 10px; flex-wrap: wrap;">
    <a href="{{ route('admin.exports.books') }}" style="background: #21262d; border: 1px solid var(--border-color); color: var(--text-main); padding: 9px 16px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
      <i class="fa-solid fa-file-csv" style="color: #3fb950;"></i> Export CSV
    </a>
    <button onclick="document.getElementById('modalTambahBuku').style.display='flex'" style="background: var(--admin-accent); color: #fff; border: none; padding: 9px 18px; border-radius: 6px; font-weight: 600; font-size: 0.88rem; cursor: pointer; display: flex; align-items: center; gap: 8px;">
      <i class="fa-solid fa-plus"></i> Tambah Buku Baru
    </button>
  </div>
</div>

{{-- Bar Pencarian & Filter Genre --}}
<div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 16px; margin-bottom: 20px;">
  <form action="{{ route('admin.books.manage') }}" method="GET" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
    {{-- Input Search --}}
    <div style="flex: 1; min-width: 240px;">
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul atau nama penulis..."
             style="width: 100%; padding: 8px 12px; background: #0d1117; border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; font-size: 0.88rem; outline: none;">
    </div>

    {{-- Filter Genre --}}
    <div style="min-width: 160px;">
      <select name="genre" onchange="this.form.submit()" style="width: 100%; padding: 8px 12px; background: #0d1117; border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; font-size: 0.88rem; outline: none; cursor: pointer;">
        <option value="all" {{ request('genre') === 'all' || !request('genre') ? 'selected' : '' }}>Semua Genre</option>
        @foreach($genres as $g)
          <option value="{{ $g }}" {{ request('genre') === $g ? 'selected' : '' }}>{{ $g }}</option>
        @endforeach
      </select>
    </div>

    {{-- Sort By --}}
    <div style="min-width: 170px;">
      <select name="sort" onchange="this.form.submit()" style="width: 100%; padding: 8px 12px; background: #0d1117; border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; font-size: 0.88rem; outline: none; cursor: pointer;">
        <option value="title_asc" {{ ($sort ?? request('sort')) === 'title_asc' ? 'selected' : '' }}>Judul (A-Z)</option>
        <option value="title_desc" {{ ($sort ?? request('sort')) === 'title_desc' ? 'selected' : '' }}>Judul (Z-A)</option>
        <option value="rating_desc" {{ ($sort ?? request('sort')) === 'rating_desc' ? 'selected' : '' }}>Rating Tertinggi</option>
        <option value="rating_asc" {{ ($sort ?? request('sort')) === 'rating_asc' ? 'selected' : '' }}>Rating Terendah</option>
        <option value="popular" {{ ($sort ?? request('sort')) === 'popular' ? 'selected' : '' }}>Paling Banyak Suka</option>
        <option value="reviews" {{ ($sort ?? request('sort')) === 'reviews' ? 'selected' : '' }}>Ulasan Terbanyak</option>
        <option value="newest" {{ ($sort ?? request('sort')) === 'newest' ? 'selected' : '' }}>Terbaru Ditambahkan</option>
      </select>
    </div>

    <button type="submit" style="background: var(--admin-accent); color: white; padding: 8px 16px; border-radius: 6px; border: none; font-size: 0.88rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
      <i class="fa-solid fa-filter"></i> Saring
    </button>

    @if(request()->anyFilled(['search', 'genre', 'sort']) && (request('genre') !== 'all' || request('search') !== '' || (request('sort') && request('sort') !== 'title_asc')))
      <a href="{{ route('admin.books.manage') }}" style="background: #21262d; border: 1px solid var(--border-color); color: var(--text-muted); padding: 8px 14px; border-radius: 6px; text-decoration: none; font-size: 0.85rem;">
        Reset
      </a>
    @endif
  </form>
</div>

<!-- Tabel List Buku -->
<table class="custom-table" style="margin-top: 0;">
  <thead>
    <tr>
      <x-sort-th column="id" :current="$sort" asc="id_asc" desc="id_desc" default="desc" style="width: 70px;">ID</x-sort-th>
      <th style="width: 70px;">Cover</th>
      <x-sort-th column="title" :current="$sort" asc="title_asc" desc="title_desc" default="asc">Judul Buku & Penulis</x-sort-th>
      <x-sort-th column="genre" :current="$sort" asc="genre_asc" desc="genre_desc" default="asc">Genre</x-sort-th>
      <x-sort-th column="rating" :current="$sort" asc="rating_asc" desc="rating_desc" default="desc">Rating & Respon</x-sort-th>
      <th style="width: 170px;">Aksi Admin</th>
    </tr>
  </thead>
  <tbody>
    @forelse ($books as $b)
      <tr>
        <td style="color: var(--text-muted); font-size: 0.85rem;">#{{ $b->id }}</td>
        <td>
          @if($b->cover)
            <img src="{{ asset('storage/' . $b->cover) }}" alt="{{ $b->title }}" style="width: 44px; height: 60px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border-color); display: block;">
          @else
            <div style="width: 44px; height: 60px; background: #21262d; border-radius: 4px; border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: center; color: #8b949e; font-size: 0.85rem;">
              <i class="fa-solid fa-book"></i>
            </div>
          @endif
        </td>
        <td>
          <div style="margin-bottom: 2px;">
            <a href="{{ route('books.show', $b->id) }}" target="_blank" style="color: var(--text-main); font-weight: 700; text-decoration: none;">
              {{ $b->title }}
              <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 0.7rem; color: #58a6ff; margin-left: 4px;"></i>
            </a>
          </div>
          <div style="color: var(--text-muted); font-size: 0.82rem;">Oleh {{ $b->author }}</div>
        </td>
        <td>
          <span style="background: #21262d; border: 1px solid var(--border-color); padding: 3px 8px; border-radius: 4px; font-size: 0.78rem; color: #79c0ff;">
            {{ $b->genre }}
          </span>
        </td>
        <td>
          <div style="display: flex; align-items: center; gap: 8px; font-size: 0.85rem; margin-bottom: 2px;">
            <span style="color: var(--star-rating); font-weight: 700;">
              <i class="fa-solid fa-star"></i> {{ number_format($b->reviews_avg_rating ?? 0, 1) }}
            </span>
            <span style="color: var(--text-muted); font-size: 0.78rem;">({{ $b->reviews_count }} ulasan)</span>
          </div>
          <div style="font-size: 0.75rem; color: var(--favorite-coral);">
            <i class="fa-solid fa-heart"></i> {{ $b->liked_by_users_count }} suka
          </div>
        </td>
        <td>
          <div style="display: flex; gap: 6px; align-items: center;">
            <!-- Tombol Edit Buku -->
            <button type="button" onclick="openEditBookModal({{ $b->id }}, {{ json_encode($b->title) }}, {{ json_encode($b->author) }}, {{ json_encode($b->genre) }}, {{ json_encode($b->description) }}, {{ json_encode($b->cover ? asset('storage/'.$b->cover) : null) }})" 
              style="background: #21262D; border: 1px solid var(--border-color); color: var(--member-accent); padding: 6px 10px; border-radius: 4px; font-size: 0.8rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
              <i class="fa-solid fa-pen-to-square"></i> Edit
            </button>

            <!-- Tombol Hapus Buku -->
            <form action="{{ route('admin.books.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku \'{{ $b->title }}\'? Tindakan ini tidak dapat dibatalkan.');" style="display: inline;">
              @csrf
              @method('DELETE')
              <button type="submit" style="background: var(--admin-danger); color: #fff; border: none; padding: 6px 10px; border-radius: 4px; font-size: 0.8rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                <i class="fa-solid fa-trash"></i> Hapus
              </button>
            </form>
          </div>
        </td>
      </tr>
    @empty
      <tr>
        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 30px;">
          Tidak ada buku yang cocok dengan filter pencarian.
        </td>
      </tr>
    @endforelse
  </tbody>
</table>

<!-- Pagination -->
<div style="margin-top: 20px;">
  {{ $books->links() }}
</div>

<!-- ================= MODAL TAMBAH BUKU ================= -->
<div id="modalTambahBuku" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.75); backdrop-filter: blur(4px); justify-content: center; align-items: center; z-index: 2000; padding: 20px;">
  <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 10px; padding: 26px 30px; width: 100%; max-width: 520px; position: relative; max-height: 90vh; overflow-y: auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
      <h3 style="font-size: 1.2rem; color: var(--text-main); font-weight: 700;">Tambah Buku Baru</h3>
      <button onclick="document.getElementById('modalTambahBuku').style.display='none'" style="background: none; border: none; color: var(--text-muted); font-size: 1.3rem; cursor: pointer;">&times;</button>
    </div>

    <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      
      <div style="margin-bottom: 14px;">
        <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 6px; font-weight: 600;">Judul Buku</label>
        <input type="text" name="title" required placeholder="Contoh: Bumi Manusia" style="width: 100%; padding: 9px 12px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none; font-size: 0.9rem;">
      </div>

      <div style="margin-bottom: 14px;">
        <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 6px; font-weight: 600;">Penulis / Pengarang</label>
        <input type="text" name="author" required placeholder="Contoh: Pramoedya Ananta Toer" style="width: 100%; padding: 9px 12px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none; font-size: 0.9rem;">
      </div>

      <div style="margin-bottom: 14px;">
        <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 6px; font-weight: 600;">Genre</label>
        <select name="genre" required style="width: 100%; padding: 9px 12px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none; font-size: 0.9rem;">
          <option value="Novel">Novel</option>
          <option value="Fiksi">Fiksi</option>
          <option value="Sastra">Sastra</option>
          <option value="Non-Fiksi">Non-Fiksi</option>
          <option value="Sejarah">Sejarah</option>
          <option value="Self-Dev">Self Development</option>
          <option value="Filsafat">Filsafat</option>
          <option value="Komik">Komik / Manga</option>
        </select>
      </div>

      <div style="margin-bottom: 14px;">
        <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 6px; font-weight: 600;">Deskripsi / Sinopsis Singkat</label>
        <textarea name="description" rows="3" required placeholder="Tulis ringkasan tentang buku ini..." style="width: 100%; padding: 9px 12px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none; resize: vertical; font-size: 0.9rem;"></textarea>
      </div>

      <div style="margin-bottom: 20px;">
        <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 6px; font-weight: 600;">Foto Cover Buku (Format: JPG, PNG, Max 2MB)</label>
        <input type="file" name="cover" id="addCoverInput" accept="image/*" style="width: 100%; padding: 8px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; font-size: 0.85rem;">
        
        {{-- Live Image Preview --}}
        <div id="addCoverPreviewContainer" style="display: none; margin-top: 10px; align-items: center; gap: 12px;">
          <img id="addCoverPreview" src="" alt="Preview Cover" style="width: 50px; height: 72px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border-color);">
          <span style="font-size: 0.8rem; color: var(--text-muted);">Pratinjau cover yang dipilih</span>
        </div>
      </div>

      <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid var(--border-color); padding-top: 16px;">
        <button type="button" onclick="document.getElementById('modalTambahBuku').style.display='none'" style="padding: 9px 16px; background: #21262d; border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; cursor: pointer; font-size: 0.88rem;">Batal</button>
        <button type="submit" style="padding: 9px 18px; background: var(--admin-accent); border: none; color: #fff; font-weight: 600; border-radius: 6px; cursor: pointer; font-size: 0.88rem;">Simpan Buku</button>
      </div>
    </form>
  </div>
</div>

<!-- ================= MODAL EDIT BUKU ================= -->
<div id="modalEditBuku" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.75); backdrop-filter: blur(4px); justify-content: center; align-items: center; z-index: 2000; padding: 20px;">
  <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 10px; padding: 26px 30px; width: 100%; max-width: 520px; position: relative; max-height: 90vh; overflow-y: auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
      <h3 style="font-size: 1.2rem; color: var(--text-main); font-weight: 700;">Edit Data Buku</h3>
      <button onclick="document.getElementById('modalEditBuku').style.display='none'" style="background: none; border: none; color: var(--text-muted); font-size: 1.3rem; cursor: pointer;">&times;</button>
    </div>

    <form id="formEditBuku" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      
      <div style="margin-bottom: 14px;">
        <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 6px; font-weight: 600;">Judul Buku</label>
        <input type="text" name="title" id="editTitle" required style="width: 100%; padding: 9px 12px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none; font-size: 0.9rem;">
      </div>

      <div style="margin-bottom: 14px;">
        <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 6px; font-weight: 600;">Penulis / Pengarang</label>
        <input type="text" name="author" id="editAuthor" required style="width: 100%; padding: 9px 12px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none; font-size: 0.9rem;">
      </div>

      <div style="margin-bottom: 14px;">
        <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 6px; font-weight: 600;">Genre</label>
        <select name="genre" id="editGenre" required style="width: 100%; padding: 9px 12px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none; font-size: 0.9rem;">
          <option value="Novel">Novel</option>
          <option value="Fiksi">Fiksi</option>
          <option value="Sastra">Sastra</option>
          <option value="Non-Fiksi">Non-Fiksi</option>
          <option value="Sejarah">Sejarah</option>
          <option value="Self-Dev">Self Development</option>
          <option value="Filsafat">Filsafat</option>
          <option value="Komik">Komik / Manga</option>
        </select>
      </div>

      <div style="margin-bottom: 14px;">
        <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 6px; font-weight: 600;">Deskripsi / Sinopsis Singkat</label>
        <textarea name="description" id="editDescription" rows="3" required style="width: 100%; padding: 9px 12px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none; resize: vertical; font-size: 0.9rem;"></textarea>
      </div>

      <div style="margin-bottom: 20px;">
        <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 6px; font-weight: 600;">Ganti Foto Cover (Biarkan kosong jika tidak ingin diubah)</label>
        <input type="file" name="cover" id="editCoverInput" accept="image/*" style="width: 100%; padding: 8px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; font-size: 0.85rem;">
        
        {{-- Live Image Preview --}}
        <div id="editCoverPreviewContainer" style="display: none; margin-top: 10px; align-items: center; gap: 12px;">
          <img id="editCoverPreview" src="" alt="Preview Cover" style="width: 50px; height: 72px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border-color);">
          <span style="font-size: 0.8rem; color: var(--text-muted);">Pratinjau cover</span>
        </div>
      </div>

      <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid var(--border-color); padding-top: 16px;">
        <button type="button" onclick="document.getElementById('modalEditBuku').style.display='none'" style="padding: 9px 16px; background: #21262d; border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; cursor: pointer; font-size: 0.88rem;">Batal</button>
        <button type="submit" style="padding: 9px 18px; background: var(--member-accent); border: none; color: #fff; font-weight: 600; border-radius: 6px; cursor: pointer; font-size: 0.88rem;">Perbarui Buku</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
  function openEditBookModal(id, title, author, genre, description, coverUrl) {
    const modal = document.getElementById('modalEditBuku');
    const form = document.getElementById('formEditBuku');

    // Action form diarahkan ke route admin.books.update
    form.action = `/admin/books/${id}`;

    // Isikan data ke input modal
    document.getElementById('editTitle').value = title;
    document.getElementById('editAuthor').value = author;
    document.getElementById('editGenre').value = genre;
    document.getElementById('editDescription').value = description;

    // Pratinjau cover saat ini jika ada
    const previewContainer = document.getElementById('editCoverPreviewContainer');
    const previewImg = document.getElementById('editCoverPreview');
    if (coverUrl) {
      previewImg.src = coverUrl;
      previewContainer.style.display = 'flex';
    } else {
      previewContainer.style.display = 'none';
    }

    modal.style.display = 'flex';
  }

  // Live file input preview
  function setupImagePreview(inputId, containerId, imgId) {
    const input = document.getElementById(inputId);
    const container = document.getElementById(containerId);
    const img = document.getElementById(imgId);

    if (input) {
      input.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = function(e) {
            img.src = e.target.result;
            container.style.display = 'flex';
          };
          reader.readAsDataURL(file);
        }
      });
    }
  }

  document.addEventListener('DOMContentLoaded', function() {
    setupImagePreview('addCoverInput', 'addCoverPreviewContainer', 'addCoverPreview');
    setupImagePreview('editCoverInput', 'editCoverPreviewContainer', 'editCoverPreview');
  });
</script>
@endpush
@endsection
