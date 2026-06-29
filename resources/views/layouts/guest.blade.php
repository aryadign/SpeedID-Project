<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Speed ID') }}</title>

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased bg-surface text-text-primary">
    <div class="relative min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 overflow-hidden">
        {{-- Background --}}
        <div class="absolute inset-0 bg-gradient-to-br from-primary/5 via-surface to-tertiary/5"></div>

        {{-- Decorative blur circles --}}
        <div class="absolute top-20 right-0 w-96 h-96 bg-primary/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-72 h-72 bg-tertiary/10 rounded-full blur-3xl"></div>

        {{-- Grid pattern --}}
        <svg class="absolute inset-0 w-full h-full opacity-[0.015] pointer-events-none" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="authGrid" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M 40 0 L 0 0 0 40" fill="none" stroke="currentColor" stroke-width="0.5" class="text-secondary"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#authGrid)"/>
        </svg>

        {{-- Branding --}}
        <div class="relative mb-6 text-center">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2">
                <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center shadow-sm">
                    <span class="text-white font-bold text-lg">S</span>
                </div>
                <span class="font-bold text-2xl text-secondary">Speed ID</span>
            </a>
        </div>

        {{-- Auth Card --}}
        <div class="relative w-full sm:max-w-md px-6 sm:px-0">
            <div class="bg-surface-alt rounded-xl shadow-card-md p-8 animate-fadeIn">
                {{ $slot }}
            </div>

            <p class="text-center text-xs text-text-muted mt-6">
                &copy; {{ date('Y') }} Speed ID. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
