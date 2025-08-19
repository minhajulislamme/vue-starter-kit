<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Subcategory::truncate();
        Category::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Main Categories with simple data
        $categories = [
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'description' => 'Electronic devices and gadgets',
                'icon_class' => 'fas fa-laptop',
                'color_code' => '#3B82F6',
                'is_featured' => true,
                'level' => 0,
                'sort_order' => 1,
                'subcategories' => ['Smartphones', 'Laptops', 'Audio', 'Cameras']
            ],
            [
                'name' => 'Fashion',
                'slug' => 'fashion',
                'description' => 'Clothing and accessories',
                'icon_class' => 'fas fa-tshirt',
                'color_code' => '#EC4899',
                'is_featured' => true,
                'level' => 0,
                'sort_order' => 2,
                'subcategories' => ['Men\'s Clothing', 'Women\'s Clothing', 'Shoes', 'Accessories']
            ],
            [
                'name' => 'Home & Garden',
                'slug' => 'home-garden',
                'description' => 'Home and garden essentials',
                'icon_class' => 'fas fa-home',
                'color_code' => '#10B981',
                'is_featured' => true,
                'level' => 0,
                'sort_order' => 3,
                'subcategories' => ['Furniture', 'Kitchen', 'Decor', 'Garden']
            ]
        ];

        foreach ($categories as $categoryData) {
            $subcategories = $categoryData['subcategories'];
            unset($categoryData['subcategories']);
            
            $category = Category::create($categoryData);

            // Create subcategories
            foreach ($subcategories as $index => $subcategoryName) {
                Subcategory::create([
                    'category_id' => $category->id,
                    'name' => $subcategoryName,
                    'slug' => Str::slug($subcategoryName),
                    'description' => "Quality {$subcategoryName} products",
                    'level' => 1,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                    'show_in_menu' => true,
                ]);
            }
        }
    }
}
