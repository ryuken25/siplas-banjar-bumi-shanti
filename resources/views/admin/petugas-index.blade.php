<x-layouts.dashboard :title="'Manajemen Petugas'">
    <x-slot:header>Manajemen Petugas</x-slot:header>
    <x-slot:subheader>Kelola data petugas kebersihan banjar.</x-slot:subheader>
    <x-slot:actions>
        <x-button :href="route('admin.petugas.create')" size="lg">+ Tambah Petugas</x-button>
    </x-slot:actions>

    @if($petugas->isEmpty())
        <x-card><x-empty-state title="Belum ada petugas" description="Tambahkan petugas pertama Anda.">
            <x-slot:action><x-button :href="route('admin.petugas.create')">Tambah Petugas</x-button></x-slot:action>
        </x-empty-state></x-card>
    @else
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($petugas as $p)
                <x-card class="!p-5 flex flex-col">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <x-avatar :user="$p" size="lg" />
                            <div>
                                <p class="font-semibold text-slate-900">{{ $p->name }}</p>
                                <p class="text-xs text-slate-500">{{ $p->email }}</p>
                            </div>
                        </div>
                        <x-status-badge :status="$p->status_akun" />
                    </div>
                    <dl class="text-sm space-y-1 mt-4">
                        <div class="flex justify-between"><dt class="text-slate-500">No HP</dt><dd>{{ $p->no_telp ?? '-' }}</dd></div>
                        <div class="flex justify-between"><dt class="text-slate-500">Bergabung</dt><dd>{{ $p->created_at?->translatedFormat('d M Y') }}</dd></div>
                    </dl>
                    <div class="mt-4 grid grid-cols-2 gap-2">
                        <x-button :href="route('admin.petugas.edit', $p->id)" variant="secondary" size="sm">Edit</x-button>
                        <form method="POST" action="{{ route('admin.petugas.destroy', $p->id) }}" onsubmit="return confirm('Hapus petugas ini?');">
                            @csrf @method('DELETE')
                            <x-button type="submit" variant="danger" size="sm" fullWidth>Hapus</x-button>
                        </form>
                    </div>
                </x-card>
            @endforeach
        </div>
        <div>{{ $petugas->links() }}</div>
    @endif
</x-layouts.dashboard>
