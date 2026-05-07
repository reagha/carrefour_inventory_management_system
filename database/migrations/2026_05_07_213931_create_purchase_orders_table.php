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
         Schema::create('purchase_orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('supplier_id')->constrained('suppliers');
        $table->foreignId('procurement_manager_id')->constrained('users');
        $table->bigInteger('total_amount'); // UGX
        $table->enum('status',['pending', 'approved', 'received'])->default('pending');
        $table->foreignId('received_by')->nullable()->constrained('users');
        $table->timestamp('received_at')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
