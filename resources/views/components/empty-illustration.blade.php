@props(['variant' => 'empty', 'size' => 160])

@php
    $size = (int) $size;
@endphp

@switch($variant)
    @case('laporan')
        <svg viewBox="0 0 200 160" xmlns="http://www.w3.org/2000/svg" style="width: {{ $size }}px; height: auto;">
            <defs>
                <linearGradient id="ei-l-g" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#ECFDF5"/><stop offset="100%" stop-color="#D1FAE5"/>
                </linearGradient>
            </defs>
            <ellipse cx="100" cy="138" rx="78" ry="8" fill="#E2E8F0" opacity="0.7"/>
            <rect x="50" y="30" width="100" height="100" rx="12" fill="url(#ei-l-g)" stroke="#10B981" stroke-width="2"/>
            <line x1="62" y1="56" x2="138" y2="56" stroke="#10B981" stroke-width="3" stroke-linecap="round" opacity="0.7"/>
            <line x1="62" y1="72" x2="120" y2="72" stroke="#10B981" stroke-width="3" stroke-linecap="round" opacity="0.5"/>
            <line x1="62" y1="88" x2="130" y2="88" stroke="#10B981" stroke-width="3" stroke-linecap="round" opacity="0.5"/>
            <line x1="62" y1="104" x2="110" y2="104" stroke="#10B981" stroke-width="3" stroke-linecap="round" opacity="0.3"/>
            <circle cx="148" cy="40" r="16" fill="#FBBF24"/>
            <path d="M148 32v8l5 3" stroke="#fff" stroke-width="2.5" stroke-linecap="round" fill="none"/>
        </svg>
        @break
    @case('iuran')
        <svg viewBox="0 0 200 160" xmlns="http://www.w3.org/2000/svg" style="width: {{ $size }}px; height: auto;">
            <defs>
                <linearGradient id="ei-i-g" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#FEF3C7"/><stop offset="100%" stop-color="#FDE68A"/>
                </linearGradient>
            </defs>
            <ellipse cx="100" cy="138" rx="78" ry="8" fill="#E2E8F0" opacity="0.7"/>
            <rect x="40" y="50" width="120" height="68" rx="10" fill="url(#ei-i-g)" stroke="#F59E0B" stroke-width="2"/>
            <rect x="50" y="60" width="100" height="14" rx="3" fill="#F59E0B" opacity="0.6"/>
            <circle cx="68" cy="98" r="8" fill="#F59E0B"/>
            <text x="68" y="102" text-anchor="middle" font-family="Inter" font-weight="700" font-size="9" fill="#fff">Rp</text>
            <line x1="84" y1="92" x2="142" y2="92" stroke="#92400E" stroke-width="2.5" stroke-linecap="round"/>
            <line x1="84" y1="102" x2="120" y2="102" stroke="#92400E" stroke-width="2.5" stroke-linecap="round" opacity="0.6"/>
        </svg>
        @break
    @case('notif')
        <svg viewBox="0 0 200 160" xmlns="http://www.w3.org/2000/svg" style="width: {{ $size }}px; height: auto;">
            <defs>
                <linearGradient id="ei-n-g" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#DBEAFE"/><stop offset="100%" stop-color="#BFDBFE"/>
                </linearGradient>
            </defs>
            <ellipse cx="100" cy="138" rx="78" ry="8" fill="#E2E8F0" opacity="0.7"/>
            <path d="M100 30 C 80 30 70 45 70 65 V 90 L 60 105 H 140 L 130 90 V 65 C 130 45 120 30 100 30 Z" fill="url(#ei-n-g)" stroke="#3B82F6" stroke-width="2"/>
            <path d="M90 110 C 90 116 95 120 100 120 C 105 120 110 116 110 110" fill="none" stroke="#3B82F6" stroke-width="2" stroke-linecap="round"/>
            <circle cx="120" cy="40" r="10" fill="#EF4444"/>
            <text x="120" y="44" text-anchor="middle" font-family="Inter" font-weight="700" font-size="11" fill="#fff">0</text>
        </svg>
        @break
    @default
        <svg viewBox="0 0 200 160" xmlns="http://www.w3.org/2000/svg" style="width: {{ $size }}px; height: auto;">
            <defs>
                <linearGradient id="ei-e-g" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#F1F5F9"/><stop offset="100%" stop-color="#E2E8F0"/>
                </linearGradient>
            </defs>
            <ellipse cx="100" cy="138" rx="70" ry="7" fill="#E2E8F0" opacity="0.7"/>
            <rect x="50" y="40" width="100" height="80" rx="10" fill="url(#ei-e-g)" stroke="#94A3B8" stroke-width="2" stroke-dasharray="4 4"/>
            <circle cx="100" cy="80" r="20" fill="#CBD5E1"/>
            <path d="M93 78 L98 83 L108 73" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
        </svg>
@endswitch
