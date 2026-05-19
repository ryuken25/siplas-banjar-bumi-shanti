@props(['active' => false, 'href' => '#', 'icon' => null])

@php
    $active = (bool) ($active);
    $base = 'group relative flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150';
    $stateClass = $active
        ? 'bg-gradient-to-r from-primary-50 to-primary-50/40 text-primary-700 shadow-sm'
        : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900';
@endphp

<a href="{{ $href }}" {{ $attributes->class([$base, $stateClass]) }}>
    @if($active)
        <span aria-hidden="true" class="absolute left-0 top-1.5 bottom-1.5 w-1 rounded-r-full bg-primary-500"></span>
    @endif
    @if($icon)
        <span class="shrink-0 [&_svg]:w-5 [&_svg]:h-5 {{ $active ? 'text-primary-600' : 'text-slate-400 group-hover:text-slate-600' }}">
            {!! $icon !!}
        </span>
    @endif
    <span class="truncate">{{ $slot }}</span>
</a>
