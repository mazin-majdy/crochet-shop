<?php

namespace App\Console\Commands;

use App\Models\PageView;
use Illuminate\Console\Command;

class PrunePageViews extends Command
{
    protected $signature = 'page-views:prune {--days=30 : Number of days to keep}';
    protected $description = 'Prune old page view records';

    public function handle(): int
    {
        $days = $this->option('days');
        $deleted = PageView::where('visited_at', '<', now()->subDays($days))->delete();

        $this->info("✅ Deleted {$deleted} page views older than {$days} days.");

        return Command::SUCCESS;
    }
}
