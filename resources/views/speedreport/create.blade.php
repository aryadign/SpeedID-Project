<x-app-layout>
<x-slot:header>Buat Laporan Baru</x-slot:header>
<x-slot:title>Buat Laporan - Speed ID</x-slot:title>

<div class="max-w-3xl mx-auto">
    <x-card>
        <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <x-input-label for="category_id" value="Kategori Laporan" />
                <select id="category_id" name="category_id" required class="mt-1 block w-full rounded-lg border-border bg-surface-alt text-text-primary text-sm px-4 py-2.5 focus:ring-primary focus:border-primary">
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('category_id')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="title" value="Judul Laporan" />
                <input id="title" type="text" name="title" value="{{ old('title') }}" required maxlength="255" placeholder="Contoh: Jalan berlubang di Jl. Sudirman" class="mt-1 block w-full rounded-lg border-border bg-surface-alt text-text-primary text-sm px-4 py-2.5 focus:ring-primary focus:border-primary placeholder:text-text-muted" />
                <x-input-error :messages="$errors->get('title')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="description" value="Deskripsi Lengkap" />
                <textarea id="description" name="description" rows="5" required placeholder="Jelaskan detail laporan Anda secara lengkap..." class="mt-1 block w-full rounded-lg border-border bg-surface-alt text-text-primary text-sm px-4 py-2.5 focus:ring-primary focus:border-primary placeholder:text-text-muted">{{ old('description') }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="media" value="Lampiran (Foto/Video)" />
                <input id="media" type="file" name="media[]" multiple accept="image/*,video/*" class="mt-1 block w-full text-sm text-text-secondary file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-medium file:bg-primary/5 file:text-primary hover:file:bg-primary/10 file:cursor-pointer cursor-pointer" />
                <p class="text-xs text-text-muted mt-1.5">Format: JPG, PNG, MP4. Maksimal 5 file (masing-masing 10MB).</p>
                <x-input-error :messages="$errors->get('media.*')" class="mt-1" />
            </div>

            <div x-data="{ showMap: false }">
                <x-input-label value="Lokasi Kejadian" />
                <div class="mt-1 flex items-center gap-3">
                    <input type="text" name="location" id="location" value="{{ old('location') }}" placeholder="Masukkan alamat atau titik lokasi" readonly class="flex-1 rounded-lg border-border bg-surface-alt text-text-primary text-sm px-4 py-2.5 focus:ring-primary focus:border-primary placeholder:text-text-muted cursor-pointer" @click="showMap = !showMap; if(showMap) { $nextTick(() => initPickerMap()) }" />
                    <button type="button" @click="showMap = !showMap; if(showMap) { $nextTick(() => initPickerMap()) }" class="inline-flex items-center gap-2 px-4 py-2.5 bg-surface border border-border text-text-primary text-sm font-medium rounded-lg hover:bg-surface-alt transition-colors">
                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                        Pilih
                    </button>
                </div>
                <div x-show="showMap" x-cloak class="mt-3 rounded-lg overflow-hidden border border-border">
                    <div id="map-picker" class="h-64 bg-surface flex items-center justify-center relative">
                        <div class="absolute inset-0 bg-surface flex items-center justify-center z-10" id="map-loader">
                            <span class="text-sm text-text-muted">Memuat peta...</span>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}" />
                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}" />
                <x-input-error :messages="$errors->get('location')" class="mt-1" />
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" id="anonymous" name="anonymous" value="1" {{ old('anonymous') ? 'checked' : '' }} class="rounded border-border text-primary focus:ring-primary w-4 h-4" />
                <x-input-label for="anonymous" value="Laporkan secara anonim (nama tidak akan ditampilkan)" class="text-sm" />
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-border">
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white text-sm font-medium rounded-full hover:bg-primary/90 transition-all shadow-card-sm">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    Kirim Laporan
                </button>
                <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-text-secondary hover:text-text-primary transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </x-card>
</div>

@push('scripts')
<script>
    let pickerMap = null;
    let pickerMarker = null;

    function initPickerMap() {
        const mapContainer = document.getElementById('map-picker');
        if (!mapContainer) return;

        if (pickerMap) {
            setTimeout(() => {
                pickerMap.invalidateSize();
            }, 50);
            return;
        }

        // Get coordinates or default to Jakarta
        const inputLat = document.getElementById('latitude');
        const inputLng = document.getElementById('longitude');
        const inputLoc = document.getElementById('location');
        const loader = document.getElementById('map-loader');

        const defaultLat = parseFloat(inputLat.value) || -6.200000;
        const defaultLng = parseFloat(inputLng.value) || 106.816666;

        pickerMap = L.map(mapContainer).setView([defaultLat, defaultLng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 19
        }).addTo(pickerMap);

        if (inputLat.value && inputLng.value) {
            pickerMarker = L.marker([defaultLat, defaultLng]).addTo(pickerMap);
        }

        // Try getting user current position
        if (navigator.geolocation && !inputLat.value) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const curLat = position.coords.latitude;
                const curLng = position.coords.longitude;

                pickerMap.setView([curLat, curLng], 15);
                if (pickerMarker) {
                    pickerMarker.setLatLng([curLat, curLng]);
                } else {
                    pickerMarker = L.marker([curLat, curLng]).addTo(pickerMap);
                }

                inputLat.value = curLat;
                inputLng.value = curLng;
                inputLoc.value = `${curLat.toFixed(6)}, ${curLng.toFixed(6)}`;

                // Reverse geocode
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${curLat}&lon=${curLng}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data && data.display_name) {
                            inputLoc.value = data.display_name;
                        }
                    })
                    .catch(() => {});
            }, function() {}, { enableHighAccuracy: true });
        }

        pickerMap.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;

            if (pickerMarker) {
                pickerMarker.setLatLng(e.latlng);
            } else {
                pickerMarker = L.marker(e.latlng).addTo(pickerMap);
            }

            inputLat.value = lat;
            inputLng.value = lng;
            inputLoc.value = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;

            // Reverse geocode Nominatim
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                .then(res => res.json())
                .then(data => {
                    if (data && data.display_name) {
                        inputLoc.value = data.display_name;
                    }
                })
                .catch(() => {});
        });

        // Hide loader when tiles are loaded
        pickerMap.whenReady(() => {
            if (loader) loader.remove();
        });

        setTimeout(() => {
            pickerMap.invalidateSize();
        }, 100);
    }
</script>
@endpush
</x-app-layout>
