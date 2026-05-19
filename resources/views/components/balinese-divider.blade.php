@props(['color' => 'primary'])

@php
    $stroke = $color === 'amber' ? '#D97706' : '#059669';
@endphp

{{-- Subtle Balinese-style ornament divider --}}
<div {{ $attributes->merge(['class' => 'flex items-center justify-center py-2 select-none']) }} aria-hidden="true">
    <svg viewBox="0 0 240 24" xmlns="http://www.w3.org/2000/svg" class="h-4 opacity-50">
        <g fill="none" stroke="{{ $stroke }}" stroke-width="1.5" stroke-linecap="round">
            <line x1="0" y1="12" x2="80" y2="12"/>
            <line x1="160" y1="12" x2="240" y2="12"/>
        </g>
        <g fill="{{ $stroke }}">
            {{-- Center motif: small padma --}}
            <g transform="translate(120 12)">
                @php $rot = [0, 60, 120, 180, 240, 300]; @endphp
                @foreach($rot as $r)
                    <g transform="rotate({{ $r }})"><ellipse cx="0" cy="-7" rx="2.5" ry="5"/></g>
                @endforeach
                <circle cx="0" cy="0" r="2.5" fill="#FFFFFF" stroke="{{ $stroke }}" stroke-width="1.2"/>
            </g>
            <circle cx="92" cy="12" r="2"/>
            <circle cx="148" cy="12" r="2"/>
        </g>
    </svg>
</div>
