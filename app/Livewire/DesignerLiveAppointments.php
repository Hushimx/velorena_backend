<?php

namespace App\Livewire;

use App\Models\Appointment;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class DesignerLiveAppointments extends Component
{
    use WithPagination;

    public $designer;
    public $pendingCount = 0;
    public $todayCount = 0;
    public $refreshInterval = 5000; // 5 seconds

    protected $listeners = [
        'echo:appointments,AppointmentCreated' => 'refreshAppointments',
        'echo:appointments,AppointmentUpdated' => 'refreshAppointments',
        'appointmentUpdated' => 'refreshAppointments'
    ];

    public function mount()
    {
        $this->designer = Auth::guard('designer')->user();
        if (!$this->designer) {
            abort(403);
        }
        $this->updateCounts();
    }

    public function refreshAppointments()
    {
        $this->updateCounts();
        $this->dispatch('appointments-refreshed');
    }

    public function updateCounts()
    {
        $this->pendingCount = Appointment::whereNull('designer_id')
            ->pending()
            ->count();

        $this->todayCount = Appointment::where('designer_id', $this->designer->id)
            ->today()
            ->whereIn('status', ['accepted'])
            ->count();
    }

    public function acceptAppointment($appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);

        // For unassigned appointments, assign to this designer
        if ($appointment->isUnassigned()) {
            $appointment->assignToDesigner($this->designer->id);
            $this->refreshAppointments();
            $this->dispatch('appointment-accepted', $appointmentId);
            return;
        }

        // For assigned appointments, only the assigned designer can accept
        if ($appointment->designer_id !== $this->designer->id) {
            return;
        }

        if ($appointment->canBeAccepted()) {
            $appointment->accept();
            $this->refreshAppointments();
            $this->dispatch('appointment-accepted', $appointmentId);
        }
    }

    public function rejectAppointment($appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);

        if ($appointment->designer_id !== $this->designer->id) {
            return;
        }

        if ($appointment->canBeRejected()) {
            $appointment->reject();
            $this->refreshAppointments();
            $this->dispatch('appointment-rejected', $appointmentId);
        }
    }

    public function completeAppointment($appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);

        if ($appointment->designer_id !== $this->designer->id) {
            return;
        }

        if ($appointment->canBeCompleted()) {
            $appointment->complete();
            $this->refreshAppointments();
            $this->dispatch('appointment-completed', $appointmentId);
        }
    }

    public function render()
    {
        // Show unassigned appointments that any designer can claim
        $unassignedAppointments = Appointment::with(['user'])
            ->whereNull('designer_id')
            ->pending()
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->get();

        // Show this designer's accepted appointments for today
        $todayAppointments = Appointment::with(['user'])
            ->where('designer_id', $this->designer->id)
            ->today()
            ->whereIn('status', ['accepted'])
            ->orderBy('appointment_time', 'asc')
            ->get();

        // Show this designer's upcoming accepted appointments
        $upcomingAppointments = Appointment::with(['user'])
            ->where('designer_id', $this->designer->id)
            ->upcoming()
            ->whereIn('status', ['accepted'])
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->limit(10)
            ->get();

        return view('livewire.designer-live-appointments', compact(
            'unassignedAppointments',
            'todayAppointments',
            'upcomingAppointments'
        ));
    }
}
