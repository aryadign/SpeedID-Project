<x-app-layout>
<x-slot:header>Laporan Saya</x-slot:header>
<x-slot:title>Laporan - Speed ID</x-slot:title>

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <form method="GET" action="{{ route('reports.index') }}" class="flex items-center gap-3">
        <select name="category" onchange="this.form.submit()" class="rounded-lg border-border bg-surface-alt text-text-primary text-sm px-4 py-2.5 focus:ring-primary focus:border-primary">
            <option value="">Semua Kategori</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
        </select>
    </form>
    <a href="{{ route('reports.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white text-sm font-medium rounded-full hover:bg-primary/90 transition-all shadow-card-sm">
        <i data-lucide="plus" class="w-4 h-4"></i>
        Buat Laporan Baru
    </a>
</div>

@if($reports->count() > 0)
    <div class="space-y-4">
        @foreach($reports as $report)
            <x-card class="hover:shadow-card-lg transition-shadow">
                <a href="{{ route('reports.show', $report->id) }}" class="block">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1.5">
                                <span class="text-xs font-medium text-primary bg-primary/5 px-2.5 py-0.5 rounded-full">{{ $report->category->name }}</span>
                                <span class="text-xs text-text-muted">{{ $report->created_at->format('d M Y, H:i') }}</span>
                            </div>
                            <h3 class="font-semibold text-text-primary truncate">{{ $report->title }}</h3>
                            <p class="text-sm text-text-muted mt-1 line-clamp-2">{{ Str::limit($report->description, 120) }}</p>
                            @if($report->tracking_code)
                                <p class="text-xs text-text-muted mt-2 font-mono">Kode: {{ $report->tracking_code }}</p>
                            @endif
                        </div>
                        <div class="shrink-0">
                            <x-status-badge :type="$report->status">{{ $report->status }}</x-status-badge>
                        </div>
                    </div>
                </a>
            </x-card>
        @endforeach
    </div>
    <div class="mt-6">
        {{ $reports->links() }}
    </div>
@else
    <x-card>
        <div class="text-center py-12">
            <i data-lucide="file-text" class="w-16 h-16 mx-auto mb-4 text-text-muted opacity-40"></i>
            <h3 class="text-lg font-semibold text-text-primary mb-2">Belum Ada Laporan</h3>
            <p class="text-text-muted text-sm mb-6">Anda belum membuat laporan apapun. Mulai dengan membuat laporan baru.</p>
            <a href="{{ route('reports.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white text-sm font-medium rounded-full hover:bg-primary/90 transition-all">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Buat Laporan Baru
            </a>
        </div>
    </x-card>
@endif
</x-app-layout>
