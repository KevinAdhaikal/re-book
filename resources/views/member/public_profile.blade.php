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
      <div style="width: 100px; height: 100px; border-radius: 50%; overflow: hidden; background: #21262d; border: 3px solid var(--border-color); flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
        @if($member->avatarUrl())
          <img src="{{ $member->avatarUrl() }}" alt="{{ $member->name }}" 
               onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';"
               style="width: 100%; height: 100%; object-fit: cover;">
          <div style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center; font-size: 2.5rem; font-weight: bold; color: var(--member-accent);">
            {{ strtoupper(substr($member->name, 0, 1)) }}
          </div>
        @else
          <div style="font-size: 2.5rem; font-weight: bold; color: var(--member-accent);">
            {{ strtoupper(substr($member->name, 0, 1)) }}
          </div>
        @endif
      </div>

      <!-- Info Utama Member (Nama, Bio, Genre, Stats) -->
      <div style="flex: 1; min-width: 280px;">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; margin-bottom: 8px;">
          <h1 style="color: white; font-size: 1.6rem; font-weight: 700; margin: 0;">{{ $member->name }}</h1>
          <span style="background: rgba(31, 111, 235, 0.15); border: 1px solid var(--member-accent); color: #58a6ff; font-size: 0.78rem; font-weight: 600; padding: 4px 12px; border-radius: 20px; text-transform: uppercase;">
            Member Komunitas
          </span>
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

        <!-- Statistik Profil -->
        <div style="display: flex; gap: 20px; padding-top: 14px; border-top: 1px solid var(--border-color); font-size: 0.85rem; color: var(--text-muted);">
          <div>
            <strong style="color: white; font-size: 1rem;">{{ $reviewsCount }}</strong> Ulasan Ditulis
          </div>
          <div>
            <strong style="color: white; font-size: 1rem;">{{ $favoriteBooks->count() }}</strong> Buku Favorit Ditampilkan
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
          <div style="background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
              @if(isset($b->cover) && $b->cover)
                <img src="{{ asset('storage/' . $b->cover) }}" alt="{{ $b->title }}"
                     onerror="this.onerror=null; this.parentElement.querySelector('.cover-fallback').style.display='flex'; this.style.display='none';"
                     style="width: 100%; height: 250px; object-fit: cover; display: block;">
                <div class="cover-fallback" style="display: none; width: 100%; height: 250px; background-color: #0d1117; flex-direction: column; align-items: center; justify-content: center; color: #8b949e; font-size: 0.75rem; padding: 15px; text-align: center;">
                  <i class="fa-solid fa-book" style="font-size: 2rem; color: #30363d; margin-bottom: 6px;"></i>
                  <span>{{ Str::limit($b->title, 25) }}</span>
                </div>
              @else
                <div style="width: 100%; height: 250px; background-color: #0d1117; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #8b949e; font-size: 0.75rem; padding: 15px; text-align: center;">
                  <i class="fa-solid fa-book" style="font-size: 2rem; color: #30363d; margin-bottom: 6px;"></i>
                  <span>{{ Str::limit($b->title, 25) }}</span>
                </div>
              @endif

              <div style="padding: 12px;">
                <div style="display: flex; align-items: center; gap: 4px; font-size: 0.72rem; margin-bottom: 4px;">
                  <span style="color: var(--star-rating);">★</span>
                  <span style="color: #8b949e;">{{ number_format($b->reviews_avg_rating ?? ($b->reviews ? $b->reviews->avg('rating') : 0), 1) }}</span>
                </div>
                <h4 style="color: white; font-size: 0.9rem; font-weight: 600; margin: 0 0 4px 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $b->title }}">{{ $b->title }}</h4>
                <p style="color: #8b949e; font-size: 0.75rem; margin: 0;">{{ $b->genre ?? 'Novel' }} &bull; {{ $b->author }}</p>
              </div>
            </div>

            <div style="padding: 0 12px 12px 12px;">
              <a href="{{ route('books.show', $b->id) }}" style="display: block; width: 100%; text-align: center; background-color: #21262d; color: #58a6ff; font-size: 0.78rem; font-weight: 600; padding: 7px 0; border-radius: 6px; text-decoration: none;">
                Lihat Ulasan
              </a>
            </div>
          </div>
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

                <div style="background: rgba(227, 179, 65, 0.12); border: 1px solid var(--star-rating); padding: 3px 10px; border-radius: 15px; color: var(--star-rating); font-weight: bold; font-size: 0.85rem;">
                  <i class="fa-solid fa-star"></i> {{ $rev->rating }}.0 / 5.0
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
