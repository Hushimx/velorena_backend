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
        'address_line',
        'city',
        'district',
        'postal_code',
        'country',
        'latitude',
        'longitude',
        'delivery_instruction',
        'drop_off_location',
        'additional_notes',
        'building_image_url',
        'is_default'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
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
            $this->address_line,
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
            'address_line' => 'required|string|max:500',
            'city' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'delivery_instruction' => 'nullable|in:hand_to_me,leave_at_spot',
            'drop_off_location' => 'nullable|string|max:255',
            'additional_notes' => 'nullable|string|max:1000',
            'building_image_url' => 'nullable|string|max:500',
            'is_default' => 'nullable|boolean'
        ];
    }
}

