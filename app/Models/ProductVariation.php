<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ProductVariation extends Model
{
    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'compare_price',
        'cost_price',
        'stock_quantity',
        'manage_stock',
        'stock_status',
        'weight',
        'dimensions',
        'image',
        'is_default',
        'is_active',
        'is_free_shipping',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'manage_stock' => 'boolean',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'is_free_shipping' => 'boolean',
    ];

    // Relationships
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductAttributeValue::class,
            'product_variation_attributes',
            'product_variation_id',
            'product_attribute_value_id'
        )->withPivot('product_attribute_id');
    }

    public function variationAttributes(): HasMany
    {
        return $this->hasMany(ProductVariationAttribute::class);
    }

    // Accessors & Mutators
    protected function dimensions(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? json_decode($value, true) : null,
            set: fn ($value) => $value ? json_encode($value) : null,
        );
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_status', 'in_stock');
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    // Helper Methods
    public function isInStock(): bool
    {
        return $this->stock_status === 'in_stock';
    }

    public function getDisplayName(): string
    {
        $attributes = $this->attributeValues()
            ->with('productAttribute')
            ->get()
            ->map(function ($attributeValue) {
                return $attributeValue->productAttribute->name . ': ' . $attributeValue->value;
            })
            ->implode(', ');

        return $this->product->name . ($attributes ? ' (' . $attributes . ')' : '');
    }

    public function getAttributeString(): string
    {
        return $this->attributeValues()
            ->with('productAttribute')
            ->get()
            ->map(function ($attributeValue) {
                return $attributeValue->value;
            })
            ->implode(' / ');
    }

    public function getImage(): ?string
    {
        if ($this->image) {
            return $this->image;
        }

        $variationImage = $this->images()->where('type', 'main')->first();
        if ($variationImage) {
            return $variationImage->image_url;
        }

        return $this->product->getMainImage();
    }
}
