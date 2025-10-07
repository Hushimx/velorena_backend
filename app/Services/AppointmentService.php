<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Designer;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppointmentService
{
    /**
     * Create a new appointment
     */
    public function createAppointment(array $data): Appointment
    {
        return DB::transaction(function () use ($data) {
            $user = Auth::user();

            // Validate appointment date is not in the past
            $this->validateAppointmentDate($data['appointment_date'], $data['appointment_time']);

            $duration = $data['duration'] ?? 30; // Default 30 minutes

            // Always check global time slot availability first
            $this->checkGlobalTimeSlotAvailability(
                $data['appointment_date'], 
                $data['appointment_time'], 
                $duration
            );

            // Check if designer is available (only if designer_id is provided)
            if (isset($data['designer_id']) && $data['designer_id']) {
                $this->checkDesignerAvailability($data['designer_id'], $data['appointment_date'], $data['appointment_time']);
            }

            // Create appointment
            $appointment = Appointment::create([
                'user_id' => $user->id,
                'designer_id' => $data['designer_id'] ?? null,
                'appointment_date' => $data['appointment_date'],
                'appointment_time' => $data['appointment_time'],
                'service_type' => $data['service_type'],
                'description' => $data['description'] ?? null,
                'duration' => $duration,
                'location' => $data['location'] ?? null,
                'notes' => $data['notes'] ?? null,
                'status' => 'pending',
                'order_id' => $data['order_id'] ?? null,
                'order_notes' => $data['order_notes'] ?? null
            ]);

            return $appointment->load(['designer', 'user', 'order']);
        });
    }

    /**
     * Update an appointment
     */
    public function updateAppointment(Appointment $appointment, array $data): Appointment
    {
        return DB::transaction(function () use ($appointment, $data) {
            // Check if appointment can be modified
            if (!$this->canModifyAppointment($appointment)) {
                throw new \Exception('Cannot modify appointment that is not pending or confirmed');
            }

            // Check availability if time-related fields are being changed
            if (isset($data['appointment_date']) || isset($data['appointment_time']) || isset($data['duration'])) {
                $appointmentDate = $data['appointment_date'] ?? $appointment->appointment_date;
                $appointmentTime = $data['appointment_time'] ?? $appointment->appointment_time;
                $duration = $data['duration'] ?? $appointment->duration ?? 30;

                // Validate appointment date is not in the past
                $this->validateAppointmentDate($appointmentDate, $appointmentTime);

                // Always check global time slot availability first
                $this->checkGlobalTimeSlotAvailability(
                    $appointmentDate, 
                    $appointmentTime, 
                    $duration, 
                    $appointment->id
                );

                // Check designer availability if designer or time is being changed
                $designerId = $data['designer_id'] ?? $appointment->designer_id;
                if ($designerId) {
                    $this->checkDesignerAvailability($designerId, $appointmentDate, $appointmentTime, $appointment->id);
                }
            }

            $appointment->update($data);

            return $appointment->load(['designer', 'user', 'order']);
        });
    }

    /**
     * Delete an appointment
     */
    public function deleteAppointment(Appointment $appointment): bool
    {
        return DB::transaction(function () use ($appointment) {
            // Check if appointment can be deleted
            if (!$this->canDeleteAppointment($appointment)) {
                throw new \Exception('Cannot delete appointment that is not pending');
            }

            return $appointment->delete();
        });
    }

    /**
     * Cancel an appointment
     */
    public function cancelAppointment(Appointment $appointment, string $reason = null, string $cancelledBy = 'user'): Appointment
    {
        return DB::transaction(function () use ($appointment, $reason, $cancelledBy) {
            // Check if appointment can be cancelled
            if (!$this->canCancelAppointment($appointment)) {
                throw new \Exception('Cannot cancel appointment that is already completed or cancelled');
            }

            // Use the model's cancel method which handles Zoom meeting cleanup
            $appointment->cancel($reason, $cancelledBy);

            return $appointment->load(['designer', 'user', 'order']);
        });
    }

    /**
     * Confirm an appointment
     */
    public function confirmAppointment(Appointment $appointment): Appointment
    {
        return DB::transaction(function () use ($appointment) {
            if ($appointment->status !== 'pending') {
                throw new \Exception('Only pending appointments can be confirmed');
            }

            $appointment->update(['status' => 'confirmed']);

            return $appointment->load(['designer', 'user', 'order']);
        });
    }

    /**
     * Complete an appointment
     */
    public function completeAppointment(Appointment $appointment): Appointment
    {
        return DB::transaction(function () use ($appointment) {
            if ($appointment->status !== 'confirmed') {
                throw new \Exception('Only confirmed appointments can be completed');
            }

            $appointment->update(['status' => 'completed']);

            return $appointment->load(['designer', 'user', 'order']);
        });
    }

    /**
     * Check designer availability
     */
    public function checkDesignerAvailability(int $designerId, string $appointmentDate, string $appointmentTime, ?int $excludeAppointmentId = null): void
    {
        $designer = Designer::findOrFail($designerId);

        if (!$designer->is_available) {
            throw new \Exception('Designer is not available for appointments');
        }

        // Check for conflicting appointments (exact time match)
        $conflictingAppointment = Appointment::where('designer_id', $designerId)
            ->where('appointment_date', $appointmentDate)
            ->where('appointment_time', $appointmentTime)
            ->whereIn('status', ['pending', 'accepted', 'confirmed'])
            ->when($excludeAppointmentId, function ($query) use ($excludeAppointmentId) {
                return $query->where('id', '!=', $excludeAppointmentId);
            })
            ->first();

        if ($conflictingAppointment) {
            throw new \Exception('Designer has a conflicting appointment at this time');
        }
    }

    /**
     * Check global time slot availability (any designer)
     */
    public function checkGlobalTimeSlotAvailability(string $appointmentDate, string $appointmentTime, int $duration = 30, ?int $excludeAppointmentId = null): void
    {
        $appointmentStartTime = Carbon::parse($appointmentTime);
        $appointmentEndTime = $appointmentStartTime->copy()->addMinutes($duration);

        // Check for any conflicting appointments at this time slot
        $conflictingAppointments = Appointment::where('appointment_date', $appointmentDate)
            ->whereIn('status', ['pending', 'accepted', 'confirmed'])
            ->when($excludeAppointmentId, function ($query) use ($excludeAppointmentId) {
                return $query->where('id', '!=', $excludeAppointmentId);
            })
            ->get();

        foreach ($conflictingAppointments as $existingAppointment) {
            $existingStartTime = Carbon::parse($existingAppointment->appointment_time);
            $existingDuration = $existingAppointment->duration_minutes ?? 30;
            $existingEndTime = $existingStartTime->copy()->addMinutes($existingDuration);

            // Check if time slots overlap
            if ($appointmentStartTime->lt($existingEndTime) && $appointmentEndTime->gt($existingStartTime)) {
                throw new \Exception('This time slot is already reserved. Please choose a different time.');
            }
        }
    }

    /**
     * Check if user can modify appointment
     */
    public function canModifyAppointment(Appointment $appointment): bool
    {
        $user = Auth::user();

        return ($appointment->user_id === $user->id || $appointment->designer_id === $user->id)
            && in_array($appointment->status, ['pending', 'confirmed']);
    }

    /**
     * Check if user can delete appointment
     */
    public function canDeleteAppointment(Appointment $appointment): bool
    {
        $user = Auth::user();

        return ($appointment->user_id === $user->id || $appointment->designer_id === $user->id)
            && $appointment->status === 'pending';
    }

    /**
     * Check if user can cancel appointment
     */
    public function canCancelAppointment(Appointment $appointment): bool
    {
        $user = Auth::user();

        return ($appointment->user_id === $user->id || $appointment->designer_id === $user->id)
            && in_array($appointment->status, ['pending', 'confirmed']);
    }

    /**
     * Check if user can access appointment
     */
    public function canAccessAppointment(Appointment $appointment): bool
    {
        $user = Auth::user();

        return $appointment->user_id === $user->id || $appointment->designer_id === $user->id;
    }

    /**
     * Get available time slots for a designer on a specific date
     */
    public function getAvailableTimeSlots(int $designerId, string $date): array
    {
        $designer = Designer::findOrFail($designerId);

        if (!$designer->is_available) {
            return [];
        }

        // Get booked time slots
        $bookedSlots = Appointment::where('designer_id', $designerId)
            ->where('appointment_date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->pluck('appointment_time')
            ->toArray();

        // Generate all possible time slots (9 AM to 6 PM, 1-hour intervals)
        $allSlots = [];
        $startTime = Carbon::createFromFormat('H:i', '09:00');
        $endTime = Carbon::createFromFormat('H:i', '18:00');

        while ($startTime->lt($endTime)) {
            $timeSlot = $startTime->format('H:i');
            if (!in_array($timeSlot, $bookedSlots)) {
                $allSlots[] = $timeSlot;
            }
            $startTime->addHour();
        }

        return $allSlots;
    }

    /**
     * Get upcoming appointments for a user
     */
    public function getUpcomingAppointments(int $userId, int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return Appointment::where('user_id', $userId)
            ->where('appointment_date', '>=', now()->format('Y-m-d'))
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->limit($limit)
            ->with(['designer', 'order'])
            ->get();
    }

    /**
     * Get designer's appointments for a date range
     */
    public function getDesignerAppointments(int $designerId, string $startDate, string $endDate): \Illuminate\Database\Eloquent\Collection
    {
        return Appointment::where('designer_id', $designerId)
            ->whereBetween('appointment_date', [$startDate, $endDate])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->with(['user', 'order'])
            ->get();
    }

    /**
     * Validate that appointment date and time are not in the past
     */
    private function validateAppointmentDate(string $appointmentDate, string $appointmentTime): void
    {
        $appointmentDateTime = Carbon::createFromFormat('Y-m-d H:i', $appointmentDate . ' ' . $appointmentTime);
        $now = Carbon::now();

        if ($appointmentDateTime->lt($now)) {
            throw new \Exception('Appointment date and time must be in the future');
        }

        // Additional check: if appointment is today, ensure it's at least 30 minutes in the future
        if ($appointmentDateTime->isToday() && $appointmentDateTime->lt($now->addMinutes(30))) {
            throw new \Exception('Appointments must be scheduled at least 30 minutes in advance');
        }
    }
}
