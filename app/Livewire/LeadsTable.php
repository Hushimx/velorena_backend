<?php

namespace App\Livewire;

use App\Models\Lead;
use App\Models\Marketer;
use Livewire\Component;
use Livewire\WithPagination;

class LeadsTable extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $priorityFilter = '';
    public $marketerFilter = '';
    
    protected $queryString = ['search', 'statusFilter', 'priorityFilter', 'marketerFilter'];
    protected string $paginationTheme = 'tailwind';

    // reset pagination when search changes
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedPriorityFilter()
    {
        $this->resetPage();
    }

    public function updatedMarketerFilter()
    {
        $this->resetPage();
    }

    public $deleteLeadId = null;
    public $showDeleteModal = false;

    public function confirmDelete($leadId)
    {
        // Reset any previous state
        $this->closeModal();

        $this->deleteLeadId = $leadId;
        $this->showDeleteModal = true;
    }

    public function deleteLead()
    {
        try {
            if ($this->deleteLeadId) {
                $lead = Lead::findOrFail($this->deleteLeadId);
                $lead->delete();
                session()->flash('message', 'تم حذف الـ lead بنجاح');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'حدث خطأ أثناء حذف الـ lead');
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
        $this->deleteLeadId = null;
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
        $marketers = Marketer::where('is_active', true)->get();
        
        $leads = Lead::query()
            ->with('marketer')
            ->when($this->search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('company_name', 'like', "%{$search}%")
                        ->orWhere('contact_person', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($this->statusFilter, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($this->priorityFilter, function ($query, $priority) {
                $query->where('priority', $priority);
            })
            ->when($this->marketerFilter, function ($query, $marketerId) {
                $query->where('marketer_id', $marketerId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.leads-table', [
            'leads' => $leads,
            'marketers' => $marketers,
        ]);
    }
}
