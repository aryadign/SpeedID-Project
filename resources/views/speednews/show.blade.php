<x-public-layout>
<x-slot:title>{{ $post->title }} - Speed ID</x-slot:title>

@if($post->is_emergency)
<div class="bg-red-600 text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center gap-3">
        <i data-lucide="alert-triangle" class="w-5 h-5 shrink-0"></i>
        <p class="text-sm font-medium">PENGUMUMAN DARURAT — {{ $post->title }}</p>
    </div>
</div>
@endif

<article class="pt-28 pb-16 bg-surface">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-4">
                @if($post->is_emergency)
                    <span class="px-3 py-1 text-xs font-medium bg-red-100 text-red-700 rounded-full">Darurat</span>
                @endif
                @if($post->category)
                    <span class="px-3 py-1 text-xs font-medium bg-primary/5 text-primary rounded-full">{{ $post->category->name }}</span>
                @endif
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-secondary leading-tight mb-4">{{ $post->title }}</h1>
            <div class="flex flex-wrap items-center gap-4 text-sm text-text-muted">
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

        <div class="bg-surface-alt rounded-xl shadow-card-md p-6 md:p-10 mb-10">
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
    </div>
</article>

@if($related->count() > 0)
<section class="bg-surface-alt border-t border-border py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-xl font-bold text-secondary mb-6">Berita Terkait</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($related as $item)
                <a href="{{ route('news.show', $item->slug) }}" class="group bg-surface rounded-xl shadow-card-sm hover:shadow-card-md transition-all overflow-hidden">
                    <div class="aspect-[16/10] bg-surface-alt overflow-hidden">
                        @if($item->thumbnail)
                            <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i data-lucide="newspaper" class="w-8 h-8 text-text-muted opacity-30"></i>
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <p class="text-xs text-text-muted mb-1">{{ $item->published_at ? $item->published_at->format('d M Y') : '' }}</p>
                        <h3 class="text-sm font-semibold text-text-primary group-hover:text-primary transition-colors line-clamp-2">{{ $item->title }}</h3>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

</x-public-layout>
