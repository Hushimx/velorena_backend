<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'subtotal',
        'tax',
        'total',
        'notes',
        'shipping_address',
        'billing_address',
        'phone',
        'confirmed_at',
        'shipped_at',
        'delivered_at',
        'cancelled_at'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relationship with appointments through pivot table
    public function appointments(): BelongsToMany
    {
        return $this->belongsToMany(Appointment::class, 'appointment_orders')
            ->withPivot('notes')
            ->withTimestamps();
    }

    // Generate unique order number
    public static function generateOrderNumber(): string
    {
        $prefix = 'ORD';
        $year = date('Y');
        $month = date('m');

        // Get the last order number for this month
        $lastOrder = self::where('order_number', 'like', "{$prefix}{$year}{$month}%")
            ->orderBy('order_number', 'desc')
            ->first();

        if ($lastOrder) {
            $lastNumber = (int) substr($lastOrder->order_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $year . $month . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    // Calculate order totals
    public function calculateTotals(): void
    {
        $subtotal = $this->items->sum('total_price');
        $tax = $subtotal * 0.15; // 15% VAT
        $total = $subtotal + $tax;

        $this->update([
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total
        ]);
    }

    // Add product to order
    public function addProduct(Product $product, int $quantity = 1, array $options = []): OrderItem
    {
        $unitPrice = $product->base_price;

        // Calculate price adjustments from options
        if (!empty($options)) {
            foreach ($options as $optionId => $valueId) {
                $optionValue = OptionValue::find($valueId);
                if ($optionValue) {
                    $unitPrice += $optionValue->price_adjustment;
                }
            }
        }

        $totalPrice = $unitPrice * $quantity;

        $orderItem = $this->items()->create([
            'product_id' => $product->id,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_price' => $totalPrice,
            'options' => $options
        ]);

        $this->calculateTotals();

        return $orderItem;
    }

    // Status methods
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    public function isShipped(): bool
    {
        return $this->status === 'shipped';
    }

    public function isDelivered(): bool
    {
        return $this->status === 'delivered';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    // Status transitions
    public function confirm(): void
    {
        $this->update([
            'status' => 'confirmed',
            'confirmed_at' => now()
        ]);
    }

    public function process(): void
    {
        $this->update(['status' => 'processing']);
    }

    public function ship(): void
    {
        $this->update([
            'status' => 'shipped',
            'shipped_at' => now()
        ]);
    }

    public function deliver(): void
    {
        $this->update([
            'status' => 'delivered',
            'delivered_at' => now()
        ]);
    }

    public function cancel(): void
    {
        $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now()
        ]);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeShipped($query)
    {
        return $query->where('status', 'shipped');
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }
}
