<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductAttributeValue extends Model
{
    protected $fillable = [
        'product_attribute_id',
        'value',
        'slug',
        'color_code',
        'image',
        'price_adjustment',
        'sort_order',
        'is_default',
    ];

    protected $casts = [
        'price_adjustment' => 'decimal:2',
        'is_default' => 'boolean',
    ];

    // Relationships
    public function productAttribute(): BelongsTo
    {
        return $this->belongsTo(ProductAttribute::class);
    }

    public function variations(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductVariation::class,
            'product_variation_attributes',
            'product_attribute_value_id',
            'product_variation_id'
        );
    }

    public function variationAttributes(): HasMany
    {
        return $this->hasMany(ProductVariationAttribute::class);
    }

    // Scopes
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    // Helper Methods
    public function hasColorCode(): bool
    {
        return !empty($this->color_code);
    }

    public function hasImage(): bool
    {
        return !empty($this->image);
    }

    public function getDisplayValue(): string
    {
        return $this->value;
    }

    public function hasPriceAdjustment(): bool
    {
        return $this->price_adjustment != 0;
    }
}
