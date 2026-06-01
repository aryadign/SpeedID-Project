@props(['class' => ''])

<div class="bg-surface-alt rounded-lg shadow-card-md p-6 {{ $class }}">
    {{ $slot }}
</div>
