<x-layouts.dashboard :title="'Detail Laporan'">
    <x-slot:header>{{ $laporan->kode_laporan }}</x-slot:header>
    <x-slot:actions>
        <x-button :href="route('admin.laporan.index')" variant="tertiary">← Kembali</x-button>
    </x-slot:actions>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-5">
            <x-card class="!p-0 overflow-hidden">
                <img src="{{ $laporan->foto_url }}" class="w-full max-h-[480px] object-cover" alt="Foto">
            </x-card>
            <x-card>
                <h3 class="font-display font-semibold text-slate-900">Detail</h3>
                <dl class="mt-4 grid sm:grid-cols-2 gap-y-3 gap-x-6 text-sm">
                    <div><dt class="text-slate-500">Pelapor</dt><dd class="font-medium">{{ $laporan->pelapor?->name }}</dd></div>
                    <div><dt class="text-slate-500">Petugas</dt><dd class="font-medium">{{ $laporan->petugas?->name ?? '-' }}</dd></div>
                    <div><dt class="text-slate-500">Jenis</dt><dd>{{ $laporan->jenis_label }}</dd></div>
                    <div><dt class="text-slate-500">Status</dt><dd><x-status-badge :status="$laporan->status" /></dd></div>
                    <div class="sm:col-span-2"><dt class="text-slate-500">Lokasi</dt><dd>{{ $laporan->lokasi_text }}</dd></div>
                    <div class="sm:col-span-2"><dt class="text-slate-500">Keterangan</dt><dd>{{ $laporan->keterangan }}</dd></div>
                </dl>
            </x-card>
        </div>
        <x-card>
            <h3 class="font-display font-semibold text-slate-900 mb-3">Timeline</h3>
            @php
                $items = [['title' => 'Dikirim', 'time' => $laporan->tanggal_lapor?->translatedFormat('d M Y, H:i'), 'color' => 'sky']];
                if ($laporan->tanggal_diterima) $items[] = ['title' => 'Diterima', 'time' => $laporan->tanggal_diterima?->translatedFormat('d M Y, H:i'), 'color' => 'primary'];
                if ($laporan->tanggal_diproses) $items[] = ['title' => 'Diproses', 'time' => $laporan->tanggal_diproses?->translatedFormat('d M Y, H:i'), 'color' => 'amber'];
                if ($laporan->tanggal_selesai) $items[] = ['title' => 'Selesai', 'time' => $laporan->tanggal_selesai?->translatedFormat('d M Y, H:i'), 'color' => 'emerald'];
                if ($laporan->status === 'ditolak') $items[] = ['title' => 'Ditolak', 'description' => $laporan->alasan_tolak, 'color' => 'rose'];
            @endphp
            <x-timeline :items="$items" />
        </x-card>
    </div>
</x-layouts.dashboard>
