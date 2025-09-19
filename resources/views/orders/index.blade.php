@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">My Orders</h1>
            </div>

            @if($orders->count() > 0)
                <div class="space-y-4">
                    @foreach($orders as $order)
                        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">
                                        Order #{{ $order->order_number }}
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        Placed on {{ $order->created_at->format('M d, Y \a\t g:i A') }}
                                    </p>
                                </div>
                                <div class="text-right">
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
                            </div>

                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm text-gray-600">Total Amount:</span>
                                    <span
                                        class="text-lg font-semibold text-gray-800">${{ number_format($order->total_amount, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center mb-4">
                                    <span class="text-sm text-gray-600">Items:</span>
                                    <span class="text-sm text-gray-800">{{ $order->orderItems->count() }} item(s)</span>
                                </div>

                                <div class="flex justify-between items-center">
                                    <a href="{{ route('orders.show', $order) }}"
                                        class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        View Details
                                    </a>

                                    @if($order->status === 'pending')
                                        <button onclick="cancelOrder({{ $order->id }})"
                                            class="text-red-600 hover:text-red-800 text-sm font-medium">
                                            Cancel Order
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="max-w-md mx-auto">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No orders yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Start shopping to see your orders here.</p>
                        <div class="mt-6">
                            <a href="{{ route('products.index') }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                Start Shopping
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function cancelOrder(orderId) {
            if (confirm('Are you sure you want to cancel this order?')) {
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