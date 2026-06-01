<x-app-layout>
<x-slot:header>Monitoring SOS</x-slot:header>
<div class="space-y-6">
    <x-card>
        <h3 class="font-semibold text-lg mb-4">Peta Permintaan Darurat</h3>
        <div id="sos-monitor-map" class="h-[500px] rounded-lg border border-border"></div>
    </x-card>
    <x-card>
        <h3 class="font-semibold text-lg mb-4">Daftar SOS Aktif</h3>
        <div id="sos-list" class="space-y-3">
            @forelse($activeSOS as $sos)
                <div class="flex items-center justify-between p-3 bg-surface rounded-lg">
                    <div>
                        <p class="text-sm font-medium capitalize">{{ $sos->emergency_type }}</p>
                        <p class="text-xs text-text-muted">{{ $sos->user->name }} &middot; {{ $sos->created_at->diffForHumans() }}</p>
                    </div>
                    <a href="{{ route('admin.sos.show', $sos->id) }}" class="text-sm text-primary hover:underline">Detail</a>
                </div>
            @empty
                <p class="text-sm text-text-muted text-center py-4">Tidak ada permintaan SOS aktif</p>
            @endforelse
        </div>
    </x-card>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mapEl = document.getElementById('sos-monitor-map');
    if (mapEl && typeof L !== 'undefined') {
        const map = L.map(mapEl).setView([-2.5, 118], 5);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap', maxZoom: 18,
        }).addTo(map);
        const sosData = @json($activeSOS->map(fn($s) => [
            'lat' => $s->latitude, 'lng' => $s->longitude,
            'type' => $s->emergency_type, 'url' => route('admin.sos.show', $s->id),
            'name' => $s->user->name, 'time' => $s->created_at->diffForHumans(),
        ]));
        sosData.forEach(sos => {
            if (sos.lat && sos.lng) {
                const color = sos.type === 'ambulans' ? 'red' : sos.type === 'polisi' ? 'blue' : sos.type === 'pemadam' ? 'orange' : 'gray';
                const icon = L.divIcon({
                    html: `<div style="background:${color};color:white;padding:4px 8px;border-radius:8px;font-size:12px;font-weight:bold;white-space:nowrap;">${sos.type}</div>`,
                    className: '', iconSize: [60, 24], iconAnchor: [30, 12],
                });
                L.marker([sos.lat, sos.lng], { icon }).addTo(map)
                    .bindPopup(`<b>${sos.type}</b><br/>${sos.name}<br/>${sos.time}`);
            }
        });
        if (sosData.length > 0 && sosData[0].lat) {
            map.setView([sosData[0].lat, sosData[0].lng], 10);
        }
    }
});
</script>
@endpush
</x-app-layout>
