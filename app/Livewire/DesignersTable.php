<?php

namespace App\Livewire;

use App\Models\Designer;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class DesignersTable extends Component
{
    use WithPagination;

    public $search = '';
    protected $queryString = ['search'];
    protected string $paginationTheme = 'tailwind';

    // reset pagination when search changes
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public $deleteDesignerId = null;
    public $showDeleteModal = false;

    public function confirmDelete($designerId)
    {
        // Reset any previous state
        $this->closeModal();

        $this->deleteDesignerId = $designerId;
        $this->showDeleteModal = true;
    }

    public function deleteDesigner()
    {
        try {
            if ($this->deleteDesignerId) {
                $designer = Designer::findOrFail($this->deleteDesignerId);
                $designer->delete();

                session()->flash('message', trans('designers.designer_deleted_successfully'));
            }
        } catch (\Exception $e) {
            session()->flash('error', trans('designers.delete_error'));
        }

        $this->closeModal();
        $this->resetPage(); // Reset pagination after deletion
    }

    public function cancelDelete()
    {
        $this->closeModal();
    }

    private function closeModal()
    {
        $this->deleteDesignerId = null;
        $this->showDeleteModal = false;
        $this->dispatch('modal-closed');
    }

    public function refreshComponent()
    {
        $this->resetPage();
        $this->closeModal();
    }

    public function render()
    {
        $designers = Designer::query()
            ->when($this->search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.designers-table', [
            'designers' => $designers,
        ]);
    }
}
