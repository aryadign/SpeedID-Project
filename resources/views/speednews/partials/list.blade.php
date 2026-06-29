@guest
{{-- Search Form --}}
<form method="GET" action="{{ route('news.index') }}" class="max-w-xl mx-auto mb-8">
    <div class="relative">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berita..." class="w-full pl-12 pr-4 py-3 bg-surface border border-border rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
        <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-text-muted"></i>
    </div>
</form>
@endguest

{{-- Categories Filter --}}
<div class="flex flex-wrap items-center justify-center gap-2 mb-10">
    <a href="{{ route('news.index') }}" class="px-4 py-2 text-sm font-medium rounded-full transition-all {{ !request('category_id') ? 'bg-primary text-white shadow-card-sm' : 'bg-surface-alt text-text-secondary hover:bg-primary/10 hover:text-primary border border-border' }}">
        Semua
    </a>
    @foreach($categories as $category)
        <a href="{{ route('news.index', array_merge(request()->except('category_id', 'page'), ['category_id' => $category->id])) }}"
           class="px-4 py-2 text-sm font-medium rounded-full transition-all {{ request('category_id') == $category->id ? 'bg-primary text-white shadow-card-sm' : 'bg-surface-alt text-text-secondary hover:bg-primary/10 hover:text-primary border border-border' }}">
            {{ $category->name }}
        </a>
    @endforeach
</div>

@if($posts->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($posts as $post)
            <a href="{{ route('news.show', $post->slug) }}" class="group bg-surface-alt rounded-xl shadow-card-sm hover:shadow-card-md transition-all overflow-hidden border border-border">
                <div class="aspect-[16/10] bg-surface overflow-hidden">
                    @if($post->thumbnail)
                        <img src="{{ asset('storage/' . $post->thumbnail) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-primary/5">
                            <i data-lucide="newspaper" class="w-12 h-12 text-primary opacity-40"></i>
                        </div>
                    @endif
                </div>
                <div class="p-5">
                    <div class="flex items-center gap-2 mb-3">
                        @if($post->is_emergency)
                            <span class="px-2 py-0.5 text-xs font-medium bg-red-100 text-red-700 rounded-full">Darurat</span>
                        @endif
                        @if($post->category)
                            <span class="px-2 py-0.5 text-xs font-medium bg-primary/5 text-primary rounded-full">{{ $post->category->name }}</span>
                        @endif
                    </div>
                    <h3 class="font-semibold text-text-primary group-hover:text-primary transition-colors line-clamp-2 mb-2 text-sm md:text-base">{{ $post->title }}</h3>
                    <p class="text-xs text-text-muted">{{ $post->published_at ? $post->published_at->format('d M Y') : '' }}</p>
                </div>
            </a>
        @endforeach
    </div>
    <div class="mt-10">
        {{ $posts->withQueryString()->links() }}
    </div>
@else
    <div class="text-center py-20 bg-surface-alt rounded-xl border border-border">
        <i data-lucide="newspaper" class="w-16 h-16 mx-auto mb-4 text-text-muted opacity-30"></i>
        <h3 class="text-lg font-semibold text-text-primary mb-2">Belum Ada Berita</h3>
        <p class="text-text-muted text-sm">Belum ada berita yang diterbitkan saat ini.</p>
    </div>
@endif
