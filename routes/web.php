<?php

use App\Http\Controllers\InvoiceController;
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

// Order API JSON Routes
Route::get('/orders', [OrderController::class, 'index']);
Route::post('/orders', [OrderController::class, 'store']);
Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

// Livewire Container Views & UI Routes
Route::get('/order-form', function () {
    return view('order-form-container');
});

Route::get('/orders-ui', function () {
    return view('order-list-container');
});

Route::get('/delivery-log', function () {
    return view('delivery-log-container');
});

// Invoice / Printable Receipt Route
Route::get('/invoices/{id}', [InvoiceController::class, 'show']);