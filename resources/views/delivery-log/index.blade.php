@extends('layouts.app')

@section('title', 'Delivery & Dispatch Log - Newspaper Distribution')

@section('content')
<div class="max-w-7xl mx-auto px-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Delivery & Dispatch Log</h1>
            <p class="text-sm text-gray-500 mt-1">Track daily dispatch schedules, farmer locations, and delivery statuses.</p>
        </div>
        <div class="space-x-3">
            <a href="/orders" class="bg-gray-600 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-700 font-semibold text-sm">Manage Orders</a>
            <a href="/order-form" class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 font-semibold text-sm">+ Record New Order</a>
        </div>
    </div>

    <!-- Quick Stats Bar -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-4 rounded-lg shadow border-l-4 border-blue-500">
            <div class="text-xs font-semibold text-gray-500 uppercase">Total Scheduled Dispatches</div>
            <div class="text-2xl font-bold text-gray-900 mt-1">{{ $totalDeliveries }}</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow border-l-4 border-amber-500">
            <div class="text-xs font-semibold text-gray-500 uppercase">Pending Dispatches</div>
            <div class="text-2xl font-bold text-amber-600 mt-1">{{ $pendingCount }}</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow border-l-4 border-emerald-500">
            <div class="text-xs font-semibold text-gray-500 uppercase">Successfully Delivered</div>
            <div class="text-2xl font-bold text-emerald-600 mt-1">{{ $deliveredCount }}</div>
        </div>
    </div>

    <!-- Delivery Table -->
    @if($deliveries->isEmpty())
        <div class="bg-white p-8 rounded-lg shadow text-center text-gray-500">
            No delivery records found.
        </div>
    @else
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Delivery Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Farmer / Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Drop-off Post / Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dispatched Items</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($deliveries as $delivery)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                {{ $delivery->delivery_date }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                {{ $delivery->customer->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs font-medium">
                                    {{ $delivery->customer->delivery_post_location ?? 'Standard Post' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <ul class="list-disc list-inside">
                                    @foreach($delivery->orderItems as $item)
                                        <li>{{ $item->product->variety_name ?? 'Item' }} [{{ $item->product->style_type ?? '' }}] &times; {{ $item->quantity }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $delivery->status == 'Delivered' ? 'bg-green-100 text-green-800' : ($delivery->status == 'Cancelled' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800') }}">
                                    {{ $delivery->status }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection