<x-app-layout>
<x-slot:header>Tiket #{{ $ticket->queue_number }}</x-slot:header>

<div x-data="ticketPolling('{{ route('poll.queue.status', $ticket->id) }}')" x-init="init()" class="max-w-md mx-auto">

    {{-- Success / Error flash --}}
    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 rounded-xl p-3 flex items-center gap-2 text-sm text-green-700">
            <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>
            {{ session('success') }}
        </div>
    @endif
    @error('ticket')
        <div class="mb-4 bg-red-50 border border-red-200 rounded-xl p-3 flex items-center gap-2 text-sm text-red-700">
            <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
            {{ $message }}
        </div>
    @enderror

    {{-- Completed / Cancelled state banner --}}
    @if($ticket->status === 'selesai')
        <div class="mb-4 bg-green-50 border border-green-200 rounded-xl p-4 flex items-center gap-3">
            <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center shrink-0">
                <i data-lucide="circle-check-big" class="w-5 h-5 text-green-600"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-green-800">Antrean Selesai</p>
                <p class="text-xs text-green-600 mt-0.5">Layanan telah selesai dilakukan{{ $ticket->completed_at ? ' pada ' . $ticket->completed_at->format('d M Y, H:i') . ' WIB' : '' }}. Terima kasih!</p>
            </div>
        </div>
    @elseif($ticket->status === 'batal')
        <div class="mb-4 bg-red-50 border border-red-200 rounded-xl p-4 flex items-center gap-3">
            <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center shrink-0">
                <i data-lucide="circle-x" class="w-5 h-5 text-red-600"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-red-800">Antrean Dibatalkan</p>
                <p class="text-xs text-red-600 mt-0.5">Antrean ini telah dibatalkan{{ $ticket->cancelled_at ? ' pada ' . $ticket->cancelled_at->format('d M Y, H:i') . ' WIB' : '' }}.</p>
            </div>
        </div>
    @elseif($ticket->status === 'dipanggil')
        <div class="mb-4 bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
                <i data-lucide="bell-ring" class="w-5 h-5 text-blue-600 animate-bounce"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-blue-800">Anda Sedang Dipanggil!</p>
                <p class="text-xs text-blue-600 mt-0.5">Segera menuju loket layanan dengan membawa tiket ini.</p>
            </div>
        </div>
    @endif

    <div class="bg-surface-alt rounded-2xl shadow-card-lg overflow-hidden border border-border">

        <!-- Header: Instansi info with photo backdrop -->
        <div class="relative bg-secondary text-white p-6 overflow-hidden">
            @if($ticket->serviceSlot->service->institution->photo)
                <div class="absolute inset-0 opacity-20 bg-cover bg-center filter blur-sm scale-105"
                     style="background-image: url('{{ asset('storage/' . $ticket->serviceSlot->service->institution->photo) }}');"></div>
            @endif
            <div class="relative z-10 flex items-center gap-4">
                @if($ticket->serviceSlot->service->institution->photo)
                    <img src="{{ asset('storage/' . $ticket->serviceSlot->service->institution->photo) }}"
                         alt="{{ $ticket->serviceSlot->service->institution->name }}"
                         class="w-14 h-14 rounded-xl object-cover border border-white/20 shadow-sm shrink-0">
                @else
                    <div class="w-14 h-14 bg-white/10 rounded-xl flex items-center justify-center border border-white/10 text-white shrink-0">
                        <i data-lucide="building-2" class="w-7 h-7"></i>
                    </div>
                @endif
                <div class="min-w-0">
                    <h3 class="font-bold text-white text-base leading-tight truncate">{{ $ticket->serviceSlot->service->institution->name }}</h3>
                    <p class="text-xs text-white/80 truncate mt-0.5">{{ $ticket->serviceSlot->service->name }}</p>
                </div>
            </div>
        </div>

        <!-- Boarding Pass Cutout -->
        <div class="relative flex items-center bg-surface-alt px-4 py-1">
            <div class="absolute -left-2.5 w-5 h-5 bg-surface rounded-full border border-border"></div>
            <div class="w-full border-t-2 border-dashed border-border/80"></div>
            <div class="absolute -right-2.5 w-5 h-5 bg-surface rounded-full border border-border"></div>
        </div>

        <!-- Ticket Body -->
        <div class="p-6 space-y-6 bg-surface-alt">

            <!-- Queue Number Display -->
            <div class="text-center bg-surface rounded-xl p-4 border border-border/60">
                <p class="text-[10px] uppercase tracking-wider text-text-muted font-bold">Nomor Antrean</p>
                <p class="text-5xl font-extrabold text-primary mt-1 tracking-tight">#{{ $ticket->queue_number }}</p>
            </div>

            <!-- QR Code -->
            <div class="flex flex-col items-center justify-center">
                <div class="bg-white p-4 rounded-2xl border border-border shadow-sm">
                    <img src="data:image/svg+xml;base64,{{ $ticket->qr_code }}"
                         alt="QR Code"
                         class="w-40 h-40">
                </div>
                <p class="text-[10px] text-text-muted mt-3 uppercase tracking-wider font-semibold">Kode: {{ $ticket->booking_code }}</p>
            </div>

            <!-- Ticket Details -->
            <div class="border-t border-border pt-5 space-y-3.5">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-text-secondary flex items-center gap-1.5">
                        <i data-lucide="calendar" class="w-4 h-4 text-text-muted"></i> Tanggal
                    </span>
                    <span class="font-semibold text-text-primary">{{ $ticket->created_at->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-text-secondary flex items-center gap-1.5">
                        <i data-lucide="clock" class="w-4 h-4 text-text-muted"></i> Sesi Waktu
                    </span>
                    <span class="font-semibold text-text-primary">{{ $ticket->serviceSlot->start_time }} – {{ $ticket->serviceSlot->end_time }}</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-text-secondary flex items-center gap-1.5">
                        <i data-lucide="hourglass" class="w-4 h-4 text-text-muted"></i> Estimasi
                    </span>
                    <span class="font-semibold text-text-primary" x-text="estimatedTime ? estimatedTime + ' WIB' : 'Menunggu'">
                        {{ $ticket->estimated_time?->format('H:i') ? $ticket->estimated_time->format('H:i') . ' WIB' : 'Menunggu' }}
                    </span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-text-secondary flex items-center gap-1.5">
                        <i data-lucide="info" class="w-4 h-4 text-text-muted"></i> Status
                    </span>
                    <span x-html="statusBadge"></span>
                </div>
            </div>

        </div>

        <!-- Ticket Footer -->
        <div class="bg-surface px-6 py-4 border-t border-border/80">
            @if(in_array($ticket->status, ['menunggu', 'dipanggil']))
                <div class="flex items-center justify-between gap-3">
                    <p class="text-xs text-text-muted flex items-center gap-1.5">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                        Tunjukkan QR Code kepada petugas.
                    </p>
                    {{-- Cancel Trigger Button --}}
                    <button type="button" @click="showConfirmModal = true"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors">
                        <i data-lucide="x-circle" class="w-3.5 h-3.5"></i>
                        Batalkan Antrean
                    </button>
                </div>
            @else
                <p class="text-xs text-text-muted text-center flex items-center justify-center gap-1.5">
                    <i data-lucide="archive" class="w-3.5 h-3.5"></i>
                    Tiket ini sudah tidak aktif.
                </p>
            @endif
        </div>
    </div>

    <!-- Custom Confirmation Modal -->
    <div x-show="showConfirmModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
        
        <div class="bg-surface-alt border border-border w-full max-w-sm rounded-2xl shadow-card-lg p-6 text-center transform transition-all"
             x-show="showConfirmModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 scale-95"
             @click.outside="if (!submitting) showConfirmModal = false">
            
            <div class="w-12 h-12 rounded-full bg-red-50 border border-red-200 text-red-600 flex items-center justify-center mx-auto mb-4">
                <i data-lucide="alert-triangle" class="w-6 h-6"></i>
            </div>
            
            <h3 class="text-base font-bold text-text-primary mb-2">Batalkan Antrean?</h3>
            <p class="text-sm text-text-secondary mb-6">Tindakan ini tidak dapat dibatalkan. Anda harus mengambil antrean baru jika berubah pikiran.</p>
            
            <div class="flex items-center gap-3">
                <button type="button" @click="showConfirmModal = false" :disabled="submitting"
                        class="flex-1 px-4 py-2.5 text-sm font-semibold text-text-secondary bg-surface border border-border rounded-xl hover:bg-surface/85 transition-colors disabled:opacity-50">
                    Batal
                </button>
                <form id="cancel-form" method="POST" action="{{ route('queue.tickets.cancel', $ticket) }}" class="flex-1" @submit="submitting = true; clearInterval(intervalId);">
                    @csrf
                    <button type="submit" :disabled="submitting"
                            class="w-full px-4 py-2.5 text-sm font-semibold text-white bg-red-600 rounded-xl hover:bg-red-700 transition-colors shadow-sm flex items-center justify-center gap-1.5 disabled:opacity-50">
                        <template x-if="submitting">
                            <svg class="animate-spin w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                            </svg>
                        </template>
                        <span x-text="submitting ? 'Memproses...' : 'Yakin, Batalkan'"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="mt-6 flex justify-center">
        <a href="{{ route('queue.tickets') }}"
            class="inline-flex items-center text-sm font-semibold text-primary hover:text-primary/80 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1.5"></i>
            Kembali ke Antrean Saya
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
        showConfirmModal: false,
        submitting: false,

        init() {
            // Only poll if ticket is active
            if (['menunggu', 'dipanggil'].includes(this.status)) {
                this.intervalId = setInterval(() => this.fetchStatus(), 10000);
            }
        },

        get statusBadge() {
            const map = {
                menunggu: { cls: 'bg-yellow-50 text-yellow-700 border border-yellow-200', icon: '⏳', label: 'Menunggu' },
                dipanggil: { cls: 'bg-blue-50 text-blue-700 border border-blue-200', icon: '🔔', label: 'Dipanggil' },
                selesai: { cls: 'bg-green-50 text-green-700 border border-green-200', icon: '✓', label: 'Selesai' },
                batal: { cls: 'bg-red-50 text-red-700 border border-red-200', icon: '✕', label: 'Dibatalkan' },
            };
            const s = map[this.status] || { cls: 'bg-surface text-text-secondary border border-border', icon: '', label: this.status };
            return `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 text-xs font-semibold rounded-full ${s.cls}">${s.icon} ${s.label}</span>`;
        },

        async fetchStatus() {
            if (this.submitting) return;
            try {
                const res = await fetch(pollUrl);
                if (!res.ok) return;
                const data = await res.json();
                if (this.submitting) return;
                this.status = data.status;
                if (data.estimated_time) this.estimatedTime = data.estimated_time;
                // Stop polling when terminal state reached
                if (['selesai', 'batal'].includes(this.status)) {
                    clearInterval(this.intervalId);
                    window.location.reload();
                }
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
