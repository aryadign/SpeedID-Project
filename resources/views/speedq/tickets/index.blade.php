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
                    class="block bg-surface-alt rounded-xl border border-border p-5 hover:shadow-card-md transition-all duration-200">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            @if($ticket->serviceSlot->service->institution->photo)
                                <img src="{{ asset('storage/' . $ticket->serviceSlot->service->institution->photo) }}"
                                     alt="{{ $ticket->serviceSlot->service->institution->name }}"
                                     class="w-16 h-16 rounded-xl object-cover border border-border shadow-sm shrink-0">
                            @else
                                <div class="w-16 h-16 bg-primary/10 rounded-xl flex items-center justify-center border border-primary/10 text-primary shrink-0">
                                    <i data-lucide="building-2" class="w-8 h-8"></i>
                                </div>
                            @endif
                            <div class="min-w-0">
                                <p class="font-semibold text-text-primary text-base truncate">{{ $ticket->serviceSlot->service->institution->name }}</p>
                                <p class="text-sm text-text-secondary truncate mt-0.5">{{ $ticket->serviceSlot->service->name }}</p>
                                <div class="flex items-center gap-3 mt-1.5 flex-wrap">
                                    <span class="text-xs text-text-muted flex items-center gap-1">
                                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                                        {{ $ticket->created_at->format('d M Y') }}
                                    </span>
                                    <span class="text-xs text-text-muted flex items-center gap-1">
                                        <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                        {{ $ticket->serviceSlot->start_time }} - {{ $ticket->serviceSlot->end_time }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between sm:justify-end gap-4 border-t border-border pt-3 sm:border-t-0 sm:pt-0">
                            <div class="flex items-center gap-3">
                                <div class="text-right hidden md:block">
                                    <span class="text-[10px] uppercase tracking-wider text-text-muted block font-medium">Nomor</span>
                                    <span class="text-lg font-bold text-primary">#{{ $ticket->queue_number }}</span>
                                </div>
                                <div class="bg-primary/5 text-primary text-base font-extrabold px-3 py-1 rounded-lg border border-primary/10 md:hidden">
                                    #{{ $ticket->queue_number }}
                                </div>
                                <x-status-badge type="{{ $ticket->status }}">
                                    {{ ucfirst($ticket->status) }}
                                </x-status-badge>
                            </div>
                            <i data-lucide="chevron-right" class="w-5 h-5 text-text-muted hidden sm:block"></i>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</x-app-layout>
