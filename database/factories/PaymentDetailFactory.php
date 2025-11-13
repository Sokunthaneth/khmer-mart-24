<?php

namespace Database\Factories;

use App\Models\OrderDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentDetail>
 */
class PaymentDetailFactory extends Factory
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
            'amount' => fake()->numberBetween(1000, 50000),
            'provider' => fake()->randomElement(['PayPal', 'Stripe', 'Square', 'Bank Transfer', 'Cash on Delivery']),
            'status' => fake()->randomElement(['pending', 'processing', 'completed', 'failed', 'cancelled', 'refunded']),
        ];
    }
}
