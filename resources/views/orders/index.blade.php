@extends('layouts.app')

@section('title', 'Orders Management - APS Distribution')

@section('content')
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Farmer Orders & Distribution History</h1>
                <p class="text-sm text-gray-500 mt-1">Manage delivery statuses, upload payment slips, and print official receipts.</p>
            </div>
            <a href="/order-form" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-2.5 rounded-lg shadow-sm transition">
                + Record New Order
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg font-medium">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded-lg text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-500 uppercase tracking-wider">
                            <th class="p-4 align-middle">Order ID</th>
                            <th class="p-4 align-middle">Farmer / Post</th>
                            <th class="p-4 align-middle">Items Ordered</th>
                            <th class="p-4 align-middle">Total Amount</th>
                            <th class="p-4 align-middle">Payment & Proof</th>
                            <th class="p-4 align-middle">Delivery Date</th>
                            <th class="p-4 align-middle">Manage Status</th>
                            <th class="p-4 text-center align-middle">Receipt</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-sm">
                        @forelse($orders as $order)
                            <tr class="hover:bg-gray-50 transition">
                                <!-- Order ID -->
                                <td class="p-4 font-bold text-gray-900 align-middle whitespace-nowrap">#{{ $order->id }}</td>

                                <!-- Farmer / Post -->
                                <td class="p-4 align-middle">
                                    <div class="font-bold text-gray-800">{{ $order->customer->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $order->customer->delivery_post_location }}</div>
                                </td>

                                <!-- Items Ordered -->
                                <td class="p-4 align-middle">
                                    <ul class="list-disc list-inside text-gray-700 space-y-1">
                                        @foreach($order->orderItems as $item)
                                            <li>
                                                {{ $item->product->variety_name }} [{{ $item->product->style_type }}] &times; {{ $item->quantity }} (₱{{ number_format($item->subtotal, 2) }})
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>

                                <!-- Total Amount -->
                                <td class="p-4 font-bold text-gray-900 align-middle whitespace-nowrap">
                                    ₱{{ number_format($order->total_amount, 2) }}
                                </td>

                                <!-- Payment Status & Proof Upload / Link -->
                                <td class="p-4 align-middle">
                                    <div class="flex items-center space-x-2">
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-md whitespace-nowrap
                                            {{ $order->payment_status === 'Paid' ? 'bg-green-100 text-green-800' : ($order->payment_status === 'Partial' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800') }}">
                                            {{ $order->payment_status }}
                                        </span>

                                        <button type="button"
                                            onclick="openPaymentModal({{ $order->id }}, '{{ $order->status }}', '{{ $order->payment_status }}')"
                                            class="text-xs bg-blue-50 text-blue-700 border border-blue-200 font-semibold px-2.5 py-1 rounded-md hover:bg-blue-100 transition inline-flex items-center space-x-1 whitespace-nowrap">
                                            <span>📷</span>
                                            <span>{{ $order->proof_of_payment_path ? 'Update Proof' : 'Upload Proof' }}</span>
                                        </button>
                                    </div>

                                    @if($order->proof_of_payment_path)
                                        <div class="mt-1.5">
                                            <a href="{{ asset('storage/' . $order->proof_of_payment_path) }}" target="_blank"
                                               class="text-xs text-blue-600 hover:text-blue-800 hover:underline font-semibold inline-flex items-center space-x-1">
                                                <span>View Uploaded Slip</span>
                                                <span>🔗</span>
                                            </a>
                                        </div>
                                    @endif
                                </td>

                                <!-- Delivery Date -->
                                <td class="p-4 text-gray-600 align-middle whitespace-nowrap">{{ $order->delivery_date }}</td>

                                <!-- Inline Status Update -->
                                <td class="p-4 align-middle">
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
                                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-3 py-1.5 rounded-md text-xs transition whitespace-nowrap">
                                            Update
                                        </button>
                                    </form>
                                </td>

                                <!-- Official Printable Receipt -->
                                <td class="p-4 text-center align-middle">
                                    <a href="/invoices/{{ $order->id }}" target="_blank"
                                       class="bg-gray-100 hover:bg-gray-200 border border-gray-300 text-gray-800 text-xs px-3 py-1.5 rounded-md font-bold transition inline-flex items-center space-x-1 whitespace-nowrap">
                                        <span>📄</span>
                                        <span>Receipt</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-6 text-center text-gray-500">No farmer orders recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Proof of Payment Upload Modal -->
    <div id="paymentProofModal" class="fixed inset-0 bg-slate-900 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6 mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 id="modalOrderTitle" class="text-lg font-bold text-gray-800">Upload Proof of Payment</h3>
                <button type="button" onclick="closePaymentModal()" class="text-gray-400 hover:text-gray-600 font-bold text-xl">&times;</button>
            </div>

            <form id="paymentProofForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" id="modalDeliveryStatus">

                <div class="mb-4">
                    <label for="modalPaymentStatus" class="block text-sm font-semibold text-gray-700 mb-1.5">Payment Status</label>
                    <select name="payment_status" id="modalPaymentStatus" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="Unpaid">Unpaid</option>
                        <option value="Partial">Partial</option>
                        <option value="Paid">Paid</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label for="proof_of_payment" class="block text-sm font-semibold text-gray-700 mb-1.5">Payment Slip / Receipt Image (JPG, PNG, WEBP)</label>
                    <input type="file" name="proof_of_payment" id="proof_of_payment" accept="image/*"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closePaymentModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold px-4 py-2 rounded-lg text-sm transition">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded-lg text-sm transition">
                        Save Payment Proof
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openPaymentModal(orderId, currentStatus, currentPaymentStatus) {
            const modal = document.getElementById('paymentProofModal');
            const form = document.getElementById('paymentProofForm');
            const title = document.getElementById('modalOrderTitle');
            const statusInput = document.getElementById('modalDeliveryStatus');
            const paymentSelect = document.getElementById('modalPaymentStatus');

            form.action = `/orders/${orderId}/status`;
            title.textContent = `Upload Payment Proof — Order #${orderId}`;
            statusInput.value = currentStatus;
            paymentSelect.value = currentPaymentStatus === 'Unpaid' ? 'Paid' : currentPaymentStatus;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closePaymentModal() {
            const modal = document.getElementById('paymentProofModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
@endsection