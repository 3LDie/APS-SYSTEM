<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;

class OrderList extends Component
{
    use WithFileUploads;

    public $selectedOrderId = null;
    public $paymentStatus = 'Paid';
    public $paymentProofPhoto;

    public function openPaymentModal($orderId)
    {
        $this->selectedOrderId = $orderId;
        $order = Order::findOrFail($orderId);
        $this->paymentStatus = $order->payment_status === 'Unpaid' ? 'Paid' : $order->payment_status;
    }

    public function closePaymentModal()
    {
        $this->selectedOrderId = null;
        $this->paymentProofPhoto = null;
    }

    public function savePaymentWithProof()
    {
        $this->validate([
            'selectedOrderId' => 'required|exists:orders,id',
            'paymentStatus' => 'required|string',
            'paymentProofPhoto' => 'nullable|image|max:2048',
        ]);

        $order = Order::findOrFail($this->selectedOrderId);

        $imagePath = $order->proof_of_payment_path;
        if ($this->paymentProofPhoto) {
            $imagePath = $this->paymentProofPhoto->store('payment-proofs', 'public');
        }

        $order->update([
            'payment_status' => $this->paymentStatus,
            'proof_of_payment_path' => $imagePath,
        ]);

        session()->flash('success', "Payment status updated to {$this->paymentStatus} for Order #{$order->id}!");
        $this->closePaymentModal();
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

        $totalRevenue = Order::sum('total_amount');
        $unpaidBalance = Order::where('payment_status', '!=', 'Paid')->sum('total_amount');
        $pendingDeliveries = Order::where('status', '!=', 'Delivered')->count();
        $lowStockProducts = Product::whereColumn('stock_quantity', '<=', 'reorder_level')->get();

        return view('livewire.order-list', [
            'orders' => $orders,
            'totalRevenue' => $totalRevenue,
            'unpaidBalance' => $unpaidBalance,
            'pendingDeliveries' => $pendingDeliveries,
            'lowStockProducts' => $lowStockProducts,
        ]);
    }
}