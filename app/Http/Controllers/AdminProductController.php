<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AdminProductController extends Controller
{
    /**
     * Update product inventory (admin only).
     */
    public function updateInventory(Request $request, Product $product)
    {
        $data = $request->validate([
            'stock' => 'required|integer|min:0'
        ]);

        $oldStock = $product->stock;
        $product->update(['stock' => $data['stock']]);

        // Enhanced audit logging for Friday demo
        $auditData = [
            'action' => 'inventory_update',
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_sku' => $product->sku,
            'old_stock' => $oldStock,
            'new_stock' => $data['stock'],
            'stock_difference' => $data['stock'] - $oldStock,
            'admin_user_id' => auth()->id(),
            'admin_user_name' => auth()->user()->name,
            'admin_user_email' => auth()->user()->email,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()->toISOString(),
        ];

        Log::info('Admin inventory update', $auditData);

        // Also log to a dedicated audit channel for security monitoring
        Log::channel('audit')->info('INVENTORY_UPDATE', $auditData);

        // Clear product caches
        Cache::forget('products_paginated_' . request('page', 1));
        Cache::forget('categories_with_products');

        return response()->json([
            'ok' => true,
            'stock' => $product->stock,
            'message' => 'Inventory updated successfully'
        ]);
    }

    /**
     * Get all products for admin management.
     */
    public function index()
    {
        $products = Product::with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($products);
    }

    /**
     * Get product details for admin.
     */
    public function show(Product $product)
    {
        $product->load('category', 'orderItems.order');

        return response()->json([
            'product' => $product,
            'total_sold' => $product->orderItems->sum('quantity'),
            'revenue' => $product->orderItems->sum(function ($item) {
                return $item->quantity * $item->price;
            })
        ]);
    }

    /**
     * Bulk update product stocks.
     */
    public function bulkUpdateInventory(Request $request)
    {
        $request->validate([
            'updates' => 'required|array',
            'updates.*.product_id' => 'required|exists:products,id',
            'updates.*.stock' => 'required|integer|min:0',
        ]);

        $updatedProducts = [];

        foreach ($request->updates as $update) {
            $product = Product::find($update['product_id']);
            $oldStock = $product->stock;
            $product->update(['stock' => $update['stock']]);

            $updatedProducts[] = [
                'id' => $product->id,
                'name' => $product->name,
                'old_stock' => $oldStock,
                'new_stock' => $update['stock']
            ];

            // Log each update
            Log::info('Admin bulk inventory update', [
                'product_id' => $product->id,
                'old_stock' => $oldStock,
                'new_stock' => $update['stock'],
                'admin_user_id' => auth()->id(),
            ]);
        }

        // Clear caches
        Cache::forget('products_paginated_1');
        Cache::forget('categories_with_products');

        return response()->json([
            'ok' => true,
            'updated_products' => $updatedProducts,
            'message' => 'Bulk inventory update completed'
        ]);
    }
}
