<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $product->name }}
            </h2>
            <a href="{{ route('products.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                ← Back to Products
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Product Image -->
                        <!-- Product Image -->
                        <div class="relative w-full h-96 overflow-hidden rounded-lg">
                            @if($product->cover)
                                <img src="{{ $product->cover }}" alt="{{ $product->name }}"
                                    class="absolute inset-0 w-full h-full object-cover object-center shadow-md"
                                    loading="lazy" onload="this.style.opacity='1'"
                                    style="opacity:0; transition: opacity 0.3s ease-in-out;">
                            @else
                                <div
                                    class="absolute inset-0 bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg flex items-center justify-center shadow-md">
                                    <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Product Details -->
                        <div class="space-y-6">
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900 mb-4">
                                    {{ $product->name }}
                                </h1>

                                @if($product->category)
                                    <span
                                        class="inline-block bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full mb-4">
                                        {{ $product->category->name }}
                                    </span>
                                @endif

                                <!-- Product Price -->
                                <div class="mb-6">
                                    @if($product->productSkus->isNotEmpty())
                                        @php
                                            $prices = $product->productSkus->pluck('price');
                                            $minPrice = $prices->min();
                                            $maxPrice = $prices->max();
                                        @endphp
                                        @if($minPrice == $maxPrice)
                                            <div class="text-3xl font-bold text-blue-600" id="selected-price">${{ number_format($minPrice, 2) }}</div>
                                        @else
                                            <div class="text-3xl font-bold text-blue-600" id="selected-price">${{ number_format($minPrice, 2) }} - ${{ number_format($maxPrice, 2) }}</div>
                                            <p class="text-sm text-gray-600 mt-1">Price varies by options</p>
                                        @endif
                                    @else
                                        <div class="text-lg text-gray-500">Price not available</div>
                                    @endif
                                </div>

                                <!-- SKU Selection -->
                                @if($product->productSkus->isNotEmpty())
                                    <div class="mb-6">
                                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Select Options:</h4>
                                        <form id="sku-selection-form">
                                            <div class="space-y-4">
                                                @foreach($product->productSkus as $index => $sku)
                                                    <label class="flex items-center justify-between p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors duration-200 sku-option" data-price="{{ $sku->price }}" data-sku-id="{{ $sku->id }}">
                                                        <div class="flex items-center">
                                                            <input type="radio" name="selected_sku" value="{{ $sku->id }}" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" @if($index === 0) checked @endif>
                                                            <div class="ml-3">
                                                                <div class="text-sm font-medium text-gray-900">
                                                                    @if($sku->sizeAttribute || $sku->colorAttribute)
                                                                        @if($sku->sizeAttribute)
                                                                            {{ $sku->sizeAttribute->name }}: {{ $sku->sizeAttribute->value }}
                                                                        @endif
                                                                        @if($sku->sizeAttribute && $sku->colorAttribute) | @endif
                                                                        @if($sku->colorAttribute)
                                                                            {{ $sku->colorAttribute->name }}: {{ $sku->colorAttribute->value }}
                                                                        @endif
                                                                    @else
                                                                        Default Option
                                                                    @endif
                                                                </div>
                                                                <div class="text-xs text-gray-500">SKU: {{ $sku->sku }}</div>
                                                                @if($sku->quantity > 0)
                                                                    <div class="text-xs text-green-600">{{ $sku->quantity }} in stock</div>
                                                                @else
                                                                    <div class="text-xs text-red-600">Out of stock</div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="text-lg font-bold text-blue-600">
                                                            ${{ number_format($sku->price, 2) }}
                                                        </div>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </form>
                                    </div>
                                @endif
                            </div>

                            @if($product->summary)
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Summary</h3>
                                    <p class="text-gray-700">{{ $product->summary }}</p>
                                </div>
                            @endif

                            @if($product->description)
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Description</h3>
                                    <div class="text-gray-700 prose max-w-none">
                                        {!! nl2br(e($product->description)) !!}
                                    </div>
                                </div>
                            @endif

            <!-- Product Actions -->
            <div class="flex space-x-4 pt-6 border-t border-gray-200">
                @auth
                    @if($product->productSkus->isNotEmpty())
                        <form action="{{ route('cart.add.sku') }}" method="POST" class="flex-1" id="add-to-cart-form">
                            @csrf
                            <input type="hidden" name="product_sku_id" id="selected-sku-id" value="{{ $product->productSkus->first()->id }}">
                            <div class="flex space-x-3 mb-4">
                                <div class="flex-1">
                                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                                    <select name="quantity" id="quantity" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        @for($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}" @if($i === 1) selected @endif>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <button type="submit" id="add-to-cart-btn"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center disabled:bg-gray-400 disabled:cursor-not-allowed">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 7a2 2 0 01-2 2H8a2 2 0 01-2-2L5 9zM9 13h6">
                                    </path>
                                </svg>
                                Add to Cart
                            </button>
                        </form>
                    @else
                        <div class="flex-1">
                            <div class="w-full bg-gray-400 text-white font-bold py-3 px-6 rounded-lg text-center">
                                Product not available
                            </div>
                        </div>
                    @endif
                @else
                    <div class="flex-1">
                        <a href="{{ route('login') }}"
                            class="w-full bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center">
                            Login to Add to Cart
                        </a>
                    </div>
                @endauth
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const skuOptions = document.querySelectorAll('input[name="selected_sku"]');
                    const selectedPriceElement = document.getElementById('selected-price');
                    const selectedSkuIdInput = document.getElementById('selected-sku-id');
                    const addToCartBtn = document.getElementById('add-to-cart-btn');

                    // Update price and form when SKU selection changes
                    skuOptions.forEach(function(option) {
                        option.addEventListener('change', function() {
                            if (this.checked) {
                                const skuOption = this.closest('.sku-option');
                                const price = skuOption.dataset.price;
                                const skuId = skuOption.dataset.skuId;

                                // Update displayed price
                                selectedPriceElement.textContent = '$' + parseFloat(price).toFixed(2);

                                // Update hidden input for form submission
                                selectedSkuIdInput.value = skuId;

                                // Check if SKU is in stock
                                const outOfStockElement = skuOption.querySelector('.text-red-600');
                                if (outOfStockElement) {
                                    addToCartBtn.disabled = true;
                                    addToCartBtn.textContent = 'Out of Stock';
                                } else {
                                    addToCartBtn.disabled = false;
                                    addToCartBtn.innerHTML = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 7a2 2 0 01-2 2H8a2 2 0 01-2-2L5 9zM9 13h6"></path></svg>Add to Cart';
                                }
                            }
                        });
                    });

                    // Initialize with first SKU
                    if (skuOptions.length > 0) {
                        skuOptions[0].dispatchEvent(new Event('change'));
                    }
                });
            </script>                            <!-- Product Meta Information -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Product Information</h3>
                                <dl class="space-y-2">
                                    <div class="flex justify-between">
                                        <dt class="text-gray-600">Product ID:</dt>
                                        <dd class="text-gray-900 font-medium">#{{ $product->id }}</dd>
                                    </div>
                                    @if($product->category)
                                        <div class="flex justify-between">
                                            <dt class="text-gray-600">Category:</dt>
                                            <dd class="text-gray-900 font-medium">{{ $product->category->name }}</dd>
                                        </div>
                                    @endif
                                    <div class="flex justify-between">
                                        <dt class="text-gray-600">Added:</dt>
                                        <dd class="text-gray-900 font-medium">
                                            {{ $product->created_at->format('M d, Y') }}
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
