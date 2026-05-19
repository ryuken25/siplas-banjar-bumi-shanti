@props([
    'variant' => 'info',
    'title' => null,
    'dismissible' => true,
])

@php
    $variants = [
        'success' => ['bg' => 'bg-emerald-50 border-emerald-200', 'text' => 'text-emerald-900', 'icon' => 'text-emerald-600',
                      'svg' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'],
        'warning' => ['bg' => 'bg-amber-50 border-amber-200', 'text' => 'text-amber-900', 'icon' => 'text-amber-600',
                      'svg' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'],
        'danger'  => ['bg' => 'bg-rose-50 border-rose-200', 'text' => 'text-rose-900', 'icon' => 'text-rose-600',
                      'svg' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>'],
        'info'    => ['bg' => 'bg-sky-50 border-sky-200', 'text' => 'text-sky-900', 'icon' => 'text-sky-600',
                      'svg' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'],
    ];
    $v = $variants[$variant] ?? $variants['info'];
@endphp

<div x-data="{ show: true }" x-show="show" x-transition.duration.250ms
     {{ $attributes->class(['rounded-xl border p-4 flex items-start gap-3 animate-fade-in', $v['bg'], $v['text']]) }}
     role="alert">
    <div class="shrink-0 {{ $v['icon'] }}">{!! $v['svg'] !!}</div>
    <div class="flex-1 text-sm">
        @if($title)
            <p class="font-semibold mb-0.5">{{ $title }}</p>
        @endif
        <div class="leading-relaxed">{{ $slot }}</div>
    </div>
    @if($dismissible)
        <button @click="show = false" type="button" class="shrink-0 rounded-md p-1 hover:bg-black/5 transition" aria-label="Tutup">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    @endif
</div>
