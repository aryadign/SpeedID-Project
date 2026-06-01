<x-public-layout>
<x-slot:title>Speed ID - Layanan Publik Digital Terintegrasi</x-slot:title>

<section class="relative min-h-screen flex items-center pt-20 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-primary/5 via-surface to-tertiary/5"></div>
    <div class="absolute top-20 right-0 w-96 h-96 bg-primary/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-72 h-72 bg-tertiary/10 rounded-full blur-3xl"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <div>
                <span class="inline-flex items-center px-4 py-1.5 bg-primary/10 text-primary text-sm font-medium rounded-full mb-6">
                    <i data-lucide="sparkles" class="w-4 h-4 mr-2"></i>
                    Platform Layanan Publik Digital
                </span>
                <h1 class="text-display font-bold text-secondary leading-tight mb-6">
                    Layanan Publik<br/>
                    <span class="text-primary">Cepat & Transparan</span>
                </h1>
                <p class="text-lg text-text-secondary leading-relaxed mb-8 max-w-lg">
                    Speed ID menghadirkan antrean digital, pelaporan masyarakat, layanan darurat, dan informasi wilayah dalam satu platform terintegrasi.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center px-6 py-3 bg-primary text-white font-medium rounded-full hover:bg-primary/90 transition-all shadow-card-md hover:shadow-card-lg">
                        Mulai Sekarang
                        <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                    </a>
                    <a href="#layanan"
                       class="inline-flex items-center px-6 py-3 bg-surface-alt text-text-primary font-medium rounded-full border border-border hover:border-primary/30 transition-all">
                        Pelajari Layanan
                    </a>
                </div>
            </div>
            <div class="hidden lg:flex items-center justify-center">
                <div class="relative">
                    <div class="w-80 h-80 bg-gradient-to-br from-primary to-tertiary rounded-xl rotate-6 opacity-10"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-72 h-72 bg-surface-alt rounded-xl shadow-card-lg flex items-center justify-center">
                            <div class="text-center">
                                <div class="w-20 h-20 bg-primary rounded-2xl flex items-center justify-center mx-auto mb-4">
                                    <i data-lucide="zap" class="w-10 h-10 text-white"></i>
                                </div>
                                <p class="text-4xl font-bold text-primary">Speed ID</p>
                                <p class="text-text-muted mt-1">Civic Precision</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            <x-stat-card label="Total Pengguna" value="12,500+" icon="users" color="primary" />
            <x-stat-card label="Total Instansi" value="150+" icon="building-2" color="success" />
            <x-stat-card label="Kepuasan" value="96%" icon="smile" color="warning" />
            <x-stat-card label="Respon Rata-rata" value="< 5 menit" icon="clock" color="info" />
        </div>
    </div>
</section>

<section id="layanan" class="py-20 bg-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-sm font-medium text-primary uppercase tracking-wider">Layanan</span>
            <h2 class="text-3xl font-bold text-secondary mt-2">Layanan Utama Speed ID</h2>
            <p class="text-text-secondary mt-3 max-w-2xl mx-auto">Empat layanan utama yang terintegrasi untuk memudahkan akses layanan publik Anda.</p>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <a href="#" class="group bg-surface-alt rounded-lg shadow-card-sm p-8 hover:shadow-card-md transition-all hover:-translate-y-1">
                <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center mb-5 group-hover:bg-primary group-hover:text-white transition-all">
                    <i data-lucide="ticket" class="w-7 h-7 text-primary group-hover:text-white transition-colors"></i>
                </div>
                <h3 class="text-lg font-semibold mb-2">SpeedQ</h3>
                <p class="text-sm text-text-secondary leading-relaxed">Antrean online tanpa perlu datang langsung. Pilih instansi, booking slot, dapatkan QR ticket.</p>
            </a>
            <a href="#" class="group bg-surface-alt rounded-lg shadow-card-sm p-8 hover:shadow-card-md transition-all hover:-translate-y-1">
                <div class="w-14 h-14 bg-orange-100 rounded-2xl flex items-center justify-center mb-5 group-hover:bg-orange-500 group-hover:text-white transition-all">
                    <i data-lucide="triangle-alert" class="w-7 h-7 text-orange-500 group-hover:text-white transition-colors"></i>
                </div>
                <h3 class="text-lg font-semibold mb-2">SpeedReport</h3>
                <p class="text-sm text-text-secondary leading-relaxed">Laporkan masalah di lingkungan Anda. Lengkapi dengan foto, lokasi, dan pantau statusnya.</p>
            </a>
            <a href="#" class="group bg-surface-alt rounded-lg shadow-card-sm p-8 hover:shadow-card-md transition-all hover:-translate-y-1">
                <div class="w-14 h-14 bg-red-100 rounded-2xl flex items-center justify-center mb-5 group-hover:bg-red-500 group-hover:text-white transition-all">
                    <i data-lucide="shield-alert" class="w-7 h-7 text-red-500 group-hover:text-white transition-colors"></i>
                </div>
                <h3 class="text-lg font-semibold mb-2">SpeedSOS</h3>
                <p class="text-sm text-text-secondary leading-relaxed">Tombol darurat dengan lokasi realtime. Terhubung langsung dengan pihak berwenang.</p>
            </a>
            <a href="#" class="group bg-surface-alt rounded-lg shadow-card-sm p-8 hover:shadow-card-md transition-all hover:-translate-y-1">
                <div class="w-14 h-14 bg-cyan-100 rounded-2xl flex items-center justify-center mb-5 group-hover:bg-cyan-500 group-hover:text-white transition-all">
                    <i data-lucide="newspaper" class="w-7 h-7 text-cyan-500 group-hover:text-white transition-colors"></i>
                </div>
                <h3 class="text-lg font-semibold mb-2">SpeedNews</h3>
                <p class="text-sm text-text-secondary leading-relaxed">Informasi wilayah, berita terkini, pengumuman, dan peringatan darurat terkini.</p>
            </a>
        </div>
    </div>
</section>

<section id="tentang" class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <div>
                <span class="text-sm font-medium text-primary uppercase tracking-wider">Tentang</span>
                <h2 class="text-3xl font-bold text-secondary mt-2 mb-6">Mengapa Speed ID?</h2>
                <div class="space-y-6">
                    <div class="flex gap-4">
                        <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center shrink-0">
                            <i data-lucide="clock" class="w-5 h-5 text-primary"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold">Hemat Waktu</h4>
                            <p class="text-sm text-text-secondary mt-1">Tidak perlu antre berjam-jam. Booking dari rumah, datang tepat waktu.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center shrink-0">
                            <i data-lucide="eye" class="w-5 h-5 text-green-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold">Transparan</h4>
                            <p class="text-sm text-text-secondary mt-1">Pantau status laporan dan antrean secara realtime.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
                            <i data-lucide="map-pin" class="w-5 h-5 text-blue-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold">Berbasis Lokasi</h4>
                            <p class="text-sm text-text-secondary mt-1">Semua layanan terintegrasi dengan peta dan wilayah Anda.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-surface rounded-lg p-8">
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-surface-alt rounded-lg p-6 text-center">
                        <p class="text-3xl font-bold text-primary">4</p>
                        <p class="text-sm text-text-muted mt-1">Layanan</p>
                    </div>
                    <div class="bg-surface-alt rounded-lg p-6 text-center">
                        <p class="text-3xl font-bold text-primary">99.9%</p>
                        <p class="text-sm text-text-muted mt-1">Uptime</p>
                    </div>
                    <div class="bg-surface-alt rounded-lg p-6 text-center">
                        <p class="text-3xl font-bold text-primary">24/7</p>
                        <p class="text-sm text-text-muted mt-1">Dukungan</p>
                    </div>
                    <div class="bg-surface-alt rounded-lg p-6 text-center">
                        <p class="text-3xl font-bold text-primary">100+</p>
                        <p class="text-sm text-text-muted mt-1">Instansi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-20 bg-primary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">Siap Menggunakan Speed ID?</h2>
        <p class="text-primary-100 text-lg mb-8 max-w-xl mx-auto">Daftar gratis sekarang dan nikmati kemudahan akses layanan publik digital.</p>
        <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-3.5 bg-white text-primary font-medium rounded-full hover:bg-primary-50 transition-all shadow-card-md">
            Daftar Gratis Sekarang
            <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
        </a>
    </div>
</section>
</x-public-layout>
