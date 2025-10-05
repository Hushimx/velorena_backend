<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'title_ar',
        'slug',
        'content',
        'content_ar',
        'excerpt',
        'excerpt_ar',
        'featured_image',
        'status',
        'is_featured',
        'published_at',
        'admin_id',
        // SEO Fields
        'meta_title',
        'meta_title_ar',
        'meta_description',
        'meta_description_ar',
        'meta_keywords',
        'meta_keywords_ar',
        'og_title',
        'og_title_ar',
        'og_description',
        'og_description_ar',
        'og_image',
        'canonical_url',
        'robots',
        'structured_data',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'structured_data' => 'array',
    ];

    protected $dates = [
        'published_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Relationships
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    // Accessors & Mutators
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getExcerptAttribute($value)
    {
        if (empty($value) && !empty($this->content)) {
            return Str::limit(strip_tags($this->content), 160);
        }
        return $value;
    }

    public function getExcerptArAttribute($value)
    {
        if (empty($value) && !empty($this->content_ar)) {
            return Str::limit(strip_tags($this->content_ar), 160);
        }
        return $value;
    }

    public function getFeaturedImageUrlAttribute()
    {
        if ($this->featured_image) {
            return asset('storage/' . $this->featured_image);
        }
        return null;
    }

    public function getOgImageUrlAttribute()
    {
        if ($this->og_image) {
            return asset('storage/' . $this->og_image);
        }
        return $this->featured_image_url;
    }
}
