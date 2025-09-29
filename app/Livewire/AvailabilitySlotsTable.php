<?php

namespace App\Livewire;

use App\Models\AvailabilitySlot;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class AvailabilitySlotsTable extends Component
{
    use WithPagination;

    public $search = '';
    public $day_filter = '';
    public $status_filter = '';
    protected $queryString = ['search', 'day_filter', 'status_filter'];
    protected string $paginationTheme = 'tailwind';

    // reset pagination when search changes
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedDayFilter()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public $deleteSlotId = null;
    public $showDeleteModal = false;

    public function confirmDelete($slotId)
    {
        // Reset any previous state
        $this->closeModal();

        $this->deleteSlotId = $slotId;
        $this->showDeleteModal = true;
    }

    public function deleteSlot()
    {
        try {
            if ($this->deleteSlotId) {
                $slot = AvailabilitySlot::findOrFail($this->deleteSlotId);
                $slot->delete();

                session()->flash('message', trans('availability.slot_deleted_successfully'));
            }
        } catch (\Exception $e) {
            session()->flash('error', trans('availability.delete_error'));
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
        $this->deleteSlotId = null;
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
        $slots = AvailabilitySlot::query()
            ->when($this->search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('notes', 'like', "%{$search}%")
                        ->orWhere('day_of_week', 'like', "%{$search}%");
                });
            })
            ->when($this->day_filter, function ($query, $day) {
                $query->where('day_of_week', $day);
            })
            ->when($this->status_filter, function ($query, $status) {
                if ($status === 'active') {
                    $query->where('is_active', true);
                } elseif ($status === 'inactive') {
                    $query->where('is_active', false);
                }
            })
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->paginate(10);

        return view('livewire.availability-slots-table', [
            'slots' => $slots,
        ]);
    }
}
