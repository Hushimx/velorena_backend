<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'order_id',
        'order_item_id',
        'rating',
        'comment',
        'comment_ar',
        'is_approved',
        'is_verified_purchase',
        'metadata'
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean',
        'is_verified_purchase' => 'boolean',
        'metadata' => 'array'
    ];

    /**
     * Get the product that owns the review
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user that owns the review
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order associated with the review
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the order item associated with the review
     */
    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    /**
     * Scope to get approved reviews
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope to get reviews for a specific product
     */
    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Scope to get verified purchase reviews
     */
    public function scopeVerifiedPurchase($query)
    {
        return $query->where('is_verified_purchase', true);
    }

    /**
     * Get the appropriate comment based on locale
     */
    public function getLocalizedCommentAttribute(): string
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? ($this->comment_ar ?: $this->comment) : $this->comment;
    }

    /**
     * Get user name for display
     */
    public function getUserDisplayNameAttribute(): string
    {
        // Return first name and last initial for privacy
        $name = $this->user->name ?? 'Anonymous';
        $parts = explode(' ', $name);
        
        if (count($parts) >= 2) {
            return $parts[0] . ' ' . substr($parts[1], 0, 1) . '.';
        }
        
        return $parts[0];
    }
}
