@props([
    'title' => 'Belum ada data',
    'description' => null,
    'icon' => null,
    'illustration' => null,
])

<div {{ $attributes->class(['text-center py-12 px-6']) }}>
    @if($illustration)
        <div class="mx-auto mb-5 flex justify-center animate-fade-in">
            <x-empty-illustration :variant="$illustration" :size="180" />
        </div>
    @else
        <div class="mx-auto w-20 h-20 rounded-full bg-gradient-to-br from-primary-50 to-primary-100 text-primary-500 flex items-center justify-center mb-4 [&_svg]:w-10 [&_svg]:h-10 ring-4 ring-primary-50/50">
            @if($icon)
                {!! $icon !!}
            @else
                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
            @endif
        </div>
    @endif
    <h3 class="text-lg font-display font-semibold text-slate-900">{{ $title }}</h3>
    @if($description)
        <p class="mt-1 text-sm text-slate-500 max-w-sm mx-auto leading-relaxed">{{ $description }}</p>
    @endif
    @isset($action)
        <div class="mt-5">{{ $action }}</div>
    @endisset
</div>
