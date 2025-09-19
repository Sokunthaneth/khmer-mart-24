@extends('layouts.app')

@section('title', 'Order #' . $order->order_number)

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Order Header -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Order #{{ $order->order_number }}</h1>
                        <p class="text-gray-600">Placed on {{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                        @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                        @elseif($order->status === 'delivered') bg-green-100 text-green-800
                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                        @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-2">Shipping Address</h3>
                        <p class="text-gray-800">{{ $order->shipping_address }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-2">Billing Address</h3>
                        <p class="text-gray-800">{{ $order->billing_address }}</p>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-500">Payment Method:</span>
                        <span class="text-gray-800">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Order Items</h2>

                <div class="space-y-4">
                    @foreach($order->orderItems as $item)
                        <div class="flex items-center space-x-4 py-4 border-b border-gray-200 last:border-b-0">
                            <div class="flex-shrink-0 w-16 h-16 bg-gray-200 rounded-md flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>

                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-gray-800">{{ $item->product->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $item->product->category->name }}</p>
                                <p class="text-xs text-gray-500">SKU: {{ $item->product->sku }}</p>
                            </div>

                            <div class="text-center">
                                <p class="text-sm text-gray-600">Quantity</p>
                                <p class="font-medium">{{ $item->quantity }}</p>
                            </div>

                            <div class="text-center">
                                <p class="text-sm text-gray-600">Price</p>
                                <p class="font-medium">${{ number_format($item->price, 2) }}</p>
                            </div>

                            <div class="text-center">
                                <p class="text-sm text-gray-600">Total</p>
                                <p class="font-medium">${{ number_format($item->price * $item->quantity, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Summary -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Order Summary</h2>

                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal ({{ $order->orderItems->sum('quantity') }} items)</span>
                        <span
                            class="text-gray-800">${{ number_format($order->orderItems->sum(function ($item) {
        return $item->price * $item->quantity; }), 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Shipping</span>
                        <span class="text-gray-800">Free</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Tax</span>
                        <span class="text-gray-800">$0.00</span>
                    </div>
                    <div class="border-t border-gray-200 pt-2">
                        <div class="flex justify-between text-lg font-semibold">
                            <span class="text-gray-800">Total</span>
                            <span class="text-gray-800">${{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex justify-between">
                <a href="{{ route('orders.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    ← Back to Orders
                </a>

                @if($order->status === 'pending')
                    <button onclick="cancelOrder({{ $order->id }})"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                        Cancel Order
                    </button>
                @endif
            </div>
        </div>
    </div>

    <script>
        function cancelOrder(orderId) {
            if (confirm('Are you sure you want to cancel this order? This action cannot be undone.')) {
                fetch(`/orders/${orderId}/cancel`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert(data.message || 'Failed to cancel order');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while cancelling the order');
                    });
            }
        }
    </script>
@endsection
