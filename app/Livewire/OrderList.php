<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;

class OrderList extends Component
{
    public function updatePaymentStatus($orderId, $status)
    {
        $order = Order::findOrFail($orderId);
        $order->update(['payment_status' => $status]);
        session()->flash('success', "Payment status updated to {$status} for Order #{$orderId}");
    }

    public function updateOrderStatus($orderId, $status)
    {
        $order = Order::findOrFail($orderId);
        $order->update(['status' => $status]);
        session()->flash('success', "Order status updated to {$status} for Order #{$orderId}");
    }

    public function render()
    {
        $orders = Order::with(['customer', 'orderItems.product'])->latest()->get();

        return view('livewire.order-list', [
            'orders' => $orders
        ]);
    }
}