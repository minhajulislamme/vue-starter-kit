<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'sku',
        'type',
        'status',
        'price',
        'compare_price',
        'cost_price',
        'stock_quantity',
        'manage_stock',
        'low_stock_threshold',
        'stock_status',
        'weight',
        'dimensions',
        'is_virtual',
        'is_downloadable',
        'is_featured',
        'meta_title',
        'meta_description',
        'category_id',
        'subcategory_id',
        'gallery_images',
        'featured_image',
        'sort_order',
        'published_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'manage_stock' => 'boolean',
        'is_virtual' => 'boolean',
        'is_downloadable' => 'boolean',
        'is_featured' => 'boolean',
        'gallery_images' => 'array',
        'published_at' => 'datetime',
    ];

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeSimple($query)
    {
        return $query->where('type', 'simple');
    }

    public function scopeVariable($query)
    {
        return $query->where('type', 'variable');
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_status', 'in_stock');
    }

    // Accessors & Mutators
    protected function dimensions(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? json_decode($value, true) : null,
            set: fn ($value) => $value ? json_encode($value) : null,
        );
    }

    // Helper Methods
    public function isSimple(): bool
    {
        return $this->type === 'simple';
    }

    public function isVariable(): bool
    {
        return $this->type === 'variable';
    }

    public function isInStock(): bool
    {
        if ($this->isVariable()) {
            return $this->variations()->where('stock_status', 'in_stock')->exists();
        }
        
        return $this->stock_status === 'in_stock';
    }

    public function getStockQuantity(): int
    {
        if ($this->isVariable()) {
            return $this->variations()->sum('stock_quantity');
        }
        
        return $this->stock_quantity;
    }

    public function getPrice()
    {
        if ($this->isVariable()) {
            $variation = $this->variations()->orderBy('price')->first();
            return $variation ? $variation->price : 0;
        }
        
        return $this->price;
    }

    public function getPriceRange()
    {
        if ($this->isSimple()) {
            return number_format($this->price, 2);
        }

        $variations = $this->variations;
        if ($variations->isEmpty()) {
            return '0.00';
        }

        $minPrice = $variations->min('price');
        $maxPrice = $variations->max('price');

        if ($minPrice == $maxPrice) {
            return number_format($minPrice, 2);
        }

        return number_format($minPrice, 2) . ' - ' . number_format($maxPrice, 2);
    }

    public function getMainImage(): ?string
    {
        if ($this->featured_image) {
            return $this->featured_image;
        }

        $mainImage = $this->images()->where('type', 'main')->first();
        return $mainImage ? $mainImage->image_url : null;
    }

    public function getGalleryImages(): array
    {
        if ($this->gallery_images && is_array($this->gallery_images)) {
            return $this->gallery_images;
        }

        return $this->images()
            ->where('type', 'gallery')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->pluck('image_url')
            ->toArray();
    }

    public function getAllImages(): array
    {
        $images = [];
        
        // Add main image first
        if ($mainImage = $this->getMainImage()) {
            $images[] = [
                'url' => $mainImage,
                'type' => 'main',
                'alt' => $this->name
            ];
        }

        // Add gallery images
        $galleryImages = $this->getGalleryImages();
        foreach ($galleryImages as $imageUrl) {
            $images[] = [
                'url' => $imageUrl,
                'type' => 'gallery',
                'alt' => $this->name
            ];
        }

        // Add images from variations if this is a variable product
        if ($this->isVariable()) {
            foreach ($this->variations as $variation) {
                if ($variation->image) {
                    $images[] = [
                        'url' => $variation->image,
                        'type' => 'variation',
                        'alt' => $variation->getDisplayName(),
                        'variation_id' => $variation->id
                    ];
                }
            }
        }

        return $images;
    }

    public function addGalleryImage(string $imageUrl, ?string $altText = null): void
    {
        $this->images()->create([
            'image_url' => $imageUrl,
            'alt_text' => $altText ?: $this->name,
            'type' => 'gallery',
            'sort_order' => $this->images()->where('type', 'gallery')->max('sort_order') + 1,
            'is_active' => true
        ]);
    }

    public function setMainImage(string $imageUrl, ?string $altText = null): void
    {
        // Remove existing main image
        $this->images()->where('type', 'main')->delete();
        
        // Add new main image
        $this->images()->create([
            'image_url' => $imageUrl,
            'alt_text' => $altText ?: $this->name,
            'type' => 'main',
            'sort_order' => 0,
            'is_active' => true
        ]);

        // Also update featured_image field
        $this->update(['featured_image' => $imageUrl]);
    }

    public function removeGalleryImage(string $imageUrl): void
    {
        $this->images()
            ->where('image_url', $imageUrl)
            ->where('type', 'gallery')
            ->delete();
    }

    public function updateImageOrder(array $imageUrls): void
    {
        foreach ($imageUrls as $index => $imageUrl) {
            $this->images()
                ->where('image_url', $imageUrl)
                ->update(['sort_order' => $index]);
        }
    }
}
