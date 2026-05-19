@props([
    'label' => null,
    'name' => '',
    'value' => '',
    'placeholder' => '',
    'helper' => null,
    'error' => null,
    'rows' => 4,
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
    <textarea
        id="{{ $inputId }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        {{ $attributes->class([
            'block w-full rounded-lg border-slate-300 bg-white text-slate-900 placeholder:text-slate-400 leading-relaxed',
            'shadow-sm transition focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30',
            $hasError ? '!border-rose-400 !ring-rose-200 focus:!border-rose-500 focus:!ring-rose-300' : '',
        ]) }}
    >{{ old($name, $value) }}</textarea>
    @if($hasError)
        <p class="mt-1.5 text-sm text-rose-600 flex items-center gap-1">⚠️ {{ $errorMessage }}</p>
    @elseif($helper)
        <p class="mt-1.5 text-sm text-slate-500">{{ $helper }}</p>
    @endif
</div>
