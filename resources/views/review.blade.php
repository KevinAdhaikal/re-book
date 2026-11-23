@extends(auth()->user() && auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.member')

@section('title', 'Moderasi & Daftar Ulasan - Re:Book')

@section(auth()->user() && auth()->user()->role === 'admin' ? 'admin_content' : 'member_content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
  <div>
    <h2>💬 {{ auth()->user()->role === 'admin' ? 'Moderasi Ulasan Komunitas' : 'Ulasan Saya' }}</h2>
    <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 4px;">
      {{ auth()->user()->role === 'admin' ? 'Pantau dan hapus ulasan yang melanggar ketentuan komunitas.' : 'Kelola dan edit ulasan buku yang pernah kamu bagikan.' }}
    </p>
  </div>
</div>

<!-- Feed / Card List Review -->
<div style="display: flex; flex-direction: column; gap: 15px;">
  @forelse ($reviews as $r)
    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 20px;">
      <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
        
        <!-- Header Evaluasi (User & Buku) -->
        <div style="display: flex; align-items: center; gap: 12px;">
          <div style="width: 42px; height: 42px; border-radius: 50%; background: var(--member-accent); display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.1rem;">
            {{ substr($r->user->name ?? 'U', 0, 1) }}
          </div>
          <div>
            <h4 style="font-size: 0.95rem; font-weight: 600;">{{ $r->user->name ?? 'Pengguna' }}</h4>
            <p style="font-size: 0.8rem; color: var(--text-muted);">
              mengulas <a href="{{ route('books.show', $r->book_id) }}" style="color: var(--member-accent); text-decoration: none; font-weight: 600;">{{ $r->book->title ?? 'Judul Buku' }}</a> 
              • <span style="color: var(--text-muted);">{{ $r->created_at->diffForHumans() }}</span>
            </p>
          </div>
        </div>

        <!-- Rating Bintang -->
        <div style="color: var(--star-rating); font-size: 0.9rem; background: #21262D; border: 1px solid var(--border-color); padding: 4px 10px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px;">
          <x-star-rating :rating="$r->rating" size="xs" :showScore="false" />
          <span style="color: var(--text-main); font-weight: 600; margin-left: 4px;">{{ number_format($r->rating, 1) }}</span>
        </div>

      </div>

      <!-- Isi Teks Ulasan (Tersensor Filter Kata Kasar) -->
      <div style="background: var(--bg-main); border: 1px solid var(--border-color); border-radius: 6px; padding: 12px 15px; margin-bottom: 15px; font-size: 0.95rem; line-height: 1.5; color: var(--text-main);">
        "{{ $r->content }}"
      </div>

      <!-- Tombol Aksi (Edit / Hapus) -->
      <div style="display: flex; justify-content: space-between; align-items: center;">
        
        <!-- Info Komentar -->
        <span style="font-size: 0.85rem; color: var(--text-muted);">
          <i class="fa-regular fa-comment"></i> {{ $r->comments->count() ?? 0 }} Komentar
        </span>

        <div style="display: flex; gap: 8px;">
          {{-- Tombol Edit (Hanya Pemilik Ulasan) --}}
          @if(auth()->id() === $r->user_id)
            <button onclick="openEditModal({{ $r->id }}, {{ $r->rating }}, '{{ addslashes($r->content) }}')" style="background: #21262D; border: 1px solid var(--border-color); color: var(--member-accent); padding: 6px 12px; border-radius: 4px; font-size: 0.8rem; font-weight: 600; cursor: pointer;">
              <i class="fa-solid fa-pen-to-square"></i> Edit
            </button>
          @endif

          {{-- Tombol Hapus (Pemilik Ulasan atau Admin) --}}
          @if(auth()->id() === $r->user_id || auth()->user()->role === 'admin')
            <form action="{{ route('member.reviews.destroy', $r->id) }}" method="POST" onsubmit="return confirm('Hapus ulasan ini secara permanen?');" style="display: inline;">
              @csrf
              @method('DELETE')
              <button type="submit" style="background: var(--admin-danger); color: #fff; border: none; padding: 6px 12px; border-radius: 4px; font-size: 0.8rem; font-weight: 600; cursor: pointer;">
                <i class="fa-solid fa-trash"></i> Hapus
              </button>
            </form>
          @endif
        </div>

      </div>
    </div>
  @empty
    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 40px; text-align: center; color: var(--text-muted);">
      <i class="fa-regular fa-comments" style="font-size: 2.5rem; margin-bottom: 15px; color: var(--border-color);"></i>
      <p>Belum ada ulasan yang ditulis.</p>
    </div>
  @endforelse
</div>

<!-- Modal Pop-up Edit Ulasan -->
<div id="modalEditReview" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.75); backdrop-filter: blur(4px); justify-content: center; align-items: center; z-index: 2000;">
  <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 10px; padding: 25px; width: 100%; max-width: 480px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">
      <h3 style="font-size: 1.1rem;">Edit Ulasan Buku</h3>
      <button onclick="document.getElementById('modalEditReview').style.display='none'" style="background: none; border: none; color: var(--text-muted); font-size: 1.2rem; cursor: pointer;">&times;</button>
    </div>

    <form id="formEditReview" method="POST">
      @csrf
      @method('PUT')

      <div style="margin-bottom: 15px;">
        <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 6px;">Rating Bintang (1-5)</label>
        <select name="rating" id="editRating" required style="width: 100%; padding: 10px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none;">
          <option value="5">⭐⭐⭐⭐⭐ (5.0 - Mahakarya)</option>
          <option value="4.5">⭐⭐⭐⭐½ (4.5 - Luar Biasa)</option>
          <option value="4">⭐⭐⭐⭐ (4.0 - Sangat Bagus)</option>
          <option value="3.5">⭐⭐⭐½ (3.5 - Bagus & Menarik)</option>
          <option value="3">⭐⭐⭐ (3.0 - Cukup)</option>
          <option value="2.5">⭐⭐½ (2.5 - Biasa Saja)</option>
          <option value="2">⭐⭐ (2.0 - Kurang)</option>
          <option value="1.5">⭐½ (1.5 - Kurang Memuaskan)</option>
          <option value="1">⭐ (1.0 - Buruk)</option>
          <option value="0.5">½ (0.5 - Sangat Buruk)</option>
        </select>
      </div>

      <div style="margin-bottom: 20px;">
        <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 6px;">Teks Ulasan</label>
        <textarea name="content" id="editContent" rows="4" required style="width: 100%; padding: 10px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none; resize: vertical;"></textarea>
      </div>

      <div style="display: flex; justify-content: flex-end; gap: 10px;">
        <button type="button" onclick="document.getElementById('modalEditReview').style.display='none'" style="padding: 8px 14px; background: #21262d; border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; cursor: pointer;">Batal</button>
        <button type="submit" style="padding: 8px 16px; background: var(--member-accent); border: none; color: #fff; font-weight: 600; border-radius: 6px; cursor: pointer;">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
  function openEditModal(reviewId, rating, content) {
    const modal = document.getElementById('modalEditReview');
    const form = document.getElementById('formEditReview');
    
    // Set Action Form ke route update laravel
    form.action = `/member/reviews/${reviewId}`;
    
    // Set Value Field
    document.getElementById('editRating').value = rating;
    document.getElementById('editContent').value = content;
    
    modal.style.display = 'flex';
  }
</script>
@endpush
@endsection