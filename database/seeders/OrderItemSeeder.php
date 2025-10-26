<?php

namespace Database\Seeders;

use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductSku;
use App\Models\OrderItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = OrderDetail::all();
        $products = Product::all();

        // Add 1-5 items to each order
        foreach ($orders as $order) {
            $itemCount = rand(1, 5);
            $randomProducts = $products->random($itemCount);

            foreach ($randomProducts as $product) {
                $productSkus = ProductSku::where('product_id', $product->id)->get();
                if ($productSkus->count() > 0) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'products_sku_id' => $productSkus->random()->id,
                        'quantity' => rand(1, 5),
                    ]);
                }
            }
        }
    }
}
