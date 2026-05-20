<x-layouts.warga :title="'Detail Laporan'">
    <x-slot:header>{{ $laporan->kode_laporan }}</x-slot:header>
    <x-slot:subheader>Detail laporan dan riwayat status.</x-slot:subheader>
    <x-slot:actions>
        <x-button :href="route('warga.laporan.index')" variant="tertiary">← Kembali</x-button>
    </x-slot:actions>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-5">
            <x-card class="!p-0 overflow-hidden">
                <img src="{{ $laporan->foto_url }}" alt="Foto laporan" class="w-full max-h-[480px] object-cover">
            </x-card>

            <x-card>
                <h3 class="font-display font-semibold text-slate-900">Detail Laporan</h3>
                <dl class="mt-4 grid sm:grid-cols-2 gap-y-3 gap-x-6 text-sm">
                    <div>
                        <dt class="text-slate-500">Jenis Sampah</dt>
                        <dd class="font-medium text-slate-900 mt-0.5">{{ $laporan->jenis_icon }} {{ $laporan->jenis_label }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">Status</dt>
                        <dd class="mt-0.5"><x-status-badge :status="$laporan->status" /></dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-slate-500">Lokasi</dt>
                        <dd class="font-medium text-slate-900 mt-0.5">{{ $laporan->lokasi_text }}</dd>
                    </div>
                    @if($laporan->latitude && $laporan->longitude)
                        <div class="sm:col-span-2">
                            <dt class="text-slate-500">Titik Lokasi</dt>
                            <dd class="mt-1">
                                <span class="font-mono text-xs text-slate-500">{{ number_format($laporan->latitude, 6) }}, {{ number_format($laporan->longitude, 6) }}</span>
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $laporan->latitude }},{{ $laporan->longitude }}"
                                   target="_blank" rel="noopener"
                                   class="ml-2 inline-flex items-center gap-1 text-xs font-semibold text-primary-700 hover:text-primary-800">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Cek di Google Maps
                                </a>
                            </dd>
                        </div>
                    @endif
                    <div class="sm:col-span-2">
                        <dt class="text-slate-500">Keterangan</dt>
                        <dd class="text-slate-700 mt-0.5 leading-relaxed">{{ $laporan->keterangan }}</dd>
                    </div>
                    @if($laporan->petugas)
                        <div>
                            <dt class="text-slate-500">Petugas Penanggung Jawab</dt>
                            <dd class="font-medium text-slate-900 mt-0.5">{{ $laporan->petugas->name }}</dd>
                        </div>
                    @endif
                    @if($laporan->alasan_tolak)
                        <div class="sm:col-span-2">
                            <dt class="text-slate-500">Alasan Penolakan</dt>
                            <dd class="text-rose-700 mt-0.5">{{ $laporan->alasan_tolak }}</dd>
                        </div>
                    @endif
                </dl>
            </x-card>
        </div>

        <div>
            <x-card>
                <h3 class="font-display font-semibold text-slate-900 mb-4">Timeline Status</h3>
                @php
                    $items = [
                        ['title' => 'Dikirim', 'time' => $laporan->tanggal_lapor?->translatedFormat('d M Y, H:i'), 'color' => 'sky', 'active' => $laporan->status === 'dikirim'],
                    ];
                    if ($laporan->tanggal_diterima || in_array($laporan->status, ['diterima', 'diproses', 'selesai'])) {
                        $items[] = ['title' => 'Diterima Petugas', 'time' => $laporan->tanggal_diterima?->translatedFormat('d M Y, H:i'), 'color' => 'primary', 'active' => $laporan->status === 'diterima'];
                    }
                    if ($laporan->tanggal_diproses || in_array($laporan->status, ['diproses', 'selesai'])) {
                        $items[] = ['title' => 'Sedang Diproses', 'time' => $laporan->tanggal_diproses?->translatedFormat('d M Y, H:i'), 'color' => 'amber', 'active' => $laporan->status === 'diproses'];
                    }
                    if ($laporan->status === 'selesai') {
                        $items[] = ['title' => 'Selesai', 'time' => $laporan->tanggal_selesai?->translatedFormat('d M Y, H:i'), 'color' => 'emerald', 'active' => true];
                    }
                    if ($laporan->status === 'ditolak') {
                        $items[] = ['title' => 'Ditolak', 'description' => $laporan->alasan_tolak, 'color' => 'rose', 'active' => true];
                    }
                @endphp
                <x-timeline :items="$items" />
            </x-card>
        </div>
    </div>
</x-layouts.warga>
