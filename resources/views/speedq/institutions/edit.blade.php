@props(['institution' => null, 'subdistricts' => []])

<x-app-layout>
    @section('title', 'Edit Instansi')
    @section('header', 'Edit Instansi')

    <div class="max-w-2xl mx-auto">
        <x-card>
            <form method="POST" action="{{ route('admin.institutions.update', $institution) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label for="name" value="Nama Instansi" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $institution->name)" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="description" value="Deskripsi" />
                    <textarea id="description" name="description" rows="3"
                        class="mt-1 block w-full rounded-lg border-border bg-surface-alt text-text-primary shadow-sm focus:border-primary focus:ring-primary sm:text-sm">{{ old('description', $institution->description) }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="address" value="Alamat" />
                    <textarea id="address" name="address" rows="2"
                        class="mt-1 block w-full rounded-lg border-border bg-surface-alt text-text-primary shadow-sm focus:border-primary focus:ring-primary sm:text-sm">{{ old('address', $institution->address) }}</textarea>
                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="subdistrict_id" value="Kecamatan" />
                    <select id="subdistrict_id" name="subdistrict_id"
                        class="mt-1 block w-full rounded-lg border-border bg-surface-alt text-text-primary shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                        <option value="">Pilih Kecamatan</option>
                        @foreach ($subdistricts as $sub)
                            <option value="{{ $sub->id }}" @selected(old('subdistrict_id', $institution->subdistrict_id) == $sub->id)>{{ $sub->name }}, {{ $sub->district->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('subdistrict_id')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="location" value="Lokasi (Google Maps Link)" />
                    <x-text-input id="location" name="location" type="text" class="mt-1 block w-full" :value="old('location', $institution->location)" placeholder="https://maps.app.goo.gl/..." />
                    <x-input-error :messages="$errors->get('location')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="photo" value="Foto Instansi" />
                    @if ($institution->photo)
                        <div class="mt-1 mb-2">
                            <img src="{{ asset('storage/' . $institution->photo) }}"
                                 alt="{{ $institution->name }}"
                                 class="w-24 h-24 rounded-lg object-cover">
                        </div>
                    @endif
                    <input type="file" id="photo" name="photo" accept="image/*"
                        class="mt-1 block w-full text-sm text-text-secondary file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                    <p class="text-xs text-text-muted mt-1">Kosongkan jika tidak ingin mengubah foto.</p>
                    <x-input-error :messages="$errors->get('photo')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="is_active" value="Status" />
                    <select id="is_active" name="is_active"
                        class="mt-1 block w-full rounded-lg border-border bg-surface-alt text-text-primary shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                        <option value="1" @selected(old('is_active', $institution->is_active))>Aktif</option>
                        <option value="0" @selected(!old('is_active', $institution->is_active))>Tidak Aktif</option>
                    </select>
                    <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <a href="{{ route('admin.institutions.index') }}"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-text-secondary bg-surface-alt border border-border rounded-lg hover:bg-surface">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-6 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary/90">
                        <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
