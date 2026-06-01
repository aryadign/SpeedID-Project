<x-app-layout>
<x-slot:header>Detail Laporan</x-slot:header>

<div x-data="reportPolling('{{ route('poll.report.status', $report->id) }}')" x-init="init()" class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <x-card>
            <div class="flex items-start justify-between gap-4 mb-4">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs font-medium text-primary bg-primary/5 px-2.5 py-0.5 rounded-full">{{ $report->category->name }}</span>
                        <span class="text-xs text-text-muted">{{ $report->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <h2 class="text-xl font-bold text-text-primary">{{ $report->title }}</h2>
                    @if($report->tracking_code)
                        <p class="text-xs text-text-muted font-mono mt-1">Kode Pelacakan: <span class="text-primary font-semibold">{{ $report->tracking_code }}</span></p>
                    @endif
                </div>
                <span x-html="statusBadge"></span>
            </div>

            @if($report->latitude && $report->longitude)
                <div class="flex items-center gap-2 text-sm text-text-secondary mb-4">
                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                    <span>{{ $report->latitude }}, {{ $report->longitude }}</span>
                </div>
            @endif

            <div class="prose prose-sm max-w-none text-text-primary">
                {{ nl2br(e($report->description)) }}
            </div>

            @if($report->anonymous)
                <p class="text-xs text-text-muted mt-4 italic">Dilaporkan secara anonim</p>
            @else
                <p class="text-xs text-text-muted mt-4">Dilaporkan oleh: {{ $report->user->name }}</p>
            @endif
        </x-card>

        @if($report->media && $report->media->count() > 0)
            <x-card>
                <h3 class="font-semibold text-lg mb-4">Lampiran</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach($report->media as $media)
                        <a href="{{ Storage::url($media->path) }}" target="_blank" class="block aspect-video bg-surface rounded-lg overflow-hidden group relative">
                            @if(str_starts_with($media->mime_type, 'image'))
                                <img src="{{ Storage::url($media->path) }}" alt="Lampiran" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i data-lucide="video" class="w-8 h-8 text-text-muted"></i>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors flex items-center justify-center">
                                <i data-lucide="expand" class="w-5 h-5 text-white opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            </div>
                        </a>
                    @endforeach
                </div>
            </x-card>
        @endif

        <x-card>
            <h3 class="font-semibold text-lg mb-4">Komentar</h3>
            <div id="comments-section" class="space-y-4 mb-6">
                @if($report->comments && $report->comments->count() > 0)
                    @foreach($report->comments as $comment)
                        <div class="flex gap-3 p-3 bg-surface rounded-lg">
                            <div class="w-8 h-8 bg-primary/10 rounded-full flex items-center justify-center shrink-0">
                                <span class="text-xs font-medium text-primary">{{ substr($comment->user->name, 0, 1) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-sm font-medium text-text-primary">{{ $comment->user->name }}</span>
                                    <span class="text-xs text-text-muted">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-text-secondary">{{ $comment->body }}</p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-sm text-text-muted">Belum ada komentar.</p>
                @endif
            </div>

            <form method="POST" action="{{ route('reports.comments.store', $report->id) }}" class="flex gap-3">
                @csrf
                <textarea name="body" rows="2" required placeholder="Tulis komentar..." class="flex-1 rounded-lg border-border bg-surface text-text-primary text-sm px-4 py-2.5 focus:ring-primary focus:border-primary placeholder:text-text-muted resize-none"></textarea>
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary text-white text-sm font-medium rounded-full hover:bg-primary/90 transition-all self-end shrink-0">
                    <i data-lucide="send" class="w-4 h-4"></i>
                </button>
            </form>
            <x-input-error :messages="$errors->get('body')" class="mt-1" />
        </x-card>
    </div>

    <div class="space-y-6">
        <x-card>
            <h3 class="font-semibold text-lg mb-4">Riwayat Status</h3>
            <div id="timeline" class="relative">
                @php
                    $timeline = $report->activities ?? collect();
                @endphp
                @if($timeline->count() > 0)
                    <div class="absolute left-3.5 top-1 bottom-1 w-0.5 bg-border"></div>
                    <div class="space-y-5">
                        @foreach($timeline as $activity)
                            <div class="flex gap-3 relative">
                                <div class="w-7 h-7 rounded-full border-2 border-primary bg-surface-alt flex items-center justify-center shrink-0 z-10">
                                    <div class="w-2 h-2 rounded-full bg-primary"></div>
                                </div>
                                <div class="flex-1 min-w-0 pt-0.5">
                                    <p class="text-sm font-medium text-text-primary capitalize">{{ $activity->description }}</p>
                                    <p class="text-xs text-text-muted">{{ $activity->created_at->format('d M Y, H:i') }}</p>
                                    @if($activity->properties && $activity->properties->has('attributes'))
                                        <p class="text-xs text-text-muted mt-0.5">{{ $activity->properties->get('attributes')['notes'] ?? '' }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex gap-3">
                        <div class="w-7 h-7 rounded-full border-2 border-primary bg-primary/10 flex items-center justify-center shrink-0">
                            <div class="w-2 h-2 rounded-full bg-primary"></div>
                        </div>
                        <div class="flex-1 pt-0.5">
                            <p class="text-sm font-medium text-text-primary capitalize">{{ $report->status }}</p>
                            <p class="text-xs text-text-muted">{{ $report->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </x-card>

        @if($report->latitude && $report->longitude)
            <x-card>
                <h3 class="font-semibold text-lg mb-4">Lokasi</h3>
                <x-map :latitude="$report->latitude" :longitude="$report->longitude" height="200px" />
            </x-card>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('reportPolling', (pollUrl) => ({
        pollingStatus: '{{ $report->status }}',
        intervalId: null,

        init() {
            this.intervalId = setInterval(() => this.fetchStatus(), 15000);
        },

        get statusBadge() {
            const colors = {
                terkirim: 'bg-blue-100 text-blue-800',
                diverifikasi: 'bg-purple-100 text-purple-800',
                diproses: 'bg-yellow-100 text-yellow-800',
                selesai: 'bg-green-100 text-green-800',
                ditolak: 'bg-red-100 text-red-800',
            };
            const color = colors[this.pollingStatus] || 'bg-surface text-text-secondary';
            return `<span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full ${color}">${this.pollingStatus}</span>`;
        },

        async fetchStatus() {
            try {
                const res = await fetch(pollUrl);
                if (!res.ok) return;
                const data = await res.json();
                this.pollingStatus = data.status;
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
