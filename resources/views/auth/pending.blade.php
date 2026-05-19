<x-guest-layout :title="'Menunggu Persetujuan'">
    <div class="container-app min-h-screen flex items-center justify-center py-10">
        <x-card class="max-w-lg mx-auto text-center animate-fade-in !p-10">
            <div class="mx-auto w-20 h-20 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mb-5">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h1 class="text-2xl sm:text-3xl font-display font-bold text-slate-900">Pendaftaran Berhasil!</h1>
            <p class="mt-3 text-slate-600 leading-relaxed">
                Terima kasih telah mendaftar di SIPLAS. Akun Anda saat ini <span class="font-semibold text-amber-700">menunggu persetujuan</span>
                dari admin Banjar Bumi Shanti.
            </p>
            <p class="mt-2 text-sm text-slate-500">
                Anda akan menerima notifikasi setelah akun Anda disetujui. Mohon menunggu beberapa saat.
            </p>

            <div class="mt-7 flex flex-col sm:flex-row gap-2 justify-center">
                <x-button :href="route('login')" variant="secondary">Kembali ke Halaman Masuk</x-button>
                <x-button :href="url('/')" variant="tertiary">Beranda</x-button>
            </div>
        </x-card>
    </div>
</x-guest-layout>
