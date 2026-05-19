<x-layouts.dashboard :title="'Detail Laporan'">
    <x-slot:header>{{ $laporan->kode_laporan }}</x-slot:header>
    <x-slot:subheader>Detail lengkap laporan dari warga.</x-slot:subheader>
    <x-slot:actions>
        <x-button :href="route('petugas.laporan.index')" variant="tertiary">← Kembali</x-button>
    </x-slot:actions>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-5">
            <x-card class="!p-0 overflow-hidden">
                <div x-data="{ open: false }">
                    <img src="{{ $laporan->foto_url }}" alt="Foto laporan" class="w-full max-h-[480px] object-cover cursor-zoom-in" @click="open = true">
                    <div x-show="open" x-cloak class="fixed inset-0 z-50 bg-black/80 backdrop-blur flex items-center justify-center p-4" @click="open = false">
                        <img src="{{ $laporan->foto_url }}" class="max-w-full max-h-full rounded-lg shadow-xl">
                        <button class="absolute top-4 right-4 w-10 h-10 rounded-full bg-white/20 text-white flex items-center justify-center" @click.stop="open = false">✕</button>
                    </div>
                </div>
            </x-card>

            <x-card>
                <h3 class="font-display font-semibold text-slate-900">Informasi Laporan</h3>
                <dl class="mt-4 grid sm:grid-cols-2 gap-y-3 gap-x-6 text-sm">
                    <div>
                        <dt class="text-slate-500">Pelapor</dt>
                        <dd class="font-medium text-slate-900 mt-0.5 flex items-center gap-2">
                            <x-avatar :user="$laporan->pelapor" size="xs" />
                            {{ $laporan->pelapor?->name }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">No. Telp Pelapor</dt>
                        <dd class="font-medium text-slate-900 mt-0.5">{{ $laporan->pelapor?->no_telp ?? '-' }}</dd>
                    </div>
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
                    <div class="sm:col-span-2">
                        <dt class="text-slate-500">Keterangan</dt>
                        <dd class="text-slate-700 mt-0.5 leading-relaxed">{{ $laporan->keterangan }}</dd>
                    </div>
                    @if($laporan->alasan_tolak)
                        <div class="sm:col-span-2">
                            <dt class="text-slate-500">Alasan Penolakan</dt>
                            <dd class="text-rose-700 mt-0.5">{{ $laporan->alasan_tolak }}</dd>
                        </div>
                    @endif
                </dl>
            </x-card>

            @if($laporan->latitude && $laporan->longitude)
                <x-card>
                    <h3 class="font-display font-semibold text-slate-900 mb-3">Lokasi di Peta</h3>
                    <div id="map" class="w-full h-72 rounded-lg overflow-hidden"></div>
                </x-card>
            @endif
        </div>

        <div class="space-y-5">
            <x-card>
                <h3 class="font-display font-semibold text-slate-900 mb-4">Timeline Status</h3>
                @php
                    $items = [['title' => 'Dikirim', 'time' => $laporan->tanggal_lapor?->translatedFormat('d M Y, H:i'), 'color' => 'sky']];
                    if (in_array($laporan->status, ['diterima', 'diproses', 'selesai'])) {
                        $items[] = ['title' => 'Diterima', 'time' => $laporan->tanggal_diterima?->translatedFormat('d M Y, H:i'), 'color' => 'primary'];
                    }
                    if (in_array($laporan->status, ['diproses', 'selesai'])) {
                        $items[] = ['title' => 'Diproses', 'time' => $laporan->tanggal_diproses?->translatedFormat('d M Y, H:i'), 'color' => 'amber', 'active' => $laporan->status === 'diproses'];
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

            @if($laporan->status !== 'selesai' && $laporan->status !== 'ditolak')
                <x-card>
                    <h3 class="font-display font-semibold text-slate-900 mb-3">Aksi Petugas</h3>
                    <div class="space-y-2">
                        @if($laporan->status === 'dikirim')
                            <form method="POST" action="{{ route('petugas.laporan.terima', $laporan->id) }}">
                                @csrf <x-button type="submit" fullWidth>Terima Laporan</x-button>
                            </form>
                        @endif
                        @if(in_array($laporan->status, ['dikirim', 'diterima']))
                            <form method="POST" action="{{ route('petugas.laporan.proses', $laporan->id) }}">
                                @csrf <x-button type="submit" variant="warning" fullWidth>Mulai Proses</x-button>
                            </form>
                        @endif
                        @if(in_array($laporan->status, ['diterima', 'diproses']))
                            <form method="POST" action="{{ route('petugas.laporan.selesai', $laporan->id) }}">
                                @csrf <x-button type="submit" variant="success" fullWidth>Tandai Selesai</x-button>
                            </form>
                        @endif

                        <x-button x-data x-on:click="$dispatch('open-modal', 'tolak-laporan')" variant="danger" fullWidth>Tolak Laporan</x-button>
                    </div>
                </x-card>

                <x-modal name="tolak-laporan" maxWidth="lg" title="Tolak Laporan">
                    <form method="POST" action="{{ route('petugas.laporan.tolak', $laporan->id) }}" class="space-y-4">
                        @csrf
                        <x-alert variant="warning" :dismissible="false">
                            Warga akan menerima notifikasi penolakan beserta alasan yang Anda tulis.
                        </x-alert>
                        <x-textarea label="Alasan Penolakan" name="alasan" required rows="4" placeholder="Jelaskan alasan penolakan dengan sopan..." />
                        <div class="flex justify-end gap-2">
                            <x-button type="button" variant="tertiary" x-on:click="$dispatch('close-modal', 'tolak-laporan')">Batal</x-button>
                            <x-button type="submit" variant="danger">Tolak Laporan</x-button>
                        </div>
                    </form>
                </x-modal>
            @endif
        </div>
    </div>

    @if($laporan->latitude && $laporan->longitude)
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            (() => {
                const map = L.map('map').setView([{{ $laporan->latitude }}, {{ $laporan->longitude }}], 17);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap',
                    maxZoom: 19
                }).addTo(map);
                L.marker([{{ $laporan->latitude }}, {{ $laporan->longitude }}]).addTo(map)
                    .bindPopup('Lokasi laporan {{ $laporan->kode_laporan }}').openPopup();
            })();
        </script>
    @endif
</x-layouts.dashboard>
