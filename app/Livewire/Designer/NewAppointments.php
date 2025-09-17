<?php

namespace App\Livewire\Designer;

use App\Models\Appointment;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NewAppointments extends Component
{
    public $availableAppointments;
    public $availableCount;
    public $lastUpdated;

    protected $listeners = [
        'appointmentClaimed' => 'refreshAppointments',
        'appointmentPassed' => 'refreshAppointments',
        'newAppointmentCreated' => 'refreshAppointments'
    ];

    public function mount()
    {
        $this->loadAppointments();
        $this->lastUpdated = now();
    }

    public function loadAppointments()
    {
        $this->availableAppointments = Appointment::whereNull('designer_id')
            ->where('status', 'pending')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $this->availableCount = Appointment::whereNull('designer_id')
            ->where('status', 'pending')
            ->count();
            
        $this->lastUpdated = now();
    }

    public function claimAppointment($appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);
        
        // Check if appointment is still available
        if ($appointment->designer_id !== null) {
            $this->dispatch('appointment-already-claimed');
            return;
        }

        // Assign to current designer using proper method
        $appointment->assignToDesigner(Auth::guard('designer')->id());

        // Refresh the appointments list
        $this->loadAppointments();

        // Dispatch events to all designers
        $this->dispatch('appointment-claimed', $appointmentId)->to('designer.new-appointments');
        $this->dispatch('appointment-accepted', $appointmentId);
        
        // Show success message
        session()->flash('success', __('dashboard.appointment_claimed_success'));
    }

    public function passAppointment($appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);
        
        // Check if appointment is still available
        if ($appointment->designer_id !== null) {
            $this->dispatch('appointment-already-claimed');
            return;
        }

        // Refresh the appointments list (just remove from our view)
        $this->loadAppointments();

        // Dispatch events to all designers
        $this->dispatch('appointment-passed', $appointmentId)->to('designer.new-appointments');
        
        // Show info message
        session()->flash('info', __('dashboard.appointment_passed_success'));
    }

    public function refreshAppointments()
    {
        $this->loadAppointments();
    }

    public function render()
    {
        return view('livewire.designer.new-appointments');
    }
}