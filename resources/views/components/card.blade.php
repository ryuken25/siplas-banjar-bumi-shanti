@props([
    'padding' => 'md',
    'as' => 'div',
    'hover' => false,
])

@php
    $paddings = [
        'none' => '',
        'sm' => 'p-4',
        'md' => 'p-6',
        'lg' => 'p-8',
    ];
    $classes = collect([
        'bg-white rounded-xl border border-slate-200/70 shadow-soft transition',
        $paddings[$padding] ?? $paddings['md'],
        $hover ? 'hover:shadow-md hover:-translate-y-0.5 transition-transform duration-200' : '',
    ])->filter()->implode(' ');
@endphp

<{{ $as }} {{ $attributes->class($classes) }}>
    {{ $slot }}
</{{ $as }}>
