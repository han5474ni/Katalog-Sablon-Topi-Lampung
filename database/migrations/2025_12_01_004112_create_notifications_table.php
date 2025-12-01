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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // order_approved, order_rejected, order_status_update, new_order, va_activated, chat_reply
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('notifiable_type'); // App\Models\Order, App\Models\CustomDesignOrder, etc
            $table->unsignedBigInteger('notifiable_id');
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Additional data (order_id, status, etc)
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'is_read']);
            $table->index(['notifiable_type', 'notifiable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
