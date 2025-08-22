<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'designer_id',
        'appointment_date',
        'appointment_time',
        'duration_minutes',
        'status',
        'notes',
        'designer_notes',
        'accepted_at',
        'rejected_at',
        'completed_at'
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'datetime:H:i',
        'accepted_at' => 'datetime',
        'rejected_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function designer(): BelongsTo
    {
        return $this->belongsTo(Designer::class);
    }

    // Check if appointment is unassigned
    public function isUnassigned()
    {
        return is_null($this->designer_id);
    }

    // Assign appointment to a designer
    public function assignToDesigner($designerId)
    {
        $this->update([
            'designer_id' => $designerId,
            'status' => 'accepted',
            'accepted_at' => now()
        ]);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeToday($query)
    {
        return $query->where('appointment_date', today());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('appointment_date', '>=', today());
    }

    public function scopeForDesigner($query, $designerId)
    {
        return $query->where('designer_id', $designerId);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Helper methods
    public function getFullDateTimeAttribute()
    {
        return $this->appointment_date->format('Y-m-d') . ' ' . $this->appointment_time->format('H:i');
    }

    public function getFormattedDateAttribute()
    {
        return $this->appointment_date->format('l, F j, Y');
    }

    public function getFormattedTimeAttribute()
    {
        return $this->appointment_time->format('g:i A');
    }

    public function getEndTimeAttribute()
    {
        return Carbon::parse($this->appointment_time)->addMinutes($this->duration_minutes);
    }

    public function getFormattedEndTimeAttribute()
    {
        return $this->end_time->format('g:i A');
    }

    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'accepted' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'completed' => 'bg-blue-100 text-blue-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getStatusTextAttribute()
    {
        return match ($this->status) {
            'pending' => 'Pending',
            'accepted' => 'Accepted',
            'rejected' => 'Rejected',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            default => 'Unknown'
        };
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isAccepted()
    {
        return $this->status === 'accepted';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function canBeAccepted()
    {
        return $this->isPending();
    }

    public function canBeRejected()
    {
        return $this->isPending();
    }

    public function canBeCompleted()
    {
        return $this->isAccepted();
    }

    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'accepted']);
    }

    // Action methods
    public function accept($designerNotes = null)
    {
        $this->update([
            'status' => 'accepted',
            'designer_notes' => $designerNotes,
            'accepted_at' => now()
        ]);
    }

    public function reject($designerNotes = null)
    {
        $this->update([
            'status' => 'rejected',
            'designer_notes' => $designerNotes,
            'rejected_at' => now()
        ]);
    }

    public function complete()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);
    }

    public function cancel()
    {
        $this->update([
            'status' => 'cancelled'
        ]);
    }

    // Check if time slot is available
    public static function isTimeSlotAvailable($designerId, $date, $time, $duration = 15, $excludeId = null)
    {
        $query = self::where('designer_id', $designerId)
            ->where('appointment_date', $date)
            ->where('status', '!=', 'cancelled')
            ->where('status', '!=', 'rejected');

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $appointmentTime = Carbon::parse($time);
        $appointmentEnd = $appointmentTime->copy()->addMinutes($duration);

        return !$query->where(function ($q) use ($appointmentTime, $appointmentEnd) {
            $q->where(function ($subQ) use ($appointmentTime, $appointmentEnd) {
                $subQ->where('appointment_time', '<=', $appointmentTime)
                    ->whereRaw('TIME_TO_SEC(appointment_time) + (duration_minutes * 60) > ?', [$appointmentTime->secondsSinceMidnight()]);
            })->orWhere(function ($subQ) use ($appointmentTime, $appointmentEnd) {
                $subQ->where('appointment_time', '<', $appointmentEnd)
                    ->where('appointment_time', '>=', $appointmentTime);
            });
        })->exists();
    }

    // Get available time slots for a designer on a specific date
    public static function getAvailableTimeSlots($designerId, $date, $duration = 15)
    {
        $workingHours = [
            'start' => '09:00',
            'end' => '17:00'
        ];

        $timeSlots = [];
        $currentTime = Carbon::parse($workingHours['start']);
        $endTime = Carbon::parse($workingHours['end']);

        while ($currentTime->lt($endTime)) {
            $timeString = $currentTime->format('H:i');

            if (self::isTimeSlotAvailable($designerId, $date, $timeString, $duration)) {
                $timeSlots[] = $timeString;
            }

            $currentTime->addMinutes($duration);
        }

        return $timeSlots;
    }
}
