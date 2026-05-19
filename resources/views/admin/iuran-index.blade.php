<x-layouts.dashboard :title="'Iuran & Tagihan'">
    <x-slot:header>Iuran & Tagihan Bulanan</x-slot:header>
    <x-slot:subheader>Pantau seluruh tagihan iuran sampah warga.</x-slot:subheader>
    <x-slot:actions>
        <x-button x-data x-on:click="$dispatch('open-modal', 'generate-tagihan')">+ Generate Tagihan Bulan Ini</x-button>
    </x-slot:actions>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
        <x-stat-card label="Lunas" :value="$ringkasan['lunas']" color="primary" />
        <x-stat-card label="Menunggu Verifikasi" :value="$ringkasan['menunggu']" color="amber" />
        <x-stat-card label="Belum Bayar" :value="$ringkasan['belum']" color="rose" />
        <x-stat-card label="Total Terkumpul" :value="'Rp ' . number_format($ringkasan['total_nominal'], 0, ',', '.')" color="violet" />
    </div>

    <x-card class="mb-5">
        <form method="GET" class="grid sm:grid-cols-4 gap-3">
            <x-select label="Tahun" name="tahun" :options="collect(range(now()->year - 3, now()->year + 1))->mapWithKeys(fn($y) => [$y => $y])->all()" :value="$tahun" :placeholder="null" />
            <x-select label="Bulan" name="bulan" :options="\App\Models\IuranBulanan::BULAN_LABEL" :value="$bulan" placeholder="Semua bulan" />
            <x-select label="Status" name="status" :options="['belum_bayar' => 'Belum Bayar', 'menunggu_verifikasi' => 'Menunggu Verifikasi', 'lunas' => 'Lunas', 'ditolak' => 'Ditolak']" :value="request('status')" placeholder="Semua status" />
            <div class="flex items-end"><x-button type="submit" fullWidth>Filter</x-button></div>
        </form>
    </x-card>

    @if($iuran->isEmpty())
        <x-card><x-empty-state title="Belum ada iuran pada periode ini" /></x-card>
    @else
        <x-card class="!p-0">
            <x-table>
                <thead class="bg-slate-50">
                    <tr class="text-left text-xs uppercase tracking-wider text-slate-500">
                        <th class="px-5 py-3">Kode</th>
                        <th class="px-5 py-3">Warga</th>
                        <th class="px-5 py-3">Periode</th>
                        <th class="px-5 py-3">Nominal</th>
                        <th class="px-5 py-3">Metode</th>
                        <th class="px-5 py-3">Verifikator</th>
                        <th class="px-5 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($iuran as $i)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-3 font-mono text-xs">{{ $i->kode_tagihan }}</td>
                            <td class="px-5 py-3">{{ $i->warga?->name }}</td>
                            <td class="px-5 py-3 text-sm">{{ $i->periode_label }}</td>
                            <td class="px-5 py-3 font-semibold tabular-nums">{{ $i->nominal_formatted }}</td>
                            <td class="px-5 py-3 text-sm capitalize">{{ $i->metode_bayar ?? '—' }}</td>
                            <td class="px-5 py-3 text-sm text-slate-600">{{ $i->verifikator?->name ?? '—' }}</td>
                            <td class="px-5 py-3"><x-status-badge :status="$i->status" /></td>
                        </tr>
                    @endforeach
                </tbody>
            </x-table>
        </x-card>
        <div>{{ $iuran->links() }}</div>
    @endif

    <x-modal name="generate-tagihan" maxWidth="lg" title="Generate Tagihan Bulanan">
        <form method="POST" action="{{ route('admin.iuran.generate') }}" class="space-y-4">
            @csrf
            <p class="text-sm text-slate-600">
                Sistem akan membuat tagihan iuran untuk seluruh warga aktif yang belum memiliki tagihan pada bulan & tahun yang dipilih.
            </p>
            <div class="grid grid-cols-2 gap-3">
                <x-select label="Bulan" name="bulan" :options="\App\Models\IuranBulanan::BULAN_LABEL" :value="now()->month" :placeholder="null" required />
                <x-select label="Tahun" name="tahun" :options="collect(range(now()->year - 1, now()->year + 1))->mapWithKeys(fn($y) => [$y => $y])->all()" :value="now()->year" :placeholder="null" required />
            </div>
            <div class="flex justify-end gap-2">
                <x-button type="button" variant="tertiary" x-on:click="$dispatch('close-modal', 'generate-tagihan')">Batal</x-button>
                <x-button type="submit">Generate Tagihan</x-button>
            </div>
        </form>
    </x-modal>
</x-layouts.dashboard>
