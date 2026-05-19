@php
    $isEdit = isset($tarif);
    $tarifData = $isEdit ? $tarif : null;
@endphp
<x-layouts.dashboard :title="$isEdit ? 'Edit Tarif' : 'Tarif Baru'">
    <x-slot:header>{{ $isEdit ? 'Edit Tarif' : 'Tarif Iuran Baru' }}</x-slot:header>
    <x-slot:actions>
        <x-button :href="route('admin.tarif.index')" variant="tertiary">← Kembali</x-button>
    </x-slot:actions>

    <x-card class="max-w-2xl">
        <form method="POST" action="{{ $isEdit ? route('admin.tarif.update', $tarifData->id) : route('admin.tarif.store') }}" class="space-y-4">
            @csrf @if($isEdit) @method('PUT') @endif
            <x-input label="Nominal (Rupiah)" name="nominal" type="number" :value="old('nominal', $tarifData?->nominal ?? 25000)" required helper="Antara Rp 1.000 dan Rp 1.000.000" />
            <x-input label="Mulai Berlaku" name="periode_mulai" type="date" :value="old('periode_mulai', $tarifData?->periode_mulai?->format('Y-m-d') ?? now()->format('Y-m-d'))" required />
            <x-textarea label="Keterangan" name="keterangan" :value="old('keterangan', $tarifData?->keterangan ?? '')" rows="3" />

            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="aktif" value="1" {{ old('aktif', $tarifData?->aktif ?? true) ? 'checked' : '' }} class="rounded border-slate-300 text-primary-600">
                <span class="text-sm font-medium text-slate-700">Aktifkan tarif ini (tarif aktif lainnya akan dinonaktifkan)</span>
            </label>

            <div class="flex justify-end gap-2 pt-3">
                <x-button :href="route('admin.tarif.index')" variant="tertiary">Batal</x-button>
                <x-button type="submit">{{ $isEdit ? 'Perbarui' : 'Simpan' }}</x-button>
            </div>
        </form>
    </x-card>
</x-layouts.dashboard>
