<x-layouts.dashboard :title="'Tarif Iuran'">
    <x-slot:header>Tarif Iuran Sampah</x-slot:header>
    <x-slot:subheader>Riwayat perubahan tarif iuran banjar.</x-slot:subheader>
    <x-slot:actions>
        <x-button :href="route('admin.tarif.create')">+ Tarif Baru</x-button>
    </x-slot:actions>

    @if($tarif->isEmpty())
        <x-card><x-empty-state title="Belum ada tarif" description="Tambahkan tarif iuran pertama.">
            <x-slot:action><x-button :href="route('admin.tarif.create')">Tambah Tarif</x-button></x-slot:action>
        </x-empty-state></x-card>
    @else
        <x-card class="!p-0">
            <x-table>
                <thead class="bg-slate-50">
                    <tr class="text-left text-xs uppercase tracking-wider text-slate-500">
                        <th class="px-5 py-3">Nominal</th>
                        <th class="px-5 py-3">Mulai Berlaku</th>
                        <th class="px-5 py-3">Keterangan</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($tarif as $t)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-3 font-display font-bold tabular-nums text-lg">{{ $t->nominal_formatted }}</td>
                            <td class="px-5 py-3">{{ $t->periode_mulai?->translatedFormat('d M Y') }}</td>
                            <td class="px-5 py-3 text-sm text-slate-600 max-w-md">{{ $t->keterangan ?? '-' }}</td>
                            <td class="px-5 py-3">
                                @if($t->aktif) <x-badge variant="success">Aktif</x-badge>
                                @else <x-badge variant="neutral">Nonaktif</x-badge> @endif
                            </td>
                            <td class="px-5 py-3 text-right">
                                <div class="inline-flex gap-1">
                                    <x-button :href="route('admin.tarif.edit', $t->id)" variant="secondary" size="xs">Edit</x-button>
                                    <form method="POST" action="{{ route('admin.tarif.destroy', $t->id) }}" onsubmit="return confirm('Hapus tarif ini?');" class="inline">
                                        @csrf @method('DELETE')
                                        <x-button type="submit" variant="danger" size="xs">Hapus</x-button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-table>
        </x-card>
        <div>{{ $tarif->links() }}</div>
    @endif
</x-layouts.dashboard>
