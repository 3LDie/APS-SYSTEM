<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class StockProcurement extends Component
{
    public $supplier_id;
    public $product_id;
    public $quantity_added = 1;
    public $notes;

    public $suppliers;
    public $products;

    public function mount()
    {
        $this->suppliers = Supplier::all();
        $this->products = Product::all();
    }

    public function submitProcurement()
    {
        $this->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'product_id' => 'required|exists:products,id',
            'quantity_added' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($this->product_id);
            $product->increment('stock_quantity', $this->quantity_added);

            DB::commit();

            session()->flash('success', "Successfully added {$this->quantity_added} bundles to {$product->variety_name} ({$product->style_type}) stock!");
            return redirect()->to('/orders-ui');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.stock-procurement');
    }
}