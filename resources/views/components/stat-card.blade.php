@props(['label', 'value', 'icon', 'color' => 'primary'])

@php
$colors = [
    'primary' => 'bg-primary/10 text-primary',
    'success' => 'bg-green-100 text-green-600',
    'warning' => 'bg-yellow-100 text-yellow-600',
    'danger' => 'bg-red-100 text-red-600',
    'info' => 'bg-blue-100 text-blue-600',
];
@endphp

<div class="bg-surface-alt rounded-lg shadow-card-sm p-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-text-muted">{{ $label }}</p>
            <p class="text-2xl font-bold mt-1">{{ $value }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl {{ $colors[$color] }} flex items-center justify-center">
            <i data-lucide="{{ $icon }}" class="w-6 h-6"></i>
        </div>
    </div>
</div>
