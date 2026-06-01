<x-public-layout>
<x-slot:title>Lacak Laporan - Speed ID</x-slot:title>

<div class="min-h-screen flex items-center justify-center px-4 pt-24 pb-16">
    <div class="w-full max-w-lg">
        <div class="text-center mb-8">
            <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i data-lucide="search" class="w-7 h-7 text-primary"></i>
            </div>
            <h1 class="text-2xl font-bold text-text-primary">Lacak Laporan</h1>
            <p class="text-text-muted text-sm mt-1">Masukkan kode pelacakan untuk melihat status laporan Anda</p>
        </div>

        <x-card class="mb-6">
            <form method="POST" action="{{ route('reports.track') }}" class="flex gap-3">
                @csrf
                <input type="text" name="tracking_code" value="{{ old('tracking_code', request('tracking_code')) }}" required maxlength="8" placeholder="Contoh: ABC12345" class="flex-1 rounded-lg border-border bg-surface text-text-primary text-sm px-4 py-2.5 focus:ring-primary focus:border-primary placeholder:text-text-muted font-mono uppercase text-center tracking-widest" />
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white text-sm font-medium rounded-full hover:bg-primary/90 transition-all shrink-0">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    Lacak
                </button>
            </form>
            <x-input-error :messages="$errors->get('tracking_code')" class="mt-2" />
        </x-card>

        @if(isset($report) && $report)
            <x-card>
                <div class="flex items-start justify-between gap-4 mb-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-medium text-primary bg-primary/5 px-2.5 py-0.5 rounded-full">{{ $report->category->name }}</span>
                            <span class="text-xs text-text-muted">{{ $report->created_at->format('d M Y') }}</span>
                        </div>
                        <h3 class="font-semibold text-text-primary">{{ $report->title }}</h3>
                        <p class="text-xs text-text-muted font-mono mt-1">Kode: <span class="text-primary font-semibold">{{ $report->tracking_code }}</span></p>
                    </div>
                    <x-status-badge :type="$report->status" size="md">{{ $report->status }}</x-status-badge>
                </div>

                @if($report->location)
                    <div class="flex items-center gap-2 text-sm text-text-secondary mb-3">
                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                        <span>{{ $report->location }}</span>
                    </div>
                @endif

                <p class="text-sm text-text-secondary">{{ Str::limit($report->description, 200) }}</p>

                <div class="mt-4 pt-4 border-t border-border">
                    <h4 class="text-sm font-medium text-text-primary mb-3">Riwayat Status</h4>
                    @php
                        $timeline = $report->activities ?? collect();
                    @endphp
                    @if($timeline->count() > 0)
                        <div class="relative pl-5">
                            <div class="absolute left-2 top-1 bottom-1 w-0.5 bg-border"></div>
                            <div class="space-y-3">
                                @foreach($timeline as $activity)
                                    <div class="flex gap-2 relative">
                                        <div class="w-4 h-4 rounded-full border-2 border-primary bg-surface-alt flex items-center justify-center shrink-0 z-10 -ml-[13px]">
                                            <div class="w-1.5 h-1.5 rounded-full bg-primary"></div>
                                        </div>
                                        <div>
                                            <p class="text-xs font-medium text-text-primary capitalize">{{ $activity->description }}</p>
                                            <p class="text-xs text-text-muted">{{ $activity->created_at->format('d M Y, H:i') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="flex gap-2">
                            <div class="w-4 h-4 rounded-full border-2 border-primary bg-primary/10 flex items-center justify-center shrink-0 mt-0.5">
                                <div class="w-1.5 h-1.5 rounded-full bg-primary"></div>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-text-primary capitalize">{{ $report->status }}</p>
                                <p class="text-xs text-text-muted">{{ $report->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </x-card>
        @elseif(request()->method() === 'POST' && !$errors->has('tracking_code'))
            <x-card>
                <div class="text-center py-8">
                    <i data-lucide="file-x" class="w-12 h-12 mx-auto mb-3 text-text-muted opacity-40"></i>
                    <p class="text-text-muted text-sm">Laporan tidak ditemukan. Periksa kembali kode pelacakan Anda.</p>
                </div>
            </x-card>
        @endif

        <p class="text-xs text-text-muted text-center mt-6">Kode pelacakan terdiri dari 8 karakter alfanumerik dan diberikan saat laporan berhasil dibuat.</p>
    </div>
</div>
</x-public-layout>
