<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('variety_name'); // e.g., Korean, Japan, Textbook, White Textbook
            $table->string('style_type');   // e.g., Cut-styled, Standard-styled
            $table->integer('stock_quantity')->default(0); // Tracks current bundles/reams
            $table->decimal('unit_price', 10, 2); // Selling price per unit
            $table->integer('reorder_level')->default(10); // Low stock alert threshold
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};