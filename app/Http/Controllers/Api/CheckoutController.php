<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Process checkout from cart.
     */
    public function process(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return response()->json([
                'message' => 'Cart is empty'
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $order = DB::transaction(function () use ($cart) {
                // Create the order
                $order = Order::create([
                    'user_id' => auth()->id(),
                    'total' => 0,
                    'status' => 'pending'
                ]);

                $total = 0;

                // Process each item
                foreach ($cart as $productId => $quantity) {
                    // Lock the product for update to prevent race conditions
                    $product = Product::lockForUpdate()->findOrFail($productId);

                    // Verify stock
                    if ($product->stock < $quantity) {
                        throw new \Exception("Insufficient stock for product: {$product->name}");
                    }

                    // Decrease stock
                    $product->decrement('stock', $quantity);

                    // Create order item
                    $lineTotal = $quantity * $product->price;
                    $order->items()->create([
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'unit_price' => $product->price
                    ]);

                    $total += $lineTotal;
                }

                // Update order total and status
                $order->update([
                    'total' => $total,
                    'status' => 'placed'
                ]);

                // Clear the cart
                session()->forget('cart');

                return $order;
            });

            return response()->json([
                'message' => 'Order placed successfully',
                'order' => $order->load('items.product')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Checkout failed: ' . $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
