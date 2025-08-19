<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductAttribute extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'type',
        'description',
        'sort_order',
        'is_required',
        'is_variation',
        'is_visible',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_variation' => 'boolean',
        'is_visible' => 'boolean',
    ];

    // Relationships
    public function values(): HasMany
    {
        return $this->hasMany(ProductAttributeValue::class)->orderBy('sort_order');
    }

    public function variationAttributes(): HasMany
    {
        return $this->hasMany(ProductVariationAttribute::class);
    }

    // Scopes
    public function scopeForVariations($query)
    {
        return $query->where('is_variation', true);
    }

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    // Helper Methods
    public function isColor(): bool
    {
        return $this->type === 'color';
    }

    public function isSelect(): bool
    {
        return $this->type === 'select';
    }

    public function isText(): bool
    {
        return $this->type === 'text';
    }

    public function isNumber(): bool
    {
        return $this->type === 'number';
    }
}
