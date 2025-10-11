<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'client_type',
        'full_name',
        'company_name',
        'contact_person',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'vat_number',
        'cr_number',
        'cr_document_path',
        'vat_document_path',
        'notes',
        'password',
        'email_notifications',
        'sms_notifications',
        'whatsapp_notifications',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function addresses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function appointments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function designFavorites(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Design::class, 'design_favorites')
            ->withPivot('notes')
            ->withTimestamps();
    }

    public function designCollections(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DesignCollection::class);
    }

    /**
     * Get the user's cart items
     */
    public function cartItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the user's favorite products
     */
    public function favorites(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Get the user's favorite products (many-to-many relationship)
     */
    public function favoriteProducts(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'favorites')
            ->withTimestamps();
    }

    /**
     * Get the user's Expo push tokens
     */
    public function expoPushTokens(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(ExpoPushToken::class, 'tokenable');
    }

    /**
     * Get active Expo push tokens
     */
    public function activeExpoPushTokens(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->expoPushTokens()->active();
    }

    /**
     * Route notifications for Expo push notifications
     */
    public function routeNotificationForExpoPush(): array
    {
        return $this->activeExpoPushTokens()->pluck('token')->toArray();
    }

    /**
     * Get validation rules for user registration
     */
    public static function getValidationRules($isUpdate = false): array
    {
        if ($isUpdate) {
            // For updates, make everything optional and skip client_type conditional logic
            $userId = request()->user() ? request()->user()->id : null;
            return [
                'client_type' => 'sometimes|in:individual,company',
                'full_name' => 'sometimes|string|max:255',
                'company_name' => 'sometimes|string|max:255',
                'contact_person' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:users,email,' . $userId,
                'phone' => 'sometimes|string|max:20|unique:users,phone,' . $userId,
                'address' => 'nullable|string|max:500',
                'city' => 'nullable|string|max:100',
                'country' => 'nullable|string|max:100',
                'vat_number' => 'nullable|string|max:50',
                'cr_number' => 'nullable|string|max:50',
                'notes' => 'nullable|string|max:1000',
            ];
        }

        // For registration (not update)
        $rules = [
            'client_type' => 'required|in:individual,company',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'vat_number' => 'nullable|string|max:50',
            'cr_number' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:1000',
            'password' => 'required|string|min:8|confirmed',
        ];

        // Conditional validation based on client type
        if (request()->input('client_type') === 'individual') {
            $rules['full_name'] = 'required|string|max:255';
            $rules['company_name'] = 'nullable|string|max:255';
            $rules['contact_person'] = 'nullable|string|max:255';
        } else {
            $rules['company_name'] = 'required|string|max:255';
            $rules['contact_person'] = 'required|string|max:255';
            $rules['full_name'] = 'nullable|string|max:255';
        }

        return $rules;
    }

    /**
     * Get file upload validation rules
     */
    public static function getFileUploadRules(): array
    {
        return [
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
            'type' => 'required|in:cr_document,vat_document'
        ];
    }

    /**
     * Get document URL
     */
    public function getCrDocumentUrlAttribute()
    {
        return $this->cr_document_path ? Storage::url($this->cr_document_path) : null;
    }

    /**
     * Get VAT document URL
     */
    public function getVatDocumentUrlAttribute()
    {
        return $this->vat_document_path ? Storage::url($this->vat_document_path) : null;
    }

    /**
     * User's support tickets
     */
    public function supportTickets(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }

    /**
     * User's support ticket replies
     */
    public function supportTicketReplies(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SupportTicketReply::class);
    }

    /**
     * User's reviews
     */
    public function reviews(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Review::class);
    }
}
