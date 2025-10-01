<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create an admin user
        User::create([
            'avatar' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=200&h=200&fit=crop&crop=face',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'username' => 'admin',
            'email' => 'admin@khmermart.com',
            'password' => Hash::make('password123'),
            'birth_of_date' => '1990-01-01',
            'phone_number' => '+855 12 345 678',
        ]);

        // Create a test user
        User::create([
            'avatar' => 'https://images.unsplash.com/photo-1494790108755-2616b612b1e0?w=200&h=200&fit=crop&crop=face',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
            'birth_of_date' => '1985-06-15',
            'phone_number' => '+855 97 123 456',
        ]);

        // Create additional random users
        User::factory()->count(20)->create();
    }
}
