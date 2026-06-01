@props(['active' => false, 'sidebar' => false, 'href' => '#'])

@if($sidebar)
<a href="{{ $href }}" @class([
    'flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 group',
    'bg-primary/10 text-primary' => $active,
    'text-text-muted hover:text-white hover:bg-white/10' => !$active,
])>
    {{ $slot }}
</a>
@else
<a href="{{ $href }}" @class([
    'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-loose transition-all duration-150',
    'border-primary text-text-primary' => $active,
    'border-transparent text-text-secondary hover:text-text-primary hover:border-border' => !$active,
])>
    {{ $slot }}
</a>
@endif
