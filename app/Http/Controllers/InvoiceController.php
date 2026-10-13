<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    // Render printable invoice / receipt view
    public function show($id)
    {
        $order = Order::with(['customer', 'orderItems.product', 'delivery'])->findOrFail($id);

        return view('invoices.receipt', compact('order'));
    }
}