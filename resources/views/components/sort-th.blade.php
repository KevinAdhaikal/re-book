@props([
    'column',
    'current' => '',
    'asc' => null,
    'desc' => null,
    'default' => 'asc', // 'asc' atau 'desc'
    'style' => '',
])

@php
    $ascValue = $asc ?? ($column . '_asc');
    $descValue = $desc ?? ($column . '_desc');
    
    $isActiveAsc = ($current === $ascValue);
    $isActiveDesc = ($current === $descValue);
    $isActive = $isActiveAsc || $isActiveDesc;

    if ($isActiveAsc) {
        $nextSort = $descValue;
        $icon = 'fa-sort-up';
        $iconColor = '#3fb950'; // Admin accent
    } elseif ($isActiveDesc) {
        $nextSort = $ascValue;
        $icon = 'fa-sort-down';
        $iconColor = '#3fb950';
    } else {
        $nextSort = ($default === 'desc') ? $descValue : $ascValue;
        $icon = 'fa-sort';
        $iconColor = 'var(--text-muted)';
    }

    $sortUrl = request()->fullUrlWithQuery([
        'sort' => $nextSort,
        'page' => 1
    ]);
@endphp

<th {{ $attributes->merge(['style' => 'user-select: none; ' . $style]) }}>
  <a href="{{ $sortUrl }}" 
     title="Urutkan berdasarkan {{ $slot }}"
     style="display: inline-flex; align-items: center; gap: 6px; color: {{ $isActive ? '#3fb950' : 'var(--text-main)' }}; text-decoration: none; font-weight: 600; cursor: pointer; transition: color 0.15s ease;">
    <span>{{ $slot }}</span>
    <i class="fa-solid {{ $icon }}" style="color: {{ $iconColor }}; font-size: 0.8rem;"></i>
  </a>
</th>
