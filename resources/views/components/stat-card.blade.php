@props([
    'label' => '',
    'value' => '',
    'icon' => null,
    'trend' => null,
    'trendDirection' => 'up',
    'color' => 'primary',
    'href' => null,
])

@php
    $palette = [
        'primary' => ['bg' => 'bg-primary-50',  'text' => 'text-primary-600',  'ring' => 'ring-primary-100'],
        'amber'   => ['bg' => 'bg-amber-50',    'text' => 'text-amber-600',    'ring' => 'ring-amber-100'],
        'sky'     => ['bg' => 'bg-sky-50',      'text' => 'text-sky-600',      'ring' => 'ring-sky-100'],
        'rose'    => ['bg' => 'bg-rose-50',     'text' => 'text-rose-600',     'ring' => 'ring-rose-100'],
        'violet'  => ['bg' => 'bg-violet-50',   'text' => 'text-violet-600',   'ring' => 'ring-violet-100'],
        'slate'   => ['bg' => 'bg-slate-100',   'text' => 'text-slate-600',    'ring' => 'ring-slate-200'],
    ];
    $c = $palette[$color] ?? $palette['primary'];
    $tag = $href ? 'a' : 'div';
@endphp

<{{ $tag }}
    @if($href) href="{{ $href }}" @endif
    {{ $attributes->class([
        'group relative bg-white rounded-xl border border-slate-200/70 p-5 shadow-soft transition',
        $href ? 'hover:shadow-md hover:-translate-y-0.5 transform duration-200' : '',
    ]) }}>
    <div class="flex items-start justify-between gap-4">
        <div class="min-w-0">
            <p class="text-xs font-medium uppercase tracking-wider text-slate-500">{{ $label }}</p>
            <p class="mt-2 text-3xl font-display font-bold text-slate-900 tabular-nums">{{ $value }}</p>
            @if($trend)
                <p class="mt-2 inline-flex items-center gap-1 text-xs font-medium {{ $trendDirection === 'up' ? 'text-emerald-600' : 'text-rose-600' }}">
                    @if($trendDirection === 'up')
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 17l6-6 4 4 8-8m0 0v6m0-6h-6"/></svg>
                    @else
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7l6 6 4-4 8 8m0 0v-6m0 6h-6"/></svg>
                    @endif
                    <span>{{ $trend }}</span>
                </p>
            @endif
        </div>
        @if($icon)
            <div class="shrink-0 w-12 h-12 rounded-xl {{ $c['bg'] }} {{ $c['text'] }} flex items-center justify-center ring-1 {{ $c['ring'] }} group-hover:scale-110 transition-transform [&_svg]:w-6 [&_svg]:h-6">
                {!! $icon !!}
            </div>
        @endif
    </div>
</{{ $tag }}>
