<x-layouts.dashboard :title="'Manajemen Pengguna'">
    <x-slot:header>Manajemen Pengguna Warga</x-slot:header>
    <x-slot:subheader>Kelola pendaftaran, aktivasi, dan status akun warga.</x-slot:subheader>

    <div class="mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div class="inline-flex bg-slate-100 rounded-lg p-1 overflow-x-auto scrollbar-thin">
            @foreach(['pending' => 'Pending', 'aktif' => 'Aktif', 'nonaktif' => 'Nonaktif'] as $key => $label)
                <a href="{{ route('admin.pengguna.index', ['tab' => $key]) }}"
                   class="relative px-4 py-2 text-sm font-medium rounded-md transition whitespace-nowrap {{ $tab === $key ? 'bg-white shadow-sm text-primary-700' : 'text-slate-600 hover:text-slate-900' }}">
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
            <x-input name="search" :value="request('search')" placeholder="Cari nama / NIK / email" class="min-w-0 flex-1 sm:w-64" />
            <x-button type="submit" variant="primary">Cari</x-button>
        </form>
    </div>

    @if($pengguna->isEmpty())
        <x-card><x-empty-state title="Tidak ada pengguna di kategori ini" description="Coba ubah filter pencarian atau pilih tab lain." /></x-card>
    @else
        <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach($pengguna as $p)
                <x-card class="!p-5 flex flex-col">
                    <div class="flex items-start gap-3">
                        <x-avatar :user="$p" size="lg" />
                        <div class="min-w-0 flex-1">
                            <p class="font-semibold text-slate-900 truncate">{{ $p->name }}</p>
                            <p class="text-xs text-slate-500 truncate">{{ $p->email }}</p>
                            <div class="mt-1.5"><x-status-badge :status="$p->status_akun" size="xs" /></div>
                        </div>
                    </div>

                    <dl class="mt-4 space-y-1.5 text-sm border-t border-slate-100 pt-3">
                        <div class="flex justify-between gap-3">
                            <dt class="text-slate-500 shrink-0">NIK</dt>
                            <dd class="font-mono text-xs text-slate-700 truncate">{{ $p->nik ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-slate-500 shrink-0">No. KK</dt>
                            <dd class="font-mono text-xs text-slate-700 truncate">{{ $p->no_kk ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-slate-500 shrink-0">No. HP</dt>
                            <dd class="text-slate-700">{{ $p->no_telp ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-slate-500 shrink-0">Alamat</dt>
                            <dd class="text-slate-700 text-right line-clamp-2">{{ $p->alamat ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-slate-500 shrink-0">Tgl Daftar</dt>
                            <dd class="text-slate-700">{{ $p->created_at?->translatedFormat('d M Y') }}</dd>
                        </div>
                    </dl>

                    @if($p->alasan_tolak_akun && $tab === 'nonaktif')
                        <p class="mt-3 text-xs text-rose-600 bg-rose-50 border border-rose-100 rounded-lg px-3 py-2">
                            ⚠ {{ $p->alasan_tolak_akun }}
                        </p>
                    @endif

                    <div class="mt-4 pt-3 border-t border-slate-100">
                        @if($tab === 'pending')
                            <div class="grid grid-cols-2 gap-2">
                                <form method="POST" action="{{ route('admin.pengguna.approve', $p->id) }}">
                                    @csrf <x-button type="submit" variant="success" size="sm" fullWidth>Setujui</x-button>
                                </form>
                                <x-button x-data x-on:click="$dispatch('open-modal', 'reject-{{ $p->id }}')" variant="danger" size="sm" fullWidth>Tolak</x-button>
                            </div>
                            <x-modal :name="'reject-' . $p->id" maxWidth="md" :title="'Tolak Pendaftaran ' . $p->name">
                                <form method="POST" action="{{ route('admin.pengguna.reject', $p->id) }}" class="space-y-3">
                                    @csrf
                                    <x-textarea label="Alasan Penolakan" name="alasan" required rows="3" placeholder="Jelaskan alasan penolakan..." />
                                    <div class="flex justify-end gap-2">
                                        <x-button type="button" variant="tertiary" x-on:click="$dispatch('close-modal', 'reject-{{ $p->id }}')">Batal</x-button>
                                        <x-button type="submit" variant="danger">Tolak Pendaftaran</x-button>
                                    </div>
                                </form>
                            </x-modal>
                        @elseif($tab === 'aktif')
                            <form method="POST" action="{{ route('admin.pengguna.nonaktifkan', $p->id) }}" onsubmit="return confirm('Nonaktifkan akun {{ $p->name }}?');">
                                @csrf <x-button type="submit" variant="secondary" size="sm" fullWidth>Nonaktifkan Akun</x-button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.pengguna.aktifkan', $p->id) }}">
                                @csrf <x-button type="submit" variant="success" size="sm" fullWidth>Aktifkan Kembali</x-button>
                            </form>
                        @endif
                    </div>
                </x-card>
            @endforeach
        </div>
        <div>{{ $pengguna->links() }}</div>
    @endif
</x-layouts.dashboard>
