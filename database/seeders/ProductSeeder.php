<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'category' => 'Phones',
                'name' => 'iPhone 14 Pro',
                'price' => 999.99,
                'stock' => 50,
                'description' => 'Latest iPhone model with advanced features'
            ],
            [
                'category' => 'Laptops',
                'name' => 'MacBook Pro M2',
                'price' => 1499.99,
                'stock' => 30,
                'description' => 'Powerful laptop for professionals'
            ],
            [
                'category' => 'Accessories',
                'name' => 'USB-C Cable',
                'price' => 19.99,
                'stock' => 200,
                'description' => 'High-quality charging cable'
            ],
            // Add more products here
        ];

        foreach ($products as $product) {
            $category = Category::where('name', $product['category'])->first();

            if ($category) {
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
        }
    }
}
