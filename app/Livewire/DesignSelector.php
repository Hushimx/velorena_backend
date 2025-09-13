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
    }

    public function updatedSearch()
    {
        $this->resetPage();
        $this->syncDesignsFromApi();
    }

    public function updatedSelectedCategory()
    {
        $this->resetPage();
        $this->syncDesignsFromApi();
    }

    public function syncDesignsFromApi()
    {
        $this->isLoading = true;

        try {
            $apiService = new DesignApiService();

            if ($this->search) {
                $apiService->searchDesigns($this->search);
            } elseif ($this->selectedCategory) {
                $apiService->getDesignsByCategory($this->selectedCategory);
            } else {
                $apiService->syncDesigns(50);
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to sync designs from API: ' . $e->getMessage());
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
        $this->selectedDesignForModal = Design::find($designId);
        $this->showDesignModal = true;
    }

    public function closeDesignModal()
    {
        $this->showDesignModal = false;
        $this->selectedDesignForModal = null;
    }

    public function getSelectedDesignsData()
    {
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

    public function getSelectedDesignsCount()
    {
        return count($this->selectedDesigns);
    }


    public function render()
    {
        $query = Design::active();

        if ($this->search) {
            $query->search($this->search);
        }

        if ($this->selectedCategory) {
            $query->byCategory($this->selectedCategory);
        }

        $designs = $query->orderBy('created_at', 'desc')
            ->paginate(20);

        $categories = Design::active()
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values();

        return view('livewire.design-selector', [
            'designs' => $designs,
            'categories' => $categories,
        ]);
    }
}
