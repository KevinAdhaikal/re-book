@extends('layouts.app')

@section('title', $book->title . ' - Re:Book')

@section('content')
<div style="display: flex; gap: 30px; margin-bottom: 40px; flex-wrap: wrap;">
  <!-- Cover Buku -->
  @if($book->cover)
    <img src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->title }}"
         onerror="this.onerror=null; this.parentElement.querySelector('.cover-fallback').style.display='flex'; this.style.display='none';"
         style="width: 180px; height: 250px; object-fit: cover; border-radius: 6px;">
    <div class="cover-fallback" style="display: none; width: 180px; height: 250px; background: #161b22; border: 1px solid var(--border-color); border-radius: 6px; flex-direction: column; align-items: center; justify-content: center; gap: 8px; color: var(--text-muted); text-align: center; padding: 15px;">
      <i class="fa-solid fa-book" style="font-size: 2.2rem; color: #30363d;"></i>
      <span style="font-size: 0.8rem; font-weight: 500; color: #8b949e;">{{ Str::limit($book->title, 25) }}</span>
    </div>
  @else
    <div style="width: 180px; height: 250px; background: #161b22; border: 1px solid var(--border-color); border-radius: 6px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; color: var(--text-muted); text-align: center; padding: 15px;">
      <i class="fa-solid fa-book" style="font-size: 2.2rem; color: #30363d;"></i>
      <span style="font-size: 0.8rem; font-weight: 500; color: #8b949e;">{{ Str::limit($book->title, 25) }}</span>
    </div>
  @endif

  <!-- Info Detail Buku -->
  <div style="flex: 1; min-width: 300px;">
    <span style="background: #21262d; border: 1px solid var(--border-color); padding: 4px 10px; border-radius: 4px; font-size: 0.85rem;">{{ $book->genre }}</span>
    <h1 style="margin: 10px 0 6px 0;">{{ $book->title }}</h1>
    <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 15px;">Penulis: <strong>{{ $book->author }}</strong></p>
    
    <div style="color: var(--star-rating); font-size: 1.1rem; margin-bottom: 20px;">
      <i class="fa-solid fa-star"></i> {{ number_format($book->reviews->avg('rating') ?? 0, 1) }} / 5.0
      <span style="color: var(--text-muted); font-size: 0.85rem; margin-left: 8px;">({{ $book->reviews->count() }} ulasan)</span>
    </div>

    <p style="line-height: 1.6; color: var(--text-main); font-size: 0.95rem; margin-bottom: 20px;">{{ $book->description }}</p>

    @auth
      <div style="display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap;">
        @php
          $isLiked = auth()->user()->hasLiked($book->id);
          $isFavorited = auth()->user()->hasFavorited($book->id);
        @endphp
        <form action="{{ route('member.likes.toggle', $book->id) }}" method="POST" style="margin: 0;">
          @csrf
          <button type="submit" style="background: {{ $isLiked ? 'rgba(247, 129, 102, 0.15)' : '#21262d' }}; border: 1px solid {{ $isLiked ? '#F78166' : 'var(--border-color)' }}; color: {{ $isLiked ? '#F78166' : 'var(--text-main)' }}; padding: 8px 14px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
            <i class="{{ $isLiked ? 'fa-solid' : 'fa-regular' }} fa-heart"></i> {{ $isLiked ? 'Disukai' : 'Sukai Buku' }}
          </button>
        </form>

        <form action="{{ route('member.fav.toggle', $book->id) }}" method="POST" style="margin: 0;">
          @csrf
          <button type="submit" style="background: {{ $isFavorited ? 'rgba(227, 179, 65, 0.15)' : '#21262d' }}; border: 1px solid {{ $isFavorited ? 'var(--star-rating)' : 'var(--border-color)' }}; color: {{ $isFavorited ? 'var(--star-rating)' : 'var(--text-main)' }}; padding: 8px 14px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
            <i class="{{ $isFavorited ? 'fa-solid' : 'fa-regular' }} fa-star"></i> {{ $isFavorited ? 'Favorit Saya' : 'Tambah ke Favorit' }}
          </button>
        </form>
      </div>
    @endauth
  </div>
</div>

<hr style="border-color: var(--border-color); margin-bottom: 30px;">

<!-- Form Tulis Ulasan (Khusus Member Login) -->
@auth
  @if(auth()->user()->role === 'member')
    @php
      $myReviewForThisBook = $book->reviews->where('user_id', auth()->id())->first();
    @endphp

    @if($myReviewForThisBook)
      <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 20px; margin-bottom: 40px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
        <div>
          <h4 style="color: var(--text-main); font-size: 0.95rem; margin-bottom: 4px; display: flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-circle-check" style="color: #3fb950;"></i> Anda sudah mengulas buku ini
          </h4>
          <p style="color: var(--text-muted); font-size: 0.85rem; margin: 0;">
            Nilai Anda: <strong style="color: var(--star-rating);">⭐ {{ $myReviewForThisBook->rating }}.0</strong> — "{{ Str::limit($myReviewForThisBook->content, 60) }}"
          </p>
        </div>
        <a href="{{ route('member.reviews.index') }}" style="background: #21262d; border: 1px solid var(--border-color); color: var(--member-accent); padding: 7px 14px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: flex; align-items: center; gap: 6px;">
          <i class="fa-solid fa-pen-to-square"></i> Kelola di Ulasan Saya
        </a>
      </div>
    @else
      <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 25px; margin-bottom: 40px;">
        <h3 style="margin-bottom: 15px;">✍️ Tulis Ulasan Kamu</h3>
        <form action="{{ route('member.reviews.store') }}" method="POST">
          @csrf
          <input type="hidden" name="book_id" value="{{ $book->id }}">
          
          <div style="margin-bottom: 15px;">
            <label style="display: block; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 6px;">Beri Rating Bintang</label>
            <select name="rating" required style="padding: 8px 12px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none;">
              <option value="5">⭐⭐⭐⭐⭐ (5.0)</option>
              <option value="4">⭐⭐⭐⭐ (4.0)</option>
              <option value="3">⭐⭐⭐ (3.0)</option>
              <option value="2">⭐⭐ (2.0)</option>
              <option value="1">⭐ (1.0)</option>
            </select>
          </div>

          <div style="margin-bottom: 15px;">
            <textarea name="content" rows="3" placeholder="Bagikan pendapatmu tentang buku ini..." required style="width: 100%; padding: 12px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; outline: none; resize: vertical; font-size: 0.9rem; box-sizing: border-box;"></textarea>
          </div>

          <button type="submit" style="background: var(--member-accent); color: #fff; border: none; padding: 10px 20px; border-radius: 6px; font-weight: 600; cursor: pointer;">Kirim Ulasan</button>
        </form>
      </div>
    @endif
  @endif
@endauth

<!-- Daftar Ulasan Komunitas -->
<h3 style="margin-bottom: 20px;">💬 Ulasan Pembaca</h3>
@forelse ($book->reviews as $r)
  <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 22px; margin-bottom: 20px;">
    
    {{-- Header Ulasan --}}
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px; flex-wrap: wrap; gap: 10px;">
      <div style="display: flex; align-items: center; gap: 10px;">
        <a href="{{ $r->user ? route('users.profile', $r->user->id) : '#' }}" style="text-decoration: none; display: flex; align-items: center; gap: 10px;">
          <div style="width: 38px; height: 38px; background: var(--member-accent); color: #fff; border-radius: 50%; overflow: hidden; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1rem; border: 1px solid var(--border-color); flex-shrink: 0;">
            @if($r->user && $r->user->avatarUrl())
              <img src="{{ $r->user->avatarUrl() }}" alt="{{ $r->user->name }}" style="width: 100%; height: 100%; object-fit: cover;">
            @else
              {{ strtoupper(substr($r->user->name ?? 'M', 0, 1)) }}
            @endif
          </div>
          <div>
            <strong style="color: var(--text-main); font-size: 0.95rem; transition: color 0.2s;">{{ $r->user->name ?? 'Member' }}</strong>
            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 2px;">
              {{ $r->created_at ? $r->created_at->format('d M Y, H:i') : '' }}
            </div>
          </div>
        </a>
      </div>

      <div style="display: flex; align-items: center; gap: 10px;">
        <span style="color: var(--star-rating); font-weight: bold; font-size: 0.9rem; background: rgba(227, 179, 65, 0.12); padding: 4px 10px; border-radius: 15px; border: 1px solid var(--star-rating);">
          <i class="fa-solid fa-star"></i> {{ $r->rating }}.0 / 5.0
        </span>

        {{-- Aksi Edit / Hapus jika ulasan milik sendiri --}}
        @if(auth()->check() && (auth()->id() === $r->user_id || auth()->user()->role === 'admin'))
          <a href="{{ route('member.reviews.index') }}" title="Kelola di Ulasan Saya" style="color: var(--text-muted); font-size: 0.8rem; text-decoration: none; padding: 4px 8px; border: 1px solid var(--border-color); border-radius: 4px;">
            <i class="fa-solid fa-gear"></i> Kelola
          </a>
        @endif
      </div>
    </div>

    {{-- Isi Ulasan --}}
    <p style="color: var(--text-main); font-size: 0.95rem; line-height: 1.6; margin-bottom: 18px; background: rgba(255, 255, 255, 0.02); padding: 12px 15px; border-radius: 6px; border-left: 3px solid var(--member-accent);">
      "{{ $r->content }}"
    </p>

    {{-- Komentar Balasan pada Ulasan --}}
    <div style="border-top: 1px solid var(--border-color); padding-top: 15px;">
      <h5 style="color: var(--text-muted); margin-bottom: 12px; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.5px;">
        <i class="fa-regular fa-comments"></i> {{ $r->comments->count() }} Komentar
      </h5>

      <!-- List Komentar -->
      <div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 14px; padding-left: 12px; border-left: 2px solid var(--border-color);">
        @forelse ($r->comments as $c)
          <div style="background: rgba(255, 255, 255, 0.03); padding: 8px 12px; border-radius: 6px; display: flex; justify-content: space-between; align-items: flex-start; gap: 10px;">
            <div style="display: flex; align-items: flex-start; gap: 8px; flex: 1;">
              <a href="{{ $c->user ? route('users.profile', $c->user->id) : '#' }}" style="text-decoration: none; flex-shrink: 0;">
                <div style="width: 22px; height: 22px; border-radius: 50%; overflow: hidden; background: var(--member-accent); color: white; display: flex; align-items: center; justify-content: center; font-size: 0.65rem; font-weight: bold; margin-top: 2px; border: 1px solid var(--border-color);">
                  @if($c->user && $c->user->avatarUrl())
                    <img src="{{ $c->user->avatarUrl() }}" alt="{{ $c->user->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                  @else
                    {{ strtoupper(substr($c->user->name ?? 'M', 0, 1)) }}
                  @endif
                </div>
              </a>
              <div>
                <a href="{{ $c->user ? route('users.profile', $c->user->id) : '#' }}" style="font-weight: 600; font-size: 0.85rem; color: var(--member-accent); text-decoration: none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                  {{ $c->user->name ?? 'Member' }}:
                </a>
                <span style="font-size: 0.88rem; color: var(--text-main); margin-left: 5px;">{{ $c->comment }}</span>
                <div style="font-size: 0.72rem; color: var(--text-muted); margin-top: 2px;">
                  {{ $c->created_at ? $c->created_at->diffForHumans() : '' }}
                </div>
              </div>
            </div>

            @if(auth()->check() && (auth()->id() === $c->user_id || auth()->user()->role === 'admin'))
              <form action="{{ route('member.comments.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Hapus komentar ini?');" style="margin: 0;">
                @csrf
                @method('DELETE')
                <button type="submit" title="Hapus Komentar" style="background: none; border: none; color: #8b949e; font-size: 0.75rem; cursor: pointer; padding: 2px 4px;">
                  <i class="fa-solid fa-trash-can"></i>
                </button>
              </form>
            @endif
          </div>
        @empty
          <p style="font-size: 0.82rem; color: var(--text-muted); font-style: italic; margin: 0;">Belum ada komentar balasan.</p>
        @endforelse
      </div>

      <!-- Form Komentar -->
      @auth
        <form action="{{ route('member.comments.store', $r->id) }}" method="POST" style="display: flex; gap: 8px; margin-top: 8px;">
          @csrf
          <input type="text" name="comment" placeholder="Tulis komentar balasan..." required style="flex: 1; padding: 7px 12px; background: var(--bg-main); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; font-size: 0.85rem; outline: none;">
          <button type="submit" style="background: var(--member-accent); color: white; border: none; padding: 7px 14px; border-radius: 6px; font-weight: 600; font-size: 0.82rem; cursor: pointer;">Kirim</button>
        </form>
      @else
        <p style="font-size: 0.8rem; color: var(--text-muted); margin: 0;">
          <a href="{{ route('login') }}" style="color: var(--member-accent); text-decoration: underline;">Login</a> untuk membalas ulasan ini.
        </p>
      @endauth
    </div>

  </div>
@empty
  <p style="color: var(--text-muted);">Belum ada ulasan untuk buku ini. Jadilah yang pertama mengulas!</p>
@endforelse
@endsection
