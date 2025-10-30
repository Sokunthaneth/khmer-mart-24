<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        $items = [];
        $total = 0;

        foreach ($cart as $id => $quantity) {
            $product = Product::with('productSkus')->find($id);
            if ($product) {
                // Get the first available SKU for price
                $productSku = $product->productSkus()->first();
                if ($productSku) {
                    $price = (float) $productSku->price;
                    $subtotal = $price * $quantity;
                    $items[] = [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $price,
                        'quantity' => $quantity,
                        'subtotal' => $subtotal,
                        'available_stock' => $productSku->stock,
                    ];
                    $total += $subtotal;
                }
            }
        }

        return response()->json([
            'items' => $items,
            'total' => $total,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function add(Request $request, string $id)
    {
        $product = Product::with('productSkus')->findOrFail($id);
        $cart = session()->get('cart', []);
        $quantity = $request->input('quantity', 1);

        // Check stock availability
        if ($quantity <= 0) {
            return response()->json([
                'message' => 'Invalid quantity',
            ], Response::HTTP_BAD_REQUEST);
        }

        // Get the first available product SKU to check stock
        $productSku = $product->productSkus()->first();
        if (! $productSku) {
            return response()->json([
                'message' => 'Product variant not available',
            ], Response::HTTP_BAD_REQUEST);
        }

        // Check if enough stock is available
        $currentCartQuantity = isset($cart[$id]) ? $cart[$id] : 0;
        $totalRequestedQuantity = $currentCartQuantity + $quantity;

        if ($totalRequestedQuantity > $productSku->stock) {
            return response()->json([
                'message' => 'Not enough stock available. Available: '.$productSku->stock.', Requested: '.$totalRequestedQuantity,
            ], Response::HTTP_BAD_REQUEST);
        }

        // If product exists in cart, update quantity
        if (isset($cart[$id])) {
            $cart[$id] += $quantity;
        } else {
            $cart[$id] = $quantity;
        }

        session()->put('cart', $cart);

        return response()->json([
            'message' => 'Product added to cart successfully',
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'quantity_in_cart' => $cart[$id],
                'price' => (float) $productSku->price,
            ],
            'cart_item_count' => array_sum($cart),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $cart = session()->get('cart', []);
        $quantity = $request->input('quantity', 1);

        if (! isset($cart[$id])) {
            return response()->json([
                'message' => 'Product not found in cart',
            ], Response::HTTP_NOT_FOUND);
        }

        // Validate quantity
        if ($quantity <= 0) {
            return response()->json([
                'message' => 'Invalid quantity. Quantity must be greater than 0.',
            ], Response::HTTP_BAD_REQUEST);
        }

        // Get product and check stock availability
        $product = Product::with('productSkus')->find($id);
        if (! $product) {
            return response()->json([
                'message' => 'Product not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $productSku = $product->productSkus()->first();
        if (! $productSku) {
            return response()->json([
                'message' => 'Product variant not available',
            ], Response::HTTP_BAD_REQUEST);
        }

        // Check if enough stock is available
        if ($quantity > $productSku->stock) {
            return response()->json([
                'message' => 'Not enough stock available. Available: '.$productSku->stock.', Requested: '.$quantity,
            ], Response::HTTP_BAD_REQUEST);
        }

        // Update the quantity
        $cart[$id] = $quantity;
        session()->put('cart', $cart);

        return response()->json([
            'message' => 'Cart updated successfully',
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'quantity_in_cart' => $cart[$id],
                'price' => (float) $productSku->price,
            ],
            'cart_item_count' => array_sum($cart),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function remove(Request $request, string $id)
    {
        $cart = session()->get('cart', []);
        $quantity = $request->input('quantity', null);

        if (! isset($cart[$id])) {
            return response()->json([
                'message' => 'Product not found in cart',
            ], Response::HTTP_NOT_FOUND);
        }

        // If no quantity specified or quantity is invalid, remove entire item
        if ($quantity === null || $quantity <= 0) {
            unset($cart[$id]);
            $message = 'Product completely removed from cart';
        } else {
            // Reduce the quantity by the specified amount
            $cart[$id] -= $quantity;

            // If quantity becomes 0 or negative, remove the item completely
            if ($cart[$id] <= 0) {
                unset($cart[$id]);
                $message = 'Product completely removed from cart';
            } else {
                $message = "Removed {$quantity} item(s) from cart. {$cart[$id]} remaining";
            }
        }

        session()->put('cart', $cart);

        // Get updated cart info for response
        $totalItems = array_sum($cart);

        return response()->json([
            'message' => $message,
            'cart_item_count' => $totalItems,
            'remaining_quantity' => isset($cart[$id]) ? $cart[$id] : 0,
        ]);
    }

    /**
     * Get cart count (total number of items)
     */
    public function count()
    {
        $cart = session()->get('cart', []);
        $totalItems = array_sum($cart);

        return response()->json([
            'count' => $totalItems,
        ]);
    }

    /**
     * Clear the entire cart
     */
    public function clear()
    {
        session()->forget('cart');

        return response()->json([
            'message' => 'Cart cleared successfully',
        ]);
    }
}
