<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_name',
        'contact_person',
        'email',
        'phone',
        'address',
        'notes',
        'status',
        'priority',
        'marketer_id',
        'category_id',
        'user_id',
        'last_contact_date',
        'next_follow_up',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'last_contact_date' => 'datetime',
        'next_follow_up' => 'datetime',
    ];

    /**
     * Get the marketer assigned to this lead
     */
    public function marketer()
    {
        return $this->belongsTo(Marketer::class);
    }

    /**
     * Get the communications for this lead
     */
    public function communications()
    {
        return $this->hasMany(LeadCommunication::class);
    }

    /**
     * Get the latest communication for this lead
     */
    public function latestCommunication()
    {
        return $this->hasOne(LeadCommunication::class)->latest('communication_date');
    }

    /**
     * Get the category assigned to this lead
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the user associated with this lead (if converted)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
