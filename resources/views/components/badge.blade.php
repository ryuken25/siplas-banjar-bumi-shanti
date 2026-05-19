@props([
    'variant' => 'neutral',
    'dot' => true,
    'size' => 'sm',
])

@php
    $variants = [
        'success' => ['bg' => 'bg-emerald-50',  'text' => 'text-emerald-700', 'dot' => 'bg-emerald-500'],
        'warning' => ['bg' => 'bg-amber-50',    'text' => 'text-amber-800',   'dot' => 'bg-amber-500'],
        'danger'  => ['bg' => 'bg-rose-50',     'text' => 'text-rose-700',    'dot' => 'bg-rose-500'],
        'info'    => ['bg' => 'bg-sky-50',      'text' => 'text-sky-700',     'dot' => 'bg-sky-500'],
        'primary' => ['bg' => 'bg-primary-50',  'text' => 'text-primary-700', 'dot' => 'bg-primary-500'],
        'neutral' => ['bg' => 'bg-slate-100',   'text' => 'text-slate-700',   'dot' => 'bg-slate-500'],
        'violet'  => ['bg' => 'bg-violet-50',   'text' => 'text-violet-700',  'dot' => 'bg-violet-500'],
    ];
    $v = $variants[$variant] ?? $variants['neutral'];
    $sizes = [
        'xs' => 'text-[10px] px-2 py-0.5 gap-1',
        'sm' => 'text-xs px-2.5 py-0.5 gap-1.5',
        'md' => 'text-sm px-3 py-1 gap-2',
    ];
@endphp

<span {{ $attributes->class([
    'inline-flex items-center font-medium rounded-full',
    $sizes[$size] ?? $sizes['sm'],
    $v['bg'], $v['text'],
]) }}>
    @if($dot)
        <span class="w-1.5 h-1.5 rounded-full {{ $v['dot'] }} shrink-0"></span>
    @endif
    {{ $slot }}
</span>
