<x-app-layout>
<x-slot:header>Dashboard</x-slot:header>
<x-slot:title>Dashboard - Speed ID</x-slot:title>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <x-stat-card label="Antrean Aktif" :value="$active_queue ? '#' . $active_queue->queue_number : 'Tidak ada'" icon="ticket" color="primary" />
    <x-stat-card label="Total Laporan" :value="$recent_reports->count()" icon="triangle-alert" color="warning" />
    <x-stat-card label="Riwayat SOS" :value="$sos_history->count()" icon="shield-alert" color="danger" />
    <x-stat-card label="Berita Baru" :value="$latest_news->count()" icon="newspaper" color="info" />
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <x-card>
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-lg">Antrean Aktif</h3>
            <a href="{{ route('queue.tickets') }}"
               class="inline-flex items-center gap-1.5 text-xs font-semibold text-primary hover:text-primary/80 transition-colors">
                <i data-lucide="list-ordered" class="w-3.5 h-3.5"></i>
                Antrean Saya
            </a>
        </div>
        @if($active_queue)
            <div class="relative bg-surface rounded-xl border border-border overflow-hidden shadow-card-sm">
                <!-- Top Section (Institution & Service Info) -->
                <div class="p-5 flex items-center gap-4 bg-surface-alt">
                    @if($active_queue->serviceSlot->service->institution->photo)
                        <img src="{{ asset('storage/' . $active_queue->serviceSlot->service->institution->photo) }}"
                             alt="{{ $active_queue->serviceSlot->service->institution->name }}"
                             class="w-12 h-12 rounded-xl object-cover border border-border shadow-sm shrink-0">
                    @else
                        <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center border border-primary/10 text-primary shrink-0">
                            <i data-lucide="building-2" class="w-6 h-6"></i>
                        </div>
                    @endif
                    <div class="min-w-0">
                        <h4 class="font-semibold text-text-primary text-sm truncate">{{ $active_queue->serviceSlot->service->institution->name }}</h4>
                        <p class="text-xs text-text-secondary truncate mt-0.5">{{ $active_queue->serviceSlot->service->name }}</p>
                        <p class="text-[10px] text-text-muted mt-1 uppercase tracking-wider font-medium">{{ $active_queue->created_at->format('d M Y') }}</p>
                    </div>
                </div>

                <!-- Boarding Pass Ticket Cutout Line -->
                <div class="relative flex items-center bg-surface-alt px-4 py-1">
                    <div class="absolute -left-2 w-4 h-4 bg-surface rounded-full border border-border"></div>
                    <div class="w-full border-t border-dashed border-border"></div>
                    <div class="absolute -right-2 w-4 h-4 bg-surface rounded-full border border-border"></div>
                </div>

                <!-- Ticket Body -->
                <div class="p-5 bg-surface-alt">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <span class="text-[10px] uppercase tracking-wider text-text-muted block font-medium">Nomor Antrean</span>
                            <span class="text-3xl font-extrabold text-primary leading-none">#{{ $active_queue->queue_number }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] uppercase tracking-wider text-text-muted block font-medium">Sesi Waktu</span>
                            <span class="text-sm font-semibold text-text-primary block mt-1">
                                {{ $active_queue->serviceSlot->start_time }} - {{ $active_queue->serviceSlot->end_time }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between border-t border-border pt-4">
                        <div>
                            <span class="text-[10px] uppercase tracking-wider text-text-muted block font-medium mb-1">Status</span>
                            <x-status-badge :type="$active_queue->status">{{ ucfirst($active_queue->status) }}</x-status-badge>
                        </div>
                        <a href="{{ route('queue.tickets.show', $active_queue->id) }}"
                           class="inline-flex items-center justify-center gap-1.5 px-4 py-2 text-xs font-semibold text-white bg-primary rounded-lg hover:bg-primary/95 transition-all shadow-sm">
                            Pantau Antrean
                            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-8 text-text-muted">
                <i data-lucide="ticket" class="w-12 h-12 mx-auto mb-3 opacity-40"></i>
                <p>Belum ada antrean aktif</p>
                <a href="{{ route('queue.booking') }}" class="text-primary text-sm font-medium hover:underline mt-2 inline-block">Booking Antrean</a>
            </div>
        @endif
    </x-card>

    <x-card>
        <h3 class="font-semibold text-lg mb-4">Laporan Terbaru</h3>
        @if($recent_reports->count() > 0)
            <div class="space-y-3">
                @foreach($recent_reports as $report)
                    <a href="{{ route('reports.show', $report->id) }}" class="flex items-center justify-between p-3 hover:bg-surface rounded-lg transition-colors">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate">{{ $report->title }}</p>
                            <p class="text-xs text-text-muted">{{ $report->category->name }} &middot; {{ $report->created_at->diffForHumans() }}</p>
                        </div>
                        <x-status-badge type="{{ $report->status }}" size="sm">{{ $report->status }}</x-status-badge>
                    </a>
                @endforeach
            </div>
            <a href="{{ route('reports.index') }}" class="text-sm text-primary font-medium hover:underline mt-3 inline-block">Lihat semua</a>
        @else
            <div class="text-center py-8 text-text-muted">
                <i data-lucide="file-text" class="w-12 h-12 mx-auto mb-3 opacity-40"></i>
                <p>Belum ada laporan</p>
                <a href="{{ route('reports.create') }}" class="text-primary text-sm font-medium hover:underline mt-2 inline-block">Buat Laporan</a>
            </div>
        @endif
    </x-card>
</div>

<div class="grid lg:grid-cols-2 gap-6 mt-6">
    <x-card>
        <h3 class="font-semibold text-lg mb-4">Berita Terbaru</h3>
        @if($latest_news->count() > 0)
            <div class="space-y-4">
                @foreach($latest_news as $post)
                    <a href="{{ route('news.show', $post->slug) }}" class="flex gap-3 group">
                        <div class="w-16 h-16 bg-surface rounded-lg flex items-center justify-center shrink-0">
                            <i data-lucide="newspaper" class="w-6 h-6 text-text-muted"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium group-hover:text-primary transition-colors">{{ $post->title }}</p>
                            <p class="text-xs text-text-muted mt-1">{{ $post->published_at->format('d M Y') }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-text-muted text-sm">Belum ada berita</p>
        @endif
    </x-card>

    <x-card>
        <h3 class="font-semibold text-lg mb-4">Riwayat SOS</h3>
        @if($sos_history->count() > 0)
            <div class="space-y-3">
                @foreach($sos_history as $sos)
                    <div class="flex items-center justify-between p-3 hover:bg-surface rounded-lg">
                        <div>
                            <p class="text-sm font-medium">{{ ucfirst($sos->emergency_type) }}</p>
                            <p class="text-xs text-text-muted">{{ $sos->created_at->diffForHumans() }}</p>
                        </div>
                        <x-status-badge :type="$sos->status" size="sm">{{ $sos->status }}</x-status-badge>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-text-muted">
                <i data-lucide="shield" class="w-12 h-12 mx-auto mb-3 opacity-40"></i>
                <p>Belum ada riwayat SOS</p>
            </div>
        @endif
    </x-card>
</div>
</x-app-layout>
