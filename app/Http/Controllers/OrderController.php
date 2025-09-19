<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class OrderController extends Controller
{
    /**
     * Display a listing of the user's orders.
     */
    public function index()
    {
        $orders = Auth::user()->orders()
            ->with(['orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to order');
        }

        // Load order with items and products
        $order->load(['orderItems.product.category']);

        return view('orders.show', compact('order'));
    }

    /**
     * Create a new order from cart data.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cart' => 'required|array',
            'cart.*.product_id' => 'required|exists:products,id',
            'cart.*.quantity' => 'required|integer|min:1',
            'shipping_address' => 'required|string|max:500',
            'billing_address' => 'required|string|max:500',
            'payment_method' => 'required|string|in:credit_card,paypal,bank_transfer',
        ]);

        try {
            DB::beginTransaction();

            // Calculate total amount
            $totalAmount = 0;
            $orderItems = [];

            foreach ($request->cart as $cartItem) {
                $product = Product::findOrFail($cartItem['product_id']);

                // Check stock availability
                if ($product->stock < $cartItem['quantity']) {
                    throw new \Exception("Insufficient stock for product: {$product->name}");
                }

                $itemTotal = $product->price * $cartItem['quantity'];
                $totalAmount += $itemTotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $cartItem['quantity'],
                    'price' => $product->price,
                ];

                // Reserve stock
                $product->decrementStock($cartItem['quantity']);
            }

            // Create the order
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'shipping_address' => $request->shipping_address,
                'billing_address' => $request->billing_address,
                'payment_method' => $request->payment_method,
                'order_number' => 'ORD-' . strtoupper(uniqid()),
            ]);

            // Create order items
            foreach ($orderItems as $item) {
                $order->orderItems()->create($item);
            }

            DB::commit();

            // Clear relevant caches
            Cache::forget('products_paginated_' . request('page', 1));
            Cache::forget('categories_with_products');

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'order_id' => $order->id,
                'order_number' => $order->order_number,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Update the order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string|in:pending,processing,shipped,delivered,cancelled',
        ]);

        // Only allow admin users to update order status
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized action');
        }

        $order->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully',
        ]);
    }

    /**
     * Cancel an order.
     */
    public function cancel(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to order');
        }

        // Only allow cancellation of pending orders
        if ($order->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending orders can be cancelled',
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Restore stock for all order items
            foreach ($order->orderItems as $orderItem) {
                $product = $orderItem->product;
                $product->increment('stock', $orderItem->quantity);
            }

            // Update order status
            $order->update(['status' => 'cancelled']);

            DB::commit();

            // Clear relevant caches
            Cache::forget('products_paginated_' . request('page', 1));

            return response()->json([
                'success' => true,
                'message' => 'Order cancelled successfully',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel order',
            ], 500);
        }
    }

    /**
     * Get order statistics for admin dashboard.
     */
    public function statistics()
    {
        // Only allow admin users to view statistics
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized action');
        }

        $stats = Cache::remember('order_statistics', 300, function () {
            return [
                'total_orders' => Order::count(),
                'pending_orders' => Order::where('status', 'pending')->count(),
                'completed_orders' => Order::where('status', 'delivered')->count(),
                'total_revenue' => Order::where('status', 'delivered')->sum('total_amount'),
                'average_order_value' => Order::avg('total_amount'),
                'recent_orders' => Order::with(['user', 'orderItems.product'])
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get(),
            ];
        });

        return response()->json($stats);
    }
}
