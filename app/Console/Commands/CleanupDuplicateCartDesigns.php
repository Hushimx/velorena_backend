<?php

namespace App\Console\Commands;

use App\Models\CartDesign;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupDuplicateCartDesigns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cart:cleanup-duplicates {--dry-run : Show what would be deleted without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up duplicate cart designs to prevent memory issues';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        
        $this->info('Starting cart designs cleanup...');
        
        if ($isDryRun) {
            $this->warn('DRY RUN MODE - No data will be deleted');
        }
        
        // Get all cart designs grouped by user/session, title, and image_url
        $duplicates = CartDesign::select('user_id', 'session_id', 'title', 'image_url', DB::raw('COUNT(*) as count'))
            ->where('is_active', true)
            ->groupBy('user_id', 'session_id', 'title', 'image_url')
            ->having('count', '>', 1)
            ->get();
        
        $totalDuplicates = 0;
        $totalDeleted = 0;
        
        foreach ($duplicates as $duplicate) {
            $this->line("Found {$duplicate->count} duplicates for: {$duplicate->title}");
            $totalDuplicates += $duplicate->count - 1; // -1 because we keep one
            
            // Get all duplicate records for this combination
            $records = CartDesign::where('user_id', $duplicate->user_id)
                ->where('session_id', $duplicate->session_id)
                ->where('title', $duplicate->title)
                ->where('image_url', $duplicate->image_url)
                ->where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Keep the most recent one, delete the rest
            $keepRecord = $records->first();
            $deleteRecords = $records->skip(1);
            
            foreach ($deleteRecords as $record) {
                if ($isDryRun) {
                    $this->line("  Would delete: ID {$record->id} (created: {$record->created_at})");
                } else {
                    $record->delete();
                    $this->line("  Deleted: ID {$record->id} (created: {$record->created_at})");
                    $totalDeleted++;
                }
            }
        }
        
        if ($totalDuplicates === 0) {
            $this->info('No duplicates found!');
        } else {
            if ($isDryRun) {
                $this->info("Found {$totalDuplicates} duplicate records that would be deleted.");
                $this->info('Run without --dry-run to actually delete them.');
            } else {
                $this->info("Successfully deleted {$totalDeleted} duplicate records.");
            }
        }
        
        // Also clean up old inactive designs (older than 30 days)
        $oldInactive = CartDesign::where('is_active', false)
            ->where('created_at', '<', now()->subDays(30))
            ->count();
        
        if ($oldInactive > 0) {
            if ($isDryRun) {
                $this->info("Found {$oldInactive} old inactive designs that would be deleted.");
            } else {
                $deletedOld = CartDesign::where('is_active', false)
                    ->where('created_at', '<', now()->subDays(30))
                    ->delete();
                $this->info("Deleted {$deletedOld} old inactive designs.");
            }
        }
        
        // Show current stats
        $totalActive = CartDesign::where('is_active', true)->count();
        $totalInactive = CartDesign::where('is_active', false)->count();
        
        $this->info("Current stats:");
        $this->info("  Active designs: {$totalActive}");
        $this->info("  Inactive designs: {$totalInactive}");
        
        return 0;
    }
}