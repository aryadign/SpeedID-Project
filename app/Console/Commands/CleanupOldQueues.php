<?php

namespace App\Console\Commands;

use App\Models\QueueTicket;
use App\Models\ServiceSlot;
use Illuminate\Console\Command;

class CleanupOldQueues extends Command
{
    protected $signature = 'speedid:cleanup-queues';
    protected $description = 'Cancel old pending queue tickets past their slot date';

    public function handle(): void
    {
        $count = QueueTicket::whereIn('status', ['menunggu', 'dipanggil'])
            ->whereHas('serviceSlot', function ($q) {
                $q->where('date', '<', now()->subDay());
            })
            ->update(['status' => 'batal', 'cancelled_at' => now()]);

        $this->info("Cleaned up {$count} old queue tickets.");
    }
}
