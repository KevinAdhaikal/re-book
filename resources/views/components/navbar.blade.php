<header style="background-color: var(--bg-card); border-bottom: 1px solid var(--border-color); padding: 10px 40px; display: flex; align-items: center; justify-content: space-between; gap: 20px; position: sticky; top: 0; z-index: 1000;">
  <a href="{{ route('home') }}" style="background-color: var(--member-accent); color: #fff; font-weight: 800; font-size: 1.1rem; padding: 4px 10px; border-radius: 4px; text-decoration: none;">Re:Book</a>
  
  <form id="navbar-search-form" action="{{ request()->routeIs('member.likes.*') ? route('member.likes.index') : url('/') }}" method="GET" style="display: flex; align-items: center; gap: 8px; position: relative;">
    <!-- Dropdown Category Filter -->
    <select name="filter_type" id="navbar-filter-type" style="padding: 8px 12px; background: var(--bg-card); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none; font-size: 0.9rem; cursor: pointer;">
      <option value="all" {{ request('filter_type') == 'all' ? 'selected' : '' }}>Semua</option>
      <option value="title" {{ request('filter_type') == 'title' ? 'selected' : '' }}>Judul</option>
      <option value="author" {{ request('filter_type') == 'author' ? 'selected' : '' }}>Penulis</option>
      <option value="genre" {{ request('filter_type') == 'genre' ? 'selected' : '' }}>Genre</option>
    </select>

    <!-- Input Keyword Pencarian & Live Search Dropdown -->
    <div style="position: relative; display: flex; align-items: center;">
      <input type="text" name="search" id="navbar-search-input" value="{{ request('search') }}" autocomplete="off" placeholder="Cari judul, penulis, atau member..." style="padding: 8px 75px 8px 12px; background: var(--bg-card); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none; width: 300px; font-size: 0.9rem; transition: border-color 0.2s;">
      
      <span id="navbar-shortcut-badge" style="position: absolute; right: 32px; background: rgba(255,255,255,0.06); border: 1px solid var(--border-color); color: var(--text-muted); font-size: 0.65rem; padding: 2px 5px; border-radius: 4px; pointer-events: none; font-family: monospace;">Ctrl K</span>

      <button type="submit" id="navbar-search-btn" style="position: absolute; right: 10px; background: none; border: none; color: var(--text-muted); cursor: pointer;">
        <i class="fa-solid fa-magnifying-glass" id="navbar-search-icon"></i>
      </button>

      <!-- Live Search Results Dropdown (Inline) -->
      <div id="live-search-dropdown" style="display: none; position: absolute; top: calc(100% + 8px); left: 0; width: 380px; max-width: 90vw; background: #161b22; border: 1px solid var(--border-color); border-radius: 10px; box-shadow: 0 16px 36px rgba(0,0,0,0.6); z-index: 1050; overflow: hidden;">
        <!-- Injected via JavaScript -->
      </div>
    </div>
  </form>

  <div style="display: flex; align-items: center; gap: 15px;">
    @auth
      @if(auth()->user()->role === 'admin')
        <a href="{{ route('admin.dashboard') }}" style="color: var(--admin-accent); font-weight: 600; text-decoration: none;"><i class="fa-solid fa-shield-halved"></i> Dashboard Admin</a>
      @else
        <a href="{{ route('member.profile') }}" style="color: var(--text-main); font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
          <x-user-avatar :user="auth()->user()" size="sm" />
          <span>{{ auth()->user()->name }}</span>
        </a>
      @endif
      
      <form action="{{ route('logout') }}" method="POST" style="display: inline;">
        @csrf
        <button type="submit" style="background: none; border: none; color: var(--admin-danger); cursor: pointer; font-weight: 600;"><i class="fa-solid fa-right-from-bracket"></i> Logout</button>
      </form>
    @else
      <a href="{{ route('login') }}" style="background-color: var(--member-accent); color: #fff; text-decoration: none; font-size: 0.9rem; font-weight: 600; padding: 6px 16px; border-radius: 6px;">Masuk</a>
    @endauth
  </div>
</header>

{{-- ================= COMMAND PALETTE MODAL OVERLAY (Ctrl+K / '/') ================= --}}
<div id="cmd-palette-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(13, 17, 23, 0.82); backdrop-filter: blur(6px); z-index: 99999; align-items: flex-start; justify-content: center; padding-top: 10vh;">
  <div id="cmd-palette-modal" style="background: #161b22; border: 1px solid #30363d; border-radius: 12px; width: 620px; max-width: 92vw; box-shadow: 0 25px 60px rgba(0, 0, 0, 0.75); overflow: hidden; display: flex; flex-direction: column;">
    
    {{-- Input Bar Palette --}}
    <div style="display: flex; align-items: center; gap: 12px; padding: 14px 18px; border-bottom: 1px solid var(--border-color); background: #0d1117;">
      <i class="fa-solid fa-magnifying-glass" id="cmd-search-icon" style="color: var(--member-accent); font-size: 1.1rem;"></i>
      <input type="text" id="cmd-palette-input" placeholder="Cari judul buku, penulis, atau anggota komunitas..." autocomplete="off"
             style="flex: 1; background: transparent; border: none; outline: none; color: var(--text-main); font-size: 1rem; font-weight: 500;">
      <button type="button" id="cmd-palette-close" style="background: none; border: none; color: var(--text-muted); cursor: pointer; font-size: 1rem; padding: 4px;">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    {{-- Filter Category Pills --}}
    <div style="display: flex; gap: 8px; padding: 8px 18px; background: rgba(255,255,255,0.02); border-bottom: 1px solid var(--border-color); overflow-x: auto;">
      <button type="button" class="cmd-filter-pill active" data-filter="all" style="padding: 4px 12px; border-radius: 14px; font-size: 0.75rem; border: 1px solid var(--member-accent); background: var(--member-accent); color: white; cursor: pointer;">Semua</button>
      <button type="button" class="cmd-filter-pill" data-filter="title" style="padding: 4px 12px; border-radius: 14px; font-size: 0.75rem; border: 1px solid var(--border-color); background: transparent; color: var(--text-muted); cursor: pointer;">Judul Buku</button>
      <button type="button" class="cmd-filter-pill" data-filter="author" style="padding: 4px 12px; border-radius: 14px; font-size: 0.75rem; border: 1px solid var(--border-color); background: transparent; color: var(--text-muted); cursor: pointer;">Penulis</button>
      <button type="button" class="cmd-filter-pill" data-filter="genre" style="padding: 4px 12px; border-radius: 14px; font-size: 0.75rem; border: 1px solid var(--border-color); background: transparent; color: var(--text-muted); cursor: pointer;">Genre</button>
    </div>

    {{-- Palette Body Container --}}
    <div id="cmd-palette-body" style="max-height: 420px; overflow-y: auto; padding: 10px 0;">
      <!-- Diinjeksi dinamis oleh JavaScript -->
    </div>

    {{-- Footer Petunjuk Keyboard --}}
    <div style="padding: 8px 18px; background: #0d1117; border-top: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; font-size: 0.75rem; color: var(--text-muted);">
      <div style="display: flex; gap: 14px; align-items: center;">
        <span><kbd style="background: #21262d; padding: 1px 5px; border-radius: 3px; border: 1px solid #30363d;">↑↓</kbd> Navigasi</span>
        <span><kbd style="background: #21262d; padding: 1px 5px; border-radius: 3px; border: 1px solid #30363d;">↵</kbd> Buka</span>
        <span><kbd style="background: #21262d; padding: 1px 5px; border-radius: 3px; border: 1px solid #30363d;">Esc</kbd> Tutup</span>
      </div>
      <span style="color: var(--member-accent); font-weight: 600;">Re:Book Multi-Search</span>
    </div>

  </div>
</div>

<script>
(function() {
  const searchInput = document.getElementById('navbar-search-input');
  const filterType = document.getElementById('navbar-filter-type');
  const dropdown = document.getElementById('live-search-dropdown');
  const searchIcon = document.getElementById('navbar-search-icon');
  const searchForm = document.getElementById('navbar-search-form');

  // Command Palette Elements
  const paletteBackdrop = document.getElementById('cmd-palette-backdrop');
  const paletteModal = document.getElementById('cmd-palette-modal');
  const paletteInput = document.getElementById('cmd-palette-input');
  const paletteBody = document.getElementById('cmd-palette-body');
  const paletteClose = document.getElementById('cmd-palette-close');
  const cmdFilterPills = document.querySelectorAll('.cmd-filter-pill');
  const cmdSearchIcon = document.getElementById('cmd-search-icon');

  const quickSearchUrl = '{{ route('books.quickSearch') }}';
  const STORAGE_KEY = 'rebook_recent_searches';
  let activePaletteFilter = 'all';
  let debounceTimer = null;
  let activeItemIndex = -1;

  // Recent Searches LocalStorage Helpers
  function getRecentSearches() {
    try {
      const data = localStorage.getItem(STORAGE_KEY);
      return data ? JSON.parse(data) : [];
    } catch(e) {
      return [];
    }
  }

  function saveRecentSearch(term) {
    if (!term || term.trim().length < 2) return;
    term = term.trim();
    let searches = getRecentSearches().filter(s => s.toLowerCase() !== term.toLowerCase());
    searches.unshift(term);
    if (searches.length > 6) searches = searches.slice(0, 6);
    try {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(searches));
    } catch(e) {}
  }

  function deleteRecentSearch(term) {
    let searches = getRecentSearches().filter(s => s.toLowerCase() !== term.toLowerCase());
    localStorage.setItem(STORAGE_KEY, JSON.stringify(searches));
  }

  function clearAllRecentSearches() {
    localStorage.removeItem(STORAGE_KEY);
  }

  // Buka / Tutup Command Palette Modal
  function openCommandPalette(initialQuery = '') {
    paletteBackdrop.style.display = 'flex';
    paletteInput.value = initialQuery;
    paletteInput.focus();
    activeItemIndex = -1;
    if (initialQuery.trim().length >= 2) {
      fetchAndRender(initialQuery.trim(), activePaletteFilter, paletteBody, true);
    } else {
      renderPaletteEmptyState();
    }
  }

  function closeCommandPalette() {
    paletteBackdrop.style.display = 'none';
    paletteInput.value = '';
    activeItemIndex = -1;
  }

  function closeInlineDropdown() {
    dropdown.style.display = 'none';
    dropdown.innerHTML = '';
  }

  // Shortcut Keyboard Global: Ctrl+K / Cmd+K / Slash '/'
  document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
      e.preventDefault();
      if (paletteBackdrop.style.display === 'flex') {
        closeCommandPalette();
      } else {
        openCommandPalette(searchInput.value);
      }
    } else if (e.key === '/' && document.activeElement !== searchInput && document.activeElement !== paletteInput && !['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) {
      e.preventDefault();
      openCommandPalette();
    } else if (e.key === 'Escape') {
      if (paletteBackdrop.style.display === 'flex') {
        closeCommandPalette();
      } else {
        closeInlineDropdown();
      }
    }
  });

  if (paletteClose) {
    paletteClose.addEventListener('click', closeCommandPalette);
  }
  if (paletteBackdrop) {
    paletteBackdrop.addEventListener('click', function(e) {
      if (e.target === paletteBackdrop) closeCommandPalette();
    });
  }

  // Filter Pills di Modal
  cmdFilterPills.forEach(pill => {
    pill.addEventListener('click', function() {
      cmdFilterPills.forEach(p => {
        p.classList.remove('active');
        p.style.background = 'transparent';
        p.style.borderColor = 'var(--border-color)';
        p.style.color = 'var(--text-muted)';
      });
      this.classList.add('active');
      this.style.background = 'var(--member-accent)';
      this.style.borderColor = 'var(--member-accent)';
      this.style.color = 'white';
      activePaletteFilter = this.dataset.filter;

      const q = paletteInput.value.trim();
      if (q.length >= 2) {
        fetchAndRender(q, activePaletteFilter, paletteBody, true);
      }
    });
  });

  // Render Empty State (Riwayat Pencarian & Buku Populer)
  function renderPaletteEmptyState() {
    const recent = getRecentSearches();
    let html = '';

    if (recent.length > 0) {
      html += `
        <div style="padding: 6px 18px 4px 18px; display: flex; justify-content: space-between; align-items: center;">
          <span style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted); font-weight: 700;">
            <i class="fa-solid fa-clock-rotate-left"></i> Pencarian Terakhir
          </span>
          <button type="button" id="btn-clear-recent-searches" style="background: none; border: none; color: #8b949e; font-size: 0.72rem; cursor: pointer;">
            Hapus Semua
          </button>
        </div>
        <div style="padding: 6px 18px 14px 18px; display: flex; flex-wrap: wrap; gap: 8px;">
      `;

      recent.forEach(term => {
        html += `
          <div class="recent-search-pill" style="display: inline-flex; align-items: center; gap: 6px; background: #21262d; border: 1px solid var(--border-color); border-radius: 16px; padding: 4px 10px; font-size: 0.8rem; color: var(--text-main);">
            <span class="recent-term-text" data-term="${escapeHtml(term)}" style="cursor: pointer;">${escapeHtml(term)}</span>
            <i class="fa-solid fa-xmark btn-delete-term" data-term="${escapeHtml(term)}" style="cursor: pointer; color: #8b949e; font-size: 0.72rem;"></i>
          </div>
        `;
      });

      html += `</div>`;
    }

    // Quick Recommendations
    html += `
      <div style="padding: 10px 18px 6px 18px; border-top: 1px solid var(--border-color);">
        <span style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted); font-weight: 700;">
          <i class="fa-solid fa-fire" style="color: #F78166;"></i> Eksplorasi Cepat Populer
        </span>
      </div>
      <div style="display: flex; flex-direction: column;">
        <a href="/?filter_type=title&search=Chainsaw+Man" class="cmd-nav-item" style="display: flex; align-items: center; justify-content: space-between; padding: 9px 18px; text-decoration: none; color: var(--text-main); font-size: 0.88rem; transition: background 0.15s ease;">
          <div style="display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-book" style="color: #58a6ff; font-size: 0.9rem;"></i>
            <span>Chainsaw Man (Vol. 1)</span>
          </div>
          <span style="font-size: 0.75rem; color: var(--text-muted);">Manga • Action</span>
        </a>
        <a href="/?filter_type=title&search=Bumi+Manusia" class="cmd-nav-item" style="display: flex; align-items: center; justify-content: space-between; padding: 9px 18px; text-decoration: none; color: var(--text-main); font-size: 0.88rem; transition: background 0.15s ease;">
          <div style="display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-book" style="color: #58a6ff; font-size: 0.9rem;"></i>
            <span>Bumi Manusia</span>
          </div>
          <span style="font-size: 0.75rem; color: var(--text-muted);">Sastra • Pramoedya</span>
        </a>
        <a href="/?filter_type=title&search=Laut+Bercerita" class="cmd-nav-item" style="display: flex; align-items: center; justify-content: space-between; padding: 9px 18px; text-decoration: none; color: var(--text-main); font-size: 0.88rem; transition: background 0.15s ease;">
          <div style="display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-book" style="color: #58a6ff; font-size: 0.9rem;"></i>
            <span>Laut Bercerita</span>
          </div>
          <span style="font-size: 0.75rem; color: var(--text-muted);">Fiksi • Leila S. Chudori</span>
        </a>
      </div>
    `;

    paletteBody.innerHTML = html;

    // Listeners for recent search pills
    const clearBtn = document.getElementById('btn-clear-recent-searches');
    if (clearBtn) {
      clearBtn.addEventListener('click', function() {
        clearAllRecentSearches();
        renderPaletteEmptyState();
      });
    }

    paletteBody.querySelectorAll('.recent-term-text').forEach(el => {
      el.addEventListener('click', function() {
        paletteInput.value = this.dataset.term;
        fetchAndRender(this.dataset.term, activePaletteFilter, paletteBody, true);
      });
    });

    paletteBody.querySelectorAll('.btn-delete-term').forEach(el => {
      el.addEventListener('click', function(e) {
        e.stopPropagation();
        deleteRecentSearch(this.dataset.term);
        renderPaletteEmptyState();
      });
    });

    paletteBody.querySelectorAll('.cmd-nav-item').forEach(item => {
      item.addEventListener('mouseenter', function() {
        paletteBody.querySelectorAll('.cmd-nav-item').forEach(el => el.style.background = 'transparent');
        this.style.background = '#21262d';
      });
      item.addEventListener('mouseleave', function() {
        this.style.background = 'transparent';
      });
    });
  }

  // Render Multi-Entity Results (Buku & Member)
  function renderGroupedResults(data, query, container, isModal) {
    if ((!data.books || data.books.length === 0) && (!data.users || data.users.length === 0)) {
      container.innerHTML = `
        <div style="padding: 30px 18px; text-align: center; color: var(--text-muted); font-size: 0.9rem;">
          <i class="fa-solid fa-magnifying-glass" style="font-size: 1.8rem; color: #30363d; margin-bottom: 8px; display: block;"></i>
          Tidak ada buku atau member ditemukan untuk "<strong>${escapeHtml(query)}</strong>"
        </div>
      `;
      container.style.display = 'block';
      return;
    }

    let html = '';

    // 1. Group Buku
    if (data.books && data.books.length > 0) {
      html += `
        <div style="padding: 8px 18px 4px 18px; display: flex; justify-content: space-between; align-items: center; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">
          <span><i class="fa-solid fa-book" style="color: #58a6ff;"></i> Buku (${data.books_total ?? data.books.length})</span>
          <span style="font-size: 0.7rem; font-weight: normal;">Tekan ↵ untuk detail</span>
        </div>
        <div style="display: flex; flex-direction: column;">
      `;

      data.books.forEach(b => {
        const coverHtml = b.cover_url
          ? `<img src="${b.cover_url}" alt="${escapeHtml(b.title)}" style="width: 36px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border-color); flex-shrink: 0;">`
          : `<div style="width: 36px; height: 50px; background: #21262d; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #8b949e; font-size: 0.9rem; flex-shrink: 0;"><i class="fa-solid fa-book"></i></div>`;

        html += `
          <a href="${b.url}" class="palette-search-item" data-type="book" data-title="${escapeHtml(b.title)}" style="display: flex; align-items: center; gap: 12px; padding: 10px 18px; text-decoration: none; border-bottom: 1px solid rgba(48,54,61,0.4); transition: background 0.15s ease;">
            ${coverHtml}
            <div style="flex: 1; min-width: 0;">
              <div style="color: var(--text-main); font-weight: 700; font-size: 0.88rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 2px;">
                ${highlightMatch(b.title, query)}
              </div>
              <div style="color: var(--text-muted); font-size: 0.75rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 3px;">
                ${highlightMatch(b.author, query)} • <span style="color: #79c0ff;">${b.genre}</span>
              </div>
              <div style="display: flex; align-items: center; gap: 8px; font-size: 0.72rem;">
                <span style="color: var(--star-rating); font-weight: 700;">
                  <i class="fa-solid fa-star"></i> ${b.rating}
                </span>
                <span style="color: var(--text-muted);">
                  <i class="fa-solid fa-heart" style="color: #F78166; font-size: 0.68rem;"></i> ${b.likes_count}
                </span>
              </div>
            </div>
            <i class="fa-solid fa-chevron-right" style="color: #30363d; font-size: 0.75rem;"></i>
          </a>
        `;
      });

      html += `</div>`;
    }

    // 2. Group Member Komunitas
    if (data.users && data.users.length > 0) {
      html += `
        <div style="padding: 12px 18px 4px 18px; display: flex; justify-content: space-between; align-items: center; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; border-top: 1px solid var(--border-color);">
          <span><i class="fa-solid fa-users" style="color: #3fb950;"></i> Member Komunitas (${data.users_total ?? data.users.length})</span>
        </div>
        <div style="display: flex; flex-direction: column;">
      `;

      data.users.forEach(u => {
        const initial = u.name ? u.name.charAt(0).toUpperCase() : 'U';
        const avatarHtml = u.avatar_url
          ? `<img src="${u.avatar_url}" alt="${escapeHtml(u.name)}" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 1px solid var(--border-color); flex-shrink: 0;">`
          : `<div style="width: 36px; height: 36px; border-radius: 50%; background: var(--member-accent); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.85rem; flex-shrink: 0;">${initial}</div>`;

        html += `
          <a href="${u.url}" class="palette-search-item" data-type="user" data-title="${escapeHtml(u.name)}" style="display: flex; align-items: center; gap: 12px; padding: 10px 18px; text-decoration: none; border-bottom: 1px solid rgba(48,54,61,0.4); transition: background 0.15s ease;">
            ${avatarHtml}
            <div style="flex: 1; min-width: 0;">
              <div style="color: var(--text-main); font-weight: 700; font-size: 0.88rem; display: flex; align-items: center; gap: 8px;">
                <span>${highlightMatch(u.name, query)}</span>
                <span style="font-size: 0.68rem; background: rgba(31,111,235,0.15); color: #58a6ff; padding: 1px 6px; border-radius: 10px; font-weight: normal;">Member</span>
              </div>
              <div style="color: var(--text-muted); font-size: 0.75rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 2px;">
                ${u.bio}
              </div>
              <div style="font-size: 0.72rem; color: #8b949e; margin-top: 2px;">
                <i class="fa-solid fa-book-bookmark" style="font-size: 0.68rem;"></i> ${u.shelved_count} buku di rak &bull; 
                <i class="fa-solid fa-pen" style="font-size: 0.68rem;"></i> ${u.reviews_count} ulasan
              </div>
            </div>
            <i class="fa-solid fa-chevron-right" style="color: #30363d; font-size: 0.75rem;"></i>
          </a>
        `;
      });

      html += `</div>`;
    }

    // See all results footer link
    html += `
      <div id="btn-see-all-palette" style="padding: 10px 18px; background: #21262d; text-align: center; border-top: 1px solid var(--border-color); cursor: pointer; color: #58a6ff; font-weight: 600; font-size: 0.82rem; transition: background 0.15s ease;">
        Lihat semua hasil pencarian untuk "<strong>${escapeHtml(query)}</strong>" &rarr;
      </div>
    `;

    container.innerHTML = html;
    container.style.display = 'block';

    // Click handler for see-all
    const seeAllBtn = container.querySelector('#btn-see-all-palette');
    if (seeAllBtn) {
      seeAllBtn.addEventListener('click', function() {
        saveRecentSearch(query);
        window.location.href = `/?search=${encodeURIComponent(query)}#katalog-buku`;
      });
    }

    // Item hover and save to recent searches on click
    container.querySelectorAll('.palette-search-item').forEach((item, idx) => {
      item.addEventListener('mouseenter', function() {
        container.querySelectorAll('.palette-search-item').forEach(el => el.style.background = 'transparent');
        this.style.background = '#21262d';
        activeItemIndex = idx;
      });
      item.addEventListener('mouseleave', function() {
        this.style.background = 'transparent';
      });
      item.addEventListener('click', function() {
        saveRecentSearch(query);
      });
    });
  }

  // Highlight helper
  function highlightMatch(text, query) {
    if (!text) return '';
    if (!query) return escapeHtml(text);
    const escapedText = escapeHtml(text);
    const regex = new RegExp(`(${escapeRegex(query)})`, 'gi');
    return escapedText.replace(regex, '<span style="color: #58A6FF; text-decoration: underline; font-weight: 800;">$1</span>');
  }

  function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>"']/g, function(m) {
      return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m];
    });
  }

  function escapeRegex(str) {
    return str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  }

  // Fetch logic
  function fetchAndRender(query, type, container, isModal) {
    const icon = isModal ? cmdSearchIcon : searchIcon;
    if (icon) icon.className = 'fa-solid fa-spinner fa-spin';

    fetch(`${quickSearchUrl}?search=${encodeURIComponent(query)}&filter_type=${encodeURIComponent(type)}`, {
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(res => res.json())
    .then(data => {
      if (icon) icon.className = 'fa-solid fa-magnifying-glass';
      if (data && data.success) {
        renderGroupedResults(data, query, container, isModal);
      }
    })
    .catch(err => {
      if (icon) icon.className = 'fa-solid fa-magnifying-glass';
      console.error('Search error:', err);
    });
  }

  // Palette Input Listener
  paletteInput.addEventListener('input', function() {
    clearTimeout(debounceTimer);
    const q = this.value.trim();
    if (q.length < 2) {
      renderPaletteEmptyState();
      return;
    }
    debounceTimer = setTimeout(() => {
      fetchAndRender(q, activePaletteFilter, paletteBody, true);
    }, 200);
  });

  // Palette Keydown Navigation
  paletteInput.addEventListener('keydown', function(e) {
    const items = paletteBody.querySelectorAll('.palette-search-item, .cmd-nav-item');
    if (!items.length) {
      if (e.key === 'Enter') {
        const q = paletteInput.value.trim();
        if (q) {
          saveRecentSearch(q);
          window.location.href = `/?search=${encodeURIComponent(q)}#katalog-buku`;
        }
      }
      return;
    }

    if (e.key === 'ArrowDown') {
      e.preventDefault();
      activeItemIndex = (activeItemIndex + 1) % items.length;
      updateActiveItem(items);
    } else if (e.key === 'ArrowUp') {
      e.preventDefault();
      activeItemIndex = (activeItemIndex - 1 + items.length) % items.length;
      updateActiveItem(items);
    } else if (e.key === 'Enter') {
      e.preventDefault();
      if (activeItemIndex >= 0 && items[activeItemIndex]) {
        items[activeItemIndex].click();
      } else {
        const q = paletteInput.value.trim();
        if (q) {
          saveRecentSearch(q);
          window.location.href = `/?search=${encodeURIComponent(q)}#katalog-buku`;
        }
      }
    }
  });

  function updateActiveItem(items) {
    items.forEach((item, idx) => {
      if (idx === activeItemIndex) {
        item.style.background = '#21262d';
        item.scrollIntoView({ block: 'nearest' });
      } else {
        item.style.background = 'transparent';
      }
    });
  }

  // Inline Navbar Search Listeners
  searchInput.addEventListener('focus', function() {
    // If user clicks into navbar input, they can also use Command Palette or keep typing inline
    if (this.value.trim().length >= 2) {
      fetchAndRender(this.value.trim(), filterType ? filterType.value : 'all', dropdown, false);
    }
  });

  searchInput.addEventListener('input', function() {
    clearTimeout(debounceTimer);
    const q = this.value.trim();
    if (q.length < 2) {
      closeInlineDropdown();
      return;
    }
    debounceTimer = setTimeout(() => {
      fetchAndRender(q, filterType ? filterType.value : 'all', dropdown, false);
    }, 250);
  });

  if (filterType) {
    filterType.addEventListener('change', function() {
      if (searchInput.value.trim().length >= 2) {
        fetchAndRender(searchInput.value.trim(), filterType.value, dropdown, false);
      }
    });
  }

  searchForm.addEventListener('submit', function() {
    const q = searchInput.value.trim();
    if (q) saveRecentSearch(q);
  });

  document.addEventListener('click', function(e) {
    if (!dropdown.contains(e.target) && !searchInput.contains(e.target)) {
      closeInlineDropdown();
    }
  });
})();
</script>
