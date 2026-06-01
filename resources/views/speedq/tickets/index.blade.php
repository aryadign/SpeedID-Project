@props(['tickets' => []])

<x-app-layout>
    @section('title', 'Tiket Antrean Saya')
    @section('header', 'Tiket Antrean Saya')

    <div class="flex justify-end mb-6">
        <a href="{{ route('queue.booking') }}"
            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary/90">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
            Ambil Antrean Baru
        </a>
    </div>

    @if ($tickets->isEmpty())
        <div class="text-center py-16">
            <i data-lucide="ticket" class="w-16 h-16 text-text-muted mx-auto mb-4"></i>
            <h3 class="text-lg font-semibold text-text-primary mb-1">Belum Ada Tiket</h3>
            <p class="text-sm text-text-secondary">Anda belum memiliki tiket antrean. Ambil antrean sekarang untuk mendapatkan layanan di instansi tujuan.</p>
            <a href="{{ route('queue.booking') }}"
                class="inline-flex items-center mt-4 px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary/90">
                <i data-lucide="ticket" class="w-4 h-4 mr-2"></i>
                Ambil Antrean
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($tickets as $ticket)
                <a href="{{ route('queue.tickets.show', $ticket) }}"
                    class="block bg-surface-alt rounded-lg shadow-card-sm p-5 hover:shadow-card-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center">
                                <span class="text-2xl font-bold text-primary">{{ $ticket->queue_number }}</span>
                            </div>
                            <div>
                                <p class="font-semibold text-text-primary">{{ $ticket->serviceSlot->service->institution->name }}</p>
                                <p class="text-sm text-text-secondary">{{ $ticket->serviceSlot->service->name }}</p>
                                <p class="text-xs text-text-muted mt-1">{{ $ticket->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <x-status-badge type="{{ $ticket->status }}">
                                {{ ucfirst($ticket->status) }}
                            </x-status-badge>
                            <i data-lucide="chevron-right" class="w-5 h-5 text-text-muted"></i>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</x-app-layout>
