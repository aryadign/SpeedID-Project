@if(Auth::check())
    <x-app-layout>
        @section('title', 'Berita - Speed ID')
        @section('header', 'Berita & Pengumuman')

        <div class="max-w-7xl mx-auto">
            @include('speednews.partials.list')
        </div>
    </x-app-layout>
@else
    <x-public-layout>
        <x-slot:title>Berita - Speed ID</x-slot:title>

        <section class="pt-28 pb-12 bg-surface">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-10">
                    <h1 class="text-3xl md:text-4xl font-bold text-secondary mb-4">Berita & Pengumuman</h1>
                    <p class="text-text-muted">Informasi terbaru seputar layanan publik dan kegiatan pemerintah daerah.</p>
                </div>

                @include('speednews.partials.list')
            </div>
        </section>
    </x-public-layout>
@endif
