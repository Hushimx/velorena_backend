<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'name_ar',
        'description',
        'description_ar',
        'base_price',
        'is_active',
        'sort_order',
        'specifications',
        'slug',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_title',
        'og_description',
        'og_image',
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
     * Get the primary image for this product
     */
    public function getPrimaryImageAttribute()
    {
        return $this->images()->where('is_primary', true)->first() ?? $this->images()->first();
    }

    /**
     * Get the best available image URL for this product
     */
    public function getBestImageUrlAttribute()
    {
        // Use already loaded images relationship to avoid N+1 queries
        if ($this->relationLoaded('images') && $this->images->count() > 0) {
            // Find primary image first
            $primaryImage = $this->images->where('is_primary', true)->first();
            if ($primaryImage && $primaryImage->image_path && file_exists(public_path($primaryImage->image_path))) {
                return asset($primaryImage->image_path);
            }

            // Fallback to first image
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
        // Try to get primary image first
        $primaryImage = $this->images()->where('is_primary', true)->first();
        if ($primaryImage && file_exists(public_path($primaryImage->image_path))) {
            return $primaryImage->image_path;
        }

        // Fallback to first image
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
     * Boot method to handle slug generation
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = $product->generateSlug();
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name') && empty($product->slug)) {
                $product->slug = $product->generateSlug();
            }
        });
    }

    /**
     * Generate a unique slug for the product
     */
    public function generateSlug()
    {
        $slug = Str::slug($this->name);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->where('id', '!=', $this->id ?? 0)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Get the route key for the model
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get SEO meta title
     */
    public function getSeoTitleAttribute()
    {
        return $this->attributes['meta_title'] ?: $this->name;
    }

    /**
     * Get SEO meta description
     */
    public function getSeoDescriptionAttribute()
    {
        if ($this->attributes['meta_description']) {
            return $this->attributes['meta_description'];
        }

        // Generate description from product description
        $description = strip_tags($this->description);
        return Str::limit($description, 160);
    }

    /**
     * Get SEO keywords
     */
    public function getSeoKeywordsAttribute()
    {
        if ($this->attributes['meta_keywords']) {
            return $this->attributes['meta_keywords'];
        }

        // Generate keywords from product name and category
        $keywords = [];
        $keywords[] = $this->name;
        if ($this->category) {
            $keywords[] = $this->category->name;
        }

        return implode(', ', array_unique($keywords));
    }

    /**
     * Get Open Graph title
     */
    public function getOgTitleAttribute()
    {
        return $this->attributes['og_title'] ?: $this->getSeoTitleAttribute();
    }

    /**
     * Get Open Graph description
     */
    public function getOgDescriptionAttribute()
    {
        return $this->attributes['og_description'] ?: $this->getSeoDescriptionAttribute();
    }

    /**
     * Get Open Graph image
     */
    public function getOgImageAttribute()
    {
        if ($this->attributes['og_image']) {
            return asset($this->attributes['og_image']);
        }

        return $this->getBestImageUrlAttribute();
    }

    /**
     * Get canonical URL
     */
    public function getCanonicalUrlAttribute()
    {
        return route('user.products.show', $this);
    }

    /**
     * Generate structured data for the product
     */
    public function generateStructuredData()
    {
        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $this->name,
            'description' => strip_tags($this->description),
            'url' => $this->getCanonicalUrlAttribute(),
            'image' => $this->getBestImageUrlAttribute(),
            'brand' => [
                '@type' => 'Brand',
                'name' => config('app.name', 'Velorena')
            ],
            'offers' => [
                '@type' => 'Offer',
                'price' => $this->base_price,
                'priceCurrency' => 'USD',
                'availability' => $this->is_active ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock'
            ]
        ];

        if ($this->category) {
            $structuredData['category'] = $this->category->name;
        }

        if ($this->images && $this->images->count() > 0) {
            $structuredData['image'] = $this->images->map(function ($image) {
                return asset($image->image_path);
            })->toArray();
        }

        return $structuredData;
    }

    /**
     * Get structured data
     */
    public function getStructuredDataAttribute()
    {
        if ($this->structured_data) {
            return $this->structured_data;
        }

        return $this->generateStructuredData();
    }
}
