<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->randomElement(['Home', 'Work', 'Office', 'Other']),
            'address_line_1' => fake()->streetAddress(),
            'address_line_2' => fake()->optional()->secondaryAddress(),
            'country' => fake()->country(),
            'city' => fake()->city(),
            'postal_code' => fake()->postcode(),
            'landmark' => fake()->optional()->words(3, true),
            'phone_number' => fake()->phoneNumber(),
        ];
    }
}
