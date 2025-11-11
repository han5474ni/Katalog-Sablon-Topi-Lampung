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
        // For SQLite, use Schema::table with change() method
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('payment_status', ['unpaid', 'va_active', 'paid', 'failed', 'refunded'])->default('unpaid')->change();
        });

        // Check if custom_design_orders has payment_status column
        if (!Schema::hasColumn('custom_design_orders', 'payment_status')) {
            Schema::table('custom_design_orders', function (Blueprint $table) {
                $table->enum('payment_status', ['unpaid', 'va_active', 'paid', 'failed', 'refunded'])->default('unpaid')->after('status');
            });
        } else {
            Schema::table('custom_design_orders', function (Blueprint $table) {
                $table->enum('payment_status', ['unpaid', 'va_active', 'paid', 'failed', 'refunded'])->default('unpaid')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('payment_status', ['unpaid', 'paid', 'failed', 'refunded'])->default('unpaid')->change();
        });

        // Revert custom_design_orders table
        Schema::table('custom_design_orders', function (Blueprint $table) {
            $table->enum('payment_status', ['unpaid', 'paid', 'failed', 'refunded'])->default('unpaid')->change();
        });
    }
};
