<x-layouts.dashboard :title="'Dashboard Admin'">
    <x-slot:header>Dashboard Admin</x-slot:header>
    <x-slot:subheader>Ringkasan operasional SIPLAS Banjar Bumi Shanti.</x-slot:subheader>

    @if($pendingApproval > 0)
        <x-alert variant="warning" :dismissible="false">
            <strong>{{ $pendingApproval }} warga</strong> menunggu persetujuan pendaftaran.
            <a href="{{ route('admin.pengguna.index', ['tab' => 'pending']) }}" class="font-semibold underline ml-1">Tinjau sekarang →</a>
        </x-alert>
    @endif

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
        <x-stat-card label="Total Warga Aktif" :value="number_format($totalWargaAktif)" color="primary"
            :href="route('admin.pengguna.index', ['tab' => 'aktif'])"
            :icon="'<svg fill=\'none\' stroke=\'currentColor\' stroke-width=\'1.8\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M17 20h5v-2a3 3 0 00-5.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M15 7a3 3 0 11-6 0 3 3 0 016 0z\'/></svg>'" />
        <x-stat-card label="Laporan Bulan Ini" :value="$laporanBulanIni" color="amber" :trend="$trendLaporan['label']" :trendDirection="$trendLaporan['direction']"
            :icon="'<svg fill=\'none\' stroke=\'currentColor\' stroke-width=\'1.8\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z\'/></svg>'" />
        <x-stat-card label="Iuran Terkumpul" :value="'Rp ' . number_format($iuranTerkumpul / 1000, 0) . 'rb'" color="violet" :trend="$trendIuran['label']" :trendDirection="$trendIuran['direction']"
            :icon="'<svg fill=\'none\' stroke=\'currentColor\' stroke-width=\'1.8\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z\'/></svg>'" />
        <x-stat-card label="Tingkat Penyelesaian" :value="$tingkatPenyelesaian . '%'" color="sky"
            :icon="'<svg fill=\'none\' stroke=\'currentColor\' stroke-width=\'1.8\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z\'/></svg>'" />
    </div>

    <div class="mt-6 grid lg:grid-cols-3 gap-6">
        <x-card class="lg:col-span-2">
            <h3 class="font-display font-semibold text-slate-900">Iuran Terkumpul (6 Bulan Terakhir)</h3>
            <div class="mt-4 h-72"><canvas id="chartIuran"></canvas></div>
        </x-card>

        <x-card>
            <h3 class="font-display font-semibold text-slate-900">Distribusi Status Laporan</h3>
            <p class="text-xs text-slate-500 mt-0.5">Bulan ini</p>
            <div class="mt-4 h-72"><canvas id="chartStatus"></canvas></div>
        </x-card>
    </div>

    <div class="mt-6">
        <x-card class="!p-0">
            <div class="px-5 py-4 border-b border-slate-100">
                <h3 class="font-display font-semibold text-slate-900">Aktivitas Laporan Terbaru</h3>
            </div>
            <ul class="divide-y divide-slate-100">
                @foreach($aktivitasTerbaru as $a)
                    <li class="px-5 py-3 flex items-center gap-3">
                        <x-avatar :user="$a->pelapor" size="sm" />
                        <div class="flex-1 min-w-0">
                            <p class="text-sm">
                                <span class="font-semibold text-slate-900">{{ $a->pelapor?->name }}</span>
                                <span class="text-slate-500">membuat laporan</span>
                                <span class="font-mono text-xs text-slate-700">{{ $a->kode_laporan }}</span>
                            </p>
                            <p class="text-xs text-slate-400">{{ $a->updated_at?->diffForHumans() }}</p>
                        </div>
                        <x-status-badge :status="$a->status" />
                    </li>
                @endforeach
            </ul>
        </x-card>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.6/dist/chart.umd.min.js"></script>
    <script>
        (() => {
            const ctxI = document.getElementById('chartIuran');
            if (ctxI) {
                const grad = ctxI.getContext('2d').createLinearGradient(0, 0, 0, 280);
                grad.addColorStop(0, 'rgba(16,185,129,0.5)');
                grad.addColorStop(1, 'rgba(16,185,129,0)');
                new Chart(ctxI, {
                    type: 'line',
                    data: {
                        labels: @json($iuranLabels),
                        datasets: [{
                            label: 'Terkumpul (Rp)', data: @json($iuranValues),
                            borderColor: '#059669', backgroundColor: grad,
                            tension: 0.35, fill: true, pointBackgroundColor: '#10B981', pointRadius: 4, pointHoverRadius: 6,
                            borderWidth: 2.5,
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true, ticks: { callback: v => 'Rp ' + (v/1000) + 'rb' } } } }
                });
            }

            const ctxS = document.getElementById('chartStatus');
            if (ctxS) {
                new Chart(ctxS, {
                    type: 'doughnut',
                    data: {
                        labels: ['Dikirim', 'Diterima', 'Diproses', 'Selesai', 'Ditolak'],
                        datasets: [{
                            data: [{{ $statusDist['dikirim'] }}, {{ $statusDist['diterima'] }}, {{ $statusDist['diproses'] }}, {{ $statusDist['selesai'] }}, {{ $statusDist['ditolak'] }}],
                            backgroundColor: ['#0EA5E9', '#10B981', '#F59E0B', '#059669', '#EF4444'],
                            borderColor: '#fff', borderWidth: 3,
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, cutout: '65%',
                        plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 12, font: { size: 11 } } } } }
                });
            }
        })();
    </script>
</x-layouts.dashboard>
