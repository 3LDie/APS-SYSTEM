<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    // Display a list of orders
    public function index()
    {
        $orders = Order::with(['customer', 'orderItems.product', 'delivery'])->latest()->get();

        if (request()->wantsJson()) {
            return response()->json($orders);
        }

        return view('orders.index', compact('orders'));
    }

    // Store a new order placed by a farmer
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'delivery_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $totalAmount = 0;

            $order = Order::create([
                'customer_id' => $request->customer_id,
                'order_date' => now(),
                'delivery_date' => $request->delivery_date,
                'status' => 'Pending',
                'payment_status' => 'Unpaid',
                'total_amount' => 0,
            ]);

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->variety_name} ({$product->style_type}). Available: {$product->stock_quantity}");
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

            if (request()->wantsJson()) {
                return response()->json([
                    'message' => 'Order successfully created and inventory updated!',
                    'order' => $order->load('orderItems.product', 'customer')
                ], 201);
            }

            return redirect('/orders')->with('success', 'Order successfully created and inventory updated!');

        } catch (\Exception $e) {
            DB::rollBack();

            if (request()->wantsJson()) {
                return response()->json(['error' => $e->getMessage()], 422);
            }

            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    // Update order status, payment status, and optional proof of payment slip
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:Pending,Picked Up,Delivered,Cancelled',
            'payment_status' => 'required|in:Unpaid,Partial,Paid',
            'proof_of_payment' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = $order->proof_of_payment_path;

        if ($request->hasFile('proof_of_payment')) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('proof_of_payment')->store('payment-proofs', 'public');
        }

        $order->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status,
            'proof_of_payment_path' => $imagePath,
        ]);

        return redirect('/orders')->with('success', "Order #{$order->id} updated successfully!");
    }
}