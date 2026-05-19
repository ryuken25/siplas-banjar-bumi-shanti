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
<body class="min-h-screen bg-app text-slate-900 font-sans antialiased"
      x-data="{ sidebar: false }">

    {{-- Mobile sidebar backdrop --}}
    <div x-show="sidebar" x-cloak @click="sidebar = false"
         x-transition.opacity.duration.200ms
         class="lg:hidden fixed inset-0 z-30 bg-slate-900/50 backdrop-blur-sm"></div>

    {{-- Sidebar --}}
    <aside :class="sidebar ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           class="fixed top-0 left-0 z-40 w-64 h-screen bg-white border-r border-slate-200 transition-transform duration-200 flex flex-col">
        <div class="h-16 sm:h-[68px] px-5 flex items-center border-b border-slate-100">
            <a href="@if(auth()->user()?->isAdmin()){{ route('admin.dashboard') }}@else{{ route('petugas.dashboard') }}@endif" class="transition-transform hover:scale-[1.02]">
                <x-application-logo :size="40" />
            </a>
        </div>

        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto scrollbar-thin">
            @if(auth()->user()?->isAdmin())
                @php $adminIcon = fn($p) => '<svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="'.$p.'"/></svg>'; @endphp
                <p class="px-3 pt-2 pb-1 text-[10px] font-semibold uppercase tracking-wider text-slate-400">Utama</p>
                <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')"
                            :icon="$adminIcon('M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6')">
                    Dashboard
                </x-nav-link>

                <p class="px-3 pt-4 pb-1 text-[10px] font-semibold uppercase tracking-wider text-slate-400">Manajemen</p>
                <x-nav-link :href="route('admin.pengguna.index')" :active="request()->routeIs('admin.pengguna.*')"
                            :icon="$adminIcon('M12 4.354a4 4 0 110 5.292V12H5.698M12 4.354A4 4 0 0114 12v0a4 4 0 01-2 7.292V12H6.698')">
                    Manajemen Pengguna
                </x-nav-link>
                <x-nav-link :href="route('admin.petugas.index')" :active="request()->routeIs('admin.petugas.*')"
                            :icon="$adminIcon('M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z')">
                    Manajemen Petugas
                </x-nav-link>
                <x-nav-link :href="route('admin.laporan.index')" :active="request()->routeIs('admin.laporan.*')"
                            :icon="$adminIcon('M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z')">
                    Laporan Sampah
                </x-nav-link>

                <p class="px-3 pt-4 pb-1 text-[10px] font-semibold uppercase tracking-wider text-slate-400">Iuran</p>
                <x-nav-link :href="route('admin.iuran.index')" :active="request()->routeIs('admin.iuran.*')"
                            :icon="$adminIcon('M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z')">
                    Iuran & Tagihan
                </x-nav-link>
                <x-nav-link :href="route('admin.tarif.index')" :active="request()->routeIs('admin.tarif.*')"
                            :icon="$adminIcon('M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3')">
                    Tarif Iuran
                </x-nav-link>

                <p class="px-3 pt-4 pb-1 text-[10px] font-semibold uppercase tracking-wider text-slate-400">Akun</p>
                <x-nav-link :href="route('notifikasi.index')" :active="request()->routeIs('notifikasi.*')"
                            :icon="$adminIcon('M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9a6 6 0 00-12 0v.75A8.967 8.967 0 013.69 15.77c1.733.64 3.56 1.085 5.455 1.31')">
                    Notifikasi
                </x-nav-link>
                <x-nav-link :href="route('admin.profil')" :active="request()->routeIs('admin.profil')"
                            :icon="$adminIcon('M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z')">
                    Profil
                </x-nav-link>
            @elseif(auth()->user()?->isPetugas())
                @php $petugasIcon = fn($p) => '<svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="'.$p.'"/></svg>'; @endphp
                <p class="px-3 pt-2 pb-1 text-[10px] font-semibold uppercase tracking-wider text-slate-400">Utama</p>
                <x-nav-link :href="route('petugas.dashboard')" :active="request()->routeIs('petugas.dashboard')"
                            :icon="$petugasIcon('M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6')">
                    Dashboard
                </x-nav-link>

                <p class="px-3 pt-4 pb-1 text-[10px] font-semibold uppercase tracking-wider text-slate-400">Operasional</p>
                <x-nav-link :href="route('petugas.laporan.index')" :active="request()->routeIs('petugas.laporan.*')"
                            :icon="$petugasIcon('M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z')">
                    Laporan Sampah
                </x-nav-link>
                <x-nav-link :href="route('petugas.iuran.index')" :active="request()->routeIs('petugas.iuran.*')"
                            :icon="$petugasIcon('M9 12l2 2 4-4M21 12a9 9 0 11-18 0 9 9 0 0118 0z')">
                    Verifikasi Iuran
                </x-nav-link>

                <p class="px-3 pt-4 pb-1 text-[10px] font-semibold uppercase tracking-wider text-slate-400">Akun</p>
                <x-nav-link :href="route('notifikasi.index')" :active="request()->routeIs('notifikasi.*')"
                            :icon="$petugasIcon('M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9a6 6 0 00-12 0v.75A8.967 8.967 0 013.69 15.77c1.733.64 3.56 1.085 5.455 1.31')">
                    Notifikasi
                </x-nav-link>
                <x-nav-link :href="route('petugas.profil')" :active="request()->routeIs('petugas.profil')"
                            :icon="$petugasIcon('M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z')">
                    Profil
                </x-nav-link>
            @endif
        </nav>

        <div class="px-3 py-3 border-t border-slate-100 bg-gradient-to-b from-white to-slate-50">
            <div class="flex items-center gap-3 px-2">
                <x-avatar :user="auth()->user()" size="sm" :ring="true" />
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-500 truncate">{{ auth()->user()->role_label }}</p>
                </div>
            </div>
        </div>
    </aside>

    {{-- Topbar --}}
    <header class="lg:pl-64 sticky top-0 z-20 bg-white/85 backdrop-blur-md border-b border-slate-200/70">
        <div class="h-16 sm:h-[68px] px-4 sm:px-6 flex items-center gap-3">
            <button @click="sidebar = true" class="lg:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg text-slate-600 hover:bg-slate-100 transition" aria-label="Buka menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>

            @isset($breadcrumb)
                <div class="hidden sm:block">{{ $breadcrumb }}</div>
            @else
                <div class="hidden sm:block text-sm text-slate-500">{{ $title ?? '' }}</div>
            @endisset

            <div class="flex-1"></div>

            <x-bell-dropdown />
            <x-user-menu />
        </div>
    </header>

    {{-- Main --}}
    <main class="lg:pl-64 animate-fade-in">
        <div class="px-4 sm:px-6 py-6">
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
        </div>
    </main>

    <x-toast-host />
    <x-scroll-top />
</body>
</html>
