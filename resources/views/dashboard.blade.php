@extends('layouts.app')

@section('title', 'Executive Dashboard - Newspaper Distribution')

@section('content')
<div class="max-w-7xl mx-auto px-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Distribution Overview</h1>
        <p class="text-sm text-gray-500 mt-1">Daily snapshot of collectibles, pending deliveries, and stock health.</p>
    </div>

    <!-- Top KPI Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Unpaid Collectibles -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-amber-500">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Unpaid Collectibles</div>
            <div class="mt-2 text-3xl font-bold text-gray-900">₱{{ number_format($totalCollectibles, 2) }}</div>
            <div class="mt-2 text-xs text-amber-600 font-medium">Awaiting farmer payment</div>
        </div>

        <!-- Total Collected Revenue -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-emerald-500">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Paid Revenue</div>
            <div class="mt-2 text-3xl font-bold text-gray-900">₱{{ number_format($totalCollected, 2) }}</div>
            <div class="mt-2 text-xs text-emerald-600 font-medium">Settled orders</div>
        </div>

        <!-- Pending Deliveries -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pending Deliveries</div>
            <div class="mt-2 text-3xl font-bold text-gray-900">{{ $pendingDeliveriesCount }}</div>
            <div class="mt-2 text-xs text-blue-600 font-medium">
                <a href="/orders" class="hover:underline">View dispatch queue &rarr;</a>
            </div>
        </div>

        <!-- Low Stock Alerts -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 {{ $lowStockProducts->count() > 0 ? 'border-red-500' : 'border-gray-300' }}">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Low Stock Alerts</div>
            <div class="mt-2 text-3xl font-bold {{ $lowStockProducts->count() > 0 ? 'text-red-600' : 'text-gray-900' }}">
                {{ $lowStockProducts->count() }}
            </div>
            <div class="mt-2 text-xs text-gray-500">
                <a href="/products" class="hover:underline">Check inventory levels &rarr;</a>
            </div>
        </div>
    </div>

    <!-- Bottom Two-Column Operational Panels -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Upcoming Pending Deliveries (2/3 width) -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-bold text-gray-800">Upcoming Pending Deliveries</h2>
                <a href="/orders" class="text-sm text-blue-600 hover:underline font-medium">Manage All</a>
            </div>

            @if($pendingOrders->isEmpty())
                <div class="p-6 text-center text-gray-500 text-sm">
                    All caught up! No pending deliveries in the queue.
                </div>
            @else
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Farmer / Post</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Items</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Delivery Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($pendingOrders as $order)
                            <tr>
                                <td class="px-6 py-4 text-sm">
                                    <div class="font-semibold text-gray-900">{{ $order->customer->name ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-500">{{ $order->customer->delivery_post_location ?? '' }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    @foreach($order->orderItems as $item)
                                        <div>{{ $item->product->variety_name }} [{{ $item->product->style_type }}] &times; {{ $item->quantity }}</div>
                                    @endforeach
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $order->delivery_date }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900">₱{{ number_format($order->total_amount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Low Stock Watchlist (1/3 width) -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-bold text-gray-800">Low Stock Watchlist</h2>
                <a href="/products" class="text-sm text-blue-600 hover:underline font-medium">Restock</a>
            </div>

            @if($lowStockProducts->isEmpty())
                <div class="p-6 text-center text-gray-500 text-sm">
                    All newspaper varieties have healthy stock levels (above 15 units).
                </div>
            @else
                <ul class="divide-y divide-gray-200">
                    @foreach($lowStockProducts as $product)
                        <li class="px-6 py-4 flex justify-between items-center">
                            <div>
                                <div class="text-sm font-semibold text-gray-900">{{ $product->variety_name }}</div>
                                <div class="text-xs text-gray-500">{{ $product->style_type }}</div>
                            </div>
                            <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800">
                                {{ $product->stock_quantity }} left
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection