<x-layouts.dashboard :title="'Verifikasi Iuran'">
    <x-slot:header>Verifikasi Pembayaran Iuran</x-slot:header>
    <x-slot:subheader>Periksa bukti pembayaran yang dikirim warga.</x-slot:subheader>

    @if($iuran->isEmpty())
        <x-card>
            <x-empty-state title="Tidak ada pembayaran menunggu verifikasi" description="Semua pembayaran sudah diproses." />
        </x-card>
    @else
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($iuran as $i)
                <x-card class="!p-5 flex flex-col">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <x-avatar :user="$i->warga" size="md" />
                            <div>
                                <p class="font-semibold text-slate-900">{{ $i->warga?->name }}</p>
                                <p class="text-xs text-slate-500">{{ $i->kode_tagihan }}</p>
                            </div>
                        </div>
                        <x-status-badge :status="$i->status" />
                    </div>

                    @if($i->bukti_bayar)
                        <a href="{{ $i->bukti_bayar_url }}" target="_blank" class="block">
                            <img src="{{ $i->bukti_bayar_url }}" class="w-full aspect-video object-cover rounded-lg border border-slate-200 mb-3 hover:opacity-90 transition" alt="Bukti">
                        </a>
                    @endif

                    <dl class="space-y-1 text-sm mb-4">
                        <div class="flex justify-between"><dt class="text-slate-500">Periode</dt><dd class="font-medium text-slate-900">{{ $i->periode_label }}</dd></div>
                        <div class="flex justify-between"><dt class="text-slate-500">Nominal</dt><dd class="font-semibold tabular-nums">{{ $i->nominal_formatted }}</dd></div>
                        <div class="flex justify-between"><dt class="text-slate-500">Metode</dt><dd class="capitalize">{{ $i->metode_bayar }}</dd></div>
                        <div class="flex justify-between"><dt class="text-slate-500">Tgl Bayar</dt><dd class="text-xs">{{ $i->tanggal_bayar?->translatedFormat('d M Y, H:i') }}</dd></div>
                    </dl>

                    <div class="mt-auto grid grid-cols-2 gap-2">
                        <form method="POST" action="{{ route('petugas.iuran.setujui', $i->id) }}" onsubmit="return confirm('Setujui pembayaran ini?');">
                            @csrf <x-button type="submit" variant="success" size="sm" fullWidth>Setujui</x-button>
                        </form>
                        <x-button x-data x-on:click="$dispatch('open-modal', 'tolak-iuran-{{ $i->id }}')" variant="danger" size="sm" fullWidth>Tolak</x-button>
                    </div>

                    <x-modal :name="'tolak-iuran-' . $i->id" maxWidth="md" title="Tolak Pembayaran">
                        <form method="POST" action="{{ route('petugas.iuran.tolak', $i->id) }}" class="space-y-3">
                            @csrf
                            <p class="text-sm text-slate-600">Bukti pembayaran milik <strong>{{ $i->warga?->name }}</strong> akan ditolak.</p>
                            <x-textarea label="Alasan Penolakan" name="alasan" required rows="3" />
                            <div class="flex justify-end gap-2">
                                <x-button type="button" variant="tertiary" x-on:click="$dispatch('close-modal', 'tolak-iuran-{{ $i->id }}')">Batal</x-button>
                                <x-button type="submit" variant="danger">Tolak Pembayaran</x-button>
                            </div>
                        </form>
                    </x-modal>
                </x-card>
            @endforeach
        </div>
        <div>{{ $iuran->links() }}</div>
    @endif
</x-layouts.dashboard>
