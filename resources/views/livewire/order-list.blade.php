<div class="max-w-6xl mx-auto p-6 bg-white shadow-md rounded-lg">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Newspaper Orders Management</h2>
        <a href="/order-form" class="bg-blue-600 text-white font-bold px-4 py-2 rounded-lg hover:bg-blue-700">
            + Record New Order
        </a>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse border border-gray-200">
            <thead>
                <tr class="bg-gray-100 border-b border-gray-200 text-gray-700">
                    <th class="p-3 border-r">Order #</th>
                    <th class="p-3 border-r">Farmer & Post Location</th>
                    <th class="p-3 border-r">Items / Varieties</th>
                    <th class="p-3 border-r">Total Amount</th>
                    <th class="p-3 border-r">Payment Status</th>
                    <th class="p-3 border-r">Delivery Status</th>
                    <th class="p-3">Delivery Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="p-3 border-r font-bold">#{{ $order->id }}</td>
                        <td class="p-3 border-r">
                            <div class="font-semibold text-gray-800">{{ $order->customer->name }}</div>
                            <div class="text-xs text-gray-500">{{ $order->customer->delivery_post_location }}</div>
                        </td>
                        <td class="p-3 border-r">
                            <ul class="text-sm list-disc list-inside">
                                @foreach($order->orderItems as $item)
                                    <li>
                                        {{ $item->product->variety_name }} ({{ $item->product->style_type }}) 
                                        <span class="font-semibold">x{{ $item->quantity }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="p-3 border-r font-bold text-green-700">₱{{ number_format($order->total_amount, 2) }}</td>
                        <td class="p-3 border-r">
                            <select wire:change="updatePaymentStatus({{ $order->id }}, $event.target.value)" class="text-sm border rounded p-1 font-semibold {{ $order->payment_status === 'Paid' ? 'bg-green-100 text-green-800 border-green-300' : 'bg-red-100 text-red-800 border-red-300' }}">
                                <option value="Unpaid" {{ $order->payment_status === 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                                <option value="Partial" {{ $order->payment_status === 'Partial' ? 'selected' : '' }}>Partial</option>
                                <option value="Paid" {{ $order->payment_status === 'Paid' ? 'selected' : '' }}>Paid</option>
                            </select>
                        </td>
                        <td class="p-3 border-r">
                            <select wire:change="updateOrderStatus({{ $order->id }}, $event.target.value)" class="text-sm border rounded p-1 font-semibold">
                                <option value="Pending" {{ $order->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Picked Up" {{ $order->status === 'Picked Up' ? 'selected' : '' }}>Picked Up</option>
                                <option value="Delivered" {{ $order->status === 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="Cancelled" {{ $order->status === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </td>
                        <td class="p-3 text-sm text-gray-600">{{ $order->delivery_date }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-4 text-center text-gray-500">No orders recorded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>