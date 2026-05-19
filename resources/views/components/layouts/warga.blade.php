<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ ($title ?? '') ? $title . ' — SIPLAS' : config('app.name') }}</title>
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%2310B981'%3E%3Cpath d='M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z'/%3E%3C/svg%3E">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-app text-slate-900 font-sans antialiased">

    <header class="sticky top-0 z-30 bg-white/85 backdrop-blur-md border-b border-slate-200/70 shadow-soft">
        <div class="container-app">
            <div class="flex h-16 items-center gap-3">
                <a href="{{ route('warga.dashboard') }}" class="shrink-0">
                    <x-application-logo />
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

                <button x-data x-on:click="$dispatch('open-modal', 'warga-mobile-nav')" class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg text-slate-600 hover:bg-slate-100" aria-label="Buka menu">
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

    @if(session('success') || session('error') || session('warning') || session('info'))
        <div class="container-app pt-4 space-y-2">
            @if(session('success')) <x-alert variant="success">{{ session('success') }}</x-alert> @endif
            @if(session('error'))   <x-alert variant="danger">{{ session('error') }}</x-alert> @endif
            @if(session('warning')) <x-alert variant="warning">{{ session('warning') }}</x-alert> @endif
            @if(session('info'))    <x-alert variant="info">{{ session('info') }}</x-alert> @endif
        </div>
    @endif

    <main class="container-app py-6 sm:py-8">
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

    <footer class="border-t border-slate-200/70 py-8 mt-12">
        <div class="container-app flex flex-col sm:flex-row sm:justify-between items-center gap-3 text-sm text-slate-500">
            <span>© {{ date('Y') }} Banjar Bumi Shanti. Dibuat dengan 💚 di Bali.</span>
            <span class="text-xs">SIPLAS — Sistem Informasi Pelaporan Sampah</span>
        </div>
    </footer>
</body>
</html>
