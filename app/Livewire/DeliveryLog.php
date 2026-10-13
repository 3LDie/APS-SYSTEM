<?php

namespace App\Livewire;

use App\Models\Delivery;
use App\Models\Order;
use Livewire\Component;
use Livewire\WithFileUploads;

class DeliveryLog extends Component
{
    use WithFileUploads;

    public $order_id;
    public $delivered_by;
    public $notes;
    public $proof_photo;

    public $orders;

    public function mount()
    {
        // Fetch orders that are not yet marked as delivered
        $this->orders = Order::where('status', '!=', 'Delivered')->with('customer')->get();
    }

    public function submitDelivery()
    {
        $this->validate([
            'order_id' => 'required|exists:orders,id',
            'delivered_by' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'proof_photo' => 'nullable|image|max:2048', // Max 2MB image
        ]);

        $imagePath = null;
        if ($this->proof_photo) {
            $imagePath = $this->proof_photo->store('delivery-proofs', 'public');
        }

        // Record Delivery Entry
        Delivery::create([
            'order_id' => $this->order_id,
            'delivered_by' => $this->delivered_by,
            'delivery_timestamp' => now(),
            'proof_of_delivery_path' => $imagePath,
            'notes' => $this->notes,
        ]);

        // Automatically update order status to 'Delivered'
        $order = Order::findOrFail($this->order_id);
        $order->update(['status' => 'Delivered']);

        session()->flash('success', "Delivery proof logged successfully for Order #{$this->order_id}!");
        return redirect()->to('/orders-ui');
    }

    public function render()
    {
        return view('livewire.delivery-log');
    }
}