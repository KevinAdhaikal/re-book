<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Member Dashboard - Re:Book')</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  {{-- Google Fonts: Plus Jakarta Sans & Lora Serif --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Lora:ital,wght@0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">

  <style>
    :root {
      --bg-main: #0D1117;
      --bg-card: #161B22;
      --bg-card-hover: #1C2129;
      --border-color: #30363D;
      --text-main: #F0F6FC;
      --text-muted: #8B949E;
      --member-accent: #1F6FEB;
      --member-accent-hover: #388BFD;
      --admin-danger: #DA3633;
      --star-rating: #E3B341;
      --favorite-coral: #F78166;
      --font-sans: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
      --font-serif: 'Lora', Georgia, serif;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; font-family: var(--font-sans); }
    body { background-color: var(--bg-main); color: var(--text-main); min-height: 100vh; display: flex; flex-direction: column; -webkit-font-smoothing: antialiased; }

    /* Layout Flexbox dengan Sidebar */
    .member-wrapper { display: flex; flex: 1; }
    
    /* Sidebar Navigation Member */
    .member-sidebar { width: 250px; background-color: var(--bg-card); border-right: 1px solid var(--border-color); padding: 25px 20px; }
    .sidebar-title { font-size: 0.8rem; color: var(--text-muted); margin-bottom: 15px; text-transform: uppercase; letter-spacing: 0.5px; }
    .sidebar-menu { list-style: none; }
    .sidebar-menu li a { display: flex; align-items: center; gap: 10px; color: var(--text-muted); text-decoration: none; padding: 10px 12px; border-radius: 6px; font-weight: 500; font-size: 0.9rem; margin-bottom: 6px; transition: all 0.2s; }
    .sidebar-menu li a:hover, .sidebar-menu li a.active { background-color: #21262d; color: var(--text-main); }
    .sidebar-menu li a.active { border-left: 3px solid var(--member-accent); border-radius: 0 6px 6px 0; }

    /* Content Area */
    .member-content { flex: 1; padding: 30px 40px; }

    /* 3D Book Card & Spine Depth */
    .book-card {
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
      height: 250px;
      background: #12161c;
    }
    .book-cover-wrap::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      bottom: 0;
      width: 8px;
      background: linear-gradient(to right, rgba(0,0,0,0.5) 0%, rgba(0,0,0,0.15) 60%, transparent 100%);
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

    /* Alert / Session Banner */
    .alert-success { background: rgba(35, 134, 54, 0.2); border: 1px solid #238636; color: #3fb950; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; font-size: 0.9rem; }
    .alert-danger { background: rgba(218, 54, 51, 0.2); border: 1px solid var(--admin-danger); color: #f85149; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; font-size: 0.9rem; }
  </style>
  @stack('styles')
</head>
<body>

  {{-- Top Header / Navbar --}}
  @include('components.navbar')

  <div class="member-wrapper">
    
    {{-- Sidebar Member --}}
    <aside class="member-sidebar">
      <h4 class="sidebar-title">PANEL MEMBER</h4>
      <ul class="sidebar-menu">
        <li>
          <a href="{{ route('member.profile') }}" class="{{ request()->routeIs('member.profile') ? 'active' : '' }}">
            <i class="fa-solid fa-user"></i>Profil Saya
          </a>
        </li>
        <li>
          <a href="{{ route('member.fav') }}" class="{{ request()->routeIs('member.fav*') ? 'active' : '' }}">
            <i class="fa-solid fa-star" style="color: var(--star-rating);"></i>Buku Favorit
          </a>
        </li>
        <li>
          <a href="{{ route('member.shelf.index') }}" class="{{ request()->routeIs('member.shelf.*') ? 'active' : '' }}">
            <i class="fa-solid fa-bookmark" style="color: #58a6ff;"></i>Rak Bacaan
          </a>
        </li>
        <li>
          <a href="{{ route('member.reviews.index') }}" class="{{ request()->routeIs('member.reviews.*') ? 'active' : '' }}">
            <i class="fa-solid fa-pen-to-square"></i>Ulasan Saya
          </a>
        </li>
        <li>
          <a href="{{ route('member.likes.index') }}" class="{{ request()->routeIs('member.likes.*') ? 'active' : '' }}">
            <i class="fa-solid fa-heart" style="color: var(--favorite-coral);"></i>Buku Disukai
          </a>
        </li>
        <li style="margin-top: 20px; border-top: 1px solid var(--border-color); padding-top: 15px;">
          <a href="{{ route('home') }}">
            <i class="fa-solid fa-compass"></i>Eksplorasi Katalog
          </a>
        </li>
      </ul>
    </aside>

    {{-- Content Area Dynamic --}}
    <main class="member-content">
      @if(session('success'))
        <div class="alert-success">
          <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
      @endif

      @if(session('error'))
        <div class="alert-danger">
          <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
        </div>
      @endif

      @yield('member_content')
    </main>
    
  </div>

  {{-- Footer --}}
  @include('components.footer')

  {{-- Include Global Toast Notification --}}
  @include('components.toast')

  @stack('scripts')
</body>
</html>