<x-layouts.warga :title="'Iuran Saya'">
    <x-slot:header>Iuran Sampah Bulanan</x-slot:header>
    <x-slot:subheader>Kelola tagihan dan riwayat pembayaran iuran sampah Anda.</x-slot:subheader>

    <div x-data="{ tab: 'aktif' }">
        <div class="mb-5">
            <div class="inline-flex bg-slate-100 rounded-lg p-1">
                <button @click="tab = 'aktif'" :class="tab === 'aktif' ? 'bg-white shadow-sm text-primary-700' : 'text-slate-600 hover:text-slate-900'" class="px-4 py-2 text-sm font-medium rounded-md transition">
                    Tagihan Aktif ({{ $aktif->count() }})
                </button>
                <button @click="tab = 'riwayat'" :class="tab === 'riwayat' ? 'bg-white shadow-sm text-primary-700' : 'text-slate-600 hover:text-slate-900'" class="px-4 py-2 text-sm font-medium rounded-md transition">
                    Riwayat Pembayaran
                </button>
            </div>
        </div>

        {{-- Tab: Aktif --}}
        <div x-show="tab === 'aktif'" x-transition>
            @if($aktif->isEmpty())
                <x-card>
                    <x-empty-state title="Tidak ada tagihan aktif" description="Semua tagihan Anda telah lunas. Terima kasih atas dukungannya!" illustration="iuran" />
                </x-card>
            @else
                <div class="grid sm:grid-cols-2 gap-4">
                    @foreach($aktif as $iuran)
                        <x-card class="!p-6">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-xs text-slate-500">{{ $iuran->kode_tagihan }}</p>
                                    <p class="font-display font-bold text-lg text-slate-900 mt-1">{{ $iuran->periode_label }}</p>
                                    <p class="text-3xl font-display font-bold text-slate-900 mt-2 tabular-nums">{{ $iuran->nominal_formatted }}</p>
                                </div>
                                <x-status-badge :status="$iuran->status" />
                            </div>

                            @if($iuran->alasan_tolak)
                                <div class="mt-3 p-3 rounded-lg bg-rose-50 border border-rose-200 text-sm text-rose-700">
                                    ⚠ <strong>Alasan Penolakan:</strong> {{ $iuran->alasan_tolak }}
                                </div>
                            @endif

                            @if($iuran->status === 'menunggu_verifikasi')
                                <div class="mt-3 p-3 rounded-lg bg-amber-50 border border-amber-200 text-sm text-amber-800">
                                    ⏳ Bukti pembayaran sedang diverifikasi petugas.
                                </div>
                            @endif

                            @if(in_array($iuran->status, ['belum_bayar', 'ditolak']))
                                <div class="mt-4">
                                    <x-button x-data x-on:click="$dispatch('open-modal', 'bayar-{{ $iuran->id }}')" fullWidth>
                                        Bayar Sekarang
                                    </x-button>
                                </div>

                                <x-modal :name="'bayar-' . $iuran->id" maxWidth="lg" :title="'Bayar Iuran ' . $iuran->periode_label">
                                    <form method="POST" action="{{ route('warga.iuran.bayar', $iuran->id) }}" enctype="multipart/form-data"
                                          x-data="{ metode: 'transfer', loading: false }" @submit="loading = true" class="space-y-4">
                                        @csrf
                                        <div class="rounded-lg bg-slate-50 p-4 flex items-center justify-between">
                                            <div>
                                                <p class="text-xs text-slate-500">Total Tagihan</p>
                                                <p class="font-display font-bold text-2xl text-slate-900 tabular-nums">{{ $iuran->nominal_formatted }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-xs text-slate-500">Periode</p>
                                                <p class="font-semibold text-slate-700">{{ $iuran->periode_label }}</p>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 mb-2">Metode Pembayaran</label>
                                            <div class="grid grid-cols-2 gap-2">
                                                <label class="relative flex flex-col items-center gap-1 p-3 rounded-lg border-2 cursor-pointer transition"
                                                       :class="metode === 'transfer' ? 'border-primary-500 bg-primary-50' : 'border-slate-200 hover:border-slate-300'">
                                                    <input type="radio" name="metode_bayar" value="transfer" class="sr-only" x-model="metode">
                                                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                                    <span class="text-sm font-medium">Transfer Bank</span>
                                                </label>
                                                <label class="relative flex flex-col items-center gap-1 p-3 rounded-lg border-2 cursor-pointer transition"
                                                       :class="metode === 'tunai' ? 'border-primary-500 bg-primary-50' : 'border-slate-200 hover:border-slate-300'">
                                                    <input type="radio" name="metode_bayar" value="tunai" class="sr-only" x-model="metode">
                                                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                                    <span class="text-sm font-medium">Tunai</span>
                                                </label>
                                            </div>
                                        </div>

                                        <div x-show="metode === 'transfer'" x-transition>
                                            <div class="rounded-lg bg-sky-50 border border-sky-200 p-3 mb-3 text-xs text-sky-900">
                                                <p class="font-semibold mb-1">Rekening Banjar Bumi Shanti</p>
                                                <p>Bank BPD Bali — <span class="font-mono">050.12.34567.89</span></p>
                                                <p>a.n. Banjar Bumi Shanti — Iuran Sampah</p>
                                            </div>
                                            <x-file-upload label="Upload Bukti Transfer" name="bukti_bayar" required />
                                        </div>

                                        <div x-show="metode === 'tunai'" x-transition>
                                            <x-alert variant="info" :dismissible="false">
                                                Setelah klik kirim, silakan datang ke balai banjar untuk membayar secara tunai. Petugas akan memverifikasi pembayaran Anda.
                                            </x-alert>
                                        </div>

                                        <div class="flex justify-end gap-2 pt-2">
                                            <x-button type="button" variant="tertiary" x-on:click="$dispatch('close-modal', 'bayar-{{ $iuran->id }}')">Batal</x-button>
                                            <x-button type="submit" x-bind:loading="loading">Kirim Pembayaran</x-button>
                                        </div>
                                    </form>
                                </x-modal>
                            @endif
                        </x-card>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Tab: Riwayat --}}
        <div x-show="tab === 'riwayat'" x-cloak x-transition>
            @if($riwayat->isEmpty())
                <x-card>
                    <x-empty-state title="Belum ada riwayat pembayaran" description="Pembayaran yang sudah lunas akan ditampilkan di sini." illustration="iuran" />
                </x-card>
            @else
                <x-card class="!p-0">
                    <x-table>
                        <thead class="bg-slate-50">
                            <tr class="text-left text-xs uppercase tracking-wider text-slate-500">
                                <th class="px-4 py-3">Kode</th>
                                <th class="px-4 py-3">Periode</th>
                                <th class="px-4 py-3">Nominal</th>
                                <th class="px-4 py-3">Tgl Bayar</th>
                                <th class="px-4 py-3">Verifikator</th>
                                <th class="px-4 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($riwayat as $r)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3 font-mono text-xs text-slate-700">{{ $r->kode_tagihan }}</td>
                                    <td class="px-4 py-3">{{ $r->periode_label }}</td>
                                    <td class="px-4 py-3 font-semibold tabular-nums">{{ $r->nominal_formatted }}</td>
                                    <td class="px-4 py-3 text-sm text-slate-600">{{ $r->tanggal_bayar?->translatedFormat('d M Y') ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-slate-600">{{ $r->verifikator?->name ?? '-' }}</td>
                                    <td class="px-4 py-3"><x-status-badge :status="$r->status" /></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </x-table>
                </x-card>
                <div>{{ $riwayat->links() }}</div>
            @endif
        </div>
    </div>
</x-layouts.warga>
