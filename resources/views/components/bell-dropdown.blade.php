@props([])

@php
    $unreadCount = auth()->check() ? auth()->user()->unreadNotifications()->count() : 0;
    $recent = auth()->check() ? auth()->user()->notifications()->take(6)->get() : collect();
@endphp

<div x-data="{
        open: false,
        unread: {{ $unreadCount }},
        items: @js($recent->map(function($n) {
            $data = $n->data;
            return [
                'id'    => $n->id,
                'title' => $data['title'] ?? 'Notifikasi',
                'body'  => $data['body']  ?? '',
                'url'   => $data['url']   ?? '#',
                'icon'  => $data['icon']  ?? 'info',
                'time'  => $n->created_at->diffForHumans(),
                'read'  => (bool) $n->read_at,
            ];
        })->values()),
        async refresh() {
            try {
                const r = await fetch('{{ route('notifikasi.unread.count') }}', { headers: { 'Accept': 'application/json' }});
                if (r.ok) {
                    const d = await r.json();
                    this.unread = d.count ?? this.unread;
                }
            } catch (e) {}
        }
     }"
     x-init="setInterval(() => refresh(), 30000)"
     class="relative">

    <button @click="open = !open" @click.outside="open = false"
            class="relative inline-flex items-center justify-center w-10 h-10 rounded-full text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition focus:outline-none focus:ring-4 focus:ring-primary-500/30"
            aria-label="Notifikasi">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
        </svg>
        <span x-show="unread > 0" x-cloak
              class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 rounded-full bg-rose-500 text-white text-[10px] font-bold flex items-center justify-center ring-2 ring-white"
              x-text="unread > 99 ? '99+' : unread"></span>
    </button>

    <div x-show="open" x-cloak
         x-transition:enter="ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 mt-2 w-[22rem] sm:w-96 origin-top-right rounded-xl bg-white shadow-xl border border-slate-200/70 z-40 overflow-hidden">

        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100">
            <h3 class="font-display font-semibold text-slate-900">Notifikasi</h3>
            <form method="POST" action="{{ route('notifikasi.mark-all') }}">@csrf
                <button type="submit" class="text-xs font-medium text-primary-700 hover:text-primary-800">Tandai semua dibaca</button>
            </form>
        </div>

        <div class="max-h-96 overflow-y-auto scrollbar-thin">
            @if($recent->isEmpty())
                <div class="px-4 py-8 text-center text-sm text-slate-500">
                    <div class="mx-auto w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mb-2 text-slate-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9a6 6 0 00-12 0v.75A8.967 8.967 0 013.69 15.77c1.733.64 3.56 1.085 5.455 1.31"/></svg>
                    </div>
                    Belum ada notifikasi
                </div>
            @else
                @foreach($recent as $n)
                    @php
                        $data = $n->data;
                        $url = $data['url'] ?? '#';
                        $title = $data['title'] ?? 'Notifikasi';
                        $body = $data['body'] ?? '';
                        $icon = $data['icon'] ?? 'info';
                        $iconMap = [
                            'success' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700'],
                            'warning' => ['bg' => 'bg-amber-100',   'text' => 'text-amber-700'],
                            'danger'  => ['bg' => 'bg-rose-100',    'text' => 'text-rose-700'],
                            'info'    => ['bg' => 'bg-sky-100',     'text' => 'text-sky-700'],
                            'primary' => ['bg' => 'bg-primary-100', 'text' => 'text-primary-700'],
                        ];
                        $iconCfg = $iconMap[$icon] ?? $iconMap['info'];
                    @endphp
                    <a href="{{ route('notifikasi.open', $n->id) }}"
                       class="flex gap-3 px-4 py-3 hover:bg-slate-50 border-b border-slate-50 last:border-0 transition {{ $n->read_at ? '' : 'bg-primary-50/40' }}">
                        <span class="shrink-0 w-9 h-9 rounded-full flex items-center justify-center {{ $iconCfg['bg'] }} {{ $iconCfg['text'] }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-slate-900 line-clamp-1">{{ $title }}</p>
                            @if($body) <p class="text-xs text-slate-600 line-clamp-2 mt-0.5">{{ $body }}</p> @endif
                            <p class="text-[11px] text-slate-400 mt-1">{{ $n->created_at->diffForHumans() }}</p>
                        </div>
                        @if(! $n->read_at)
                            <span class="shrink-0 mt-1 w-2 h-2 rounded-full bg-primary-500" aria-hidden="true"></span>
                        @endif
                    </a>
                @endforeach
            @endif
        </div>

        <a href="{{ route('notifikasi.index') }}" class="block px-4 py-3 text-center text-sm font-medium text-primary-700 hover:bg-primary-50 border-t border-slate-100 transition">
            Lihat semua notifikasi →
        </a>
    </div>
</div>
