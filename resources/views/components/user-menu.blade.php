@props([])

@php $u = auth()->user(); @endphp

<div x-data="{ open: false }" class="relative">
    <button @click="open = !open" @click.outside="open = false"
            class="flex items-center gap-2 rounded-full hover:bg-slate-100 p-1 pr-3 transition focus:outline-none focus:ring-4 focus:ring-primary-500/30">
        <x-avatar :user="$u" size="sm" />
        <span class="hidden sm:flex flex-col items-start leading-tight pr-1">
            <span class="text-sm font-semibold text-slate-900 line-clamp-1 max-w-[140px]">{{ $u->name }}</span>
            <span class="text-[11px] text-slate-500">{{ $u->role_label }}</span>
        </span>
        <svg class="hidden sm:block w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
    </button>

    <div x-show="open" x-cloak
         x-transition:enter="ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 mt-2 w-56 rounded-xl bg-white shadow-xl border border-slate-200/70 z-40 overflow-hidden origin-top-right">
        <div class="px-4 py-3 border-b border-slate-100">
            <p class="text-sm font-semibold text-slate-900 truncate">{{ $u->name }}</p>
            <p class="text-xs text-slate-500 truncate">{{ $u->email }}</p>
        </div>
        <div class="py-1">
            @php
                $profilRoute = $u->isAdmin() ? route('admin.profil') : ($u->isPetugas() ? route('petugas.profil') : route('profile.edit'));
            @endphp
            <a href="{{ $profilRoute }}" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Profil Saya
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-sm text-rose-600 hover:bg-rose-50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Keluar
                </button>
            </form>
        </div>
    </div>
</div>
