<?php

/**
 * Script untuk menggabungkan semua conversation duplikat per user
 * Semua pesan akan dipindahkan ke SATU conversation per user
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\DB;

echo "=== Merging Duplicate Conversations ===\n\n";

// Get all users with conversations
$usersWithConversations = ChatConversation::whereNotNull('user_id')
    ->select('user_id')
    ->distinct()
    ->pluck('user_id');

echo "Found " . count($usersWithConversations) . " users with conversations\n\n";

foreach ($usersWithConversations as $userId) {
    echo "Processing User ID: {$userId}\n";
    
    // Get all conversations for this user
    $conversations = ChatConversation::where('user_id', $userId)
        ->orderBy('created_at', 'asc')
        ->get();
    
    if ($conversations->count() <= 1) {
        echo "  - Only 1 conversation, skipping\n\n";
        continue;
    }
    
    echo "  - Found {$conversations->count()} conversations\n";
    
    // Use the FIRST conversation as the primary (oldest)
    $primaryConversation = $conversations->first();
    echo "  - Primary conversation ID: {$primaryConversation->id}\n";
    
    // Move all messages from other conversations to primary
    $otherConversationIds = $conversations->where('id', '!=', $primaryConversation->id)->pluck('id');
    
    $movedCount = ChatMessage::whereIn('conversation_id', $otherConversationIds)
        ->update([
            'conversation_id' => $primaryConversation->id,
            'chat_conversation_id' => $primaryConversation->id
        ]);
    
    echo "  - Moved {$movedCount} messages to primary conversation\n";
    
    // Delete the duplicate conversations
    $deletedCount = ChatConversation::whereIn('id', $otherConversationIds)->delete();
    echo "  - Deleted {$deletedCount} duplicate conversations\n";
    
    // Update primary conversation to be open and chatbot source
    $primaryConversation->update([
        'status' => 'open',
        'chat_source' => 'chatbot',
        'expires_at' => now()->addDays(30)
    ]);
    echo "  - Updated primary conversation to open status\n\n";
}

echo "=== Merge Complete ===\n\n";

// Show final state
echo "=== Final Conversation State ===\n\n";

$conversations = ChatConversation::withCount('messages')->get();

foreach ($conversations as $conv) {
    echo "Conv ID: {$conv->id}\n";
    echo "  User ID: {$conv->user_id}\n";
    echo "  Status: {$conv->status}\n";
    echo "  Source: {$conv->chat_source}\n";
    echo "  Messages: {$conv->messages_count}\n";
    echo "\n";
}
