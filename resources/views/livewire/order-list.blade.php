<div class="max-w-6xl mx-auto p-6 bg-white shadow-md rounded-lg relative">
    
    <!-- Top Action Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Newspaper Orders Management</h2>
        <div class="flex items-center space-x-2">
            <a href="/stock-procurement" class="bg-purple-600 text-white font-bold px-4 py-2 rounded-lg hover:bg-purple-700 transition">
                📦 Replenish Stock
            </a>
            <a href="/delivery-log" class="bg-green-600 text-white font-bold px-4 py-2 rounded-lg hover:bg-green-700 transition">
                🚚 Delivery Log
            </a>
            <a href="/order-form" class="bg-blue-600 text-white font-bold px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                + Record New Order
            </a>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Real-Time Dashboard KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="p-4 bg-blue-50 border-l-4 border-blue-500 rounded-r-lg shadow-sm">
            <p class="text-xs font-bold text-blue-600 uppercase tracking-wide">Total Sales Revenue</p>
            <p class="text-2xl font-extrabold text-gray-800">₱{{ number_format($totalRevenue, 2) }}</p>
        </div>
        <div class="p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg shadow-sm">
            <p class="text-xs font-bold text-red-600 uppercase tracking-wide">Outstanding Unpaid Balance</p>
            <p class="text-2xl font-extrabold text-red-700">₱{{ number_format($unpaidBalance, 2) }}</p>
        </div>
        <div class="p-4 bg-amber-50 border-l-4 border-amber-500 rounded-r-lg shadow-sm">
            <p class="text-xs font-bold text-amber-600 uppercase tracking-wide">Pending Deliveries</p>
            <p class="text-2xl font-extrabold text-gray-800">{{ $pendingDeliveries }} Orders</p>
        </div>
    </div>

    <!-- Low Stock Warning Banner -->
    @if($lowStockProducts->count() > 0)
        <div class="mb-6 p-4 bg-amber-100 border border-amber-300 text-amber-800 rounded-lg">
            <p class="font-bold mb-1">⚠️ Low Inventory Stock Warning:</p>
            <ul class="text-sm list-disc list-inside">
                @foreach($lowStockProducts as $product)
                    <li>
                        <span class="font-semibold">{{ $product->variety_name }} ({{ $product->style_type }})</span>: 
                        Only <span class="font-bold text-red-700">{{ $product->stock_quantity }}</span> bundles left.
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Orders Management Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse border border-gray-200">
            <thead>
                <tr class="bg-gray-100 border-b border-gray-200 text-gray-700 text-sm">
                    <th class="p-3 border-r align-middle">Order #</th>
                    <th class="p-3 border-r align-middle">Farmer & Post Location</th>
                    <th class="p-3 border-r align-middle">Items / Varieties</th>
                    <th class="p-3 border-r align-middle">Total Amount</th>
                    <th class="p-3 border-r align-middle">Payment Status & Proof</th>
                    <th class="p-3 border-r align-middle">Delivery Status</th>
                    <th class="p-3 border-r align-middle">Delivery Date</th>
                    <th class="p-3 text-center align-middle">Receipt</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <!-- Order # -->
                        <td class="p-3 border-r font-bold align-middle">#{{ $order->id }}</td>
                        
                        <!-- Farmer & Post Location -->
                        <td class="p-3 border-r align-middle">
                            <div class="font-semibold text-gray-800">{{ $order->customer->name }}</div>
                            <div class="text-xs text-gray-500">{{ $order->customer->delivery_post_location }}</div>
                        </td>
                        
                        <!-- Items / Varieties -->
                        <td class="p-3 border-r align-middle">
                            <ul class="text-sm list-disc list-inside space-y-1">
                                @foreach($order->orderItems as $item)
                                    <li>
                                        {{ $item->product->variety_name }} ({{ $item->product->style_type }}) 
                                        <span class="font-semibold">x{{ $item->quantity }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                        
                        <!-- Total Amount -->
                        <td class="p-3 border-r font-bold text-green-700 whitespace-nowrap align-middle">
                            ₱{{ number_format($order->total_amount, 2) }}
                        </td>
                        
                        <!-- Payment Status & Proof (Unified Clean Alignment) -->
                        <td class="p-3 border-r align-middle">
                            <div class="flex items-center space-x-2">
                                <span class="px-2.5 py-1 text-xs font-bold rounded-md whitespace-nowrap {{ $order->payment_status === 'Paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $order->payment_status }}
                                </span>
                                
                                <button wire:click="openPaymentModal({{ $order->id }})" class="text-xs bg-blue-100 text-blue-700 font-bold px-3 py-1.5 rounded-md hover:bg-blue-200 transition inline-flex items-center space-x-1 whitespace-nowrap">
                                    <span>📷</span>
                                    <span>{{ $order->proof_of_payment_path ? 'View Proof' : 'Upload Proof' }}</span>
                                </button>
                            </div>
                            
                            @if($order->proof_of_payment_path)
                                <div class="mt-1">
                                    <a href="{{ asset('storage/' . $order->proof_of_payment_path) }}" target="_blank" class="text-xs text-blue-600 hover:underline font-semibold inline-flex items-center space-x-1">
                                        <span>View Uploaded Slip</span>
                                        <span>🔗</span>
                                    </a>
                                </div>
                            @endif
                        </td>
                        
                        <!-- Delivery Status Dropdown -->
                        <td class="p-3 border-r align-middle">
                            <select wire:change="updateOrderStatus({{ $order->id }}, $event.target.value)" class="text-sm border border-gray-300 rounded-md p-1.5 font-semibold bg-white shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                                <option value="Pending" {{ $order->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Picked Up" {{ $order->status === 'Picked Up' ? 'selected' : '' }}>Picked Up</option>
                                <option value="Delivered" {{ $order->status === 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="Cancelled" {{ $order->status === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </td>
                        
                        <!-- Delivery Date -->
                        <td class="p-3 border-r text-sm text-gray-600 whitespace-nowrap align-middle">
                            {{ $order->delivery_date }}
                        </td>
                        
                        <!-- Receipt Action -->
                        <td class="p-3 text-center align-middle">
                            <a href="/invoices/{{ $order->id }}" target="_blank" class="bg-gray-200 text-gray-800 text-xs px-3 py-1.5 rounded-md font-bold hover:bg-gray-300 transition inline-flex items-center space-x-1 whitespace-nowrap">
                                <span>📄</span>
                                <span>Receipt</span>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="p-4 text-center text-gray-500">No orders recorded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Upload Payment Proof Modal -->
    @if($selectedOrderId)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
                <h3 class="text-lg font-bold mb-4 text-gray-800">Upload Payment Proof for Order #{{ $selectedOrderId }}</h3>
                
                <form wire:submit.prevent="savePaymentWithProof" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Payment Status</label>
                        <select wire:model="paymentStatus" class="w-full border-gray-300 rounded-lg p-2 border">
                            <option value="Unpaid">Unpaid</option>
                            <option value="Partial">Partial</option>
                            <option value="Paid">Paid</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">Attach GCash / Deposit Slip Photo</label>
                        <input type="file" wire:model="paymentProofPhoto" accept="image/*" class="w-full border-gray-300 rounded-lg p-2 border">
                        @error('paymentProofPhoto') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" wire:click="closePaymentModal" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-400">
                            Cancel
                        </button>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-blue-700">
                            Save Payment Proof
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>