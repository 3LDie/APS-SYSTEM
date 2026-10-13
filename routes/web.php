<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Models\Customer;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Inventory Management Routes
Route::get('/products', [ProductController::class, 'index']);
Route::patch('/products/{product}', [ProductController::class, 'updateStock'])->name('products.update');

// Customer Verification Route
Route::get('/customers', function() {
    return Customer::all();
});

// Order Management Routes
Route::get('/orders', [OrderController::class, 'index']);
Route::post('/orders', [OrderController::class, 'store']);
Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

// Livewire Order Entry Form Route via Wrapper View
Route::get('/order-form', function () {
    return view('order-form-container');
});