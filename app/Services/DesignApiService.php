<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Design;

class DesignApiService
{
    private $apiKey;
    private $baseUrl = 'https://api.freepik.com/v1'; // Example base URL - adjust as needed

    public function __construct()
    {
        $this->apiKey = 'FPSX853eec3a2a8b4fe1da2d17e3e27114b3';
    }

    /**
     * Fetch designs from external API
     */
    public function fetchDesigns($params = [])
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->get($this->baseUrl . '/resources', array_merge([
                'limit' => 50,
                'page' => 1,
            ], $params));

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Design API request failed', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Design API exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return null;
        }
    }

    /**
     * Search designs by query
     */
    public function searchDesigns($query, $params = [])
    {
        return $this->fetchDesigns(array_merge([
            'q' => $query,
        ], $params));
    }

    /**
     * Get designs by category
     */
    public function getDesignsByCategory($category, $params = [])
    {
        return $this->fetchDesigns(array_merge([
            'category' => $category,
        ], $params));
    }

    /**
     * Sync designs from external API to local database
     */
    public function syncDesigns($limit = 100)
    {
        try {
            $designs = $this->fetchDesigns(['limit' => $limit]);

            if (!$designs || !isset($designs['data'])) {
                return false;
            }

            $synced = 0;
            foreach ($designs['data'] as $designData) {
                if ($this->syncDesign($designData)) {
                    $synced++;
                }
            }

            Log::info("Synced {$synced} designs from external API");
            return $synced;
        } catch (\Exception $e) {
            Log::error('Failed to sync designs', [
                'message' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Sync a single design to local database
     */
    private function syncDesign($designData)
    {
        try {
            $externalId = $designData['id'] ?? null;
            if (!$externalId) {
                return false;
            }

            $design = Design::updateOrCreate(
                ['external_id' => $externalId],
                [
                    'title' => $designData['title'] ?? 'Untitled Design',
                    'description' => $designData['description'] ?? null,
                    'image_url' => $designData['image']['source']['url'] ?? $designData['image_url'] ?? null,
                    'thumbnail_url' => $designData['image']['preview']['url'] ?? $designData['thumbnail_url'] ?? null,
                    'category' => $designData['category'] ?? null,
                    'tags' => isset($designData['tags']) ? implode(',', $designData['tags']) : null,
                    'metadata' => $designData,
                    'is_active' => true,
                ]
            );

            return $design;
        } catch (\Exception $e) {
            Log::error('Failed to sync design', [
                'external_id' => $designData['id'] ?? 'unknown',
                'message' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Get design categories
     */
    public function getCategories()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->get($this->baseUrl . '/categories');

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Failed to fetch categories', [
                'message' => $e->getMessage()
            ]);
            return null;
        }
    }
}
