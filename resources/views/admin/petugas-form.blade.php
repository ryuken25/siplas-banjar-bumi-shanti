@php $isEdit = isset($user); @endphp

<x-layouts.dashboard :title="$isEdit ? 'Edit Petugas' : 'Tambah Petugas'">
    <x-slot:header>{{ $isEdit ? 'Edit Petugas' : 'Tambah Petugas Baru' }}</x-slot:header>
    <x-slot:actions>
        <x-button :href="route('admin.petugas.index')" variant="tertiary">← Kembali</x-button>
    </x-slot:actions>

    <x-card class="max-w-2xl !p-6">
        <form method="POST" action="{{ $isEdit ? route('admin.petugas.update', $user->id) : route('admin.petugas.store') }}" class="space-y-4">
            @csrf
            @if($isEdit) @method('PUT') @endif

            <div class="grid sm:grid-cols-2 gap-4">
                <x-input label="Nama Lengkap" name="name" :value="old('name', $user->name ?? '')" required />
                <x-input label="Email" name="email" type="email" :value="old('email', $user->email ?? '')" required />
                @if(! $isEdit)
                    <x-input label="NIK" name="nik" :value="old('nik')" helper="Opsional (16 digit)" />
                @endif
                <x-input label="Nomor HP" name="no_telp" :value="old('no_telp', $user->no_telp ?? '')" required />
            </div>
            <x-textarea label="Alamat" name="alamat" :value="old('alamat', $user->alamat ?? '')" rows="2" />

            <div class="grid sm:grid-cols-2 gap-4 pt-3 border-t border-slate-100">
                <x-input label="{{ $isEdit ? 'Kata Sandi Baru (opsional)' : 'Kata Sandi' }}" name="password" type="password" :required="!$isEdit" />
                @if($isEdit)
                    <x-select label="Status Akun" name="status_akun" :options="['aktif' => 'Aktif', 'nonaktif' => 'Nonaktif']" :value="old('status_akun', $user->status_akun)" :placeholder="null" required />
                @endif
            </div>

            <div class="flex justify-end gap-2 pt-4">
                <x-button :href="route('admin.petugas.index')" variant="tertiary">Batal</x-button>
                <x-button type="submit">{{ $isEdit ? 'Perbarui' : 'Tambah Petugas' }}</x-button>
            </div>
        </form>
    </x-card>
</x-layouts.dashboard>
