<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Create some specific featured products
        $featuredProducts = [
            [
                'name' => 'iPhone 15 Pro',
                'description' => 'Latest iPhone model with titanium design and advanced camera system. Features the powerful A17 Pro chip for incredible performance.',
                'summary' => 'Premium smartphone with cutting-edge technology',
                'cover' => 'https://images.unsplash.com/photo-1678652197831-2d180705cd2c?w=400&h=400&fit=crop&q=80', // iPhone 15 Pro
                'category_id' => '1',
            ],
            [
                'name' => 'MacBook Pro M3',
                'description' => 'Revolutionary MacBook Pro with M3 chip delivers exceptional performance for professionals and creatives.',
                'summary' => 'Professional laptop with M3 chip technology',
                'cover' => 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=400&h=400&fit=crop&q=80', // MacBook Pro
                'category_id' => '1',
            ],
            [
                'name' => 'Wireless Headphones Pro',
                'description' => 'Premium wireless headphones with active noise cancellation and superior sound quality.',
                'summary' => 'High-quality wireless audio experience',
                'cover' => 'https://images.unsplash.com/photo-1583394838336-acd977736f90?w=400&h=400&fit=crop&q=80', // Wireless headphones
                'category_id' => '1',
            ],
            [
                'name' => 'Vintage Leather Jacket',
                'description' => 'Classic vintage brown leather jacket with premium craftsmanship. Perfect for casual and semi-formal occasions.',
                'summary' => 'Timeless leather jacket with vintage appeal',
                'cover' => 'https://images.unsplash.com/photo-1520975954732-35dd22299614?w=400&h=400&fit=crop&q=80', // Leather jacket
                'category_id' => '2',
            ],
            [
                'name' => 'Smart Coffee Maker',
                'description' => 'WiFi-enabled coffee maker with app control, programmable brewing, and built-in grinder for the perfect cup every time.',
                'summary' => 'Smart coffee maker with app control',
                'cover' => 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=400&h=400&fit=crop&q=80', // Coffee maker
                'category_id' => '3',
            ],
            [
                'name' => 'Professional Basketball',
                'description' => 'Official size and weight basketball made with premium composite leather for indoor and outdoor play.',
                'summary' => 'Professional grade basketball',
                'cover' => 'https://images.unsplash.com/photo-1546519638-68e109498ffc?w=400&h=400&fit=crop&q=80', // Basketball
                'category_id' => '4',
            ],
            [
                'name' => 'Programming Fundamentals',
                'description' => 'Comprehensive guide to programming fundamentals covering algorithms, data structures, and best practices.',
                'summary' => 'Essential programming concepts book',
                'cover' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=400&h=400&fit=crop&q=80', // Programming book
                'category_id' => '5',
            ],
            [
                'name' => 'Yoga Mat Premium',
                'description' => 'Non-slip premium yoga mat with excellent cushioning and durability. Perfect for all types of yoga practice.',
                'summary' => 'High-quality non-slip yoga mat',
                'cover' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=400&h=400&fit=crop&q=80', // Yoga mat
                'category_id' => '4',
            ],
            [
                'name' => 'Ergonomic Office Chair',
                'description' => 'Comfortable ergonomic office chair with lumbar support, adjustable height, and breathable mesh back.',
                'summary' => 'Ergonomic chair for office comfort',
                'cover' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=400&h=400&fit=crop&q=80', // Office chair
                'category_id' => '3',
            ],
            [
                'name' => 'Cashmere Scarf',
                'description' => 'Luxurious 100% cashmere scarf in elegant colors. Soft, warm, and perfect for any season.',
                'summary' => 'Premium cashmere scarf',
                'cover' => 'https://images.unsplash.com/photo-1601924994987-69e26d50dc26?w=400&h=400&fit=crop&q=80', // Cashmere scarf
                'category_id' => '2',
            ],
            [
                'name' => 'Mechanical Keyboard RGB',
                'description' => 'High-performance mechanical keyboard with RGB backlighting, custom switches, and programmable keys for gaming and productivity.',
                'summary' => 'Premium mechanical keyboard with RGB lighting',
                'cover' => 'https://images.unsplash.com/photo-1541140532154-b024d705b90a?w=400&h=400&fit=crop&q=80', // Mechanical keyboard
                'category_id' => '1',
            ],
            [
                'name' => 'Running Shoes Pro',
                'description' => 'High-performance running shoes with advanced cushioning, breathable mesh, and durable sole for long-distance running.',
                'summary' => 'Professional running shoes for athletes',
                'cover' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&h=400&fit=crop&q=80', // Running shoes
                'category_id' => '2',
            ],
            [
                'name' => 'Bamboo Desk Organizer',
                'description' => 'Eco-friendly bamboo desk organizer with multiple compartments for pens, papers, and office supplies.',
                'summary' => 'Sustainable bamboo office organizer',
                'cover' => 'https://images.unsplash.com/photo-1554415707-6e8cfc93fe23?w=400&h=400&fit=crop&q=80', // Desk organizer
                'category_id' => '3',
            ],
            [
                'name' => 'LED Floor Lamp',
                'description' => 'Modern LED floor lamp with adjustable brightness, multiple color temperatures, and minimalist design.',
                'summary' => 'Contemporary LED lamp with smart features',
                'cover' => 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=400&h=400&fit=crop&q=80', // Floor lamp
                'category_id' => '3',
            ],
            [
                'name' => 'Camping Tent 4-Person',
                'description' => 'Waterproof 4-person camping tent with easy setup, UV protection, and excellent ventilation for outdoor adventures.',
                'summary' => 'Durable 4-person camping tent',
                'cover' => 'https://images.unsplash.com/photo-1504851149312-7a075b496cc7?w=400&h=400&fit=crop&q=80', // Camping tent
                'category_id' => '4',
            ],
            [
                'name' => 'Dumbbell Set Adjustable',
                'description' => 'Adjustable dumbbell set with quick-change weight system. Perfect for home gym and strength training.',
                'summary' => 'Versatile adjustable dumbbells for home workouts',
                'cover' => 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?w=400&h=400&fit=crop&q=80', // Dumbbells
                'category_id' => '4',
            ],
            [
                'name' => 'Digital Photography Guide',
                'description' => 'Complete guide to digital photography covering composition, lighting, editing, and professional techniques.',
                'summary' => 'Comprehensive photography learning resource',
                'cover' => 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=400&h=400&fit=crop&q=80', // Photography book
                'category_id' => '5',
            ],
        ];

        foreach ($featuredProducts as $product) {
            Product::create($product);
        }

        // // Create 15 additional products using the factory (total will be 25 products)
        // Product::factory()->count(15)->create();
    }
}
