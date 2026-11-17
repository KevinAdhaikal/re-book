<header style="background-color: var(--bg-card); border-bottom: 1px solid var(--border-color); padding: 10px 40px; display: flex; align-items: center; justify-content: space-between; gap: 20px; position: sticky; top: 0; z-index: 1000;">
  <a href="{{ route('home') }}" style="background-color: var(--member-accent); color: #fff; font-weight: 800; font-size: 1.1rem; padding: 4px 10px; border-radius: 4px; text-decoration: none;">Re:Book</a>
  
  <form action="{{ request()->routeIs('member.likes.*') ? route('member.likes.index') : url('/') }}" method="GET" style="display: flex; align-items: center; gap: 8px;">
    <!-- Dropdown Category Filter -->
    <select name="filter_type" style="padding: 8px 12px; background: var(--bg-card); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none; font-size: 0.9rem;">
      <option value="all" {{ request('filter_type') == 'all' ? 'selected' : '' }}>Semua</option>
      <option value="title" {{ request('filter_type') == 'title' ? 'selected' : '' }}>Judul</option>
      <option value="author" {{ request('filter_type') == 'author' ? 'selected' : '' }}>Penulis</option>
      <option value="genre" {{ request('filter_type') == 'genre' ? 'selected' : '' }}>Genre</option>
    </select>

    <!-- Input Keyword Pencarian -->
    <div style="position: relative; display: flex; align-items: center;">
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul buku, penulis, atau genre..." style="padding: 8px 35px 8px 12px; background: var(--bg-card); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none; width: 260px; font-size: 0.9rem;">
    
      <button type="submit" style="position: absolute; right: 10px; background: none; border: none; color: var(--text-muted); cursor: pointer;">
        <i class="fa-solid fa-magnifying-glass"></i>
      </button>
    </div>
  </form>

  <div style="display: flex; align-items: center; gap: 15px;">
    @auth
      @if(auth()->user()->role === 'admin')
        <a href="{{ route('admin.dashboard') }}" style="color: var(--admin-accent); font-weight: 600; text-decoration: none;"><i class="fa-solid fa-shield-halved"></i> Dashboard Admin</a>
      @else
        <a href="{{ route('member.profile') }}" style="color: var(--text-main); font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
          @if(auth()->user()->avatarUrl())
            <img src="{{ auth()->user()->avatarUrl() }}" alt="{{ auth()->user()->name }}" style="width: 28px; height: 28px; border-radius: 50%; object-fit: cover; border: 1px solid var(--border-color);">
          @else
            <div style="width: 28px; height: 28px; border-radius: 50%; background: var(--member-accent); display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: bold; color: white;">
              {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
          @endif
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