<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductSku>
 */
class ProductSkuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'size_attribute_id' => ProductAttribute::factory()->size(),
            'color_attribute_id' => ProductAttribute::factory()->color(),
            'sku' => strtoupper(fake()->unique()->bothify('??##??####')),
            'price' => fake()->randomFloat(2, 5, 1000),
            'quantity' => fake()->numberBetween(0, 100),
        ];
    }
}
