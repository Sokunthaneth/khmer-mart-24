<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subcategories = [
            // Electronics subcategories
            ['parent_id' => 1, 'name' => 'Smartphones', 'description' => 'Mobile phones and smartphones'],
            ['parent_id' => 1, 'name' => 'Laptops', 'description' => 'Portable computers and laptops'],
            ['parent_id' => 1, 'name' => 'Headphones', 'description' => 'Audio devices and headphones'],
            ['parent_id' => 1, 'name' => 'Tablets', 'description' => 'Tablet computers and e-readers'],

            // Clothing subcategories
            ['parent_id' => 2, 'name' => 'Men\'s Clothing', 'description' => 'Clothing and apparel for men'],
            ['parent_id' => 2, 'name' => 'Women\'s Clothing', 'description' => 'Clothing and apparel for women'],
            ['parent_id' => 2, 'name' => 'Kids Clothing', 'description' => 'Clothing for children'],
            ['parent_id' => 2, 'name' => 'Shoes', 'description' => 'Footwear for all ages'],

            // Home & Garden subcategories
            ['parent_id' => 3, 'name' => 'Furniture', 'description' => 'Home furniture and decor'],
            ['parent_id' => 3, 'name' => 'Kitchen Appliances', 'description' => 'Kitchen tools and appliances'],
            ['parent_id' => 3, 'name' => 'Garden Tools', 'description' => 'Gardening equipment and tools'],
        ];

        foreach ($subcategories as $subcategory) {
            SubCategory::create($subcategory);
        }
    }
}
