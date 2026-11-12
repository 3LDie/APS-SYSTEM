<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;

class DashboardController extends Controller
{
    public function index()
    {
        // Key Operational Metrics
        $totalCollectibles = Order::where('payment_status', 'Unpaid')
            ->where('status', '!=', 'Cancelled')
            ->sum('total_amount');

        $totalCollected = Order::where('payment_status', 'Paid')
            ->where('status', '!=', 'Cancelled')
            ->sum('total_amount');

        $pendingDeliveriesCount = Order::where('status', 'Pending')->count();
        $totalCustomersCount = Customer::count();

        // Low Stock Products (15 units or fewer)
        $lowStockProducts = Product::where('stock_quantity', '<=', 15)
            ->orderBy('stock_quantity', 'asc')
            ->get();

        // Recent & Pending Orders for Quick View
        $pendingOrders = Order::with(['customer', 'orderItems.product'])
            ->where('status', 'Pending')
            ->orderBy('delivery_date', 'asc')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalCollectibles',
            'totalCollected',
            'pendingDeliveriesCount',
            'totalCustomersCount',
            'lowStockProducts',
            'pendingOrders'
        ));
    }
}