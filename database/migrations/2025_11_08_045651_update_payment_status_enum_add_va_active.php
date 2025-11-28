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
        // Update orders table payment_status enum
        DB::statement("ALTER TABLE orders MODIFY payment_status ENUM('unpaid','va_active','paid','failed','refunded') NOT NULL DEFAULT 'unpaid'");
        
        // Check if custom_design_orders has payment_status column
        $columns = DB::select("SHOW COLUMNS FROM custom_design_orders WHERE Field = 'payment_status'");
        
        if (empty($columns)) {
            // Add payment_status column if it doesn't exist
            DB::statement("ALTER TABLE custom_design_orders ADD COLUMN payment_status ENUM('unpaid','va_active','paid','failed','refunded') NOT NULL DEFAULT 'unpaid' AFTER status");
        } else {
            // Update existing column
            DB::statement("ALTER TABLE custom_design_orders MODIFY payment_status ENUM('unpaid','va_active','paid','failed','refunded') NOT NULL DEFAULT 'unpaid'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert orders table
        DB::statement("ALTER TABLE orders MODIFY payment_status ENUM('unpaid','paid','failed','refunded') NOT NULL DEFAULT 'unpaid'");
        
        // Revert custom_design_orders table
        DB::statement("ALTER TABLE custom_design_orders MODIFY payment_status ENUM('unpaid','paid','failed','refunded') NOT NULL DEFAULT 'unpaid'");
    }
};
