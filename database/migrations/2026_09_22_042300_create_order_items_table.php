<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Links to the master order
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Links to the newspaper variety/style
            $table->integer('quantity'); // Number of bundles or packs ordered
            $table->decimal('subtotal', 10, 2); // Calculated subtotal price
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};