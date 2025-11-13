<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductSku;
use App\Models\CartItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CartItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $carts = Cart::all();
        $products = Product::all();

        // Add 1-5 items to each cart
        foreach ($carts as $cart) {
            $itemCount = rand(1, 5);
            $randomProducts = $products->random($itemCount);

            foreach ($randomProducts as $product) {
                $productSkus = ProductSku::where('product_id', $product->id)->get();
                if ($productSkus->count() > 0) {
                    CartItem::create([
                        'cart_id' => $cart->id,
                        'product_id' => $product->id,
                        'products_sku_id' => $productSkus->random()->id,
                        'quantity' => rand(1, 3),
                    ]);
                }
            }
        }
    }
}
