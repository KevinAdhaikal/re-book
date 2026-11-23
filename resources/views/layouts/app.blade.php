<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
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
      height: 260px;
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

    @media (max-width: 768px) {
      .main-content { padding: 20px 16px; }
      .book-cover-wrap { height: 220px; }
    }

    /* Clean Re:Book Unified Pagination */
    .rebook-pagination,
    nav[role="navigation"] {
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 12px;
      margin-top: 24px;
      padding: 12px 0;
      font-size: 0.85rem;
      color: var(--text-muted);
    }
    .rebook-pagination .pagination-info {
      color: var(--text-muted);
    }
    .rebook-pagination .pagination-info strong {
      color: var(--text-main);
    }
    .rebook-pagination .pagination-links {
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }
    .rebook-pagination .page-link,
    nav[role="navigation"] a,
    nav[role="navigation"] span[aria-disabled],
    nav[role="navigation"] span[aria-current] {
      padding: 6px 12px !important;
      background: var(--bg-card) !important;
      border: 1px solid var(--border-color) !important;
      color: var(--text-main) !important;
      border-radius: 6px !important;
      text-decoration: none !important;
      display: inline-flex !important;
      align-items: center !important;
      justify-content: center !important;
      min-width: 32px;
      height: 32px;
      gap: 6px !important;
      font-size: 0.82rem !important;
      transition: all 0.15s ease !important;
    }
    .rebook-pagination .page-link:hover:not(.disabled):not(.active),
    nav[role="navigation"] a:hover {
      background: #21262d !important;
      border-color: #58a6ff !important;
      color: #58a6ff !important;
    }
    .rebook-pagination .page-link.active,
    nav[role="navigation"] span[aria-current] span,
    nav[role="navigation"] span[aria-current] {
      background: var(--member-accent) !important;
      border-color: var(--member-accent) !important;
      color: white !important;
      font-weight: 700 !important;
      cursor: default;
    }
    .rebook-pagination .page-link.disabled,
    nav[role="navigation"] span[aria-disabled] {
      opacity: 0.45 !important;
      cursor: not-allowed !important;
    }
    nav[role="navigation"] svg {
      width: 14px !important;
      height: 14px !important;
      max-width: 14px !important;
      max-height: 14px !important;
      display: inline-block;
      vertical-align: middle;
    }
    /* Sembunyikan blok duplikat mobile jika menggunakan template bawaan framework */
    nav[role="navigation"] .sm\:hidden {
      display: none !important;
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

  {{-- Include Global Toast Notification --}}
  @include('components.toast')

  @stack('scripts')
</body>
</html>