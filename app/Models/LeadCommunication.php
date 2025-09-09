<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadCommunication extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'lead_id',
        'marketer_id',
        'type',
        'notes',
        'communication_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'communication_date' => 'datetime',
    ];

    /**
     * Get the lead that owns this communication
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    /**
     * Get the marketer that made this communication
     */
    public function marketer()
    {
        return $this->belongsTo(Marketer::class);
    }
}
