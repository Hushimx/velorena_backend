<?php

namespace App\Livewire;

use App\Models\Lead;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class MarketerLeadsTable extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $priorityFilter = '';
    
    protected $queryString = ['search', 'statusFilter', 'priorityFilter'];
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

    public function render()
    {
        $marketerId = Auth::guard('marketer')->id();
        
        $leads = Lead::query()
            ->where('marketer_id', $marketerId)
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
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.marketer-leads-table', [
            'leads' => $leads,
        ]);
    }
}
