<x-layouts.warga :title="'Lapor Sampah'">
    <x-slot:header>Buat Laporan Sampah</x-slot:header>
    <x-slot:subheader>Sertakan foto dan keterangan lokasi agar petugas dapat menanggapi lebih cepat.</x-slot:subheader>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <x-card class="!p-6 sm:!p-8">
                <form method="POST" action="{{ route('warga.lapor.store') }}" enctype="multipart/form-data"
                      x-data="{ loading: false }" @submit="loading = true" class="space-y-5">
                    @csrf

                    <x-select label="Jenis Sampah" name="jenis_sampah" required
                        :options="[
                            'organik' => '🥗 Organik (sisa makanan, daun)',
                            'anorganik' => '♻️ Anorganik (plastik, kaca, kaleng)',
                            'b3' => '☣️ B3 (Bahan Berbahaya & Beracun)',
                            'campuran' => '🗑️ Campuran',
                        ]" :value="old('jenis_sampah')" />

                    <div x-data="{
                            geo: false,
                            getLocation() {
                                if (!navigator.geolocation) { alert('Geolocation tidak didukung browser ini.'); return; }
                                this.geo = true;
                                navigator.geolocation.getCurrentPosition(
                                    p => {
                                        document.getElementById('latitude').value = p.coords.latitude.toFixed(7);
                                        document.getElementById('longitude').value = p.coords.longitude.toFixed(7);
                                        this.geo = false;
                                    },
                                    e => { alert('Gagal mengambil lokasi: ' + e.message); this.geo = false; },
                                    { enableHighAccuracy: true, timeout: 10000 }
                                );
                            }
                         }">
                        <x-textarea label="Lokasi Lengkap" name="lokasi_text" required rows="2"
                            placeholder="Contoh: Gang Mawar No. 12, depan pos kamling"
                            :value="old('lokasi_text')" />
                        <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                        <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
                        <div class="mt-2">
                            <x-button type="button" variant="secondary" size="sm" @click="getLocation()" x-bind:loading="geo">
                                📍 Gunakan Lokasi Saya Sekarang
                            </x-button>
                            <span class="ml-2 text-xs text-slate-500" x-text="document.getElementById('latitude').value ? 'Koordinat tersimpan ✓' : ''"></span>
                        </div>
                    </div>

                    <x-textarea label="Keterangan" name="keterangan" required rows="4"
                        placeholder="Jelaskan kondisi sampah: kapan menumpuk, perkiraan volume, dampak yang terjadi, dll. (minimal 10 karakter)"
                        :value="old('keterangan')" />

                    <x-file-upload label="Foto Sampah" name="foto" required
                        helper="Foto harus jelas memperlihatkan lokasi & tumpukan sampah. Maksimal 2 MB." />

                    <div class="pt-2 flex flex-col-reverse sm:flex-row gap-3 sm:justify-end">
                        <x-button :href="route('warga.dashboard')" variant="tertiary">Batal</x-button>
                        <x-button type="submit" size="lg" x-bind:loading="loading" fullWidth class="sm:!w-auto">
                            Kirim Laporan
                        </x-button>
                    </div>
                </form>
            </x-card>
        </div>

        <div class="space-y-4">
            <x-card class="bg-gradient-to-br from-primary-50 to-white !border-primary-100">
                <h3 class="font-display font-semibold text-slate-900">Tips Pelaporan</h3>
                <ul class="mt-3 space-y-2.5 text-sm text-slate-600">
                    <li class="flex gap-2"><span class="text-primary-600 shrink-0">✓</span> Pastikan foto terang & jelas.</li>
                    <li class="flex gap-2"><span class="text-primary-600 shrink-0">✓</span> Sebutkan patokan/lokasi spesifik.</li>
                    <li class="flex gap-2"><span class="text-primary-600 shrink-0">✓</span> Pilih jenis sampah yang sesuai agar penanganan tepat.</li>
                    <li class="flex gap-2"><span class="text-primary-600 shrink-0">✓</span> Aktifkan "Gunakan Lokasi" untuk akurasi koordinat.</li>
                </ul>
            </x-card>

            <x-card>
                <h3 class="font-display font-semibold text-slate-900">Estimasi Respon</h3>
                <p class="mt-2 text-sm text-slate-600">Setelah laporan dikirim, status awal adalah <strong>Dikirim</strong>. Petugas biasanya menanggapi dalam 1 - 24 jam.</p>
                <div class="mt-3">
                    <x-timeline :items="[
                        ['title' => 'Dikirim', 'description' => 'Laporan terkirim ke sistem', 'color' => 'sky'],
                        ['title' => 'Diterima', 'description' => 'Petugas membaca laporan', 'color' => 'primary'],
                        ['title' => 'Diproses', 'description' => 'Petugas turun ke lokasi', 'color' => 'amber'],
                        ['title' => 'Selesai', 'description' => 'Sampah berhasil diangkut', 'color' => 'emerald'],
                    ]" />
                </div>
            </x-card>
        </div>
    </div>
</x-layouts.warga>
