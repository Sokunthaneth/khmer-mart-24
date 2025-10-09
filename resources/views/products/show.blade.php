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
                        <div class="aspect-w-1 aspect-h-1">
                            @if($product->cover)
                                <img src="{{ $product->cover }}" alt="{{ $product->name }}"
                                    class="w-full h-96 object-cover object-center rounded-lg shadow-md">
                            @else
                                <div class="w-full h-96 bg-gray-300 rounded-lg flex items-center justify-center">
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
                                    <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit"
                                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2 2m2-2v4a2 2 0 002 2h2a2 2 0 002-2v-4M9 21h6">
                                                </path>
                                            </svg>
                                            Add to Cart
                                        </button>
                                    </form>
                                @else
                                    <div class="flex-1">
                                        <a href="{{ route('login') }}"
                                            class="w-full bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center">
                                            Login to Add to Cart
                                        </a>
                                    </div>
                                @endauth
                            </div>

                            <!-- Product Meta Information -->
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
                                            {{ $product->created_at->format('M d, Y') }}</dd>
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