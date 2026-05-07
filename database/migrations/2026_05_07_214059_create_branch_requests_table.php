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
       Schema::create('branch_requests', function (Blueprint $table) {
        $table->id();
        $table->foreignId('branch_id')->constrained('branches');
        $table->foreignId('branch_manager_id')->constrained('users');
        $table->enum('status', ['pending', 'dispatched'])->default('pending');
        $table->foreignId('dispatched_by')->nullable()->constrained('users');
        $table->timestamp('dispatched_at')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_requests');
    }
};
