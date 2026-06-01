@props(['subdistricts' => []])

<x-app-layout>
    @section('title', 'Tambah Instansi')
    @section('header', 'Tambah Instansi')

    <div class="max-w-2xl mx-auto">
        <x-card>
            <form method="POST" action="{{ route('admin.institutions.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <x-input-label for="name" value="Nama Instansi" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="description" value="Deskripsi" />
                    <textarea id="description" name="description" rows="3"
                        class="mt-1 block w-full rounded-lg border-border bg-surface-alt text-text-primary shadow-sm focus:border-primary focus:ring-primary sm:text-sm">{{ old('description') }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="address" value="Alamat" />
                    <textarea id="address" name="address" rows="2"
                        class="mt-1 block w-full rounded-lg border-border bg-surface-alt text-text-primary shadow-sm focus:border-primary focus:ring-primary sm:text-sm">{{ old('address') }}</textarea>
                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="subdistrict" value="Kecamatan" />
                    <select id="subdistrict" name="subdistrict"
                        class="mt-1 block w-full rounded-lg border-border bg-surface-alt text-text-primary shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                        <option value="">Pilih Kecamatan</option>
                        @foreach ($subdistricts as $sub)
                            <option value="{{ $sub }}" @selected(old('subdistrict') == $sub)>{{ $sub }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('subdistrict')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="location" value="Lokasi (Google Maps Link)" />
                    <x-text-input id="location" name="location" type="text" class="mt-1 block w-full" :value="old('location')" placeholder="https://maps.app.goo.gl/..." />
                    <x-input-error :messages="$errors->get('location')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="photo" value="Foto Instansi" />
                    <input type="file" id="photo" name="photo" accept="image/*"
                        class="mt-1 block w-full text-sm text-text-secondary file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                    <x-input-error :messages="$errors->get('photo')" class="mt-2" />
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <a href="{{ route('admin.institutions.index') }}"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-text-secondary bg-surface-alt border border-border rounded-lg hover:bg-surface">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-6 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary/90">
                        <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                        Simpan
                    </button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
