@if(Auth::check())
    <x-app-layout>
        @section('title', $post->title)
        @section('header', 'Detail Berita')

        @if($post->is_emergency)
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-red-500 shrink-0 mt-0.5"></i>
                <div>
                    <p class="text-sm font-semibold text-red-700">PENGUMUMAN DARURAT</p>
                    <p class="text-xs text-red-600 mt-0.5">Berita ini dikategorikan sebagai informasi penting dan mendesak.</p>
                </div>
            </div>
        @endif

        <div class="max-w-4xl mx-auto">
            @include('speednews.partials.detail')
        </div>

        @if($related->count() > 0)
            <div class="max-w-4xl mx-auto mt-12 border-t border-border pt-10">
                <h2 class="text-lg font-bold text-secondary mb-6">Berita Terkait</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    @foreach($related as $item)
                        <a href="{{ route('news.show', $item->slug) }}" class="group bg-surface-alt rounded-xl shadow-card-sm hover:shadow-card-md transition-all overflow-hidden border border-border">
                            <div class="aspect-[16/10] bg-surface overflow-hidden">
                                @if($item->thumbnail)
                                    <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-primary/5">
                                        <i data-lucide="newspaper" class="w-8 h-8 text-primary opacity-40"></i>
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
        @endif
    </x-app-layout>
@else
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
                @include('speednews.partials.detail')
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
                                        <div class="w-full h-full flex items-center justify-center bg-primary/5">
                                            <i data-lucide="newspaper" class="w-8 h-8 text-primary opacity-40"></i>
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
@endif
