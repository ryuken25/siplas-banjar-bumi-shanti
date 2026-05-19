@props([
    'user' => null,
    'src' => null,
    'name' => '',
    'size' => 'md',
    'ring' => false,
])

@php
    $displayName = $user?->name ?? $name;
    $displaySrc = $src ?? $user?->foto_profil_url;
    $sizes = [
        'xs' => 'w-7 h-7 text-[11px]',
        'sm' => 'w-9 h-9 text-xs',
        'md' => 'w-10 h-10 text-sm',
        'lg' => 'w-12 h-12 text-base',
        'xl' => 'w-16 h-16 text-lg',
        '2xl' => 'w-24 h-24 text-2xl',
    ];
    $palette = ['#10B981', '#059669', '#34D399', '#F59E0B', '#0EA5E9', '#6366F1', '#EC4899', '#14B8A6'];
    $color = $palette[abs(crc32($displayName ?: 'U')) % count($palette)];
    $initials = collect(preg_split('/\s+/', trim($displayName ?: 'U')))
        ->filter()
        ->take(2)
        ->map(fn($p) => mb_substr($p, 0, 1))
        ->join('');
@endphp

<div {{ $attributes->class([
    'inline-flex items-center justify-center rounded-full font-semibold text-white overflow-hidden select-none',
    $sizes[$size] ?? $sizes['md'],
    $ring ? 'ring-2 ring-white ring-offset-2 ring-offset-app' : '',
]) }} style="background-color: {{ $color }};">
    @if($displaySrc)
        <img src="{{ $displaySrc }}" alt="{{ $displayName }}" class="w-full h-full object-cover">
    @else
        {{ strtoupper($initials ?: 'U') }}
    @endif
</div>
