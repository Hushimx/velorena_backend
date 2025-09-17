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
            Log::warning('No Freepik API key configured, using mock data');
            return $this->getMockDesignData($params);
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

            // Return mock data as fallback
            return $this->getMockDesignData($params);
        } catch (\Exception $e) {
            Log::error('Freepik API exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return mock data as fallback
            return $this->getMockDesignData($params);
        }
    }

    /**
     * Search designs directly from external API
     */
    public function searchExternalDesigns($query, $params = [])
    {
        if (!$this->apiKey) {
            Log::warning('No Freepik API key configured, using mock data for search');
            return $this->getMockDesignData(array_merge(['q' => $query], $params));
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

            // Return mock data as fallback
            return $this->getMockDesignData(array_merge(['q' => $query], $params));
        } catch (\Exception $e) {
            Log::error('Freepik API search exception', [
                'query' => $query,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return mock data as fallback
            return $this->getMockDesignData(array_merge(['q' => $query], $params));
        }
    }

    /**
     * Get designs by category directly from external API
     */
    public function getExternalDesignsByCategory($category, $params = [])
    {
        if (!$this->apiKey) {
            Log::warning('No Freepik API key configured, using mock data for category');
            return $this->getMockDesignData(array_merge(['category' => $category], $params));
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

            // Return mock data as fallback
            return $this->getMockDesignData(array_merge(['category' => $category], $params));
        } catch (\Exception $e) {
            Log::error('Freepik API category exception', [
                'category' => $category,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return mock data as fallback
            return $this->getMockDesignData(array_merge(['category' => $category], $params));
        }
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

    /**
     * Generate mock design data for testing
     */
    private function getMockDesignData($params = [])
    {
        $query = $params['q'] ?? 'design';
        $category = $params['category'] ?? 'general';
        $limit = $params['limit'] ?? 10;
        $page = $params['page'] ?? 1;

        // Real design images from Unsplash for better demo
        $designImages = [
            'business' => [
                'https://images.unsplash.com/photo-1551434678-e076c223a692?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1559136555-9303baea8ebd?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1553877522-43269d4ea984?w=800&h=600&fit=crop'
            ],
            'technology' => [
                'https://images.unsplash.com/photo-1518709268805-4e9042af2176?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1518709268805-4e9042af2176?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1518709268805-4e9042af2176?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1518709268805-4e9042af2176?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1518709268805-4e9042af2176?w=800&h=600&fit=crop'
            ],
            'nature' => [
                'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop'
            ],
            'gaming' => [
                'https://images.unsplash.com/photo-1493711662062-fa541adb3fc8?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1511512578047-dfb367046420?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1493711662062-fa541adb3fc8?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1511512578047-dfb367046420?w=800&h=600&fit=crop'
            ],
            'food' => [
                'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=800&h=600&fit=crop',
                'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=800&h=600&fit=crop'
            ]
        ];

        // Generate dynamic titles and descriptions based on search query
        $searchTerm = strtolower($query);
        
        // Select appropriate images based on search term
        $imageCategory = 'business'; // default
        if (strpos($searchTerm, 'gaming') !== false) {
            $imageCategory = 'gaming';
        } elseif (strpos($searchTerm, 'tech') !== false || strpos($searchTerm, 'technology') !== false) {
            $imageCategory = 'technology';
        } elseif (strpos($searchTerm, 'nature') !== false) {
            $imageCategory = 'nature';
        } elseif (strpos($searchTerm, 'food') !== false || strpos($searchTerm, 'restaurant') !== false) {
            $imageCategory = 'food';
        } elseif ($category && isset($designImages[$category])) {
            $imageCategory = $category;
        }
        
        $images = $designImages[$imageCategory] ?? $designImages['business'];
        $titles = [];
        $descriptions = [];
        
        if (strpos($searchTerm, 'gaming') !== false) {
            $titles = [
                'Gaming Setup Design',
                'Pro Gaming Station Layout', 
                'Gaming Room Concept',
                'Gaming Equipment Design',
                'Gaming Accessories Layout'
            ];
            $descriptions = [
                'Perfect gaming setup design for your gaming room',
                'Professional gaming station layout design',
                'Modern gaming room concept design',
                'Gaming equipment and accessories design',
                'Gaming accessories layout and organization'
            ];
        } elseif (strpos($searchTerm, 'tech') !== false || strpos($searchTerm, 'technology') !== false) {
            $titles = [
                'Technology Dashboard',
                'Tech Innovation Layout', 
                'Digital Technology Design',
                'Tech Startup Concept',
                'Modern Tech Interface'
            ];
            $descriptions = [
                'Modern technology dashboard design',
                'Tech innovation and digital layout',
                'Digital technology interface design',
                'Tech startup company concept',
                'Modern technology interface design'
            ];
        } elseif (strpos($searchTerm, 'nature') !== false) {
            $titles = [
                'Nature Landscape Design',
                'Natural Environment Layout', 
                'Eco-Friendly Concept',
                'Nature Conservation Design',
                'Green Nature Theme'
            ];
            $descriptions = [
                'Beautiful nature landscape design',
                'Natural environment layout concept',
                'Eco-friendly and sustainable design',
                'Nature conservation awareness design',
                'Green nature theme and concept'
            ];
        } elseif (strpos($searchTerm, 'food') !== false || strpos($searchTerm, 'restaurant') !== false) {
            $titles = [
                'Restaurant Menu Design',
                'Food Presentation Layout', 
                'Culinary Concept Design',
                'Food Service Design',
                'Gourmet Food Layout'
            ];
            $descriptions = [
                'Professional restaurant menu design',
                'Food presentation and layout design',
                'Culinary concept and food design',
                'Food service and restaurant design',
                'Gourmet food presentation layout'
            ];
        } else {
            // Default business designs
            $titles = [
                ucfirst($query) . ' Design Template',
                'Modern ' . ucfirst($query) . ' Layout', 
                'Creative ' . ucfirst($query) . ' Concept',
                'Professional ' . ucfirst($query) . ' Style',
                'Minimalist ' . ucfirst($query) . ' Design'
            ];
            $descriptions = [
                'Beautiful ' . $query . ' design template perfect for your projects',
                'Clean and modern ' . $query . ' layout design',
                'Innovative and creative ' . $query . ' concept design',
                'Professional grade ' . $query . ' style design',
                'Simple and elegant minimalist ' . $query . ' design'
            ];
        }

        $mockDesigns = [];
        for ($i = 0; $i < min(5, $limit); $i++) {
            $imageIndex = $i % count($images);
            $mockDesigns[] = [
                'id' => 'mock-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'title' => $titles[$i] ?? ucfirst($query) . ' Design ' . ($i + 1),
                'description' => $descriptions[$i] ?? 'Beautiful ' . $query . ' design template',
                'image_url' => $images[$imageIndex],
                'thumbnail_url' => str_replace('w=800&h=600', 'w=300&h=200', $images[$imageIndex]),
                'category' => $category,
                'type' => $i % 2 === 0 ? 'vector' : 'photo',
                'orientation' => ['horizontal', 'vertical', 'square'][$i % 3],
                'width' => [800, 600, 500, 1200, 400][$i],
                'height' => [600, 800, 500, 800, 600][$i],
                'tags' => [$query, $category, 'template', 'design', 'professional'],
                'featured' => $i % 3 === 0,
                'price' => 0, // Free designs
                'downloads' => rand(100, 5000),
                'rating' => round(4.0 + (rand(0, 20) / 10), 1)
            ];
        }

        return [
            'data' => $mockDesigns,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => ceil(count($mockDesigns) / $limit),
                'total_results' => count($mockDesigns),
                'per_page' => $limit
            ]
        ];
    }
}
