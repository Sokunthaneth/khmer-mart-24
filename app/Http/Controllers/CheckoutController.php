<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    /**
     * Process the checkout from cart.
     */
    public function process(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Your cart is empty');
        }

        try {
            $order = DB::transaction(function () use ($cart) {
                // 1. Create the order
                $order = Order::create([
                    'user_id' => auth()->id(),
                    'total' => 0,
                    'status' => 'pending'
                ]);

                $total = 0;

                // 2. Process each item
                foreach ($cart as $productId => $quantity) {
                    // Lock the product for update to prevent race conditions
                    $product = Product::lockForUpdate()->findOrFail($productId);

                    // Verify stock
                    if ($product->stock < $quantity) {
                        throw new \Exception("Insufficient stock for product: {$product->name}");
                    }

                    // Create order item
                    $lineTotal = $quantity * $product->price;
                    $order->items()->create([
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'unit_price' => $product->price
                    ]);

                    // Update stock
                    $product->decrement('stock', $quantity);

                    $total += $lineTotal;
                }

                // 3. Update order total and status
                $order->update([
                    'total' => $total,
                    'status' => 'placed'
                ]);

                // 4. Clear the cart
                session()->forget('cart');

                // 5. Clear product cache for updated stock
                $this->clearProductCache();

                return $order;
            });

            return redirect()->route('orders.show', $order)
                ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            Log::error('Checkout failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Checkout failed: ' . $e->getMessage());
        }
    }

    /**
     * Clear product-related cache.
     */
    private function clearProductCache(): void
    {
        Cache::tags(['products', 'categories'])->flush();
    }
}
