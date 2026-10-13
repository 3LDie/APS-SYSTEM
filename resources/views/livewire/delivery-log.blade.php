<div class="max-w-3xl mx-auto p-6 bg-white shadow-md rounded-lg">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Log Digital Proof of Delivery</h2>
        <a href="/orders-ui" class="text-gray-600 hover:text-gray-900 font-semibold">&larr; Back to Orders</a>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="submitDelivery" enctype="multipart/form-data">
        <!-- Select Order -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Select Pending Order</label>
            <select wire:model="order_id" class="w-full border-gray-300 rounded-lg shadow-sm p-2 border">
                <option value="">Choose Order to Deliver...</option>
                @foreach($orders as $order)
                    <option value="{{ $order->id }}">
                        Order #{{ $order->id }} - {{ $order->customer->name }} ({{ $order->customer->delivery_post_location }})
                    </option>
                @endforeach
            </select>
            @error('order_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Delivered By -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Delivered By (Driver / Staff Name)</label>
            <input type="text" wire:model="delivered_by" placeholder="e.g. Kuya Ben / Juan" class="w-full border-gray-300 rounded-lg shadow-sm p-2 border">
            @error('delivered_by') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Proof of Delivery Image / Signature Photo -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Proof of Delivery (Photo of Signed Notebook / Receipt)</label>
            <input type="file" wire:model="proof_photo" accept="image/*" class="w-full border-gray-300 rounded-lg shadow-sm p-2 border">
            @error('proof_photo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Notes -->
        <div class="mb-6">
            <label class="block text-gray-700 font-semibold mb-2">Delivery Remarks / Post Notes</label>
            <textarea wire:model="notes" rows="3" placeholder="e.g. Left at Shed 3 near vegetable crate..." class="w-full border-gray-300 rounded-lg shadow-sm p-2 border"></textarea>
        </div>

        <button type="submit" class="w-full bg-green-600 text-white font-bold py-3 rounded-lg hover:bg-green-700 transition">
            Save Delivery Log & Mark as Delivered
        </button>
    </form>
</div>