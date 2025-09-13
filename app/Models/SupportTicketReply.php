<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportTicketReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'admin_id',
        'message',
        'attachments',
        'is_internal',
    ];

    protected $casts = [
        'attachments' => 'array',
        'is_internal' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Accessor methods
     */
    public function getAuthorNameAttribute(): string
    {
        if ($this->admin) {
            return $this->admin->name . ' (Admin)';
        }
        
        if ($this->user) {
            return $this->user->full_name ?? $this->user->company_name ?? 'User';
        }

        return 'System';
    }

    public function getAuthorTypeAttribute(): string
    {
        if ($this->admin) {
            return 'admin';
        }
        
        if ($this->user) {
            return 'user';
        }

        return 'system';
    }

    /**
     * Scope methods
     */
    public function scopePublic($query)
    {
        return $query->where('is_internal', false);
    }

    public function scopeInternal($query)
    {
        return $query->where('is_internal', true);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByAdmin($query, $adminId)
    {
        return $query->where('admin_id', $adminId);
    }

    /**
     * Get validation rules
     */
    public static function getValidationRules(): array
    {
        return [
            'message' => 'required|string|max:5000',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx,txt|max:10240', // 10MB max
            'is_internal' => 'boolean',
        ];
    }

    /**
     * Get admin validation rules
     */
    public static function getAdminValidationRules(): array
    {
        return [
            'message' => 'required|string|max:5000',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx,txt|max:10240',
            'is_internal' => 'boolean',
        ];
    }
}