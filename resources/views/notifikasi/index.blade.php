@php
    $layout = auth()->user()->isWarga() ? 'layouts.warga' : 'layouts.dashboard';
@endphp

<x-dynamic-component :component="$layout" :title="'Notifikasi'">
    <x-slot:header>Notifikasi</x-slot:header>
    <x-slot:subheader>Riwayat lengkap pemberitahuan dari sistem.</x-slot:subheader>
    <x-slot:actions>
        @if(auth()->user()->unreadNotifications->count())
            <form method="POST" action="{{ route('notifikasi.mark-all') }}">
                @csrf
                <x-button variant="secondary" type="submit">Tandai semua sebagai dibaca</x-button>
            </form>
        @endif
    </x-slot:actions>

    @if($notifications->isEmpty())
        <x-card>
            <x-empty-state title="Belum ada notifikasi" description="Pemberitahuan dari sistem akan muncul di sini." />
        </x-card>
    @else
        <x-card class="!p-0">
            <ul class="divide-y divide-slate-100">
                @foreach($notifications as $n)
                    @php
                        $data = $n->data;
                        $iconMap = [
                            'success' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700'],
                            'warning' => ['bg' => 'bg-amber-100',   'text' => 'text-amber-700'],
                            'danger'  => ['bg' => 'bg-rose-100',    'text' => 'text-rose-700'],
                            'info'    => ['bg' => 'bg-sky-100',     'text' => 'text-sky-700'],
                            'primary' => ['bg' => 'bg-primary-100', 'text' => 'text-primary-700'],
                        ];
                        $iconCfg = $iconMap[$data['icon'] ?? 'info'] ?? $iconMap['info'];
                    @endphp
                    <li>
                        <a href="{{ route('notifikasi.open', $n->id) }}" class="flex gap-4 p-5 hover:bg-slate-50 transition {{ $n->read_at ? '' : 'bg-primary-50/30' }}">
                            <span class="shrink-0 w-11 h-11 rounded-full flex items-center justify-center {{ $iconCfg['bg'] }} {{ $iconCfg['text'] }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </span>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start gap-2">
                                    <p class="font-semibold text-slate-900 flex-1">{{ $data['title'] ?? 'Notifikasi' }}</p>
                                    @if(! $n->read_at)
                                        <x-badge variant="primary" size="xs">Baru</x-badge>
                                    @endif
                                </div>
                                @if(! empty($data['body']))
                                    <p class="text-sm text-slate-600 mt-1 leading-relaxed">{{ $data['body'] }}</p>
                                @endif
                                <p class="text-xs text-slate-400 mt-2">{{ $n->created_at->translatedFormat('d M Y, H:i') }} · {{ $n->created_at->diffForHumans() }}</p>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </x-card>

        <div>{{ $notifications->links() }}</div>
    @endif
</x-dynamic-component>
