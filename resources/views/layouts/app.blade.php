<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Re:Book - Platform Rating, Ulasan dan Eksplorasi Buku')</title>
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
      --border-light: #21262D;
      --text-main: #F0F6FC;
      --text-muted: #8B949E;
      --member-accent: #1F6FEB;
      --member-accent-hover: #388BFD;
      --admin-accent: #238636;
      --admin-danger: #DA3633;
      --star-rating: #E3B341;
      --favorite-coral: #F78166;
      --font-sans: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
      --font-serif: 'Lora', Georgia, serif;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; font-family: var(--font-sans); }
    body { background-color: var(--bg-main); color: var(--text-main); min-height: 100vh; display: flex; flex-direction: column; -webkit-font-smoothing: antialiased; }
    
    .font-serif { font-family: var(--font-serif); }
    .main-content { flex: 1; padding: 30px 40px; }

    @media (max-width: 768px) {
      .main-content { padding: 20px 16px; }
    }
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