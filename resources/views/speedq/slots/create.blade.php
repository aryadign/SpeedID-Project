@props(['service' => null])

<x-app-layout>
    @section('title', 'Tambah Sesi')
    @section('header', 'Tambah Sesi')

    <div class="max-w-2xl mx-auto">
        <x-card>
            <form method="POST" action="{{ route('admin.services.slots.store', $service) }}" class="space-y-6">
                @csrf

                <div>
                    <x-input-label for="institution" value="Instansi" />
                    <p class="mt-1 text-sm font-medium text-text-primary">{{ $service->institution->name }}</p>
                </div>

                <div>
                    <x-input-label for="service" value="Layanan" />
                    <p class="mt-1 text-sm font-medium text-text-primary">{{ $service->name }}</p>
                </div>

                <div>
                    <x-input-label for="date" value="Tanggal" />
                    <input type="date" id="date" name="date" value="{{ old('date') }}"
                        class="mt-1 block w-full rounded-lg border-border bg-surface-alt text-text-primary shadow-sm focus:border-primary focus:ring-primary sm:text-sm"
                        required>
                    <x-input-error :messages="$errors->get('date')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="start_time" value="Waktu Mulai" />
                    <input type="time" id="start_time" name="start_time" value="{{ old('start_time') }}"
                        class="mt-1 block w-full rounded-lg border-border bg-surface-alt text-text-primary shadow-sm focus:border-primary focus:ring-primary sm:text-sm"
                        required>
                    <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="end_time" value="Waktu Selesai" />
                    <input type="time" id="end_time" name="end_time" value="{{ old('end_time') }}"
                        class="mt-1 block w-full rounded-lg border-border bg-surface-alt text-text-primary shadow-sm focus:border-primary focus:ring-primary sm:text-sm"
                        required>
                    <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="quota" value="Kuota" />
                    <x-text-input id="quota" name="quota" type="number" class="mt-1 block w-full" :value="old('quota', 20)" min="1" required />
                    <x-input-error :messages="$errors->get('quota')" class="mt-2" />
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <a href="{{ route('admin.services.slots.index', $service) }}"
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
