<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use Illuminate\Support\Str;

class ProductAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attributes = [
            [
                'name' => 'Size',
                'type' => 'select',
                'description' => 'Product size options',
                'is_variation' => true,
                'is_visible' => true,
                'values' => [
                    ['value' => 'Extra Small', 'slug' => 'xs'],
                    ['value' => 'Small', 'slug' => 's'],
                    ['value' => 'Medium', 'slug' => 'm', 'is_default' => true],
                    ['value' => 'Large', 'slug' => 'l'],
                    ['value' => 'Extra Large', 'slug' => 'xl'],
                    ['value' => '2XL', 'slug' => '2xl'],
                    ['value' => '3XL', 'slug' => '3xl'],
                ]
            ],
            [
                'name' => 'Color',
                'type' => 'color',
                'description' => 'Product color variations',
                'is_variation' => true,
                'is_visible' => true,
                'values' => [
                    ['value' => 'Black', 'slug' => 'black', 'color_code' => '#000000'],
                    ['value' => 'White', 'slug' => 'white', 'color_code' => '#FFFFFF', 'is_default' => true],
                    ['value' => 'Red', 'slug' => 'red', 'color_code' => '#EF4444'],
                    ['value' => 'Blue', 'slug' => 'blue', 'color_code' => '#3B82F6'],
                    ['value' => 'Green', 'slug' => 'green', 'color_code' => '#10B981'],
                    ['value' => 'Yellow', 'slug' => 'yellow', 'color_code' => '#F59E0B'],
                    ['value' => 'Purple', 'slug' => 'purple', 'color_code' => '#8B5CF6'],
                    ['value' => 'Pink', 'slug' => 'pink', 'color_code' => '#EC4899'],
                    ['value' => 'Gray', 'slug' => 'gray', 'color_code' => '#6B7280'],
                    ['value' => 'Navy', 'slug' => 'navy', 'color_code' => '#1E3A8A'],
                ]
            ],
            [
                'name' => 'Material',
                'type' => 'select',
                'description' => 'Product material composition',
                'is_variation' => true,
                'is_visible' => true,
                'values' => [
                    ['value' => 'Cotton', 'slug' => 'cotton'],
                    ['value' => 'Polyester', 'slug' => 'polyester'],
                    ['value' => 'Leather', 'slug' => 'leather'],
                    ['value' => 'Denim', 'slug' => 'denim'],
                    ['value' => 'Silk', 'slug' => 'silk'],
                    ['value' => 'Wool', 'slug' => 'wool'],
                    ['value' => 'Linen', 'slug' => 'linen'],
                    ['value' => 'Synthetic', 'slug' => 'synthetic'],
                ]
            ],
            [
                'name' => 'Storage',
                'type' => 'select',
                'description' => 'Storage capacity for electronic devices',
                'is_variation' => true,
                'is_visible' => true,
                'values' => [
                    ['value' => '64GB', 'slug' => '64gb', 'price_adjustment' => 0],
                    ['value' => '128GB', 'slug' => '128gb', 'price_adjustment' => 50],
                    ['value' => '256GB', 'slug' => '256gb', 'price_adjustment' => 100],
                    ['value' => '512GB', 'slug' => '512gb', 'price_adjustment' => 200],
                    ['value' => '1TB', 'slug' => '1tb', 'price_adjustment' => 300],
                ]
            ],
            [
                'name' => 'RAM',
                'type' => 'select',
                'description' => 'Memory capacity for electronic devices',
                'is_variation' => true,
                'is_visible' => true,
                'values' => [
                    ['value' => '4GB', 'slug' => '4gb', 'price_adjustment' => 0],
                    ['value' => '8GB', 'slug' => '8gb', 'price_adjustment' => 75],
                    ['value' => '16GB', 'slug' => '16gb', 'price_adjustment' => 150],
                    ['value' => '32GB', 'slug' => '32gb', 'price_adjustment' => 300],
                ]
            ],
            [
                'name' => 'Style',
                'type' => 'select',
                'description' => 'Product style variations',
                'is_variation' => false,
                'is_visible' => true,
                'values' => [
                    ['value' => 'Classic', 'slug' => 'classic'],
                    ['value' => 'Modern', 'slug' => 'modern'],
                    ['value' => 'Vintage', 'slug' => 'vintage'],
                    ['value' => 'Casual', 'slug' => 'casual'],
                    ['value' => 'Formal', 'slug' => 'formal'],
                    ['value' => 'Sport', 'slug' => 'sport'],
                ]
            ],
        ];

        foreach ($attributes as $index => $attributeData) {
            $values = $attributeData['values'];
            unset($attributeData['values']);
            
            $attributeData['slug'] = Str::slug($attributeData['name']);
            $attributeData['sort_order'] = $index;
            
            $attribute = ProductAttribute::create($attributeData);

            // Create attribute values
            foreach ($values as $valueIndex => $valueData) {
                $valueData['product_attribute_id'] = $attribute->id;
                $valueData['sort_order'] = $valueIndex;
                
                ProductAttributeValue::create($valueData);
            }
        }

        echo "Created " . ProductAttribute::count() . " product attributes with " . ProductAttributeValue::count() . " values.\n";
    }
}
