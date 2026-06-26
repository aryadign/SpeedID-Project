<article class="max-w-4xl mx-auto">
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-4">
            @if($post->is_emergency)
                <span class="px-3 py-1 text-xs font-medium bg-red-100 text-red-700 rounded-full">Darurat</span>
            @endif
            @if($post->category)
                <span class="px-3 py-1 text-xs font-medium bg-primary/5 text-primary rounded-full">{{ $post->category->name }}</span>
            @endif
        </div>
        <h1 class="text-2xl md:text-3xl font-bold text-secondary leading-tight mb-4">{{ $post->title }}</h1>
        <div class="flex flex-wrap items-center gap-4 text-xs text-text-muted">
            <span class="flex items-center gap-1.5">
                <i data-lucide="calendar" class="w-4 h-4"></i>
                {{ $post->published_at ? $post->published_at->format('d M Y') : '' }}
            </span>
            @if($post->user)
                <span class="flex items-center gap-1.5">
                    <i data-lucide="user" class="w-4 h-4"></i>
                    {{ $post->user->name }}
                </span>
            @endif
            @if($post->subdistrict)
                <span class="flex items-center gap-1.5">
                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                    {{ $post->subdistrict->name ?? '' }}
                </span>
            @endif
        </div>
    </div>

    @if($post->thumbnail)
        <div class="aspect-[21/9] bg-surface rounded-xl overflow-hidden mb-8">
            <img src="{{ asset('storage/' . $post->thumbnail) }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
        </div>
    @endif

    <div class="bg-surface-alt rounded-xl shadow-card-md p-6 md:p-10 mb-10 border border-border">
        <div class="prose prose-lg max-w-none text-text-primary leading-relaxed">
            {!! nl2br(e($post->content)) !!}
        </div>
    </div>

    <div class="border-t border-border pt-6 flex items-center justify-between">
        <a href="{{ route('news.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-primary hover:text-primary/80 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Kembali ke Berita
        </a>
    </div>
</article>
