<x-layouts.dashboard :title="'Detail Laporan'">
    <x-slot:header>{{ $laporan->kode_laporan }}</x-slot:header>
    <x-slot:actions>
        <x-button :href="route('admin.laporan.index')" variant="tertiary">← Kembali</x-button>
    </x-slot:actions>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-5">
            <x-card class="!p-0 overflow-hidden">
                <img src="{{ $laporan->foto_url }}" class="w-full max-h-[480px] object-cover" alt="Foto">
            </x-card>
            <x-card>
                <h3 class="font-display font-semibold text-slate-900">Detail</h3>
                <dl class="mt-4 grid sm:grid-cols-2 gap-y-3 gap-x-6 text-sm">
                    <div><dt class="text-slate-500">Pelapor</dt><dd class="font-medium">{{ $laporan->pelapor?->name }}</dd></div>
                    <div><dt class="text-slate-500">Petugas</dt><dd class="font-medium">{{ $laporan->petugas?->name ?? '-' }}</dd></div>
                    <div><dt class="text-slate-500">Jenis</dt><dd>{{ $laporan->jenis_label }}</dd></div>
                    <div><dt class="text-slate-500">Status</dt><dd><x-status-badge :status="$laporan->status" /></dd></div>
                    <div class="sm:col-span-2"><dt class="text-slate-500">Lokasi</dt><dd>{{ $laporan->lokasi_text }}</dd></div>
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
                    <div class="sm:col-span-2"><dt class="text-slate-500">Keterangan</dt><dd>{{ $laporan->keterangan }}</dd></div>
                </dl>
            </x-card>

            @if($laporan->latitude && $laporan->longitude)
                <x-card>
                    <div class="flex items-center justify-between gap-3 mb-3">
                        <h3 class="font-display font-semibold text-slate-900">Lokasi di Peta</h3>
                        <a href="https://www.google.com/maps/search/?api=1&query={{ $laporan->latitude }},{{ $laporan->longitude }}"
                           target="_blank" rel="noopener" class="text-xs font-semibold text-primary-700 hover:text-primary-800">Buka Google Maps →</a>
                    </div>
                    <div id="map" class="w-full h-64 rounded-lg overflow-hidden z-0"></div>
                </x-card>
            @endif
        </div>
        <x-card>
            <h3 class="font-display font-semibold text-slate-900 mb-3">Timeline</h3>
            @php
                $items = [['title' => 'Dikirim', 'time' => $laporan->tanggal_lapor?->translatedFormat('d M Y, H:i'), 'color' => 'sky']];
                if ($laporan->tanggal_diterima) $items[] = ['title' => 'Diterima', 'time' => $laporan->tanggal_diterima?->translatedFormat('d M Y, H:i'), 'color' => 'primary'];
                if ($laporan->tanggal_diproses) $items[] = ['title' => 'Diproses', 'time' => $laporan->tanggal_diproses?->translatedFormat('d M Y, H:i'), 'color' => 'amber'];
                if ($laporan->tanggal_selesai) $items[] = ['title' => 'Selesai', 'time' => $laporan->tanggal_selesai?->translatedFormat('d M Y, H:i'), 'color' => 'emerald'];
                if ($laporan->status === 'ditolak') $items[] = ['title' => 'Ditolak', 'description' => $laporan->alasan_tolak, 'color' => 'rose'];
            @endphp
            <x-timeline :items="$items" />
        </x-card>
    </div>

    @if($laporan->latitude && $laporan->longitude)
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            (() => {
                const el = document.getElementById('map');
                if (!el) return;
                const map = L.map(el).setView([{{ $laporan->latitude }}, {{ $laporan->longitude }}], 17);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap', maxZoom: 19,
                }).addTo(map);
                L.marker([{{ $laporan->latitude }}, {{ $laporan->longitude }}]).addTo(map)
                    .bindPopup('{{ $laporan->kode_laporan }}').openPopup();
                setTimeout(() => map.invalidateSize(), 200);
            })();
        </script>
    @endif
</x-layouts.dashboard>
