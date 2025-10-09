<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard/order/{id}', [DashboardController::class, 'showOrder'])->middleware(['auth', 'verified'])->name('dashboard.order.show');
Route::get('/dashboard/my-orders', [DashboardController::class, 'userOrders'])->middleware(['auth', 'verified'])->name('dashboard.user-orders');

// Public product routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Protected product routes (create, edit, delete)
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::patch('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
});

// Cart routes (available to both guests and authenticated users)
Route::get('/cart', [CartController::class, 'show'])->name('cart.show');

// SKU-based cart routes
Route::post('/cart/add-sku', [CartController::class, 'addSku'])->name('cart.add.sku');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.update-quantity');

// Legacy product-based cart route (keep for backward compatibility)
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');

Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::post('/checkout', [CartController::class, 'submitOrder'])->name('cart.submit');

require __DIR__.'/auth.php';
