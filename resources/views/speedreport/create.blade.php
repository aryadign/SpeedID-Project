<x-app-layout>
<x-slot:header>Buat Laporan Baru</x-slot:header>
<x-slot:title>Buat Laporan - Speed ID</x-slot:title>

<div class="max-w-3xl mx-auto">
    <x-card>
        <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <x-input-label for="category_id" value="Kategori Laporan" />
                <select id="category_id" name="category_id" required class="mt-1 block w-full rounded-lg border-border bg-surface-alt text-text-primary text-sm px-4 py-2.5 focus:ring-primary focus:border-primary">
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('category_id')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="title" value="Judul Laporan" />
                <input id="title" type="text" name="title" value="{{ old('title') }}" required maxlength="255" placeholder="Contoh: Jalan berlubang di Jl. Sudirman" class="mt-1 block w-full rounded-lg border-border bg-surface-alt text-text-primary text-sm px-4 py-2.5 focus:ring-primary focus:border-primary placeholder:text-text-muted" />
                <x-input-error :messages="$errors->get('title')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="description" value="Deskripsi Lengkap" />
                <textarea id="description" name="description" rows="5" required placeholder="Jelaskan detail laporan Anda secara lengkap..." class="mt-1 block w-full rounded-lg border-border bg-surface-alt text-text-primary text-sm px-4 py-2.5 focus:ring-primary focus:border-primary placeholder:text-text-muted">{{ old('description') }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="media" value="Lampiran (Foto/Video)" />
                <input id="media" type="file" name="media[]" multiple accept="image/*,video/*" class="mt-1 block w-full text-sm text-text-secondary file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-medium file:bg-primary/5 file:text-primary hover:file:bg-primary/10 file:cursor-pointer cursor-pointer" />
                <p class="text-xs text-text-muted mt-1.5">Format: JPG, PNG, MP4. Maksimal 5 file (masing-masing 10MB).</p>
                <x-input-error :messages="$errors->get('media.*')" class="mt-1" />
            </div>

            <div x-data="{ showMap: false }">
                <x-input-label value="Lokasi Kejadian" />
                <div class="mt-1 flex items-center gap-3">
                    <input type="text" name="location" id="location" value="{{ old('location') }}" placeholder="Masukkan alamat atau titik lokasi" readonly class="flex-1 rounded-lg border-border bg-surface-alt text-text-primary text-sm px-4 py-2.5 focus:ring-primary focus:border-primary placeholder:text-text-muted cursor-pointer" @click="showMap = !showMap" />
                    <button type="button" @click="showMap = !showMap" class="inline-flex items-center gap-2 px-4 py-2.5 bg-surface border border-border text-text-primary text-sm font-medium rounded-lg hover:bg-surface-alt transition-colors">
                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                        Pilih
                    </button>
                </div>
                <div x-show="showMap" x-cloak class="mt-3 rounded-lg overflow-hidden border border-border">
                    <div id="map-picker" class="h-64 bg-surface flex items-center justify-center">
                        <p class="text-sm text-text-muted">Peta interaktif akan muncul di sini</p>
                    </div>
                </div>
                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}" />
                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}" />
                <x-input-error :messages="$errors->get('location')" class="mt-1" />
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" id="anonymous" name="anonymous" value="1" {{ old('anonymous') ? 'checked' : '' }} class="rounded border-border text-primary focus:ring-primary w-4 h-4" />
                <x-input-label for="anonymous" value="Laporkan secara anonim (nama tidak akan ditampilkan)" class="text-sm" />
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-border">
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white text-sm font-medium rounded-full hover:bg-primary/90 transition-all shadow-card-sm">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    Kirim Laporan
                </button>
                <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-text-secondary hover:text-text-primary transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </x-card>
</div>
</x-app-layout>
