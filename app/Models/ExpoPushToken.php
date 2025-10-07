<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ExpoPushToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'tokenable_id',
        'tokenable_type',
        'device_id',
        'platform',
        'is_active',
        'last_used_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_used_at' => 'datetime',
    ];

    /**
     * Get the owning tokenable model (User, Designer, etc.)
     */
    public function tokenable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope to get only active tokens
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get tokens by platform
     */
    public function scopePlatform($query, string $platform)
    {
        return $query->where('platform', $platform);
    }

    /**
     * Mark token as used
     */
    public function markAsUsed(): void
    {
        $this->update(['last_used_at' => now()]);
    }

    /**
     * Deactivate this token
     */
    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
    }

    /**
     * Activate this token
     */
    public function activate(): void
    {
        $this->update(['is_active' => true]);
    }

    /**
     * Check if token is valid Expo push token format
     */
    public static function isValidExpoToken(string $token): bool
    {
        return preg_match('/^ExponentPushToken\[[\w-]+\]$/', $token) === 1 ||
               preg_match('/^ExpoPushToken\[[\w-]+\]$/', $token) === 1;
    }

    /**
     * Get all tokens for a specific user
     */
    public static function getTokensForUser($userId, string $userType = User::class): array
    {
        return self::where('tokenable_id', $userId)
            ->where('tokenable_type', $userType)
            ->where('is_active', true)
            ->pluck('token')
            ->toArray();
    }
}
