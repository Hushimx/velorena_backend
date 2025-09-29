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
        'slug',
        'description',
        'description_ar',
        'image_url',
        'base_price',
        'is_active',
        'sort_order',
        'specifications',
        // SEO Meta Fields
        'meta_title',
        'meta_title_ar',
        'meta_description',
        'meta_description_ar',
        'meta_keywords',
        'meta_keywords_ar',
        // Open Graph Fields
        'og_title',
        'og_title_ar',
        'og_description',
        'og_description_ar',
        'og_image',
        // Twitter Card Fields
        'twitter_title',
        'twitter_title_ar',
        'twitter_description',
        'twitter_description_ar',
        'twitter_image',
        // Additional SEO Fields
        'canonical_url',
        'robots',
        'structured_data'
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'specifications' => 'array',
        'structured_data' => 'array'
    ];

    /**
     * Scope to get active products with optimized loading
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to load product with all necessary relationships
     */
    public function scopeWithFullDetails($query)
    {
        return $query->with([
            'category:id,name,name_ar',
            'options' => function ($q) {
                $q->where('is_active', true)
                    ->orderBy('sort_order')
                    ->with(['values' => function ($valuesQuery) {
                        $valuesQuery->where('is_active', true)
                            ->orderBy('sort_order');
                    }]);
            },
            'images' => function ($q) {
                $q->orderBy('sort_order');
            }
        ]);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(ProductOption::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage(): HasMany
    {
        return $this->hasMany(ProductImage::class)->where('is_primary', true);
    }

    /**
     * Get SEO meta title for current locale
     */
    public function getSeoTitleAttribute(): string
    {
        $locale = app()->getLocale();
        if ($locale === 'ar' && $this->meta_title_ar) {
            return $this->meta_title_ar;
        }
        return $this->meta_title ?: $this->name;
    }

    /**
     * Get SEO meta description for current locale
     */
    public function getSeoDescriptionAttribute(): string
    {
        $locale = app()->getLocale();
        if ($locale === 'ar' && $this->meta_description_ar) {
            return $this->meta_description_ar;
        }
        return $this->meta_description ?: $this->description ?: '';
    }

    /**
     * Get SEO meta keywords for current locale
     */
    public function getSeoKeywordsAttribute(): string
    {
        $locale = app()->getLocale();
        if ($locale === 'ar' && $this->meta_keywords_ar) {
            return $this->meta_keywords_ar;
        }
        return $this->meta_keywords ?: '';
    }

    /**
     * Get Open Graph title for current locale
     */
    public function getOpenGraphTitleAttribute(): string
    {
        $locale = app()->getLocale();
        if ($locale === 'ar' && $this->og_title_ar) {
            return $this->og_title_ar;
        }
        return $this->og_title ?: $this->getSeoTitleAttribute();
    }

    /**
     * Get Open Graph description for current locale
     */
    public function getOpenGraphDescriptionAttribute(): string
    {
        $locale = app()->getLocale();
        if ($locale === 'ar' && $this->og_description_ar) {
            return $this->og_description_ar;
        }
        return $this->og_description ?: $this->getSeoDescriptionAttribute();
    }

    /**
     * Get Twitter title for current locale
     */
    public function getTwitterCardTitleAttribute(): string
    {
        $locale = app()->getLocale();
        if ($locale === 'ar' && $this->twitter_title_ar) {
            return $this->twitter_title_ar;
        }
        return $this->twitter_title ?: $this->getSeoTitleAttribute();
    }

    /**
     * Get Twitter description for current locale
     */
    public function getTwitterCardDescriptionAttribute(): string
    {
        $locale = app()->getLocale();
        if ($locale === 'ar' && $this->twitter_description_ar) {
            return $this->twitter_description_ar;
        }
        return $this->twitter_description ?: $this->getSeoDescriptionAttribute();
    }

    /**
     * Get the primary image URL for SEO
     */
    public function getSeoImageAttribute(): string
    {
        // First try the main image_url field
        if ($this->image_url) {
            return asset($this->image_url);
        }

        // Fallback to first additional image
        $firstImage = $this->images()->first();
        if ($firstImage && $firstImage->image_path) {
            return asset($firstImage->image_path);
        }

        // Fallback to legacy image field
        if ($this->image) {
            return asset($this->image);
        }

        // Fallback to Open Graph image
        if ($this->og_image) {
            return asset($this->og_image);
        }

        // Fallback to Twitter image
        if ($this->twitter_image) {
            return asset($this->twitter_image);
        }

        return '';
    }

    /**
     * Get canonical URL
     */
    public function getCanonicalUrlAttribute(): string
    {
        // Access the raw attribute to avoid infinite recursion
        $canonicalUrl = $this->attributes['canonical_url'] ?? null;

        if ($canonicalUrl) {
            return $canonicalUrl;
        }

        // Only generate route if product is active and route exists
        if ($this->is_active && \Illuminate\Support\Facades\Route::has('user.products.show')) {
            try {
                return route('user.products.show', $this);
            } catch (\Exception $e) {
                // Fallback if route generation fails
                return url('/products/' . $this->id);
            }
        }

        // Fallback URL
        return url('/products/' . $this->id);
    }

    /**
     * Get structured data for the product
     */
    public function getStructuredDataAttribute(): array
    {
        // Access the raw attribute to avoid infinite recursion
        $structuredData = $this->attributes['structured_data'] ?? null;

        if ($structuredData) {
            return is_string($structuredData) ? json_decode($structuredData, true) : $structuredData;
        }

        // Default structured data
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->getSeoImageAttribute(),
            'offers' => [
                '@type' => 'Offer',
                'price' => $this->base_price,
                'priceCurrency' => 'SAR',
                'availability' => $this->is_active ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock'
            ]
        ];
    }

    /**
     * Get the primary image for this product (now uses main image_url)
     */
    public function getPrimaryImageAttribute()
    {
        return $this->images()->first();
    }

    /**
     * Get the best available image URL for this product
     */
    public function getBestImageUrlAttribute()
    {
        // First try the main image_url field
        if ($this->image_url && file_exists(public_path($this->image_url))) {
            return asset($this->image_url);
        }

        // Fallback to first additional image
        if ($this->relationLoaded('images') && $this->images->count() > 0) {
            $firstImage = $this->images->first();
            if ($firstImage && $firstImage->image_path && file_exists(public_path($firstImage->image_path))) {
                return asset($firstImage->image_path);
            }
        }

        // Fallback to legacy image field
        if ($this->image && file_exists(public_path($this->image))) {
            return asset($this->image);
        }

        return 'https://placehold.co/600x400/f8f9fa/6c757d?text=No+Image';
    }

    /**
     * Get the best available image path for this product
     */
    public function getBestImagePathAttribute()
    {
        // First try the main image_url field
        if ($this->image_url && file_exists(public_path($this->image_url))) {
            return $this->image_url;
        }

        // Fallback to first additional image
        $firstImage = $this->images()->first();
        if ($firstImage && file_exists(public_path($firstImage->image_path))) {
            return $firstImage->image_path;
        }

        // Fallback to legacy image field
        if ($this->image && file_exists(public_path($this->image))) {
            return $this->image;
        }

        return null;
    }

    // Note: product_designs relationship has been removed as the table no longer exists
    // Designs are now managed through appointments and orders directly

    // Relationship with highlights through product_highlights pivot table
    public function highlights(): BelongsToMany
    {
        return $this->belongsToMany(Highlight::class, 'product_highlights')
            ->withPivot('sort_order')
            ->withTimestamps()
            ->orderByPivot('sort_order');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
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

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Generate slug from name
     */
    public static function generateSlug($name)
    {
        $slug = \Illuminate\Support\Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Boot method to automatically generate slug on creation
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = static::generateSlug($product->name);
            }
        });

        static::updating(function ($product) {
            // Only regenerate slug if name changed and no custom slug was provided
            if ($product->isDirty('name') && empty($product->slug)) {
                $product->slug = static::generateSlug($product->name);
            }
        });
    }
}
