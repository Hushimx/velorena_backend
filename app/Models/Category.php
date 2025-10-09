<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'name_ar',
        'description',
        'description_ar',
        'image',
        'slider_image',
        'main_image',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function marketers(): HasMany
    {
        return $this->hasMany(Marketer::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    /**
     * Get the best available image URL for this category
     */
    public function getImageUrlAttribute()
    {
        // Check for main_image first (mobile/app image)
        if (!empty($this->attributes['main_image'])) {
            // If it's already a full URL, return as is
            if (filter_var($this->attributes['main_image'], FILTER_VALIDATE_URL)) {
                return $this->attributes['main_image'];
            }
            return asset($this->attributes['main_image']);
        }

        // Fallback to regular image field
        if (!empty($this->attributes['image'])) {
            if (filter_var($this->attributes['image'], FILTER_VALIDATE_URL)) {
                return $this->attributes['image'];
            }
            return asset($this->attributes['image']);
        }

        // Fallback to slider image
        if (!empty($this->attributes['slider_image'])) {
            if (filter_var($this->attributes['slider_image'], FILTER_VALIDATE_URL)) {
                return $this->attributes['slider_image'];
            }
            return asset($this->attributes['slider_image']);
        }

        return null;
    }

    /**
     * Append image_url to JSON serialization
     */
    protected $appends = ['image_url'];
}
