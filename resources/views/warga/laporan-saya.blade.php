<x-layouts.warga :title="'Laporan Saya'">
    <x-slot:header>Laporan Saya</x-slot:header>
    <x-slot:subheader>Pantau riwayat laporan yang Anda kirim ke petugas.</x-slot:subheader>
    <x-slot:actions>
        <x-button :href="route('warga.lapor.create')">+ Buat Laporan Baru</x-button>
    </x-slot:actions>

    <div class="mb-4">
        <x-segmented-filter name="status" :options="$statusOptions" :current="request('status', '')" :route="route('warga.laporan.index')" />
    </div>

    @if($laporan->isEmpty())
        <x-card>
            <x-empty-state title="Belum ada laporan dengan filter ini" description="Coba ubah filter atau buat laporan baru." illustration="laporan">
                <x-slot:action>
                    <x-button :href="route('warga.lapor.create')">Buat Laporan</x-button>
                </x-slot:action>
            </x-empty-state>
        </x-card>
    @else
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($laporan as $l)
                <a href="{{ route('warga.laporan.show', $l->id) }}" class="group">
                    <x-card hover class="!p-0 overflow-hidden h-full flex flex-col">
                        <div class="aspect-[4/3] bg-slate-100 overflow-hidden relative">
                            <img src="{{ $l->foto_url }}" alt="{{ $l->kode_laporan }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute top-2 right-2">
                                <x-status-badge :status="$l->status" />
                            </div>
                        </div>
                        <div class="p-4 flex-1 flex flex-col">
                            <p class="text-xs text-slate-500">{{ $l->kode_laporan }}</p>
                            <p class="font-semibold text-slate-900 mt-1">{{ $l->jenis_icon }} {{ $l->jenis_label }}</p>
                            <p class="text-sm text-slate-500 line-clamp-2 mt-1">{{ $l->lokasi_text }}</p>
                            <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-100">
                                <p class="text-xs text-slate-400">{{ $l->tanggal_lapor?->translatedFormat('d M Y') }}</p>
                                <span class="text-xs font-medium text-primary-700 group-hover:text-primary-800">Detail →</span>
                            </div>
                        </div>
                    </x-card>
                </a>
            @endforeach
        </div>

        <div>{{ $laporan->links() }}</div>
    @endif
</x-layouts.warga>
