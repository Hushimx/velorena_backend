<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'user_id',
        'subject',
        'description',
        'priority',
        'status',
        'category',
        'assigned_to',
        'resolved_at',
        'closed_at',
        'attachments',
        'admin_notes',
    ];

    protected $casts = [
        'attachments' => 'array',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    // Boot method to auto-generate ticket number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            if (empty($ticket->ticket_number)) {
                $ticket->ticket_number = self::generateTicketNumber();
            }
        });
    }

    /**
     * Generate unique ticket number
     */
    public static function generateTicketNumber(): string
    {
        do {
            $number = 'TKT-' . date('Y') . '-' . strtoupper(Str::random(6));
        } while (self::where('ticket_number', $number)->exists());

        return $number;
    }

    /**
     * Relationships
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedAdmin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'assigned_to');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(SupportTicketReply::class, 'ticket_id')->orderBy('created_at');
    }

    /**
     * Scope methods
     */
    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['open', 'in_progress', 'pending']);
    }

    public function scopeClosed($query)
    {
        return $query->whereIn('status', ['resolved', 'closed']);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByAssignedAdmin($query, $adminId)
    {
        return $query->where('assigned_to', $adminId);
    }

    /**
     * Simple helper methods
     */
    public function isOpen(): bool
    {
        return in_array($this->status, ['open', 'in_progress', 'pending']);
    }

    public function assignToAdmin($adminId): void
    {
        $this->update([
            'assigned_to' => $adminId,
            'status' => 'in_progress',
        ]);
    }

    /**
     * Get validation rules
     */
    public static function getValidationRules($isUpdate = false): array
    {
        $rules = [
            'subject' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'priority' => 'required|in:low,medium,high,urgent',
            'category' => 'required|in:technical,billing,general,feature_request,bug_report',
        ];

        if (!$isUpdate) {
            $rules['user_id'] = 'required|exists:users,id';
        }

        return $rules;
    }

    /**
     * Get admin validation rules
     */
    public static function getAdminValidationRules(): array
    {
        return [
            'subject' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'priority' => 'required|in:low,medium,high,urgent',
            'category' => 'required|in:technical,billing,general,feature_request,bug_report',
            'status' => 'required|in:open,in_progress,pending,resolved,closed',
            'assigned_to' => 'nullable|exists:admins,id',
            'admin_notes' => 'nullable|string|max:2000',
        ];
    }
}