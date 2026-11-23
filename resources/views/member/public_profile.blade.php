@extends('layouts.app')

@section('title', 'Profil ' . $member->name . ' - Re:Book')

@section('content')
<div style="max-width: 1000px; margin: 0 auto;">

  <!-- Tombol Navigasi Kembali & Aksi Edit -->
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 12px;">
    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('home') }}" 
       style="display: inline-flex; align-items: center; gap: 8px; color: var(--text-muted); text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: color 0.2s;"
       onmouseover="this.style.color='var(--text-main)';" onmouseout="this.style.color='var(--text-muted)';">
      <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>

    @if(auth()->check() && auth()->id() === $member->id)
      <a href="{{ route('member.profile') }}" 
         style="background: var(--member-accent); color: white; padding: 8px 18px; border-radius: 6px; text-decoration: none; font-size: 0.88rem; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; transition: background 0.2s;">
        <i class="fa-solid fa-pen-to-square"></i> Edit Profil Saya
      </a>
    @endif
  </div>

  <!-- Header Profil Card -->
  <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; padding: 30px; margin-bottom: 35px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
    <div style="display: flex; align-items: flex-start; gap: 25px; flex-wrap: wrap;">
      
      <!-- Avatar Profil -->
      <x-user-avatar :user="$member" size="xl" />

      <!-- Info Utama Member (Nama, Bio, Genre, Stats) -->
      <div style="flex: 1; min-width: 280px;">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; margin-bottom: 8px;">
          <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
            <h1 style="color: white; font-size: 1.6rem; font-weight: 700; margin: 0;">{{ $member->name }}</h1>
            <span style="background: rgba(31, 111, 235, 0.15); border: 1px solid var(--member-accent); color: #58a6ff; font-size: 0.78rem; font-weight: 600; padding: 4px 12px; border-radius: 20px; text-transform: uppercase;">
              Member Komunitas
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
                      style="background: {{ $isFollowing ? '#21262d' : 'var(--member-accent)' }};
                             border: 1px solid {{ $isFollowing ? 'var(--border-color)' : 'var(--member-accent)' }};
                             color: white; padding: 6px 16px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s;">
                <i class="fa-solid {{ $isFollowing ? 'fa-user-check' : 'fa-user-plus' }}" id="icon-toggle-follow"></i>
                <span id="text-toggle-follow">{{ $isFollowing ? 'Mengikuti' : 'Ikuti Member' }}</span>
              </button>
            @endif
          @else
            <a href="{{ route('login') }}" style="background: var(--member-accent); color: white; padding: 6px 16px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
              <i class="fa-solid fa-user-plus"></i> Ikuti Member
            </a>
          @endauth
        </div>

        <!-- Bio Profil -->
        <p style="color: {{ !empty($member->bio) ? 'var(--text-main)' : 'var(--text-muted)' }}; font-style: {{ !empty($member->bio) ? 'normal' : 'italic' }}; font-size: 0.95rem; line-height: 1.6; margin: 0 0 16px 0;">
          {{ !empty($member->bio) ? $member->bio : 'Belum ada bio.' }}
        </p>

        <!-- Genre Favorit Badges -->
        <div style="margin-bottom: 18px;">
          <div style="font-size: 0.8rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
            <i class="fa-solid fa-bookmark" style="color: var(--member-accent);"></i> Genre Buku Favorit:
          </div>
          <div style="display: flex; flex-wrap: wrap; gap: 8px;">
            @if(!empty($member->favorite_genres) && count($member->favorite_genres) > 0)
              @foreach($member->favorite_genres as $genre)
                <span style="background: rgba(31, 111, 235, 0.12); border: 1px solid rgba(56, 139, 253, 0.4); color: #58a6ff; font-size: 0.8rem; font-weight: 500; padding: 4px 12px; border-radius: 16px;">
                  {{ $genre }}
                </span>
              @endforeach
            @else
              <span style="color: var(--text-muted); font-size: 0.85rem; font-style: italic;">
                Belum memilih genre favorit.
              </span>
            @endif
          </div>
        </div>

        <!-- Statistik Profil (Termasuk Followers & Following) -->
        <div style="display: flex; gap: 20px; padding-top: 14px; border-top: 1px solid var(--border-color); font-size: 0.85rem; color: var(--text-muted); flex-wrap: wrap;">
          <div>
            <strong id="member-followers-count" style="color: white; font-size: 1rem;">{{ $member->followers()->count() }}</strong> Pengikut
          </div>
          <div>
            <strong style="color: white; font-size: 1rem;">{{ $member->following()->count() }}</strong> Mengikuti
          </div>
          <div>
            <strong style="color: white; font-size: 1rem;">{{ $reviewsCount }}</strong> Ulasan
          </div>
          <div>
            <strong style="color: white; font-size: 1rem;">{{ $member->readingShelves()->count() }}</strong> Buku di Rak
          </div>
          <div>
            Bergabung sejak <strong style="color: white;">{{ $member->created_at ? $member->created_at->format('M Y') : '-' }}</strong>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Showcase 4 Buku Favorit Member -->
  <div style="margin-bottom: 40px;">
    <h3 style="color: white; font-weight: bold; font-size: 1.2rem; margin: 0 0 16px 0; display: flex; align-items: center; gap: 8px;">
      <span style="color: var(--star-rating);">⭐</span> 4 Buku Favorit Pilihan {{ $member->name }}
    </h3>

    @if($favoriteBooks->isEmpty())
      <div style="background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 30px; text-align: center;">
        <i class="fa-regular fa-star" style="font-size: 2rem; color: #8b949e; margin-bottom: 8px;"></i>
        <p style="color: var(--text-muted); font-size: 0.9rem; margin: 0;">Member ini belum memajang buku favorit.</p>
      </div>
    @else
      <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 18px;">
        @foreach ($favoriteBooks as $b)
          <x-book-card :book="$b" :showLike="false" badge="⭐ Top 4" badgeColor="#d29922" />
        @endforeach
      </div>
    @endif
  </div>

  <!-- Riwayat Ulasan Buku yang Ditulis Member -->
  <div>
    <h3 style="color: white; font-weight: bold; font-size: 1.2rem; margin: 0 0 16px 0; display: flex; align-items: center; gap: 8px;">
      <i class="fa-solid fa-pen-nib" style="color: var(--member-accent);"></i> Ulasan Buku oleh {{ $member->name }}
      <span style="font-size: 0.9rem; color: var(--text-muted); font-weight: normal;">({{ $reviewsCount }})</span>
    </h3>

    @if($memberReviews->isEmpty())
      <div style="background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 35px 20px; text-align: center;">
        <i class="fa-regular fa-comment-dots" style="font-size: 2rem; color: #8b949e; margin-bottom: 8px;"></i>
        <p style="color: var(--text-muted); font-size: 0.9rem; margin: 0;">Member ini belum menulis ulasan buku.</p>
      </div>
    @else
      <div style="display: flex; flex-direction: column; gap: 16px;">
        @foreach ($memberReviews as $rev)
          <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 20px; display: flex; gap: 18px; align-items: flex-start; flex-wrap: wrap;">
            
            <!-- Cover Buku yang Diulas -->
            <div style="width: 65px; height: 95px; border-radius: 4px; overflow: hidden; background: #0d1117; flex-shrink: 0; border: 1px solid var(--border-color);">
              @if(isset($rev->book->cover) && $rev->book->cover)
                <img src="{{ asset('storage/' . $rev->book->cover) }}" alt="{{ $rev->book->title ?? 'Buku' }}" 
                     style="width: 100%; height: 100%; object-fit: cover;">
              @else
                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 1.2rem;">
                  <i class="fa-solid fa-book"></i>
                </div>
              @endif
            </div>

            <!-- Konten Ulasan -->
            <div style="flex: 1; min-width: 250px;">
              <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 8px; margin-bottom: 8px;">
                <div>
                  <h4 style="margin: 0 0 4px 0; font-size: 1.05rem;">
                    <a href="{{ route('books.show', $rev->book_id) }}" style="color: white; text-decoration: none; font-weight: 600;">
                      {{ $rev->book->title ?? 'Buku' }}
                    </a>
                  </h4>
                  <span style="font-size: 0.8rem; color: var(--text-muted);">
                    {{ $rev->book->genre ?? 'Novel' }} &bull; Ditulis pada {{ $rev->created_at ? $rev->created_at->format('d M Y') : '' }}
                  </span>
                </div>

                <div style="background: rgba(227, 179, 65, 0.12); border: 1px solid var(--star-rating); padding: 3px 10px; border-radius: 15px; color: var(--star-rating); font-weight: bold; font-size: 0.85rem; display: flex; align-items: center; gap: 5px;">
                  <x-star-rating :rating="$rev->rating" size="xs" :showScore="false" />
                  <span>{{ number_format($rev->rating, 1) }} / 5.0</span>
                </div>
              </div>

              <p style="color: var(--text-main); font-size: 0.92rem; line-height: 1.6; margin: 0 0 12px 0; background: rgba(255,255,255,0.02); padding: 10px 14px; border-radius: 6px; border-left: 3px solid var(--member-accent);">
                "{{ $rev->content }}"
              </p>

              <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 0.78rem; color: var(--text-muted);">
                  <i class="fa-regular fa-comments"></i> {{ $rev->comments->count() }} Komentar Balasan
                </span>
                <a href="{{ route('books.show', $rev->book_id) }}" style="color: var(--member-accent); font-size: 0.82rem; font-weight: 600; text-decoration: none;">
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
        btnFollow.style.background = '#21262d';
        btnFollow.style.borderColor = 'var(--border-color)';
        if (iconEl) iconEl.className = 'fa-solid fa-user-check';
        if (textEl) textEl.textContent = 'Mengikuti';
      } else {
        btnFollow.style.background = 'var(--member-accent)';
        btnFollow.style.borderColor = 'var(--member-accent)';
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
          window.showToast({
            title: newFollowingState ? 'Mengikuti' : 'Berhenti Mengikuti',
            message: data.message,
            type: 'success'
          });
        } else {
          throw new Error(data.message);
        }
      } catch (err) {
        // Rollback
        btnFollow.dataset.following = isCurrentlyFollowing ? 'true' : 'false';
        if (countEl) countEl.textContent = currentCount;
        if (isCurrentlyFollowing) {
          btnFollow.style.background = '#21262d';
          btnFollow.style.borderColor = 'var(--border-color)';
          if (iconEl) iconEl.className = 'fa-solid fa-user-check';
          if (textEl) textEl.textContent = 'Mengikuti';
        } else {
          btnFollow.style.background = 'var(--member-accent)';
          btnFollow.style.borderColor = 'var(--member-accent)';
          if (iconEl) iconEl.className = 'fa-solid fa-user-plus';
          if (textEl) textEl.textContent = 'Ikuti Member';
        }
        window.showToast({ title: 'Gagal', message: err.message || 'Koneksi bermasalah.', type: 'error' });
      }
    });
  }
});
</script>
@endpush
