<x-layouts.warga :title="'Beranda'">
    <x-slot:header>Selamat datang, {{ explode(' ', auth()->user()->name)[0] }} 👋</x-slot:header>
    <x-slot:subheader>Lihat ringkasan aktivitas Anda di SIPLAS Banjar Bumi Shanti.</x-slot:subheader>

    {{-- Stat cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <x-stat-card label="Laporan Aktif" :value="$stat['laporan_aktif']" color="primary"
            :icon="'<svg fill=\'none\' stroke=\'currentColor\' stroke-width=\'1.8\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z\'/></svg>'" />
        <x-stat-card label="Laporan Selesai" :value="$stat['laporan_selesai']" color="sky"
            :icon="'<svg fill=\'none\' stroke=\'currentColor\' stroke-width=\'1.8\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z\'/></svg>'" />
        <x-stat-card label="Tagihan Belum Bayar" :value="$stat['tagihan_belum']" color="amber"
            :icon="'<svg fill=\'none\' stroke=\'currentColor\' stroke-width=\'1.8\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z\'/></svg>'" />
        <x-stat-card label="Total Lunas" :value="$stat['tagihan_lunas']" color="violet"
            :icon="'<svg fill=\'none\' stroke=\'currentColor\' stroke-width=\'1.8\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5\'/></svg>'" />
    </div>

    {{-- Quick actions --}}
    <div class="mt-6 grid md:grid-cols-2 gap-4">
        <a href="{{ route('warga.lapor.create') }}"
           class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-primary-500 to-primary-700 p-6 text-white shadow-glow-primary hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
            <div class="absolute -bottom-10 -right-10 w-40 h-40 rounded-full bg-white/10 blur-2xl"></div>
            <div class="absolute -top-8 -right-8 w-32 h-32 rounded-full bg-amber-300/20 blur-2xl"></div>
            <div class="relative">
                <div class="w-12 h-12 rounded-xl bg-white/15 backdrop-blur flex items-center justify-center mb-3 group-hover:scale-110 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="font-display font-bold text-xl">Lapor Sampah</h3>
                <p class="text-white/85 text-sm mt-1">Foto + lokasi + jenis sampah. Petugas akan menanggapi.</p>
                <span class="inline-flex items-center gap-1 mt-4 text-sm font-semibold">
                    Buat laporan
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </span>
            </div>
        </a>

        <a href="{{ route('warga.iuran.index') }}"
           class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-400 to-amber-600 p-6 text-white shadow-glow-secondary hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
            <div class="absolute -bottom-10 -right-10 w-40 h-40 rounded-full bg-white/10 blur-2xl"></div>
            <div class="relative">
                <div class="w-12 h-12 rounded-xl bg-white/15 backdrop-blur flex items-center justify-center mb-3 group-hover:scale-110 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="font-display font-bold text-xl">Bayar Iuran</h3>
                <p class="text-white/90 text-sm mt-1">Lihat tagihan bulanan & upload bukti pembayaran.</p>
                <span class="inline-flex items-center gap-1 mt-4 text-sm font-semibold">
                    Lihat tagihan
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </span>
            </div>
        </a>
    </div>

    {{-- Tagihan belum bayar --}}
    @if($tagihanBelum->isNotEmpty())
        <div class="mt-8">
            <div class="flex items-center justify-between mb-3">
                <h2 class="font-display font-bold text-xl text-slate-900">Tagihan Aktif</h2>
                <a href="{{ route('warga.iuran.index') }}" class="text-sm font-medium text-primary-700 hover:text-primary-800">Lihat semua →</a>
            </div>
            <div class="grid sm:grid-cols-3 gap-3">
                @foreach($tagihanBelum as $iuran)
                    <x-card class="!p-5">
                        <div class="flex items-start justify-between gap-2 mb-3">
                            <div>
                                <p class="text-xs text-slate-500">{{ $iuran->kode_tagihan }}</p>
                                <p class="font-semibold text-slate-900">{{ $iuran->periode_label }}</p>
                            </div>
                            <x-status-badge :status="$iuran->status" />
                        </div>
                        <p class="text-2xl font-display font-bold text-slate-900 tabular-nums">{{ $iuran->nominal_formatted }}</p>
                        @if($iuran->alasan_tolak)
                            <p class="mt-2 text-xs text-rose-600 line-clamp-2">⚠ {{ $iuran->alasan_tolak }}</p>
                        @endif
                        <a href="{{ route('warga.iuran.index') }}" class="mt-3 block text-center text-sm font-medium text-primary-700 hover:text-primary-800">Bayar Sekarang →</a>
                    </x-card>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Laporan terbaru --}}
    <div class="mt-8">
        <div class="flex items-center justify-between mb-3">
            <h2 class="font-display font-bold text-xl text-slate-900">Laporan Terbaru Saya</h2>
            <a href="{{ route('warga.laporan.index') }}" class="text-sm font-medium text-primary-700 hover:text-primary-800">Lihat semua →</a>
        </div>
        @if($laporanTerbaru->isEmpty())
            <x-card>
                <x-empty-state title="Belum ada laporan" description="Mulai dengan membuat laporan pertama Anda.">
                    <x-slot:action>
                        <x-button :href="route('warga.lapor.create')">Buat Laporan</x-button>
                    </x-slot:action>
                </x-empty-state>
            </x-card>
        @else
            <div class="grid sm:grid-cols-3 gap-3">
                @foreach($laporanTerbaru as $laporan)
                    <a href="{{ route('warga.laporan.show', $laporan->id) }}" class="group">
                        <x-card hover class="!p-0 overflow-hidden h-full">
                            <div class="aspect-video bg-slate-100 overflow-hidden">
                                <img src="{{ $laporan->foto_url }}" alt="{{ $laporan->kode_laporan }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                            <div class="p-4">
                                <div class="flex items-start justify-between gap-2 mb-2">
                                    <p class="text-xs text-slate-500">{{ $laporan->kode_laporan }}</p>
                                    <x-status-badge :status="$laporan->status" />
                                </div>
                                <p class="font-semibold text-slate-900 line-clamp-1">{{ $laporan->jenis_icon }} {{ $laporan->jenis_label }}</p>
                                <p class="text-sm text-slate-500 line-clamp-1 mt-0.5">{{ $laporan->lokasi_text }}</p>
                                <p class="text-xs text-slate-400 mt-2">{{ $laporan->tanggal_lapor?->diffForHumans() }}</p>
                            </div>
                        </x-card>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.warga>
