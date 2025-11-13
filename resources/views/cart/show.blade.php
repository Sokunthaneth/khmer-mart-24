<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Shopping Cart') }} ({{ $cartCount }} {{ $cartCount === 1 ? 'item' : 'items' }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($cartCount > 0)
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <!-- Cart Items -->
                            <div class="lg:col-span-2">
                                <h3 class="text-lg font-semibold mb-4">Items in Your Cart</h3>
                                <div class="space-y-4">
                                    @foreach($cartItems as $item)
                                        <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                                            <!-- Product Image -->
                                            <div class="flex-shrink-0">
                                                @if($item['product']->cover)
                                                    <img src="{{ $item['product']->cover }}" alt="{{ $item['product']->name }}"
                                                         class="w-16 h-16 object-cover rounded">
                                                @else
                                                    <div class="w-16 h-16 bg-gray-300 rounded flex items-center justify-center">
                                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Product Details -->
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-sm font-medium text-gray-900 truncate">{{ $item['product']->name }}</h4>
                                                @if($item['product']->category)
                                                    <p class="text-sm text-gray-500">{{ $item['product']->category->name }}</p>
                                                @endif

                                                <!-- SKU Options -->
                                                <div class="text-xs text-gray-600 mt-1">
                                                    @if($item['sku']->sizeAttribute || $item['sku']->colorAttribute)
                                                        <span class="bg-gray-100 px-2 py-1 rounded">
                                                            @if($item['sku']->sizeAttribute)
                                                                {{ $item['sku']->sizeAttribute->name }}: {{ $item['sku']->sizeAttribute->value }}
                                                            @endif
                                                            @if($item['sku']->sizeAttribute && $item['sku']->colorAttribute)
                                                                |
                                                            @endif
                                                            @if($item['sku']->colorAttribute)
                                                                {{ $item['sku']->colorAttribute->name }}: {{ $item['sku']->colorAttribute->value }}
                                                            @endif
                                                        </span>
                                                    @endif
                                                    <div class="mt-1">SKU: {{ $item['sku']->sku }}</div>
                                                </div>

                                                <p class="text-sm font-semibold text-gray-900 mt-1">${{ number_format($item['sku']->price, 2) }}</p>
                                            </div>

                                            <!-- Quantity Controls -->
                                            <div class="flex items-center space-x-2">
                                                <form action="{{ route('cart.update-quantity') }}" method="POST" class="flex items-center space-x-2">
                                                    @csrf
                                                    <input type="hidden" name="sku_id" value="{{ $item['sku']->id }}">
                                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}"
                                                           min="1" max="{{ $item['sku']->quantity }}"
                                                           class="w-16 text-center rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                    <button type="submit"
                                                            class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors">
                                                        Update
                                                    </button>
                                                </form>
                                            </div>

                                            <!-- Subtotal -->
                                            <div class="text-right">
                                                <p class="text-sm font-semibold text-gray-900">
                                                    ${{ number_format($item['subtotal'], 2) }}
                                                </p>
                                            </div>

                                            <!-- Remove Button -->
                                            <div>
                                                <form action="{{ route('cart.remove') }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="sku_id" value="{{ $item['sku']->id }}">
                                                    <button type="submit"
                                                            class="text-red-600 hover:text-red-800 transition-colors"
                                                            onclick="return confirm('Are you sure you want to remove this item?')">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Order Summary -->
                            <div class="lg:col-span-1">
                                <div class="bg-gray-50 p-6 rounded-lg sticky top-4">
                                    <h3 class="text-lg font-semibold mb-4">Order Summary</h3>

                                    <div class="space-y-2 mb-4">
                                        <div class="flex justify-between">
                                            <span>Subtotal ({{ $cartCount }} items)</span>
                                            <span>${{ number_format($total, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Shipping</span>
                                            <span class="text-green-600">Free</span>
                                        </div>
                                        <div class="border-t pt-2">
                                            <div class="flex justify-between text-lg font-semibold">
                                                <span>Total</span>
                                                <span>${{ number_format($total, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-3">
                                        <a href="{{ route('cart.checkout') }}"
                                           class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors text-center block font-medium">
                                            Proceed to Checkout
                                        </a>

                                        <a href="{{ route('products.index') }}"
                                           class="w-full bg-gray-200 text-gray-800 py-3 px-4 rounded-lg hover:bg-gray-300 transition-colors text-center block">
                                            Continue Shopping
                                        </a>

                                        <form action="{{ route('cart.clear') }}" method="POST" class="w-full">
                                            @csrf
                                            <button type="submit"
                                                    class="w-full bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition-colors"
                                                    onclick="return confirm('Are you sure you want to clear your cart?')">
                                                Clear Cart
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 7a2 2 0 01-2 2H8a2 2 0 01-2-2L5 9zM9 13h6"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Your cart is empty</h3>
                            <p class="mt-1 text-sm text-gray-500">Start adding some products to your cart.</p>
                            <div class="mt-6">
                                <a href="{{ route('products.index') }}"
                                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Browse Products
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
