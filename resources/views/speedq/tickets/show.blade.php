<x-app-layout>
<x-slot:header>Tiket #{{ $ticket->queue_number }}</x-slot:header>

<div x-data="ticketPolling('{{ route('poll.queue.status', $ticket->id) }}')" x-init="init()" class="max-w-md mx-auto">
    <div class="bg-surface-alt rounded-xl shadow-card-md overflow-hidden border border-border">
        <div class="bg-primary px-6 py-5 text-center">
            <p class="text-primary/80 text-xs font-medium uppercase tracking-wider">Nomor Antrean</p>
            <p class="text-5xl font-bold text-white mt-1">{{ $ticket->queue_number }}</p>
        </div>

        <div class="p-6 space-y-4">
            <div class="flex justify-center">
                <div class="bg-surface p-3 rounded-lg">
                    <img src="data:image/png;base64,{{ $ticket->qr_code }}"
                         alt="QR Code"
                         class="w-32 h-32">
                </div>
            </div>

            <div class="border-t border-border pt-4 space-y-3">
                <div class="flex justify-between">
                    <span class="text-sm text-text-secondary">Instansi</span>
                    <span class="text-sm font-medium text-text-primary">{{ $ticket->serviceSlot->service->institution->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-text-secondary">Layanan</span>
                    <span class="text-sm font-medium text-text-primary">{{ $ticket->serviceSlot->service->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-text-secondary">Tanggal</span>
                    <span class="text-sm font-medium text-text-primary">{{ $ticket->created_at->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-text-secondary">Jam</span>
                    <span class="text-sm font-medium text-text-primary">{{ $ticket->serviceSlot->start_time }} - {{ $ticket->serviceSlot->end_time }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-text-secondary">Estimasi</span>
                    <span class="text-sm font-medium text-text-primary" x-text="estimatedTime || 'Menunggu'">{{ $ticket->estimated_time?->format('H:i') ?? 'Menunggu' }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-text-secondary">Status</span>
                    <span x-html="statusBadge"></span>
                </div>
            </div>
        </div>

        <div class="bg-surface px-6 py-4 border-t border-border">
            <p class="text-xs text-text-muted text-center">
                Tunjukkan tiket ini kepada petugas saat dipanggil.
            </p>
        </div>
    </div>

    <div class="mt-4 flex justify-center gap-4">
        <a href="{{ route('queue.tickets') }}"
            class="inline-flex items-center text-sm font-medium text-primary hover:text-primary/80">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i>
            Kembali
        </a>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('ticketPolling', (pollUrl) => ({
        status: '{{ $ticket->status }}',
        estimatedTime: '{{ $ticket->estimated_time?->format("H:i") ?? "" }}',
        intervalId: null,

        init() {
            this.intervalId = setInterval(() => this.fetchStatus(), 10000);
        },

        get statusBadge() {
            const colors = {
                menunggu: 'bg-yellow-100 text-yellow-800',
                dipanggil: 'bg-blue-100 text-blue-800',
                selesai: 'bg-green-100 text-green-800',
                batal: 'bg-red-100 text-red-800',
            };
            const color = colors[this.status] || 'bg-surface text-text-secondary';
            return `<span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full ${color}">${this.status}</span>`;
        },

        async fetchStatus() {
            try {
                const res = await fetch(pollUrl);
                if (!res.ok) return;
                const data = await res.json();
                this.status = data.status;
                if (data.estimated_time) this.estimatedTime = data.estimated_time;
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
