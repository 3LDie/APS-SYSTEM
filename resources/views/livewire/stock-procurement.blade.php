<div class="max-w-3xl mx-auto p-6 bg-white shadow-sm border border-gray-200 rounded-xl">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Log Supplier Stock Procurement</h2>
            <p class="text-sm text-gray-500 mt-1">Record incoming newspaper deliveries to replenish inventory levels.</p>
        </div>
        <a href="/products" class="text-sm text-gray-600 hover:text-gray-900 font-semibold">&larr; Back to Inventory</a>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded-lg font-medium">
            {{ session('error') }}
        </div>
    @endif

    <form wire:submit.prevent="submitProcurement">
        <!-- Select Supplier / Manufacturer -->
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Supplier / Manufacturer</label>
            <select wire:model="supplier_id" class="w-full border border-gray-300 rounded-lg shadow-sm p-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Select Supplier...</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }} ({{ $supplier->contact_person ?? 'N/A' }})</option>
                @endforeach
            </select>
            @error('supplier_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <!-- Select Product Variety & Style -->
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Newspaper Variety & Style to Replenish</label>
            <select wire:model="product_id" class="w-full border border-gray-300 rounded-lg shadow-sm p-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Select Newspaper Stock...</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">
                        {{ $product->variety_name }} [{{ $product->style_type }}] — Current Stock: {{ $product->stock_quantity }} units
                    </option>
                @endforeach
            </select>
            @error('product_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <!-- Quantity Added -->
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Quantity Picked Up / Received (Bundles)</label>
            <input type="number" wire:model="quantity_added" min="1" class="w-full border border-gray-300 rounded-lg shadow-sm p-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('quantity_added') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <!-- Procurement Notes -->
        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Notes / Batch Remarks</label>
            <textarea wire:model="notes" rows="3" placeholder="e.g. Picked up 50 bundles of Korean Cut from Mang Juan..." class="w-full border border-gray-300 rounded-lg shadow-sm p-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 shadow-sm transition">
            Save Procurement & Replenish Stock
        </button>
    </form>
</div>