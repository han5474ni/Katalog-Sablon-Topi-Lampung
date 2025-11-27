<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Only modify if columns don't already exist - migration already created in create_chat_tables
    }

    public function down()
    {
    // Schema::table('chat_conversations', function (Blueprint $table) { ... dropColumn ... });
    // Schema::table('chat_messages', function (Blueprint $table) { ... dropColumn ... });
    }
};
