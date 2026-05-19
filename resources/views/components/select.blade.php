@props([
    'label' => null,
    'name' => '',
    'options' => [],
    'value' => '',
    'placeholder' => 'Pilih...',
    'helper' => null,
    'error' => null,
    'required' => false,
])

@php
    $errorMessage = $error ?? ($name ? $errors->first($name) : null);
    $hasError = ! empty($errorMessage);
    $inputId = $attributes->get('id') ?: $name;
    $currentValue = old($name, $value);
@endphp

<div class="w-full">
    @if($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-slate-700 mb-1.5">
            {{ $label }}
            @if($required) <span class="text-rose-500">*</span> @endif
        </label>
    @endif
    <select
        id="{{ $inputId }}"
        name="{{ $name }}"
        @if($required) required @endif
        {{ $attributes->class([
            'block w-full rounded-lg border-slate-300 bg-white text-slate-900',
            'shadow-sm transition focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30',
            $hasError ? '!border-rose-400 !ring-rose-200' : '',
        ]) }}
    >
        @if($placeholder)
            <option value="" {{ $currentValue === '' ? 'selected' : '' }}>{{ $placeholder }}</option>
        @endif
        @foreach($options as $key => $label)
            <option value="{{ $key }}" {{ (string)$currentValue === (string)$key ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
    @if($hasError)
        <p class="mt-1.5 text-sm text-rose-600 flex items-center gap-1">⚠️ {{ $errorMessage }}</p>
    @elseif($helper)
        <p class="mt-1.5 text-sm text-slate-500">{{ $helper }}</p>
    @endif
</div>
