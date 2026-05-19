@props([
    'variant' => 'ghost',
    'size' => 'md',
    'rounded' => 'lg',
    'href' => null,
    'type' => 'button',
    'tooltip' => null,
])

@php
    $sizes = [
        'xs' => 'w-7 h-7 [&_svg]:w-4 [&_svg]:h-4',
        'sm' => 'w-9 h-9 [&_svg]:w-[18px] [&_svg]:h-[18px]',
        'md' => 'w-10 h-10 [&_svg]:w-5 [&_svg]:h-5',
        'lg' => 'w-12 h-12 [&_svg]:w-6 [&_svg]:h-6',
    ];
    $variants = [
        'primary' => 'bg-gradient-to-b from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white shadow-md shadow-primary-500/25',
        'ghost' => 'bg-transparent text-slate-600 hover:bg-slate-100 hover:text-slate-900',
        'soft' => 'bg-slate-100 text-slate-700 hover:bg-slate-200',
        'soft-primary' => 'bg-primary-50 text-primary-700 hover:bg-primary-100',
        'soft-danger' => 'bg-rose-50 text-rose-700 hover:bg-rose-100',
        'danger' => 'bg-gradient-to-b from-rose-500 to-rose-600 text-white hover:from-rose-600 hover:to-rose-700 shadow-md shadow-rose-500/25',
    ];
    $roundedClass = $rounded === 'full' ? 'rounded-full' : 'rounded-lg';
    $classes = collect([
        'group inline-flex items-center justify-center transition-all duration-200 ease-out transform hover:-translate-y-0.5 active:translate-y-0 focus:outline-none focus:ring-4 focus:ring-primary-500/30 disabled:opacity-50 disabled:cursor-not-allowed',
        $roundedClass,
        $sizes[$size] ?? $sizes['md'],
        $variants[$variant] ?? $variants['ghost'],
    ])->filter()->implode(' ');
    $tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }} @if($href) href="{{ $href }}" @else type="{{ $type }}" @endif
    @if($tooltip) title="{{ $tooltip }}" @endif
    {{ $attributes->class($classes) }}>
    {{ $slot }}
</{{ $tag }}>
