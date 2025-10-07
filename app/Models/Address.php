<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'contact_name',
        'contact_phone',
        'city',           // المدينة
        'district',       // الحي
        'street',         // الشارع
        'house_description', // وصف البيت
        'postal_code',    // الرمز البريدي
        'country',
        'is_default'
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    protected $appends = ['full_address'];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Accessors
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->street,
            $this->district,
            $this->city,
            $this->postal_code,
            $this->country
        ]);
        
        return implode(', ', $parts);
    }

    // Scopes
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Methods
    public function setAsDefault(): void
    {
        // Remove default flag from other addresses
        self::where('user_id', $this->user_id)
            ->where('id', '!=', $this->id)
            ->update(['is_default' => false]);
        
        // Set this address as default
        $this->update(['is_default' => true]);
    }

    /**
     * Get validation rules for creating/updating address
     */
    public static function getValidationRules($isUpdate = false): array
    {
        return [
            'name' => 'nullable|string|max:100',
            'contact_name' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'city' => 'required|string|max:100',              // المدينة - مطلوب
            'district' => 'required|string|max:100',          // الحي - مطلوب
            'street' => 'required|string|max:255',            // الشارع - مطلوب
            'house_description' => 'nullable|string|max:500', // وصف البيت - اختياري
            'postal_code' => 'nullable|string|max:20',        // الرمز البريدي - اختياري
            'country' => 'nullable|string|max:100',
            'is_default' => 'nullable|boolean'
        ];
    }
}

