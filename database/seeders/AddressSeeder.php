<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Address;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users
        $users = User::all();

        // Create 2-3 addresses for each user
        foreach ($users as $user) {
            $addressCount = rand(1, 3);
            Address::factory()->count($addressCount)->create([
                'user_id' => $user->id
            ]);
        }
    }
}
