<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run seeders in dependency order
        $this->call([
                // Core data first
            UserSeeder::class,
            CategorySeeder::class,
            SubCategorySeeder::class,
            ProductSeeder::class,

                // Product attributes and SKUs
            ProductAttributeSeeder::class,
            ProductSkuSeeder::class,

                // User-related data
            AddressSeeder::class,
            WishlistSeeder::class,
            CartSeeder::class,
            CartItemSeeder::class,

                // Order system
            OrderDetailSeeder::class,
            OrderItemSeeder::class,
            PaymentDetailSeeder::class,
        ]);
    }
}
