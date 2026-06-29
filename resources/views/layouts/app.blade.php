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
    <div class="flex min-h-screen">
        <x-sidebar />
        <div class="flex-1 flex flex-col lg:ml-[280px]">
            <header x-data="notificationPolling('{{ route('poll.notifications') }}')" x-init="init()" class="h-16 bg-surface-alt border-b border-border flex items-center justify-between px-6 sticky top-0 z-30">
                <div class="flex items-center gap-3 shrink-0">
                    <button x-data @click="$dispatch('toggle-sidebar')" class="lg:hidden p-2 hover:bg-surface rounded-md">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>
                    <h1 class="text-lg font-semibold">{{ $header ?? '' }}</h1>
                </div>

                <div class="hidden sm:block flex-1 max-w-md mx-auto px-4">
                    <div x-data="liveSearch()" class="relative" @keydown.escape.window="results = []">
                        <form method="GET" action="{{ route('search') }}" @submit="results = []">
                            <div class="relative">
                                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-text-muted pointer-events-none"></i>
                                <input type="text" name="q" x-model="query" @input.debounce.350ms="doSearch"
                                       placeholder="Cari instansi, berita, laporan..." autocomplete="off"
                                       class="w-full pl-12 pr-3 py-2 bg-surface border border-border rounded-lg text-sm focus:ring-1 focus:ring-primary focus:border-primary placeholder:text-text-muted">
                            </div>
                        </form>
                        <div x-show="results.length > 0" @click.outside="results = []"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="absolute top-full mt-2 left-0 right-0 bg-surface-alt rounded-lg shadow-card-lg border border-border overflow-hidden z-50">
                            <template x-for="item in results" :key="item.url">
                                <a :href="item.url" @click="results = []"
                                   class="flex items-center gap-3 px-4 py-3 hover:bg-surface transition-colors border-b border-border last:border-0">
                                    <i :data-lucide="item.icon" class="w-4 h-4 text-primary shrink-0"></i>
                                    <div class="min-w-0">
                                        <p class="text-xs text-text-muted" x-text="item.type"></p>
                                        <p class="text-sm font-medium text-text-primary truncate" x-text="item.title"></p>
                                    </div>
                                </a>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('search') }}" class="sm:hidden p-2 hover:bg-surface rounded-md">
                        <i data-lucide="search" class="w-5 h-5 text-text-secondary"></i>
                    </a>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="relative p-2 hover:bg-surface rounded-md">
                            <i data-lucide="bell" class="w-5 h-5 text-text-secondary"></i>
                            <span x-show="unreadCount > 0"
                                  x-text="unreadCount"
                                  class="absolute -top-0.5 -right-0.5 w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center"></span>
                        </button>
                        <div x-show="open" @click.outside="open = false"
                             class="absolute right-0 mt-2 w-80 bg-surface-alt rounded-lg shadow-card-lg border border-border overflow-hidden z-50">
                            <div class="px-4 py-3 border-b border-border flex items-center justify-between">
                                <p class="text-sm font-semibold">Notifikasi</p>
                                <button x-show="unreadCount > 0" @click="markAllRead" class="text-xs text-primary hover:underline">Tandai semua dibaca</button>
                            </div>
                            <div class="max-h-72 overflow-y-auto">
                                <template x-if="notifications.length === 0">
                                    <p class="text-sm text-text-muted text-center py-8">Tidak ada notifikasi</p>
                                </template>
                                <template x-for="notif in notifications" :key="notif.id">
                                    <a :href="notif.url" @click="markRead(notif.id)" class="flex items-start gap-3 px-4 py-3 hover:bg-surface transition-colors border-b border-border last:border-0">
                                        <div class="w-2 h-2 mt-2 rounded-full bg-primary shrink-0"></div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-medium text-text-primary" x-text="notif.title"></p>
                                            <p class="text-xs text-text-muted" x-text="notif.message"></p>
                                            <p class="text-xs text-text-muted mt-1" x-text="notif.created_at"></p>
                                        </div>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </div>
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center gap-2 p-1.5 hover:bg-surface rounded-lg">
                                <div class="w-8 h-8 bg-primary/10 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-primary">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                                <span class="hidden sm:block text-sm font-medium">{{ Auth::user()->name }}</span>
                                <i data-lucide="chevron-down" class="w-4 h-4 text-text-muted"></i>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                <i data-lucide="user" class="w-4 h-4 mr-2"></i> Profil
                            </x-dropdown-link>
                            @if(Auth::user()->hasRole('Admin'))
                                <x-dropdown-link :href="route('admin.dashboard')">
                                    <i data-lucide="shield" class="w-4 h-4 mr-2"></i> Admin Panel
                                </x-dropdown-link>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    <i data-lucide="log-out" class="w-4 h-4 mr-2"></i> Keluar
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </header>
            <main class="flex-1 p-6">
                @if (session('success'))
                    <x-alert type="success" :message="session('success')" />
                @endif
                @if (session('error'))
                    <x-alert type="error" :message="session('error')" />
                @endif
                {{ $slot }}
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
