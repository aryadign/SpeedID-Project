<x-app-layout>
<x-slot:header>Kelola Berita</x-slot:header>
<x-slot:title>Kelola Berita - Speed ID</x-slot:title>

<div class="flex items-center justify-between gap-4 mb-6">
    <p class="text-sm text-text-muted">Total {{ $posts->total() }} berita</p>
    <a href="{{ route('admin.news.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white text-sm font-medium rounded-full hover:bg-primary/90 transition-all shadow-card-sm">
        <i data-lucide="plus" class="w-4 h-4"></i>
        Buat Berita
    </a>
</div>

<div class="bg-surface-alt rounded-xl shadow-card-sm overflow-hidden">
    @if($posts->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-border bg-surface">
                    <th class="text-left font-semibold text-text-primary px-5 py-4">Judul</th>
                    <th class="text-left font-semibold text-text-primary px-5 py-4">Kategori</th>
                    <th class="text-left font-semibold text-text-primary px-5 py-4">Penulis</th>
                    <th class="text-left font-semibold text-text-primary px-5 py-4">Status</th>
                    <th class="text-left font-semibold text-text-primary px-5 py-4">Tanggal Publikasi</th>
                    <th class="text-right font-semibold text-text-primary px-5 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($posts as $post)
                <tr class="border-b border-border last:border-b-0 hover:bg-surface/50 transition-colors">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-surface overflow-hidden shrink-0">
                                @if($post->thumbnail)
                                    <img src="{{ asset('storage/' . $post->thumbnail) }}" alt="" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i data-lucide="file-image" class="w-4 h-4 text-text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="font-medium text-text-primary truncate max-w-[280px]">{{ $post->title }}</p>
                                @if($post->is_emergency)
                                    <span class="text-xs text-red-600 font-medium">Darurat</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        <span class="text-text-secondary">{{ $post->category?->name ?? '-' }}</span>
                    </td>
                    <td class="px-5 py-4 text-text-secondary">{{ $post->user?->name ?? '-' }}</td>
                    <td class="px-5 py-4">
                        <x-status-badge type="{{ $post->status }}">{{ $post->status }}</x-status-badge>
                    </td>
                    <td class="px-5 py-4 text-text-secondary">
                        {{ $post->published_at ? $post->published_at->format('d M Y') : '-' }}
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.news.edit', $post->id) }}" class="p-2 hover:bg-primary/10 rounded-lg text-text-secondary hover:text-primary transition-colors" title="Edit">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.news.destroy', $post->id) }}" onsubmit="return confirm('Yakin ingin menghapus berita ini?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 hover:bg-red-50 rounded-lg text-text-secondary hover:text-red-600 transition-colors" title="Hapus">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-border">
        {{ $posts->links() }}
    </div>
    @else
    <div class="text-center py-16">
        <i data-lucide="newspaper" class="w-16 h-16 mx-auto mb-4 text-text-muted opacity-30"></i>
        <h3 class="text-lg font-semibold text-text-primary mb-2">Belum Ada Berita</h3>
        <p class="text-text-muted text-sm mb-6">Mulai dengan membuat berita baru.</p>
        <a href="{{ route('admin.news.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white text-sm font-medium rounded-full hover:bg-primary/90 transition-all">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Buat Berita
        </a>
    </div>
    @endif
</div>

</x-app-layout>
