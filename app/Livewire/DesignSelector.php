<?php

namespace App\Livewire;

use App\Models\Design;
use App\Services\DesignApiService;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class DesignSelector extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedCategory = '';
    public $selectedDesigns = [];
    public $designNotes = [];
    public $isLoading = false;
    public $showDesignModal = false;
    public $selectedDesignForModal = null;
    public $apiDesigns = [];
    public $useApiDesigns = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedCategory' => ['except' => ''],
    ];

    protected $listeners = [
        'refreshDesigns' => '$refresh',
        'clearSelection' => 'clearSelection'
    ];

    public function mount()
    {
        $this->selectedDesigns = [];
        $this->designNotes = [];
        
        // Don't load designs automatically, wait for user to click search
        $this->apiDesigns = [];
    }

    public function updatedSearch()
    {
        // Don't auto-search, wait for button click
        // Just reset page when search text changes
        $this->resetPage();
    }

    public function updatedSelectedCategory()
    {
        // Don't auto-search, wait for button click
        // Just reset page when category changes
        $this->resetPage();
    }

    public function searchDesignsFromApi()
    {
        $this->isLoading = true;
        $this->useApiDesigns = true;
        $this->apiDesigns = []; // Clear previous results immediately

        try {
            $apiService = new DesignApiService();
            $searchQuery = trim($this->search);
            $category = trim($this->selectedCategory);

            Log::info('Searching designs (fresh search):', [
                'search' => $searchQuery,
                'category' => $category,
                'timestamp' => now()
            ]);

            if ($searchQuery) {
                // Search designs from Freepik API with search query
                $result = $apiService->searchExternalDesigns($searchQuery, ['limit' => 20]);
                Log::info('Search result for query "' . $searchQuery . '":', ['found' => $result ? count($result['data'] ?? []) : 0]);
            } elseif ($category) {
                // Get designs by category from Freepik API
                $result = $apiService->getExternalDesignsByCategory($category, ['limit' => 20]);
                Log::info('Category result for "' . $category . '":', ['found' => $result ? count($result['data'] ?? []) : 0]);
            } else {
                // Get general designs from Freepik API
                $result = $apiService->fetchExternalDesigns(['limit' => 20]);
                Log::info('General designs result:', ['found' => $result ? count($result['data'] ?? []) : 0]);
            }

            if ($result && isset($result['data']) && !empty($result['data'])) {
                $this->apiDesigns = $result['data'];
                Log::info('API designs loaded successfully (fresh):', [
                    'count' => count($this->apiDesigns),
                    'search' => $searchQuery,
                    'category' => $category,
                    'timestamp' => now()
                ]);
            } else {
                $this->apiDesigns = [];
                Log::warning('No designs found from API', [
                    'search' => $searchQuery,
                    'category' => $category
                ]);
                session()->flash('warning', 'No designs found for your search criteria.');
            }
        } catch (\Exception $e) {
            Log::error('Failed to search designs from Freepik API: ' . $e->getMessage());
            $this->apiDesigns = [];
            session()->flash('error', 'Failed to search designs from API: ' . $e->getMessage());
        } finally {
            $this->isLoading = false;
        }
    }

    public function toggleDesignSelection($designId)
    {
        Log::info('toggleDesignSelection called:', ['designId' => $designId, 'currentSelection' => $this->selectedDesigns]);

        if (in_array($designId, $this->selectedDesigns)) {
            $this->selectedDesigns = array_diff($this->selectedDesigns, [$designId]);
            unset($this->designNotes[$designId]);
            // Emit event to parent to remove design
            $this->dispatch('design-removed', designId: $designId);
            Log::info('Design removed, dispatching design-removed event:', ['designId' => $designId]);
        } else {
            $this->selectedDesigns[] = $designId;
            $this->designNotes[$designId] = '';
            // Emit event to parent to add design
            $this->dispatch('design-added', designId: $designId, notes: '');
            Log::info('Design added, dispatching design-added event:', ['designId' => $designId]);
        }

        Log::info('Updated selection:', ['selectedDesigns' => $this->selectedDesigns, 'designNotes' => $this->designNotes]);
    }

    public function addToCart($designId)
    {
        Log::info('addToCart called:', ['designId' => $designId]);
        
        // Find the design data
        $designData = null;
        if ($this->useApiDesigns) {
            $designData = collect($this->apiDesigns)->firstWhere('id', $designId);
        } else {
            $design = Design::find($designId);
            if ($design) {
                $designData = [
                    'id' => $design->id,
                    'title' => $design->title,
                    'image_url' => $design->image_url,
                    'thumbnail_url' => $design->thumbnail_url,
                    'category' => $design->category
                ];
            }
        }

        if ($designData) {
            // Create cart design data
            $cartDesignData = [
                'title' => $designData['title'] ?? 'Selected Design',
                'designData' => ['selectedDesign' => $designData],
                'imageUrl' => $designData['image_url'] ?? $designData['thumbnail_url']
            ];

            // Emit event to save to cart
            $this->dispatch('save-cart-design', $cartDesignData);
            
            // Close modal
            $this->dispatch('close-cart-design-modal');
            
            session()->flash('success', 'تم إضافة التصميم إلى السلة!');
        }
    }

    public function updateDesignNote($designId, $note)
    {
        $this->designNotes[$designId] = $note;
        // Emit event to parent to update notes
        $this->dispatch('design-note-updated', designId: $designId, notes: $note);
    }

    public function removeDesign($designId)
    {
        $this->selectedDesigns = array_diff($this->selectedDesigns, [$designId]);
        unset($this->designNotes[$designId]);
        // Emit event to parent to remove design
        $this->dispatch('design-removed', designId: $designId);
    }

    public function clearSelection()
    {
        $this->selectedDesigns = [];
        $this->designNotes = [];
        // Emit event to parent to clear all designs
        $this->dispatch('designs-cleared');
    }

    public function openDesignModal($designId)
    {
        if ($this->useApiDesigns) {
            // Find design in API designs
            $this->selectedDesignForModal = collect($this->apiDesigns)->firstWhere('id', $designId);
        } else {
            // Find design in database
            $this->selectedDesignForModal = Design::find($designId);
        }
        $this->showDesignModal = true;
        
        // Emit event for JavaScript to initialize the design studio
        $this->dispatch('design-modal-opened');
    }

    public function closeDesignModal()
    {
        $this->showDesignModal = false;
        $this->selectedDesignForModal = null;
        
        // Emit event to parent (ShoppingCart) to close the cart design modal
        $this->dispatch('close-cart-design-modal');
    }

    public function getSelectedDesignsData()
    {
        if ($this->useApiDesigns) {
            // Get selected designs from API designs
            return collect($this->apiDesigns)
                ->whereIn('id', $this->selectedDesigns)
                ->map(function ($design) {
                    return [
                        'id' => $design['id'],
                        'title' => $design['title'],
                        'image_url' => $design['image_url'],
                        'thumbnail_url' => $design['thumbnail_url'],
                        'notes' => $this->designNotes[$design['id']] ?? '',
                    ];
                })
                ->values();
        } else {
            // Get selected designs from database
            return Design::whereIn('id', $this->selectedDesigns)->get()->map(function ($design) {
                return [
                    'id' => $design->id,
                    'title' => $design->title,
                    'image_url' => $design->image_url,
                    'thumbnail_url' => $design->thumbnail_url,
                    'notes' => $this->designNotes[$design->id] ?? '',
                ];
            });
        }
    }

    public function getSelectedDesignsCount()
    {
        return count($this->selectedDesigns);
    }

    public function performSearch()
    {
        $this->resetPage();
        $this->useApiDesigns = true;
        $this->apiDesigns = []; // Clear previous results
        $this->searchDesignsFromApi();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->selectedCategory = '';
        $this->resetPage();
        $this->useApiDesigns = true;
        $this->apiDesigns = []; // Clear previous results
        $this->searchDesignsFromApi();
    }


    public function render()
    {
        // If we have search or category, use API designs
        if ($this->search || $this->selectedCategory) {
            // Always search when we have search/category to ensure fresh results
            if (empty($this->apiDesigns) || $this->isLoading) {
                // Don't call searchDesignsFromApi here to avoid infinite loops
                // The search should be triggered by user actions
            }
            
            // Create a paginated collection from API designs
            $designs = collect($this->apiDesigns);
            $perPage = 20;
            $currentPage = $this->getPage();
            $offset = ($currentPage - 1) * $perPage;
            $paginatedDesigns = $designs->slice($offset, $perPage)->values();
            
            // Create a custom paginator
            $designs = new \Illuminate\Pagination\LengthAwarePaginator(
                $paginatedDesigns,
                $designs->count(),
                $perPage,
                $currentPage,
                ['path' => request()->url(), 'pageName' => 'page']
            );
            
            $categories = ['business', 'technology', 'nature', 'design', 'creative'];
        } else {
            // Use database designs when no search/category
            $this->useApiDesigns = false;
            $query = Design::active();
            $designs = $query->orderBy('created_at', 'desc')->paginate(20);
            
            $categories = Design::active()
                ->distinct()
                ->pluck('category')
                ->filter()
                ->values();
        }

        return view('livewire.design-selector', [
            'designs' => $designs,
            'categories' => $categories,
            'useApiDesigns' => $this->useApiDesigns,
        ]);
    }
}
