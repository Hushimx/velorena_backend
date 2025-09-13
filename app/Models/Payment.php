<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'charge_id',
        'amount',
        'currency',
        'status',
        'payment_method',
        'gateway_response',
        'transaction_id',
        'paid_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_response' => 'array',
        'paid_at' => 'datetime'
    ];

    // Relationships
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // Status methods
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isRefunded(): bool
    {
        return $this->status === 'refunded';
    }

    // Mark payment as completed
    public function markAsCompleted(string $transactionId = null): void
    {
        $this->update([
            'status' => 'completed',
            'transaction_id' => $transactionId,
            'paid_at' => now()
        ]);
    }

    // Mark payment as failed
    public function markAsFailed(): void
    {
        $this->update(['status' => 'failed']);
    }

    // Mark payment as cancelled
    public function markAsCancelled(): void
    {
        $this->update(['status' => 'cancelled']);
    }

    // Mark payment as refunded
    public function markAsRefunded(): void
    {
        $this->update(['status' => 'refunded']);
    }
}
