<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
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
        'cancelled_at'
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'datetime:H:i',
        'accepted_at' => 'datetime',
        'rejected_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
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
}
