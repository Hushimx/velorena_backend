<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'selected_options',
        'notes',
        'unit_price',
        'total_price'
    ];

    protected $casts = [
        'selected_options' => 'array',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2'
    ];

    /**
     * Get the user that owns the cart item
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product for this cart item
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get designs attached to this cart item
     */
    public function designs(): HasMany
    {
        return $this->hasMany(ProductDesign::class, 'product_id', 'product_id')
            ->where('user_id', $this->user_id);
    }

    /**
     * Calculate and update the unit price based on product and selected options
     */
    public function calculateUnitPrice(): float
    {
        $product = $this->product;
        if (!$product) {
            return 0;
        }

        $price = $product->base_price;

        // Add option prices
        if ($this->selected_options && is_array($this->selected_options)) {
            foreach ($this->selected_options as $optionId => $valueId) {
                $optionValue = \App\Models\OptionValue::find($valueId);
                if ($optionValue && $optionValue->price_adjustment) {
                    $price += $optionValue->price_adjustment;
                }
            }
        }

        return $price;
    }

    /**
     * Calculate and update the total price
     */
    public function calculateTotalPrice(): float
    {
        return $this->calculateUnitPrice() * $this->quantity;
    }

    /**
     * Update prices and save
     */
    public function updatePrices(): void
    {
        $this->unit_price = $this->calculateUnitPrice();
        $this->total_price = $this->calculateTotalPrice();
        $this->save();
    }
}
