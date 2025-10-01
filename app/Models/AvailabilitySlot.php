<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AvailabilitySlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'day_of_week',
        'start_time',
        'end_time',
        'slot_duration_minutes',
        'is_active',
        'notes'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForDay($query, $dayOfWeek)
    {
        return $query->where('day_of_week', $dayOfWeek);
    }

    // Helper methods
    public function getFormattedStartTimeAttribute()
    {
        return Carbon::parse($this->start_time)->format('H:i');
    }

    public function getFormattedEndTimeAttribute()
    {
        return Carbon::parse($this->end_time)->format('H:i');
    }

    public function getDurationInMinutesAttribute()
    {
        $start = Carbon::parse($this->start_time);
        $end = Carbon::parse($this->end_time);
        return $end->diffInMinutes($start, false);
    }

    public function getTotalSlotsAttribute()
    {
        return floor($this->duration_in_minutes / $this->slot_duration_minutes);
    }

    // Generate time slots for this availability
    public function generateTimeSlots()
    {
        $slots = [];
        $currentTime = Carbon::parse($this->start_time);
        $endTime = Carbon::parse($this->end_time);

        while ($currentTime->lt($endTime)) {
            $slots[] = $currentTime->format('H:i');
            $currentTime->addMinutes($this->slot_duration_minutes);
        }

        return $slots;
    }

    // Check if a specific time is within this availability
    public function containsTime($time)
    {
        $checkTime = Carbon::parse($time);
        $startTime = Carbon::parse($this->start_time);
        $endTime = Carbon::parse($this->end_time);

        return $checkTime->between($startTime, $endTime);
    }

    // Static method to get available time slots for a specific date
    public static function getAvailableTimeSlotsForDate($date)
    {
        $dayOfWeek = strtolower(Carbon::parse($date)->format('l'));
        
        $availabilitySlots = self::active()
            ->forDay($dayOfWeek)
            ->orderBy('start_time')
            ->get();

        $allSlots = [];
        foreach ($availabilitySlots as $slot) {
            $allSlots = array_merge($allSlots, $slot->generateTimeSlots());
        }

        // Remove duplicates and sort
        $allSlots = array_unique($allSlots);
        sort($allSlots);

        return $allSlots;
    }

    // Get available slots excluding booked appointments
    public static function getAvailableTimeSlotsExcludingBooked($date)
    {
        $availableSlots = self::getAvailableTimeSlotsForDate($date);
        
        // Get booked appointments for this date (any designer)
        $bookedAppointments = Appointment::where('appointment_date', $date)
            ->whereIn('status', ['pending', 'accepted'])
            ->get();

        $bookedTimes = [];
        foreach ($bookedAppointments as $appointment) {
            $appointmentTime = Carbon::parse($appointment->appointment_time)->format('H:i');
            $duration = $appointment->duration_minutes;
            
            // Add all time slots that this appointment occupies
            $currentTime = Carbon::parse($appointmentTime);
            for ($i = 0; $i < $duration; $i += 15) { // Assuming 15-minute slots
                $bookedTimes[] = $currentTime->format('H:i');
                $currentTime->addMinutes(15);
            }
        }

        // Remove booked times from available slots
        return array_diff($availableSlots, $bookedTimes);
    }
}