<x-layouts.dashboard :title="'Dashboard'">
    <x-slot:header>Dashboard Petugas</x-slot:header>
    <x-slot:subheader>Ringkasan tugas dan aktivitas harian.</x-slot:subheader>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <x-stat-card label="Laporan Baru" :value="$stat['baru']" color="primary"
            :href="route('petugas.laporan.index', ['status' => 'dikirim'])"
            :icon="'<svg fill=\'none\' stroke=\'currentColor\' stroke-width=\'1.8\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M12 6v6m0 0v6m0-6h6m-6 0H6\'/></svg>'" />
        <x-stat-card label="Sedang Diproses" :value="$stat['diproses']" color="amber"
            :href="route('petugas.laporan.index', ['status' => 'diproses'])"
            :icon="'<svg fill=\'none\' stroke=\'currentColor\' stroke-width=\'1.8\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z\'/></svg>'" />
        <x-stat-card label="Selesai Hari Ini" :value="$stat['selesai_hari_ini']" color="sky"
            :icon="'<svg fill=\'none\' stroke=\'currentColor\' stroke-width=\'1.8\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z\'/></svg>'" />
        <x-stat-card label="Verifikasi Iuran" :value="$stat['menunggu_verifikasi']" color="violet"
            :href="route('petugas.iuran.index')"
            :icon="'<svg fill=\'none\' stroke=\'currentColor\' stroke-width=\'1.8\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z\'/></svg>'" />
    </div>

    <div class="mt-6 grid lg:grid-cols-3 gap-6">
        <x-card class="lg:col-span-2">
            <h3 class="font-display font-semibold text-slate-900">Laporan 7 Hari Terakhir</h3>
            <p class="text-sm text-slate-500 mt-0.5">Total laporan masuk per hari.</p>
            <div class="mt-4 h-64">
                <canvas id="chartLaporan7"></canvas>
            </div>
        </x-card>

        <x-card>
            <h3 class="font-display font-semibold text-slate-900">Aksi Cepat</h3>
            <p class="text-sm text-slate-500 mt-0.5">Buka tugas prioritas.</p>
            <div class="mt-4 space-y-2">
                <x-button :href="route('petugas.laporan.index', ['status' => 'dikirim'])" variant="primary" fullWidth>
                    Lihat Laporan Baru ({{ $stat['baru'] }})
                </x-button>
                <x-button :href="route('petugas.iuran.index')" variant="secondary" fullWidth>
                    Verifikasi Iuran ({{ $stat['menunggu_verifikasi'] }})
                </x-button>
            </div>
        </x-card>
    </div>

    <div class="mt-6">
        <x-card class="!p-0">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-display font-semibold text-slate-900">Laporan Terbaru</h3>
                <a href="{{ route('petugas.laporan.index') }}" class="text-sm font-medium text-primary-700">Lihat semua →</a>
            </div>
            @if($laporanTerbaru->isEmpty())
                <x-empty-state title="Belum ada laporan" />
            @else
                <x-table>
                    <thead class="bg-slate-50">
                        <tr class="text-left text-xs uppercase tracking-wider text-slate-500">
                            <th class="px-5 py-3">Kode</th>
                            <th class="px-5 py-3">Pelapor</th>
                            <th class="px-5 py-3">Jenis</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Waktu</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($laporanTerbaru as $l)
                            <tr class="hover:bg-slate-50">
                                <td class="px-5 py-3 font-mono text-xs">{{ $l->kode_laporan }}</td>
                                <td class="px-5 py-3"><span class="font-medium text-slate-900">{{ $l->pelapor?->name }}</span></td>
                                <td class="px-5 py-3 text-sm">{{ $l->jenis_label }}</td>
                                <td class="px-5 py-3"><x-status-badge :status="$l->status" /></td>
                                <td class="px-5 py-3 text-xs text-slate-500">{{ $l->tanggal_lapor?->diffForHumans() }}</td>
                                <td class="px-5 py-3 text-right">
                                    <x-button :href="route('petugas.laporan.show', $l->id)" size="xs" variant="ghost">Detail →</x-button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </x-table>
            @endif
        </x-card>
    </div>

    @push('scripts')
    @endpush

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.6/dist/chart.umd.min.js"></script>
    <script>
        (() => {
            const ctx = document.getElementById('chartLaporan7');
            if (!ctx) return;
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: 'Laporan',
                        data: @json($values),
                        backgroundColor: 'rgba(16,185,129,0.7)',
                        borderColor: '#059669',
                        borderWidth: 1,
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
                }
            });
        })();
    </script>
</x-layouts.dashboard>
