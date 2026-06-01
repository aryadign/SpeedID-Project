@props(['institution' => null, 'services' => []])

<x-app-layout>
    @section('title', 'Layanan - ' . $institution->name)
    @section('header', 'Layanan ' . $institution->name)

    <div class="flex justify-end mb-6">
        <a href="{{ route('admin.institutions.services.create', $institution) }}"
            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary/90">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
            Tambah Layanan
        </a>
    </div>

    <x-card>
        @if ($services->isEmpty())
            <div class="text-center py-12">
                <i data-lucide="folder-open" class="w-12 h-12 text-text-muted mx-auto mb-3"></i>
                <p class="text-text-secondary">Belum ada layanan untuk instansi ini.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-border">
                            <th class="text-left py-3 px-4 font-medium text-text-secondary">Nama Layanan</th>
                            <th class="text-left py-3 px-4 font-medium text-text-secondary">Deskripsi</th>
                            <th class="text-center py-3 px-4 font-medium text-text-secondary">Durasi (menit)</th>
                            <th class="text-center py-3 px-4 font-medium text-text-secondary">Kuota Harian</th>
                            <th class="text-right py-3 px-4 font-medium text-text-secondary">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @foreach ($services as $service)
                            <tr class="hover:bg-surface">
                                <td class="py-3 px-4 font-medium text-text-primary">{{ $service->name }}</td>
                                <td class="py-3 px-4 text-text-secondary max-w-xs truncate">{{ $service->description ?? '-' }}</td>
                                <td class="py-3 px-4 text-center text-text-secondary">{{ $service->duration }}</td>
                                <td class="py-3 px-4 text-center text-text-secondary">{{ $service->daily_quota }}</td>
                                <td class="py-3 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.services.slots.index', $service) }}"
                                            class="p-2 text-text-secondary hover:text-tertiary hover:bg-tertiary/10 rounded-lg transition-colors"
                                            title="Jadwal Sesi">
                                            <i data-lucide="calendar-clock" class="w-4 h-4"></i>
                                        </a>
                                        <a href="{{ route('admin.services.edit', $service) }}"
                                            class="p-2 text-text-secondary hover:text-primary hover:bg-primary/10 rounded-lg transition-colors"
                                            title="Edit">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.services.destroy', $service) }}"
                                            onsubmit="return confirm('Yakin ingin menghapus layanan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-text-secondary hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Hapus">
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

    <div class="mt-4">
        <a href="{{ route('admin.institutions.index') }}"
            class="inline-flex items-center text-sm font-medium text-primary hover:text-primary/80">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i>
            Kembali ke Daftar Instansi
        </a>
    </div>
</x-app-layout>
