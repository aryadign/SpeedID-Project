<x-guest-layout>
    @section('title', 'Masuk - Speed ID')

    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-secondary">Selamat Datang</h1>
        <p class="text-sm text-text-secondary mt-1">Masuk ke akun Speed ID Anda</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block mt-1.5 w-full" type="email" name="email"
                :value="old('email')" required autofocus autocomplete="username"
                placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <div class="flex items-center justify-between mb-1">
                <x-input-label for="password" value="Password" />
                @if (Route::has('password.request'))
                    <a class="text-xs text-primary hover:text-primary/80 font-medium"
                       href="{{ route('password.request') }}">
                        Lupa password?
                    </a>
                @endif
            </div>
            <x-text-input id="password" class="block mt-1.5 w-full" type="password"
                name="password" required autocomplete="current-password"
                placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <label class="flex items-center gap-2.5">
            <input id="remember_me" type="checkbox"
                class="rounded-md border-border text-primary focus:ring-primary"
                name="remember">
            <span class="text-sm text-text-secondary">Ingat saya</span>
        </label>

        <button type="submit"
            class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-primary text-white text-sm font-semibold rounded-full hover:bg-primary/90 transition-all shadow-sm">
            Masuk
            <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </button>

        <p class="text-center text-sm text-text-secondary">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-primary font-semibold hover:text-primary/80">
                Daftar sekarang
            </a>
        </p>
    </form>
</x-guest-layout>
