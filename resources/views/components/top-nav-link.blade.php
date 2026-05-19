@props(['active' => false, 'href' => '#'])

@php
    $base = 'relative inline-flex items-center px-3 py-2 text-sm font-medium transition rounded-lg';
    $stateClass = $active
        ? 'text-primary-700 bg-primary-50/60'
        : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100';
@endphp

<a href="{{ $href }}" {{ $attributes->class([$base, $stateClass]) }}>
    {{ $slot }}
    @if($active)
        <span aria-hidden="true" class="absolute left-3 right-3 -bottom-px h-0.5 rounded-full bg-primary-500"></span>
    @endif
</a>
