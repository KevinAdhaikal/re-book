@extends('layouts.app')

@section('title', ($search !== '' ? 'Hasil Pencarian: ' . $search : 'Eksplorasi Katalog & Komunitas') . ' - Re:Book')

@push('styles')
<style>
  .search-container {
    max-width: 1200px;
    margin: 0 auto;
  }
  
  /* Filter & Facet Bar */
  .search-filter-bar {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    padding: 14px 20px;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 28px;
  }
  .filter-select {
    background: #0d1117;
    border: 1px solid var(--border-color);
    color: var(--text-main);
    padding: 7px 12px;
    border-radius: 6px;
    font-size: 0.85rem;
    outline: none;
    cursor: pointer;
    transition: border-color 0.15s ease;
  }
  .filter-select:focus {
    border-color: var(--member-accent);
  }
  
  /* Entity Switcher Tabs */
  .search-tabs {
    display: flex;
    gap: 8px;
    border-bottom: 1px solid var(--border-color);
    margin-bottom: 20px;
  }
  .search-tab-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    text-decoration: none;
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--text-muted);
    border-bottom: 2px solid transparent;
    transition: all 0.15s ease;
  }
  .search-tab-link:hover {
    color: var(--text-main);
  }
  .search-tab-link.active {
    color: #58a6ff;
    border-bottom-color: var(--member-accent);
  }
  .search-tab-badge {
    background: #21262d;
    color: var(--text-muted);
    font-size: 0.75rem;
    padding: 2px 7px;
    border-radius: 10px;
    font-weight: 500;
  }
  .search-tab-link.active .search-tab-badge {
    background: rgba(31, 111, 235, 0.2);
    color: #79c0ff;
  }

  /* View Mode Toggle */
  .view-toggle-btn {
    background: #0d1117;
    border: 1px solid var(--border-color);
    color: var(--text-muted);
    padding: 6px 10px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.85rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.15s ease;
  }
  .view-toggle-btn:hover {
    color: var(--text-main);
    border-color: #8b949e;
  }
  .view-toggle-btn.active {
    background: var(--member-accent);
    border-color: var(--member-accent);
    color: white;
  }

  /* List View Book Item (Letterboxd Style) */
  .search-book-row {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 16px 20px;
    display: flex;
    gap: 20px;
    align-items: flex-start;
    margin-bottom: 14px;
    transition: border-color 0.2s ease, transform 0.15s ease;
  }
  .search-book-row:hover {
    border-color: #388bfd;
    transform: translateX(3px);
  }
  .search-book-thumb {
    width: 75px;
    height: 110px;
    border-radius: 6px;
    overflow: hidden;
    flex-shrink: 0;
    border: 1px solid var(--border-color);
    background: #12161c;
    position: relative;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
  }
  .search-book-thumb::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    width: 6px;
    background: linear-gradient(to right, rgba(0,0,0,0.5) 0%, transparent 100%);
    pointer-events: none;
  }
  .search-book-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }

  /* Grid View */
  .search-book-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
  }

  /* Member Card Grid */
  .search-member-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 16px;
    margin-bottom: 30px;
  }
  .search-member-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 16px 20px;
    display: flex;
    gap: 16px;
    align-items: center;
    text-decoration: none;
    transition: border-color 0.2s ease, transform 0.15s ease;
  }
  .search-member-card:hover {
    border-color: #388bfd;
    transform: translateY(-2px);
  }

  /* Keyword Highlight Tag */
  .search-hl {
    background: rgba(31, 111, 235, 0.28);
    color: #79c0ff;
    padding: 0 3px;
    border-radius: 3px;
    font-weight: 600;
  }

  @media (max-width: 768px) {
    .search-filter-bar {
      flex-direction: column;
      align-items: stretch;
    }
    .search-book-row {
      flex-direction: column;
      gap: 14px;
    }
    .search-book-thumb {
      width: 60px;
      height: 90px;
    }
    .search-book-grid {
      grid-template-columns: repeat(2, 1fr);
      gap: 12px;
    }
    .search-member-grid {
      grid-template-columns: 1fr;
    }
  }
</style>
@endpush

@section('content')
@php
  // Safe keyword highlighter function
  $highlight = function($text, $query) {
      if (empty($query) || empty($text)) return e($text);
      $words = array_filter(explode(' ', trim($query)));
      if (empty($words)) return e($text);
      $escapedWords = array_map(function($w) {
          return preg_quote($w, '/');
      }, $words);
      $pattern = '/(' . implode('|', $escapedWords) . ')/i';
      return preg_replace($pattern, '<mark class="search-hl">$1</mark>', e($text));
  };
@endphp

<div class="search-container">
  
  {{-- Header & Dedicated Search Input --}}
  <div style="margin-bottom: 24px;">
    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-end; gap: 16px; margin-bottom: 16px;">
      <div>
        <h1 style="font-size: 1.5rem; font-weight: 800; color: var(--text-main); margin-bottom: 6px;">
          @if($search !== '')
            Hasil Pencarian untuk <span style="color: #58a6ff;">&ldquo;{{ $search }}&rdquo;</span>
          @else
            Eksplorasi Katalog & Komunitas
          @endif
        </h1>
        <p style="color: var(--text-muted); font-size: 0.88rem;">
          Ditemukan <strong>{{ $books->total() }}</strong> buku dan <strong>{{ $users->total() }}</strong> member komunitas.
        </p>
      </div>

      {{-- Search Form on Page --}}
      <form action="{{ route('search') }}" method="GET" style="display: flex; gap: 8px; width: 400px; max-width: 100%;">
        <input type="hidden" name="tab" value="{{ $tab }}">
        <input type="hidden" name="view" value="{{ $view }}">
        <div style="position: relative; flex: 1; display: flex; align-items: center; background: #0d1117; border: 1px solid var(--border-color); border-radius: 24px; padding: 0 14px; box-shadow: inset 0 1px 2px rgba(0,0,0,0.25); transition: all 0.2s ease;">
          <i class="fa-solid fa-magnifying-glass" style="color: var(--text-muted); font-size: 0.82rem; margin-right: 8px;"></i>
          <input type="text" name="q" value="{{ $search }}" placeholder="Cari buku, penulis, genre, atau member..." 
                 style="flex: 1; background: transparent; border: none; padding: 9px 0; color: var(--text-main); font-size: 0.88rem; outline: none;">
          @if($search !== '')
            <a href="{{ route('search', ['tab' => $tab, 'view' => $view]) }}" title="Bersihkan pencarian" style="color: var(--text-muted); text-decoration: none; font-size: 0.82rem; padding: 4px; margin-right: 2px;">
              <i class="fa-solid fa-xmark"></i>
            </a>
          @endif
        </div>
        <button type="submit" style="background: var(--member-accent); color: white; border: none; border-radius: 24px; padding: 0 18px; font-weight: 600; font-size: 0.88rem; cursor: pointer; display: flex; align-items: center; gap: 6px; box-shadow: 0 2px 8px rgba(31, 111, 235, 0.3); transition: background 0.15s ease;">
          Cari
        </button>
      </form>
    </div>

    {{-- Entity Switcher Tabs --}}
    <div class="search-tabs">
      <a href="{{ route('search', array_merge(request()->query(), ['tab' => 'books'])) }}" class="search-tab-link {{ $tab === 'books' ? 'active' : '' }}">
        <i class="fa-solid fa-book"></i>
        <span>Buku</span>
        <span class="search-tab-badge">{{ $books->total() }}</span>
      </a>
      <a href="{{ route('search', array_merge(request()->query(), ['tab' => 'users'])) }}" class="search-tab-link {{ $tab === 'users' ? 'active' : '' }}">
        <i class="fa-solid fa-users"></i>
        <span>Member Komunitas</span>
        <span class="search-tab-badge">{{ $users->total() }}</span>
      </a>
    </div>
  </div>

  {{-- Letterboxd Style Horizontal Filter Bar (for Books tab) --}}
  @if($tab === 'books')
    <form action="{{ route('search') }}" method="GET" class="search-filter-bar">
      <input type="hidden" name="q" value="{{ $search }}">
      <input type="hidden" name="tab" value="books">
      <input type="hidden" name="view" value="{{ $view }}">

      {{-- Dropdown Filters --}}
      <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 12px;">
        {{-- Filter Genre --}}
        <div style="display: flex; align-items: center; gap: 6px;">
          <label style="font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted); font-weight: 700;">Genre:</label>
          <select name="genre" class="filter-select" onchange="this.form.submit()">
            <option value="all" {{ $genre === 'all' || empty($genre) ? 'selected' : '' }}>Semua Genre</option>
            @foreach($genres as $g)
              <option value="{{ $g }}" {{ $genre === $g ? 'selected' : '' }}>{{ $g }}</option>
            @endforeach
          </select>
        </div>

        {{-- Filter Rating --}}
        <div style="display: flex; align-items: center; gap: 6px;">
          <label style="font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted); font-weight: 700;">Rating:</label>
          <select name="min_rating" class="filter-select" onchange="this.form.submit()">
            <option value="0" {{ $minRating == 0 ? 'selected' : '' }}>Semua Rating</option>
            <option value="3" {{ $minRating == 3 ? 'selected' : '' }}>3.0★ ke atas</option>
            <option value="4" {{ $minRating == 4 ? 'selected' : '' }}>4.0★ ke atas</option>
            <option value="4.5" {{ $minRating == 4.5 ? 'selected' : '' }}>4.5★ ke atas</option>
          </select>
        </div>

        {{-- Sort By --}}
        <div style="display: flex; align-items: center; gap: 6px;">
          <label style="font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted); font-weight: 700;">Urutkan:</label>
          <select name="sort" class="filter-select" onchange="this.form.submit()">
            <option value="relevance" {{ $sort === 'relevance' ? 'selected' : '' }}>Paling Relevan</option>
            <option value="popular" {{ $sort === 'popular' ? 'selected' : '' }}>Paling Populer</option>
            <option value="rating" {{ $sort === 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
            <option value="title" {{ $sort === 'title' ? 'selected' : '' }}>Judul (A-Z)</option>
          </select>
        </div>

        @if($genre !== 'all' || $minRating > 0 || $sort !== 'relevance')
          <a href="{{ route('search', ['q' => $search, 'tab' => 'books', 'view' => $view]) }}" style="font-size: 0.8rem; color: #f85149; text-decoration: none; margin-left: 4px;">
            <i class="fa-solid fa-rotate-left"></i> Reset Filter
          </a>
        @endif
      </div>

      {{-- View Mode Toggles (List vs Grid) --}}
      <div style="display: flex; align-items: center; gap: 6px;">
        <span style="font-size: 0.78rem; color: var(--text-muted); font-weight: 600; margin-right: 4px;">Tampilan:</span>
        <a href="{{ route('search', array_merge(request()->query(), ['view' => 'list'])) }}" 
           class="view-toggle-btn {{ $view === 'list' ? 'active' : '' }}" title="Tampilan List Terinci">
          <i class="fa-solid fa-bars"></i>
          <span>List</span>
        </a>
        <a href="{{ route('search', array_merge(request()->query(), ['view' => 'grid'])) }}" 
           class="view-toggle-btn {{ $view === 'grid' ? 'active' : '' }}" title="Tampilan Grid Poster">
          <i class="fa-solid fa-grip"></i>
          <span>Grid</span>
        </a>
      </div>
    </form>
  @endif

  {{-- ================= TAB CONTENT: BUKU ================= --}}
  @if($tab === 'books')
    @if($books->count() > 0)
      
      {{-- View Option 1: Detailed List View --}}
      @if($view === 'list')
        <div style="margin-bottom: 30px;">
          @foreach($books as $book)
            <div class="search-book-row">
              <a href="{{ route('books.show', $book->id) }}" class="search-book-thumb">
                @if($book->cover_image)
                  <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" loading="lazy">
                @else
                  <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #8b949e;">
                    <i class="fa-solid fa-book" style="font-size: 1.5rem;"></i>
                  </div>
                @endif
              </a>

              <div style="flex: 1; min-width: 0;">
                <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 4px;">
                  <h3 style="font-size: 1.1rem; font-weight: 700; margin: 0;">
                    <a href="{{ route('books.show', $book->id) }}" style="color: var(--text-main); text-decoration: none;">
                      {!! $highlight($book->title, $search) !!}
                    </a>
                  </h3>
                  
                  <div style="display: flex; align-items: center; gap: 12px; font-size: 0.82rem;">
                    <x-star-rating :rating="$book->reviews_avg_rating ?? 0" size="sm" :count="$book->reviews_count" />
                    <span style="color: var(--text-muted); display: inline-flex; align-items: center; gap: 4px;">
                      <i class="fa-solid fa-heart" style="color: #F78166; font-size: 0.78rem;"></i> {{ $book->liked_by_users_count }}
                    </span>
                  </div>
                </div>

                <div style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 8px; display: flex; flex-wrap: wrap; align-items: center; gap: 8px;">
                  <span>Oleh <strong style="color: #c9d1d9;">{!! $highlight($book->author, $search) !!}</strong></span>
                  <span>&bull;</span>
                  <span style="background: rgba(31, 111, 235, 0.15); color: #58a6ff; padding: 1px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">
                    {!! $highlight($book->genre, $search) !!}
                  </span>
                  @if($book->publication_year)
                    <span>&bull;</span>
                    <span>{{ $book->publication_year }}</span>
                  @endif
                </div>

                <p style="color: #8b949e; font-size: 0.85rem; line-height: 1.5; margin-bottom: 12px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                  {!! $highlight(Str::limit($book->description, 200), $search) !!}
                </p>

                <div style="display: flex; align-items: center; gap: 12px;">
                  <a href="{{ route('books.show', $book->id) }}" style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.82rem; font-weight: 600; color: #58a6ff; text-decoration: none;">
                    <span>Lihat Ulasan & Detail</span>
                    <i class="fa-solid fa-arrow-right" style="font-size: 0.75rem;"></i>
                  </a>
                </div>
              </div>
            </div>
          @endforeach
        </div>

      {{-- View Option 2: Poster Grid View --}}
      @else
        <div class="search-book-grid">
          @foreach($books as $book)
            <x-book-card :book="$book" />
          @endforeach
        </div>
      @endif

      {{-- Pagination Links --}}
      <div style="margin-top: 24px;">
        {{ $books->links() }}
      </div>

    @else
      {{-- Empty State Buku --}}
      <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 10px; padding: 40px 20px; text-align: center; margin-bottom: 30px;">
        <i class="fa-solid fa-book-open" style="font-size: 2.2rem; color: #30363d; margin-bottom: 12px; display: block;"></i>
        <h3 style="font-size: 1.1rem; color: var(--text-main); font-weight: 700; margin-bottom: 6px;">
          Tidak ada buku yang cocok dengan filter pencarian
        </h3>
        <p style="color: var(--text-muted); font-size: 0.88rem; max-width: 500px; margin: 0 auto 16px;">
          Coba ubah kata kunci pencarian, pilih &ldquo;Semua Genre&rdquo;, atau turunkan filter batas rating.
        </p>
        @if($users->total() > 0)
          <div style="margin-top: 14px;">
            <a href="{{ route('search', array_merge(request()->query(), ['tab' => 'users'])) }}" style="background: #21262d; border: 1px solid var(--border-color); color: #58a6ff; font-weight: 600; font-size: 0.85rem; padding: 8px 16px; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
              <i class="fa-solid fa-users"></i> Lihat {{ $users->total() }} Member Komunitas yang Ditemukan &rarr;
            </a>
          </div>
        @endif
      </div>
    @endif

  {{-- ================= TAB CONTENT: MEMBER KOMUNITAS ================= --}}
  @elseif($tab === 'users')
    @if($users->count() > 0)
      <div class="search-member-grid">
        @foreach($users as $user)
          <a href="{{ route('users.profile', $user->id) }}" class="search-member-card">
            <x-user-avatar :user="$user" size="lg" />
            
            <div style="flex: 1; min-width: 0;">
              <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 4px;">
                <h4 style="font-size: 0.98rem; font-weight: 700; color: var(--text-main); margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                  {!! $highlight($user->name, $search) !!}
                </h4>
                <span style="font-size: 0.7rem; background: rgba(31, 111, 235, 0.15); color: #58a6ff; padding: 1px 6px; border-radius: 10px; font-weight: 600; flex-shrink: 0;">
                  Member
                </span>
              </div>

              @if($user->bio)
                <p style="color: var(--text-muted); font-size: 0.8rem; margin-bottom: 6px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                  {!! $highlight($user->bio, $search) !!}
                </p>
              @endif

              <div style="font-size: 0.75rem; color: #8b949e; display: flex; align-items: center; gap: 10px;">
                <span>
                  <i class="fa-solid fa-book-bookmark" style="color: #58a6ff; font-size: 0.72rem;"></i> {{ $user->reading_shelves_count ?? $user->readingShelves()->count() }} di rak
                </span>
                <span>&bull;</span>
                <span>
                  <i class="fa-solid fa-pen-nib" style="color: #3fb950; font-size: 0.72rem;"></i> {{ $user->reviews_count ?? $user->reviews()->count() }} ulasan
                </span>
              </div>
            </div>
            
            <i class="fa-solid fa-chevron-right" style="color: #30363d; font-size: 0.85rem;"></i>
          </a>
        @endforeach
      </div>

      {{-- Pagination Links for Users --}}
      <div style="margin-top: 24px;">
        {{ $users->links() }}
      </div>

    @else
      {{-- Empty State Member --}}
      <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 10px; padding: 40px 20px; text-align: center; margin-bottom: 30px;">
        <i class="fa-solid fa-user-slash" style="font-size: 2.2rem; color: #30363d; margin-bottom: 12px; display: block;"></i>
        <h3 style="font-size: 1.1rem; color: var(--text-main); font-weight: 700; margin-bottom: 6px;">
          Tidak ada member yang cocok dengan pencarian
        </h3>
        <p style="color: var(--text-muted); font-size: 0.88rem; max-width: 500px; margin: 0 auto 16px;">
          Pastikan nama atau bio member yang dicari sudah sesuai.
        </p>
        @if($books->total() > 0)
          <div style="margin-top: 14px;">
            <a href="{{ route('search', array_merge(request()->query(), ['tab' => 'books'])) }}" style="background: #21262d; border: 1px solid var(--border-color); color: #58a6ff; font-weight: 600; font-size: 0.85rem; padding: 8px 16px; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
              <i class="fa-solid fa-book"></i> Lihat {{ $books->total() }} Buku yang Ditemukan &rarr;
            </a>
          </div>
        @endif
      </div>
    @endif
  @endif

  {{-- ================= GLOBAL ZERO STATE & SMART RECOMMENDATIONS ================= --}}
  @if($books->total() === 0 && $users->total() === 0)
    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; padding: 40px 24px; text-align: center; margin-bottom: 40px;">
      <i class="fa-solid fa-magnifying-glass" style="font-size: 3rem; color: #30363d; margin-bottom: 16px; display: block;"></i>
      <h2 style="font-size: 1.3rem; font-weight: 800; color: var(--text-main); margin-bottom: 8px;">
        Tidak ada hasil untuk &ldquo;{{ $search }}&rdquo;
      </h2>
      <p style="color: var(--text-muted); font-size: 0.9rem; max-width: 520px; margin: 0 auto 20px;">
        Kami tidak dapat menemukan buku maupun member komunitas dengan kata kunci tersebut.
      </p>

      {{-- Search Tips --}}
      <div style="max-width: 480px; margin: 0 auto 24px; text-align: left; background: #0d1117; border: 1px solid var(--border-color); border-radius: 8px; padding: 16px 20px;">
        <span style="font-size: 0.78rem; text-transform: uppercase; font-weight: 700; color: #58a6ff; letter-spacing: 0.5px; display: block; margin-bottom: 8px;">
          <i class="fa-solid fa-lightbulb"></i> Tips Pencarian:
        </span>
        <ul style="color: var(--text-muted); font-size: 0.82rem; padding-left: 20px; line-height: 1.6;">
          <li>Periksa ejaan kata kunci (contoh: judul, nama penulis, atau nama member).</li>
          <li>Gunakan kata kunci yang lebih umum atau potongan kata yang lebih pendek.</li>
          <li>Pastikan tidak ada filter genre atau batas rating yang terlalu ketat.</li>
        </ul>
      </div>

      <a href="{{ route('home') }}" style="background: var(--member-accent); color: white; text-decoration: none; padding: 8px 20px; border-radius: 6px; font-weight: 600; font-size: 0.88rem; display: inline-flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-house"></i> Kembali ke Beranda
      </a>
    </div>

    {{-- Fallback Recommendations Showcase --}}
    @if($fallbackBooks->isNotEmpty())
      <div style="margin-top: 30px;">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 18px;">
          <i class="fa-solid fa-fire" style="color: #F78166; font-size: 1.2rem;"></i>
          <h2 style="font-size: 1.15rem; font-weight: 800; color: var(--text-main); margin: 0;">
            Rekomendasi Populer di Re:Book
          </h2>
          <span style="font-size: 0.8rem; color: var(--text-muted);">Pilihan favorit komunitas yang layak dicoba</span>
        </div>

        <div class="search-book-grid">
          @foreach($fallbackBooks as $fb)
            <x-book-card :book="$fb" />
          @endforeach
        </div>
      </div>
    @endif
  @endif

</div>
@endsection
