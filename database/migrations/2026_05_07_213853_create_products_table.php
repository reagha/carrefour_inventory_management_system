<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('sku')->unique();
        $table->string('name');
        $table->foreignId('supplier_id')->constrained('suppliers')->restrictOnDelete();
        $table->bigInteger('unit_cost'); // Money in UGX
        $table->integer('quantity_in_warehouse')->default(0);
        $table->integer('reorder_level')->default(10);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
