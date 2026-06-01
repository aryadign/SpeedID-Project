@props(['service' => null])

<x-app-layout>
    @section('title', 'Edit Layanan')
    @section('header', 'Edit Layanan')

    <div class="max-w-2xl mx-auto">
        <x-card>
            <form method="POST" action="{{ route('admin.services.update', $service) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label for="name" value="Nama Layanan" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $service->name)" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="description" value="Deskripsi" />
                    <textarea id="description" name="description" rows="3"
                        class="mt-1 block w-full rounded-lg border-border bg-surface-alt text-text-primary shadow-sm focus:border-primary focus:ring-primary sm:text-sm">{{ old('description', $service->description) }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="duration" value="Durasi (menit)" />
                    <x-text-input id="duration" name="duration" type="number" class="mt-1 block w-full" :value="old('duration', $service->duration)" min="1" required />
                    <x-input-error :messages="$errors->get('duration')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="daily_quota" value="Kuota Harian" />
                    <x-text-input id="daily_quota" name="daily_quota" type="number" class="mt-1 block w-full" :value="old('daily_quota', $service->daily_quota)" min="1" required />
                    <x-input-error :messages="$errors->get('daily_quota')" class="mt-2" />
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <a href="{{ route('admin.institutions.services.index', $service->institution) }}"
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
