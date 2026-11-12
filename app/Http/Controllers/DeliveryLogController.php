<?php

namespace App\Http\Controllers;

use App\Models\Order;

class DeliveryLogController extends Controller
{
    public function index()
    {
        // Fetch all orders with customer and item relations, ordered by delivery date
        $deliveries = Order::with(['customer', 'orderItems.product'])
            ->orderBy('delivery_date', 'desc')
            ->get();

        // Summary counts for the delivery log header
        $totalDeliveries = $deliveries->count();
        $pendingCount = $deliveries->where('status', 'Pending')->count();
        $deliveredCount = $deliveries->where('status', 'Delivered')->count();

        return view('delivery-log.index', compact('deliveries', 'totalDeliveries', 'pendingCount', 'deliveredCount'));
    }
}