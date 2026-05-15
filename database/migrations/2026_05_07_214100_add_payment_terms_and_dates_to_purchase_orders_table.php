<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('purchase_orders', 'payment_terms')) {
                $table->enum('payment_terms', ['pre', 'post'])->default('post')->after('procurement_manager_id');
            }
            if (!Schema::hasColumn('purchase_orders', 'issue_date')) {
                $table->date('issue_date')->nullable()->after('payment_terms');
            }
            if (!Schema::hasColumn('purchase_orders', 'due_date')) {
                $table->date('due_date')->nullable()->after('issue_date');
            }
        });

        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE purchase_orders MODIFY COLUMN status ENUM('pending','approved','received','canceled') NOT NULL DEFAULT 'pending'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn(['payment_terms', 'issue_date', 'due_date']);
        });

        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE purchase_orders MODIFY COLUMN status ENUM('pending','approved','received') NOT NULL DEFAULT 'pending'");
        }
    }
};
