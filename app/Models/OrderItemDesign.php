<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItemDesign extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_item_id',
        'design_id',
        'notes',
        'priority'
    ];

    protected $casts = [
        'priority' => 'integer'
    ];

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function design(): BelongsTo
    {
        return $this->belongsTo(Design::class);
    }
}
