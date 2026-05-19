@props(['size' => 40, 'showText' => true, 'theme' => 'light'])

@php
    $textColorMain = $theme === 'dark' ? 'text-white' : 'text-slate-900';
    $textColorSub  = $theme === 'dark' ? 'text-white/70' : 'text-slate-500';
@endphp

<div {{ $attributes->merge(['class' => 'inline-flex items-center gap-2.5 select-none']) }}>
    {{-- Padma 8-petal logo (Balinese-inspired emblem with eco leaf inside) --}}
    <svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"
         style="width: {{ $size }}px; height: {{ $size }}px;" class="drop-shadow-sm">
        <defs>
            <linearGradient id="siplasPetal-{{ $size }}" x1="50%" y1="0%" x2="50%" y2="100%">
                <stop offset="0%"  stop-color="#34D399"/>
                <stop offset="55%" stop-color="#10B981"/>
                <stop offset="100%" stop-color="#047857"/>
            </linearGradient>
            <linearGradient id="siplasCenter-{{ $size }}" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%"  stop-color="#065F46"/>
                <stop offset="100%" stop-color="#022C22"/>
            </linearGradient>
            <linearGradient id="siplasLeaf-{{ $size }}" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%"  stop-color="#A7F3D0"/>
                <stop offset="100%" stop-color="#FFFFFF"/>
            </linearGradient>
            <radialGradient id="siplasGold-{{ $size }}" cx="50%" cy="50%" r="50%">
                <stop offset="0%" stop-color="#FBBF24"/>
                <stop offset="100%" stop-color="#D97706"/>
            </radialGradient>
        </defs>

        {{-- 8 petals (Asta Dik / 8 cardinal directions — Balinese Hindu motif) --}}
        <g fill="url(#siplasPetal-{{ $size }})">
            @php
                $rotations = [0, 45, 90, 135, 180, 225, 270, 315];
            @endphp
            @foreach($rotations as $r)
                <g transform="rotate({{ $r }} 32 32)">
                    <path d="M32 4
                             C 27 12, 27 18, 32 26
                             C 37 18, 37 12, 32 4 Z"/>
                </g>
            @endforeach
        </g>

        {{-- Center disc (representing center of the universe / kahyangan) --}}
        <circle cx="32" cy="32" r="14" fill="url(#siplasCenter-{{ $size }})"/>
        <circle cx="32" cy="32" r="14" fill="none" stroke="#FFFFFF" stroke-width="1.5" stroke-opacity="0.95"/>

        {{-- Eco leaf inside --}}
        <path d="M 26 38
                 C 23 33, 25 26, 32 23
                 C 39 27, 41 33, 38 38
                 C 35 41, 29 41, 26 38 Z"
              fill="url(#siplasLeaf-{{ $size }})"/>
        {{-- Leaf vein --}}
        <path d="M 32 23 C 32 30, 32 36, 32 40"
              stroke="#047857" stroke-width="1.4" stroke-linecap="round" fill="none" opacity="0.7"/>
        <path d="M 32 28 L 28.5 30.5 M 32 32 L 28.5 34 M 32 28 L 35.5 30.5 M 32 32 L 35.5 34"
              stroke="#047857" stroke-width="0.9" stroke-linecap="round" fill="none" opacity="0.55"/>

        {{-- Tiny gold accent dot (sentuhan emas Bali) --}}
        <circle cx="32" cy="21" r="1.4" fill="url(#siplasGold-{{ $size }})"/>
    </svg>

    @if($showText)
        <span class="flex flex-col leading-none">
            <span class="font-display font-extrabold tracking-tight {{ $textColorMain }}" style="font-size: {{ max(14, round($size * 0.40)) }}px;">
                SIPLAS
            </span>
            <span class="font-medium uppercase tracking-[0.12em] {{ $textColorSub }} mt-1" style="font-size: {{ max(9, round($size * 0.22)) }}px;">
                Banjar Bumi Shanti
            </span>
        </span>
    @endif
</div>
