<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Products') }}
            </h2>

            <!-- Search and Filter Section -->
            <div class="flex space-x-4">
                <form method="GET" action="{{ route('products.index') }}" class="flex space-x-2">
                    <!-- Search Input -->
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search products..."
                        class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >

                    <!-- Category Filter -->
                    <select
                        name="category_id"
                        class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    <button
                        type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        Filter
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($products->count() > 0)
                        <!-- Product Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
                            @foreach($products as $product)
                                <div class="product-card group">
                                    <!-- Product Image -->
                                    <div class="relative w-full h-48 overflow-hidden rounded-t-lg">
                                        @if($product->cover)
                                            <img
                                                src="{{ $product->cover }}"
                                                alt="{{ $product->name }}"
                                                class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-300"
                                                loading="lazy"
                                                onload="this.style.opacity='1'"
                                                style="opacity:0; transition: opacity 0.3s ease-in-out;"
                                            >
                                        @else
                                            <div class="absolute inset-0 bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Product Info -->
                                    <div class="p-4">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                            <a href="{{ route('products.show', $product) }}" class="hover:text-blue-600 transition-colors duration-200">
                                                {{ $product->name }}
                                            </a>
                                        </h3>

                                        @if($product->category)
                                            <span class="inline-block bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-full mb-2">
                                                {{ $product->category->name }}
                                            </span>
                                        @endif

                                        @if($product->summary)
                                            <p class="text-gray-600 text-sm mb-3 line-clamp-3">
                                                {{ $product->summary }}
                                            </p>
                                        @elseif($product->description)
                                            <p class="text-gray-600 text-sm mb-3 line-clamp-3">
                                                {{ Str::limit($product->description, 100) }}
                                            </p>
                                        @endif

                                        <!-- Product Price -->
                                        <div class="mb-3">
                                            @if($product->productSkus->isNotEmpty())
                                                @php
                                                    $prices = $product->productSkus->pluck('price');
                                                    $minPrice = $prices->min();
                                                    $maxPrice = $prices->max();
                                                @endphp
                                                @if($minPrice == $maxPrice)
                                                    <span class="text-lg font-bold text-blue-600">${{ number_format($minPrice, 2) }}</span>
                                                @else
                                                    <span class="text-lg font-bold text-blue-600">${{ number_format($minPrice, 2) }} - ${{ number_format($maxPrice, 2) }}</span>
                                                @endif
                                            @else
                                                <span class="text-sm text-gray-500">Price not available</span>
                                            @endif
                                        </div>

                                        <!-- Product Actions -->
                                        <div class="flex justify-end mt-4">
                                            <a
                                                href="{{ route('products.show', $product) }}"
                                                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded text-sm transition-colors duration-200 inline-flex items-center"
                                            >
                                                View Details
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Results Summary and Pagination -->
                        <div class="mt-8 space-y-4">
                            <!-- Showing Results Label -->
                            <div class="text-center">
                                <p class="text-sm text-gray-700">
                                    Showing
                                    <span class="font-medium">{{ $products->firstItem() ?? 0 }}</span>
                                    to
                                    <span class="font-medium">{{ $products->lastItem() ?? 0 }}</span>
                                    of
                                    <span class="font-medium">{{ $products->total() }}</span>
                                    results
                                    @if(request('search'))
                                        for "<span class="font-medium text-blue-600">{{ request('search') }}</span>"
                                    @endif
                                    @if(request('category_id'))
                                        @php
                                            $selectedCategory = $categories->firstWhere('id', request('category_id'));
                                        @endphp
                                        @if($selectedCategory)
                                            in <span class="font-medium text-blue-600">{{ $selectedCategory->name }}</span>
                                        @endif
                                    @endif
                                </p>
                            </div>

                            <!-- Pagination -->
                            <div class="flex justify-center">
                                {{ $products->appends(request()->query())->links() }}
                            </div>
                        </div>
                    @else
                        <!-- No Products Found -->
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m14 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m14 0H6m14 0l-3-3m-3 3l3-3"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No products found</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                @if(request('search') || request('category_id'))
                                    Try adjusting your search or filter criteria.
                                @else
                                    Get started by adding some products.
                                @endif
                            </p>
                            @if(request('search') || request('category_id'))
                                <div class="mt-6">
                                    <a
                                        href="{{ route('products.index') }}"
                                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
                                    >
                                        Clear Filters
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
