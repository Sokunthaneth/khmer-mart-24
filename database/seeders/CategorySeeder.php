<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics', 'description' => 'Electronic devices and gadgets including phones, laptops, and accessories'],
            ['name' => 'Clothing', 'description' => 'Fashion and apparel for men, women, and children'],
            ['name' => 'Home & Garden', 'description' => 'Home improvement, furniture, and garden supplies'],
            ['name' => 'Sports & Outdoors', 'description' => 'Sports equipment, outdoor gear, and fitness accessories'],
            ['name' => 'Books & Media', 'description' => 'Books, movies, music, and digital media content'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'description' => $category['description']
            ]);
        }
    }
}
