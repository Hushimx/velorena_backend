<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'designer_id',
        'order_id',
        'appointment_date',
        'appointment_time',
        'duration_minutes',
        'status',
        'notes',
        'designer_notes',
        'order_notes',
        'accepted_at',
        'rejected_at',
        'completed_at',
        'cancelled_at',
        'started_at',
        'cancellation_reason',
        'google_meet_id',
        'google_meet_link',
        'meet_created_at',
        'zoom_meeting_id',
        'zoom_meeting_url',
        'zoom_start_url',
        'zoom_meeting_created_at'
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'datetime',
        'accepted_at' => 'datetime',
        'rejected_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'started_at' => 'datetime',
        'meet_created_at' => 'datetime',
        'zoom_meeting_created_at' => 'datetime',
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

    // Relationship with order (one-to-one)
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // Get all products from the linked order
    public function products(): HasManyThrough
    {
        return $this->hasManyThrough(
            OrderItem::class,
            Order::class,
            'id', // Foreign key on orders table
            'order_id', // Foreign key on order_items table
            'order_id', // Local key on appointments table
            'id' // Local key on orders table
        );
    }

    // Get all order items from the linked order
    public function orderItems(): HasManyThrough
    {
        return $this->hasManyThrough(
            OrderItem::class,
            Order::class,
            'id', // Foreign key on orders table
            'order_id', // Foreign key on order_items table
            'order_id', // Local key on appointments table
            'id' // Local key on orders table
        );
    }

    // Relationship with designs (many-to-many)
    public function designs(): BelongsToMany
    {
        return $this->belongsToMany(Design::class, 'appointment_designs')
            ->withPivot('notes', 'priority')
            ->withTimestamps();
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

        // Create Zoom meeting when appointment is assigned and accepted
        $this->createZoomMeeting();
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

    public function scopeStarted($query)
    {
        return $query->where('status', 'started');
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
        if (!$this->appointment_date || !$this->appointment_time) {
            return '';
        }
        return Carbon::parse($this->appointment_date)->format('Y-m-d') . ' ' . Carbon::parse($this->appointment_time)->format('H:i');
    }

    public function getFormattedDateAttribute()
    {
        if (!$this->appointment_date) {
            return '';
        }
        return Carbon::parse($this->appointment_date)->format('l, F j, Y');
    }

    public function getFormattedTimeAttribute()
    {
        if (!$this->appointment_time) {
            return '';
        }
        return Carbon::parse($this->appointment_time)->format('g:i A');
    }

    public function getEndTimeAttribute()
    {
        return Carbon::parse($this->appointment_time)->addMinutes($this->duration_minutes);
    }

    public function getFormattedEndTimeAttribute()
    {
        if (!$this->appointment_time) {
            return '';
        }
        $endTime = Carbon::parse($this->appointment_time)->addMinutes($this->duration_minutes);
        return $endTime->format('g:i A');
    }

    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'accepted' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'completed' => 'bg-blue-100 text-blue-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
            'started' => 'bg-purple-100 text-purple-800',
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
            'started' => 'Started',
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

    public function isStarted()
    {
        return $this->status === 'started';
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

    public function canBeStarted()
    {
        return $this->status === 'accepted';
    }

    // Action methods
    public function accept($designerNotes = null)
    {
        $this->update([
            'status' => 'accepted',
            'designer_notes' => $designerNotes,
            'accepted_at' => now()
        ]);

        // Create Zoom meeting when appointment is accepted
        $this->createZoomMeeting();
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

    public function cancel($reason = null)
    {
        $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $reason
        ]);

        // Delete Zoom meeting when appointment is cancelled
        $this->deleteZoomMeeting();
    }

    public function start()
    {
        $this->update([
            'status' => 'started',
            'started_at' => now()
        ]);
    }

    // Check if time slot is available (global check - any designer)
    public static function isTimeSlotAvailable($designerId, $date, $time, $duration = 15, $excludeId = null)
    {
        // First check global availability (any designer)
        $globalQuery = self::where('appointment_date', $date)
            ->whereIn('status', ['pending', 'accepted', 'confirmed'])
            ->when($excludeId, function ($query) use ($excludeId) {
                return $query->where('id', '!=', $excludeId);
            });

        $appointmentTime = Carbon::parse($time);
        $appointmentEnd = $appointmentTime->copy()->addMinutes($duration);

        // Check for time overlap conflicts
        $hasConflict = $globalQuery->where(function ($q) use ($appointmentTime, $appointmentEnd) {
            $q->where(function ($subQ) use ($appointmentTime, $appointmentEnd) {
                // Check if existing appointment starts before new appointment and ends after new appointment starts
                $subQ->where('appointment_time', '<=', $appointmentTime)
                    ->whereRaw('TIME_TO_SEC(appointment_time) + (COALESCE(duration_minutes, 30) * 60) > ?', [$appointmentTime->secondsSinceMidnight()]);
            })->orWhere(function ($subQ) use ($appointmentTime, $appointmentEnd) {
                // Check if existing appointment starts within new appointment time range
                $subQ->where('appointment_time', '<', $appointmentEnd)
                    ->where('appointment_time', '>=', $appointmentTime);
            });
        })->exists();

        if ($hasConflict) {
            return false;
        }

        // If designer is specified, also check designer-specific availability
        if ($designerId) {
            $designerQuery = self::where('designer_id', $designerId)
                ->where('appointment_date', $date)
                ->whereIn('status', ['pending', 'accepted', 'confirmed'])
                ->when($excludeId, function ($query) use ($excludeId) {
                    return $query->where('id', '!=', $excludeId);
                });

            $designerConflict = $designerQuery->where(function ($q) use ($appointmentTime, $appointmentEnd) {
                $q->where(function ($subQ) use ($appointmentTime, $appointmentEnd) {
                    $subQ->where('appointment_time', '<=', $appointmentTime)
                        ->whereRaw('TIME_TO_SEC(appointment_time) + (COALESCE(duration_minutes, 30) * 60) > ?', [$appointmentTime->secondsSinceMidnight()]);
                })->orWhere(function ($subQ) use ($appointmentTime, $appointmentEnd) {
                    $subQ->where('appointment_time', '<', $appointmentEnd)
                        ->where('appointment_time', '>=', $appointmentTime);
                });
            })->exists();

            if ($designerConflict) {
                return false;
            }
        }

        return true;
    }

    // Get available time slots for a designer on a specific date
    // This method now uses the AvailabilitySlot model for better flexibility
    public static function getAvailableTimeSlots($designerId, $date, $duration = 15)
    {
        // Use the new AvailabilitySlot model if available
        if (class_exists('App\Models\AvailabilitySlot')) {
            return \App\Models\AvailabilitySlot::getAvailableTimeSlotsExcludingBooked($designerId, $date);
        }

        // Fallback to old hardcoded method if AvailabilitySlot doesn't exist
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

    // Helper methods for managing order
    public function linkOrder(Order $order, string $notes = null): void
    {
        $this->update([
            'order_id' => $order->id,
            'order_notes' => $notes
        ]);
    }

    public function unlinkOrder(): void
    {
        $this->update([
            'order_id' => null,
            'order_notes' => null
        ]);
    }

    public function getTotalProductsCount(): int
    {
        return $this->orderItems()->sum('quantity');
    }

    public function getTotalOrderValue(): float
    {
        return $this->order ? $this->order->total : 0;
    }

    public function hasOrder(): bool
    {
        return !is_null($this->order_id);
    }

    public function getProductsSummary(): array
    {
        $summary = [];
        foreach ($this->orderItems as $item) {
            $productName = $item->product->name ?? 'Unknown Product';
            if (!isset($summary[$productName])) {
                $summary[$productName] = [
                    'quantity' => 0,
                    'total_price' => 0,
                    'unit_price' => $item->unit_price
                ];
            }
            $summary[$productName]['quantity'] += $item->quantity;
            $summary[$productName]['total_price'] += $item->total_price;
        }
        return $summary;
    }

    // Zoom meeting helper methods
    public function hasZoomMeeting(): bool
    {
        return !is_null($this->zoom_meeting_id);
    }

    public function hasGoogleMeet(): bool
    {
        return !is_null($this->google_meet_id);
    }

    public function getMeetingUrl(): ?string
    {
        if ($this->hasZoomMeeting()) {
            return $this->zoom_meeting_url;
        }
        
        if ($this->hasGoogleMeet()) {
            return $this->google_meet_link;
        }
        
        return null;
    }

    public function getHostMeetingUrl(): ?string
    {
        if ($this->hasZoomMeeting()) {
            return $this->zoom_start_url;
        }
        
        return $this->getMeetingUrl();
    }

    public function getMeetingType(): string
    {
        if ($this->hasZoomMeeting()) {
            return 'zoom';
        }
        
        if ($this->hasGoogleMeet()) {
            return 'google_meet';
        }
        
        return 'none';
    }

    /**
     * Get the meeting datetime as a Carbon instance
     */
    public function getMeetingDateTime(): \Carbon\Carbon
    {
        try {
            // Handle different input formats
            if ($this->appointment_date instanceof \DateTime) {
                $dateString = $this->appointment_date->format('Y-m-d');
            } else {
                $dateString = Carbon::parse($this->appointment_date)->format('Y-m-d');
            }
            
            if ($this->appointment_time instanceof \DateTime) {
                $timeString = $this->appointment_time->format('H:i:s');
            } else {
                $timeString = Carbon::parse($this->appointment_time)->format('H:i:s');
            }
            
            return Carbon::createFromFormat('Y-m-d H:i:s', $dateString . ' ' . $timeString);
        } catch (\Exception $e) {
            Log::error('Failed to parse appointment datetime: ' . $e->getMessage(), [
                'appointment_id' => $this->id,
                'appointment_date' => $this->appointment_date,
                'appointment_time' => $this->appointment_time
            ]);
            return now();
        }
    }

    /**
     * Check if the meeting can be joined (5 minutes before start time)
     */
    public function canJoinMeeting(): bool
    {
        $meetingTime = $this->getMeetingDateTime();
        return now()->gte($meetingTime->subMinutes(5));
    }

    /**
     * Check if the meeting is currently active
     */
    public function isMeetingActive(): bool
    {
        $meetingTime = $this->getMeetingDateTime();
        return now()->between($meetingTime, $meetingTime->addMinutes($this->duration_minutes));
    }


    /**
     * Create Zoom meeting for this appointment
     */
    public function createZoomMeeting(): bool
    {
        try {
            // Check if Zoom is configured
            $zoomService = app(\App\Services\ZoomService::class);
            
            if (!$zoomService->isConfigured()) {
                Log::warning('Zoom is not configured, skipping meeting creation for appointment: ' . $this->id);
                return false;
            }

            // Don't create if already has a Zoom meeting
            if ($this->hasZoomMeeting()) {
                Log::info('Appointment already has Zoom meeting, skipping creation: ' . $this->id);
                return true;
            }

            // Create the meeting
            $meetingData = $zoomService->createAppointmentMeeting($this);
            
            // Update appointment with Zoom meeting details
            $this->update([
                'zoom_meeting_id' => $meetingData['id'],
                'zoom_meeting_url' => $meetingData['join_url'],
                'zoom_start_url' => $meetingData['start_url'],
                'zoom_meeting_created_at' => now()
            ]);

            Log::info('Zoom meeting created successfully for appointment: ' . $this->id, [
                'meeting_id' => $meetingData['id'],
                'join_url' => $meetingData['join_url']
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to create Zoom meeting for appointment: ' . $this->id, [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Delete Zoom meeting for this appointment
     */
    public function deleteZoomMeeting(): bool
    {
        try {
            if (!$this->hasZoomMeeting()) {
                return true; // Nothing to delete
            }

            $zoomService = app(\App\Services\ZoomService::class);
            $deleted = $zoomService->deleteMeeting($this->zoom_meeting_id);

            if ($deleted) {
                $this->update([
                    'zoom_meeting_id' => null,
                    'zoom_meeting_url' => null,
                    'zoom_start_url' => null,
                    'zoom_meeting_created_at' => null
                ]);
            }

            return $deleted;
        } catch (\Exception $e) {
            Log::error('Failed to delete Zoom meeting for appointment: ' . $this->id, [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
