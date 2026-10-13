<div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Newspaper Distribution: Record Farmer Order</h2>

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

    <form wire:submit.prevent="submitOrder">
        <!-- Customer Selection -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Farmer / Customer</label>
            <select wire:model="customer_id" class="w-full border-gray-300 rounded-lg shadow-sm p-2 border">
                <option value="">Select Farmer Post...</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->delivery_post_location }})</option>
                @endforeach
            </select>
            @error('customer_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Delivery Date -->
        <div class="mb-6">
            <label class="block text-gray-700 font-semibold mb-2">Target Delivery Date</label>
            <input type="date" wire:model="delivery_date" class="w-full border-gray-300 rounded-lg shadow-sm p-2 border">
            @error('delivery_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Dynamic Order Items -->
        <div class="mb-6">
            <label class="block text-gray-700 font-semibold mb-2">Newspaper Varieties & Quantities</label>
            
            @foreach($items as $index => $item)
                <div class="flex items-center space-x-4 mb-3">
                    <select wire:model="items.{{ $index }}.product_id" class="w-2/3 border-gray-300 rounded-lg shadow-sm p-2 border">
                        <option value="">Select Newspaper Variety & Style...</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">
                                {{ $product->variety_name }} [{{ $product->style_type }}] - Stock: {{ $product->stock_quantity }} (₱{{ $product->unit_price }})
                            </option>
                        @endforeach
                    </select>

                    <input type="number" wire:model="items.{{ $index }}.quantity" min="1" placeholder="Qty" class="w-1/6 border-gray-300 rounded-lg shadow-sm p-2 border">

                    @if(count($items) > 1)
                        <button type="button" wire:click="removeItemRow({{ $index }})" class="bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-600">X</button>
                    @endif
                </div>
            @endforeach

            <button type="button" wire:click="addItemRow" class="mt-2 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 font-semibold">+ Add Another Variety</button>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition">
            Submit Order & Deduct Inventory
        </button>
    </form>
</div>