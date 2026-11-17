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
      <input type="text" name="search" id="navbar-search-input" value="{{ request('search') }}" autocomplete="off" placeholder="Cari judul buku, penulis, atau genre..." style="padding: 8px 35px 8px 12px; background: var(--bg-card); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none; width: 280px; font-size: 0.9rem; transition: border-color 0.2s;">
    
      <button type="submit" id="navbar-search-btn" style="position: absolute; right: 10px; background: none; border: none; color: var(--text-muted); cursor: pointer;">
        <i class="fa-solid fa-magnifying-glass" id="navbar-search-icon"></i>
      </button>

      <!-- Live Search Results Dropdown -->
      <div id="live-search-dropdown" style="display: none; position: absolute; top: calc(100% + 8px); left: 0; width: 360px; max-width: 90vw; background: #161b22; border: 1px solid var(--border-color); border-radius: 10px; box-shadow: 0 16px 36px rgba(0,0,0,0.55); z-index: 1050; overflow: hidden;">
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

<script>
(function() {
  const searchInput = document.getElementById('navbar-search-input');
  const filterType = document.getElementById('navbar-filter-type');
  const dropdown = document.getElementById('live-search-dropdown');
  const searchIcon = document.getElementById('navbar-search-icon');
  const searchForm = document.getElementById('navbar-search-form');
  
  if (!searchInput || !dropdown) return;

  let debounceTimer = null;
  let activeIndex = -1;
  const quickSearchUrl = '{{ route('books.quickSearch') }}';

  function closeDropdown() {
    dropdown.style.display = 'none';
    dropdown.innerHTML = '';
    activeIndex = -1;
  }

  function renderResults(data, query) {
    if (!data.books || data.books.length === 0) {
      dropdown.innerHTML = `
        <div style="padding: 18px 16px; text-align: center; color: var(--text-muted); font-size: 0.85rem;">
          <i class="fa-solid fa-book-open" style="font-size: 1.5rem; color: #30363d; margin-bottom: 6px; display: block;"></i>
          Tidak ada buku ditemukan untuk "<strong>${escapeHtml(query)}</strong>"
        </div>
      `;
      dropdown.style.display = 'block';
      return;
    }

    let html = `
      <div style="padding: 8px 14px; background: rgba(255,255,255,0.03); border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted); font-weight: 600;">
        <span>Hasil Pencarian Buku</span>
        <span>${data.total} Ditemukan</span>
      </div>
      <div class="search-items-list" style="max-height: 340px; overflow-y: auto;">
    `;

    data.books.forEach((b, idx) => {
      const coverHtml = b.cover_url 
        ? `<img src="${b.cover_url}" alt="${escapeHtml(b.title)}" style="width: 38px; height: 52px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border-color); flex-shrink: 0;">`
        : `<div style="width: 38px; height: 52px; background: #21262d; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #8b949e; font-size: 1rem; flex-shrink: 0;"><i class="fa-solid fa-book"></i></div>`;

      html += `
        <a href="${b.url}" class="live-search-item" data-index="${idx}" style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; text-decoration: none; border-bottom: 1px solid rgba(48,54,61,0.5); transition: background 0.15s ease;">
          ${coverHtml}
          <div style="flex: 1; min-width: 0;">
            <div style="color: var(--text-main); font-weight: 600; font-size: 0.88rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 3px;">
              ${escapeHtml(b.title)}
            </div>
            <div style="color: var(--text-muted); font-size: 0.75rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 4px;">
              ${escapeHtml(b.author)} • <span style="color: #79c0ff;">${escapeHtml(b.genre)}</span>
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

    if (data.total > data.books.length) {
      html += `
        <div id="btn-see-all-search" style="padding: 10px 14px; background: #21262d; text-align: center; border-top: 1px solid var(--border-color); cursor: pointer; color: #58a6ff; font-weight: 600; font-size: 0.82rem; transition: background 0.15s ease;">
          Lihat semua ${data.total} hasil pencarian &rarr;
        </div>
      `;
    }

    dropdown.innerHTML = html;
    dropdown.style.display = 'block';

    const seeAllBtn = document.getElementById('btn-see-all-search');
    if (seeAllBtn) {
      seeAllBtn.addEventListener('click', function() {
        searchForm.submit();
      });
    }

    // Hover styles
    dropdown.querySelectorAll('.live-search-item').forEach(item => {
      item.addEventListener('mouseenter', function() {
        dropdown.querySelectorAll('.live-search-item').forEach(el => el.style.background = 'transparent');
        this.style.background = '#21262d';
        activeIndex = parseInt(this.dataset.index);
      });
      item.addEventListener('mouseleave', function() {
        this.style.background = 'transparent';
      });
    });
  }

  function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>"']/g, function(m) {
      return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m];
    });
  }

  function fetchQuickSearch() {
    const q = searchInput.value.trim();
    const type = filterType ? filterType.value : 'all';

    if (q.length < 2) {
      closeDropdown();
      return;
    }

    if (searchIcon) {
      searchIcon.className = 'fa-solid fa-spinner fa-spin';
    }

    fetch(`${quickSearchUrl}?search=${encodeURIComponent(q)}&filter_type=${encodeURIComponent(type)}`, {
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(res => res.json())
    .then(data => {
      if (searchIcon) {
        searchIcon.className = 'fa-solid fa-magnifying-glass';
      }
      if (data && data.success) {
        renderResults(data, q);
      }
    })
    .catch(err => {
      if (searchIcon) {
        searchIcon.className = 'fa-solid fa-magnifying-glass';
      }
      console.error('Quick search error:', err);
    });
  }

  searchInput.addEventListener('input', function() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(fetchQuickSearch, 250);
  });

  if (filterType) {
    filterType.addEventListener('change', function() {
      if (searchInput.value.trim().length >= 2) {
        fetchQuickSearch();
      }
    });
  }

  // Keyboard navigation: ArrowDown, ArrowUp, Enter, Escape
  searchInput.addEventListener('keydown', function(e) {
    const items = dropdown.querySelectorAll('.live-search-item');
    if (!items.length || dropdown.style.display === 'none') {
      if (e.key === 'Escape') closeDropdown();
      return;
    }

    if (e.key === 'ArrowDown') {
      e.preventDefault();
      activeIndex = (activeIndex + 1) % items.length;
      updateActiveItem(items);
    } else if (e.key === 'ArrowUp') {
      e.preventDefault();
      activeIndex = (activeIndex - 1 + items.length) % items.length;
      updateActiveItem(items);
    } else if (e.key === 'Enter') {
      if (activeIndex >= 0 && items[activeIndex]) {
        e.preventDefault();
        items[activeIndex].click();
      }
    } else if (e.key === 'Escape') {
      closeDropdown();
    }
  });

  function updateActiveItem(items) {
    items.forEach((item, idx) => {
      if (idx === activeIndex) {
        item.style.background = '#21262d';
        item.scrollIntoView({ block: 'nearest' });
      } else {
        item.style.background = 'transparent';
      }
    });
  }

  // Close on outside click
  document.addEventListener('click', function(e) {
    if (!dropdown.contains(e.target) && !searchInput.contains(e.target)) {
      closeDropdown();
    }
  });
})();
</script>
