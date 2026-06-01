@props(['service' => null, 'slots' => []])

<x-app-layout>
    @section('title', 'Jadwal Sesi - ' . $service->name)
    @section('header', 'Jadwal Sesi ' . $service->name)

    <div class="flex justify-end mb-6">
        <a href="{{ route('admin.services.slots.create', $service) }}"
            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary/90">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
            Tambah Sesi
        </a>
    </div>

    <x-card>
        @if ($slots->isEmpty())
            <div class="text-center py-12">
                <i data-lucide="calendar-x" class="w-12 h-12 text-text-muted mx-auto mb-3"></i>
                <p class="text-text-secondary">Belum ada sesi untuk layanan ini.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-border">
                            <th class="text-left py-3 px-4 font-medium text-text-secondary">Tanggal</th>
                            <th class="text-left py-3 px-4 font-medium text-text-secondary">Waktu Mulai</th>
                            <th class="text-left py-3 px-4 font-medium text-text-secondary">Waktu Selesai</th>
                            <th class="text-center py-3 px-4 font-medium text-text-secondary">Kuota</th>
                            <th class="text-center py-3 px-4 font-medium text-text-secondary">Terbooking</th>
                            <th class="text-center py-3 px-4 font-medium text-text-secondary">Sisa</th>
                            <th class="text-right py-3 px-4 font-medium text-text-secondary">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @foreach ($slots as $slot)
                            @php
                                $remaining = $slot->quota - $slot->booked_count;
                            @endphp
                            <tr class="hover:bg-surface">
                                <td class="py-3 px-4 font-medium text-text-primary">{{ $slot->date->format('d M Y') }}</td>
                                <td class="py-3 px-4 text-text-secondary">{{ $slot->start_time }}</td>
                                <td class="py-3 px-4 text-text-secondary">{{ $slot->end_time }}</td>
                                <td class="py-3 px-4 text-center text-text-secondary">{{ $slot->quota }}</td>
                                <td class="py-3 px-4 text-center text-text-secondary">{{ $slot->booked_count }}</td>
                                <td class="py-3 px-4 text-center">
                                    @if ($remaining > 0)
                                        <span class="text-green-600 font-medium">{{ $remaining }}</span>
                                    @else
                                        <span class="text-red-600 font-medium">Penuh</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <form method="POST" action="{{ route('admin.services.slots.destroy', $slot) }}"
                                        onsubmit="return confirm('Yakin ingin menghapus sesi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-2 text-text-secondary hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                            title="Hapus">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-card>

    <div class="mt-4">
        <a href="{{ route('admin.institutions.services.index', $service->institution) }}"
            class="inline-flex items-center text-sm font-medium text-primary hover:text-primary/80">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i>
            Kembali ke Daftar Layanan
        </a>
    </div>
</x-app-layout>
