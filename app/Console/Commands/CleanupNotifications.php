<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupNotifications extends Command
{
    protected $signature = 'speedid:cleanup-notifications';
    protected $description = 'Delete read notifications older than 30 days';

    public function handle(): void
    {
        $count = DB::table('notifications')
            ->whereNotNull('read_at')
            ->where('read_at', '<', now()->subDays(30))
            ->delete();

        $this->info("Deleted {$count} old read notifications.");
    }
}
