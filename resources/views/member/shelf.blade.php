@extends('layouts.member')

@section('title', 'Rak Bacaan Saya - Re:Book')

@section('member_content')
<div style="margin-bottom: 25px;">
  <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 15px;">
    <div>
      <h2 style="font-size: 1.6rem; color: var(--text-main); margin-bottom: 6px; display: flex; align-items: center; gap: 10px;">
        <i class="fa-solid fa-bookmark" style="color: #58a6ff;"></i> Rak Bacaan Saya
      </h2>
      <p style="color: var(--text-muted); font-size: 0.95rem;">
        Kelola progres dan target membaca Anda (Goodreads / Letterboxd style).
      </p>
    </div>
    <a href="{{ route('home') }}" style="background: #21262d; border: 1px solid var(--border-color); color: var(--text-main); padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
      <i class="fa-solid fa-plus" style="color: #3fb950;"></i> Tambah Buku dari Katalog
    </a>
  </div>
</div>

{{-- Tab Filter Status Rak --}}
<div style="display: flex; gap: 10px; margin-bottom: 25px; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; flex-wrap: wrap; align-items: center; justify-content: space-between;">
  <div style="display: flex; gap: 8px; flex-wrap: wrap;">
    @php
      $tabs = [
        'all' => ['label' => 'Semua', 'icon' => 'fa-list'],
        'want_to_read' => ['label' => 'Ingin Dibaca', 'icon' => 'fa-clock'],
        'currently_reading' => ['label' => 'Sedang Dibaca', 'icon' => 'fa-book-open-reader'],
        'read' => ['label' => 'Selesai Dibaca', 'icon' => 'fa-circle-check'],
      ];
    @endphp

    @foreach($tabs as $key => $tab)
      @php
        $isActive = ($statusFilter === $key);
        $tabCount = $counts[$key] ?? 0;
      @endphp
      <a href="{{ route('member.shelf.index', ['status' => $key, 'search' => request('search')]) }}"
         style="text-decoration: none; padding: 7px 14px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;
                background: {{ $isActive ? '#1f6feb' : '#21262d' }};
                color: {{ $isActive ? '#fff' : 'var(--text-muted)' }};
                border: 1px solid {{ $isActive ? '#1f6feb' : 'var(--border-color)' }};">
        <i class="fa-solid {{ $tab['icon'] }}"></i>
        <span>{{ $tab['label'] }}</span>
        <span style="background: rgba(255,255,255,0.15); padding: 1px 7px; border-radius: 10px; font-size: 0.75rem;">{{ $tabCount }}</span>
      </a>
    @endforeach
  </div>

  {{-- Search dalam Rak --}}
  <form action="{{ route('member.shelf.index') }}" method="GET" style="display: flex; align-items: center; gap: 6px;">
    <input type="hidden" name="status" value="{{ $statusFilter }}">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari dalam rak..."
           style="padding: 7px 12px; background: var(--bg-card); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; font-size: 0.85rem; outline: none; width: 190px;">
    <button type="submit" style="background: #21262d; border: 1px solid var(--border-color); color: var(--text-main); padding: 7px 12px; border-radius: 6px; cursor: pointer;">
      <i class="fa-solid fa-magnifying-glass"></i>
    </button>
  </form>
</div>

{{-- Grid Item Rak Bacaan --}}
@if($shelvedItems->count() > 0)
  <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; margin-bottom: 30px;">
    @foreach($shelvedItems as $item)
      @php
        $b = $item->book;
        $statusColors = [
          'want_to_read' => ['bg' => 'rgba(88, 166, 255, 0.15)', 'border' => '#58a6ff', 'color' => '#58a6ff', 'icon' => 'fa-clock'],
          'currently_reading' => ['bg' => 'rgba(210, 153, 34, 0.15)', 'border' => '#d29922', 'color' => '#d29922', 'icon' => 'fa-book-open-reader'],
          'read' => ['bg' => 'rgba(63, 185, 80, 0.15)', 'border' => '#3fb950', 'color' => '#3fb950', 'icon' => 'fa-circle-check'],
        ];
        $st = $statusColors[$item->status] ?? $statusColors['want_to_read'];
      @endphp
      <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 16px; display: flex; gap: 16px; position: relative;">
        {{-- Cover --}}
        <a href="{{ route('books.show', $b->id) }}" style="flex-shrink: 0; text-decoration: none;">
          @if($b->cover)
            <img src="{{ asset('storage/' . $b->cover) }}" alt="{{ $b->title }}" style="width: 80px; height: 115px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border-color);">
          @else
            <div style="width: 80px; height: 115px; background: #21262d; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 1.5rem;">
              <i class="fa-solid fa-book"></i>
            </div>
          @endif
        </a>

        {{-- Detail & Kontrol Rak --}}
        <div style="flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
          <div>
            <div style="display: inline-flex; align-items: center; gap: 5px; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600; background: {{ $st['bg'] }}; border: 1px solid {{ $st['border'] }}; color: {{ $st['color'] }}; margin-bottom: 6px;">
              <i class="fa-solid {{ $st['icon'] }}"></i> {{ $item->status_label }}
            </div>
            <h4 style="font-size: 0.95rem; margin-bottom: 4px; line-height: 1.3;">
              <a href="{{ route('books.show', $b->id) }}" style="color: var(--text-main); text-decoration: none;">{{ $b->title }}</a>
            </h4>
            <p style="color: var(--text-muted); font-size: 0.8rem; margin-bottom: 6px;">{{ $b->author }} • <span style="color: #7d8590;">{{ $b->genre }}</span></p>
            <div style="color: var(--star-rating); font-size: 0.8rem;">
              <i class="fa-solid fa-star"></i> {{ number_format($b->reviews->avg('rating') ?? 0, 1) }}
            </div>
          </div>

          {{-- Form Ubah Status Rak --}}
          <div style="margin-top: 10px; display: flex; align-items: center; gap: 8px;">
            <form action="{{ route('member.shelf.update', $b->id) }}" method="POST" style="flex: 1; margin: 0;">
              @csrf
              <select name="status" onchange="this.form.submit()"
                      style="width: 100%; padding: 5px 8px; background: #21262d; border: 1px solid var(--border-color); color: var(--text-main); border-radius: 4px; font-size: 0.78rem; outline: none; cursor: pointer;">
                <option value="want_to_read" {{ $item->status === 'want_to_read' ? 'selected' : '' }}>⏳ Ingin Dibaca</option>
                <option value="currently_reading" {{ $item->status === 'currently_reading' ? 'selected' : '' }}>📖 Sedang Dibaca</option>
                <option value="read" {{ $item->status === 'read' ? 'selected' : '' }}>✅ Selesai Dibaca</option>
              </select>
            </form>

            {{-- Hapus dari Rak --}}
            <form action="{{ route('member.shelf.destroy', $b->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Hapus buku ini dari rak bacaan Anda?');">
              @csrf
              @method('DELETE')
              <button type="submit" title="Hapus dari Rak"
                      style="background: transparent; border: 1px solid var(--border-color); color: var(--admin-danger); padding: 5px 8px; border-radius: 4px; cursor: pointer; font-size: 0.8rem;">
                <i class="fa-solid fa-trash-can"></i>
              </button>
            </form>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div style="margin-top: 20px;">
    {{ $shelvedItems->links() }}
  </div>
@else
  <div style="background: var(--bg-card); border: 1px dashed var(--border-color); border-radius: 10px; padding: 50px 20px; text-align: center; color: var(--text-muted);">
    <i class="fa-solid fa-bookmark" style="font-size: 3rem; color: #30363d; margin-bottom: 15px;"></i>
    <h3 style="color: var(--text-main); margin-bottom: 8px;">Rak Bacaan Masih Kosong</h3>
    <p style="font-size: 0.9rem; max-width: 450px; margin: 0 auto 20px;">
      @if($statusFilter !== 'all')
        Belum ada buku dengan status <strong>{{ $tabs[$statusFilter]['label'] ?? $statusFilter }}</strong>.
      @else
        Anda belum menambahkan buku ke rak bacaan. Jelajahi katalog dan tandai buku yang ingin atau sedang Anda baca!
      @endif
    </p>
    <a href="{{ route('home') }}" style="background: var(--member-accent); color: white; padding: 8px 18px; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px;">
      <i class="fa-solid fa-compass"></i> Eksplorasi Buku Sekarang
    </a>
  </div>
@endif
@endsection
