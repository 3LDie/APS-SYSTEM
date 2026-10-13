<div class="max-w-3xl mx-auto p-6 bg-white shadow-md rounded-lg">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Log Supplier Stock Procurement</h2>
        <a href="/orders-ui" class="text-gray-600 hover:text-gray-900 font-semibold">&larr; Back to Dashboard</a>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <form wire:submit.prevent="submitProcurement">
        <!-- Select Supplier / Manufacturer -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Supplier / Manufacturer</label>
            <select wire:model="supplier_id" class="w-full border-gray-300 rounded-lg shadow-sm p-2 border">
                <option value="">Select Supplier...</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }} ({{ $supplier->contact_person ?? 'N/A' }})</option>
                @endforeach
            </select>
            @error('supplier_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Select Product Variety & Style -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Newspaper Variety & Style to Replenish</label>
            <select wire:model="product_id" class="w-full border-gray-300 rounded-lg shadow-sm p-2 border">
                <option value="">Select Newspaper Stock...</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">
                        {{ $product->variety_name }} [{{ $product->style_type }}] — Current Stock: {{ $product->stock_quantity }}
                    </option>
                @endforeach
            </select>
            @error('product_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Quantity Added -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Quantity Picked Up / Received (Bundles)</label>
            <input type="number" wire:model="quantity_added" min="1" class="w-full border-gray-300 rounded-lg shadow-sm p-2 border">
            @error('quantity_added') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Procurement Notes -->
        <div class="mb-6">
            <label class="block text-gray-700 font-semibold mb-2">Notes / Batch Remarks</label>
            <textarea wire:model="notes" rows="3" placeholder="e.g. Picked up 50 bundles of Korean Cut from Mang Juan..." class="w-full border-gray-300 rounded-lg shadow-sm p-2 border"></textarea>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition">
            Save Procurement & Replenish Stock
        </button>
    </form>
</div>