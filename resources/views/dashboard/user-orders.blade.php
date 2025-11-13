<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Orders') }}
            </h2>
            <a href="{{ route('dashboard') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($orders->count() > 0)
                        <div class="space-y-6">
                            @foreach($orders as $order)
                                <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">Order #{{ $order->id }}</h3>
                                            <p class="text-sm text-gray-500">Placed on
                                                {{ $order->created_at->format('M d, Y \a\t H:i') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-lg font-bold text-gray-900">
                                                ${{ $order->getFormattedTotal() }}</p>
                                            <a href="{{ route('dashboard.order.show', $order->id) }}"
                                                class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                                View Details →
                                            </a>
                                        </div>
                                    </div>

                                    @if($order->orderItems->count() > 0)
                                        <div class="border-t pt-4">
                                            <h4 class="text-sm font-medium text-gray-700 mb-2">Items
                                                ({{ $order->orderItems->count() }})</h4>
                                            <div class="space-y-2">
                                                @foreach($order->orderItems->take(3) as $item)
                                                    <div class="flex justify-between items-center text-sm">
                                                        <span class="text-gray-900">
                                                            {{ $item->product->name ?? 'Product not found' }}
                                                            @if($item->productSku)
                                                                <span class="text-gray-500">({{ $item->productSku->sku }})</span>
                                                            @endif
                                                        </span>
                                                        <span class="text-gray-600">Qty: {{ $item->quantity }}</span>
                                                    </div>
                                                @endforeach
                                                @if($order->orderItems->count() > 3)
                                                    <p class="text-sm text-gray-500">
                                                        ... and {{ $order->orderItems->count() - 3 }} more item(s)
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-gray-500 text-lg">No orders found</div>
                            <p class="text-gray-400 mt-2">You haven't placed any orders yet.</p>
                            <a href="{{ route('products.index') }}"
                                class="mt-4 inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Start Shopping
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
