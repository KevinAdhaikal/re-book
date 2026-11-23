@extends('layouts.app')

@push('styles')
<style>
  .trending-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 16px;
    margin-bottom: 40px;
  }
  .trending-poster-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    position: relative;
    transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.2s ease, border-color 0.2s ease;
  }
  .trending-poster-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 24px -6px rgba(0, 0, 0, 0.6);
    border-color: var(--member-accent) !important;
  }
  .trending-cover-wrap {
    position: relative;
    height: 220px;
    overflow: hidden;
    background: #12161c;
  }
  .trending-cover-wrap::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    width: 6px;
    background: linear-gradient(to right, rgba(0,0,0,0.5) 0%, transparent 100%);
    pointer-events: none;
  }
  .trending-cover-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.3s ease;
  }
  .trending-poster-card:hover .trending-cover-img {
    transform: scale(1.05);
  }
  .book-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 20px;
    margin-bottom: 45px;
  }
  .filter-pill {
    padding: 5px 13px;
    border-radius: 20px;
    font-size: 0.8rem;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.15s ease;
    white-space: nowrap;
    border: 1px solid var(--border-color);
  }
  .filter-pill.active {
    background: var(--member-accent);
    color: #ffffff !important;
    border-color: var(--member-accent);
  }
  .filter-pill:not(.active) {
    background: #161b22;
    color: var(--text-muted);
  }
  .filter-pill:not(.active):hover {
    background: #21262d;
    color: var(--text-main);
  }
  .review-stream-item {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    padding: 20px;
    display: flex;
    gap: 18px;
    align-items: flex-start;
    transition: border-color 0.15s ease;
  }
  .review-stream-item:hover {
    border-color: #388bfd;
  }
  .review-book-poster {
    width: 65px;
    height: 95px;
    border-radius: 4px;
    overflow: hidden;
    flex-shrink: 0;
    border: 1px solid var(--border-color);
    background: #12161c;
    position: relative;
    box-shadow: 0 4px 8px rgba(0,0,0,0.3);
  }
  .review-book-poster::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    width: 4px;
    background: linear-gradient(to right, rgba(0,0,0,0.5), transparent);
    pointer-events: none;
  }
  .review-book-poster img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }

  /* Animasi Komentar Beranda */
  @keyframes commentSlideIn {
    from {
      opacity: 0;
      transform: translateY(-8px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  .comment-item-entering {
    animation: commentSlideIn 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
  }
  .comment-item-leaving {
    opacity: 0 !important;
    transform: translateX(-15px) !important;
    max-height: 0 !important;
    padding-top: 0 !important;
    padding-bottom: 0 !important;
    margin-top: 0 !important;
    margin-bottom: 0 !important;
    overflow: hidden !important;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
  }
  #book-catalog-container {
    transition: opacity 0.2s ease;
  }
  #book-catalog-container.is-loading {
    opacity: 0.35;
    pointer-events: none;
  }

  @media (max-width: 1024px) {
    .trending-grid {
      grid-template-columns: repeat(3, 1fr);
    }
  }
  @media (max-width: 768px) {
    .trending-grid {
      grid-template-columns: repeat(3, 1fr);
      gap: 10px;
    }
    .trending-cover-wrap {
      height: 170px;
    }
    .book-grid {
      grid-template-columns: repeat(2, 1fr);
      gap: 14px;
    }
    .review-stream-item {
      flex-direction: column;
      gap: 12px;
    }
    .review-book-poster {
      width: 50px;
      height: 75px;
    }
  }
  @media (max-width: 480px) {
    .trending-grid {
      grid-template-columns: repeat(2, 1fr);
    }
    .book-grid {
      grid-template-columns: 1fr;
    }
  }
</style>
@endpush

@section('content')

  {{-- ================= 1. SHOWCASE TRENDING POPULER ALA LETTERBOXD ================= --}}
  @if(!request()->filled('search') && isset($trendingBooks) && $trendingBooks->isNotEmpty())
    <section style="margin-bottom: 35px;">
      <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px; flex-wrap: wrap; gap: 8px;">
        <div>
          <h2 style="font-size: 1.05rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: var(--text-main); margin: 0; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-fire" style="color: #F78166;"></i> Populer di Re:Book
          </h2>
          <span style="font-size: 0.8rem; color: var(--text-muted);">Buku dengan kombinasi rating tertinggi dan pembaca terbanyak</span>
        </div>
        <span style="font-size: 0.78rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">6 Pilihan Komunitas</span>
      </div>

      <div class="trending-grid">
        @foreach($trendingBooks as $tb)
          <div class="trending-poster-card">
            {{-- Cover Poster --}}
            <a href="{{ route('books.show', $tb->id) }}" class="trending-cover-wrap" style="text-decoration: none; display: block;">
              @if($tb->cover)
                <img src="{{ asset('storage/' . $tb->cover) }}" alt="{{ $tb->title }}" class="trending-cover-img" onerror="this.onerror=null; this.parentElement.querySelector('.t-fallback').style.display='flex'; this.style.display='none';">
                <div class="t-fallback" style="display: none; width: 100%; height: 100%; background: #161b22; align-items: center; justify-content: center; color: var(--text-muted); font-size: 1.8rem;">
                  <i class="fa-solid fa-book"></i>
                </div>
              @else
                <div style="width: 100%; height: 100%; background: #161b22; display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 1.8rem;">
                  <i class="fa-solid fa-book"></i>
                </div>
              @endif
            </a>

            {{-- Metadata di Bawah Poster --}}
            <div style="padding: 10px 12px; display: flex; flex-direction: column; flex-grow: 1;">
              <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; font-size: 0.78rem;">
                <span style="color: var(--star-rating); font-weight: 700;">
                  <i class="fa-solid fa-star"></i> {{ number_format($tb->reviews_avg_rating ?? 0, 1) }}
                </span>
                <span style="color: var(--text-muted);" title="Disukai {{ $tb->liked_by_users_count }} pembaca">
                  <i class="fa-solid fa-heart" style="color: #F78166; font-size: 0.7rem;"></i> {{ $tb->liked_by_users_count }}
                </span>
              </div>
              <h4 style="font-size: 0.88rem; font-weight: 700; margin: 0 0 3px 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $tb->title }}">
                <a href="{{ route('books.show', $tb->id) }}" style="color: var(--text-main); text-decoration: none;">
                  {{ $tb->title }}
                </a>
              </h4>
              <p style="font-size: 0.75rem; color: var(--text-muted); margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                {{ $tb->author }}
              </p>
            </div>
          </div>
        @endforeach
      </div>
    </section>
  @endif

  {{-- ================= 2. KATALOG BUKU & KONTROL FILTER ================= --}}
  <section id="katalog-buku" style="margin-bottom: 45px;">

    {{-- Filter Header Bar --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; flex-wrap: wrap; gap: 14px; border-bottom: 1px solid var(--border-color); padding-bottom: 14px;">
      <div>
        <h2 style="font-size: 1.15rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: var(--text-main); margin: 0; display: flex; align-items: center; gap: 8px;">
          <i class="fa-solid fa-book" style="color: var(--member-accent);"></i>
          @if(request()->filled('search'))
            Hasil Pencarian: "{{ request('search') }}"
          @else
            Katalog Buku
          @endif
        </h2>
        <span id="catalog-total-count" style="font-size: 0.8rem; color: var(--text-muted);">
          Menampilkan {{ $books->total() }} judul dalam koleksi Re:Book
        </span>
      </div>

      {{-- Kontrol Sortir --}}
      <div style="display: flex; align-items: center; gap: 10px;">
        <span style="font-size: 0.8rem; color: var(--text-muted);">Urutkan:</span>
        <select id="catalog-sort-select" style="padding: 5px 10px; background: #161b22; border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; font-size: 0.82rem; outline: none; cursor: pointer;">
          @php
            $currentSort = request('sort', 'title');
            $baseParams = request()->except('sort');
          @endphp
          <option value="{{ request()->fullUrlWithQuery(['sort' => 'title']) }}" {{ $currentSort === 'title' ? 'selected' : '' }}>Judul (A-Z)</option>
          <option value="{{ request()->fullUrlWithQuery(['sort' => 'popular']) }}" {{ $currentSort === 'popular' ? 'selected' : '' }}>Paling Populer (Likes)</option>
          <option value="{{ request()->fullUrlWithQuery(['sort' => 'rating']) }}" {{ $currentSort === 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
        </select>

        @if(request()->filled('search') || request()->filled('filter_type') && request('filter_type') !== 'all')
          <a href="{{ url('/') }}" style="color: #F78166; font-size: 0.82rem; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; margin-left: 6px;">
            <i class="fa-solid fa-xmark"></i> Reset
          </a>
        @endif
      </div>
    </div>

    {{-- Genre Filter Tabs (hanya jika tidak mencari spesifik) --}}
    @if(!request()->filled('search'))
      @php
        $genreList = ['Semua', 'Fiksi', 'Manga', 'Novel', 'Sastra', 'Filsafat', 'Self-Dev', 'Fantasi', 'Komik'];
      @endphp
      <div id="genre-filter-tabs" style="display: flex; gap: 8px; overflow-x: auto; padding-bottom: 14px; margin-bottom: 20px;">
        @foreach($genreList as $g)
          @php
            $isGenreActive = ($g === 'Semua' && (!request('filter_type') || (request('filter_type') === 'all' && !request('search')))) || (request('filter_type') === 'genre' && strcasecmp(request('search'), $g) === 0);
            $genreUrl = $g === 'Semua' ? url('/') : url('/?filter_type=genre&search=' . urlencode($g));
          @endphp
          <a href="{{ $genreUrl }}" class="filter-pill genre-filter-pill {{ $isGenreActive ? 'active' : '' }}" data-genre="{{ $g }}" data-url="{{ $genreUrl }}">
            {{ $g }}
          </a>
        @endforeach
      </div>
    @else
      {{-- Tab Hasil Pencarian Multi-Entity (Buku vs Member) --}}
      @php
        $activeSearchTab = request('tab', 'books');
      @endphp
      <div style="display: flex; gap: 10px; margin-bottom: 22px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">
        <a href="{{ request()->fullUrlWithQuery(['tab' => 'books']) }}" class="filter-pill {{ $activeSearchTab === 'books' ? 'active' : '' }}" style="display: inline-flex; align-items: center; gap: 6px;">
          <i class="fa-solid fa-book"></i> Buku ({{ $books->total() }})
        </a>
        <a href="{{ request()->fullUrlWithQuery(['tab' => 'users']) }}" class="filter-pill {{ $activeSearchTab === 'users' ? 'active' : '' }}" style="display: inline-flex; align-items: center; gap: 6px;">
          <i class="fa-solid fa-users"></i> Member Komunitas ({{ $matchedUsers ? $matchedUsers->total() : 0 }})
        </a>
      </div>
    @endif

    @if(request()->filled('search') && request('tab') === 'users')
      {{-- Grid Hasil Pencarian Member Komunitas --}}
      <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px; margin-bottom: 40px;">
        @forelse($matchedUsers ?? [] as $user)
          <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 18px; display: flex; gap: 14px; align-items: center; transition: border-color 0.15s ease;">
            <a href="{{ route('users.profile', $user->id) }}" style="text-decoration: none;">
              <x-user-avatar :user="$user" size="lg" />
            </a>
            <div style="flex: 1; min-width: 0;">
              <h4 style="margin: 0 0 3px 0; font-size: 0.95rem; font-weight: 700;">
                <a href="{{ route('users.profile', $user->id) }}" style="color: var(--text-main); text-decoration: none;">
                  {{ $user->name }}
                </a>
              </h4>
              <p style="color: var(--text-muted); font-size: 0.78rem; margin: 0 0 6px 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                {{ $user->bio ?: 'Member Komunitas Re:Book' }}
              </p>
              <div style="font-size: 0.72rem; color: #8b949e; display: flex; gap: 10px;">
                <span><i class="fa-solid fa-book-bookmark"></i> {{ $user->readingShelves()->count() }} buku</span>
                <span><i class="fa-solid fa-pen"></i> {{ $user->reviews()->count() }} ulasan</span>
              </div>
            </div>
            <a href="{{ route('users.profile', $user->id) }}" style="background: #21262d; border: 1px solid var(--border-color); color: #58a6ff; font-size: 0.75rem; font-weight: 600; padding: 6px 12px; border-radius: 6px; text-decoration: none;">
              Profil
            </a>
          </div>
        @empty
          <div style="grid-column: 1 / -1; text-align: center; padding: 45px 20px; background: var(--bg-card); border-radius: 8px; border: 1px solid var(--border-color);">
            <i class="fa-solid fa-user-xmark" style="font-size: 2rem; color: #30363d; margin-bottom: 8px;"></i>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin: 0;">Tidak ada member ditemukan dengan kata kunci "{{ request('search') }}".</p>
          </div>
        @endforelse
      </div>

      @if($matchedUsers && $matchedUsers->hasPages())
        <div style="margin-top: 20px; display: flex; justify-content: center;">
          {{ $matchedUsers->links() }}
        </div>
      @endif
    @else
      {{-- Grid Buku --}}
      <div id="book-catalog-container">
        @include('partials.book_grid', ['books' => $books])
      </div>
    @endif

  </section>

  {{-- ================= 3. FEED ULASAN KOMUNITAS ALA LETTERBOXD ================= --}}
  <section style="border-top: 1px solid var(--border-color); padding-top: 35px; margin-bottom: 40px;">
    
    <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px; flex-wrap: wrap; gap: 8px;">
      <div>
        <h2 style="font-size: 1.05rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: var(--text-main); margin: 0; display: flex; align-items: center; gap: 8px;">
          <i class="fa-solid fa-comment-dots" style="color: var(--member-accent);"></i> Ulasan Baru Komunitas
        </h2>
        <span style="font-size: 0.8rem; color: var(--text-muted);">Pendapat dan ulasan jujur dari sesama pembaca di Re:Book</span>
      </div>
      <span style="font-size: 0.78rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Aktivitas Pembaca</span>
    </div>

    <div style="display: flex; flex-direction: column; gap: 16px;">
      @forelse ($popularReviews ?? [] as $review)
        <div class="review-stream-item">

          {{-- Mini Poster Cover Buku --}}
          <a href="{{ $review->book ? route('books.show', $review->book->id) : '#' }}" class="review-book-poster">
            @if($review->book && $review->book->cover)
              <img src="{{ asset('storage/' . $review->book->cover) }}" alt="{{ $review->book->title }}">
            @else
              <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 1.2rem;">
                <i class="fa-solid fa-book"></i>
              </div>
            @endif
          </a>

          {{-- Konten Ulasan --}}
          <div style="flex: 1; min-width: 0;">
            
            {{-- Header Ulasan: User, Rating & Buku --}}
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; flex-wrap: wrap; gap: 8px;">
              <div style="display: flex; align-items: center; gap: 10px;">
                <a href="{{ $review->user ? route('users.profile', $review->user->id) : '#' }}" style="text-decoration: none;">
                  <x-user-avatar :user="$review->user" size="sm" />
                </a>
                <div>
                  <div style="font-size: 0.88rem; line-height: 1.3;">
                    <a href="{{ $review->user ? route('users.profile', $review->user->id) : '#' }}" style="color: var(--text-main); font-weight: 700; text-decoration: none;">
                      {{ $review->user->name ?? 'Pengguna' }}
                    </a>
                    <span style="color: var(--text-muted); font-size: 0.8rem;">mengulas</span>
                    <a href="{{ $review->book ? route('books.show', $review->book->id) : '#' }}" style="color: #79c0ff; font-weight: 600; text-decoration: none;">
                      {{ $review->book->title ?? 'Buku' }}
                    </a>
                  </div>
                  <span style="font-size: 0.75rem; color: var(--text-muted);">
                    {{ $review->created_at ? $review->created_at->diffForHumans() : '' }}
                  </span>
                </div>
              </div>

              {{-- Rating Bintang --}}
              <div>
                <x-star-rating :rating="$review->rating" size="sm" />
              </div>
            </div>

            {{-- Teks Ulasan --}}
            <p style="color: var(--text-main); font-size: 0.92rem; line-height: 1.6; margin: 0 0 14px 0; background: rgba(0,0,0,0.2); padding: 12px 14px; border-radius: 6px; border-left: 3px solid var(--member-accent);">
              "{{ $review->content }}"
            </p>

            {{-- Thread Balasan Komentar --}}
            <div id="landing-comments-wrap-{{ $review->id }}" style="margin-bottom: 12px; padding-left: 10px; border-left: 2px solid var(--border-color); display: flex; flex-direction: column; gap: 8px;">
              {{-- Form Cepat Balas Komentar di Atas --}}
              @auth
                <form class="form-comment-landing-ajax" action="{{ route('member.comments.store', $review->id) }}" method="POST" data-review-id="{{ $review->id }}" style="display: flex; gap: 8px; margin-bottom: 4px;">
                  @csrf
                  <input type="text" name="comment" placeholder="Tulis tanggapan untuk ulasan ini..." required
                    style="flex: 1; padding: 6px 12px; background: #0d1117; border: 1px solid var(--border-color); color: var(--text-main); border-radius: 4px; font-size: 0.82rem; outline: none;">
                  <button type="submit" class="btn-submit-comment" style="background: #21262d; border: 1px solid var(--border-color); color: var(--text-main); padding: 6px 14px; border-radius: 4px; font-weight: 600; font-size: 0.8rem; cursor: pointer; transition: background 0.15s ease; display: inline-flex; align-items: center; gap: 6px;">
                    <span class="btn-text">Balas</span>
                  </button>
                </form>
              @endauth

              <div id="landing-comments-list-{{ $review->id }}" style="display: flex; flex-direction: column; gap: 8px;">
                @foreach($review->comments->take(2) as $comment)
                  <div id="landing-comment-item-{{ $comment->id }}" class="landing-comment-item" style="font-size: 0.8rem; color: var(--text-muted); display: flex; align-items: center; justify-content: space-between; transition: all 0.25s ease;">
                    <div>
                      <a href="{{ $comment->user ? route('users.profile', $comment->user->id) : '#' }}" style="color: #79c0ff; font-weight: 600; text-decoration: none;">
                        {{ $comment->user->name ?? 'Member' }}
                      </a>: 
                      <span style="color: var(--text-main);">{{ $comment->comment }}</span>
                    </div>

                    @if(auth()->check() && (auth()->id() === $comment->user_id || auth()->user()->role === 'admin'))
                      <button type="button" class="btn-delete-comment-ajax" data-id="{{ $comment->id }}" data-url="{{ route('member.comments.destroy', $comment->id) }}" data-review-id="{{ $review->id }}" title="Hapus Komentar" style="background: none; border: none; color: #8b949e; font-size: 0.72rem; cursor: pointer; padding: 2px 4px; transition: color 0.15s ease;" onmouseover="this.style.color='#f85149'" onmouseout="this.style.color='#8b949e'">
                        <i class="fa-solid fa-trash-can"></i>
                      </button>
                    @endif
                  </div>
                @endforeach
              </div>
              @if($review->comments->count() > 2)
                <a href="{{ route('books.show', $review->book_id) }}" style="font-size: 0.75rem; color: var(--text-muted); text-decoration: none;">
                  + {{ $review->comments->count() - 2 }} komentar lainnya di halaman buku &rarr;
                </a>
              @endif
            </div>

          </div>

        </div>
      @empty
        <div style="text-align: center; padding: 35px; background: var(--bg-card); border-radius: 8px; border: 1px solid var(--border-color);">
          <p style="color: var(--text-muted); font-size: 0.9rem; margin: 0;">Belum ada ulasan komunitas.</p>
        </div>
      @endforelse
    </div>

  </section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const csrfToken = '{{ csrf_token() }}';

  // ==========================================
  // 1. REUSABLE LIKE BUTTON HANDLERS
  // ==========================================
  function initLikeButtons(context = document) {
    context.querySelectorAll('.btn-like-toggle').forEach(button => {
      if (button.dataset.likeBound === 'true') return;
      button.dataset.likeBound = 'true';

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
  }

  initLikeButtons(document);

  // ==========================================
  // 2. AJAX BALAS KOMENTAR DI BERANDA
  // ==========================================
  document.querySelectorAll('.form-comment-landing-ajax').forEach(form => {
    form.addEventListener('submit', async function (e) {
      e.preventDefault();
      const reviewId = this.dataset.reviewId;
      const inputEl = this.querySelector('input[name="comment"]');
      const submitBtn = this.querySelector('.btn-submit-comment');
      const btnText = this.querySelector('.btn-text');
      const commentText = inputEl ? inputEl.value.trim() : '';

      if (!commentText) return;

      submitBtn.disabled = true;
      submitBtn.style.opacity = '0.6';
      if (btnText) btnText.textContent = '...';

      try {
        const formData = new FormData(this);
        const res = await fetch(this.action, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          },
          body: formData
        });

        const data = await res.json();

        if (res.ok && data.success) {
          window.showToast({ title: 'Terkirim', message: data.message, type: 'success' });
          inputEl.value = '';

          const wrapEl = document.getElementById(`landing-comments-wrap-${reviewId}`);
          const listEl = document.getElementById(`landing-comments-list-${reviewId}`);

          if (wrapEl) wrapEl.style.display = 'flex';

          if (listEl) {
            const newComment = document.createElement('div');
            newComment.id = `landing-comment-item-${data.comment.id}`;
            newComment.className = 'landing-comment-item comment-item-entering';
            newComment.style.cssText = 'font-size: 0.8rem; color: var(--text-muted); display: flex; align-items: center; justify-content: space-between; transition: all 0.25s ease;';
            newComment.innerHTML = `
              <div>
                <a href="${data.comment.user_profile_url}" style="color: #79c0ff; font-weight: 600; text-decoration: none;">
                  ${data.comment.user_name}
                </a>: 
                <span style="color: var(--text-main);">${data.comment.comment}</span>
              </div>
              <button type="button" class="btn-delete-comment-ajax" data-id="${data.comment.id}" data-url="/member/comments/${data.comment.id}" data-review-id="${reviewId}" title="Hapus Komentar" style="background: none; border: none; color: #8b949e; font-size: 0.72rem; cursor: pointer; padding: 2px 4px; transition: color 0.15s ease;" onmouseover="this.style.color='#f85149'" onmouseout="this.style.color='#8b949e'">
                <i class="fa-solid fa-trash-can"></i>
              </button>
            `;
            listEl.prepend(newComment);
          }
        } else {
          throw new Error(data.message || 'Gagal mengirim tanggapan.');
        }
      } catch (err) {
        window.showToast({ title: 'Gagal', message: err.message, type: 'error' });
      } finally {
        submitBtn.disabled = false;
        submitBtn.style.opacity = '1';
        if (btnText) btnText.textContent = 'Balas';
      }
    });
  });

  // ==========================================
  // 3. 1-KLIK HAPUS KOMENTAR INSTAN (BERANDA)
  // ==========================================
  document.addEventListener('click', async function (e) {
    const btn = e.target.closest('.btn-delete-comment-ajax');
    if (!btn) return;
    e.preventDefault();

    const commentId = btn.dataset.id;
    const url = btn.dataset.url;
    const commentItem = document.getElementById(`landing-comment-item-${commentId}`);

    btn.disabled = true;
    if (commentItem) {
      commentItem.classList.add('comment-item-leaving');
    }

    try {
      const res = await fetch(url, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ _method: 'DELETE' })
      });
      const data = await res.json();
      if (res.ok && data.success) {
        window.showToast({ title: 'Dihapus', message: data.message, type: 'info' });
        setTimeout(() => {
          if (commentItem) commentItem.remove();
        }, 250);
      } else {
        throw new Error(data.message || 'Gagal menghapus komentar.');
      }
    } catch (err) {
      if (commentItem) commentItem.classList.remove('comment-item-leaving');
      btn.disabled = false;
      window.showToast({ title: 'Gagal', message: err.message, type: 'error' });
    }
  });

  // ==========================================
  // 4. INSTANT KATALOG FILTER & SORTIR (AJAX + HISTORY)
  // ==========================================
  const catalogContainer = document.getElementById('book-catalog-container');
  const catalogTotalCount = document.getElementById('catalog-total-count');
  const sortSelect = document.getElementById('catalog-sort-select');

  async function loadCatalog(targetUrl, pushState = true) {
    if (!catalogContainer) return;

    catalogContainer.classList.add('is-loading');

    try {
      const separator = targetUrl.includes('?') ? '&' : '?';
      const ajaxUrl = targetUrl + separator + 'ajax=1';

      const res = await fetch(ajaxUrl, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        }
      });

      const data = await res.json();

      if (data && data.success) {
        catalogContainer.innerHTML = data.html;
        if (catalogTotalCount && typeof data.total !== 'undefined') {
          catalogTotalCount.textContent = `Menampilkan ${data.total} judul dalam koleksi Re:Book`;
        }

        initLikeButtons(catalogContainer);

        if (pushState) {
          window.history.pushState({ url: targetUrl }, '', targetUrl);
        }
      }
    } catch (err) {
      console.error('Catalog AJAX load error:', err);
      // Fallback reload jika terjadi kesalahan koneksi
      window.location.href = targetUrl;
    } finally {
      catalogContainer.classList.remove('is-loading');
    }
  }

  // Klik Filter Genre Pill
  document.querySelectorAll('.genre-filter-pill').forEach(pill => {
    pill.addEventListener('click', function (e) {
      e.preventDefault();
      const targetUrl = this.dataset.url || this.href;

      document.querySelectorAll('.genre-filter-pill').forEach(p => p.classList.remove('active'));
      this.classList.add('active');

      loadCatalog(targetUrl);
    });
  });

  // Ganti Pilihan Sortir
  if (sortSelect) {
    sortSelect.addEventListener('change', function () {
      loadCatalog(this.value);
    });
  }

  // Delegasi Klik Link Pagination
  document.addEventListener('click', function (e) {
    const pageLink = e.target.closest('.catalog-pagination-wrap a');
    if (!pageLink) return;

    e.preventDefault();
    loadCatalog(pageLink.href);

    const catalogSection = document.getElementById('katalog-buku');
    if (catalogSection) {
      catalogSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  });

  // Handle Browser Back / Forward Button
  window.addEventListener('popstate', function () {
    loadCatalog(window.location.href, false);
  });
});
</script>
@endpush
