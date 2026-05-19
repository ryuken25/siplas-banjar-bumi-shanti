<x-guest-layout :title="'Atur Ulang Kata Sandi'">
    <div class="container-app min-h-screen flex items-center justify-center py-10">
        <x-card class="max-w-md w-full !p-8 animate-fade-in">
            <a href="/" class="inline-block mb-6"><x-application-logo :size="40" /></a>

            <h1 class="text-2xl font-display font-bold text-slate-900">Atur Ulang Kata Sandi</h1>
            <p class="mt-2 text-slate-500 text-sm">Masukkan kata sandi baru Anda untuk melanjutkan.</p>

            <form method="POST" action="{{ route('password.store') }}" class="mt-6 space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                <x-input label="Email" name="email" type="email" :value="old('email', $request->email)" required autofocus />
                <x-input label="Kata Sandi Baru" name="password" type="password" required helper="Minimal 8 karakter, huruf besar, kecil, dan angka." />
                <x-input label="Konfirmasi Kata Sandi" name="password_confirmation" type="password" required />
                <x-button type="submit" fullWidth size="lg">Atur Ulang Kata Sandi</x-button>
            </form>
        </x-card>
    </div>
</x-guest-layout>
