@extends('layouts.app')

@push('styles')
<style>
  .hero-section {
    position: relative;
    background: radial-gradient(ellipse at top, rgba(31, 111, 235, 0.18) 0%, rgba(22, 27, 34, 0.95) 70%);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 56px 36px 44px 36px;
    margin-bottom: 45px;
    overflow: hidden;
    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.35);
  }
  .hero-glow-1 {
    position: absolute;
    top: -80px;
    right: 8%;
    width: 320px;
    height: 320px;
    background: rgba(88, 166, 255, 0.12);
    filter: blur(80px);
    border-radius: 50%;
    pointer-events: none;
  }
  .hero-glow-2 {
    position: absolute;
    bottom: -80px;
    left: 5%;
    width: 260px;
    height: 260px;
    background: rgba(188, 140, 255, 0.08);
    filter: blur(70px);
    border-radius: 50%;
    pointer-events: none;
  }
  .hero-headline {
    font-size: 2.6rem;
    line-height: 1.25;
    font-weight: 800;
    color: var(--text-main);
    margin-bottom: 16px;
    letter-spacing: -0.5px;
  }
  .book-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
    gap: 22px;
    margin-bottom: 50px;
  }
  .book-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    position: relative;
    transition: transform 0.22s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.22s ease, border-color 0.2s ease;
  }
  .book-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 14px 28px -6px rgba(0, 0, 0, 0.5);
    border-color: #388BFD !important;
  }
  .book-cover-wrap {
    position: relative;
    overflow: hidden;
    height: 260px;
    background: #12161c;
  }
  .book-cover-wrap::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    width: 8px;
    background: linear-gradient(to right, rgba(0,0,0,0.45) 0%, rgba(0,0,0,0.15) 60%, transparent 100%);
    pointer-events: none;
  }
  .book-cover-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.35s ease;
  }
  .book-card:hover .book-cover-img {
    transform: scale(1.04);
  }
  .genre-chip {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.8rem;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s;
    white-space: nowrap;
  }
  @media (max-width: 768px) {
    .hero-section {
      padding: 36px 20px !important;
      margin-bottom: 30px !important;
    }
    .hero-headline {
      font-size: 1.85rem !important;
    }
    .book-grid {
      grid-template-columns: repeat(2, 1fr) !important;
      gap: 14px !important;
    }
    .book-cover-wrap {
      height: 220px !important;
    }
  }
  @media (max-width: 480px) {
    .book-grid {
      grid-template-columns: 1fr !important;
    }
    .book-cover-wrap {
      height: 270px !important;
    }
  }
</style>
@endpush

@section('content')

  {{-- ================= HERO BANNER SECTION ================= --}}
  <div class="hero-section">
    <div class="hero-glow-1"></div>
    <div class="hero-glow-2"></div>

    <div style="position: relative; z-index: 2; max-width: 840px; margin: 0 auto; text-align: center;">
      
      {{-- Floating Top Badge --}}
      <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(31, 111, 235, 0.15); border: 1px solid rgba(88, 166, 255, 0.3); color: #58A6FF; font-size: 0.82rem; font-weight: 600; padding: 6px 16px; border-radius: 30px; margin-bottom: 22px; letter-spacing: 0.3px;">
        <i class="fa-solid fa-sparkles"></i>
        <span>Platform Komunitas Pembaca, Rating & Diskusi Buku</span>
      </div>

      {{-- Editorial Headline --}}
      <h1 class="hero-headline">
        Temukan Bacaan Terbaik, <br>
        <span class="font-serif" style="font-weight: 500; font-style: italic; background: linear-gradient(135deg, #79C0FF 0%, #D2A8FF 50%, #FFA657 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
          Catat Rak Bukumu
        </span> & Bagikan Ulasan
      </h1>

      {{-- Subtitle --}}
      <p style="font-size: 1.05rem; line-height: 1.6; color: var(--text-muted); max-width: 680px; margin: 0 auto 30px auto;">
        Jelajahi ulasan organik dari sesama pembaca, simpan koleksi buku di rak bacaan, pamerkan 4 buku favorit di profilmu, dan temukan inspirasi karya berikutnya.
      </p>

      {{-- Community Stats Counter Row --}}
      @php
        $totalBooksCount = \App\Models\Book::count();
        $totalReviewsCount = \App\Models\Review::count();
        $totalMembersCount = \App\Models\User::where('role', 'member')->count();
      @endphp
      <div style="display: flex; justify-content: center; align-items: center; gap: 14px; flex-wrap: wrap; margin-bottom: 32px;">
        <div style="background: rgba(22, 27, 34, 0.85); border: 1px solid var(--border-color); padding: 8px 18px; border-radius: 10px; display: flex; align-items: center; gap: 8px; font-size: 0.88rem; box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
          <i class="fa-solid fa-book-bookmark" style="color: #58A6FF;"></i>
          <span style="color: var(--text-main); font-weight: 700;">{{ $totalBooksCount }}+</span>
          <span style="color: var(--text-muted);">Buku Terkurasi</span>
        </div>

        <div style="background: rgba(22, 27, 34, 0.85); border: 1px solid var(--border-color); padding: 8px 18px; border-radius: 10px; display: flex; align-items: center; gap: 8px; font-size: 0.88rem; box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
          <i class="fa-solid fa-pen-nib" style="color: #E3B341;"></i>
          <span style="color: var(--text-main); font-weight: 700;">{{ $totalReviewsCount }}+</span>
          <span style="color: var(--text-muted);">Ulasan Komunitas</span>
        </div>

        <div style="background: rgba(22, 27, 34, 0.85); border: 1px solid var(--border-color); padding: 8px 18px; border-radius: 10px; display: flex; align-items: center; gap: 8px; font-size: 0.88rem; box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
          <i class="fa-solid fa-users" style="color: #3FB950;"></i>
          <span style="color: var(--text-main); font-weight: 700;">{{ $totalMembersCount }}+</span>
          <span style="color: var(--text-muted);">Pembaca Aktif</span>
        </div>
      </div>

      {{-- Action Buttons --}}
      <div style="display: flex; justify-content: center; align-items: center; gap: 14px; flex-wrap: wrap; margin-bottom: 30px;">
        <a href="#katalog-buku" style="background: var(--member-accent); color: #fff; padding: 12px 26px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 0.95rem; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(31, 111, 235, 0.4); transition: transform 0.15s ease, background 0.2s ease;">
          <i class="fa-solid fa-compass"></i> Jelajahi Katalog
        </a>
        @guest
          <a href="{{ route('register') }}" style="background: rgba(240, 246, 252, 0.08); border: 1px solid var(--border-color); color: var(--text-main); padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.95rem; display: inline-flex; align-items: center; gap: 8px; transition: background 0.2s ease;">
            <i class="fa-solid fa-user-plus"></i> Gabung Gratis
          </a>
        @else
          <a href="{{ route('member.shelf.index') }}" style="background: rgba(240, 246, 252, 0.08); border: 1px solid var(--border-color); color: var(--text-main); padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.95rem; display: inline-flex; align-items: center; gap: 8px; transition: background 0.2s ease;">
            <i class="fa-solid fa-book-open"></i> Rak Bacaan Saya
          </a>
        @endguest
      </div>

      {{-- Quick Genre Filter Chips --}}
      @php
        $genres = ['Semua', 'Fiksi', 'Manga', 'Novel', 'Sastra', 'Filsafat', 'Self-Dev', 'Fantasi', 'Komik'];
      @endphp
      <div style="display: flex; justify-content: center; align-items: center; gap: 8px; flex-wrap: wrap;">
        <span style="font-size: 0.8rem; color: var(--text-muted); margin-right: 4px;">Pilih Genre Cepat:</span>
        @foreach($genres as $g)
          @php
            $isGenreActive = ($g === 'Semua' && (!request('filter_type') || (request('filter_type') === 'all' && !request('search')))) || (request('filter_type') === 'genre' && strcasecmp(request('search'), $g) === 0);
            $genreUrl = $g === 'Semua' ? url('/') . '#katalog-buku' : url('/?filter_type=genre&search=' . urlencode($g) . '#katalog-buku');
          @endphp
          <a href="{{ $genreUrl }}" class="genre-chip" style="{{ $isGenreActive ? 'background: #1F6FEB; color: #fff; border: 1px solid #388BFD;' : 'background: rgba(255,255,255,0.05); color: var(--text-muted); border: 1px solid var(--border-color);' }}">
            {{ $g }}
          </a>
        @endforeach
      </div>

    </div>
  </div>

  {{-- ================= KATALOG BUKU SECTION ================= --}}
  <div id="katalog-buku" style="margin-bottom: 50px;">
    
    {{-- Section Header --}}
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 24px; flex-wrap: wrap; gap: 14px; border-bottom: 1px solid var(--border-color); padding-bottom: 14px;">
      <div>
        <div style="display: flex; align-items: center; gap: 10px;">
          <span style="display: inline-block; width: 4px; height: 24px; background: var(--member-accent); border-radius: 2px;"></span>
          <h2 style="font-size: 1.45rem; font-weight: 700; color: var(--text-main); margin: 0;">
            @if(request()->filled('search'))
              Hasil Pencarian: "{{ request('search') }}"
            @else
              Rekomendasi Buku Terpopuler
            @endif
          </h2>
        </div>
        <p style="color: var(--text-muted); font-size: 0.85rem; margin: 4px 0 0 14px;">
          Menampilkan {{ $books->total() }} buku pilihan komunitas Re:Book
        </p>
      </div>

      @if(request()->filled('search'))
        <a href="{{ url('/') }}" style="color: var(--member-accent); text-decoration: none; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
          <i class="fa-solid fa-arrow-rotate-left"></i> Reset Filter & Pencarian
        </a>
      @endif
    </div>

    {{-- Grid Buku --}}
    <div class="book-grid">
      @php
        $userLikedIds = auth()->check() ? \Illuminate\Support\Facades\DB::table('book_likes')->where('user_id', auth()->id())->pluck('book_id')->toArray() : [];
      @endphp

      @forelse ($books as $b)
        @php
          $isLiked = in_array($b->id, $userLikedIds);
        @endphp
        <div class="book-card">

          {{-- Tombol Love di Pojok Kanan Atas Cover --}}
          @auth
            <button type="button" class="btn-like-toggle" data-book-id="{{ $b->id }}" data-action="{{ route('member.likes.toggle', $b->id) }}" title="{{ $isLiked ? 'Batal Sukai' : 'Sukai Buku' }}"
              style="position: absolute; top: 10px; right: 10px; z-index: 10; background-color: rgba(13, 17, 23, 0.88); backdrop-filter: blur(4px); border: 1px solid #30363d; width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; color: {{ $isLiked ? '#F78166' : '#8B949E' }}; font-size: 0.95rem; padding: 0; transition: transform 0.15s ease, color 0.2s ease;">
              <i class="{{ $isLiked ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>
            </button>
          @else
            <a href="{{ route('login') }}" title="Login untuk menyukai buku"
              style="position: absolute; top: 10px; right: 10px; z-index: 10; background-color: rgba(13, 17, 23, 0.88); backdrop-filter: blur(4px); border: 1px solid #30363d; width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #8B949E; text-decoration: none; font-size: 0.95rem;">
              <i class="fa-regular fa-heart"></i>
            </a>
          @endauth

          {{-- Book Cover Container with Spine Crease --}}
          <div class="book-cover-wrap">
            @if($b->cover)
              <img
                src="{{ asset('storage/' . $b->cover) }}"
                alt="{{ $b->title }}"
                class="book-cover-img"
                onerror="this.onerror=null; this.parentElement.querySelector('.cover-fallback').style.display='flex'; this.style.display='none';">
              <div class="cover-fallback" style="display: none; width: 100%; height: 100%; background: #161b22; flex-direction: column; align-items: center; justify-content: center; gap: 8px; color: var(--text-muted); border-bottom: 1px solid var(--border-color); text-align: center; padding: 15px;">
                <i class="fa-solid fa-book" style="font-size: 2.2rem; color: #30363d;"></i>
                <span style="font-size: 0.8rem; font-weight: 500; color: #8b949e;">{{ Str::limit($b->title, 25) }}</span>
              </div>
            @else
              <div style="width: 100%; height: 100%; background: #161b22; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; color: var(--text-muted); border-bottom: 1px solid var(--border-color); text-align: center; padding: 15px;">
                <i class="fa-solid fa-book" style="font-size: 2.2rem; color: #30363d;"></i>
                <span style="font-size: 0.8rem; font-weight: 500; color: #8b949e;">{{ Str::limit($b->title, 25) }}</span>
              </div>
            @endif
          </div>

          {{-- Card Content --}}
          <div style="padding: 14px; display: flex; flex-direction: column; flex-grow: 1;">
            
            {{-- Meta Row: Rating & Likes --}}
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
              <div style="color: var(--star-rating); font-size: 0.88rem; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                <i class="fa-solid fa-star"></i> {{ number_format($b->reviews_avg_rating ?? 0, 1) }}
                <span style="color: var(--text-muted); font-size: 0.75rem; font-weight: normal;">({{ $b->reviews_count ?? $b->reviews->count() }})</span>
              </div>
              <div style="color: var(--text-muted); font-size: 0.78rem; display: inline-flex; align-items: center; gap: 4px;" title="Jumlah pembaca yang menyukai buku ini">
                <i class="fa-solid fa-heart" style="color: #F78166; font-size: 0.75rem;"></i>
                <span id="book-likes-count-{{ $b->id }}">{{ $b->liked_by_users_count ?? $b->likedByUsers->count() }}</span>
              </div>
            </div>

            {{-- Title --}}
            <h4 style="margin-bottom: 6px; font-size: 0.95rem; font-weight: 700; line-height: 1.35; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 2.6em;" title="{{ $b->title }}">
              {{ $b->title }}
            </h4>

            {{-- Author & Genre --}}
            <div style="margin-bottom: 14px;">
              <p style="color: var(--text-muted); font-size: 0.78rem; margin-bottom: 6px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                <i class="fa-solid fa-user-pen" style="font-size: 0.72rem; margin-right: 4px;"></i> {{ $b->author }}
              </p>
              <span style="display: inline-block; background: rgba(255, 255, 255, 0.05); border: 1px solid var(--border-color); color: #8B949E; font-size: 0.72rem; padding: 2px 8px; border-radius: 4px;">
                {{ $b->genre }}
              </span>
            </div>

            {{-- Action Link --}}
            <a href="{{ route('books.show', $b->id) }}"
              style="margin-top: auto; background: #21262D; color: #58A6FF; text-align: center; padding: 8px 10px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 600; border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: center; gap: 6px; transition: all 0.2s ease;">
              <span>Lihat Ulasan</span>
              <i class="fa-solid fa-arrow-right" style="font-size: 0.75rem;"></i>
            </a>

          </div>
        </div>
      @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 50px 20px; background: var(--bg-card); border-radius: 12px; border: 1px solid var(--border-color);">
          <i class="fa-solid fa-book-open" style="font-size: 2.5rem; color: #30363d; margin-bottom: 12px;"></i>
          <h3 style="color: var(--text-main); font-size: 1.1rem; margin-bottom: 6px;">Buku Tidak Ditemukan</h3>
          <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 18px;">
            Tidak ada buku yang cocok dengan kata kunci "{{ request('search') }}".
          </p>
          <a href="{{ url('/') }}" style="background: var(--member-accent); color: white; padding: 8px 18px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: inline-block;">
            Kembali ke Semua Buku
          </a>
        </div>
      @endforelse
    </div>

    {{-- Pagination Links --}}
    @if ($books->hasPages())
      <div style="margin-top: 30px; display: flex; justify-content: center;">
        {{ $books->links() }}
      </div>
    @endif

  </div>

  {{-- ================= SECTION ULASAN TERPOPULER & KOMENTAR ================= --}}
  <section style="padding: 60px 0 40px 0; background: var(--bg-main); border-top: 1px solid var(--border-color);">
    <div style="max-width: 1000px; margin: 0 auto;">

      <!-- Judul Section -->
      <div style="text-align: center; margin-bottom: 40px;">
        <span style="color: #58A6FF; font-size: 0.82rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Suara Komunitas</span>
        <h2 style="font-size: 1.85rem; color: var(--text-main); margin: 6px 0 10px 0;">Ulasan & Diskusi Pembaca Terpopuler</h2>
        <p style="color: var(--text-muted); font-size: 0.95rem;">Lihat apa kata komunitas tentang buku favorit mereka dan gabung dalam diskusi!</p>
      </div>

      <!-- Grid Ulasan -->
      <div style="display: flex; flex-direction: column; gap: 24px;">
        @forelse ($popularReviews ?? [] as $review)
          <div style="position: relative; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; padding: 24px; box-shadow: 0 4px 16px rgba(0,0,0,0.2); overflow: hidden;">
            
            {{-- Background Quote Icon Watermark --}}
            <i class="fa-solid fa-quote-left" style="position: absolute; right: 24px; top: 18px; font-size: 4rem; color: rgba(255, 255, 255, 0.02); pointer-events: none;"></i>

            <!-- Header Ulasan: Info User & Buku -->
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
              <div style="display: flex; align-items: center; gap: 12px;">
                <a href="{{ $review->user ? route('users.profile', $review->user->id) : '#' }}" style="text-decoration: none; display: flex; align-items: center; gap: 12px;">
                  <div style="width: 44px; height: 44px; background: var(--member-accent); color: #fff; border-radius: 50%; overflow: hidden; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.1rem; border: 1px solid var(--border-color); flex-shrink: 0;">
                    @if($review->user && $review->user->avatarUrl())
                      <img src="{{ $review->user->avatarUrl() }}" alt="{{ $review->user->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                      {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                    @endif
                  </div>
                  <div>
                    <h4 style="margin: 0; color: var(--text-main); font-size: 1rem; font-weight: 700;">
                      {{ $review->user->name ?? 'Pengguna' }}
                    </h4>
                    <span style="font-size: 0.85rem; color: var(--text-muted);">
                      Mengulas 
                      <a href="{{ $review->book ? route('books.show', $review->book->id) : '#' }}" style="color: #58A6FF; text-decoration: none; font-weight: 600;">
                        {{ $review->book->title ?? 'Buku' }}
                      </a>
                    </span>
                  </div>
                </a>
              </div>

              <!-- Rating Bintang -->
              <div style="background: rgba(227, 179, 65, 0.12); border: 1px solid rgba(227, 179, 65, 0.4); padding: 4px 12px; border-radius: 20px; color: var(--star-rating); font-weight: bold; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-star"></i> {{ $review->rating }}.0 / 5.0
              </div>
            </div>

            <!-- Isi Ulasan -->
            <p class="font-serif" style="color: var(--text-main); font-size: 1rem; line-height: 1.65; margin-bottom: 20px; background: rgba(255,255,255,0.02); padding: 14px 18px; border-radius: 8px; border-left: 3px solid var(--member-accent); font-style: italic;">
              "{{ $review->content }}"
            </p>

            <!-- Section Komentar Balasan -->
            <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--border-color);">
              <h5 style="color: var(--text-muted); margin-bottom: 12px; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">
                <i class="fa-regular fa-comments"></i> {{ $review->comments->count() }} Komentar Diskusi
              </h5>

              <!-- Daftar Balasan Komentar -->
              <div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 15px; padding-left: 12px; border-left: 2px solid var(--border-color);">
                @forelse ($review->comments as $comment)
                  <div style="background: rgba(255, 255, 255, 0.03); padding: 9px 14px; border-radius: 6px; display: flex; justify-content: space-between; align-items: flex-start; gap: 10px;">
                    <div style="display: flex; align-items: flex-start; gap: 10px; flex: 1;">
                      <a href="{{ $comment->user ? route('users.profile', $comment->user->id) : '#' }}" style="text-decoration: none; flex-shrink: 0;">
                        <div style="width: 26px; height: 26px; border-radius: 50%; overflow: hidden; background: var(--member-accent); color: white; display: flex; align-items: center; justify-content: center; font-size: 0.72rem; font-weight: bold; margin-top: 2px; border: 1px solid var(--border-color);">
                          @if($comment->user && $comment->user->avatarUrl())
                            <img src="{{ $comment->user->avatarUrl() }}" alt="{{ $comment->user->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                          @else
                            {{ strtoupper(substr($comment->user->name ?? 'M', 0, 1)) }}
                          @endif
                        </div>
                      </a>
                      <div>
                        <a href="{{ $comment->user ? route('users.profile', $comment->user->id) : '#' }}" style="font-weight: 600; font-size: 0.85rem; color: #58A6FF; text-decoration: none;">
                          {{ $comment->user->name ?? 'Member' }}
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
                  <p style="font-size: 0.85rem; color: var(--text-muted); font-style: italic; margin: 0;">Belum ada komentar balasan. Jadilah yang pertama membalas!</p>
                @endforelse
              </div>

              <!-- Form Kirim Komentar Balasan -->
              @auth
                <form action="{{ route('member.comments.store', $review->id) }}" method="POST" style="display: flex; gap: 10px; margin-top: 10px;">
                  @csrf
                  <input type="text" name="comment" placeholder="Tulis komentar balasan kamu..." required
                    style="flex: 1; padding: 9px 14px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; font-size: 0.88rem; outline: none;">
                  <button type="submit" style="background: var(--member-accent); color: #fff; border: none; padding: 9px 18px; border-radius: 6px; font-weight: 600; font-size: 0.85rem; cursor: pointer; transition: background 0.2s;">
                    Kirim
                  </button>
                </form>
              @else
                <p style="font-size: 0.82rem; color: var(--text-muted); margin: 0;">
                  <a href="{{ route('login') }}" style="color: #58A6FF; text-decoration: underline;">Login</a> untuk ikut membalas ulasan ini.
                </p>
              @endauth

            </div>

          </div>
        @empty
          <div style="text-align: center; padding: 40px; background: var(--bg-card); border-radius: 12px; border: 1px solid var(--border-color);">
            <p style="color: var(--text-muted); margin: 0;">Belum ada ulasan populer yang ditampilkan.</p>
          </div>
        @endforelse
      </div>

    </div>
  </section>

  {{-- ================= GUEST COMMUNITY CTA BANNER ================= --}}
  @guest
    <div style="background: linear-gradient(135deg, rgba(31, 111, 235, 0.15) 0%, rgba(22, 27, 34, 0.95) 100%); border: 1px solid var(--border-color); border-radius: 16px; padding: 40px 30px; max-width: 650px; margin: 40px auto 20px auto; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
      <div style="width: 50px; height: 50px; background: rgba(31, 111, 235, 0.2); border: 1px solid rgba(88, 166, 255, 0.4); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto; color: #58A6FF; font-size: 1.3rem;">
        <i class="fa-solid fa-book-reader"></i>
      </div>
      <h3 style="font-size: 1.4rem; font-weight: 700; color: var(--text-main); margin-bottom: 8px;">Gabung Komunitas Re:Book</h3>
      <p style="color: var(--text-muted); margin: 0 0 24px 0; font-size: 0.95rem; line-height: 1.5;">
        Buat akun gratis untuk mengulas buku, mengatur rak bacaan pribadi, dan pamerkan 4 buku favoritmu kepada ribuan sesama pembaca!
      </p>
      <div style="display: flex; justify-content: center; gap: 12px; flex-wrap: wrap;">
        <a href="{{ route('register') }}" style="background: var(--member-accent); color: #fff; padding: 11px 26px; text-decoration: none; border-radius: 8px; font-weight: 700; font-size: 0.95rem; box-shadow: 0 4px 12px rgba(31, 111, 235, 0.35);">
          Daftar Sekarang — Gratis
        </a>
        <a href="{{ route('login') }}" style="background: rgba(255,255,255,0.06); border: 1px solid var(--border-color); color: var(--text-main); padding: 11px 22px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 0.95rem;">
          Sudah Punya Akun? Masuk
        </a>
      </div>
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
