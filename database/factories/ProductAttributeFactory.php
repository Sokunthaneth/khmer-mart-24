<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductAttribute>
 */
class ProductAttributeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['color', 'size']);

        return [
            'type' => $type,
            'value' => $type === 'color'
                ? fake()->randomElement(['Red', 'Blue', 'Green', 'Black', 'White', 'Yellow', 'Purple', 'Orange'])
                : fake()->randomElement(['XS', 'S', 'M', 'L', 'XL', 'XXL', '28', '30', '32', '34', '36', '38']),
        ];
    }

    /**
     * Create a color attribute.
     */
    public function color(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'color',
            'value' => fake()->randomElement(['Red', 'Blue', 'Green', 'Black', 'White', 'Yellow', 'Purple', 'Orange']),
        ]);
    }

    /**
     * Create a size attribute.
     */
    public function size(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'size',
            'value' => fake()->randomElement(['XS', 'S', 'M', 'L', 'XL', 'XXL', '28', '30', '32', '34', '36', '38']),
        ]);
    }
}
