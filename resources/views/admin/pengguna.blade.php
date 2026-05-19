<x-layouts.dashboard :title="'Manajemen Pengguna'">
    <x-slot:header>Manajemen Pengguna Warga</x-slot:header>
    <x-slot:subheader>Kelola pendaftaran, aktivasi, dan status akun warga.</x-slot:subheader>

    <div class="mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div class="inline-flex bg-slate-100 rounded-lg p-1">
            @foreach(['pending' => 'Pending', 'aktif' => 'Aktif', 'nonaktif' => 'Nonaktif'] as $key => $label)
                <a href="{{ route('admin.pengguna.index', ['tab' => $key]) }}"
                   class="relative px-4 py-2 text-sm font-medium rounded-md transition {{ $tab === $key ? 'bg-white shadow-sm text-primary-700' : 'text-slate-600 hover:text-slate-900' }}">
                    {{ $label }}
                    @if($counts[$key] > 0)
                        <span class="ml-1 inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full text-[10px] font-bold {{ $tab === $key ? 'bg-primary-100 text-primary-800' : 'bg-slate-200 text-slate-700' }}">
                            {{ $counts[$key] }}
                        </span>
                    @endif
                </a>
            @endforeach
        </div>
        <form method="GET" class="flex gap-2">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <x-input name="search" :value="request('search')" placeholder="Cari nama / NIK / email" />
            <x-button type="submit" variant="primary">Cari</x-button>
        </form>
    </div>

    @if($pengguna->isEmpty())
        <x-card><x-empty-state title="Tidak ada pengguna di kategori ini" description="Coba ubah filter pencarian." /></x-card>
    @else
        <x-card class="!p-0">
            <x-table>
                <thead class="bg-slate-50">
                    <tr class="text-left text-xs uppercase tracking-wider text-slate-500">
                        <th class="px-5 py-3">Warga</th>
                        <th class="px-5 py-3">NIK / KK</th>
                        <th class="px-5 py-3">No HP</th>
                        <th class="px-5 py-3">Alamat</th>
                        <th class="px-5 py-3">Tgl Daftar</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($pengguna as $p)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <x-avatar :user="$p" size="md" />
                                    <div>
                                        <p class="font-medium text-slate-900">{{ $p->name }}</p>
                                        <p class="text-xs text-slate-500">{{ $p->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-xs font-mono text-slate-700">
                                <p>{{ $p->nik ?? '-' }}</p>
                                <p class="text-slate-400">{{ $p->no_kk ?? '-' }}</p>
                            </td>
                            <td class="px-5 py-3 text-sm">{{ $p->no_telp ?? '-' }}</td>
                            <td class="px-5 py-3 text-sm text-slate-600 max-w-[200px] truncate">{{ $p->alamat ?? '-' }}</td>
                            <td class="px-5 py-3 text-xs text-slate-500">{{ $p->created_at?->translatedFormat('d M Y') }}</td>
                            <td class="px-5 py-3 text-right">
                                <div class="inline-flex gap-1 justify-end">
                                    @if($tab === 'pending')
                                        <form method="POST" action="{{ route('admin.pengguna.approve', $p->id) }}" class="inline">
                                            @csrf <x-button type="submit" variant="success" size="xs">Approve</x-button>
                                        </form>
                                        <x-button x-data x-on:click="$dispatch('open-modal', 'reject-{{ $p->id }}')" variant="danger" size="xs">Tolak</x-button>
                                        <x-modal :name="'reject-' . $p->id" maxWidth="md" :title="'Tolak Pendaftaran ' . $p->name">
                                            <form method="POST" action="{{ route('admin.pengguna.reject', $p->id) }}" class="space-y-3">
                                                @csrf
                                                <x-textarea label="Alasan" name="alasan" required rows="3" />
                                                <div class="flex justify-end gap-2">
                                                    <x-button type="button" variant="tertiary" x-on:click="$dispatch('close-modal', 'reject-{{ $p->id }}')">Batal</x-button>
                                                    <x-button type="submit" variant="danger">Tolak</x-button>
                                                </div>
                                            </form>
                                        </x-modal>
                                    @elseif($tab === 'aktif')
                                        <form method="POST" action="{{ route('admin.pengguna.nonaktifkan', $p->id) }}" onsubmit="return confirm('Nonaktifkan akun ini?');" class="inline">
                                            @csrf <x-button type="submit" variant="ghost" size="xs">Nonaktifkan</x-button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.pengguna.aktifkan', $p->id) }}" class="inline">
                                            @csrf <x-button type="submit" variant="success" size="xs">Aktifkan Kembali</x-button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-table>
        </x-card>
        <div>{{ $pengguna->links() }}</div>
    @endif
</x-layouts.dashboard>
