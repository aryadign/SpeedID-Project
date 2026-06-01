<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupActivityLogs extends Command
{
    protected $signature = 'speedid:cleanup-activity';
    protected $description = 'Delete activity logs older than 90 days';

    public function handle(): void
    {
        $count = DB::table('activity_log')
            ->where('created_at', '<', now()->subDays(90))
            ->delete();

        $this->info("Deleted {$count} old activity logs.");
    }
}
