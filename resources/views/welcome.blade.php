<x-guest-layout :title="'SIPLAS Banjar Bumi Shanti'">
    @php
        $arrowIcon = '<svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>';
    @endphp

    {{-- Decorative top pattern (Balinese accent band) --}}
    <div aria-hidden="true" class="absolute inset-x-0 top-0 h-2 bg-gradient-to-r from-primary-600 via-amber-500 to-primary-600 opacity-90 z-30"></div>

    {{-- Top nav --}}
    <header class="absolute top-0 left-0 right-0 z-20 pt-2">
        <div class="container-app">
            <div class="h-20 flex items-center justify-between">
                <x-application-logo :size="44" />
                <div class="flex items-center gap-2 sm:gap-3">
                    <a href="{{ route('login') }}" class="hidden sm:inline-flex text-sm font-medium text-slate-700 hover:text-slate-900 px-3 py-2 transition">Masuk</a>
                    <x-button :href="route('register')" variant="primary" size="sm" :icon="$arrowIcon" iconPosition="right">
                        Daftar Sekarang
                    </x-button>
                </div>
            </div>
        </div>
    </header>

    {{-- Hero --}}
    <section class="relative pt-32 sm:pt-40 pb-16 sm:pb-28 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-mesh -z-0"></div>
        <div aria-hidden="true" class="absolute -top-12 -right-12 w-96 h-96 rounded-full bg-primary-300/30 blur-3xl -z-0"></div>
        <div aria-hidden="true" class="absolute bottom-0 -left-16 w-72 h-72 rounded-full bg-amber-300/20 blur-3xl -z-0"></div>

        <div class="container-app relative">
            <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
                <div class="animate-fade-in">
                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-medium bg-white/80 backdrop-blur text-primary-700 ring-1 ring-primary-200 shadow-soft">
                        <span class="relative flex w-2 h-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-500"></span>
                        </span>
                        Banjar Bumi Shanti · Denpasar Barat, Bali
                    </span>
                    <h1 class="mt-5 text-4xl sm:text-5xl lg:text-6xl font-display font-extrabold tracking-tight text-slate-900 text-balance leading-[1.05]">
                        Lingkungan Bersih, <span class="bg-gradient-to-br from-primary-600 to-primary-800 bg-clip-text text-transparent">Banjar Sehat.</span>
                    </h1>
                    <p class="mt-5 text-lg text-slate-600 max-w-xl leading-relaxed text-balance">
                        Sistem digital untuk warga Banjar Bumi Shanti — laporkan penumpukan sampah dengan foto,
                        bayar iuran bulanan, dan pantau respon petugas dalam genggaman.
                    </p>
                    <div class="mt-8 flex flex-col sm:flex-row gap-3">
                        <x-button :href="route('register')" variant="primary" size="lg" :icon="$arrowIcon" iconPosition="right">
                            Daftar Sekarang
                        </x-button>
                        <x-button :href="route('login')" variant="secondary" size="lg">Masuk ke Akun</x-button>
                    </div>

                    <div class="mt-10 grid grid-cols-3 gap-4 sm:gap-8 max-w-md">
                        @php
                            $stats = [
                                ['n' => '2.134', 'l' => 'KK terdaftar'],
                                ['n' => '24/7',  'l' => 'Online'],
                                ['n' => '100%',  'l' => 'Transparan'],
                            ];
                        @endphp
                        @foreach($stats as $s)
                            <div class="text-center sm:text-left">
                                <p class="text-2xl sm:text-3xl font-display font-bold text-slate-900 tabular-nums">{{ $s['n'] }}</p>
                                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">{{ $s['l'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Mockup preview --}}
                <div class="relative animate-slide-up hidden lg:block">
                    <div class="relative max-w-md ml-auto">
                        <div class="relative bg-white rounded-2xl shadow-2xl border border-slate-200/70 overflow-hidden">
                            <div class="px-5 py-3 border-b border-slate-100 flex items-center gap-2 bg-slate-50">
                                <span class="w-3 h-3 rounded-full bg-rose-400"></span>
                                <span class="w-3 h-3 rounded-full bg-amber-400"></span>
                                <span class="w-3 h-3 rounded-full bg-emerald-400"></span>
                                <span class="ml-auto text-xs text-slate-400 font-mono">siplas.banjar.id</span>
                            </div>
                            <div class="p-5">
                                <div class="flex items-center justify-between mb-3">
                                    <div>
                                        <p class="text-xs text-slate-500 font-mono">LPS-20260520-X7F2</p>
                                        <p class="font-display font-semibold text-slate-900 mt-0.5">Tumpukan sampah di Gg. Mawar</p>
                                    </div>
                                    <x-status-badge status="diproses" />
                                </div>

                                <div class="relative aspect-video rounded-lg overflow-hidden bg-gradient-to-br from-primary-400 via-primary-500 to-primary-700 shadow-inner mb-4">
                                    <div class="absolute inset-0 bg-gradient-mesh opacity-40"></div>
                                    <div class="absolute bottom-2 left-2 px-2 py-1 rounded-md bg-black/30 backdrop-blur text-white text-xs">
                                        📍 Banjar Bumi Shanti
                                    </div>
                                    <div class="absolute top-2 right-2 px-2 py-1 rounded-md bg-white/20 backdrop-blur text-white text-xs font-medium">
                                        🥗 Organik
                                    </div>
                                </div>

                                <x-timeline :items="[
                                    ['title' => 'Dikirim',         'time' => 'Hari ini, 08:24', 'color' => 'sky'],
                                    ['title' => 'Diterima petugas','time' => 'Hari ini, 09:10', 'color' => 'primary'],
                                    ['title' => 'Sedang diproses', 'time' => 'Saat ini',        'color' => 'amber', 'active' => true],
                                ]" />
                            </div>
                        </div>

                        {{-- Floating notification card --}}
                        <div class="absolute -top-6 -left-10 bg-white rounded-xl shadow-xl border border-slate-200 px-3 py-2.5 flex items-center gap-2.5 animate-fade-in" style="animation-delay: 600ms; animation-fill-mode: both;">
                            <span class="inline-flex w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-slate-900">Pembayaran Terverifikasi</p>
                                <p class="text-[11px] text-slate-500">Iuran April 2026</p>
                            </div>
                        </div>

                        {{-- Floating stat card --}}
                        <div class="absolute -bottom-6 -right-6 bg-white rounded-xl shadow-xl border border-slate-200 px-3 py-2.5 animate-fade-in" style="animation-delay: 900ms; animation-fill-mode: both;">
                            <p class="text-[10px] uppercase tracking-wider text-slate-500 font-medium">Hari ini</p>
                            <p class="text-2xl font-display font-bold text-slate-900 tabular-nums">12 <span class="text-xs font-medium text-slate-500">laporan</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Trusted by --}}
    <section class="py-8 border-y border-slate-100 bg-white">
        <div class="container-app">
            <p class="text-center text-xs uppercase tracking-widest text-slate-500 font-medium mb-5">Dipercaya & didukung oleh</p>
            <div class="flex flex-wrap items-center justify-center gap-x-10 gap-y-3 text-slate-400">
                <span class="font-display font-bold text-base sm:text-lg">Kepala Lingkungan</span>
                <span class="text-slate-300">•</span>
                <span class="font-display font-bold text-base sm:text-lg">Desa Dauh Puri Kelod</span>
                <span class="text-slate-300">•</span>
                <span class="font-display font-bold text-base sm:text-lg">Petugas Kebersihan</span>
                <span class="text-slate-300">•</span>
                <span class="font-display font-bold text-base sm:text-lg">2.134 Warga</span>
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section class="py-16 sm:py-24">
        <div class="container-app">
            <div class="text-center max-w-2xl mx-auto">
                <x-badge variant="primary" size="md">Fitur Utama</x-badge>
                <h2 class="mt-4 text-3xl sm:text-4xl font-display font-bold text-slate-900 text-balance">Tiga langkah, lingkungan lebih bersih</h2>
                <p class="mt-3 text-slate-600">Semua layanan kebersihan banjar dalam satu tempat yang mudah, transparan, dan cepat.</p>
            </div>

            <div class="mt-12 grid md:grid-cols-3 gap-6">
                @php
                    $features = [
                        [
                            'title' => 'Lapor Mudah',
                            'desc'  => 'Cukup foto, isi lokasi & jenis sampah, lalu kirim. Petugas terima notifikasi seketika.',
                            'color' => 'primary',
                            'svg'   => '<svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z"/></svg>',
                        ],
                        [
                            'title' => 'Iuran Transparan',
                            'desc'  => 'Tagihan bulanan otomatis, pembayaran tercatat digital. Tidak lagi rebutan buku catatan.',
                            'color' => 'amber',
                            'svg'   => '<svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>',
                        ],
                        [
                            'title' => 'Notifikasi Real-time',
                            'desc'  => 'Setiap perubahan status laporan & tagihan langsung dikirim ke aplikasi. Tidak ada yang terlewat.',
                            'color' => 'sky',
                            'svg'   => '<svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9a6 6 0 00-12 0v.75A8.967 8.967 0 013.69 15.77c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>',
                        ],
                    ];
                    $palette = [
                        'primary' => ['bg' => 'bg-primary-50', 'text' => 'text-primary-600', 'ring' => 'ring-primary-100'],
                        'amber'   => ['bg' => 'bg-amber-50',   'text' => 'text-amber-600',   'ring' => 'ring-amber-100'],
                        'sky'     => ['bg' => 'bg-sky-50',     'text' => 'text-sky-600',     'ring' => 'ring-sky-100'],
                    ];
                @endphp
                @foreach($features as $i => $f)
                    @php $c = $palette[$f['color']]; @endphp
                    <div class="group relative bg-white rounded-2xl border border-slate-200/70 p-6 shadow-soft hover:shadow-xl hover:-translate-y-1 transition-all duration-300 animate-fade-in"
                         style="animation-delay: {{ $i * 100 }}ms; animation-fill-mode: both;">
                        <div class="w-14 h-14 rounded-xl flex items-center justify-center {{ $c['bg'] }} {{ $c['text'] }} ring-4 {{ $c['ring'] }} [&_svg]:w-7 [&_svg]:h-7 mb-5 group-hover:scale-110 transition-transform">
                            {!! $f['svg'] !!}
                        </div>
                        <h3 class="font-display font-semibold text-xl text-slate-900">{{ $f['title'] }}</h3>
                        <p class="mt-2 text-slate-600 leading-relaxed">{{ $f['desc'] }}</p>
                        <span class="absolute top-0 right-0 m-4 text-[42px] font-display font-extrabold text-slate-100 leading-none select-none">0{{ $i + 1 }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- How it works --}}
    <section class="py-16 sm:py-24 bg-slate-50 border-y border-slate-100">
        <div class="container-app">
            <div class="text-center max-w-2xl mx-auto">
                <x-badge variant="primary" size="md">Cara Kerja</x-badge>
                <h2 class="mt-4 text-3xl sm:text-4xl font-display font-bold text-slate-900 text-balance">Tiga Langkah Sederhana</h2>
                <p class="mt-3 text-slate-600">Dari laporan sampai sampah diangkut, semua transparan.</p>
            </div>

            <div class="mt-12 grid md:grid-cols-3 gap-6 relative">
                <div aria-hidden="true" class="hidden md:block absolute top-10 left-[16%] right-[16%] h-px bg-gradient-to-r from-transparent via-primary-300 to-transparent"></div>

                @foreach([
                    ['n' => '1', 'title' => 'Foto & Kirim Laporan', 'desc' => 'Warga ambil foto sampah, isi lokasi & jenis, lalu kirim dari aplikasi.'],
                    ['n' => '2', 'title' => 'Petugas Tanggapi',     'desc' => 'Notifikasi otomatis ke petugas. Status laporan ter-update real-time.'],
                    ['n' => '3', 'title' => 'Selesai & Dikonfirmasi','desc' => 'Sampah diangkut, warga diberi notifikasi penyelesaian.'],
                ] as $i => $step)
                    <div class="relative text-center bg-white rounded-2xl p-6 border border-slate-200/70 shadow-soft animate-fade-in" style="animation-delay: {{ $i * 150 }}ms; animation-fill-mode: both;">
                        <div class="mx-auto w-20 h-20 rounded-full bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center text-white shadow-glow-primary relative">
                            <span class="font-display font-extrabold text-3xl">{{ $step['n'] }}</span>
                        </div>
                        <h3 class="mt-5 font-display font-bold text-xl text-slate-900">{{ $step['title'] }}</h3>
                        <p class="mt-2 text-slate-600 leading-relaxed">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Tentang + CTA --}}
    <section class="py-16 sm:py-24">
        <div class="container-app">
            <x-balinese-divider class="mb-16" />

            <div class="grid lg:grid-cols-2 gap-10 items-center">
                <div>
                    <x-badge variant="primary">Tentang Kami</x-badge>
                    <h2 class="mt-4 text-3xl sm:text-4xl font-display font-bold text-slate-900 text-balance">Banjar Bumi Shanti</h2>
                    <p class="mt-4 text-slate-600 leading-relaxed">
                        Banjar Bumi Shanti adalah salah satu banjar di <strong>Desa Dauh Puri Kelod, Kecamatan Denpasar Barat, Bali</strong>,
                        dengan total <strong>2.134 Kepala Keluarga</strong>. SIPLAS dibangun untuk memudahkan
                        koordinasi pengelolaan sampah dan pencatatan iuran yang sebelumnya dilakukan secara manual.
                    </p>
                    <p class="mt-3 text-slate-600 leading-relaxed">
                        Sistem ini dipimpin oleh <strong>I Dewa Gede Satia Putra</strong> sebagai Kepala Lingkungan,
                        dibantu petugas kebersihan dan dukungan seluruh warga banjar.
                    </p>
                    <div class="mt-6 flex flex-wrap gap-2">
                        <x-badge variant="success" size="md">✓ Tidak ada biaya aplikasi</x-badge>
                        <x-badge variant="success" size="md">✓ Data aman & terenkripsi</x-badge>
                    </div>
                </div>

                <div class="relative rounded-2xl bg-gradient-to-br from-primary-500 to-primary-700 p-8 sm:p-10 text-white shadow-2xl overflow-hidden">
                    <div aria-hidden="true" class="absolute -top-12 -right-12 w-48 h-48 rounded-full bg-amber-300/20 blur-2xl"></div>
                    <div aria-hidden="true" class="absolute -bottom-16 -left-16 w-64 h-64 rounded-full bg-white/10 blur-3xl"></div>

                    <h3 class="relative font-display font-extrabold text-3xl text-balance">Bergabung dengan SIPLAS</h3>
                    <p class="relative mt-3 text-white/90 leading-relaxed">
                        Warga Banjar Bumi Shanti dapat mendaftar dan mulai menggunakan layanan setelah
                        akun disetujui oleh admin banjar.
                    </p>
                    <ul class="relative mt-6 space-y-2.5">
                        @foreach([
                            'Pelaporan sampah dengan foto & lokasi',
                            'Pembayaran iuran digital, bukti tersimpan rapi',
                            'Notifikasi otomatis tiap perubahan status',
                            'Gratis untuk seluruh warga banjar',
                        ] as $point)
                            <li class="flex gap-2 items-start text-sm">
                                <span class="shrink-0 mt-0.5 w-5 h-5 rounded-full bg-white/20 flex items-center justify-center">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                {{ $point }}
                            </li>
                        @endforeach
                    </ul>
                    <div class="relative mt-7 flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg bg-white text-primary-700 font-semibold shadow-md hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200">
                            Daftar Akun Warga
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-lg bg-white/15 backdrop-blur border border-white/25 text-white font-semibold hover:bg-white/25 transition-all duration-200">
                            Masuk ke Akun
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-slate-200/70 py-10 bg-white">
        <div class="container-app">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <x-application-logo :size="36" />
                <div class="text-xs text-slate-400">© {{ date('Y') }} Banjar Bumi Shanti · Made with 💚 in Bali</div>
            </div>
        </div>
    </footer>
</x-guest-layout>
