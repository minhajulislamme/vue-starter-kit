<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ProductVariationAttribute;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get categories and subcategories
        $electronics = Category::where('name', 'Electronics')->first();
        $fashion = Category::where('name', 'Fashion')->first();
        $home = Category::where('name', 'Home & Garden')->first();

        $smartphones = Subcategory::where('name', 'Smartphones')->first();
        $laptops = Subcategory::where('name', 'Laptops')->first();
        $mensClothing = Subcategory::where('name', 'Men\'s Clothing')->first();
        $womensClothing = Subcategory::where('name', 'Women\'s Clothing')->first();

        // Get attributes for variations
        $sizeAttr = ProductAttribute::where('name', 'Size')->first();
        $colorAttr = ProductAttribute::where('name', 'Color')->first();
        $storageAttr = ProductAttribute::where('name', 'Storage')->first();
        $ramAttr = ProductAttribute::where('name', 'RAM')->first();

        // Simple Products
        $simpleProducts = [
            [
                'name' => 'Wireless Bluetooth Headphones',
                'slug' => 'wireless-bluetooth-headphones',
                'description' => 'High-quality wireless headphones with noise cancellation and 30-hour battery life. Perfect for music lovers and professionals.',
                'short_description' => 'Premium wireless headphones with noise cancellation.',
                'sku' => 'WBH-001',
                'type' => 'simple',
                'status' => 'active',
                'price' => 199.99,
                'compare_price' => 249.99,
                'cost_price' => 120.00,
                'stock_quantity' => 50,
                'category_id' => $electronics->id,
                'subcategory_id' => $smartphones->id,
                'is_featured' => true,
                'featured_image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800',
                'gallery_images' => [
                    'https://images.unsplash.com/photo-1484704849700-f032a568e944?w=800',
                    'https://images.unsplash.com/photo-1583394838336-acd977736f90?w=800',
                ],
                'published_at' => now(),
            ],
            [
                'name' => 'Smart Watch Series 8',
                'slug' => 'smart-watch-series-8',
                'description' => 'Advanced smartwatch with health monitoring, GPS, and 2-day battery life. Track your fitness and stay connected.',
                'short_description' => 'Feature-rich smartwatch for health and fitness.',
                'sku' => 'SW-008',
                'type' => 'simple',
                'status' => 'active',
                'price' => 399.99,
                'compare_price' => 449.99,
                'cost_price' => 200.00,
                'stock_quantity' => 25,
                'category_id' => $electronics->id,
                'subcategory_id' => $smartphones->id,
                'is_featured' => true,
                'featured_image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800',
                'gallery_images' => [
                    'https://images.unsplash.com/photo-1510017098667-27dfc8942d5e?w=800',
                ],
                'published_at' => now(),
            ],
        ];

        // Create simple products
        foreach ($simpleProducts as $productData) {
            Product::create($productData);
        }

        // Variable Products with Variations
        $variableProducts = [
            [
                'name' => 'Premium Cotton T-Shirt',
                'slug' => 'premium-cotton-t-shirt',
                'description' => 'Ultra-soft premium cotton t-shirt perfect for everyday wear. Made from 100% organic cotton with superior comfort and durability.',
                'short_description' => 'Comfortable premium cotton t-shirt available in multiple sizes and colors.',
                'sku' => 'PCT-001',
                'type' => 'variable',
                'status' => 'active',
                'price' => null, // Will be determined by variations
                'compare_price' => 34.99,
                'cost_price' => 12.00,
                'stock_quantity' => 0, // Managed by variations
                'manage_stock' => false,
                'category_id' => $fashion->id,
                'subcategory_id' => $mensClothing->id,
                'is_featured' => true,
                'featured_image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=800',
                'gallery_images' => [
                    'https://images.unsplash.com/photo-1583743814966-8936f37f4ec2?w=800',
                    'https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=800',
                ],
                'published_at' => now(),
                'variations' => [
                    ['size' => 'Small', 'color' => 'Black', 'price' => 24.99, 'stock' => 15, 'sku' => 'PCT-001-SM-BLK'],
                    ['size' => 'Small', 'color' => 'White', 'price' => 24.99, 'stock' => 12, 'sku' => 'PCT-001-SM-WHT'],
                    ['size' => 'Small', 'color' => 'Blue', 'price' => 24.99, 'stock' => 10, 'sku' => 'PCT-001-SM-BLU'],
                    ['size' => 'Medium', 'color' => 'Black', 'price' => 24.99, 'stock' => 20, 'sku' => 'PCT-001-MD-BLK', 'is_default' => true],
                    ['size' => 'Medium', 'color' => 'White', 'price' => 24.99, 'stock' => 18, 'sku' => 'PCT-001-MD-WHT'],
                    ['size' => 'Medium', 'color' => 'Blue', 'price' => 24.99, 'stock' => 16, 'sku' => 'PCT-001-MD-BLU'],
                    ['size' => 'Large', 'color' => 'Black', 'price' => 26.99, 'stock' => 10, 'sku' => 'PCT-001-LG-BLK'],
                    ['size' => 'Large', 'color' => 'White', 'price' => 26.99, 'stock' => 8, 'sku' => 'PCT-001-LG-WHT'],
                    ['size' => 'Extra Large', 'color' => 'Black', 'price' => 28.99, 'stock' => 5, 'sku' => 'PCT-001-XL-BLK'],
                ]
            ],
            [
                'name' => 'Gaming Laptop Pro',
                'slug' => 'gaming-laptop-pro',
                'description' => 'High-performance gaming laptop with latest GPU, fast SSD storage, and premium cooling system. Perfect for gaming enthusiasts and content creators.',
                'short_description' => 'Powerful gaming laptop with configurable RAM and storage options.',
                'sku' => 'GLP-001',
                'type' => 'variable',
                'status' => 'active',
                'price' => null,
                'compare_price' => 1899.99,
                'cost_price' => 800.00,
                'stock_quantity' => 0,
                'manage_stock' => false,
                'category_id' => $electronics->id,
                'subcategory_id' => $laptops->id,
                'is_featured' => true,
                'featured_image' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=800',
                'gallery_images' => [
                    'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=800',
                    'https://images.unsplash.com/photo-1593640408182-31c70c8268f5?w=800',
                ],
                'published_at' => now(),
                'variations' => [
                    ['ram' => '8GB', 'storage' => '256GB', 'price' => 1299.99, 'stock' => 5, 'sku' => 'GLP-001-8GB-256GB'],
                    ['ram' => '8GB', 'storage' => '512GB', 'price' => 1399.99, 'stock' => 7, 'sku' => 'GLP-001-8GB-512GB'],
                    ['ram' => '16GB', 'storage' => '512GB', 'price' => 1599.99, 'stock' => 8, 'sku' => 'GLP-001-16GB-512GB', 'is_default' => true],
                    ['ram' => '16GB', 'storage' => '1TB', 'price' => 1799.99, 'stock' => 6, 'sku' => 'GLP-001-16GB-1TB'],
                    ['ram' => '32GB', 'storage' => '1TB', 'price' => 1999.99, 'stock' => 3, 'sku' => 'GLP-001-32GB-1TB'],
                ]
            ],
        ];

        // Create variable products with variations
        foreach ($variableProducts as $productData) {
            $variations = $productData['variations'];
            unset($productData['variations']);

            $product = Product::create($productData);

            // Create variations
            foreach ($variations as $variationData) {
                $variation = ProductVariation::create([
                    'product_id' => $product->id,
                    'sku' => $variationData['sku'],
                    'price' => $variationData['price'],
                    'compare_price' => $productData['compare_price'],
                    'cost_price' => $productData['cost_price'],
                    'stock_quantity' => $variationData['stock'],
                    'manage_stock' => true,
                    'stock_status' => $variationData['stock'] > 0 ? 'in_stock' : 'out_of_stock',
                    'is_default' => $variationData['is_default'] ?? false,
                    'is_active' => true,
                ]);

                // Create variation attributes
                if (isset($variationData['size'])) {
                    $sizeValue = ProductAttributeValue::where('product_attribute_id', $sizeAttr->id)
                        ->where('value', $variationData['size'])->first();
                    if ($sizeValue) {
                        ProductVariationAttribute::create([
                            'product_variation_id' => $variation->id,
                            'product_attribute_id' => $sizeAttr->id,
                            'product_attribute_value_id' => $sizeValue->id,
                        ]);
                    }
                }

                if (isset($variationData['color'])) {
                    $colorValue = ProductAttributeValue::where('product_attribute_id', $colorAttr->id)
                        ->where('value', $variationData['color'])->first();
                    if ($colorValue) {
                        ProductVariationAttribute::create([
                            'product_variation_id' => $variation->id,
                            'product_attribute_id' => $colorAttr->id,
                            'product_attribute_value_id' => $colorValue->id,
                        ]);
                    }
                }

                if (isset($variationData['ram'])) {
                    $ramValue = ProductAttributeValue::where('product_attribute_id', $ramAttr->id)
                        ->where('value', $variationData['ram'])->first();
                    if ($ramValue) {
                        ProductVariationAttribute::create([
                            'product_variation_id' => $variation->id,
                            'product_attribute_id' => $ramAttr->id,
                            'product_attribute_value_id' => $ramValue->id,
                        ]);
                    }
                }

                if (isset($variationData['storage'])) {
                    $storageValue = ProductAttributeValue::where('product_attribute_id', $storageAttr->id)
                        ->where('value', $variationData['storage'])->first();
                    if ($storageValue) {
                        ProductVariationAttribute::create([
                            'product_variation_id' => $variation->id,
                            'product_attribute_id' => $storageAttr->id,
                            'product_attribute_value_id' => $storageValue->id,
                        ]);
                    }
                }
            }
        }
    }
}
