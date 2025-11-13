<?php

namespace Database\Factories;

use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductSku;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => OrderDetail::factory(),
            'product_id' => Product::factory(),
            'products_sku_id' => ProductSku::factory(),
            'quantity' => fake()->numberBetween(1, 10),
        ];
    }
}
