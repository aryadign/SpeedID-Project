<x-app-layout>
<x-slot:header>Kontak Darurat</x-slot:header>
<x-slot:title>Kontak Darurat - Speed ID</x-slot:title>

<div class="flex items-center justify-between gap-4 mb-6">
    <div>
        <p class="text-sm text-text-muted">Kelola daftar kontak darurat yang ditampilkan di halaman SOS.</p>
    </div>
    <button @click="$dispatch('open-modal', 'create-contact')" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white text-sm font-medium rounded-full hover:bg-primary/90 transition-all shadow-card-sm">
        <i data-lucide="plus" class="w-4 h-4"></i>
        Tambah Kontak
    </button>
</div>

<x-card class="overflow-hidden p-0">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-surface border-b border-border">
                    <th class="text-left px-6 py-3.5 font-semibold text-text-primary text-xs uppercase tracking-wider">Nama</th>
                    <th class="text-left px-6 py-3.5 font-semibold text-text-primary text-xs uppercase tracking-wider">Nomor Telepon</th>
                    <th class="text-left px-6 py-3.5 font-semibold text-text-primary text-xs uppercase tracking-wider">Tipe</th>
                    <th class="text-left px-6 py-3.5 font-semibold text-text-primary text-xs uppercase tracking-wider">Status</th>
                    <th class="text-right px-6 py-3.5 font-semibold text-text-primary text-xs uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse($contacts as $contact)
                    <tr class="hover:bg-surface/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-red-50 rounded-lg flex items-center justify-center">
                                    <i data-lucide="phone" class="w-4 h-4 text-red-600"></i>
                                </div>
                                <span class="font-medium text-text-primary">{{ $contact->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <a href="tel:{{ $contact->phone }}" class="text-primary font-medium hover:underline">{{ $contact->phone }}</a>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm capitalize text-text-secondary">{{ $contact->type ?? 'Umum' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <x-status-badge :type="$contact->is_active ? 'aktif' : 'tidak_aktif'" size="sm">
                                {{ $contact->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </x-status-badge>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button @click="$dispatch('open-modal', 'edit-contact-{{ $contact->id }}')" class="p-2 text-text-secondary hover:text-primary hover:bg-primary/5 rounded-lg transition-colors">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.contacts.destroy', $contact->id) }}" onsubmit="return confirm('Hapus kontak ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-text-secondary hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <i data-lucide="phone-off" class="w-12 h-12 mx-auto mb-3 text-text-muted opacity-40"></i>
                            <p class="text-text-muted text-sm">Belum ada kontak darurat.</p>
                            <p class="text-xs text-text-muted mt-1">Tambahkan kontak darurat pertama Anda.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-card>

@if($contacts->hasPages())
    <div class="mt-6">
        {{ $contacts->links() }}
    </div>
@endif

<x-modal name="create-contact" :show="false">
    <form method="POST" action="{{ route('admin.contacts.store') }}" class="p-6 space-y-6">
        @csrf
        <h2 class="text-lg font-semibold text-text-primary">Tambah Kontak Darurat</h2>

        <div>
            <x-input-label for="create-name" value="Nama" />
            <input id="create-name" type="text" name="name" required maxlength="255" placeholder="Contoh: Call Center 112" class="mt-1 block w-full rounded-lg border-border bg-surface text-text-primary text-sm px-4 py-2.5 focus:ring-primary focus:border-primary placeholder:text-text-muted" />
        </div>

        <div>
            <x-input-label for="create-phone" value="Nomor Telepon" />
            <input id="create-phone" type="text" name="phone" required maxlength="20" placeholder="Contoh: 112" class="mt-1 block w-full rounded-lg border-border bg-surface text-text-primary text-sm px-4 py-2.5 focus:ring-primary focus:border-primary placeholder:text-text-muted" />
        </div>

        <div>
            <x-input-label for="create-type" value="Tipe" />
            <select id="create-type" name="type" class="mt-1 block w-full rounded-lg border-border bg-surface text-text-primary text-sm px-4 py-2.5 focus:ring-primary focus:border-primary">
                <option value="umum">Umum</option>
                <option value="ambulans">Ambulans</option>
                <option value="polisi">Polisi</option>
                <option value="pemadam">Pemadam Kebakaran</option>
                <option value="bencana">Bencana Alam</option>
            </select>
        </div>

        <div class="flex items-center gap-3">
            <input type="checkbox" id="create-is_active" name="is_active" value="1" checked class="rounded border-border text-primary focus:ring-primary w-4 h-4" />
            <x-input-label for="create-is_active" value="Aktif" />
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-border">
            <button type="button" @click="$dispatch('close-modal', 'create-contact')" class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-text-secondary hover:text-text-primary transition-colors">
                Batal
            </button>
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white text-sm font-medium rounded-full hover:bg-primary/90 transition-all">
                <i data-lucide="save" class="w-4 h-4"></i>
                Simpan
            </button>
        </div>
    </form>
</x-modal>

@foreach($contacts as $contact)
    <x-modal name="edit-contact-{{ $contact->id }}" :show="false">
        <form method="POST" action="{{ route('admin.contacts.update', $contact->id) }}" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            <h2 class="text-lg font-semibold text-text-primary">Edit Kontak Darurat</h2>

            <div>
                <x-input-label for="edit-name-{{ $contact->id }}" value="Nama" />
                <input id="edit-name-{{ $contact->id }}" type="text" name="name" value="{{ $contact->name }}" required maxlength="255" class="mt-1 block w-full rounded-lg border-border bg-surface text-text-primary text-sm px-4 py-2.5 focus:ring-primary focus:border-primary" />
            </div>

            <div>
                <x-input-label for="edit-phone-{{ $contact->id }}" value="Nomor Telepon" />
                <input id="edit-phone-{{ $contact->id }}" type="text" name="phone" value="{{ $contact->phone }}" required maxlength="20" class="mt-1 block w-full rounded-lg border-border bg-surface text-text-primary text-sm px-4 py-2.5 focus:ring-primary focus:border-primary" />
            </div>

            <div>
                <x-input-label for="edit-type-{{ $contact->id }}" value="Tipe" />
                <select id="edit-type-{{ $contact->id }}" name="type" class="mt-1 block w-full rounded-lg border-border bg-surface text-text-primary text-sm px-4 py-2.5 focus:ring-primary focus:border-primary">
                    <option value="umum" {{ $contact->type == 'umum' ? 'selected' : '' }}>Umum</option>
                    <option value="ambulans" {{ $contact->type == 'ambulans' ? 'selected' : '' }}>Ambulans</option>
                    <option value="polisi" {{ $contact->type == 'polisi' ? 'selected' : '' }}>Polisi</option>
                    <option value="pemadam" {{ $contact->type == 'pemadam' ? 'selected' : '' }}>Pemadam Kebakaran</option>
                    <option value="bencana" {{ $contact->type == 'bencana' ? 'selected' : '' }}>Bencana Alam</option>
                </select>
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" id="edit-is_active-{{ $contact->id }}" name="is_active" value="1" {{ $contact->is_active ? 'checked' : '' }} class="rounded border-border text-primary focus:ring-primary w-4 h-4" />
                <x-input-label for="edit-is_active-{{ $contact->id }}" value="Aktif" />
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-border">
                <button type="button" @click="$dispatch('close-modal', 'edit-contact-{{ $contact->id }}')" class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-text-secondary hover:text-text-primary transition-colors">
                    Batal
                </button>
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white text-sm font-medium rounded-full hover:bg-primary/90 transition-all">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Simpan
                </button>
            </div>
        </form>
    </x-modal>
@endforeach
</x-app-layout>
