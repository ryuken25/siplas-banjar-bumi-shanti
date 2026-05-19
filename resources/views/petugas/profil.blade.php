<x-layouts.dashboard :title="'Profil Saya'">
    <x-slot:header>Profil Saya</x-slot:header>
    <x-slot:subheader>Perbarui informasi akun dan kata sandi Anda.</x-slot:subheader>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <x-card class="!p-6">
                <form method="POST" action="{{ route('petugas.profil.update') }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf @method('PATCH')

                    <div class="flex items-center gap-4 pb-5 border-b border-slate-100">
                        <x-avatar :user="$user" size="2xl" :ring="true" />
                        <div class="flex-1">
                            <p class="font-semibold text-slate-900">{{ $user->name }}</p>
                            <p class="text-sm text-slate-500">{{ $user->email }}</p>
                            <label for="foto_profil" class="inline-block mt-2 text-sm font-medium text-primary-700 hover:text-primary-800 cursor-pointer">Ubah Foto Profil</label>
                            <input type="file" id="foto_profil" name="foto_profil" accept="image/*" class="sr-only">
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <x-input label="Nama Lengkap" name="name" :value="old('name', $user->name)" required />
                        <x-input label="Email" name="email" type="email" :value="old('email', $user->email)" required />
                        <x-input label="Nomor HP" name="no_telp" :value="old('no_telp', $user->no_telp)" required />
                        <x-input label="NIK" :value="$user->nik" disabled helper="NIK tidak dapat diubah." />
                    </div>
                    <x-textarea label="Alamat" name="alamat" :value="old('alamat', $user->alamat)" rows="2" />

                    <div class="pt-5 border-t border-slate-100">
                        <h3 class="font-display font-semibold text-slate-900 mb-3">Ubah Kata Sandi</h3>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <x-input label="Kata Sandi Baru" name="password" type="password" helper="Kosongkan jika tidak ingin diubah." />
                            <x-input label="Konfirmasi Kata Sandi" name="password_confirmation" type="password" />
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <x-button type="submit">Simpan Perubahan</x-button>
                    </div>
                </form>
            </x-card>
        </div>

        <x-card class="h-fit">
            <h3 class="font-display font-semibold text-slate-900">Status Akun</h3>
            <div class="mt-3 space-y-3 text-sm">
                <div class="flex justify-between"><span class="text-slate-500">Role</span><x-badge variant="primary">{{ $user->role_label }}</x-badge></div>
                <div class="flex justify-between"><span class="text-slate-500">Status</span><x-status-badge :status="$user->status_akun" /></div>
                <div class="flex justify-between"><span class="text-slate-500">Bergabung</span><span class="font-medium">{{ $user->created_at?->translatedFormat('d M Y') }}</span></div>
            </div>
        </x-card>
    </div>
</x-layouts.dashboard>
