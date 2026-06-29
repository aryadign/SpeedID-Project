@props(['institutions' => []])

<x-app-layout>
    @section('title', 'Kelola Instansi')
    @section('header', 'Kelola Instansi')

    <div class="flex justify-end mb-6">
        <a href="{{ route('admin.institutions.create') }}"
            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary/90">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
            Tambah Instansi
        </a>
    </div>

    <x-card>
        @if ($institutions->isEmpty())
            <div class="text-center py-12">
                <i data-lucide="building-2" class="w-12 h-12 text-text-muted mx-auto mb-3"></i>
                <p class="text-text-secondary">Belum ada instansi.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-border">
                            <th class="text-left py-3 px-4 font-medium text-text-secondary">Nama</th>
                            <th class="text-left py-3 px-4 font-medium text-text-secondary">Kecamatan</th>
                            <th class="text-left py-3 px-4 font-medium text-text-secondary">Status</th>
                            <th class="text-right py-3 px-4 font-medium text-text-secondary">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @foreach ($institutions as $institution)
                            <tr class="hover:bg-surface">
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-3">
                                        @if ($institution->photo)
                                            <img src="{{ asset('storage/' . $institution->photo) }}"
                                                 alt="{{ $institution->name }}"
                                                 class="w-10 h-10 rounded-lg object-cover">
                                        @else
                                            <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center">
                                                <i data-lucide="building-2" class="w-5 h-5 text-primary"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-medium text-text-primary">{{ $institution->name }}</p>
                                            <p class="text-xs text-text-muted">{{ $institution->description }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-text-secondary">
                                    {{ $institution->subdistrict ? $institution->subdistrict->name . ', ' . $institution->subdistrict->district->name : '-' }}
                                </td>
                                <td class="py-3 px-4">
                                    <x-status-badge type="{{ $institution->is_active ? 'aktif' : 'tidak_aktif' }}">
                                        {{ $institution->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </x-status-badge>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.institutions.edit', $institution) }}"
                                            class="p-2 text-text-secondary hover:text-primary hover:bg-primary/10 rounded-lg transition-colors">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </a>
                                        <a href="{{ route('admin.institutions.services.index', $institution) }}"
                                            class="p-2 text-text-secondary hover:text-tertiary hover:bg-tertiary/10 rounded-lg transition-colors">
                                            <i data-lucide="list" class="w-4 h-4"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.institutions.destroy', $institution) }}"
                                            onsubmit="return confirm('Yakin ingin menghapus instansi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-text-secondary hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
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
        @endif
    </x-card>
</x-app-layout>
