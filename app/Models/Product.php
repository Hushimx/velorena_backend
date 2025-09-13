<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_id',
        'name',
        'name_ar',
        'description',
        'description_ar',
        'image',
        'base_price',
        'is_active',
        'sort_order',
        'specifications'
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'specifications' => 'array'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(ProductOption::class);
    }

    // Relationship with designs through product_designs pivot table
    public function designs(): BelongsToMany
    {
        return $this->belongsToMany(Design::class, 'product_designs')
            ->withPivot('user_id', 'notes', 'priority')
            ->withTimestamps();
    }

    // Get designs for a specific user
    public function designsForUser($userId)
    {
        return $this->designs()
            ->wherePivot('user_id', $userId)
            ->orderByPivot('priority');
    }

    // Relationship with highlights through product_highlights pivot table
    public function highlights(): BelongsToMany
    {
        return $this->belongsToMany(Highlight::class, 'product_highlights')
            ->withPivot('sort_order')
            ->withTimestamps()
            ->orderByPivot('sort_order');
    }

    /**
     * Scope to get products with highlights
     */
    public function scopeWithHighlights($query)
    {
        return $query->with('highlights');
    }

    /**
     * Scope to filter products by highlight
     */
    public function scopeByHighlight($query, $highlightId)
    {
        return $query->whereHas('highlights', function ($q) use ($highlightId) {
            $q->where('highlights.id', $highlightId);
        });
    }
}
