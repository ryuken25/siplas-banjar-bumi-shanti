@props([
    'name',
    'maxWidth' => '2xl',
    'title' => null,
    'closeable' => true,
])

@php
    $maxWidthClasses = [
        'sm' => 'sm:max-w-sm',
        'md' => 'sm:max-w-md',
        'lg' => 'sm:max-w-lg',
        'xl' => 'sm:max-w-xl',
        '2xl' => 'sm:max-w-2xl',
        '3xl' => 'sm:max-w-3xl',
        '4xl' => 'sm:max-w-4xl',
    ][$maxWidth] ?? 'sm:max-w-2xl';
@endphp

<div x-data="{ show: false }"
     x-on:open-modal.window="$event.detail == '{{ $name }}' ? show = true : null"
     x-on:close-modal.window="$event.detail == '{{ $name }}' ? show = false : null"
     x-on:close.stop="show = false"
     x-on:keydown.escape.window="@if($closeable) show = false @endif"
     x-show="show"
     class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0"
     style="display: none;">

    <div x-show="show" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-all"
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         @if($closeable) @click="show = false" @endif>
    </div>

    <div x-show="show"
         x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         class="relative mx-auto bg-white rounded-2xl shadow-xl transform transition-all w-full sm:mx-auto {{ $maxWidthClasses }}">

        @if($title || $closeable)
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <h3 class="text-lg font-display font-semibold text-slate-900">{{ $title }}</h3>
                @if($closeable)
                    <button type="button" @click="show = false"
                            class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition" aria-label="Tutup">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                @endif
            </div>
        @endif

        <div class="px-6 py-5">
            {{ $slot }}
        </div>

        @isset($footer)
            <div class="px-6 py-4 bg-slate-50 rounded-b-2xl border-t border-slate-100 flex items-center justify-end gap-2">
                {{ $footer }}
            </div>
        @endisset
    </div>
</div>
