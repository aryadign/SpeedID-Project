@props(['latitude', 'longitude', 'height' => '200px', 'zoom' => 15, 'id' => null])

@php $mapId = $id ?? 'map-' . \Illuminate\Support\Str::random(6); @endphp

<div id="{{ $mapId }}" style="height: {{ $height }};" class="rounded-lg border border-border">
    <div class="flex items-center justify-center h-full bg-surface text-text-muted text-sm">
        <i data-lucide="map-pin" class="w-5 h-5 mr-2"></i>
        Memuat peta...
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mapEl = document.getElementById('{{ $mapId }}');
    if (mapEl && typeof L !== 'undefined') {
        const map = L.map(mapEl).setView([{{ $latitude }}, {{ $longitude }}], {{ $zoom }});
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 19,
        }).addTo(map);
        L.marker([{{ $latitude }}, {{ $longitude }}]).addTo(map);
    }
});
</script>
@endpush
