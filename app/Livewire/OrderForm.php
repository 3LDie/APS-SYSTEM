<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class OrderForm extends Component
{
    public $customers;
    public $products;

    public $customer_id;
    public $delivery_date;
    public $items = [];

    public function mount()
    {
        $this->customers = Customer::all();
        $this->products = Product::all();
        $this->delivery_date = date('Y-m-d');
        $this->addItemRow();
    }

    public function addItemRow()
    {
        $this->items[] = ['product_id' => '', 'quantity' => 1];
    }

    public function removeItemRow($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function submitOrder()
    {
        $this->validate([
            'customer_id' => 'required',
            'delivery_date' => 'required|date',
            'items.*.product_id' => 'required',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $totalAmount = 0;

            $order = Order::create([
                'customer_id' => $this->customer_id,
                'order_date' => now(),
                'delivery_date' => $this->delivery_date,
                'status' => 'Pending',
                'payment_status' => 'Unpaid',
                'total_amount' => 0,
            ]);

            foreach ($this->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->variety_name} ({$product->style_type}). Only {$product->stock_quantity} left.");
                }

                $subtotal = $product->unit_price * $item['quantity'];
                $totalAmount += $subtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotal,
                ]);

                $product->decrement('stock_quantity', $item['quantity']);
            }

            $order->update(['total_amount' => $totalAmount]);

            DB::commit();

            session()->flash('success', 'Order successfully placed and inventory updated!');
            return redirect()->to('/orders');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.order-form');
    }
}