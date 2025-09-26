<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Design;

class DesignApiService
{
    private $apiKey;
    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.freepik.api_key');
        $this->baseUrl = config('services.freepik.base_url', 'https://api.freepik.com/v1');
    }

    /**
     * Fetch designs from external API
     */
    public function fetchDesigns($params = [])
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'X-API-Key' => $this->apiKey, // Try API key as header
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
                'Accept' => 'application/json',
                'X-API-Key' => $this->apiKey, // Try API key as header
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

    /**
     * Fetch designs directly from external API (without syncing to database)
     */
    public function fetchExternalDesigns($params = [])
    {
        if (!$this->apiKey) {
            Log::error('No Freepik API key configured');
            throw new \Exception('Freepik API key is required but not configured');
        }

        try {
            // Use the correct Freepik API authentication header
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'x-freepik-api-key' => $this->apiKey, // Correct Freepik API header
            ])->get($this->baseUrl . '/resources', array_merge([
                'limit' => 50,
                'page' => 1,
            ], $params));

            Log::info('Freepik API request', [
                'url' => $this->baseUrl . '/resources',
                'params' => $params,
                'status' => $response->status()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Freepik API success', ['results_count' => count($data['data'] ?? [])]);
                
                // Transform the real API response to match our expected format
                if (isset($data['data'])) {
                    $data['data'] = array_map([$this, 'transformFreepikResponse'], $data['data']);
                }
                
                return $data;
            }

            Log::error('Freepik API request failed', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            throw new \Exception('Freepik API request failed: ' . $response->status());
        } catch (\Exception $e) {
            Log::error('Freepik API exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    /**
     * Search designs directly from external API
     */
    public function searchExternalDesigns($query, $params = [])
    {
        if (!$this->apiKey) {
            Log::error('No Freepik API key configured');
            throw new \Exception('Freepik API key is required but not configured');
        }

        try {
            // Use the correct Freepik API authentication header
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'x-freepik-api-key' => $this->apiKey, // Correct Freepik API header
            ])->get($this->baseUrl . '/resources', array_merge([
                'term' => $query, // Use 'term' instead of 'q' for proper search
                'limit' => 50,
                'page' => 1,
        ], $params));

            Log::info('Freepik API search request', [
                'url' => $this->baseUrl . '/resources',
                'query' => $query,
                'params' => $params,
                'status' => $response->status()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Freepik API search success', [
                    'query' => $query,
                    'results_count' => count($data['data'] ?? [])
                ]);
                
                // Transform the real API response to match our expected format
                if (isset($data['data'])) {
                    $data['data'] = array_map([$this, 'transformFreepikResponse'], $data['data']);
                }
                
                return $data;
            }

            Log::error('Freepik API search failed', [
                'query' => $query,
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            throw new \Exception('Freepik API search failed: ' . $response->status());
        } catch (\Exception $e) {
            Log::error('Freepik API search exception', [
                'query' => $query,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    /**
     * Get designs by category directly from external API
     */
    public function getExternalDesignsByCategory($category, $params = [])
    {
        if (!$this->apiKey) {
            Log::error('No Freepik API key configured');
            throw new \Exception('Freepik API key is required but not configured');
        }

        try {
            // Use the correct Freepik API authentication header
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'x-freepik-api-key' => $this->apiKey, // Correct Freepik API header
            ])->get($this->baseUrl . '/resources', array_merge([
            'category' => $category,
                'limit' => 50,
                'page' => 1,
        ], $params));

            Log::info('Freepik API category request', [
                'url' => $this->baseUrl . '/resources',
                'category' => $category,
                'params' => $params,
                'status' => $response->status()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Freepik API category success', [
                    'category' => $category,
                    'results_count' => count($data['data'] ?? [])
                ]);
                
                // Transform the real API response to match our expected format
                if (isset($data['data'])) {
                    $data['data'] = array_map([$this, 'transformFreepikResponse'], $data['data']);
                }
                
                return $data;
            }

            Log::error('Freepik API category failed', [
                'category' => $category,
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            throw new \Exception('Freepik API category request failed: ' . $response->status());
        } catch (\Exception $e) {
            Log::error('Freepik API category exception', [
                'category' => $category,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    /**
     * Get external API categories
     */
    public function getExternalCategories()
    {
        if (!$this->apiKey) {
            Log::error('No Freepik API key configured');
            throw new \Exception('Freepik API key is required but not configured');
        }

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'x-freepik-api-key' => $this->apiKey,
            ])->get($this->baseUrl . '/categories');

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('External Categories API request failed', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            throw new \Exception('Freepik Categories API request failed: ' . $response->status());
        } catch (\Exception $e) {
            Log::error('External Categories API exception', [
                'message' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Transform Freepik API response to our expected format
     */
    private function transformFreepikResponse($item)
    {
        return [
            'id' => $item['id'] ?? 'unknown',
            'title' => $item['title'] ?? 'Untitled Design',
            'description' => $item['title'] ?? 'Design from Freepik',
            'image_url' => $item['image']['source']['url'] ?? null,
            'thumbnail_url' => $item['image']['source']['url'] ?? null, // Use same URL for now
            'category' => $item['image']['type'] ?? 'general',
            'type' => $item['image']['type'] ?? 'vector',
            'orientation' => $item['image']['orientation'] ?? 'square',
            'width' => $item['image']['source']['size'] ? explode('x', $item['image']['source']['size'])[0] : 800,
            'height' => $item['image']['source']['size'] ? explode('x', $item['image']['source']['size'])[1] : 600,
            'tags' => ['freepik', $item['image']['type'] ?? 'vector'],
            'featured' => false,
            'price' => 0, // Free designs
            'downloads' => $item['stats']['downloads'] ?? 0,
            'rating' => 4.5, // Default rating
            'author' => $item['author']['name'] ?? 'Unknown',
            'freepik_url' => $item['url'] ?? null,
            'licenses' => $item['licenses'] ?? []
        ];
    }

}
