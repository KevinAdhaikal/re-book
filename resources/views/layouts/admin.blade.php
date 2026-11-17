<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Admin Dashboard - Re:Book')</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --bg-main: #0D1117;
      --bg-card: #161B22;
      --bg-card-hover: #1C2129;
      --border-color: #30363D;
      --border-light: #21262D;
      --text-main: #F0F6FC;
      --text-muted: #8B949E;
      --member-accent: #1F6FEB;
      --member-accent-hover: #388BFD;
      --admin-accent: #238636;
      --admin-accent-hover: #2EA043;
      --admin-danger: #DA3633;
      --admin-danger-hover: #F85149;
      --star-rating: #E3B341;
      --favorite-coral: #F78166;
      --font-sans: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; font-family: var(--font-sans); }
    body { background-color: var(--bg-main); color: var(--text-main); min-height: 100vh; display: flex; flex-direction: column; -webkit-font-smoothing: antialiased; }
    .admin-wrapper { display: flex; flex: 1; }
    .admin-sidebar { width: 250px; background-color: var(--bg-card); border-right: 1px solid var(--border-color); padding: 20px; flex-shrink: 0; }
    .sidebar-menu { list-style: none; }
    .sidebar-menu li a { display: flex; align-items: center; gap: 10px; color: var(--text-muted); text-decoration: none; padding: 10px 12px; border-radius: 6px; font-weight: 500; font-size: 0.9rem; margin-bottom: 4px; transition: all 0.15s ease; }
    .sidebar-menu li a:hover, .sidebar-menu li a.active { background-color: #21262d; color: #3fb950; font-weight: 600; }
    .admin-content { flex: 1; padding: 30px 40px; min-width: 0; }
    .custom-table { width: 100%; border-collapse: collapse; margin-top: 15px; background: var(--bg-card); border-radius: 8px; overflow: hidden; border: 1px solid var(--border-color); }
    .custom-table th, .custom-table td { padding: 12px 16px; text-align: left; border-bottom: 1px solid var(--border-color); font-size: 0.9rem; }
    .custom-table th { background: #21262d; color: var(--text-main); font-weight: 600; }
  </style>
</head>
<body>

  @include('components.navbar')

  <div class="admin-wrapper">
    <aside class="admin-sidebar">
      <h4 style="font-size: 0.8rem; color: var(--admin-accent); margin-bottom: 15px; text-transform: uppercase;">PANEL ADMIN</h4>
      <ul class="sidebar-menu">
        <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fa-solid fa-chart-line"></i> Overview</a></li>
        <li><a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><i class="fa-solid fa-users"></i> Kelola Member</a></li>
        <li><a href="{{ route('admin.books.manage') }}" class="{{ request()->routeIs('admin.books.*') ? 'active' : '' }}"><i class="fa-solid fa-book"></i> Kelola Buku</a></li>
        <li><a href="{{ route('admin.reviews.index') }}" class="{{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}"><i class="fa-solid fa-comments"></i> Moderasi Ulasan</a></li>
        <li><a href="{{ route('admin.words.index') }}" class="{{ request()->routeIs('admin.words.*') ? 'active' : '' }}"><i class="fa-solid fa-filter"></i> Filter Kata Kasar</a></li>
        <li><a href="{{ route('admin.exports.index') }}" class="{{ request()->routeIs('admin.exports.*') ? 'active' : '' }}"><i class="fa-solid fa-file-arrow-down"></i> Export Data</a></li>
      </ul>
    </aside>

    <main class="admin-content">
      @if(session('success'))
        <div style="background: rgba(35, 134, 54, 0.18); border: 1px solid var(--admin-accent); color: #3fb950; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; font-size: 0.9rem; display: flex; align-items: center; gap: 8px;">
          <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
      @endif

      @if(session('error'))
        <div style="background: rgba(218, 54, 51, 0.18); border: 1px solid var(--admin-danger); color: #f85149; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; font-size: 0.9rem; display: flex; align-items: center; gap: 8px;">
          <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
        </div>
      @endif

      @yield('admin_content')
    </main>
  </div>

  @include('components.footer')
  @stack('scripts')
</body>
</html>