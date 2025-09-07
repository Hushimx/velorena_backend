<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DesignApiService;

class SyncDesignsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'designs:sync {--limit=100 : Number of designs to sync} {--category= : Specific category to sync} {--search= : Search query to sync}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync designs from external API to local database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting design sync...');

        $apiService = new DesignApiService();
        $limit = $this->option('limit');
        $category = $this->option('category');
        $search = $this->option('search');

        try {
            if ($search) {
                $this->info("Searching for designs with query: {$search}");
                $result = $apiService->searchDesigns($search, ['limit' => $limit]);
            } elseif ($category) {
                $this->info("Syncing designs from category: {$category}");
                $result = $apiService->getDesignsByCategory($category, ['limit' => $limit]);
            } else {
                $this->info("Syncing {$limit} designs from API...");
                $result = $apiService->syncDesigns($limit);
            }

            if ($result === false) {
                $this->error('Failed to sync designs from API');
                return 1;
            }

            if (is_numeric($result)) {
                $this->info("Successfully synced {$result} designs!");
            } else {
                $this->info('Designs synced successfully!');
            }

            return 0;
        } catch (\Exception $e) {
            $this->error('Error syncing designs: ' . $e->getMessage());
            return 1;
        }
    }
}
