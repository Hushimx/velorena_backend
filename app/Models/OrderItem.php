<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        'total_price',
        'options',
        'notes'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'options' => 'array'
    ];

    // Relationships
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function designs(): HasMany
    {
        return $this->hasMany(OrderItemDesign::class);
    }

    // Calculate total price for this item
    public function calculateTotalPrice(): void
    {
        $this->total_price = $this->unit_price * $this->quantity;
        $this->save();
    }

    // Get formatted options display
    public function getFormattedOptionsAttribute(): string
    {
        if (empty($this->options)) {
            return '';
        }

        // Ensure options is an array
        $options = is_array($this->options) ? $this->options : [];

        if (empty($options)) {
            return '';
        }

        $formatted = [];
        foreach ($options as $optionId => $valueId) {
            $option = ProductOption::find($optionId);
            $value = OptionValue::find($valueId);

            if ($option && $value) {
                $formatted[] = $option->name . ': ' . $value->value;
            }
        }

        return implode(', ', $formatted);
    }

    // Get detailed options with price adjustments
    public function getDetailedOptionsAttribute(): array
    {
        if (empty($this->options) || !is_array($this->options)) {
            return [];
        }

        $detailedOptions = [];
        foreach ($this->options as $optionId => $valueId) {
            $option = ProductOption::find($optionId);
            $value = OptionValue::find($valueId);

            if ($option && $value) {
                $detailedOptions[] = [
                    'option_id' => $optionId,
                    'option_name' => $option->name,
                    'value_id' => $valueId,
                    'value' => $value->value,
                    'price_adjustment' => $value->price_adjustment,
                    'is_required' => $option->is_required,
                    'type' => $option->type,
                ];
            }
        }

        return $detailedOptions;
    }

    // Calculate total price adjustments from options
    public function getOptionsPriceAdjustmentAttribute(): float
    {
        $adjustment = 0;
        foreach ($this->detailed_options as $option) {
            $adjustment += $option['price_adjustment'];
        }
        return $adjustment;
    }
}
