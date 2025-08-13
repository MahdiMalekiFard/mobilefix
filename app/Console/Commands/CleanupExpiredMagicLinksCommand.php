<?php

namespace App\Console\Commands;

use App\Services\MagicLinkService;
use Illuminate\Console\Command;

class CleanupExpiredMagicLinksCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'magic-links:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired magic links from the database';

    /**
     * Execute the console command.
     */
    public function handle(MagicLinkService $magicLinkService): int
    {
        $this->info('Starting cleanup of expired magic links...');
        
        $deletedCount = $magicLinkService->cleanupExpired();
        
        $this->info("Cleanup completed! Deleted {$deletedCount} expired magic links.");
        
        return Command::SUCCESS;
    }
}
