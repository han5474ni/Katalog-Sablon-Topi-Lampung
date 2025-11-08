<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('va_number')->nullable()->after('payment_deadline');
            $table->timestamp('va_generated_at')->nullable()->after('va_number');
        });

        Schema::table('custom_design_orders', function (Blueprint $table) {
            $table->string('va_number')->nullable()->after('payment_deadline');
            $table->timestamp('va_generated_at')->nullable()->after('va_number');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['va_number', 'va_generated_at']);
        });

        Schema::table('custom_design_orders', function (Blueprint $table) {
            $table->dropColumn(['va_number', 'va_generated_at']);
        });
    }
};