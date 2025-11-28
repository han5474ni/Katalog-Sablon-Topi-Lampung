<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
    // Add columns ke chat_conversations
        Schema::table('chat_conversations', function (Blueprint $table) {
            $table->json('keywords')->nullable()->comment('Selected category/keywords by user');
            $table->foreignId('subcategory_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('chat_source', ['product_detail', 'catalog', 'all_products', 'help_page'])->default('help_page');
            $table->boolean('is_admin_active')->default(false)->comment('Toggle bot auto-response vs manual admin reply');
            $table->timestamp('expires_at')->nullable()->index()->comment('Auto-delete after 3 days');
            $table->foreignId('admin_id')->nullable()->constrained('admins')->onDelete('set null');
        });
    
    // Add columns ke chat_messages
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->boolean('is_admin_reply')->default(false);
            $table->boolean('is_read_by_user')->default(false);
            $table->boolean('is_read_by_admin')->default(false);
        });
    }

    public function down()
    {
    // Schema::table('chat_conversations', function (Blueprint $table) { ... dropColumn ... });
    // Schema::table('chat_messages', function (Blueprint $table) { ... dropColumn ... });
    }
};
