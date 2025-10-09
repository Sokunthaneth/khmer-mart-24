<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductSku;
use App\Models\ProductAttribute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSkuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        $colorAttributes = ProductAttribute::where('type', 'color')->get();
        $sizeAttributes = ProductAttribute::where('type', 'size')->get();

        // Create 2-5 SKUs for each product
        foreach ($products as $product) {
            $skuCount = rand(2, 5);

            for ($i = 0; $i < $skuCount; $i++) {
                ProductSku::create([
                    'product_id' => $product->id,
                    'size_attribute_id' => $sizeAttributes->random()->id,
                    'color_attribute_id' => $colorAttributes->random()->id,
                    'sku' => strtoupper(fake()->unique()->bothify('??##??####')),
                    'price' => fake()->randomFloat(2, 5, 1000),
                    'quantity' => fake()->numberBetween(0, 100),
                ]);
            }
        }
    }
}
