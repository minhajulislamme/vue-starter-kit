<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subcategory extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'image',
        'icon',
        'icon_class',
        'color_code',
        'level',
        'path',
        'sort_order',
        'is_active',
        'is_featured',
        'show_in_menu',
        'meta_title',
        'meta_description',
        'product_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'show_in_menu' => 'boolean',
    ];

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInMenu($query)
    {
        return $query->where('show_in_menu', true);
    }

    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    // Helper Methods
    public function getFullName(): string
    {
        return $this->category->name . ' > ' . $this->name;
    }

    public function getBreadcrumb(): array
    {
        if ($this->path) {
            return explode(' > ', $this->path);
        }

        return [$this->category->name, $this->name];
    }

    public function updateProductCount(): void
    {
        $this->update([
            'product_count' => $this->products()->count()
        ]);
    }

    public function getImageUrl(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function getIconUrl(): ?string
    {
        return $this->icon ? asset('storage/' . $this->icon) : null;
    }
}
