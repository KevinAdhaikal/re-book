@props([
    'rating' => 0,
    'max' => 5,
    'showScore' => true,
    'reviewsCount' => null,
    'size' => 'sm',
])

@php
    $score = floatval($rating);
    $fullStars = floor($score);
    $hasHalf = ($score - $fullStars) >= 0.5;
    $emptyStars = max(0, $max - $fullStars - ($hasHalf ? 1 : 0));
    
    $fontSize = match($size) {
        'xs' => '0.75rem',
        'md' => '1.05rem',
        'lg' => '1.25rem',
        default => '0.88rem',
    };
@endphp

<div class="star-rating-wrapper" style="display: inline-flex; align-items: center; gap: 4px; font-size: {{ $fontSize }};">
    <div style="color: var(--star-rating); display: inline-flex; align-items: center; gap: 2px;">
        @for($i = 0; $i < $fullStars; $i++)
            <i class="fa-solid fa-star"></i>
        @endfor

        @if($hasHalf)
            <i class="fa-solid fa-star-half-stroke"></i>
        @endif

        @for($i = 0; $i < $emptyStars; $i++)
            <i class="fa-regular fa-star" style="opacity: 0.4;"></i>
        @endfor
    </div>

    @if($showScore)
        <span style="font-weight: 700; color: var(--star-rating); margin-left: 2px;">
            {{ number_format($score, 1) }}
        </span>
    @endif

    @if(!is_null($reviewsCount))
        <span style="color: var(--text-muted); font-size: 0.85em; font-weight: normal;">
            ({{ $reviewsCount }})
        </span>
    @endif
</div>
