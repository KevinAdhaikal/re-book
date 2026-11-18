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
        <span style="font-size: 0.8rem; color: var(--text-muted);">
          Menampilkan {{ $books->total() }} judul dalam koleksi Re:Book
        </span>
      </div>

      {{-- Kontrol Sortir --}}
      <div style="display: flex; align-items: center; gap: 10px;">
        <span style="font-size: 0.8rem; color: var(--text-muted);">Urutkan:</span>
        <select onchange="window.location.href=this.value;" style="padding: 5px 10px; background: #161b22; border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; font-size: 0.82rem; outline: none; cursor: pointer;">
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
      <div style="display: flex; gap: 8px; overflow-x: auto; padding-bottom: 14px; margin-bottom: 20px;">
        @foreach($genreList as $g)
          @php
            $isGenreActive = ($g === 'Semua' && (!request('filter_type') || (request('filter_type') === 'all' && !request('search')))) || (request('filter_type') === 'genre' && strcasecmp(request('search'), $g) === 0);
            $genreUrl = $g === 'Semua' ? url('/') : url('/?filter_type=genre&search=' . urlencode($g));
          @endphp
          <a href="{{ $genreUrl }}" class="filter-pill {{ $isGenreActive ? 'active' : '' }}">
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
      <div class="book-grid">
        @php
          $userLikedIds = auth()->check() ? \Illuminate\Support\Facades\DB::table('book_likes')->where('user_id', auth()->id())->pluck('book_id')->toArray() : [];
        @endphp

        @forelse ($books as $b)
          @php
            $isLiked = in_array($b->id, $userLikedIds);
          @endphp
          <x-book-card :book="$b" :isLiked="$isLiked" />
        @empty
          <div style="grid-column: 1 / -1; text-align: center; padding: 50px 20px; background: var(--bg-card); border-radius: 8px; border: 1px solid var(--border-color);">
            <i class="fa-solid fa-magnifying-glass" style="font-size: 2rem; color: #30363d; margin-bottom: 10px;"></i>
            <h3 style="color: var(--text-main); font-size: 1rem; margin-bottom: 6px;">Buku Tidak Ditemukan</h3>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 16px;">
              Tidak ada buku yang cocok dengan pencarian "{{ request('search') }}".
            </p>
            <a href="{{ url('/') }}" style="background: var(--member-accent); color: white; padding: 7px 16px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: inline-block;">
              Kembali ke Semua Koleksi
            </a>
          </div>
        @endforelse
      </div>

      {{-- Pagination Links --}}
      @if ($books->hasPages())
        <div style="margin-top: 24px; display: flex; justify-content: center;">
          {{ $books->links() }}
        </div>
      @endif
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
            @if($review->comments->count() > 0)
              <div style="margin-bottom: 12px; padding-left: 10px; border-left: 2px solid var(--border-color); display: flex; flex-direction: column; gap: 8px;">
                @foreach($review->comments->take(2) as $comment)
                  <div style="font-size: 0.8rem; color: var(--text-muted); display: flex; align-items: center; justify-content: space-between;">
                    <div>
                      <a href="{{ $comment->user ? route('users.profile', $comment->user->id) : '#' }}" style="color: #79c0ff; font-weight: 600; text-decoration: none;">
                        {{ $comment->user->name ?? 'Member' }}
                      </a>: 
                      <span style="color: var(--text-main);">{{ $comment->comment }}</span>
                    </div>

                    @if(auth()->check() && (auth()->id() === $comment->user_id || auth()->user()->role === 'admin'))
                      <form action="{{ route('member.comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Hapus komentar Anda?');" style="margin: 0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" title="Hapus Komentar" style="background: none; border: none; color: #8b949e; font-size: 0.72rem; cursor: pointer; padding: 2px 4px;">
                          <i class="fa-solid fa-trash-can"></i>
                        </button>
                      </form>
                    @endif
                  </div>
                @endforeach
                @if($review->comments->count() > 2)
                  <a href="{{ route('books.show', $review->book_id) }}" style="font-size: 0.75rem; color: var(--text-muted); text-decoration: none;">
                    + {{ $review->comments->count() - 2 }} komentar lainnya di halaman buku &rarr;
                  </a>
                @endif
              </div>
            @endif

            {{-- Form Cepat Balas Komentar --}}
            @auth
              <form action="{{ route('member.comments.store', $review->id) }}" method="POST" style="display: flex; gap: 8px;">
                @csrf
                <input type="text" name="comment" placeholder="Tulis tanggapan untuk ulasan ini..." required
                  style="flex: 1; padding: 6px 12px; background: #0d1117; border: 1px solid var(--border-color); color: var(--text-main); border-radius: 4px; font-size: 0.82rem; outline: none;">
                <button type="submit" style="background: #21262d; border: 1px solid var(--border-color); color: var(--text-main); padding: 6px 14px; border-radius: 4px; font-weight: 600; font-size: 0.8rem; cursor: pointer; transition: background 0.15s ease;">
                  Balas
                </button>
              </form>
            @endauth

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
