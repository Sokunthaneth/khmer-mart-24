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
            $product = Product::find($id);
            if ($product) {
                $subtotal = $product->price * $quantity;
                $items[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal
                ];
                $total += $subtotal;
            }
        }

        return response()->json([
            'items' => $items,
            'total' => $total
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function add(Request $request, string $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);
        $quantity = $request->input('quantity', 1);

        // Check stock availability
        if ($quantity <= 0) {
            return response()->json([
                'message' => 'Invalid quantity'
            ], Response::HTTP_BAD_REQUEST);
        }

        // For demo purposes, let's assume products have a stock field
        // You might need to add this field to your products table
        if (isset($product->stock) && $quantity > $product->stock) {
            return response()->json([
                'message' => 'Not enough stock available'
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
            'message' => 'Product added to cart',
            'cart' => $cart
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function remove(string $id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
            return response()->json([
                'message' => 'Product removed from cart',
                'cart' => $cart
            ]);
        }

        return response()->json([
            'message' => 'Product not found in cart'
        ], Response::HTTP_NOT_FOUND);
    }
}
