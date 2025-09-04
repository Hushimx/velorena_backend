<?php

namespace App\Livewire;

use App\Models\Marketer;
use Livewire\Component;
use Livewire\WithPagination;

class MarketersTable extends Component
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

    public $deleteMarketerId = null;
    public $showDeleteModal = false;

    public function confirmDelete($marketerId)
    {
        // Reset any previous state
        $this->closeModal();

        $this->deleteMarketerId = $marketerId;
        $this->showDeleteModal = true;
    }

    public function deleteMarketer()
    {
        try {
            if ($this->deleteMarketerId) {
                $marketer = Marketer::findOrFail($this->deleteMarketerId);
                
                // Check if marketer has leads
                if ($marketer->leads()->count() > 0) {
                    session()->flash('error', 'لا يمكن حذف المسوق لأنه لديه leads مسندة إليه');
                    $this->closeModal();
                    return;
                }
                
                $marketer->delete();
                session()->flash('message', 'تم حذف المسوق بنجاح');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'حدث خطأ أثناء حذف المسوق');
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
        $this->deleteMarketerId = null;
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
        $marketers = Marketer::query()
            ->when($this->search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->withCount('leads')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.marketers-table', [
            'marketers' => $marketers,
        ]);
    }
}
