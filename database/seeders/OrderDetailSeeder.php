<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\OrderDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        // Create 1-3 orders for each user
        foreach ($users as $user) {
            $orderCount = rand(1, 3);

            for ($i = 0; $i < $orderCount; $i++) {
                OrderDetail::create([
                    'user_id' => $user->id,
                    'total' => fake()->numberBetween(1000, 50000),
                ]);
            }
        }
    }
}
