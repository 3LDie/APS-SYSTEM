@extends('layouts.app')

@section('title', 'Orders Management - APS Distribution')

@section('content')
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Farmer Orders & Distribution History</h1>
            <a href="/order-form" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-2.5 rounded-lg shadow-sm transition">
                + Record New Order
            </a>
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
                            <th class="p-4">Order ID</th>
                            <th class="p-4">Farmer / Post</th>
                            <th class="p-4">Items Ordered</th>
                            <th class="p-4">Total Amount</th>
                            <th class="p-4">Delivery Date</th>
                            <th class="p-4">Manage Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-sm">
                        @forelse($orders as $order)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-4 font-bold text-gray-900">#{{ $order->id }}</td>
                                <td class="p-4">
                                    <div class="font-bold text-gray-800">{{ $order->customer->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $order->customer->delivery_post_location }}</div>
                                </td>
                                <td class="p-4">
                                    <ul class="list-disc list-inside text-gray-700 space-y-1">
                                        @foreach($order->orderItems as $item)
                                            <li>
                                                {{ $item->product->variety_name }} [{{ $item->product->style_type }}] &times; {{ $item->quantity }} (₱{{ number_format($item->subtotal, 2) }})
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="p-4 font-bold text-gray-900">₱{{ number_format($order->total_amount, 2) }}</td>
                                <td class="p-4 text-gray-600">{{ $order->delivery_date }}</td>
                                <td class="p-4">
                                    <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" class="flex items-center space-x-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="border border-gray-300 rounded-md px-2.5 py-1.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="Pending" {{ $order->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="Picked Up" {{ $order->status === 'Picked Up' ? 'selected' : '' }}>Picked Up</option>
                                            <option value="Delivered" {{ $order->status === 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                            <option value="Cancelled" {{ $order->status === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                        <select name="payment_status" class="border border-gray-300 rounded-md px-2.5 py-1.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="Unpaid" {{ $order->payment_status === 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                                            <option value="Partial" {{ $order->payment_status === 'Partial' ? 'selected' : '' }}>Partial</option>
                                            <option value="Paid" {{ $order->payment_status === 'Paid' ? 'selected' : '' }}>Paid</option>
                                        </select>
                                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-3 py-1.5 rounded-md text-xs transition">
                                            Update
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-6 text-center text-gray-500">No farmer orders recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection