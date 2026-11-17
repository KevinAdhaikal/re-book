@props([
    'user',
    'size' => 'md',
    'border' => true,
])

@php
    $dimension = match($size) {
        'xs' => '24px',
        'sm' => '30px',
        'lg' => '54px',
        'xl' => '80px',
        '2xl' => '110px',
        default => '42px',
    };
    $fontSize = match($size) {
        'xs' => '0.7rem',
        'sm' => '0.8rem',
        'lg' => '1.3rem',
        'xl' => '2rem',
        '2xl' => '2.5rem',
        default => '1.05rem',
    };
    $name = $user->name ?? 'User';
    $initial = strtoupper(substr($name, 0, 1));
    $avatarUrl = method_exists($user, 'avatarUrl') ? $user->avatarUrl() : null;
@endphp

<div class="user-avatar-wrap" style="width: {{ $dimension }}; height: {{ $dimension }}; border-radius: 50%; overflow: hidden; background: var(--member-accent); color: white; display: inline-flex; align-items: center; justify-content: center; font-size: {{ $fontSize }}; font-weight: 700; flex-shrink: 0; position: relative; {{ $border ? 'border: 1px solid var(--border-color);' : '' }}">
    @if($avatarUrl)
        <img src="{{ $avatarUrl }}" alt="{{ $name }}"
             onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';"
             style="width: 100%; height: 100%; object-fit: cover; display: block;">
        <div style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center; font-size: {{ $fontSize }}; font-weight: bold; color: white;">
            {{ $initial }}
        </div>
    @else
        <span>{{ $initial }}</span>
    @endif
</div>
