<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use App\Models\OrderItem;
use App\Models\ProductSku;
use Illuminate\Http\Request;
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
                // 1. Create the order detail
                $order = OrderDetail::create([
                    'user_id' => auth()->id(),
                    'total' => 0,
                    'status' => 'pending'
                ]);

                $total = 0;

                // 2. Process each cart item (expected format: ['sku_id' => qty])
                foreach ($cart as $skuId => $qty) {
                    // Lock the ProductSku for update to prevent race conditions
                    $sku = ProductSku::lockForUpdate()->findOrFail($skuId);

                    // Verify stock availability
                    if ($sku->stock < $qty) {
                        throw new \Exception("Insufficient stock for SKU: {$sku->sku}");
                    }

                    // Calculate line total
                    $lineTotal = $qty * $sku->price;

                    // Create order item
                    OrderItem::create([
                        'order_detail_id' => $order->id,
                        'product_id' => $sku->product_id,
                        'product_sku_id' => $sku->id,
                        'qty' => $qty,
                        'unit_price' => $sku->price,
                    ]);

                    // Decrement SKU stock
                    $sku->decrement('stock', $qty);

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

            return redirect()->route('order.success', $order->id)
                ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            Log::error('Checkout failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Checkout failed: ' . $e->getMessage());
        }
    }

    /**
     * Show order success page
     */
    public function success($orderId)
    {
        $order = OrderDetail::with(['items.productSku.product', 'items.product'])
            ->where('id', $orderId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('orders.success', compact('order'));
    }

    /**
     * Clear product-related cache.
     */
    private function clearProductCache(): void
    {
        Cache::forget('categories_with_counts');
        // Clear other product-related caches if needed
    }
}
