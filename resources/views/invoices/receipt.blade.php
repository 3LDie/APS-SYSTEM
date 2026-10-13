  <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->id }} - Newspaper Distribution</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background-color: white !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100 p-6">

    <!-- Print Action Buttons -->
    <div class="max-w-3xl mx-auto mb-6 flex justify-between items-center no-print">
        <a href="/orders-ui" class="text-gray-600 hover:text-gray-900 font-semibold">&larr; Back to Dashboard</a>
        <button onclick="window.print()" class="bg-blue-600 text-white font-bold px-5 py-2 rounded-lg shadow hover:bg-blue-700">
            🖨️ Print Receipt / Save PDF
        </button>
    </div>

    <!-- Printable Receipt Card -->
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-md border border-gray-200">
        <!-- Header -->
        <div class="flex justify-between items-start border-b pb-6 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">APS Newspaper Supply</h1>
                <p class="text-sm text-gray-500">Vegetable-Wrapping Newspaper Distributor</p>
                <p class="text-xs text-gray-400 mt-1">Trading Post / Farm Delivery Service</p>
            </div>
            <div class="text-right">
                <h2 class="text-xl font-bold text-blue-600">OFFICIAL RECEIPT</h2>
                <p class="text-sm font-semibold text-gray-700">Invoice #: <span class="font-mono">{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span></p>
                <p class="text-xs text-gray-500">Date: {{ $order->order_date }}</p>
            </div>
        </div>

        <!-- Customer & Delivery Info -->
        <div class="grid grid-cols-2 gap-6 mb-6 text-sm">
            <div>
                <p class="font-bold text-gray-700">Billed To (Farmer):</p>
                <p class="text-gray-800 font-semibold">{{ $order->customer->name }}</p>
                <p class="text-gray-600">Delivery Post: {{ $order->customer->delivery_post_location }}</p>
                <p class="text-gray-600">Contact: {{ $order->customer->contact_number ?? 'N/A' }}</p>
            </div>
            <div class="text-right">
                <p class="font-bold text-gray-700">Status Overview:</p>
                <p class="text-gray-800 font-semibold">
                    Payment Status: 
                    <span class="{{ $order->payment_status === 'Paid' ? 'text-green-600' : 'text-red-600' }}">
                        {{ strtoupper($order->payment_status) }}
                    </span>
                </p>
                <p class="text-gray-600">Delivery Status: {{ $order->status }}</p>
                <p class="text-gray-600">Target Delivery: {{ $order->delivery_date }}</p>
            </div>
        </div>

        <!-- Order Items Table -->
        <table class="w-full text-left border-collapse border border-gray-200 mb-6 text-sm">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="p-3 border">Variety</th>
                    <th class="p-3 border">Style</th>
                    <th class="p-3 border text-center">Qty (Bundles)</th>
                    <th class="p-3 border text-right">Unit Price</th>
                    <th class="p-3 border text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                    <tr class="border-b">
                        <td class="p-3 border font-semibold">{{ $item->product->variety_name }}</td>
                        <td class="p-3 border">{{ $item->product->style_type }}</td>
                        <td class="p-3 border text-center font-bold">{{ $item->quantity }}</td>
                        <td class="p-3 border text-right">₱{{ number_format($item->product->unit_price, 2) }}</td>
                        <td class="p-3 border text-right font-bold text-gray-800">₱{{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-gray-50 font-bold text-base">
                    <td colspan="4" class="p-3 border text-right text-gray-700">TOTAL AMOUNT DUE:</td>
                    <td class="p-3 border text-right text-green-700">₱{{ number_format($order->total_amount, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <!-- Signatures & Notes -->
        <div class="grid grid-cols-2 gap-10 mt-10 pt-6 border-t text-sm">
            <div>
                <p class="font-bold text-gray-700 mb-8">Issued By (Staff):</p>
                <div class="border-b border-gray-400 w-3/4"></div>
                <p class="text-xs text-gray-500 mt-1">Authorized Signature</p>
            </div>
            <div class="text-right">
                <p class="font-bold text-gray-700 mb-8">Received By (Farmer / Post Representative):</p>
                <div class="border-b border-gray-400 w-3/4 ml-auto"></div>
                <p class="text-xs text-gray-500 mt-1">Proof of Receipt Signature</p>
            </div>
        </div>
    </div>

</body>
</html>