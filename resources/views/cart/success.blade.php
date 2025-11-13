<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Order Confirmation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center">
                    <!-- Success Icon -->
                    <div class="flex justify-center mb-6">
                        <div class="rounded-full bg-green-100 p-3">
                            <svg class="w-16 h-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Success Message -->
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">Order Placed Successfully!</h1>
                    <p class="text-lg text-gray-600 mb-6">
                        Thank you for your purchase, {{ $customerName }}. Your order has been received and is being
                        processed.
                    </p>

                    <!-- Order Details -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Details</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-left">
                            <div>
                                <p class="text-sm text-gray-600">Order Number</p>
                                <p class="font-semibold text-gray-900">{{ $orderId }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Total Amount</p>
                                <p class="font-semibold text-gray-900">${{ number_format($total, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Order Date</p>
                                <p class="font-semibold text-gray-900">{{ now()->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Status</p>
                                <p class="font-semibold text-green-600">Processing</p>
                            </div>
                        </div>
                    </div>

                    <!-- What's Next -->
                    <div class="bg-blue-50 rounded-lg p-6 mb-6 text-left">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">What happens next?</h3>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                You will receive an order confirmation email shortly
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                We'll process your order within 1-2 business days
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                Your order will be shipped and you'll receive tracking information
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                                We'll notify you when your order is delivered
                            </li>
                        </ul>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('products.index') }}"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Continue Shopping
                        </a>

                        @auth
                            <a href="{{ route('dashboard') }}"
                                class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                                View Orders
                            </a>
                        @endauth
                    </div>

                    <!-- Support Information -->
                    <div class="mt-8 pt-6 border-t text-sm text-gray-500">
                        <p>Need help with your order? Contact our support team at
                            <a href="mailto:support@khmermart24.com"
                                class="text-blue-600 hover:text-blue-800">support@khmermart24.com</a>
                            or call us at <span class="font-medium">+855 123 456 789</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>