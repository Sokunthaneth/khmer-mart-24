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
        // Create 20 products using the factory
        Product::factory()->count(20)->create();

        // Create some specific featured products
        $featuredProducts = [
            [
                'name' => 'iPhone 15 Pro',
                'description' => 'Latest iPhone model with titanium design and advanced camera system. Features the powerful A17 Pro chip for incredible performance.',
                'summary' => 'Premium smartphone with cutting-edge technology',
                'cover' => 'https://images.unsplash.com/photo-1592286667157-c9d60f36dfab?w=400&h=400&fit=crop',
                'category_id' => '1'
            ],
            [
                'name' => 'MacBook Pro M3',
                'description' => 'Revolutionary MacBook Pro with M3 chip delivers exceptional performance for professionals and creatives.',
                'summary' => 'Professional laptop with M3 chip technology',
                'cover' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=400&h=400&fit=crop',
                'category_id' => '1'
            ],
            [
                'name' => 'Wireless Headphones Pro',
                'description' => 'Premium wireless headphones with active noise cancellation and superior sound quality.',
                'summary' => 'High-quality wireless audio experience',
                'cover' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=400&fit=crop',
                'category_id' => '1'
            ]
        ];

        foreach ($featuredProducts as $product) {
            Product::create($product);
        }
    }
}
