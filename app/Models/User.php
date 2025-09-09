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
     * Get validation rules for user registration
     */
    public static function getValidationRules($isUpdate = false): array
    {
        $rules = [
            'client_type' => 'required|in:individual,company',
            'email' => 'required|email|unique:users,email' . ($isUpdate ? ',' . request()->user()->id : ''),
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'vat_number' => 'nullable|string|max:50',
            'cr_number' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:1000',
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

        if (!$isUpdate) {
            $rules['password'] = 'required|string|min:8|confirmed';
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
}
