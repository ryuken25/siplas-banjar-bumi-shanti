<x-guest-layout :title="'Daftar — SIPLAS'">
    <div class="container-app py-10 sm:py-16">
        <div class="max-w-2xl mx-auto animate-fade-in">
            <a href="/" class="inline-block mb-6">
                <x-application-logo :size="44" />
            </a>

            <h1 class="text-3xl sm:text-4xl font-display font-bold text-slate-900 text-balance">Buat Akun Warga Baru</h1>
            <p class="text-slate-500 mt-2">Daftarkan diri Anda sebagai warga Banjar Bumi Shanti untuk mengakses layanan SIPLAS.</p>

            <x-card class="mt-8 !p-6 sm:!p-8">
                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <div>
                        <h2 class="font-display font-semibold text-slate-900 mb-3">Identitas Pribadi</h2>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <x-input label="Nama Lengkap" name="name" :value="old('name')" required autofocus placeholder="Sesuai KTP" />
                            <x-input label="NIK" name="nik" :value="old('nik')" required placeholder="16 digit NIK" helper="Nomor Induk Kependudukan (16 digit)" />
                            <x-input label="Nomor KK" name="no_kk" :value="old('no_kk')" required placeholder="16 digit Nomor KK" />
                            <x-input label="Nomor HP" name="no_telp" :value="old('no_telp')" required placeholder="08xxxxxxxxxx" type="tel" />
                        </div>
                        <x-textarea label="Alamat (di wilayah Banjar Bumi Shanti)" name="alamat" :value="old('alamat')" required rows="2" placeholder="Contoh: Gang Mawar No. 12" class="mt-4" />
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        <h2 class="font-display font-semibold text-slate-900 mb-3">Informasi Akun</h2>
                        <x-input label="Email" name="email" type="email" :value="old('email')" required placeholder="nama@email.com" autocomplete="username" />
                        <div class="grid sm:grid-cols-2 gap-4 mt-4">
                            <x-input label="Kata Sandi" name="password" type="password" required autocomplete="new-password" helper="Minimal 8 karakter, kombinasi huruf besar, kecil, dan angka." />
                            <x-input label="Konfirmasi Kata Sandi" name="password_confirmation" type="password" required autocomplete="new-password" />
                        </div>
                    </div>

                    <div class="flex items-start gap-2 pt-2">
                        <span class="text-xs text-slate-500 leading-relaxed">
                            ⓘ Dengan mendaftar, Anda menyetujui bahwa data Anda akan diverifikasi oleh pengurus banjar.
                            Akun akan aktif setelah disetujui admin.
                        </span>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-2">
                        <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">
                            Sudah punya akun? <span class="text-primary-700">Masuk di sini</span>
                        </a>
                        <x-button type="submit" size="lg" class="sm:!w-auto !w-full">Daftar Sekarang</x-button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-guest-layout>
