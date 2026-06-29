@props(['institutions' => []])

<x-app-layout>
    @section('title', 'Booking Antrean')
    @section('header', 'Booking Antrean')

    <div class="max-w-3xl mx-auto" x-data="bookingForm()" x-init="init()">

        {{-- Error Alert --}}
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3">
                <i data-lucide="alert-circle" class="w-5 h-5 text-red-500 shrink-0 mt-0.5"></i>
                <div>
                    <p class="text-sm font-semibold text-red-700 mb-1">Terjadi kesalahan:</p>
                    <ul class="text-sm text-red-600 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('queue.book') }}" @submit="submitting = true">
            @csrf

            {{-- Step 1: Pilih Instansi --}}
            <div class="mb-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-7 h-7 rounded-full bg-primary text-white flex items-center justify-center text-xs font-bold shrink-0">1</div>
                    <h2 class="text-base font-semibold text-text-primary">Pilih Instansi</h2>
                </div>

                <div class="max-h-[390px] sm:max-h-[200px] overflow-y-auto pr-1.5 py-1 -my-1 -mr-1.5 custom-scrollbar">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach ($institutions as $institution)
                            <label
                                class="relative flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all duration-200"
                                :class="selectedInstitution == {{ $institution->id }}
                                    ? 'border-primary bg-primary/5 shadow-card-sm'
                                    : 'border-border bg-surface-alt hover:border-primary/40 hover:bg-primary/[0.02]'"
                            >
                                <input type="radio" name="institution_id" value="{{ $institution->id }}"
                                       class="sr-only"
                                       x-model="selectedInstitution"
                                       @change="onInstitutionChange({{ $institution->id }})"
                                       {{ old('institution_id') == $institution->id ? 'checked' : '' }}>

                                {{-- Institution Photo --}}
                                @if($institution->photo)
                                    <img src="{{ asset('storage/' . $institution->photo) }}"
                                         alt="{{ $institution->name }}"
                                         class="w-12 h-12 rounded-xl object-cover border border-border shrink-0">
                                @else
                                    <div class="w-12 h-12 rounded-xl bg-primary/10 border border-primary/10 flex items-center justify-center shrink-0">
                                        <i data-lucide="building-2" class="w-6 h-6 text-primary"></i>
                                    </div>
                                @endif

                                <div class="min-w-0 flex-1">
                                    <p class="font-semibold text-sm text-text-primary truncate">{{ $institution->name }}</p>
                                    <p class="text-xs text-text-muted truncate mt-0.5">{{ $institution->description }}</p>
                                    <p class="text-[10px] text-text-muted mt-1 flex items-center gap-1">
                                        <i data-lucide="map-pin" class="w-3 h-3"></i>
                                        {{ $institution->address }}
                                    </p>
                                </div>

                                {{-- Selected checkmark --}}
                                <div class="absolute top-3 right-3 w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all"
                                     :class="selectedInstitution == {{ $institution->id }}
                                        ? 'border-primary bg-primary'
                                        : 'border-border bg-transparent'">
                                    <i data-lucide="check" class="w-3 h-3 text-white"
                                       x-show="selectedInstitution == {{ $institution->id }}"></i>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
                <x-input-error :messages="$errors->get('institution_id')" class="mt-2" />
            </div>

            {{-- Step 2: Pilih Layanan + Tanggal --}}
            <div class="mb-6" x-show="selectedInstitution" x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="bg-surface-alt rounded-xl border border-border overflow-hidden">
                    <div class="px-5 pt-5 pb-4 border-b border-border flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full bg-primary text-white flex items-center justify-center text-xs font-bold shrink-0">2</div>
                        <h2 class="text-base font-semibold text-text-primary">Pilih Layanan & Tanggal</h2>
                    </div>
                    <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-5">
                        {{-- Layanan --}}
                        <div>
                            <x-input-label for="service_id" value="Layanan" />
                            <div class="relative mt-1">
                                <select id="service_id" name="service_id"
                                    class="block w-full rounded-lg border border-border bg-surface text-text-primary px-3 py-2.5 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary appearance-none pr-10"
                                    @change="onServiceChange()">
                                    <option value="">Pilih Layanan</option>
                                </select>
                                <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-text-muted pointer-events-none"></i>
                            </div>
                            <x-input-error :messages="$errors->get('service_id')" class="mt-1" />
                        </div>

                        {{-- Tanggal --}}
                        <div>
                            <x-input-label for="date" value="Tanggal" />
                            <div class="relative mt-1">
                                <input type="date" id="date" name="date"
                                    value="{{ old('date', date('Y-m-d')) }}"
                                    min="{{ date('Y-m-d') }}"
                                    class="block w-full rounded-lg border border-border bg-surface text-text-primary px-3 py-2.5 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary"
                                    @change="loadSlots()">
                            </div>
                            <x-input-error :messages="$errors->get('date')" class="mt-1" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- Step 3: Pilih Sesi --}}
            <div class="mb-6" x-show="slots.length > 0" x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="bg-surface-alt rounded-xl border border-border overflow-hidden">
                    <div class="px-5 pt-5 pb-4 border-b border-border flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full bg-primary text-white flex items-center justify-center text-xs font-bold shrink-0">3</div>
                        <h2 class="text-base font-semibold text-text-primary">Pilih Sesi Waktu</h2>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <template x-for="slot in slots" :key="slot.id">
                                <label
                                    class="relative flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all duration-200"
                                    :class="selectedSlot == slot.id
                                        ? 'border-primary bg-primary/5 shadow-card-sm'
                                        : 'border-border bg-surface hover:border-primary/40'">
                                    <input type="radio" name="service_slot_id" :value="slot.id"
                                           class="sr-only" x-model="selectedSlot">

                                    <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-primary">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12 6 12 12 16 14"></polyline>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-text-primary" x-text="slot.start_time + ' — ' + slot.end_time"></p>
                                    </div>
                                    <div class="absolute top-3 right-3 w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all"
                                         :class="selectedSlot == slot.id ? 'border-primary bg-primary' : 'border-border'">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3 text-white" x-show="selectedSlot == slot.id">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                    </div>
                                </label>
                            </template>
                        </div>
                        <x-input-error :messages="$errors->get('service_slot_id')" class="mt-2" />
                    </div>
                </div>
            </div>

            {{-- No slots notice --}}
            <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-xl flex items-center gap-3 text-sm text-yellow-700"
                 x-show="noSlotsAvailable" x-transition>
                <i data-lucide="info" class="w-5 h-5 shrink-0"></i>
                Tidak ada sesi tersedia untuk layanan dan tanggal yang dipilih. Coba tanggal lain.
            </div>

            {{-- Loading indicator --}}
            <div class="mb-6 flex items-center gap-2 text-sm text-text-muted" x-show="loadingSlots" x-transition>
                <svg class="animate-spin w-4 h-4 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                </svg>
                Memuat sesi waktu...
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-3 pt-2">
                <a href="{{ route('queue.tickets') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-text-secondary bg-surface-alt border border-border rounded-lg hover:bg-surface transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Antrean Saya
                </a>
                <button type="submit"
                    :disabled="!selectedSlot || submitting"
                    class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white bg-primary rounded-lg hover:bg-primary/90 transition-all shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                    <template x-if="!submitting">
                        <i data-lucide="ticket" class="w-4 h-4"></i>
                    </template>
                    <template x-if="submitting">
                        <svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                        </svg>
                    </template>
                    <span x-text="submitting ? 'Memproses...' : 'Ambil Antrean'"></span>
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function bookingForm() {
            return {
                selectedInstitution: {{ old('institution_id', request('institution_id', 'null')) }},
                selectedSlot: {{ old('service_slot_id', 'null') }},
                slots: [],
                loadingSlots: false,
                noSlotsAvailable: false,
                submitting: false,

                init() {
                    if (this.selectedInstitution) {
                        this.loadServices(this.selectedInstitution);
                    }
                },

                onInstitutionChange(id) {
                    this.selectedInstitution = id;
                    this.slots = [];
                    this.noSlotsAvailable = false;
                    document.getElementById('service_id').innerHTML = '<option value="">Pilih Layanan</option>';
                    this.loadServices(id);
                },

                loadServices(id) {
                    fetch(`/api/institutions/${id}/services`)
                        .then(res => {
                            if (!res.ok) throw new Error('Gagal memuat layanan');
                            return res.json();
                        })
                        .then(data => {
                            const sel = document.getElementById('service_id');
                            sel.innerHTML = '<option value="">Pilih Layanan</option>';
                            if (!Array.isArray(data)) return;
                            data.forEach(svc => {
                                const opt = document.createElement('option');
                                opt.value = svc.id;
                                opt.textContent = svc.name;
                                sel.appendChild(opt);
                            });
                        })
                        .catch(err => console.error('loadServices error:', err));
                },

                onServiceChange() {
                    this.slots = [];
                    this.noSlotsAvailable = false;
                    this.selectedSlot = null;
                    this.loadSlots();
                },

                loadSlots() {
                    const serviceId = document.getElementById('service_id').value;
                    const date = document.getElementById('date').value;
                    this.slots = [];
                    this.noSlotsAvailable = false;
                    this.selectedSlot = null;

                    if (!serviceId || !date) return;

                    this.loadingSlots = true;
                    fetch(`/api/services/${serviceId}/slots?date=${date}`)
                        .then(res => res.json())
                        .then(data => {
                            this.loadingSlots = false;
                            this.slots = data;
                            this.noSlotsAvailable = data.length === 0;
                            @if(old('service_slot_id'))
                            const old = {{ old('service_slot_id') }};
                            if (data.find(s => s.id == old)) this.selectedSlot = old;
                            @endif
                        })
                        .catch(() => { this.loadingSlots = false; });
                },
            };
        }
    </script>
    @endpush
</x-app-layout>
