@props([
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'iconPosition' => 'left',
    'loading' => false,
    'fullWidth' => false,
    'href' => null,
    'type' => 'button',
])

@php
    // Accept icon as a string prop OR as named slots <x-slot:iconLeft> / <x-slot:iconRight>.
    $iconHtml = '';
    if ($icon !== null && $icon !== '') {
        $iconHtml = (string) $icon;
    } elseif (isset($iconLeft) && trim((string) $iconLeft) !== '') {
        $iconHtml = (string) $iconLeft;
        $iconPosition = 'left';
    } elseif (isset($iconRight) && trim((string) $iconRight) !== '') {
        $iconHtml = (string) $iconRight;
        $iconPosition = 'right';
    }
    $hasIcon = $iconHtml !== '';

    // Icon size is fixed per button size — applied to ALL descendant svg.
    $sizeClasses = [
        'xs' => 'px-2.5 py-1.5 text-xs gap-1.5 [&_svg]:w-3.5 [&_svg]:h-3.5',
        'sm' => 'px-3.5 py-2 text-sm gap-1.5 [&_svg]:w-4 [&_svg]:h-4',
        'md' => 'px-5 py-2.5 text-sm gap-2 [&_svg]:w-[18px] [&_svg]:h-[18px]',
        'lg' => 'px-6 py-3 text-base gap-2 [&_svg]:w-5 [&_svg]:h-5',
        'xl' => 'px-8 py-4 text-lg gap-2.5 [&_svg]:w-6 [&_svg]:h-6',
    ];

    $variantClasses = [
        'primary'   => 'group bg-gradient-to-b from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 active:from-primary-700 active:to-primary-800 text-white font-semibold shadow-md shadow-primary-500/25 hover:shadow-lg hover:shadow-primary-500/40 active:shadow-sm focus-visible:ring-primary-500/40',
        'secondary' => 'group bg-white border border-primary-300 hover:border-primary-500 text-primary-700 hover:bg-primary-50 font-semibold shadow-soft hover:shadow-md focus-visible:ring-primary-500/30',
        'tertiary'  => 'bg-transparent text-slate-700 hover:bg-slate-100 hover:text-slate-900 font-medium focus-visible:ring-slate-400/30',
        'ghost'     => 'bg-transparent text-slate-600 hover:bg-slate-100 hover:text-slate-900 font-medium focus-visible:ring-slate-400/30',
        'danger'    => 'group bg-gradient-to-b from-rose-500 to-rose-600 hover:from-rose-600 hover:to-rose-700 text-white font-semibold shadow-md shadow-rose-500/30 hover:shadow-lg hover:shadow-rose-500/40 focus-visible:ring-rose-500/30',
        'warning'   => 'group bg-gradient-to-b from-amber-400 to-amber-500 hover:from-amber-500 hover:to-amber-600 text-slate-900 font-semibold shadow-md shadow-amber-500/30 focus-visible:ring-amber-500/30',
        'success'   => 'group bg-gradient-to-b from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-semibold shadow-md shadow-emerald-500/30 focus-visible:ring-emerald-500/30',
        'dark'      => 'group bg-gradient-to-b from-slate-800 to-slate-900 hover:from-slate-900 hover:to-black text-white font-semibold shadow-md shadow-slate-700/30 focus-visible:ring-slate-500/30',
    ];

    $base = 'relative inline-flex items-center justify-center rounded-lg overflow-hidden transition-all duration-200 ease-out transform hover:-translate-y-0.5 active:translate-y-0 focus:outline-none focus-visible:ring-4 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none disabled:hover:shadow-none whitespace-nowrap leading-none';

    $classes = collect([
        $base,
        $sizeClasses[$size] ?? $sizeClasses['md'],
        $variantClasses[$variant] ?? $variantClasses['primary'],
        $fullWidth ? 'w-full' : '',
    ])->filter()->implode(' ');

    $tag = $href ? 'a' : 'button';
    $hasGradientShimmer = in_array($variant, ['primary', 'danger', 'warning', 'success', 'dark']);
@endphp

<{{ $tag }}
    @if($href) href="{{ $loading ? 'javascript:void(0)' : $href }}" @else type="{{ $type }}" @endif
    @if($loading) disabled aria-busy="true" @endif
    {{ $attributes->class($classes) }}
>
    @if($hasGradientShimmer)
        <span aria-hidden="true"
              class="pointer-events-none absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/25 to-transparent transition-transform duration-700 ease-out group-hover:translate-x-full"></span>
    @endif

    @if($loading)
        <svg class="animate-spin shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
        </svg>
    @elseif($hasIcon && $iconPosition === 'left')
        <span class="relative shrink-0 inline-flex">{!! $iconHtml !!}</span>
    @endif

    @if(! $slot->isEmpty())
        <span class="relative {{ $loading ? 'opacity-80' : '' }}">{{ $slot }}</span>
    @endif

    @if(! $loading && $hasIcon && $iconPosition === 'right')
        <span class="relative shrink-0 inline-flex">{!! $iconHtml !!}</span>
    @endif
</{{ $tag }}>
