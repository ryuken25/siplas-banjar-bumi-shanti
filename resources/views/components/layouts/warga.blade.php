<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#10B981">
    <title>{{ ($title ?? '') ? $title . ' — SIPLAS' : config('app.name') }}</title>
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Ccircle cx='32' cy='32' r='14' fill='%23047857'/%3E%3Cpath d='M 26 38 C 23 33, 25 26, 32 23 C 39 27, 41 33, 38 38 C 35 41, 29 41, 26 38 Z' fill='%23A7F3D0'/%3E%3C/svg%3E">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-app text-slate-900 font-sans antialiased">

    <header class="sticky top-0 z-30 bg-white/85 backdrop-blur-md border-b border-slate-200/70 shadow-soft">
        <div class="container-app">
            <div class="h-16 sm:h-[68px] flex items-center gap-3">
                <a href="{{ route('warga.dashboard') }}" class="shrink-0 transition-transform hover:scale-[1.02]">
                    <x-application-logo :size="40" />
                </a>

                <nav class="hidden md:flex items-center gap-1 ml-6">
                    <x-top-nav-link :href="route('warga.dashboard')" :active="request()->routeIs('warga.dashboard')">Beranda</x-top-nav-link>
                    <x-top-nav-link :href="route('warga.lapor.create')" :active="request()->routeIs('warga.lapor.*') || request()->routeIs('warga.laporan.*')">Lapor Sampah</x-top-nav-link>
                    <x-top-nav-link :href="route('warga.iuran.index')" :active="request()->routeIs('warga.iuran.*')">Iuran Saya</x-top-nav-link>
                    <x-top-nav-link :href="route('notifikasi.index')" :active="request()->routeIs('notifikasi.*')">Notifikasi</x-top-nav-link>
                </nav>

                <div class="flex-1"></div>

                <x-bell-dropdown />
                <x-user-menu />

                <button x-data x-on:click="$dispatch('open-modal', 'warga-mobile-nav')" class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg text-slate-600 hover:bg-slate-100 transition" aria-label="Buka menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>
    </header>

    <x-modal name="warga-mobile-nav" maxWidth="sm" title="Menu">
        <nav class="space-y-1">
            <x-nav-link :href="route('warga.dashboard')" :active="request()->routeIs('warga.dashboard')">Beranda</x-nav-link>
            <x-nav-link :href="route('warga.lapor.create')" :active="request()->routeIs('warga.lapor.*')">Lapor Sampah</x-nav-link>
            <x-nav-link :href="route('warga.laporan.index')" :active="request()->routeIs('warga.laporan.*')">Laporan Saya</x-nav-link>
            <x-nav-link :href="route('warga.iuran.index')" :active="request()->routeIs('warga.iuran.*')">Iuran Saya</x-nav-link>
            <x-nav-link :href="route('notifikasi.index')" :active="request()->routeIs('notifikasi.*')">Notifikasi</x-nav-link>
            <x-nav-link :href="route('profile.edit')">Profil</x-nav-link>
        </nav>
    </x-modal>

    <main class="container-app py-6 sm:py-8 animate-fade-in">
        @isset($header)
            <div class="mb-6 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-display font-bold text-slate-900 text-balance">{{ $header }}</h1>
                    @isset($subheader)
                        <p class="text-slate-500 mt-1">{{ $subheader }}</p>
                    @endisset
                </div>
                @isset($actions)
                    <div class="flex flex-wrap gap-2">{{ $actions }}</div>
                @endisset
            </div>
        @endisset

        {{ $slot }}
    </main>

    <footer class="border-t border-slate-200/70 mt-12">
        <div class="container-app py-8">
            <x-balinese-divider class="mb-6" />
            <div class="flex flex-col sm:flex-row sm:justify-between items-center gap-3 text-sm text-slate-500">
                <span>© {{ date('Y') }} Banjar Bumi Shanti. Dibuat dengan 💚 di Bali.</span>
                <span class="text-xs">SIPLAS — Sistem Informasi Pelaporan Sampah</span>
            </div>
        </div>
    </footer>

    <x-toast-host />
    <x-scroll-top />
</body>
</html>
