<x-layouts.warga :title="'Profil Saya'">
    <x-slot:header>Profil Saya</x-slot:header>
    <x-slot:subheader>Perbarui informasi akun dan kata sandi Anda.</x-slot:subheader>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <x-card>
                <h3 class="font-display font-semibold text-slate-900">Informasi Profil</h3>
                <p class="text-sm text-slate-500 mt-0.5">Perbarui informasi akun dan email Anda.</p>

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-5 space-y-4">
                    @csrf @method('PATCH')

                    <div class="flex items-center gap-4 pb-4 border-b border-slate-100">
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

                    <div class="flex justify-end">
                        <x-button type="submit">Simpan Perubahan</x-button>
                    </div>
                </form>
            </x-card>

            <x-card>
                <h3 class="font-display font-semibold text-slate-900">Ubah Kata Sandi</h3>
                <p class="text-sm text-slate-500 mt-0.5">Gunakan kata sandi yang kuat dan unik.</p>

                <form method="POST" action="{{ route('password.update') }}" class="mt-5 space-y-4">
                    @csrf @method('PUT')
                    <x-input label="Kata Sandi Saat Ini" name="current_password" type="password" autocomplete="current-password" required :error="$errors->updatePassword->first('current_password')" />
                    <div class="grid sm:grid-cols-2 gap-4">
                        <x-input label="Kata Sandi Baru" name="password" type="password" autocomplete="new-password" required :error="$errors->updatePassword->first('password')" />
                        <x-input label="Konfirmasi Kata Sandi" name="password_confirmation" type="password" autocomplete="new-password" required />
                    </div>
                    <div class="flex justify-end"><x-button type="submit" variant="secondary">Perbarui Kata Sandi</x-button></div>
                </form>
            </x-card>

            <x-card class="border-rose-200/70">
                <h3 class="font-display font-semibold text-rose-700">Hapus Akun</h3>
                <p class="text-sm text-slate-500 mt-0.5">Setelah akun dihapus, semua data Anda akan hilang permanen.</p>
                <div class="mt-4">
                    <x-button x-data x-on:click="$dispatch('open-modal', 'confirm-delete')" variant="danger">Hapus Akun</x-button>
                </div>

                <x-modal name="confirm-delete" maxWidth="md" title="Hapus Akun Anda?">
                    <form method="POST" action="{{ route('profile.destroy') }}" class="space-y-4">
                        @csrf @method('DELETE')
                        <p class="text-sm text-slate-600">Tindakan ini tidak dapat dibatalkan. Masukkan kata sandi untuk konfirmasi.</p>
                        <x-input label="Kata Sandi" name="password" type="password" required :error="$errors->userDeletion->first('password')" />
                        <div class="flex justify-end gap-2">
                            <x-button type="button" variant="tertiary" x-on:click="$dispatch('close-modal', 'confirm-delete')">Batal</x-button>
                            <x-button type="submit" variant="danger">Ya, Hapus Akun</x-button>
                        </div>
                    </form>
                </x-modal>
            </x-card>
        </div>

        <x-card class="h-fit">
            <h3 class="font-display font-semibold text-slate-900">Status Akun</h3>
            <div class="mt-3 space-y-3 text-sm">
                <div class="flex justify-between"><span class="text-slate-500">Role</span><x-badge variant="primary">{{ $user->role_label }}</x-badge></div>
                <div class="flex justify-between"><span class="text-slate-500">Status</span><x-status-badge :status="$user->status_akun" /></div>
                <div class="flex justify-between"><span class="text-slate-500">No KK</span><span class="font-mono text-xs">{{ $user->no_kk ?? '-' }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">Bergabung</span><span class="font-medium">{{ $user->created_at?->translatedFormat('d M Y') }}</span></div>
            </div>
        </x-card>
    </div>
</x-layouts.warga>
