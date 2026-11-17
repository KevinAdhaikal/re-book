@extends('layouts.admin')

@section('title', 'Kelola Buku - Admin Re:Book')

@section('admin_content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
  <div>
    <h2>Kelola Daftar Buku</h2>
    <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 4px;">Tambah, ubah, dan hapus katalog buku di Re:Book.</p>
  </div>
  <button onclick="document.getElementById('modalTambahBuku').style.display='flex'" style="background: var(--admin-accent); color: #fff; border: none; padding: 10px 18px; border-radius: 6px; font-weight: 600; font-size: 0.9rem; cursor: pointer; display: flex; align-items: center; gap: 8px;">
    <i class="fa-solid fa-plus"></i> Tambah Buku Baru
  </button>
</div>

<!-- Tabel List Buku -->
<table class="custom-table">
  <thead>
    <tr>
      <th style="width: 60px;">ID</th>
      <th style="width: 80px;">Cover</th>
      <th>Judul Buku</th>
      <th>Penulis</th>
      <th>Genre</th>
      <th>Rating Rata-rata</th>
      <th style="width: 160px;">Aksi</th>
    </tr>
  </thead>
  <tbody>
    @forelse ($books as $b)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>
          <img src="{{ $b->cover ? asset('storage/'.$b->cover) : 'https://via.placeholder.com/50x70/161b22/8b949e?text=Cover' }}" alt="Cover Buku" style="width: 45px; height: 60px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border-color);">
        </td>
        <td><strong>{{ $b->title }}</strong></td>
        <td>{{ $b->author }}</td>
        <td><span style="background: #21262d; border: 1px solid var(--border-color); padding: 3px 8px; border-radius: 4px; font-size: 0.8rem;">{{ $b->genre }}</span></td>
        <td>
          <span style="color: var(--star-rating); font-weight: 600;">
            <i class="fa-solid fa-star"></i> {{ number_format($b->reviews_avg_rating ?? 0, 1) }}
          </span>
        </td>
        <td>
          <div style="display: flex; gap: 6px;">
            <!-- Tombol Edit Buku -->
            <button type="button" onclick="openEditBookModal({{ $b->id }}, {{ json_encode($b->title) }}, {{ json_encode($b->author) }}, {{ json_encode($b->genre) }}, {{ json_encode($b->description) }})" 
              style="background: #21262D; border: 1px solid var(--border-color); color: var(--member-accent); padding: 6px 10px; border-radius: 4px; font-size: 0.8rem; font-weight: 600; cursor: pointer;">
            <i class="fa-solid fa-pen-to-square"></i> Edit
            </button>

            <!-- Tombol Hapus Buku -->
            <form action="{{ route('admin.books.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini?');" style="display: inline;">
              @csrf
              @method('DELETE')
              <button type="submit" style="background: var(--admin-danger); color: #fff; border: none; padding: 6px 10px; border-radius: 4px; font-size: 0.8rem; font-weight: 600; cursor: pointer;">
                <i class="fa-solid fa-trash"></i> Hapus
              </button>
            </form>
          </div>
        </td>
      </tr>
    @empty
      <tr>
        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">
          Belum ada buku yang terdaftar. Klik tombol <strong>Tambah Buku Baru</strong> di atas.
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
<div id="modalTambahBuku" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); backdrop-filter: blur(4px); justify-content: center; align-items: center; z-index: 2000;">
  <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 10px; padding: 30px; width: 100%; max-width: 500px; position: relative;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
      <h3 style="font-size: 1.2rem;">Tambah Buku Baru</h3>
      <button onclick="document.getElementById('modalTambahBuku').style.display='none'" style="background: none; border: none; color: var(--text-muted); font-size: 1.2rem; cursor: pointer;">&times;</button>
    </div>

    <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      
      <div style="margin-bottom: 15px;">
        <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 6px;">Judul Buku</label>
        <input type="text" name="title" required placeholder="Masukkan judul buku" style="width: 100%; padding: 10px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none;">
      </div>

      <div style="margin-bottom: 15px;">
        <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 6px;">Penulis / Pengarang</label>
        <input type="text" name="author" required placeholder="Nama penulis" style="width: 100%; padding: 10px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none;">
      </div>

      <div style="margin-bottom: 15px;">
        <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 6px;">Genre</label>
        <select name="genre" required style="width: 100%; padding: 10px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none;">
          <option value="Novel">Novel</option>
          <option value="Fiksi">Fiksi</option>
          <option value="Non-Fiksi">Non-Fiksi</option>
          <option value="Sejarah">Sejarah</option>
          <option value="Self-Dev">Self Development</option>
          <option value="Filsafat">Filsafat</option>
          <option value="Komik">Komik / Manga</option>
        </select>
      </div>

      <div style="margin-bottom: 15px;">
        <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 6px;">Deskripsi / Sinopsis Singkat</label>
        <textarea name="description" rows="3" required placeholder="Tulis ringkasan tentang buku ini..." style="width: 100%; padding: 10px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none; resize: vertical;"></textarea>
      </div>

      <div style="margin-bottom: 25px;">
        <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 6px;">Foto Cover Buku (Format: JPG, PNG, Max 2MB)</label>
        <input type="file" name="cover" accept="image/*" style="width: 100%; padding: 8px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px;">
      </div>

      <div style="display: flex; justify-content: flex-end; gap: 10px;">
        <button type="button" onclick="document.getElementById('modalTambahBuku').style.display='none'" style="padding: 10px 16px; background: #21262d; border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; cursor: pointer;">Batal</button>
        <button type="submit" style="padding: 10px 18px; background: var(--admin-accent); border: none; color: #fff; font-weight: 600; border-radius: 6px; cursor: pointer;">Simpan Buku</button>
      </div>
    </form>
  </div>
</div>

<!-- ================= MODAL EDIT BUKU ================= -->
<div id="modalEditBuku" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); backdrop-filter: blur(4px); justify-content: center; align-items: center; z-index: 2000;">
  <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 10px; padding: 30px; width: 100%; max-width: 500px; position: relative;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
      <h3 style="font-size: 1.2rem;">Edit Data Buku</h3>
      <button onclick="document.getElementById('modalEditBuku').style.display='none'" style="background: none; border: none; color: var(--text-muted); font-size: 1.2rem; cursor: pointer;">&times;</button>
    </div>

    <form id="formEditBuku" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      
      <div style="margin-bottom: 15px;">
        <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 6px;">Judul Buku</label>
        <input type="text" name="title" id="editTitle" required style="width: 100%; padding: 10px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none;">
      </div>

      <div style="margin-bottom: 15px;">
        <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 6px;">Penulis / Pengarang</label>
        <input type="text" name="author" id="editAuthor" required style="width: 100%; padding: 10px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none;">
      </div>

      <div style="margin-bottom: 15px;">
        <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 6px;">Genre</label>
        <select name="genre" id="editGenre" required style="width: 100%; padding: 10px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none;">
          <option value="Novel">Novel</option>
          <option value="Fiksi">Fiksi</option>
          <option value="Non-Fiksi">Non-Fiksi</option>
          <option value="Sejarah">Sejarah</option>
          <option value="Self-Dev">Self Development</option>
          <option value="Filsafat">Filsafat</option>
          <option value="Komik">Komik / Manga</option>
        </select>
      </div>

      <div style="margin-bottom: 15px;">
        <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 6px;">Deskripsi / Sinopsis Singkat</label>
        <textarea name="description" id="editDescription" rows="3" required style="width: 100%; padding: 10px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none; resize: vertical;"></textarea>
      </div>

      <div style="margin-bottom: 25px;">
        <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 6px;">Ganti Foto Cover (Biarkan kosong jika tidak ingin diubah)</label>
        <input type="file" name="cover" accept="image/*" style="width: 100%; padding: 8px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px;">
      </div>

      <div style="display: flex; justify-content: flex-end; gap: 10px;">
        <button type="button" onclick="document.getElementById('modalEditBuku').style.display='none'" style="padding: 10px 16px; background: #21262d; border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; cursor: pointer;">Batal</button>
        <button type="submit" style="padding: 10px 18px; background: var(--member-accent); border: none; color: #fff; font-weight: 600; border-radius: 6px; cursor: pointer;">Perbarui Buku</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
  function openEditBookModal(id, title, author, genre, description) {
    const modal = document.getElementById('modalEditBuku');
    const form = document.getElementById('formEditBuku');

    // Action form diarahkan ke route admin.books.update
    form.action = `/admin/books/${id}`;

    // Isikan data ke input modal
    document.getElementById('editTitle').value = title;
    document.getElementById('editAuthor').value = author;
    document.getElementById('editGenre').value = genre;
    document.getElementById('editDescription').value = description;

    modal.style.display = 'flex';
  }
</script>
@endpush
@endsection