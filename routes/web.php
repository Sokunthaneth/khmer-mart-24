<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PageController;

Route::get('/', [PageController::class, 'home']);
Route::get('/about', [PageController::class, 'about']);
Route::get('/register', [PageController::class, 'showRegister']);
Route::post('/register', [PageController::class, 'register']);
