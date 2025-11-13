<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with orders list
     */
    public function index()
    {
        // Get only the authenticated user's orders with their related data for the dashboard
        $orders = OrderDetail::with(['user', 'orderItems.product', 'orderItems.productSku', 'paymentDetails'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard', compact('orders'));
    }

    /**
     * Display the specified order details
     */
    public function showOrder($id)
    {
        $order = OrderDetail::with(['user', 'orderItems.product', 'orderItems.productSku', 'paymentDetails'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return view('dashboard.order-detail', compact('order'));
    }

    /**
     * Get orders by user (for user-specific dashboard)
     */
    public function userOrders(Request $request)
    {
        $userId = $request->user()->id;
        $orders = OrderDetail::with(['orderItems.product', 'orderItems.productSku', 'paymentDetails'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.user-orders', compact('orders'));
    }
}
