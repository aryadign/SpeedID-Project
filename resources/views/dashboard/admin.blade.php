<x-app-layout>
<x-slot:header>Admin Dashboard</x-slot:header>
<x-slot:title>Admin Dashboard - Speed ID</x-slot:title>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <x-stat-card label="Total Pengguna" :value="$total_users" icon="users" color="primary" />
    <x-stat-card label="Antrean Aktif" :value="$active_queues" icon="list-ordered" color="warning" />
    <x-stat-card label="Laporan Pending" :value="$pending_reports" icon="file-text" color="info" />
    <x-stat-card label="SOS Aktif" :value="$active_sos" icon="radio" color="danger" />
</div>

<div class="grid lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-surface-alt rounded-lg shadow-card-md p-6">
        <h3 class="font-semibold text-lg mb-4">Statistik Cepat</h3>
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-surface rounded-lg p-4">
                <p class="text-2xl font-bold text-primary">{{ $total_queues }}</p>
                <p class="text-xs text-text-muted">Total Antrean</p>
            </div>
            <div class="bg-surface rounded-lg p-4">
                <p class="text-2xl font-bold text-primary">{{ $total_reports }}</p>
                <p class="text-xs text-text-muted">Total Laporan</p>
            </div>
            <div class="bg-surface rounded-lg p-4">
                <p class="text-2xl font-bold text-primary">{{ $total_sos }}</p>
                <p class="text-xs text-text-muted">Total SOS</p>
            </div>
            <div class="bg-surface rounded-lg p-4">
                <p class="text-2xl font-bold text-primary">{{ $total_articles }}</p>
                <p class="text-xs text-text-muted">Total Artikel</p>
            </div>
        </div>
    </div>
    <div class="bg-surface-alt rounded-lg shadow-card-md p-6">
        <h3 class="font-semibold text-lg mb-4">Aksi Cepat</h3>
        <div class="space-y-3">
            <a href="{{ route('admin.institutions.index') }}" class="flex items-center gap-3 p-3 hover:bg-surface rounded-lg transition-colors">
                <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center"><i data-lucide="building-2" class="w-5 h-5 text-primary"></i></div>
                <span class="text-sm font-medium">Kelola Instansi</span>
            </a>
            <a href="{{ route('admin.queue.index') }}" class="flex items-center gap-3 p-3 hover:bg-surface rounded-lg transition-colors">
                <div class="w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center"><i data-lucide="list-ordered" class="w-5 h-5 text-yellow-600"></i></div>
                <span class="text-sm font-medium">Kelola Antrean</span>
            </a>
            <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 p-3 hover:bg-surface rounded-lg transition-colors">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center"><i data-lucide="file-text" class="w-5 h-5 text-blue-600"></i></div>
                <span class="text-sm font-medium">Kelola Laporan</span>
            </a>
            <a href="{{ route('admin.sos.index') }}" class="flex items-center gap-3 p-3 hover:bg-surface rounded-lg transition-colors">
                <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center"><i data-lucide="radio" class="w-5 h-5 text-red-600"></i></div>
                <span class="text-sm font-medium">Monitoring SOS</span>
            </a>
        </div>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <x-card>
        <h3 class="font-semibold text-lg mb-4">Laporan Terbaru</h3>
        <div class="space-y-3">
            @forelse($recent_reports as $report)
                <a href="{{ route('admin.reports.show', $report->id) }}" class="flex items-center justify-between p-3 hover:bg-surface rounded-lg transition-colors">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate">{{ $report->title }}</p>
                        <p class="text-xs text-text-muted">{{ $report->user->name }} &middot; {{ $report->created_at->diffForHumans() }}</p>
                    </div>
                    <x-status-badge type="{{ $report->status }}" size="sm">{{ $report->status }}</x-status-badge>
                </a>
            @empty
                <p class="text-sm text-text-muted text-center py-4">Belum ada laporan</p>
            @endforelse
        </div>
    </x-card>
    <x-card>
        <h3 class="font-semibold text-lg mb-4">SOS Masuk</h3>
        <div class="space-y-3">
            @forelse($recent_sos as $sos)
                <a href="{{ route('admin.sos.show', $sos->id) }}" class="flex items-center justify-between p-3 hover:bg-surface rounded-lg transition-colors">
                    <div>
                        <p class="text-sm font-medium">{{ ucfirst($sos->emergency_type) }}</p>
                        <p class="text-xs text-text-muted">{{ $sos->user->name }} &middot; {{ $sos->created_at->diffForHumans() }}</p>
                    </div>
                    <x-status-badge type="{{ $sos->status }}" size="sm">{{ $sos->status }}</x-status-badge>
                </a>
            @empty
                <p class="text-sm text-text-muted text-center py-4">Tidak ada SOS masuk</p>
            @endforelse
        </div>
    </x-card>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('statsChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                    datasets: [{
                        label: 'Laporan',
                        data: [12, 19, 3, 5, 2, 3, 7],
                        borderColor: '#2563EB',
                        backgroundColor: 'rgba(37,99,235,0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } }
                }
            });
        }
    });
</script>
@endpush
</x-app-layout>
