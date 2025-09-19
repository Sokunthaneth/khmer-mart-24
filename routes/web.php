<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;

Route::get('/', [PageController::class, 'home']);
Route::resource('products', ProductController::class);
Route::get('/about', [PageController::class, 'about']);
Route::get('/register', [PageController::class, 'showRegister']);
Route::post('/register', [PageController::class, 'register']);
