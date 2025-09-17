<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProtectedPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'title_ar',
        'slug',
        'content',
        'content_ar',
        'type',
        'access_level',
        'is_active',
        'sort_order',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Scope for active pages
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for authenticated users
     */
    public function scopeForAuthenticated($query)
    {
        return $query->where('access_level', 'authenticated');
    }

    /**
     * Scope for public access
     */
    public function scopePublic($query)
    {
        return $query->where('access_level', 'public');
    }

    /**
     * Scope for admin only
     */
    public function scopeAdminOnly($query)
    {
        return $query->where('access_level', 'admin');
    }

    /**
     * Get the localized title
     */
    public function getLocalizedTitleAttribute()
    {
        return app()->getLocale() === 'ar' && $this->title_ar ? $this->title_ar : $this->title;
    }

    /**
     * Get the localized content
     */
    public function getLocalizedContentAttribute()
    {
        return app()->getLocale() === 'ar' && $this->content_ar ? $this->content_ar : $this->content;
    }

    /**
     * Check if user can access this page
     */
    public function canAccess($user = null)
    {
        if ($this->access_level === 'public') {
            return true;
        }

        if ($this->access_level === 'authenticated') {
            return $user !== null;
        }

        if ($this->access_level === 'admin') {
            return $user && $user->hasRole('admin');
        }

        return false;
    }
}
