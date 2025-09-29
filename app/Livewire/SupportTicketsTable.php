<?php

namespace App\Livewire;

use App\Models\SupportTicket;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class SupportTicketsTable extends Component
{
    use WithPagination;

    public $search = '';
    public $status_filter = '';
    public $priority_filter = '';
    protected $queryString = ['search', 'status_filter', 'priority_filter'];
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

    public $deleteTicketId = null;
    public $showDeleteModal = false;

    public function confirmDelete($ticketId)
    {
        // Reset any previous state
        $this->closeModal();

        $this->deleteTicketId = $ticketId;
        $this->showDeleteModal = true;
    }

    public function deleteTicket()
    {
        try {
            if ($this->deleteTicketId) {
                $ticket = SupportTicket::findOrFail($this->deleteTicketId);

                // Check if ticket can be deleted (only open tickets)
                if (!$ticket->isOpen()) {
                    session()->flash('error', trans('support.cannot_delete_non_open_ticket'));
                    $this->closeModal();
                    return;
                }

                $ticket->delete();

                session()->flash('message', trans('support.ticket_deleted_successfully'));
            }
        } catch (\Exception $e) {
            session()->flash('error', trans('support.delete_error'));
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
        $this->deleteTicketId = null;
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
        $tickets = SupportTicket::query()
            ->with(['user', 'assignedAdmin'])
            ->when($this->search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('ticket_number', 'like', "%{$search}%")
                        ->orWhere('subject', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->when($this->status_filter, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($this->priority_filter, function ($query, $priority) {
                $query->where('priority', $priority);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.support-tickets-table', [
            'tickets' => $tickets,
        ]);
    }
}
