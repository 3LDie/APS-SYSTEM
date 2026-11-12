@extends('layouts.app')

@section('title', 'Inventory & Pricing - APS Distribution')

@section('content')
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Inventory Stock & Pricing Management</h1>
            <div class="flex items-center space-x-3">
                <a href="/stock-procurement" class="bg-purple-600 hover:bg-purple-700 text-white font-bold px-4 py-2.5 rounded-lg shadow-sm transition">
                    📦 Replenish Stock
                </a>
                <a href="/orders" class="bg-slate-700 hover:bg-slate-800 text-white font-bold px-4 py-2.5 rounded-lg shadow-sm transition">
                    View Orders
                </a>
                <a href="/order-form" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-2.5 rounded-lg shadow-sm transition">
                    + Record New Order
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg font-medium">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-500 uppercase tracking-wider">
                            <th class="p-4">Variety</th>
                            <th class="p-4">Type</th>
                            <th class="p-4">Current Stock</th>
                            <th class="p-4">Unit Price (₱)</th>
                            <th class="p-4">Quick Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-sm">
                        @forelse($products as $product)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-4 font-bold text-gray-900">{{ $product->variety_name }}</td>
                                <td class="p-4">
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-50 text-blue-700">
                                        {{ $product->style_type }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-md {{ $product->stock_quantity <= ($product->reorder_level ?? 15) ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $product->stock_quantity }} units
                                    </span>
                                </td>
                                <td class="p-4 font-bold text-gray-900">₱{{ number_format($product->unit_price, 2) }}</td>
                                <td class="p-4">
                                    <form action="{{ route('products.update', $product->id) }}" method="POST" class="flex items-end space-x-3">
                                        @csrf
                                        @method('PATCH')
                                        <div>
                                            <label class="block text-[11px] font-semibold text-gray-500 mb-1">Stock Qty</label>
                                            <input type="number" name="stock_quantity" value="{{ $product->stock_quantity }}" min="0" class="w-24 border border-gray-300 rounded-md px-2.5 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-semibold text-gray-500 mb-1">Price (₱)</label>
                                            <input type="number" step="0.01" name="unit_price" value="{{ intval($product->unit_price) == $product->unit_price ? intval($product->unit_price) : $product->unit_price }}" min="0" class="w-24 border border-gray-300 rounded-md px-2.5 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        </div>
                                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-1.5 rounded-md text-xs transition">
                                            Save
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-6 text-center text-gray-500">No newspaper inventory items found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection