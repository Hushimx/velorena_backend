<?php

namespace App\Livewire;

use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Carbon\Carbon;

class BookAppointment extends Component
{
    public $selectedDate;
    public $selectedTime;
    public $notes;

    protected $rules = [
        'selectedDate' => 'required|date_format:Y-m-d\TH:i|after_or_equal:now',
        'notes' => 'nullable|string|max:500',
    ];

    public function mount()
    {
        // No need to load designers since users don't select them
    }

    public function updatedSelectedDate()
    {
        if ($this->selectedDate) {
            // Extract date and time from the selected date input
            $this->extractDateTimeFromInput();
        } else {
            $this->selectedTime = null;
        }
    }

    private function extractDateTimeFromInput()
    {
        // The selectedDate field now contains the full datetime in Y-m-d\TH:i format
        // We just need to validate that it's a valid datetime
        if ($this->selectedDate) {
            $dateTime = Carbon::parse($this->selectedDate);
        }
    }



    public function bookAppointment()
    {
        $this->validate();

        // Parse the datetime and extract date and time for database storage
        $dateTime = Carbon::parse($this->selectedDate);
        $appointmentDate = $dateTime->format('Y-m-d');
        $appointmentTime = $dateTime->format('H:i:s');

        // Create the appointment without a designer (will be assigned later)
        $appointment = Appointment::create([
            'user_id' => Auth::id(),
            'designer_id' => null, // No designer assigned yet
            'appointment_date' => $appointmentDate,
            'appointment_time' => $appointmentTime,
            'duration_minutes' => 15,
            'status' => 'pending',
            'notes' => $this->notes,
        ]);

        // Reset form
        $this->reset(['selectedDate', 'selectedTime', 'notes']);

        // Dispatch event to notify other components about the new appointment
        $this->dispatch('appointment-created', $appointment->id);

        // Show success message and redirect
        session()->flash('success', trans('dashboard.appointment_booked_success'));

        return redirect()->route('appointments.index');
    }

    public function getMinDateProperty()
    {
        return now()->addMinutes(1)->format('Y-m-d\TH:i');
    }

    public function getMaxDateProperty()
    {
        return now()->addMonths(3)->format('Y-m-d\TH:i');
    }

    public function render()
    {
        return view('livewire.book-appointment');
    }
}
