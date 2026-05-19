<x-guest-layout :title="'Masuk — SIPLAS'">
    <div class="grid lg:grid-cols-2 min-h-screen">
        {{-- Form column --}}
        <div class="flex items-center justify-center p-6 sm:p-10">
            <div class="w-full max-w-md animate-fade-in">
                <a href="/" class="inline-block mb-8">
                    <x-application-logo :size="44" />
                </a>

                <h1 class="text-3xl sm:text-4xl font-display font-bold text-slate-900 text-balance">Selamat datang kembali</h1>
                <p class="text-slate-500 mt-2">Masuk untuk mengakses layanan SIPLAS Banjar Bumi Shanti.</p>

                @if(session('status'))
                    <div class="mt-6"><x-alert variant="success">{{ session('status') }}</x-alert></div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-5">
                    @csrf

                    <x-input label="Email" name="email" type="email" placeholder="nama@email.com" :value="old('email')" required autofocus
                        :icon="'<svg fill=\'none\' stroke=\'currentColor\' stroke-width=\'2\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z\'/></svg>'" />

                    <x-input label="Kata Sandi" name="password" type="password" placeholder="••••••••" required autocomplete="current-password"
                        :icon="'<svg fill=\'none\' stroke=\'currentColor\' stroke-width=\'2\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z\'/></svg>'" />

                    <div class="flex items-center justify-between">
                        <label class="inline-flex items-center gap-2 select-none">
                            <input type="checkbox" name="remember" class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                            <span class="text-sm text-slate-700">Ingat saya</span>
                        </label>
                        @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm font-medium text-primary-700 hover:text-primary-800">Lupa kata sandi?</a>
                        @endif
                    </div>

                    <x-button type="submit" size="lg" fullWidth>Masuk ke Akun</x-button>

                    <p class="text-center text-sm text-slate-500">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="font-semibold text-primary-700 hover:text-primary-800">Daftar di sini</a>
                    </p>
                </form>
            </div>
        </div>

        {{-- Illustration column --}}
        <div class="hidden lg:flex relative bg-gradient-to-br from-primary-500 to-primary-700 items-center justify-center overflow-hidden">
            <div class="absolute inset-0 bg-gradient-mesh opacity-30"></div>
            <div class="absolute -bottom-32 -right-32 w-96 h-96 rounded-full bg-primary-300/30 blur-3xl"></div>
            <div class="absolute -top-24 -left-24 w-72 h-72 rounded-full bg-amber-300/20 blur-3xl"></div>

            <div class="relative max-w-md px-10 text-white">
                <svg class="w-28 h-28 mb-6 text-white/90" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2 21c0-3 1.85-5.36 5.08-6"/>
                </svg>
                <h2 class="font-display text-3xl font-bold leading-tight text-balance">Lingkungan Bersih, Banjar Sehat.</h2>
                <p class="mt-4 text-white/85 leading-relaxed">
                    SIPLAS membantu warga Banjar Bumi Shanti melaporkan sampah, membayar iuran, dan
                    memantau perkembangan layanan kebersihan — semua dalam satu tempat.
                </p>
                <div class="mt-8 flex items-center gap-4">
                    <div class="flex -space-x-2">
                        @foreach(['#FBBF24','#34D399','#0EA5E9'] as $c)
                            <span class="w-8 h-8 rounded-full ring-2 ring-white" style="background:{{ $c }}"></span>
                        @endforeach
                    </div>
                    <p class="text-sm text-white/80">2.134 KK Banjar Bumi Shanti</p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
