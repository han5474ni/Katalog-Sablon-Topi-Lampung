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
        Schema::table('chat_conversations', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable()->after('user_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            $table->index(['user_id', 'product_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_conversations', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropIndex(['user_id', 'product_id', 'status']);
            $table->dropColumn('product_id');
        });
    }
};
