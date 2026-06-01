@props(['institutions' => []])

<x-app-layout>
    @section('title', 'Booking Antrean')
    @section('header', 'Booking Antrean')

    <div class="max-w-2xl mx-auto">
        <x-card>
            <form method="POST" action="{{ route('queue.book') }}" class="space-y-6">
                @csrf

                <div>
                    <x-input-label for="institution_id" value="Instansi" />
                    <select id="institution_id" name="institution_id"
                        class="mt-1 block w-full rounded-lg border-border bg-surface-alt text-text-primary shadow-sm focus:border-primary focus:ring-primary sm:text-sm"
                        x-data
                        @change="$dispatch('institution-changed', { id: $event.target.value })">
                        <option value="">Pilih Instansi</option>
                        @foreach ($institutions as $institution)
                            <option value="{{ $institution->id }}" @selected(old('institution_id') == $institution->id)>
                                {{ $institution->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('institution_id')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="service_id" value="Layanan" />
                    <select id="service_id" name="service_id"
                        class="mt-1 block w-full rounded-lg border-border bg-surface-alt text-text-primary shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                        <option value="">Pilih Layanan</option>
                    </select>
                    <x-input-error :messages="$errors->get('service_id')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="date" value="Tanggal" />
                    <input type="date" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}"
                        class="mt-1 block w-full rounded-lg border-border bg-surface-alt text-text-primary shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                    <x-input-error :messages="$errors->get('date')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="slot_id" value="Sesi Waktu" />
                    <select id="slot_id" name="slot_id"
                        class="mt-1 block w-full rounded-lg border-border bg-surface-alt text-text-primary shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                        <option value="">Pilih Sesi</option>
                    </select>
                    <x-input-error :messages="$errors->get('slot_id')" class="mt-2" />
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <a href="{{ route('queue.tickets') }}"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-text-secondary bg-surface-alt border border-border rounded-lg hover:bg-surface">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-6 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary/90">
                        <i data-lucide="ticket" class="w-4 h-4 mr-2"></i>
                        Ambil Antrean
                    </button>
                </div>
            </form>
        </x-card>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            const institutionSelect = document.getElementById('institution_id');
            const serviceSelect = document.getElementById('service_id');
            const slotSelect = document.getElementById('slot_id');
            const dateInput = document.getElementById('date');

            institutionSelect.addEventListener('change', function() {
                const id = this.value;
                serviceSelect.innerHTML = '<option value="">Pilih Layanan</option>';
                slotSelect.innerHTML = '<option value="">Pilih Sesi</option>';

                if (!id) return;

                fetch(`/api/institutions/${id}/services`)
                    .then(res => res.json())
                    .then(data => {
                        data.forEach(service => {
                            const opt = document.createElement('option');
                            opt.value = service.id;
                            opt.textContent = service.name;
                            serviceSelect.appendChild(opt);
                        });
                    });
            });

            serviceSelect.addEventListener('change', loadSlots);
            dateInput.addEventListener('change', loadSlots);

            function loadSlots() {
                const serviceId = serviceSelect.value;
                const date = dateInput.value;
                slotSelect.innerHTML = '<option value="">Pilih Sesi</option>';

                if (!serviceId || !date) return;

                fetch(`/api/services/${serviceId}/slots?date=${date}`)
                    .then(res => res.json())
                    .then(data => {
                        data.forEach(slot => {
                            const opt = document.createElement('option');
                            opt.value = slot.id;
                            opt.textContent = `${slot.start_time} - ${slot.end_time} (${slot.available} tersedia)`;
                            slotSelect.appendChild(opt);
                        });
                    });
            }
        });
    </script>
    @endpush
</x-app-layout>
