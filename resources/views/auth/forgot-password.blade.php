<x-guest-layout :title="'Lupa Kata Sandi'">
    <div class="container-app min-h-screen flex items-center justify-center py-10">
        <x-card class="max-w-md w-full !p-8 animate-fade-in">
            <a href="/" class="inline-block mb-6"><x-application-logo :size="40" /></a>

            <h1 class="text-2xl font-display font-bold text-slate-900">Lupa Kata Sandi?</h1>
            <p class="mt-2 text-slate-500 text-sm leading-relaxed">
                Tidak masalah. Cukup masukkan alamat email Anda dan kami akan mengirim tautan untuk mengatur ulang kata sandi.
            </p>

            @if(session('status'))
                <div class="mt-4"><x-alert variant="success">{{ session('status') }}</x-alert></div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-4">
                @csrf
                <x-input label="Email" name="email" type="email" :value="old('email')" required autofocus placeholder="nama@email.com" />
                <x-button type="submit" fullWidth size="lg">Kirim Tautan Reset</x-button>
                <a href="{{ route('login') }}" class="block text-center text-sm text-slate-600 hover:text-slate-900">← Kembali ke halaman masuk</a>
            </form>
        </x-card>
    </div>
</x-guest-layout>
