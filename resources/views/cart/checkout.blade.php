<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Checkout Form -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-6">Billing Information</h3>

                        <form action="{{ route('cart.submit') }}" method="POST" class="space-y-6">
                            @csrf

                            <!-- Personal Information -->
                            <div class="space-y-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                    <input type="text" name="name" id="name"
                                        value="{{ old('name', auth()->user()->name ?? '') }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email
                                        Address</label>
                                    <input type="email" name="email" id="email"
                                        value="{{ old('email', auth()->user()->email ?? '') }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone
                                        Number</label>
                                    <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Shipping Address -->
                            <div class="space-y-4">
                                <h4 class="text-md font-semibold text-gray-900">Shipping Address</h4>

                                <div>
                                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                                    <textarea name="address" id="address" rows="3" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('address') }}</textarea>
                                    @error('address')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Payment Method -->
                            <div class="space-y-4">
                                <h4 class="text-md font-semibold text-gray-900">Payment Method</h4>

                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="radio" name="payment_method" value="cash" {{ old('payment_method', 'cash') === 'cash' ? 'checked' : '' }}
                                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                        <span class="ml-2 text-sm text-gray-700">Cash on Delivery</span>
                                    </label>

                                    <label class="flex items-center">
                                        <input type="radio" name="payment_method" value="card" {{ old('payment_method') === 'card' ? 'checked' : '' }}
                                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                        <span class="ml-2 text-sm text-gray-700">Credit/Debit Card</span>
                                    </label>

                                    <label class="flex items-center">
                                        <input type="radio" name="payment_method" value="mobile" {{ old('payment_method') === 'mobile' ? 'checked' : '' }}
                                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                        <span class="ml-2 text-sm text-gray-700">Mobile Payment</span>
                                    </label>
                                </div>
                                @error('payment_method')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex space-x-4">
                                <a href="{{ route('cart.show') }}"
                                    class="flex-1 bg-gray-200 text-gray-800 py-3 px-4 rounded-lg hover:bg-gray-300 transition-colors text-center">
                                    Back to Cart
                                </a>
                                <button type="submit"
                                    class="flex-1 bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                    Place Order
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold">Order Summary</h3>
                            <span class="text-sm text-gray-600">{{ $cartCount }}
                                {{ $cartCount === 1 ? 'item' : 'items' }}</span>
                        </div>

                        <div class="space-y-4">
                            @foreach($cartItems as $item)
                                <div class="flex items-center space-x-4 p-3 border border-gray-200 rounded">
                                    @if($item['product']->cover)
                                        <img src="{{ $item['product']->cover }}" alt="{{ $item['product']->name }}"
                                            class="w-12 h-12 object-cover rounded">
                                    @else
                                        <div class="w-12 h-12 bg-gray-300 rounded flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif

                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $item['product']->name }}
                                        </p>
                                        @if($item['product']->category)
                                            <p class="text-xs text-gray-500">{{ $item['product']->category->name }}</p>
                                        @endif

                                        <!-- SKU Options -->
                                        @if($item['sku']->sizeAttribute || $item['sku']->colorAttribute)
                                            <div class="text-xs text-gray-600 mt-1">
                                                <span class="bg-gray-100 px-2 py-0.5 rounded text-xs">
                                                    @if($item['sku']->sizeAttribute)
                                                        {{ $item['sku']->sizeAttribute->name }}:
                                                        {{ $item['sku']->sizeAttribute->value }}
                                                    @endif
                                                    @if($item['sku']->sizeAttribute && $item['sku']->colorAttribute) | @endif
                                                    @if($item['sku']->colorAttribute)
                                                        {{ $item['sku']->colorAttribute->name }}:
                                                        {{ $item['sku']->colorAttribute->value }}
                                                    @endif
                                                </span>
                                            </div>
                                        @endif

                                        <div class="flex justify-between items-center mt-1">
                                            <span class="text-xs text-gray-500">SKU: {{ $item['sku']->sku }}</span>
                                            <span class="text-xs text-gray-500">Qty: {{ $item['quantity'] }}</span>
                                        </div>

                                        <p class="text-sm font-medium text-blue-600 mt-1">
                                            ${{ number_format($item['sku']->price, 2) }} each</p>
                                    </div>

                                    <div class="text-right">
                                        <div class="text-sm font-semibold text-gray-900">
                                            ${{ number_format($item['subtotal'], 2) }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6 pt-6 border-t space-y-3">
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Subtotal ({{ $cartCount }} {{ $cartCount === 1 ? 'item' : 'items' }})</span>
                                <span class="font-medium">${{ number_format($total, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Shipping</span>
                                <span class="text-green-600 font-medium">Free</span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Estimated Tax</span>
                                <span class="font-medium">$0.00</span>
                            </div>
                            <div class="border-t pt-3">
                                <div class="flex justify-between text-lg font-bold text-gray-900">
                                    <span>Order Total</span>
                                    <span class="text-blue-600">${{ number_format($total, 2) }}</span>
                                </div>
                            </div>

                            <!-- Additional Order Info -->
                            <div class="mt-4 pt-4 border-t">
                                <div class="text-xs text-gray-500 space-y-1">
                                    <p>• Free shipping on all orders</p>
                                    <p>• Secure checkout with SSL encryption</p>
                                    <p>• Order confirmation will be sent to your email</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>