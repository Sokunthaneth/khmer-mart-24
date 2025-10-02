<?php

namespace Database\Seeders;

use App\Models\ProductAttribute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create color attributes
        $colors = ['Red', 'Blue', 'Green', 'Black', 'White', 'Yellow', 'Purple', 'Orange', 'Pink', 'Brown'];
        foreach ($colors as $color) {
            ProductAttribute::create([
                'type' => 'color',
                'value' => $color
            ]);
        }

        // Create size attributes
        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL', '28', '30', '32', '34', '36', '38', '40', '42'];
        foreach ($sizes as $size) {
            ProductAttribute::create([
                'type' => 'size',
                'value' => $size
            ]);
        }
    }
}
