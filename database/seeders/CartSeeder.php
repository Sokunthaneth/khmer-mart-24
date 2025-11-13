<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Cart;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        // Create a cart for each user
        foreach ($users as $user) {
            Cart::create([
                'user_id' => $user->id,
                'total' => 0, // Will be updated when cart items are added
            ]);
        }
    }
}
