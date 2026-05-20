@props([
    'latName' => 'latitude',
    'lngName' => 'longitude',
    'lat' => null,
    'lng' => null,
    'defaultLat' => -8.6705,
    'defaultLng' => 115.2126,
    'defaultZoom' => 16,
    'addressTarget' => null,   // DOM id of a textarea/input to fill with reverse-geocoded address
    'label' => 'Titik Lokasi di Peta',
    'helper' => null,
    'required' => false,
])

<div
    x-data="mapPicker({
        latName: @js($latName),
        lngName: @js($lngName),
        initLat: {{ $lat !== null && $lat !== '' ? (float) $lat : 'null' }},
        initLng: {{ $lng !== null && $lng !== '' ? (float) $lng : 'null' }},
        defaultLat: {{ (float) $defaultLat }},
        defaultLng: {{ (float) $defaultLng }},
        defaultZoom: {{ (int) $defaultZoom }},
        addressTarget: @js($addressTarget),
    })"
    x-init="init()"
    {{ $attributes->class('w-full') }}
>
    @if($label)
        <label class="block text-sm font-medium text-slate-700 mb-1">
            {{ $label }}
            @if($required) <span class="text-rose-500">*</span> @endif
        </label>
    @endif
    @if($helper)
        <p class="text-xs text-slate-500 mb-2">{{ $helper }}</p>
    @endif

    {{-- Toolbar: search + my-location --}}
    <div class="flex flex-col sm:flex-row gap-2 mb-2">
        <div class="relative flex-1">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
            </span>
            <input type="text" x-model="query" @keydown.enter.prevent="search()" @input.debounce.600ms="search()"
                   placeholder="Cari nama jalan / tempat (mis. Gang Mawar Denpasar)..."
                   class="block w-full rounded-lg border-slate-300 bg-white text-slate-900 placeholder:text-slate-400 text-sm pl-10 pr-3 py-2.5 shadow-sm transition focus:border-primary-500 focus:ring-2 focus:ring-primary-500/30">

            {{-- Search results dropdown --}}
            <div x-show="results.length > 0" x-cloak @click.outside="results = []"
                 class="absolute z-[1000] mt-1 w-full bg-white rounded-lg border border-slate-200 shadow-xl overflow-hidden max-h-60 overflow-y-auto scrollbar-thin">
                <template x-for="r in results" :key="r.place_id">
                    <button type="button" @click="pickResult(r)"
                            class="w-full text-left px-3 py-2 text-sm hover:bg-primary-50 border-b border-slate-50 last:border-0 flex gap-2 items-start">
                        <svg class="w-4 h-4 text-primary-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span class="text-slate-700 line-clamp-2" x-text="r.display_name"></span>
                    </button>
                </template>
            </div>
        </div>

        <button type="button" @click="locate()"
                class="group inline-flex items-center justify-center gap-2 shrink-0 rounded-lg px-4 py-2.5 text-sm font-semibold bg-gradient-to-b from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white shadow-md shadow-primary-500/25 transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 focus:outline-none focus-visible:ring-4 focus-visible:ring-primary-500/30 disabled:opacity-60 disabled:cursor-wait"
                :disabled="locating">
            <template x-if="!locating">
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 2v3m0 14v3m10-10h-3M5 12H2m15.07-5.07l-2.12 2.12M9.05 14.95l-2.12 2.12m0-10.14l2.12 2.12m5.9 5.9l2.12 2.12"/>
                    <circle cx="12" cy="12" r="3.5"/>
                </svg>
            </template>
            <template x-if="locating">
                <svg class="w-[18px] h-[18px] animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <circle class="opacity-25" cx="12" cy="12" r="10"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
            </template>
            <span x-text="locating ? 'Mencari lokasi...' : 'Lokasi Saya Sekarang'"></span>
        </button>
    </div>

    {{-- The map --}}
    <div class="relative rounded-xl overflow-hidden border border-slate-200 shadow-soft">
        <div x-ref="map" class="w-full h-72 sm:h-80 bg-slate-100 z-0"></div>

        {{-- Loading overlay --}}
        <div x-show="!ready" class="absolute inset-0 flex items-center justify-center bg-slate-100">
            <div class="flex flex-col items-center gap-2 text-slate-400">
                <svg class="w-7 h-7 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <circle class="opacity-25" cx="12" cy="12" r="10"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                <span class="text-xs">Memuat peta...</span>
            </div>
        </div>

        {{-- Instruction pill --}}
        <div x-show="ready && !hasPin" x-cloak
             class="absolute top-3 left-1/2 -translate-x-1/2 z-[500] px-3 py-1.5 rounded-full bg-slate-900/80 backdrop-blur text-white text-xs font-medium pointer-events-none whitespace-nowrap">
            👆 Ketuk peta untuk menandai lokasi sampah
        </div>
    </div>

    {{-- Coordinate readout + clear --}}
    <div class="mt-2 flex flex-wrap items-center gap-x-4 gap-y-1.5 text-xs">
        <template x-if="hasPin">
            <span class="inline-flex items-center gap-1.5 text-primary-700 font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Lokasi ditandai
            </span>
        </template>
        <template x-if="hasPin">
            <span class="font-mono text-slate-500" x-text="`${lat?.toFixed(6)}, ${lng?.toFixed(6)}`"></span>
        </template>
        <template x-if="hasPin">
            <button type="button" @click="clearPin()" class="text-rose-600 hover:text-rose-700 font-medium">Hapus pin</button>
        </template>
        <template x-if="!hasPin">
            <span class="text-slate-400">Belum ada titik lokasi dipilih.</span>
        </template>
    </div>

    {{-- Reverse-geocoded address suggestion --}}
    <div x-show="resolvedAddress" x-cloak class="mt-2 rounded-lg bg-primary-50 border border-primary-100 px-3 py-2 flex items-start gap-2">
        <svg class="w-4 h-4 text-primary-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        <div class="min-w-0 flex-1">
            <p class="text-xs text-slate-600" x-text="resolvedAddress"></p>
            @if($addressTarget)
                <button type="button" @click="useAddress()" class="mt-1 text-xs font-semibold text-primary-700 hover:text-primary-800">
                    Pakai alamat ini sebagai keterangan lokasi →
                </button>
            @endif
        </div>
    </div>

    {{-- Hidden inputs --}}
    <input type="hidden" name="{{ $latName }}" x-ref="latInput" value="{{ old($latName, $lat) }}">
    <input type="hidden" name="{{ $lngName }}" x-ref="lngInput" value="{{ old($lngName, $lng) }}">
</div>

@once
    <script>
        function loadLeaflet() {
            if (window._leafletPromise) return window._leafletPromise;
            window._leafletPromise = new Promise((resolve, reject) => {
                if (window.L) { resolve(); return; }
                if (!document.getElementById('leaflet-css')) {
                    const link = document.createElement('link');
                    link.id = 'leaflet-css';
                    link.rel = 'stylesheet';
                    link.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
                    document.head.appendChild(link);
                }
                const s = document.createElement('script');
                s.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
                s.onload = () => resolve();
                s.onerror = () => reject(new Error('Gagal memuat peta'));
                document.head.appendChild(s);
            });
            return window._leafletPromise;
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('mapPicker', (cfg) => ({
                map: null,
                marker: null,
                accuracyCircle: null,
                ready: false,
                locating: false,
                hasPin: false,
                lat: cfg.initLat,
                lng: cfg.initLng,
                query: '',
                results: [],
                resolvedAddress: '',

                async init() {
                    try {
                        await loadLeaflet();
                    } catch (e) {
                        window.toast && window.toast({ type: 'error', message: 'Peta gagal dimuat. Periksa koneksi internet.' });
                        return;
                    }
                    this.buildMap();
                },

                buildMap() {
                    const startLat = this.lat ?? cfg.defaultLat;
                    const startLng = this.lng ?? cfg.defaultLng;

                    this.map = L.map(this.$refs.map, { zoomControl: true })
                        .setView([startLat, startLng], this.lat ? cfg.defaultZoom + 1 : cfg.defaultZoom);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap',
                        maxZoom: 19,
                    }).addTo(this.map);

                    this.map.on('click', (e) => this.setPin(e.latlng.lat, e.latlng.lng, true));

                    // Restore pin if old() values exist
                    if (this.lat !== null && this.lng !== null) {
                        this.setPin(this.lat, this.lng, false);
                    }

                    // Leaflet sometimes needs a nudge inside flex/tab containers
                    setTimeout(() => this.map.invalidateSize(), 250);
                    this.ready = true;
                },

                setPin(lat, lng, reverse = true) {
                    this.lat = lat;
                    this.lng = lng;
                    this.hasPin = true;

                    if (!this.marker) {
                        const icon = L.divIcon({
                            className: '',
                            html: `<div style="position:relative">
                                <div style="width:30px;height:30px;background:linear-gradient(180deg,#10B981,#047857);border:3px solid #fff;border-radius:50% 50% 50% 0;transform:rotate(-45deg);box-shadow:0 4px 10px rgba(0,0,0,.3)"></div>
                                <div style="position:absolute;top:9px;left:9px;width:8px;height:8px;background:#fff;border-radius:50%"></div>
                            </div>`,
                            iconSize: [30, 30],
                            iconAnchor: [15, 30],
                        });
                        this.marker = L.marker([lat, lng], { draggable: true, icon }).addTo(this.map);
                        this.marker.on('dragend', (e) => {
                            const p = e.target.getLatLng();
                            this.setPin(p.lat, p.lng, true);
                        });
                    } else {
                        this.marker.setLatLng([lat, lng]);
                    }

                    this.$refs.latInput.value = lat.toFixed(7);
                    this.$refs.lngInput.value = lng.toFixed(7);

                    if (reverse) this.reverseGeocode(lat, lng);
                },

                clearPin() {
                    this.hasPin = false;
                    this.lat = null;
                    this.lng = null;
                    this.resolvedAddress = '';
                    this.$refs.latInput.value = '';
                    this.$refs.lngInput.value = '';
                    if (this.marker) { this.map.removeLayer(this.marker); this.marker = null; }
                    if (this.accuracyCircle) { this.map.removeLayer(this.accuracyCircle); this.accuracyCircle = null; }
                },

                locate() {
                    if (!navigator.geolocation) {
                        window.toast && window.toast({ type: 'error', message: 'Browser Anda tidak mendukung deteksi lokasi.' });
                        return;
                    }
                    this.locating = true;
                    navigator.geolocation.getCurrentPosition(
                        (pos) => {
                            const { latitude, longitude, accuracy } = pos.coords;
                            this.map.flyTo([latitude, longitude], 18, { duration: 1 });
                            this.setPin(latitude, longitude, true);

                            if (this.accuracyCircle) this.map.removeLayer(this.accuracyCircle);
                            this.accuracyCircle = L.circle([latitude, longitude], {
                                radius: accuracy,
                                color: '#10B981', fillColor: '#10B981', fillOpacity: 0.12, weight: 1,
                            }).addTo(this.map);

                            this.locating = false;
                            window.toast && window.toast({
                                type: 'success',
                                message: `Lokasi ditemukan (akurasi ±${Math.round(accuracy)} m). Geser pin bila kurang tepat.`,
                            });
                        },
                        (err) => {
                            this.locating = false;
                            const msg = {
                                1: 'Izin lokasi ditolak. Aktifkan izin lokasi di browser lalu coba lagi.',
                                2: 'Lokasi tidak tersedia saat ini. Pastikan GPS/lokasi perangkat aktif.',
                                3: 'Permintaan lokasi melebihi waktu tunggu. Coba lagi di area dengan sinyal lebih baik.',
                            }[err.code] || 'Gagal mendapatkan lokasi Anda.';
                            window.toast && window.toast({ type: 'error', message: msg });
                        },
                        { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
                    );
                },

                async search() {
                    const q = this.query.trim();
                    if (q.length < 3) { this.results = []; return; }
                    try {
                        const url = `https://nominatim.openstreetmap.org/search?format=json&limit=5&accept-language=id&q=${encodeURIComponent(q)}`;
                        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                        if (!res.ok) throw new Error();
                        this.results = await res.json();
                    } catch (e) {
                        this.results = [];
                    }
                },

                pickResult(r) {
                    const lat = parseFloat(r.lat), lng = parseFloat(r.lon);
                    this.results = [];
                    this.query = '';
                    this.map.flyTo([lat, lng], 18, { duration: 1 });
                    this.setPin(lat, lng, false);
                    this.resolvedAddress = r.display_name;
                },

                async reverseGeocode(lat, lng) {
                    try {
                        const url = `https://nominatim.openstreetmap.org/reverse?format=json&accept-language=id&lat=${lat}&lon=${lng}`;
                        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                        if (!res.ok) throw new Error();
                        const data = await res.json();
                        this.resolvedAddress = data.display_name || '';
                    } catch (e) {
                        this.resolvedAddress = '';
                    }
                },

                useAddress() {
                    if (!cfg.addressTarget || !this.resolvedAddress) return;
                    const el = document.getElementById(cfg.addressTarget);
                    if (el) {
                        el.value = this.resolvedAddress;
                        el.dispatchEvent(new Event('input'));
                        window.toast && window.toast({ type: 'info', message: 'Alamat dari peta disalin ke keterangan lokasi.' });
                    }
                },
            }));
        });
    </script>
@endonce
