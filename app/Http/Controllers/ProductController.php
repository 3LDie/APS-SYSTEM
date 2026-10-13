<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Display all inventory products
    public function index()
    {
        $products = Product::all();

        if (request()->wantsJson()) {
            return response()->json($products);
        }

        return view('products.index', compact('products'));
    }

    // Update stock quantity and pricing
    public function updateStock(Request $request, Product $product)
    {
        $request->validate([
            'stock_quantity' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $product->update([
            'stock_quantity' => $request->stock_quantity,
            'unit_price' => $request->unit_price,
        ]);

        return redirect('/products')->with('success', "Inventory updated successfully for {$product->variety_name} ({$product->style_type})!");
    }
}