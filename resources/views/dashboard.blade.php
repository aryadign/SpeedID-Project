<x-app-layout>
<x-slot:header>Dashboard</x-slot:header>
<x-slot:title>Dashboard - Speed ID</x-slot:title>

<div class="grid lg:grid-cols-3 gap-6 mb-8">
    <a href="{{ route('queue.booking') }}" class="bg-surface-alt rounded-xl shadow-card-sm p-6 hover:shadow-card-md transition-all group">
        <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center mb-4">
            <i data-lucide="ticket" class="w-6 h-6 text-primary"></i>
        </div>
        <h3 class="font-semibold text-text-primary group-hover:text-primary transition-colors mb-1">SpeedQ</h3>
        <p class="text-sm text-text-muted">Booking dan cek antrean layanan publik</p>
    </a>
    <a href="{{ route('reports.create') }}" class="bg-surface-alt rounded-xl shadow-card-sm p-6 hover:shadow-card-md transition-all group">
        <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center mb-4">
            <i data-lucide="triangle-alert" class="w-6 h-6 text-yellow-600"></i>
        </div>
        <h3 class="font-semibold text-text-primary group-hover:text-primary transition-colors mb-1">SpeedReport</h3>
        <p class="text-sm text-text-muted">Laporkan masalah dan pantau statusnya</p>
    </a>
    <a href="{{ route('sos.index') }}" class="bg-surface-alt rounded-xl shadow-card-sm p-6 hover:shadow-card-md transition-all group">
        <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center mb-4">
            <i data-lucide="shield-alert" class="w-6 h-6 text-red-600"></i>
        </div>
        <h3 class="font-semibold text-text-primary group-hover:text-primary transition-colors mb-1">SpeedSOS</h3>
        <p class="text-sm text-text-muted">Kirim sinyal darurat darurat</p>
    </a>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <a href="{{ route('news.index') }}" class="bg-surface-alt rounded-xl shadow-card-sm p-6 hover:shadow-card-md transition-all group">
        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4">
            <i data-lucide="newspaper" class="w-6 h-6 text-blue-600"></i>
        </div>
        <h3 class="font-semibold text-text-primary group-hover:text-primary transition-colors mb-1">Berita</h3>
        <p class="text-sm text-text-muted">Baca berita dan pengumuman terbaru</p>
    </a>
    <a href="{{ route('profile.edit') }}" class="bg-surface-alt rounded-xl shadow-card-sm p-6 hover:shadow-card-md transition-all group">
        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mb-4">
            <i data-lucide="user" class="w-6 h-6 text-green-600"></i>
        </div>
        <h3 class="font-semibold text-text-primary group-hover:text-primary transition-colors mb-1">Profil Saya</h3>
        <p class="text-sm text-text-muted">Kelola informasi akun dan pengaturan</p>
    </a>
</div>

</x-app-layout>
