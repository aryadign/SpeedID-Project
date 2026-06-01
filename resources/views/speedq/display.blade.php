<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Antrean - {{ $service->name }}</title>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-secondary text-white min-h-screen flex flex-col">
    <div x-data="queueDisplay('{{ route('queue.current', $service->id) }}')" x-init="init()">
        <header class="bg-secondary/80 border-b border-white/10 px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center">
                        <span class="text-white font-bold text-sm">S</span>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold">SpeedQ</h1>
                        <p class="text-sm text-white/60">Sistem Antrean Digital</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-lg font-semibold">{{ $service->institution->name }}</p>
                    <p class="text-sm text-white/60">{{ $service->name }}</p>
                </div>
            </div>
        </header>

        <main class="flex-1 flex flex-col items-center justify-center px-8 py-12">
            <p class="text-lg text-white/60 uppercase tracking-widest mb-2">Sekarang Dipanggil</p>

            <div class="text-[12rem] font-bold leading-none mb-4 tracking-tight transition-all duration-300"
                 x-text="currentNumber || '---'">
                {{ $current->first()?->queue_number ?? '---' }}
            </div>

            <div class="h-1 w-48 bg-primary rounded-full mb-6"></div>

            <div class="flex items-center gap-8 text-center">
                <div>
                    <p class="text-2xl text-white/60">Layanan</p>
                    <p class="text-3xl font-semibold mt-1">{{ $service->name }}</p>
                </div>
                <div class="w-px h-12 bg-white/20"></div>
                <div>
                    <p class="text-2xl text-white/60">Loket</p>
                    <p class="text-3xl font-semibold mt-1" x-text="currentCounter || '---'">---</p>
                </div>
            </div>

            <div class="mt-12 text-center">
                <p class="text-lg text-white/40">Mengantre</p>
                <p class="text-2xl font-semibold text-white/60 mt-1" x-text="waitingCount || '0'">0</p>
            </div>
        </main>

        <footer class="bg-secondary/80 border-t border-white/10 px-8 py-4">
            <div class="flex items-center justify-between text-sm text-white/40">
                <p>{{ now()->format('d M Y') }}</p>
                <p class="flex items-center gap-2">
                    <i data-lucide="clock" class="w-4 h-4"></i>
                    <span x-text="currentTime">{{ now()->format('H:i') }}</span>
                </p>
            </div>
        </footer>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('queueDisplay', (pollUrl) => ({
                currentNumber: null,
                currentCounter: null,
                waitingCount: 0,
                currentTime: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }),
                intervalId: null,

                init() {
                    this.fetchQueue();
                    this.intervalId = setInterval(() => this.fetchQueue(), 10000);
                    setInterval(() => {
                        this.currentTime = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                    }, 10000);
                },

                async fetchQueue() {
                    try {
                        const res = await fetch(pollUrl);
                        if (!res.ok) return;
                        const data = await res.json();
                        if (data) {
                            this.currentNumber = data.queue_number;
                            this.currentCounter = data.counter || null;
                        }
                    } catch (e) {
                        // silent
                    }
                },

                destroy() {
                    if (this.intervalId) clearInterval(this.intervalId);
                }
            }));
        });
    </script>
</body>
</html>
