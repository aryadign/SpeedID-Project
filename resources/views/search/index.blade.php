<x-app-layout>
<x-slot:header>Pencarian</x-slot:header>

<div class="max-w-3xl mx-auto">
    <form method="GET" action="{{ route('search') }}" class="mb-8">
        <div class="relative">
            <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-text-muted"></i>
            <input type="text" name="q" value="{{ $query }}"
                   placeholder="Cari instansi, berita, laporan..."
                   class="w-full pl-12 pr-4 py-3.5 bg-surface-alt border border-border rounded-lg text-sm focus:ring-primary focus:border-primary"
                   autofocus>
        </div>
    </form>

    @if($query && strlen($query) >= 2)
        @if($results->count() > 0)
            <div class="space-y-3">
                @foreach($results as $item)
                    <a href="{{ $item['url'] }}" class="flex items-center gap-4 p-4 bg-surface-alt rounded-lg hover:shadow-card-sm transition-all border border-border">
                        <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center shrink-0">
                            <i data-lucide="{{ $item['icon'] }}" class="w-5 h-5 text-primary"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-text-muted">{{ $item['type'] }}</p>
                            <p class="text-sm font-medium text-text-primary truncate">{{ $item['title'] }}</p>
                        </div>
                        <i data-lucide="chevron-right" class="w-4 h-4 text-text-muted shrink-0"></i>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <i data-lucide="search-x" class="w-12 h-12 mx-auto mb-3 text-text-muted opacity-40"></i>
                <p class="text-text-muted">Tidak ditemukan hasil untuk "{{ $query }}"</p>
            </div>
        @endif
    @elseif($query)
        <p class="text-sm text-text-muted text-center">Minimal 2 karakter untuk mencari</p>
    @endif
</div>
</x-app-layout>
