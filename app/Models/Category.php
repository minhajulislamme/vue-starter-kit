<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'icon',
        'icon_class',
        'color_code',
        'parent_id',
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

    // Boot method to handle automatic slug generation
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    // Relationships
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function subcategories(): HasMany
    {
        return $this->hasMany(Subcategory::class)->orderBy('sort_order');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order');
    }

    public function descendants(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->with('descendants');
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

    public function scopeRootCategories($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    // Helper Methods
    public function updatePath()
    {
        $path = [$this->slug];
        $parent = $this->parent;

        while ($parent) {
            array_unshift($path, $parent->slug);
            $parent = $parent->parent;
        }

        $this->update(['path' => implode('/', $path)]);
    }

    public function updateLevel()
    {
        $level = 0;
        $parent = $this->parent;

        while ($parent) {
            $level++;
            $parent = $parent->parent;
        }

        $this->update(['level' => $level]);
    }

    public function getBreadcrumbs(): array
    {
        $breadcrumbs = [];
        $category = $this;

        while ($category) {
            array_unshift($breadcrumbs, [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'url' => route('categories.show', $category->slug)
            ]);
            $category = $category->parent;
        }

        return $breadcrumbs;
    }

    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }

    public function isRoot(): bool
    {
        return is_null($this->parent_id);
    }

    public function getIcon(): ?string
    {
        return $this->icon ?: $this->icon_class;
    }

    public function hasIcon(): bool
    {
        return !empty($this->icon) || !empty($this->icon_class);
    }

    public function getDisplayName(): string
    {
        return $this->name;
    }

    public function getFullPath(): string
    {
        return $this->path ?: $this->slug;
    }

    public function getAllProducts()
    {
        $productIds = collect([$this->id]);
        
        // Get all descendant category IDs
        $descendants = $this->descendants()->pluck('id');
        $productIds = $productIds->merge($descendants);

        return Product::whereIn('category_id', $productIds);
    }

    public function updateProductCount()
    {
        $count = $this->getAllProducts()->count();
        $this->update(['product_count' => $count]);
        
        // Update parent counts as well
        if ($this->parent) {
            $this->parent->updateProductCount();
        }
    }

    public function getProductCount(): int
    {
        return $this->product_count ?: $this->getAllProducts()->count();
    }
}
