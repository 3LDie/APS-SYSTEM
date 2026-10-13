<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newspaper Distribution - Order History</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 py-10">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Farmer Orders & Distribution History</h2>
            <a href="/order-form" class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 font-semibold">+ Record New Order</a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if($orders->isEmpty())
            <div class="bg-white p-6 rounded-lg shadow text-center text-gray-500">
                No orders have been recorded yet. Go ahead and submit an order from the order form!
            </div>
        @else
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Farmer / Post</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items Ordered</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Delivery Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Manage Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($orders as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    <div class="font-semibold">{{ $order->customer->name ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-500">{{ $order->customer->delivery_post_location ?? '' }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <ul class="list-disc list-inside">
                                        @foreach($order->orderItems as $item)
                                            <li>
                                                {{ $item->product->variety_name ?? 'Item' }} [{{ $item->product->style_type ?? '' }}] 
                                                &times; {{ $item->quantity }} (₱{{ number_format($item->subtotal, 2) }})
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">₱{{ number_format($order->total_amount, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->delivery_date }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" class="flex items-center space-x-2">
                                        @csrf
                                        @method('PATCH')
                                        
                                        <!-- Status Selector -->
                                        <select name="status" class="text-xs border-gray-300 rounded p-1 border">
                                            <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="Delivered" {{ $order->status == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                            <option value="Cancelled" {{ $order->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>

                                        <!-- Payment Status Selector -->
                                        <select name="payment_status" class="text-xs border-gray-300 rounded p-1 border">
                                            <option value="Unpaid" {{ $order->payment_status == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                                            <option value="Paid" {{ $order->payment_status == 'Paid' ? 'selected' : '' }}>Paid</option>
                                        </select>

                                        <button type="submit" class="bg-indigo-600 text-white px-2 py-1 text-xs rounded hover:bg-indigo-700">Update</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</body>
</html>