<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all categories
        $categories = Category::all();

        // Create 4 featured products manually
        $featuredProducts = [
            [
                'name' => 'iPhone 14 Pro',
                'price' => 999.99,
                'stock' => 50,
                'description' => 'Latest iPhone model with advanced features'
            ],
            [
                'name' => 'MacBook Pro M2',
                'price' => 1499.99,
                'stock' => 30,
                'description' => 'Powerful laptop for professionals'
            ],
            [
                'name' => 'USB-C Cable',
                'price' => 19.99,
                'stock' => 200,
                'description' => 'High-quality charging cable'
            ],
            [
                'name' => 'Smart Watch Pro',
                'price' => 299.99,
                'stock' => 75,
                'description' => 'Advanced fitness tracking and notifications'
            ]
        ];

        // Create featured products with random categories
        foreach ($featuredProducts as $product) {
            $category = $categories->random();
            Product::create([
                'category_id' => $category->id,
                'name' => $product['name'],
                'slug' => Str::slug($product['name']),
                'sku' => strtoupper(Str::random(8)),
                'price' => $product['price'],
                'stock' => $product['stock'],
                'description' => $product['description']
            ]);
        }

        // Create additional random products for each category
        foreach ($categories as $category) {
            Product::factory()
                ->count(4) // Creates 4 products per category
                ->create(['category_id' => $category->id]);
        }
    }
}
