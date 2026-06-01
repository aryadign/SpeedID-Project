<x-app-layout>
<x-slot:header>Kategori Berita</x-slot:header>
<x-slot:title>Kategori Berita - Speed ID</x-slot:title>

<div x-data="{ showModal: false, editing: null }" class="max-w-4xl mx-auto">

    <div class="flex items-center justify-between gap-4 mb-6">
        <p class="text-sm text-text-muted">Total {{ $categories->total() }} kategori</p>
        <button @click="showModal = true; editing = null" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white text-sm font-medium rounded-full hover:bg-primary/90 transition-all shadow-card-sm">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Tambah Kategori
        </button>
    </div>

    <div class="bg-surface-alt rounded-xl shadow-card-sm overflow-hidden">
        @if($categories->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-border bg-surface">
                        <th class="text-left font-semibold text-text-primary px-5 py-4">Nama</th>
                        <th class="text-left font-semibold text-text-primary px-5 py-4">Slug</th>
                        <th class="text-center font-semibold text-text-primary px-5 py-4">Jumlah Berita</th>
                        <th class="text-center font-semibold text-text-primary px-5 py-4">Status</th>
                        <th class="text-right font-semibold text-text-primary px-5 py-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                    <tr class="border-b border-border last:border-b-0 hover:bg-surface/50 transition-colors">
                        <td class="px-5 py-4 font-medium text-text-primary">{{ $category->name }}</td>
                        <td class="px-5 py-4 text-text-secondary">{{ $category->slug }}</td>
                        <td class="px-5 py-4 text-center text-text-secondary">{{ $category->posts_count }}</td>
                        <td class="px-5 py-4 text-center">
                            <x-status-badge type="{{ $category->is_active ? 'aktif' : 'tidak_aktif' }}">
                                {{ $category->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </x-status-badge>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button @click="showModal = true; editing = {{ $category->id }}"
                                    class="p-2 hover:bg-primary/10 rounded-lg text-text-secondary hover:text-primary transition-colors" title="Edit">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.news.categories.destroy', $category->id) }}"
                                    onsubmit="return confirm('Yakin ingin menghapus kategori ini?')" class="inline">
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
            {{ $categories->links() }}
        </div>
        @else
        <div class="text-center py-16">
            <i data-lucide="folder" class="w-16 h-16 mx-auto mb-4 text-text-muted opacity-30"></i>
            <h3 class="text-lg font-semibold text-text-primary mb-2">Belum Ada Kategori</h3>
            <p class="text-text-muted text-sm mb-6">Tambahkan kategori berita pertama.</p>
            <button @click="showModal = true; editing = null" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white text-sm font-medium rounded-full hover:bg-primary/90 transition-all">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Tambah Kategori
            </button>
        </div>
        @endif
    </div>

    {{-- Create/Edit Modal --}}
    <div x-show="showModal"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm"
        @click.away="showModal = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100">
        <div class="bg-surface-alt rounded-xl shadow-card-lg p-6 w-full max-w-md" @click.stop>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-text-primary" x-text="editing ? 'Edit Kategori' : 'Tambah Kategori'"></h3>
                <button @click="showModal = false" class="p-1.5 hover:bg-surface rounded-lg">
                    <i data-lucide="x" class="w-5 h-5 text-text-muted"></i>
                </button>
            </div>

            {{-- Create Form --}}
            <form x-show="!editing" method="POST" action="{{ route('admin.news.categories.store') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <x-input-label for="name" value="Nama Kategori" />
                        <input id="name" type="text" name="name" required
                            class="mt-1 block w-full rounded-lg border-border bg-surface text-text-primary px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                            placeholder="Contoh: Pendidikan">
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-border">
                    <button type="button" @click="showModal = false" class="px-4 py-2 text-sm font-medium text-text-secondary hover:text-text-primary transition-colors">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-primary text-white text-sm font-medium rounded-full hover:bg-primary/90 transition-all">Simpan</button>
                </div>
            </form>

            {{-- Edit Form --}}
            <form x-show="editing" method="POST" :action="`{{ route('admin.news.categories.update', '') }}/${editing}`">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <x-input-label for="edit_name" value="Nama Kategori" />
                        <input id="edit_name" type="text" name="name" required
                            class="mt-1 block w-full rounded-lg border-border bg-surface text-text-primary px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                            placeholder="Contoh: Pendidikan">
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>
                    <div class="flex items-center gap-3">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1"
                                class="w-4 h-4 rounded border-border text-primary focus:ring-primary">
                            <span class="text-sm font-medium text-text-primary">Aktif</span>
                        </label>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-border">
                    <button type="button" @click="showModal = false" class="px-4 py-2 text-sm font-medium text-text-secondary hover:text-text-primary transition-colors">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-primary text-white text-sm font-medium rounded-full hover:bg-primary/90 transition-all">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>

</x-app-layout>
