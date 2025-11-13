<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Order Details - #' . $order->id) }}
            </h2>
            <a href="{{ route('dashboard') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Order Summary -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-500">Order ID</h4>
                            <p class="text-lg font-semibold text-gray-900">#{{ $order->id }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-500">Customer</h4>
                            <p class="text-lg font-semibold text-gray-900">{{ $order->user->name ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-600">{{ $order->user->email ?? '' }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-500">Total Amount</h4>
                            <p class="text-lg font-semibold text-gray-900">${{ $order->getFormattedTotal() }}
                            </p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-500">Order Date</h4>
                            <p class="text-lg font-semibold text-gray-900">{{ $order->created_at->format('M d, Y') }}
                            </p>
                            <p class="text-sm text-gray-600">{{ $order->created_at->format('H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h3>
                    @if($order->orderItems->count() > 0)
                        <div class="overflow-x-auto -mx-6 sm:mx-0">
                            <div class="inline-block min-w-full align-middle">
                                <table class="min-w-full table-fixed">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="w-2/5 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Product</th>
                                            <th
                                                class="w-32 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">
                                                SKU</th>
                                            <th
                                                class="w-20 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Quantity</th>
                                            <th
                                                class="w-24 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Price</th>
                                            <th
                                                class="w-28 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($order->orderItems as $item)
                                            <tr>
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center">
                                                        <div class="flex-1 min-w-0">
                                                            <div class="text-sm font-medium text-gray-900 truncate">
                                                                {{ $item->product->name ?? 'Product not found' }}
                                                            </div>
                                                            @if($item->product && $item->product->description)
                                                                <div class="text-sm text-gray-500 truncate">
                                                                    {{ Str::limit($item->product->description, 50) }}
                                                                </div>
                                                            @endif
                                                            <!-- Show SKU on mobile -->
                                                            <div class="text-xs text-gray-400 sm:hidden">
                                                                SKU: {{ $item->productSku ? $item->productSku->sku : 'N/A' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900 truncate hidden sm:table-cell">
                                                    @if($item->productSku)
                                                        {{ $item->productSku->sku }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900 text-center">
                                                    {{ $item->quantity }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900 text-right">
                                                    @if($item->productSku)
                                                        ${{ number_format(floatval($item->productSku->price), 2) }}
                                                    @else
                                                        $0.00
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900 font-medium text-right">
                                                    ${{ number_format($item->calculateTotal(), 2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        <!-- Total Row -->
                                        <tr class="bg-gray-50 font-medium">
                                            <td colspan="4"
                                                class="px-6 py-4 text-sm text-gray-900 text-right sm:table-cell">
                                                <strong>Order Total:</strong>
                                            </td>
                                            <td
                                                class="px-6 py-4 text-sm text-gray-900 font-bold text-right hidden sm:table-cell">
                                                ${{ $order->getFormattedTotal() }}
                                            </td>
                                            <!-- Mobile total row -->
                                            <td colspan="3" class="px-6 py-4 text-sm text-gray-900 text-right sm:hidden">
                                                <strong>Total: ${{ $order->getFormattedTotal() }}</strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-500">No items found for this order</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment Details -->
            @if($order->paymentDetails->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Details</h3>
                        <div class="space-y-4">
                            @foreach($order->paymentDetails as $payment)
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-500">Payment ID</h4>
                                            <p class="text-sm text-gray-900">#{{ $payment->id }}</p>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-500">Amount</h4>
                                            <p class="text-sm text-gray-900">${{ number_format($payment->amount / 100, 2) }}</p>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-500">Status</h4>
                                            <p class="text-sm text-gray-900">{{ ucfirst($payment->status ?? 'pending') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>