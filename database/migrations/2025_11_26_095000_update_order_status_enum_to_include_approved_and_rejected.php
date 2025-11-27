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
        // Update orders table status enum to include 'approved' and 'rejected'
        Schema::table('orders', function (Blueprint $table) {
            DB::statement("ALTER TABLE orders MODIFY status ENUM('pending', 'approved', 'rejected', 'processing', 'completed', 'cancelled') DEFAULT 'pending'");
        });

        // Update custom_design_orders table status enum to include 'approved' and 'rejected'
        Schema::table('custom_design_orders', function (Blueprint $table) {
            DB::statement("ALTER TABLE custom_design_orders MODIFY status ENUM('pending', 'approved', 'rejected', 'processing', 'completed', 'cancelled') DEFAULT 'pending'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum
        Schema::table('orders', function (Blueprint $table) {
            DB::statement("ALTER TABLE orders MODIFY status ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending'");
        });

        Schema::table('custom_design_orders', function (Blueprint $table) {
            DB::statement("ALTER TABLE custom_design_orders MODIFY status ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending'");
        });
    }
};
