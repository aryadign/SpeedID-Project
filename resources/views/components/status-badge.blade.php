@props(['type' => 'info', 'size' => 'sm'])

@php
$colors = [
    'menunggu' => 'bg-yellow-100 text-yellow-800',
    'dipanggil' => 'bg-blue-100 text-blue-800',
    'selesai' => 'bg-green-100 text-green-800',
    'batal' => 'bg-red-100 text-red-800',
    'terkirim' => 'bg-blue-100 text-blue-800',
    'diverifikasi' => 'bg-purple-100 text-purple-800',
    'diproses' => 'bg-yellow-100 text-yellow-800',
    'ditolak' => 'bg-red-100 text-red-800',
    'masuk' => 'bg-red-100 text-red-800',
    'dalam_penanganan' => 'bg-yellow-100 text-yellow-800',
    'published' => 'bg-green-100 text-green-800',
    'draft' => 'bg-surface text-text-secondary',
    'aktif' => 'bg-green-100 text-green-800',
    'tidak_aktif' => 'bg-surface text-text-secondary',
];
$sizeClasses = [
    'sm' => 'px-2 py-0.5 text-xs',
    'md' => 'px-3 py-1 text-sm',
];
@endphp

<span class="inline-flex items-center font-medium rounded-full {{ $sizeClasses[$size] }} {{ $colors[$type] ?? 'bg-surface text-text-secondary' }}">
    {{ $slot }}
</span>
