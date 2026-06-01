@props(['type' => 'success', 'message' => ''])

@php
$colors = [
    'success' => 'bg-green-50 border-green-200 text-green-800',
    'error' => 'bg-red-50 border-red-200 text-red-800',
    'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
    'info' => 'bg-blue-50 border-blue-200 text-blue-800',
];
$icons = [
    'success' => 'check-circle-2',
    'error' => 'alert-circle',
    'warning' => 'alert-triangle',
    'info' => 'info',
];
@endphp

<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="flex items-center gap-3 px-4 py-3 rounded-lg border mb-4 {{ $colors[$type] }}">
    <i data-lucide="{{ $icons[$type] }}" class="w-5 h-5 shrink-0"></i>
    <p class="text-sm font-medium flex-1">{{ $message }}</p>
    <button @click="show = false" class="shrink-0 hover:opacity-70">
        <i data-lucide="x" class="w-4 h-4"></i>
    </button>
</div>
