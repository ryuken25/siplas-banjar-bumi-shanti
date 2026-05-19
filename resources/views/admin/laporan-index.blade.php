<x-layouts.dashboard :title="'Laporan Sampah'">
    <x-slot:header>Semua Laporan Sampah</x-slot:header>
    <x-slot:subheader>Pantauan global laporan dari seluruh warga.</x-slot:subheader>
    <x-slot:actions>
        <x-button :href="route('admin.laporan.export', request()->query())" variant="secondary">⬇ Export CSV</x-button>
    </x-slot:actions>

    <x-card class="mb-5">
        <form method="GET" class="grid sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <x-select name="status" :options="$statusOptions" :value="request('status')" :placeholder="null" />
            <x-select name="jenis" :options="$jenisOptions" :value="request('jenis')" :placeholder="null" />
            <x-input name="from" type="date" :value="request('from')" />
            <x-input name="to" type="date" :value="request('to')" />
            <div class="flex gap-2">
                <x-input name="search" :value="request('search')" placeholder="Cari kode/pelapor" class="flex-1" />
                <x-button type="submit">Cari</x-button>
            </div>
        </form>
    </x-card>

    @if($laporan->isEmpty())
        <x-card><x-empty-state title="Tidak ada laporan ditemukan" /></x-card>
    @else
        <x-card class="!p-0">
            <x-table>
                <thead class="bg-slate-50">
                    <tr class="text-left text-xs uppercase tracking-wider text-slate-500">
                        <th class="px-5 py-3">Kode</th>
                        <th class="px-5 py-3">Pelapor</th>
                        <th class="px-5 py-3">Jenis</th>
                        <th class="px-5 py-3">Petugas</th>
                        <th class="px-5 py-3">Tanggal</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($laporan as $l)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-3 font-mono text-xs">{{ $l->kode_laporan }}</td>
                            <td class="px-5 py-3">{{ $l->pelapor?->name }}</td>
                            <td class="px-5 py-3 text-sm">{{ $l->jenis_label }}</td>
                            <td class="px-5 py-3 text-sm text-slate-600">{{ $l->petugas?->name ?? '—' }}</td>
                            <td class="px-5 py-3 text-xs text-slate-500">{{ $l->tanggal_lapor?->translatedFormat('d M, H:i') }}</td>
                            <td class="px-5 py-3"><x-status-badge :status="$l->status" /></td>
                            <td class="px-5 py-3 text-right">
                                <x-button :href="route('admin.laporan.show', $l->id)" size="xs" variant="ghost">Detail</x-button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-table>
        </x-card>
        <div>{{ $laporan->links() }}</div>
    @endif
</x-layouts.dashboard>
