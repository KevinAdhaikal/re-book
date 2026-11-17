<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - Re:Book</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --bg-main: #0D1117;
      --bg-card: #161B22;
      --border-color: #30363D;
      --text-main: #F0F6FC;
      --text-muted: #8B949E;
      --member-accent: #1F6FEB;
      --admin-accent: #238636;
      --admin-danger: #DA3633;
      --star-rating: #E3B341;
      --favorite-coral: #F78166;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
    body { background-color: var(--bg-main); color: var(--text-main); min-height: 100vh; display: flex; flex-direction: column; }
    .admin-wrapper { display: flex; flex: 1; }
    .admin-sidebar { width: 250px; background-color: var(--bg-card); border-right: 1px solid var(--border-color); padding: 20px; }
    .sidebar-menu { list-style: none; }
    .sidebar-menu li a { display: flex; align-items: center; gap: 10px; color: var(--text-muted); text-decoration: none; padding: 10px 12px; border-radius: 6px; font-weight: 500; font-size: 0.9rem; margin-bottom: 4px; }
    .sidebar-menu li a:hover, .sidebar-menu li a.active { background-color: #21262d; color: var(--admin-accent); }
    .admin-content { flex: 1; padding: 30px 40px; }
    .custom-table { width: 100%; border-collapse: collapse; margin-top: 15px; background: var(--bg-card); border-radius: 8px; overflow: hidden; border: 1px solid var(--border-color); }
    .custom-table th, .custom-table td { padding: 12px 16px; text-align: left; border-bottom: 1px solid var(--border-color); font-size: 0.9rem; }
    .custom-table th { background: #21262d; color: var(--text-main); }
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
        <li><a href="{{ route('admin.words.index') }}" class="{{ request()->routeIs('admin.words.*') ? 'active' : '' }}"><i class="fa-solid fa-filter"></i> Filter Kata Kasar</a></li>
      </ul>
    </aside>

    <main class="admin-content">
      @if(session('success'))
        <div style="background: rgba(35, 134, 54, 0.2); border: 1px solid var(--admin-accent); color: #3fb950; padding: 10px 15px; border-radius: 6px; margin-bottom: 20px;">
          {{ session('success') }}
        </div>
      @endif

      @yield('admin_content')
    </main>
  </div>

  @include('components.footer')
  @stack('scripts')
</body>
</html>