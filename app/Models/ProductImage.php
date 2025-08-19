<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'product_variation_id',
        'image_url',
        'alt_text',
        'type',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function productVariation(): BelongsTo
    {
        return $this->belongsTo(ProductVariation::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeMain($query)
    {
        return $query->where('type', 'main');
    }

    public function scopeGallery($query)
    {
        return $query->where('type', 'gallery');
    }

    public function scopeThumbnail($query)
    {
        return $query->where('type', 'thumbnail');
    }

    // Helper Methods
    public function isMain(): bool
    {
        return $this->type === 'main';
    }

    public function isGallery(): bool
    {
        return $this->type === 'gallery';
    }

    public function isThumbnail(): bool
    {
        return $this->type === 'thumbnail';
    }
}
