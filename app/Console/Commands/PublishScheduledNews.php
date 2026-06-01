<?php

namespace App\Console\Commands;

use App\Models\NewsPost;
use Illuminate\Console\Command;

class PublishScheduledNews extends Command
{
    protected $signature = 'speedid:publish-news';
    protected $description = 'Publish scheduled news posts';

    public function handle(): void
    {
        $count = NewsPost::where('status', 'draft')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->update(['status' => 'published']);

        $this->info("Published {$count} scheduled news articles.");
    }
}
