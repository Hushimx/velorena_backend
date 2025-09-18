<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDesign extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'title',
        'image_url',
        'thumbnail_url',
        'design_data',
        'notes',
        'priority'
    ];

    protected $casts = [
        'priority' => 'integer',
        'design_data' => 'array'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
