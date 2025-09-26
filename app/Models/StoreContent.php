<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'type',
        'value_en',
        'value_ar',
        'description',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'value_en' => 'array',
        'value_ar' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Scope for active content
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get the localized value
     */
    public function getLocalizedValueAttribute()
    {
        return app()->getLocale() === 'ar' && $this->value_ar ? $this->value_ar : $this->value_en;
    }

    /**
     * Get setting by key
     */
    public static function getSetting($key, $default = null)
    {
        $setting = static::where('key', $key)->active()->first();
        return $setting ? $setting->localized_value : $default;
    }

    /**
     * Set setting by key
     */
    public static function setSetting($key, $value, $type = 'setting')
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'type' => $type,
                'value_en' => app()->getLocale() === 'en' ? $value : null,
                'value_ar' => app()->getLocale() === 'ar' ? $value : null,
            ]
        );
    }
}
