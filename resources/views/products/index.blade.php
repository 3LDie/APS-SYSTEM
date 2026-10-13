<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newspaper Distribution - Inventory Stock Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 py-10">
    <div class="max-w-6xl mx-auto px-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Inventory Stock & Pricing Management</h2>
            <div class="space-x-3">
                <a href="/orders" class="bg-gray-600 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-700 font-semibold">View Orders</a>
                <a href="/order-form" class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 font-semibold">+ Record New Order</a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Variety</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price (₱)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quick Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($products as $product)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                {{ $product->variety_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $product->style_type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 py-1 font-bold text-xs rounded {{ $product->stock_quantity <= 10 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $product->stock_quantity }} units
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                ₱{{ number_format($product->unit_price, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <form action="{{ route('products.update', $product->id) }}" method="POST" class="flex items-center space-x-2">
                                    @csrf
                                    @method('PATCH')
                                    
                                    <div>
                                        <label class="block text-[10px] text-gray-500">Stock Qty</label>
                                        <input type="number" name="stock_quantity" value="{{ $product->stock_quantity }}" min="0" class="w-20 text-xs border-gray-300 rounded p-1 border">
                                    </div>

                                    <div>
                                        <label class="block text-[10px] text-gray-500">Price (₱)</label>
                                        <input type="number" step="0.01" name="unit_price" value="{{ $product->unit_price }}" min="0" class="w-24 text-xs border-gray-300 rounded p-1 border">
                                    </div>

                                    <div class="pt-4">
                                        <button type="submit" class="bg-indigo-600 text-white px-3 py-1 text-xs rounded hover:bg-indigo-700 font-semibold">Save</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>