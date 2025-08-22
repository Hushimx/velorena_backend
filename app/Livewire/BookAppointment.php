<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\Designer;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class BookAppointment extends Component
{
    public $selectedDate;
    public $selectedTime;
    public $notes;
    public $availableSlots = [];
    public $loading = false;

    protected $rules = [
        'selectedDate' => 'required|date|after:today',
        'selectedTime' => 'required|date_format:H:i',
        'notes' => 'nullable|string|max:500',
    ];

    public function mount()
    {
        // No need to load designers since users don't select them
    }

    public function updatedSelectedDate()
    {
        $this->selectedTime = null;
        $this->loadAvailableSlots();
    }

    public function loadAvailableSlots()
    {
        if (!$this->selectedDate) {
            $this->availableSlots = [];
            return;
        }

        $this->loading = true;

        // Get general available time slots for the date (no specific designer)
        $this->availableSlots = $this->getGeneralAvailableSlots($this->selectedDate);

        $this->loading = false;
    }

    private function getGeneralAvailableSlots($date)
    {
        $workingHours = [
            'start' => '09:00',
            'end' => '17:00'
        ];

        $timeSlots = [];
        $currentTime = \Carbon\Carbon::parse($workingHours['start']);
        $endTime = \Carbon\Carbon::parse($workingHours['end']);

        while ($currentTime->lt($endTime)) {
            $timeString = $currentTime->format('H:i');
            $timeSlots[] = $timeString;
            $currentTime->addMinutes(15);
        }

        return $timeSlots;
    }

    public function bookAppointment()
    {
        $this->validate();

        // Create the appointment without a designer (will be assigned later)
        $appointment = Appointment::create([
            'user_id' => Auth::id(),
            'designer_id' => null, // No designer assigned yet
            'appointment_date' => $this->selectedDate,
            'appointment_time' => $this->selectedTime,
            'duration_minutes' => 15,
            'status' => 'pending',
            'notes' => $this->notes,
        ]);

        // Reset form
        $this->reset(['selectedDate', 'selectedTime', 'notes', 'availableSlots']);

        // Show success message and redirect
        session()->flash('success', 'Appointment booked successfully! A designer will be assigned to you soon.');

        return redirect()->route('appointments.index');
    }

    public function getMinDateProperty()
    {
        return now()->addDay()->format('Y-m-d');
    }

    public function getMaxDateProperty()
    {
        return now()->addMonths(3)->format('Y-m-d');
    }

    public function render()
    {
        return view('livewire.book-appointment');
    }
}
