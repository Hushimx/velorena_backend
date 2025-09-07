<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Design;
use Illuminate\Support\Facades\Http;

class TestDesignImagesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'designs:test-images {--limit=5 : Number of designs to test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test design image URLs to ensure they are accessible';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $limit = $this->option('limit');
        $this->info("Testing image URLs for {$limit} designs...\n");

        $designs = Design::take($limit)->get();

        if ($designs->isEmpty()) {
            $this->error('No designs found in database. Run the seeder first: php artisan db:seed --class=DesignSeeder');
            return 1;
        }

        $table = [];
        $successCount = 0;
        $errorCount = 0;

        foreach ($designs as $design) {
            $this->line("Testing: {$design->title}");

            // Test thumbnail
            $thumbnailStatus = $this->testImageUrl($design->thumbnail_url, 'Thumbnail');

            // Test main image
            $mainImageStatus = $this->testImageUrl($design->image_url, 'Main Image');

            $table[] = [
                'ID' => $design->id,
                'Title' => $design->title,
                'Thumbnail' => $design->thumbnail_url,
                'Thumbnail Status' => $thumbnailStatus,
                'Main Image' => $design->image_url,
                'Main Image Status' => $mainImageStatus,
            ];

            if ($thumbnailStatus === '✅ OK' && $mainImageStatus === '✅ OK') {
                $successCount++;
            } else {
                $errorCount++;
            }

            $this->newLine();
        }

        // Display results table
        $this->table([
            'ID',
            'Title',
            'Thumbnail URL',
            'Thumbnail Status',
            'Main Image URL',
            'Main Image Status'
        ], $table);

        // Summary
        $this->newLine();
        $this->info("=== SUMMARY ===");
        $this->info("Total Designs Tested: " . $designs->count());
        $this->info("✅ All Images Working: {$successCount}");
        $this->error("❌ Some Images Failed: {$errorCount}");

        if ($errorCount > 0) {
            $this->newLine();
            $this->warn("Some images failed to load. This could be due to:");
            $this->warn("- Network connectivity issues");
            $this->warn("- Image URL restrictions");
            $this->warn("- CORS policies");
            $this->warn("- Image service being down");

            $this->newLine();
            $this->info("To fix this:");
            $this->info("1. Check your internet connection");
            $this->info("2. Verify the image URLs in the seeder");
            $this->info("3. Consider using local images or a different image service");
        }

        return 0;
    }

    /**
     * Test if an image URL is accessible
     */
    private function testImageUrl($url, $type)
    {
        if (empty($url)) {
            return '❌ No URL';
        }

        try {
            $response = Http::timeout(10)->head($url);

            if ($response->successful()) {
                $contentType = $response->header('Content-Type');
                if (str_contains($contentType, 'image/')) {
                    return '✅ OK';
                } else {
                    return '⚠️ Not an image (' . $contentType . ')';
                }
            } else {
                return '❌ HTTP ' . $response->status();
            }
        } catch (\Exception $e) {
            return '❌ Error: ' . $e->getMessage();
        }
    }
}
