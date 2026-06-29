<aside x-data="{ open: window.innerWidth >= 1024 }"
       @toggle-sidebar.window="open = !open"
       class="fixed top-0 left-0 h-full bg-secondary z-40 transition-all duration-300 flex flex-col"
       :class="open ? 'w-[280px]' : '-translate-x-full lg:translate-x-0 lg:w-16'">
    <div class="h-16 flex items-center px-4 border-b border-white/10">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 min-w-0">
            <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center shrink-0">
                <span class="text-white font-bold text-xs">S</span>
            </div>
            <span x-show="open" class="font-bold text-white text-lg whitespace-nowrap">Speed ID</span>
        </a>
        <button @click="open = !open" class="ml-auto p-1.5 hover:bg-white/10 rounded-lg lg:block hidden">
            <i data-lucide="panel-left-close" class="w-4 h-4 text-text-muted"></i>
        </button>
    </div>
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" sidebar>
            <i data-lucide="layout-dashboard" class="w-5 h-5 shrink-0"></i>
            <span x-show="open" class="ml-3">Dashboard</span>
        </x-nav-link>

        <x-nav-link :href="route('queue.booking')" :active="request()->routeIs('queue.*')" sidebar>
            <i data-lucide="ticket" class="w-5 h-5 shrink-0"></i>
            <span x-show="open" class="ml-3">Antrean</span>
        </x-nav-link>
        <x-nav-link :href="route('reports.create')" :active="request()->routeIs('reports.*')" sidebar>
            <i data-lucide="triangle-alert" class="w-5 h-5 shrink-0"></i>
            <span x-show="open" class="ml-3">Laporan</span>
        </x-nav-link>
        <x-nav-link :href="route('sos.index')" :active="request()->routeIs('sos.*')" sidebar>
            <i data-lucide="shield-alert" class="w-5 h-5 shrink-0"></i>
            <span x-show="open" class="ml-3">SOS Darurat</span>
        </x-nav-link>
        <x-nav-link :href="route('news.index')" :active="request()->routeIs('news.*')" sidebar>
            <i data-lucide="newspaper" class="w-5 h-5 shrink-0"></i>
            <span x-show="open" class="ml-3">Berita</span>
        </x-nav-link>

        @if(Auth::user()->hasRole('Admin'))
        <div class="pt-4 pb-2" x-show="open">
            <p class="text-xs font-medium text-text-muted uppercase tracking-wider px-3">Admin</p>
        </div>
        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" sidebar>
            <i data-lucide="bar-chart-3" class="w-5 h-5 shrink-0"></i>
            <span x-show="open" class="ml-3">Dashboard</span>
        </x-nav-link>
        <x-nav-link :href="route('admin.institutions.index')" :active="request()->routeIs('admin.institutions.*')" sidebar>
            <i data-lucide="building-2" class="w-5 h-5 shrink-0"></i>
            <span x-show="open" class="ml-3">Instansi</span>
        </x-nav-link>
        <x-nav-link :href="route('admin.queue.index')" :active="request()->routeIs('admin.queue.*')" sidebar>
            <i data-lucide="list-ordered" class="w-5 h-5 shrink-0"></i>
            <span x-show="open" class="ml-3">Antrean</span>
        </x-nav-link>
        <x-nav-link :href="route('admin.reports.index')" :active="request()->routeIs('admin.reports.*')" sidebar>
            <i data-lucide="file-text" class="w-5 h-5 shrink-0"></i>
            <span x-show="open" class="ml-3">Laporan</span>
        </x-nav-link>
        <x-nav-link :href="route('admin.sos.monitoring')" :active="request()->routeIs('admin.sos.*')" sidebar>
            <i data-lucide="radio" class="w-5 h-5 shrink-0"></i>
            <span x-show="open" class="ml-3">SOS Monitoring</span>
        </x-nav-link>
        <x-nav-link :href="route('admin.news.index')" :active="request()->routeIs('admin.news.*')" sidebar>
            <i data-lucide="pen-square" class="w-5 h-5 shrink-0"></i>
            <span x-show="open" class="ml-3">Berita</span>
        </x-nav-link>
        <x-nav-link :href="route('admin.contacts.index')" :active="request()->routeIs('admin.contacts.*')" sidebar>
            <i data-lucide="phone" class="w-5 h-5 shrink-0"></i>
            <span x-show="open" class="ml-3">Kontak Darurat</span>
        </x-nav-link>
        @endif
    </nav>
    <div class="p-3 border-t border-white/10">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center w-full px-3 py-2 text-sm text-text-muted hover:text-white hover:bg-white/10 rounded-lg transition-colors">
                <i data-lucide="log-out" class="w-5 h-5 shrink-0"></i>
                <span x-show="open" class="ml-3">Keluar</span>
            </button>
        </form>
    </div>
</aside>
