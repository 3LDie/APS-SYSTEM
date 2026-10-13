<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Links to the order being delivered
            $table->string('delivered_by'); // Staff member or driver name
            $table->timestamp('delivery_timestamp')->nullable(); // Exact time of drop-off at the post
            $table->string('proof_of_delivery_path')->nullable(); // Stores image path of signed receipt or digital signature
            $table->text('notes')->nullable(); // Any delivery remarks
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};