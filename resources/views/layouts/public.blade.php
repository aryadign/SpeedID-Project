<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Speed ID - Layanan Publik Digital')</title>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased bg-surface text-text-primary">
    <x-public-navbar />
    <main>
        <div x-data="emergencyAlert('{{ route('news.emergency') }}')" x-init="init()">
            <template x-if="alerts.length > 0">
                <div class="fixed top-0 left-0 right-0 z-[60] bg-red-600 text-white text-center py-2 px-4">
                    <template x-for="alert in alerts" :key="alert.id">
                        <div class="flex items-center justify-center gap-2">
                            <i data-lucide="triangle-alert" class="w-4 h-4 shrink-0"></i>
                            <a :href="alert.url" class="text-sm font-medium hover:underline" x-text="alert.title"></a>
                        </div>
                    </template>
                </div>
            </template>
        </div>
        {{ $slot }}
    </main>
    <x-footer />
    @stack('scripts')
</body>
</html>
