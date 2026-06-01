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
        <h3 class="font-semibold text-lg mb-4">Antrean Aktif</h3>
        @if($active_queue)
            <div class="bg-primary/5 rounded-lg p-6 text-center">
                <p class="text-sm text-text-muted mb-1">Nomor Antrean</p>
                <p class="text-4xl font-bold text-primary mb-2">#{{ $active_queue->queue_number }}</p>
                <p class="text-sm font-medium">{{ $active_queue->serviceSlot->service->name }}</p>
                <p class="text-xs text-text-muted">{{ $active_queue->serviceSlot->service->institution->name }}</p>
                <div class="mt-4">
                    <x-status-badge :type="$active_queue->status">{{ $active_queue->status }}</x-status-badge>
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
