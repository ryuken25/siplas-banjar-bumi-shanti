<x-guest-layout :title="'SIPLAS Banjar Bumi Shanti'">
    {{-- Top nav --}}
    <header class="absolute top-0 left-0 right-0 z-20">
        <div class="container-app">
            <div class="h-20 flex items-center justify-between">
                <x-application-logo :size="44" />
                <div class="flex items-center gap-2 sm:gap-3">
                    <a href="{{ route('login') }}" class="hidden sm:inline-flex text-sm font-medium text-slate-700 hover:text-slate-900 px-3 py-2">Masuk</a>
                    <x-button :href="route('register')" variant="primary" size="sm">Daftar Sekarang</x-button>
                </div>
            </div>
        </div>
    </header>

    {{-- Hero --}}
    <section class="relative pt-32 sm:pt-40 pb-16 sm:pb-24">
        <div class="absolute inset-0 bg-gradient-mesh -z-0"></div>
        <div class="container-app relative">
            <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
                <div class="animate-fade-in">
                    <x-badge variant="primary" size="md">🌿 Banjar Bumi Shanti · Denpasar Barat</x-badge>
                    <h1 class="mt-5 text-4xl sm:text-5xl lg:text-6xl font-display font-bold tracking-tight text-slate-900 text-balance leading-[1.05]">
                        Lingkungan Bersih, <span class="text-primary-600">Banjar Sehat.</span>
                    </h1>
                    <p class="mt-5 text-lg text-slate-600 max-w-xl leading-relaxed text-balance">
                        Sistem digital untuk warga Banjar Bumi Shanti — laporkan penumpukan sampah dengan foto, bayar iuran bulanan,
                        dan pantau respon petugas dalam genggaman. Semua dalam satu aplikasi.
                    </p>
                    <div class="mt-8 flex flex-col sm:flex-row gap-3">
                        <x-button :href="route('register')" variant="primary" size="lg">
                            Daftar Sekarang
                            <x-slot:icon><svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></x-slot:icon>
                        </x-button>
                        <x-button :href="route('login')" variant="secondary" size="lg">Masuk ke Akun</x-button>
                    </div>

                    <div class="mt-10 flex items-center gap-6">
                        <div>
                            <p class="text-3xl font-display font-bold text-slate-900">2.134</p>
                            <p class="text-sm text-slate-500">KK terdaftar</p>
                        </div>
                        <div class="h-10 w-px bg-slate-200"></div>
                        <div>
                            <p class="text-3xl font-display font-bold text-slate-900">24/7</p>
                            <p class="text-sm text-slate-500">Pelaporan online</p>
                        </div>
                        <div class="h-10 w-px bg-slate-200"></div>
                        <div>
                            <p class="text-3xl font-display font-bold text-slate-900">100%</p>
                            <p class="text-sm text-slate-500">Transparan</p>
                        </div>
                    </div>
                </div>

                <div class="relative animate-slide-up hidden lg:block">
                    {{-- mockup card --}}
                    <div class="relative bg-white rounded-2xl shadow-xl border border-slate-200/70 p-6 max-w-md ml-auto">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="w-3 h-3 rounded-full bg-rose-300"></span>
                            <span class="w-3 h-3 rounded-full bg-amber-300"></span>
                            <span class="w-3 h-3 rounded-full bg-emerald-300"></span>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-slate-500">Laporan #LPS-20260519</p>
                                    <p class="font-semibold text-slate-900">Tumpukan sampah di Gg. Mawar</p>
                                </div>
                                <x-status-badge status="diproses" />
                            </div>
                            <div class="bg-gradient-to-br from-primary-400 to-primary-600 h-40 rounded-lg shadow-inner relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-mesh opacity-50"></div>
                                <div class="absolute bottom-3 left-3 text-white">
                                    <p class="text-xs opacity-80">📍 Banjar Bumi Shanti</p>
                                </div>
                            </div>
                            <x-timeline :items="[
                                ['title' => 'Dikirim', 'time' => 'Hari ini, 08:24', 'color' => 'sky'],
                                ['title' => 'Diterima petugas', 'time' => 'Hari ini, 09:10', 'color' => 'primary'],
                                ['title' => 'Sedang diproses', 'time' => 'Saat ini', 'color' => 'amber', 'active' => true],
                            ]" />
                        </div>
                    </div>
                    {{-- decorative element --}}
                    <div class="absolute -top-8 -right-8 w-32 h-32 rounded-full bg-amber-300/30 blur-3xl -z-10"></div>
                    <div class="absolute -bottom-12 -left-12 w-40 h-40 rounded-full bg-primary-300/30 blur-3xl -z-10"></div>
                </div>
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section class="py-16 sm:py-24 bg-white border-y border-slate-100">
        <div class="container-app">
            <div class="text-center max-w-2xl mx-auto">
                <h2 class="text-3xl sm:text-4xl font-display font-bold text-slate-900 text-balance">Tiga langkah, lingkungan lebih bersih</h2>
                <p class="mt-3 text-slate-600">Semua layanan kebersihan banjar, kini dalam satu tempat yang mudah, transparan, dan cepat.</p>
            </div>

            <div class="mt-12 grid md:grid-cols-3 gap-6">
                @php
                    $features = [
                        [
                            'title' => 'Lapor Mudah',
                            'desc'  => 'Cukup foto, isi lokasi & jenis sampah, lalu kirim. Petugas terima notifikasi seketika.',
                            'color' => 'primary',
                            'svg'   => '<svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>',
                        ],
                        [
                            'title' => 'Iuran Transparan',
                            'desc'  => 'Tagihan bulanan otomatis, pembayaran tercatat digital. Tidak lagi rebutan buku catatan.',
                            'color' => 'amber',
                            'svg'   => '<svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>',
                        ],
                        [
                            'title' => 'Notifikasi Real-time',
                            'desc'  => 'Setiap perubahan status laporan & tagihan langsung dikirim ke dalam aplikasi. Tidak ada yang terlewat.',
                            'color' => 'sky',
                            'svg'   => '<svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9a6 6 0 00-12 0v.75A8.967 8.967 0 013.69 15.77c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>',
                        ],
                    ];
                @endphp
                @foreach($features as $f)
                    @php
                        $palette = [
                            'primary' => 'bg-primary-50 text-primary-600',
                            'amber'   => 'bg-amber-50 text-amber-600',
                            'sky'     => 'bg-sky-50 text-sky-600',
                        ];
                    @endphp
                    <x-card hover class="h-full">
                        <div class="w-14 h-14 rounded-xl flex items-center justify-center {{ $palette[$f['color']] }} [&_svg]:w-7 [&_svg]:h-7 mb-5">
                            {!! $f['svg'] !!}
                        </div>
                        <h3 class="font-display font-semibold text-xl text-slate-900">{{ $f['title'] }}</h3>
                        <p class="mt-2 text-slate-600 leading-relaxed">{{ $f['desc'] }}</p>
                    </x-card>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Tentang Banjar --}}
    <section class="py-16 sm:py-24">
        <div class="container-app">
            <div class="grid lg:grid-cols-2 gap-10 items-center">
                <div>
                    <x-badge variant="primary">Tentang Kami</x-badge>
                    <h2 class="mt-4 text-3xl sm:text-4xl font-display font-bold text-slate-900 text-balance">Banjar Bumi Shanti</h2>
                    <p class="mt-4 text-slate-600 leading-relaxed">
                        Banjar Bumi Shanti adalah salah satu banjar di <strong>Desa Dauh Puri Kelod, Kecamatan Denpasar Barat, Bali</strong>,
                        dengan total <strong>2.134 Kepala Keluarga</strong>. SIPLAS dibangun untuk memudahkan koordinasi pengelolaan sampah
                        dan pencatatan iuran yang sebelumnya dilakukan secara manual.
                    </p>
                    <p class="mt-3 text-slate-600 leading-relaxed">
                        Sistem ini dipimpin oleh <strong>I Dewa Gede Satia Putra</strong> sebagai Kepala Lingkungan,
                        dibantu oleh petugas kebersihan dan dukungan seluruh warga banjar.
                    </p>
                </div>
                <x-card class="!p-8 bg-gradient-to-br from-primary-500 to-primary-700 !border-0 text-white">
                    <h3 class="font-display font-bold text-2xl">Bergabung dengan SIPLAS</h3>
                    <p class="mt-2 text-white/85 leading-relaxed">
                        Warga Banjar Bumi Shanti dapat segera mendaftar dan mulai menggunakan layanan setelah akun
                        Anda disetujui oleh admin banjar.
                    </p>
                    <ul class="mt-5 space-y-2 text-sm text-white/90">
                        <li class="flex gap-2 items-start"><span>✓</span> Pelaporan sampah dengan foto & lokasi</li>
                        <li class="flex gap-2 items-start"><span>✓</span> Pembayaran iuran digital, bukti tersimpan rapi</li>
                        <li class="flex gap-2 items-start"><span>✓</span> Notifikasi otomatis tiap perubahan status</li>
                        <li class="flex gap-2 items-start"><span>✓</span> Gratis untuk seluruh warga banjar</li>
                    </ul>
                    <div class="mt-6">
                        <x-button :href="route('register')" variant="dark" size="lg" class="!bg-white !text-primary-700 hover:!bg-slate-50">Daftar Akun Warga</x-button>
                    </div>
                </x-card>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-slate-200/70 py-10">
        <div class="container-app">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-3 text-sm text-slate-500">
                    <x-application-logo :size="32" :showText="false" />
                    <span>SIPLAS Banjar Bumi Shanti © {{ date('Y') }}</span>
                </div>
                <div class="text-xs text-slate-400">Made with 💚 in Bali</div>
            </div>
        </div>
    </footer>
</x-guest-layout>
