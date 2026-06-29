<x-guest-layout>
    @section('title', 'Daftar - Speed ID')

    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-secondary">Buat Akun</h1>
        <p class="text-sm text-text-secondary mt-1">Daftar untuk menggunakan layanan Speed ID</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="name" value="Nama Lengkap" />
            <x-text-input id="name" class="block mt-1.5 w-full" type="text" name="name"
                :value="old('name')" required autofocus autocomplete="name"
                placeholder="Nama Anda" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block mt-1.5 w-full" type="email" name="email"
                :value="old('email')" required autocomplete="username"
                placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" class="block mt-1.5 w-full" type="password"
                name="password" required autocomplete="new-password"
                placeholder="Min. 8 karakter" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Konfirmasi Password" />
            <x-text-input id="password_confirmation" class="block mt-1.5 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password"
                placeholder="Ulangi password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button type="submit"
            class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-primary text-white text-sm font-semibold rounded-full hover:bg-primary/90 transition-all shadow-sm">
            Daftar
            <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </button>

        <p class="text-center text-sm text-text-secondary">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-primary font-semibold hover:text-primary/80">
                Masuk
            </a>
        </p>
    </form>
</x-guest-layout>
