<?php

namespace App\Livewire;

use App\Models\Appointment;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class AppointmentsTable extends Component
{
    use WithPagination;

    public $search = '';
    public $status_filter = '';
    protected $queryString = ['search', 'status_filter'];
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

    public $deleteAppointmentId = null;
    public $showDeleteModal = false;

    public function confirmDelete($appointmentId)
    {
        // Reset any previous state
        $this->closeModal();

        $this->deleteAppointmentId = $appointmentId;
        $this->showDeleteModal = true;
    }

    public function deleteAppointment()
    {
        try {
            if ($this->deleteAppointmentId) {
                $appointment = Appointment::findOrFail($this->deleteAppointmentId);

                // Check if appointment can be deleted (only pending appointments)
                if (!$appointment->isPending()) {
                    session()->flash('error', trans('appointments.cannot_delete_non_pending_appointment'));
                    $this->closeModal();
                    return;
                }

                $appointment->delete();

                session()->flash('message', trans('appointments.appointment_deleted_successfully'));
            }
        } catch (\Exception $e) {
            session()->flash('error', trans('appointments.delete_error'));
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
        $this->deleteAppointmentId = null;
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
        $appointments = Appointment::query()
            ->with(['user', 'designer', 'order'])
            ->when($this->search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('id', 'like', "%{$search}%")
                        ->orWhere('notes', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        })
                        ->orWhereHas('designer', function ($designerQuery) use ($search) {
                            $designerQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->when($this->status_filter, function ($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(10);

        return view('livewire.appointments-table', [
            'appointments' => $appointments,
        ]);
    }
}
