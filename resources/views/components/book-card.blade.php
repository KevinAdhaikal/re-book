@props([
    'book',
    'isLiked' => false,
    'showLike' => true,
    'badge' => null,
    'badgeColor' => null,
    'actionUrl' => null,
    'actionText' => 'Lihat Ulasan',
    'id' => null,
])

@php
    $cardId = $id ?? 'book-card-' . $book->id;
    $rating = $book->reviews_avg_rating ?? ($book->reviews ? $book->reviews->avg('rating') : 0);
    $ratingCount = $book->reviews_count ?? ($book->reviews ? $book->reviews->count() : 0);
    $likesCount = $book->liked_by_users_count ?? ($book->likedByUsers ? $book->likedByUsers->count() : 0);
    $targetUrl = $actionUrl ?? route('books.show', $book->id);
@endphp

<div id="{{ $cardId }}" {{ $attributes->merge(['class' => 'book-card']) }} style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 10px; overflow: hidden; display: flex; flex-direction: column; position: relative;">

    {{-- Custom Status Badge jika ada (misal: Sedang Dibaca) --}}
    @if($badge)
        <span style="position: absolute; top: 10px; left: 10px; z-index: 10; background: {{ $badgeColor ?? 'rgba(31, 111, 235, 0.9)' }}; color: #fff; font-size: 0.72rem; font-weight: 700; padding: 3px 8px; border-radius: 4px; box-shadow: 0 2px 6px rgba(0,0,0,0.3); backdrop-filter: blur(4px);">
            {{ $badge }}
        </span>
    @endif

    {{-- Tombol di Pojok Kanan Atas Cover (Custom Slot atau Default Love) --}}
    @if(isset($topRight))
        {{ $topRight }}
    @elseif($showLike)
        @auth
            <button type="button" class="btn-like-toggle" data-book-id="{{ $book->id }}" data-action="{{ route('member.likes.toggle', $book->id) }}" title="{{ $isLiked ? 'Batal Sukai' : 'Sukai Buku' }}"
                style="position: absolute; top: 10px; right: 10px; z-index: 10; background-color: rgba(13, 17, 23, 0.88); backdrop-filter: blur(4px); border: 1px solid #30363d; width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; color: {{ $isLiked ? '#F78166' : '#8B949E' }}; font-size: 0.95rem; padding: 0; transition: transform 0.15s ease, color 0.2s ease;">
                <i class="{{ $isLiked ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>
            </button>
        @else
            <a href="{{ route('login') }}" title="Login untuk menyukai buku"
                style="position: absolute; top: 10px; right: 10px; z-index: 10; background-color: rgba(13, 17, 23, 0.88); backdrop-filter: blur(4px); border: 1px solid #30363d; width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #8B949E; text-decoration: none; font-size: 0.95rem;">
                <i class="fa-regular fa-heart"></i>
            </a>
        @endauth
    @endif

    {{-- Book Cover Container with 3D Spine Crease --}}
    <a href="{{ $targetUrl }}" class="book-cover-wrap" style="text-decoration: none; display: block;">
        @if($book->cover)
            <img
                src="{{ asset('storage/' . $book->cover) }}"
                alt="{{ $book->title }}"
                class="book-cover-img"
                onerror="this.onerror=null; this.parentElement.querySelector('.cover-fallback').style.display='flex'; this.style.display='none';">
            <div class="cover-fallback" style="display: none; width: 100%; height: 100%; background: #161b22; flex-direction: column; align-items: center; justify-content: center; gap: 8px; color: var(--text-muted); border-bottom: 1px solid var(--border-color); text-align: center; padding: 15px;">
                <i class="fa-solid fa-book" style="font-size: 2.2rem; color: #30363d;"></i>
                <span style="font-size: 0.8rem; font-weight: 500; color: #8b949e;">{{ Str::limit($book->title, 25) }}</span>
            </div>
        @else
            <div style="width: 100%; height: 100%; background: #161b22; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; color: var(--text-muted); border-bottom: 1px solid var(--border-color); text-align: center; padding: 15px;">
                <i class="fa-solid fa-book" style="font-size: 2.2rem; color: #30363d;"></i>
                <span style="font-size: 0.8rem; font-weight: 500; color: #8b949e;">{{ Str::limit($book->title, 25) }}</span>
            </div>
        @endif
    </a>

    {{-- Card Content --}}
    <div style="padding: 14px; display: flex; flex-direction: column; flex-grow: 1;">
        
        {{-- Meta Row: Rating & Likes --}}
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
            <div style="color: var(--star-rating); font-size: 0.88rem; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                <i class="fa-solid fa-star"></i> {{ number_format($rating ?? 0, 1) }}
                @if($ratingCount > 0)
                    <span style="color: var(--text-muted); font-size: 0.75rem; font-weight: normal;">({{ $ratingCount }})</span>
                @endif
            </div>
            <div style="color: var(--text-muted); font-size: 0.78rem; display: inline-flex; align-items: center; gap: 4px;" title="Jumlah pembaca yang menyukai buku ini">
                <i class="fa-solid fa-heart" style="color: #F78166; font-size: 0.75rem;"></i>
                <span id="book-likes-count-{{ $book->id }}">{{ $likesCount }}</span>
            </div>
        </div>

        {{-- Title --}}
        <h4 style="margin-bottom: 6px; font-size: 0.95rem; font-weight: 700; line-height: 1.35; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 2.6em;" title="{{ $book->title }}">
            <a href="{{ $targetUrl }}" style="color: var(--text-main); text-decoration: none; transition: color 0.15s ease;">
                {{ $book->title }}
            </a>
        </h4>

        {{-- Author & Genre --}}
        <div style="margin-bottom: 14px;">
            <p style="color: var(--text-muted); font-size: 0.78rem; margin-bottom: 6px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                <i class="fa-solid fa-user-pen" style="font-size: 0.72rem; margin-right: 4px;"></i> {{ $book->author }}
            </p>
            <span style="display: inline-block; background: rgba(255, 255, 255, 0.05); border: 1px solid var(--border-color); color: #8B949E; font-size: 0.72rem; padding: 2px 8px; border-radius: 4px;">
                {{ $book->genre }}
            </span>
        </div>

        {{-- Slot tambahan (misal: tombol hapus dari likes, selector shelf, dll) --}}
        {{ $slot }}

        {{-- Default Action Button if slot empty --}}
        @if($slot->isEmpty())
            <a href="{{ $targetUrl }}"
                style="margin-top: auto; background: #21262D; color: #58A6FF; text-align: center; padding: 8px 10px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 600; border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: center; gap: 6px; transition: all 0.2s ease;">
                <span>{{ $actionText }}</span>
                <i class="fa-solid fa-arrow-right" style="font-size: 0.75rem;"></i>
            </a>
        @endif

    </div>
</div>
