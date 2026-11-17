@extends('layouts.app')

@section('content')
  <h2 style="border-left: 4px solid var(--member-accent); padding-left: 10px; margin-bottom: 20px;">Rekomendasi Buku
    Terpopuler</h2>

  <div
    style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 20px; margin-bottom: 50px;">
    @php
      $userLikedIds = auth()->check() ? \Illuminate\Support\Facades\DB::table('book_likes')->where('user_id', auth()->id())->pluck('book_id')->toArray() : [];
    @endphp

    @foreach ($books as $b)
      @php
        $isLiked = in_array($b->id, $userLikedIds);
      @endphp
      <div
        style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; overflow: hidden; display: flex; flex-direction: column; position: relative;">

        {{-- Tombol Love di Pojok Kanan Atas Cover --}}
        @auth
          <button type="button" class="btn-like-toggle" data-book-id="{{ $b->id }}" data-action="{{ route('member.likes.toggle', $b->id) }}" title="{{ $isLiked ? 'Batal Sukai' : 'Sukai Buku' }}"
            style="position: absolute; top: 10px; right: 10px; z-index: 99; background-color: #0d1117; border: 1px solid #30363d; width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; color: {{ $isLiked ? '#F78166' : '#8B949E' }}; font-size: 0.95rem; padding: 0; transition: transform 0.15s ease, color 0.2s ease;">
            <i class="{{ $isLiked ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>
          </button>
        @else
          <a href="{{ route('login') }}" title="Login untuk menyukai buku"
            style="position: absolute; top: 10px; right: 10px; z-index: 99; background-color: #0d1117; border: 1px solid #30363d; width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #8B949E; text-decoration: none; font-size: 0.95rem;">
            <i class="fa-regular fa-heart"></i>
          </a>
        @endauth

        @if($b->cover)
          <img
            src="{{ asset('storage/' . $b->cover) }}"
            alt="{{ $b->title }}"
            onerror="this.onerror=null; this.parentElement.querySelector('.cover-fallback').style.display='flex'; this.style.display='none';"
            style="width: 100%; height: 250px; object-fit: cover; display: block;">
          <div class="cover-fallback" style="display: none; width: 100%; height: 250px; background: #161b22; flex-direction: column; align-items: center; justify-content: center; gap: 8px; color: var(--text-muted); border-bottom: 1px solid var(--border-color); text-align: center; padding: 15px;">
            <i class="fa-solid fa-book" style="font-size: 2.2rem; color: #30363d;"></i>
            <span style="font-size: 0.8rem; font-weight: 500; color: #8b949e;">{{ Str::limit($b->title, 25) }}</span>
          </div>
        @else
          <div style="width: 100%; height: 250px; background: #161b22; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; color: var(--text-muted); border-bottom: 1px solid var(--border-color); text-align: center; padding: 15px;">
            <i class="fa-solid fa-book" style="font-size: 2.2rem; color: #30363d;"></i>
            <span style="font-size: 0.8rem; font-weight: 500; color: #8b949e;">{{ Str::limit($b->title, 25) }}</span>
          </div>
        @endif
        <div style="padding: 12px; display: flex; flex-direction: column; flex-grow: 1;">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
            <div style="color: var(--star-rating); font-size: 0.9rem;">
              <i class="fa-solid fa-star"></i> {{ number_format($b->reviews_avg_rating ?? 0, 1) }}
            </div>
            <div style="color: var(--text-muted); font-size: 0.78rem;" title="Jumlah pembaca yang menyukai buku ini">
              <i class="fa-solid fa-heart" style="color: #F78166; font-size: 0.75rem;"></i>
              <span id="book-likes-count-{{ $b->id }}">{{ $b->liked_by_users_count ?? $b->likedByUsers->count() }}</span>
            </div>
          </div>
          <h4 style="margin-bottom: 6px; font-size: 0.95rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $b->title }}</h4>
          <p style="color: var(--text-muted); font-size: 0.8rem; margin-bottom: 12px;">{{ $b->genre }}</p>
          <a href="{{ route('books.show', $b->id) }}"
            style="margin-top: auto; background: #21262D; color: var(--member-accent); text-align: center; padding: 6px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 600;">Lihat
            Ulasan</a>
        </div>
      </div>
    @endforeach
  </div>

  <!-- ================= SECTION ULASAN TERPOPULER & KOMENTAR ================= -->
  <section style="padding: 60px 0; background: var(--bg-main); border-top: 1px solid var(--border-color);">
    <div style="max-width: 1100px; margin: 0 auto; padding: 0 20px;">

      <!-- Judul Section -->
      <div style="text-align: center; margin-bottom: 45px;">
        <h2 style="font-size: 2rem; color: var(--text-main); margin-bottom: 10px;">Ulasan & Diskusi Pembaca Terpopuler
        </h2>
        <p style="color: var(--text-muted); font-size: 1rem;">Lihat apa kata komunitas tentang buku favorit mereka dan
          gabung dalam diskusi!</p>
      </div>

      <!-- Grid Ulasan -->
      <div style="display: flex; flex-direction: column; gap: 25px;">
        @forelse ($popularReviews ?? [] as $review)
          <div
            style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 10px; padding: 25px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">

            <!-- Header Ulasan: Info User & Buku -->
            <div
              style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px; flex-wrap: wrap; gap: 10px;">
              <div style="display: flex; align-items: center; gap: 12px;">
                <a href="{{ $review->user ? route('users.profile', $review->user->id) : '#' }}" style="text-decoration: none; display: flex; align-items: center; gap: 12px;">
                  <div
                    style="width: 42px; height: 42px; background: var(--member-accent); color: #fff; border-radius: 50%; overflow: hidden; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.1rem; border: 1px solid var(--border-color); flex-shrink: 0;">
                    @if($review->user && $review->user->avatarUrl())
                      <img src="{{ $review->user->avatarUrl() }}" alt="{{ $review->user->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                      {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                    @endif
                  </div>
                  <div>
                    <h4 style="margin: 0; color: var(--text-main); font-size: 1rem; transition: color 0.2s;">
                      {{ $review->user->name ?? 'Pengguna' }}
                    </h4>
                    <span style="font-size: 0.85rem; color: var(--text-muted);">Mengulas
                      <strong>{{ $review->book->title ?? 'Buku' }}</strong></span>
                  </div>
                </a>
              </div>

              <!-- Rating Bintang -->
              <div
                style="background: rgba(230, 145, 56, 0.15); border: 1px solid var(--star-rating); padding: 4px 10px; border-radius: 20px; color: var(--star-rating); font-weight: bold; font-size: 0.9rem;">
                <i class="fa-solid fa-star"></i> {{ $review->rating }}.0 / 5.0
              </div>
            </div>

            <!-- Isi Ulasan -->
            <p
              style="color: var(--text-main); font-size: 0.98rem; line-height: 1.6; margin-bottom: 20px; background: rgba(255,255,255,0.02); padding: 12px 15px; border-radius: 6px; border-left: 3px solid var(--member-accent);">
              "{{ $review->content }}"
            </p>

            <!-- Section Komentar Balasan -->
            <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid var(--border-color);">
              <h5
                style="color: var(--text-muted); margin-bottom: 12px; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
                <i class="fa-regular fa-comments"></i> {{ $review->comments->count() }} Komentar
              </h5>

              <!-- Daftar Balasan Komentar -->
              <div
                style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 15px; padding-left: 15px; border-left: 2px solid var(--border-color);">
                @forelse ($review->comments as $comment)
                  <div style="background: rgba(255, 255, 255, 0.03); padding: 8px 12px; border-radius: 6px; display: flex; justify-content: space-between; align-items: flex-start; gap: 10px;">
                    <div style="display: flex; align-items: flex-start; gap: 8px; flex: 1;">
                      <a href="{{ $comment->user ? route('users.profile', $comment->user->id) : '#' }}" style="text-decoration: none; flex-shrink: 0;">
                        <div style="width: 24px; height: 24px; border-radius: 50%; overflow: hidden; background: var(--member-accent); color: white; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: bold; margin-top: 2px; border: 1px solid var(--border-color);">
                          @if($comment->user && $comment->user->avatarUrl())
                            <img src="{{ $comment->user->avatarUrl() }}" alt="{{ $comment->user->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                          @else
                            {{ strtoupper(substr($comment->user->name ?? 'M', 0, 1)) }}
                          @endif
                        </div>
                      </a>
                      <div>
                        <a href="{{ $comment->user ? route('users.profile', $comment->user->id) : '#' }}" style="font-weight: 600; font-size: 0.85rem; color: var(--member-accent); text-decoration: none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                          {{ $comment->user->name ?? 'Member' }}:
                        </a>
                        <span style="font-size: 0.88rem; color: var(--text-main); margin-left: 6px;">{{ $comment->comment }}</span>
                        <div style="font-size: 0.72rem; color: var(--text-muted); margin-top: 3px;">
                          <i class="fa-regular fa-clock"></i> {{ $comment->created_at ? $comment->created_at->diffForHumans() : '' }}
                        </div>
                      </div>
                    </div>

                    @if(auth()->check() && (auth()->id() === $comment->user_id || auth()->user()->role === 'admin'))
                      <form action="{{ route('member.comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Hapus komentar Anda?');" style="margin: 0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" title="Hapus Komentar" style="background: none; border: none; color: #8b949e; font-size: 0.75rem; cursor: pointer; padding: 2px 6px;">
                          <i class="fa-solid fa-trash-can"></i>
                        </button>
                      </form>
                    @endif
                  </div>
                @empty
                  <p style="font-size: 0.85rem; color: var(--text-muted); font-style: italic; margin: 0;">Belum ada komentar
                    balasan. Jadilah yang pertama membalas!</p>
                @endforelse
              </div>

              <!-- Form Kirim Komentar Balasan (Hanya Tampil Jika Sudah Login) -->
              @auth
                <form action="{{ route('member.comments.store', $review->id) }}" method="POST"
                  style="display: flex; gap: 10px; margin-top: 10px;">
                  @csrf
                  <input type="text" name="comment" placeholder="Tulis komentar balasan kamu..." required
                    style="flex: 1; padding: 8px 14px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; font-size: 0.88rem; outline: none;">
                  <button type="submit"
                    style="background: var(--member-accent); color: #fff; border: none; padding: 8px 16px; border-radius: 6px; font-weight: 600; font-size: 0.85rem; cursor: pointer;">Kirim</button>
                </form>
              @else
                <p style="font-size: 0.82rem; color: var(--text-muted); margin: 0;">
                  <a href="{{ route('login') }}" style="color: var(--member-accent); text-decoration: underline;">Login</a>
                  untuk ikut membalas komentar ulasan ini.
                </p>
              @endauth

            </div>

          </div>
        @empty
          <div
            style="text-align: center; padding: 40px; background: var(--bg-card); border-radius: 8px; border: 1px solid var(--border-color);">
            <p style="color: var(--text-muted); margin: 0;">Belum ada ulasan populer yang ditampilkan.</p>
          </div>
        @endforelse
      </div>

    </div>
  </section>

  @guest
    <div
      style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; padding: 30px; max-width: 500px; margin: 0 auto; text-align: center;">
      <h3>Gabung Komunitas Re:Book</h3>
      <p style="color: var(--text-muted); margin: 10px 0 20px 0; font-size: 0.9rem;">Buat akun gratis untuk mengulas buku &
        pamer 4 buku favoritmu!</p>
      <a href="{{ route('register') }}"
        style="background: var(--member-accent); color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 600; display: inline-block;">Daftar
        Sekarang</a>
    </div>
  @endguest
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const csrfToken = '{{ csrf_token() }}';
  
  document.querySelectorAll('.btn-like-toggle').forEach(button => {
    button.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      
      const actionUrl = this.dataset.action;
      const icon = this.querySelector('i');
      
      this.style.pointerEvents = 'none';
      this.style.transform = 'scale(0.85)';
      
      fetch(actionUrl, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'Content-Type': 'application/json'
        }
      })
      .then(response => {
        if (response.status === 401) {
          window.location.href = '{{ route('login') }}';
          return;
        }
        return response.json();
      })
      .then(data => {
        this.style.pointerEvents = 'auto';
        this.style.transform = 'scale(1)';
        
        if (data && data.success) {
          if (data.liked) {
            icon.classList.remove('fa-regular');
            icon.classList.add('fa-solid');
            this.style.color = '#F78166';
            this.setAttribute('title', 'Batal Sukai');
          } else {
            icon.classList.remove('fa-solid');
            icon.classList.add('fa-regular');
            this.style.color = '#8B949E';
            this.setAttribute('title', 'Sukai Buku');
          }

          const countEl = document.getElementById('book-likes-count-' + data.book_id);
          if (countEl && typeof data.book_likes_count !== 'undefined') {
            countEl.textContent = data.book_likes_count;
          }
        }
      })
      .catch(err => {
        console.error('Like toggle error:', err);
        this.style.pointerEvents = 'auto';
        this.style.transform = 'scale(1)';
      });
    });
  });
});
</script>
@endpush