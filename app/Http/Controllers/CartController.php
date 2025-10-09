<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductSku;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function show()
    {
        $cart = session()->get('cart', []);
        $cartItems = collect();
        $total = 0;
        $cartCount = 0;

        if (! empty($cart)) {
            // Get all SKU IDs from cart
            $skuIds = array_keys($cart);
            $skus = ProductSku::with(['product.category', 'sizeAttribute', 'colorAttribute'])
                ->whereIn('id', $skuIds)
                ->get();

            foreach ($skus as $sku) {
                $quantity = $cart[$sku->id];
                $cartItems->push([
                    'sku' => $sku,
                    'product' => $sku->product,
                    'quantity' => $quantity,
                    'subtotal' => $sku->price * $quantity,
                ]);
                $total += $sku->price * $quantity;
                $cartCount += $quantity;
            }
        }

        return view('cart.show', [
            'cart' => $cart,
            'cartItems' => $cartItems,
            'total' => $total,
            'cartCount' => $cartCount,
        ]);
    }

    public function addSku(Request $request)
    {
        $request->validate([
            'product_sku_id' => 'required|exists:products_skus,id',
            'quantity' => 'integer|min:1|max:99',
        ]);

        $productSku = ProductSku::findOrFail($request->product_sku_id);
        $quantity = $request->input('quantity', 1);

        // Check if SKU has enough stock
        if ($productSku->quantity < $quantity) {
            return back()->with('error', 'Not enough stock available');
        }

        // Check if SKU is in stock
        if ($productSku->quantity <= 0) {
            return back()->with('error', 'This item is out of stock');
        }

        $cart = session()->get('cart', []);
        $currentQuantity = $cart[$productSku->id] ?? 0;
        $newQuantity = $currentQuantity + $quantity;

        // Check total quantity doesn't exceed stock
        if ($newQuantity > $productSku->quantity) {
            return back()->with('error', 'Cannot add more items than available in stock');
        }

        // Ensure we don't exceed maximum quantity
        if ($newQuantity > 99) {
            $newQuantity = 99;
        }

        $cart[$productSku->id] = $newQuantity;
        session(['cart' => $cart]);

        $message = $productSku->product->name;
        if ($productSku->sizeAttribute || $productSku->colorAttribute) {
            $message .= ' (';
            if ($productSku->sizeAttribute) {
                $message .= $productSku->sizeAttribute->value;
            }
            if ($productSku->sizeAttribute && $productSku->colorAttribute) {
                $message .= ', ';
            }
            if ($productSku->colorAttribute) {
                $message .= $productSku->colorAttribute->value;
            }
            $message .= ')';
        }
        $message .= ' added to cart';

        return back()->with('success', $message);
    }

    public function add(Request $request, Product $product)
    {
        $quantity = $request->input('quantity', 1);

        // Validate quantity
        if ($quantity < 1 || $quantity > 99) {
            return back()->with('error', 'Invalid quantity specified');
        }

        $cart = session()->get('cart', []);
        $cart[$product->id] = ($cart[$product->id] ?? 0) + $quantity;

        // Ensure we don't exceed maximum quantity
        if ($cart[$product->id] > 99) {
            $cart[$product->id] = 99;
        }

        session(['cart' => $cart]);

        return back()->with('success', $product->name.' added to cart');
    }

    public function remove(Request $request)
    {
        $skuId = $request->input('sku_id');
        $cart = session()->get('cart', []);

        if (isset($cart[$skuId])) {
            $sku = ProductSku::find($skuId);
            $productName = $sku ? $sku->product->name : 'Product';

            unset($cart[$skuId]);
            session(['cart' => $cart]);

            return back()->with('success', $productName.' removed from cart');
        }

        return back()->with('error', 'Product not found in cart');
    }

    public function clear()
    {
        session()->forget('cart');

        return back()->with('success', 'Cart cleared successfully');
    }

    public function updateQuantity(Request $request)
    {
        $request->validate([
            'sku_id' => 'required|exists:products_skus,id',
            'quantity' => 'required|integer|min:0|max:99',
        ]);

        $skuId = $request->sku_id;
        $quantity = $request->quantity;
        $cart = session()->get('cart', []);

        $sku = ProductSku::findOrFail($skuId);

        if ($quantity > 0) {
            // Check stock availability
            if ($quantity > $sku->quantity) {
                return back()->with('error', 'Not enough stock available');
            }

            $cart[$skuId] = $quantity;
            session(['cart' => $cart]);

            return back()->with('success', 'Cart updated successfully');
        } else {
            unset($cart[$skuId]);
            session(['cart' => $cart]);

            return back()->with('success', 'Product removed from cart');
        }
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.show')->with('error', 'Your cart is empty');
        }

        $cartItems = collect();
        $total = 0;
        $cartCount = 0;

        // Get all SKU IDs from cart
        $skuIds = array_keys($cart);
        $skus = ProductSku::with(['product.category', 'sizeAttribute', 'colorAttribute'])
            ->whereIn('id', $skuIds)
            ->get();

        foreach ($skus as $sku) {
            $quantity = $cart[$sku->id];
            $subtotal = $sku->price * $quantity;

            $cartItems->push([
                'sku' => $sku,
                'product' => $sku->product,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            ]);

            $total += $subtotal;
            $cartCount += $quantity;
        }

        return view('cart.checkout', [
            'cartItems' => $cartItems,
            'total' => $total,
            'cartCount' => $cartCount,
        ]);
    }

    public function submitOrder(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'payment_method' => 'required|in:cash,card,mobile',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.show')->with('error', 'Your cart is empty');
        }

        // Here you would typically:
        // 1. Create an order record in the database
        // 2. Process payment
        // 3. Send confirmation email
        // For now, we'll just simulate the process

        // Calculate total using SKU-based pricing
        $skuIds = array_keys($cart);
        $skus = ProductSku::whereIn('id', $skuIds)->get();
        $total = 0;

        foreach ($skus as $sku) {
            $quantity = $cart[$sku->id];
            $total += $sku->price * $quantity;
        }

        // Clear the cart after successful order
        session()->forget('cart');

        // Generate a mock order ID
        $orderId = 'ORD-'.strtoupper(uniqid());

        return view('cart.success', [
            'orderId' => $orderId,
            'total' => $total,
            'customerName' => $request->name,
        ]);
    }

    public function getCartCount()
    {
        $cart = session()->get('cart', []);

        return array_sum($cart);
    }
}
