<nav x-data="{ scrolled: false }" x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 20)" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300" :class="scrolled ? 'bg-white/80 backdrop-blur-xl shadow-card-sm' : 'bg-transparent'">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <div class="w-9 h-9 bg-primary rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-sm">S</span>
                </div>
                <span class="font-bold text-xl text-secondary">Speed ID</span>
            </a>
            <div class="hidden md:flex items-center gap-8">
                <a href="{{ route('home') }}" class="text-sm font-medium text-text-secondary hover:text-primary transition-colors">{{ __('Beranda') }}</a>
                <a href="#layanan" class="text-sm font-medium text-text-secondary hover:text-primary transition-colors">{{ __('Layanan') }}</a>
                <a href="{{ route('news.index') }}" class="text-sm font-medium text-text-secondary hover:text-primary transition-colors">{{ __('Berita') }}</a>
                <a href="#tentang" class="text-sm font-medium text-text-secondary hover:text-primary transition-colors">{{ __('Tentang') }}</a>
            </div>
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-sm font-medium text-primary hover:text-primary/80 transition-colors">{{ __('Dashboard') }}</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-text-secondary hover:text-primary transition-colors">{{ __('Masuk') }}</a>
                    <a href="{{ route('register') }}" class="inline-flex items-center px-5 py-2.5 bg-primary text-white text-sm font-medium rounded-full hover:bg-primary/90 transition-all">
                        {{ __('Daftar') }}
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
