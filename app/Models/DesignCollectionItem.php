<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DesignCollectionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection_id',
        'design_id',
        'notes',
        'added_at'
    ];

    protected $casts = [
        'added_at' => 'datetime',
    ];

    // Relationships
    public function collection(): BelongsTo
    {
        return $this->belongsTo(DesignCollection::class);
    }

    public function design(): BelongsTo
    {
        return $this->belongsTo(Design::class);
    }

    // Scopes
    public function scopeForCollection($query, $collectionId)
    {
        return $query->where('collection_id', $collectionId);
    }

    public function scopeForDesign($query, $designId)
    {
        return $query->where('design_id', $designId);
    }
}
