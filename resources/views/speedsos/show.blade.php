<x-app-layout>
<x-slot:header>Detail Sinyal Darurat</x-slot:header>

<div x-data="sosPolling('{{ $sos->id }}')" x-init="init()" class="max-w-3xl mx-auto">
    <div class="grid md:grid-cols-2 gap-6">
        <div class="space-y-6">
            <x-card>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
                        <i data-lucide="triangle-alert" class="w-6 h-6 text-red-600"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-text-primary capitalize">{{ $sos->emergency_type }}</h2>
                        <p class="text-xs text-text-muted">{{ $sos->created_at->format('d M Y, H:i') }} &middot; {{ $sos->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span x-html="statusBadge"></span>
                </div>
            </x-card>

            <x-card>
                <h3 class="font-semibold text-lg mb-4">Informasi Pengirim</h3>
                <div class="space-y-3">
                    <div class="flex items-center gap-3 text-sm">
                        <i data-lucide="user" class="w-4 h-4 text-text-muted"></i>
                        <span class="text-text-primary">{{ $sos->user->name }}</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <i data-lucide="mail" class="w-4 h-4 text-text-muted"></i>
                        <span class="text-text-primary">{{ $sos->user->email }}</span>
                    </div>
                    @if($sos->user->phone)
                        <div class="flex items-center gap-3 text-sm">
                            <i data-lucide="phone" class="w-4 h-4 text-text-muted"></i>
                            <span class="text-text-primary">{{ $sos->user->phone }}</span>
                        </div>
                    @endif
                </div>
            </x-card>

            @if($sos->notes)
                <x-card>
                    <h3 class="font-semibold text-lg mb-3">Catatan</h3>
                    <p class="text-sm text-text-secondary">{{ $sos->notes }}</p>
                </x-card>
            @endif
        </div>

        <div class="space-y-6">
            @if($sos->latitude && $sos->longitude)
                <x-card>
                    <h3 class="font-semibold text-lg mb-4">Lokasi</h3>
                    <x-map :latitude="$sos->latitude" :longitude="$sos->longitude" height="200px" />
                </x-card>
            @endif

            <x-card>
                <h3 class="font-semibold text-lg mb-4">Riwayat Status</h3>
                <div id="sos-timeline" class="relative pl-5">
                    @php
                        $timeline = $sos->activities ?? collect();
                    @endphp
                    @if($timeline->count() > 0)
                        <div class="absolute left-2 top-1 bottom-1 w-0.5 bg-border"></div>
                        <div class="space-y-4">
                            @foreach($timeline as $activity)
                                <div class="flex gap-3 relative">
                                    <div class="w-4 h-4 rounded-full border-2 border-red-500 bg-surface-alt flex items-center justify-center shrink-0 z-10 -ml-[13px]">
                                        <div class="w-1.5 h-1.5 rounded-full bg-red-500"></div>
                                    </div>
                                    <div class="flex-1 min-w-0 pt-0.5">
                                        <p class="text-sm font-medium text-text-primary capitalize">{{ $activity->description }}</p>
                                        <p class="text-xs text-text-muted">{{ $activity->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex gap-3">
                            <div class="w-4 h-4 rounded-full border-2 border-red-500 bg-red-50 flex items-center justify-center shrink-0 mt-0.5">
                                <div class="w-1.5 h-1.5 rounded-full bg-red-500"></div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-text-primary capitalize">{{ $sos->status }}</p>
                                <p class="text-xs text-text-muted">{{ $sos->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </x-card>

            <x-card>
                <h3 class="font-semibold text-lg mb-4">Tindakan Cepat</h3>
                <div class="space-y-3">
                    <a href="tel:112" class="flex items-center gap-3 px-4 py-3 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition-colors">
                        <i data-lucide="phone" class="w-5 h-5"></i>
                        <span class="text-sm font-medium">Hubungi 112 - Darurat Umum</span>
                    </a>
                    @if($sos->latitude && $sos->longitude)
                        <a href="https://www.google.com/maps?q={{ $sos->latitude }},{{ $sos->longitude }}" target="_blank" class="flex items-center gap-3 px-4 py-3 bg-surface text-text-primary rounded-lg hover:bg-surface-alt transition-colors border border-border">
                            <i data-lucide="external-link" class="w-5 h-5 text-text-muted"></i>
                            <span class="text-sm font-medium">Buka di Google Maps</span>
                        </a>
                    @endif
                </div>
            </x-card>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('sosPolling', (sosId) => ({
        pollingStatus: '{{ $sos->status }}',
        intervalId: null,

        init() {
            this.intervalId = setInterval(() => this.fetchStatus(), 5000);
        },

        get statusBadge() {
            const colors = {
                masuk: 'bg-red-100 text-red-800',
                diproses: 'bg-yellow-100 text-yellow-800',
                selesai: 'bg-green-100 text-green-800',
                ditolak: 'bg-surface text-text-secondary',
            };
            const color = colors[this.pollingStatus] || 'bg-surface text-text-secondary';
            return `<span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full ${color}">${this.pollingStatus}</span>`;
        },

        async fetchStatus() {
            try {
                const res = await fetch('/sos/active');
                if (!res.ok) return;
            } catch (e) {}
        },

        destroy() {
            if (this.intervalId) clearInterval(this.intervalId);
        }
    }));
});
</script>
@endpush
</x-app-layout>
