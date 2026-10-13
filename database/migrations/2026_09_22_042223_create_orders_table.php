<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade'); // Links to the farmer
            $table->date('order_date');
            $table->date('delivery_date');
            $table->string('status')->default('Pending'); // Pending, Picked Up, Delivered, Cancelled
            $table->string('payment_status')->default('Unpaid'); // Unpaid, Partial, Paid
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};