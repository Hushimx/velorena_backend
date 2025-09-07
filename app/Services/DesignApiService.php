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
        $this->apiKey = config('services.freepik.api_key', 'FPSX853eec3a2a8b4fe1da2d17e3e27114b3');
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
        try {
            // Try different authentication methods for FreeAPI
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'API-Key ' . $this->apiKey, // Try API-Key format
            ])->get($this->baseUrl . '/resources', array_merge([
                'limit' => 50,
                'page' => 1,
            ], $params));

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('External Design API request failed', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            // Return mock data for testing
            return $this->getMockDesignData($params);
        } catch (\Exception $e) {
            Log::error('External Design API exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return mock data for testing
            return $this->getMockDesignData($params);
        }
    }

    /**
     * Search designs directly from external API
     */
    public function searchExternalDesigns($query, $params = [])
    {
        return $this->fetchExternalDesigns(array_merge([
            'q' => $query,
        ], $params));
    }

    /**
     * Get designs by category directly from external API
     */
    public function getExternalDesignsByCategory($category, $params = [])
    {
        return $this->fetchExternalDesigns(array_merge([
            'category' => $category,
        ], $params));
    }

    /**
     * Get external API categories
     */
    public function getExternalCategories()
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'API-Key ' . $this->apiKey, // Try API-Key format
            ])->get($this->baseUrl . '/categories');

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('External Categories API request failed', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            // Return mock data for testing
            return [
                'data' => [
                    ['id' => 'business', 'name' => 'Business', 'description' => 'Business designs', 'count' => 1000],
                    ['id' => 'technology', 'name' => 'Technology', 'description' => 'Tech designs', 'count' => 800],
                    ['id' => 'nature', 'name' => 'Nature', 'description' => 'Nature designs', 'count' => 1200],
                ]
            ];
        } catch (\Exception $e) {
            Log::error('External Categories API exception', [
                'message' => $e->getMessage()
            ]);

            // Return mock data for testing
            return [
                'data' => [
                    ['id' => 'business', 'name' => 'Business', 'description' => 'Business designs', 'count' => 1000],
                    ['id' => 'technology', 'name' => 'Technology', 'description' => 'Tech designs', 'count' => 800],
                    ['id' => 'nature', 'name' => 'Nature', 'description' => 'Nature designs', 'count' => 1200],
                ]
            ];
        }
    }

    /**
     * Generate mock design data for testing
     */
    private function getMockDesignData($params = [])
    {
        $query = $params['q'] ?? 'design';
        $category = $params['category'] ?? 'general';
        $limit = $params['limit'] ?? 10;
        $page = $params['page'] ?? 1;

        $mockDesigns = [
            [
                'id' => 'mock-001',
                'title' => ucfirst($query) . ' Design Template',
                'description' => 'Beautiful ' . $query . ' design template perfect for your projects',
                'image_url' => 'https://via.placeholder.com/800x600/007bff/ffffff?text=' . urlencode($query),
                'thumbnail_url' => 'https://via.placeholder.com/300x200/007bff/ffffff?text=' . urlencode($query),
                'category' => $category,
                'type' => 'vector',
                'orientation' => 'horizontal',
                'width' => 800,
                'height' => 600,
                'tags' => [$query, $category, 'template', 'design'],
                'featured' => false
            ],
            [
                'id' => 'mock-002',
                'title' => 'Modern ' . ucfirst($query) . ' Layout',
                'description' => 'Clean and modern ' . $query . ' layout design',
                'image_url' => 'https://via.placeholder.com/800x600/28a745/ffffff?text=Modern+' . urlencode($query),
                'thumbnail_url' => 'https://via.placeholder.com/300x200/28a745/ffffff?text=Modern+' . urlencode($query),
                'category' => $category,
                'type' => 'photo',
                'orientation' => 'vertical',
                'width' => 600,
                'height' => 800,
                'tags' => [$query, 'modern', 'layout', 'clean'],
                'featured' => true
            ],
            [
                'id' => 'mock-003',
                'title' => 'Creative ' . ucfirst($query) . ' Concept',
                'description' => 'Innovative and creative ' . $query . ' concept design',
                'image_url' => 'https://via.placeholder.com/800x600/dc3545/ffffff?text=Creative+' . urlencode($query),
                'thumbnail_url' => 'https://via.placeholder.com/300x200/dc3545/ffffff?text=Creative+' . urlencode($query),
                'category' => $category,
                'type' => 'vector',
                'orientation' => 'square',
                'width' => 500,
                'height' => 500,
                'tags' => [$query, 'creative', 'concept', 'innovative'],
                'featured' => false
            ],
            [
                'id' => 'mock-004',
                'title' => 'Professional ' . ucfirst($query) . ' Style',
                'description' => 'Professional grade ' . $query . ' style design',
                'image_url' => 'https://via.placeholder.com/800x600/6f42c1/ffffff?text=Professional+' . urlencode($query),
                'thumbnail_url' => 'https://via.placeholder.com/300x200/6f42c1/ffffff?text=Professional+' . urlencode($query),
                'category' => $category,
                'type' => 'photo',
                'orientation' => 'horizontal',
                'width' => 1200,
                'height' => 800,
                'tags' => [$query, 'professional', 'business', 'corporate'],
                'featured' => true
            ],
            [
                'id' => 'mock-005',
                'title' => 'Minimalist ' . ucfirst($query) . ' Design',
                'description' => 'Simple and elegant minimalist ' . $query . ' design',
                'image_url' => 'https://via.placeholder.com/800x600/20c997/ffffff?text=Minimalist+' . urlencode($query),
                'thumbnail_url' => 'https://via.placeholder.com/300x200/20c997/ffffff?text=Minimalist+' . urlencode($query),
                'category' => $category,
                'type' => 'vector',
                'orientation' => 'vertical',
                'width' => 400,
                'height' => 600,
                'tags' => [$query, 'minimalist', 'simple', 'elegant'],
                'featured' => false
            ]
        ];

        // Limit the results based on the limit parameter
        $limitedDesigns = array_slice($mockDesigns, 0, min($limit, count($mockDesigns)));

        return [
            'data' => $limitedDesigns,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => ceil(count($mockDesigns) / $limit),
                'total_results' => count($mockDesigns),
                'per_page' => $limit
            ]
        ];
    }
}
