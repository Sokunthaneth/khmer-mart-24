<?php

namespace Database\Seeders;

use App\Models\OrderDetail;
use App\Models\PaymentDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = OrderDetail::all();

        // Create a payment for each order
        foreach ($orders as $order) {
            PaymentDetail::create([
                'order_id' => $order->id,
                'amount' => $order->total,
                'provider' => fake()->randomElement(['PayPal', 'Stripe', 'Square', 'Bank Transfer', 'Cash on Delivery']),
                'status' => fake()->randomElement(['pending', 'processing', 'completed', 'failed', 'cancelled']),
            ]);
        }
    }
}
