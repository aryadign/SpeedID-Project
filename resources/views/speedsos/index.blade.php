<x-app-layout>
<x-slot:header>SOS Darurat</x-slot:header>
<x-slot:title>SOS Darurat - Speed ID</x-slot:title>

<div class="max-w-4xl mx-auto">
    <div class="text-center mb-8">
        <p class="text-text-muted text-sm">Kirim sinyal darurat untuk mendapatkan bantuan secepat mungkin</p>
    </div>

    <form method="POST" action="{{ route('sos.store') }}" class="space-y-6">
        @csrf

        <div class="flex justify-center">
            <button type="submit" id="sos-button" class="relative w-[180px] h-[180px] md:w-[220px] md:h-[220px] rounded-full bg-red-600 hover:bg-red-700 active:scale-95 transition-all duration-200 flex flex-col items-center justify-center text-white shadow-xl shadow-red-600/30">
                <div class="absolute inset-0 rounded-full bg-red-600 animate-ping opacity-20"></div>
                <div class="absolute inset-2 rounded-full bg-red-600/10 animate-pulse"></div>
                <i data-lucide="triangle-alert" class="w-12 h-12 md:w-14 md:h-14 relative z-10"></i>
                <span class="text-xl md:text-2xl font-bold mt-2 relative z-10 tracking-wider">SOS</span>
                <span class="text-xs font-medium mt-1 relative z-10 opacity-80">Tekan untuk Darurat</span>
            </button>
        </div>

        <x-card>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <x-input-label for="emergency_type" value="Jenis Darurat" />
                    <select id="emergency_type" name="emergency_type" required class="mt-1 block w-full rounded-lg border-border bg-surface-alt text-text-primary text-sm px-4 py-2.5 focus:ring-red-500 focus:border-red-500">
                        <option value="">Pilih Jenis Darurat</option>
                        <option value="ambulans" {{ old('emergency_type') == 'ambulans' ? 'selected' : '' }}>🚑 Ambulans</option>
                        <option value="polisi" {{ old('emergency_type') == 'polisi' ? 'selected' : '' }}>👮 Kepolisian</option>
                        <option value="pemadam" {{ old('emergency_type') == 'pemadam' ? 'selected' : '' }}>🧯 Pemadam Kebakaran</option>
                        <option value="bencana" {{ old('emergency_type') == 'bencana' ? 'selected' : '' }}>🌊 Bencana Alam</option>
                        <option value="lainnya" {{ old('emergency_type') == 'lainnya' ? 'selected' : '' }}>📞 Lainnya</option>
                    </select>
                    <x-input-error :messages="$errors->get('emergency_type')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="location" value="Lokasi Anda" />
                    <div class="flex gap-2 mt-1">
                        <input type="text" id="location" name="location" value="{{ old('location') }}" placeholder="Lokasi otomatis terdeteksi" readonly class="flex-1 rounded-lg border-border bg-surface text-text-primary text-sm px-4 py-2.5 focus:ring-red-500 focus:border-red-500 placeholder:text-text-muted cursor-not-allowed" />
                        <button type="button" id="get-location" class="inline-flex items-center gap-2 px-4 py-2.5 bg-surface border border-border text-text-primary text-sm font-medium rounded-lg hover:bg-surface-alt transition-colors">
                            <i data-lucide="crosshair" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}" />
                    <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}" />
                    <x-input-error :messages="$errors->get('location')" class="mt-1" />
                </div>
            </div>

            <div class="mt-6">
                <x-input-label for="notes" value="Catatan Tambahan (opsional)" />
                <textarea id="notes" name="notes" rows="3" placeholder="Deskripsikan situasi darurat Anda..." class="mt-1 block w-full rounded-lg border-border bg-surface text-text-primary text-sm px-4 py-2.5 focus:ring-red-500 focus:border-red-500 placeholder:text-text-muted resize-none">{{ old('notes') }}</textarea>
                <x-input-error :messages="$errors->get('notes')" class="mt-1" />
            </div>

            <div class="mt-6 text-center">
                <p class="text-xs text-text-muted mb-3">Atau hubungi nomor darurat langsung</p>
                <div class="flex flex-wrap justify-center gap-3">
                    <a href="tel:112" class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-50 text-red-700 text-sm font-medium rounded-full hover:bg-red-100 transition-colors border border-red-200">
                        <i data-lucide="phone" class="w-4 h-4"></i>
                        112 - Darurat Umum
                    </a>
                    <a href="tel:118" class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-50 text-red-700 text-sm font-medium rounded-full hover:bg-red-100 transition-colors border border-red-200">
                        <i data-lucide="ambulance" class="w-4 h-4"></i>
                        118 - Ambulans
                    </a>
                    <a href="tel:110" class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-50 text-red-700 text-sm font-medium rounded-full hover:bg-red-100 transition-colors border border-red-200">
                        <i data-lucide="shield" class="w-4 h-4"></i>
                        110 - Polisi
                    </a>
                    <a href="tel:113" class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-50 text-red-700 text-sm font-medium rounded-full hover:bg-red-100 transition-colors border border-red-200">
                        <i data-lucide="flame" class="w-4 h-4"></i>
                        113 - Pemadam
                    </a>
                </div>
            </div>
        </x-card>
    </form>

    @if(isset($contacts) && $contacts->count() > 0)
        <div class="mt-8">
            <h3 class="font-semibold text-lg mb-4">Kontak Darurat</h3>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($contacts as $contact)
                    <x-card class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center shrink-0">
                            <i data-lucide="phone" class="w-5 h-5 text-red-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-text-primary truncate">{{ $contact->name }}</p>
                            <a href="tel:{{ $contact->phone }}" class="text-sm text-primary font-medium hover:underline">{{ $contact->phone }}</a>
                        </div>
                    </x-card>
                @endforeach
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    const btn = document.getElementById('sos-button');
    if (btn) {
        btn.addEventListener('click', function(e) {
            if (!confirm('Apakah Anda yakin ingin mengirim sinyal darurat? Ini akan mengirimkan lokasi Anda ke petugas terkait.')) {
                e.preventDefault();
                return false;
            }
        });
    }

    const locBtn = document.getElementById('get-location');
    if (locBtn && navigator.geolocation) {
        locBtn.addEventListener('click', function() {
            navigator.geolocation.getCurrentPosition(
                function(pos) {
                    document.getElementById('latitude').value = pos.coords.latitude;
                    document.getElementById('longitude').value = pos.coords.longitude;
                    document.getElementById('location').value =
                        pos.coords.latitude.toFixed(6) + ', ' + pos.coords.longitude.toFixed(6);
                },
                function() {
                    alert('Gagal mendapatkan lokasi. Silakan masukkan lokasi manual.');
                }
            );
        });
    }
});
</script>
@endpush
</x-app-layout>
