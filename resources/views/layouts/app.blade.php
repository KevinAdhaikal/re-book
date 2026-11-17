<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Re:Book - Platform Rating, Ulasan dan Eksplorasi Buku')</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <style>
    :root {
      --bg-main: #0D1117;
      --bg-card: #161B22;
      --border-color: #30363D;
      --text-main: #F0F6FC;
      --text-muted: #8B949E;
      --member-accent: #1F6FEB;
      --member-accent-hover: #388BFD;
      --admin-accent: #238636;
      --admin-danger: #DA3633;
      --star-rating: #E3B341;
      --favorite-coral: #F78166;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
    body { background-color: var(--bg-main); color: var(--text-main); min-height: 100vh; display: flex; flex-direction: column; }
    
    .main-content { flex: 1; padding: 30px 40px; }
  </style>
  @stack('styles')
</head>
<body>

  {{-- Include Component Navbar Header --}}
  @include('components.navbar')

  {{-- Content Dynamic --}}
  <main class="main-content">
    @yield('content')
  </main>

  {{-- Include Component Footer --}}
  @include('components.footer')

  @stack('scripts')
</body>
</html>