@props([
    'label' => null,
    'name' => '',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'helper' => null,
    'error' => null,
    'icon' => null,
    'required' => false,
])

@php
    $errorMessage = $error ?? ($name ? $errors->first($name) : null);
    $hasError = ! empty($errorMessage);
    $inputId = $attributes->get('id') ?: $name;
@endphp

<div class="w-full">
    @if($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-slate-700 mb-1.5">
            {{ $label }}
            @if($required) <span class="text-rose-500">*</span> @endif
        </label>
    @endif

    <div class="relative">
        @if($icon)
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none [&_svg]:w-5 [&_svg]:h-5">{!! $icon !!}</span>
        @endif
        <input
            type="{{ $type }}"
            id="{{ $inputId }}"
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            @if($required) required @endif
            {{ $attributes->class([
                'block w-full rounded-lg border-slate-300 bg-white text-slate-900 placeholder:text-slate-400',
                'shadow-sm transition focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30',
                'disabled:bg-slate-50 disabled:text-slate-500',
                $icon ? 'pl-10' : '',
                $hasError ? '!border-rose-400 !ring-rose-200 focus:!border-rose-500 focus:!ring-rose-300' : '',
            ]) }}
        />
        @if($hasError)
            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-rose-500 pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                </svg>
            </span>
        @endif
    </div>

    @if($hasError)
        <p class="mt-1.5 text-sm text-rose-600 flex items-center gap-1">
            <span aria-hidden="true">⚠️</span>{{ $errorMessage }}
        </p>
    @elseif($helper)
        <p class="mt-1.5 text-sm text-slate-500">{{ $helper }}</p>
    @endif
</div>
