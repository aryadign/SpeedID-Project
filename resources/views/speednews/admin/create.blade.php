<x-app-layout>
<x-slot:header>Buat Berita</x-slot:header>
<x-slot:title>Buat Berita - Speed ID</x-slot:title>

<div class="max-w-3xl mx-auto">
    <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data" class="bg-surface-alt rounded-xl shadow-card-sm p-6 md:p-8">
        @csrf

        <div class="space-y-6">
            <div>
                <x-input-label for="title" value="Judul Berita" />
                <input id="title" type="text" name="title" value="{{ old('title') }}" required
                    class="mt-1 block w-full rounded-lg border-border bg-surface text-text-primary px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                    placeholder="Masukkan judul berita">
                <x-input-error :messages="$errors->get('title')" class="mt-1" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <x-input-label for="category_id" value="Kategori" />
                    <select id="category_id" name="category_id" required
                        class="mt-1 block w-full rounded-lg border-border bg-surface text-text-primary px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('category_id')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="subdistrict_id" value="Kecamatan (Opsional)" />
                    <select id="subdistrict_id" name="subdistrict_id"
                        class="mt-1 block w-full rounded-lg border-border bg-surface text-text-primary px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                        <option value="">Pilih Kecamatan</option>
                        @foreach($subdistricts as $subdistrict)
                            <option value="{{ $subdistrict->id }}" {{ old('subdistrict_id') == $subdistrict->id ? 'selected' : '' }}>
                                {{ $subdistrict->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('subdistrict_id')" class="mt-1" />
                </div>
            </div>

            <div>
                <x-input-label for="content" value="Konten Berita" />
                <textarea id="content" name="content" rows="12" required
                    class="mt-1 block w-full rounded-lg border-border bg-surface text-text-primary px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all resize-y"
                    placeholder="Tulis konten berita di sini...">{{ old('content') }}</textarea>
                <x-input-error :messages="$errors->get('content')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="thumbnail" value="Thumbnail (Opsional)" />
                <input id="thumbnail" type="file" name="thumbnail" accept="image/jpg,image/jpeg,image/png,image/webp"
                    class="mt-1 block w-full text-sm text-text-secondary file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-all cursor-pointer">
                <p class="text-xs text-text-muted mt-1">Format: JPG, PNG, WebP. Maks 2MB.</p>
                <x-input-error :messages="$errors->get('thumbnail')" class="mt-1" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <x-input-label for="status" value="Status" />
                    <select id="status" name="status"
                        class="mt-1 block w-full rounded-lg border-border bg-surface text-text-primary px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Publikasikan</option>
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-1" />
                </div>

                <div class="flex items-end pb-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_emergency" value="1" {{ old('is_emergency') ? 'checked' : '' }}
                            class="w-4 h-4 rounded border-border text-primary focus:ring-primary">
                        <span class="text-sm font-medium text-text-primary">Tandai sebagai Darurat</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-border">
            <a href="{{ route('admin.news.index') }}" class="px-5 py-2.5 text-sm font-medium text-text-secondary hover:text-text-primary transition-colors">Batal</a>
            <button type="submit" class="px-6 py-2.5 bg-primary text-white text-sm font-medium rounded-full hover:bg-primary/90 transition-all shadow-card-sm">
                Simpan Berita
            </button>
        </div>
    </form>
</div>

</x-app-layout>
