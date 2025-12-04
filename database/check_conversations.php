<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\ChatConversation;
use App\Models\ChatMessage;

echo "=== Checking Conversations ===\n\n";

$conversations = ChatConversation::withCount('messages')->get();

foreach ($conversations as $conv) {
    echo "Conv ID: {$conv->id}\n";
    echo "  User ID: {$conv->user_id}\n";
    echo "  Status: {$conv->status}\n";
    echo "  Source: {$conv->chat_source}\n";
    echo "  Messages: {$conv->messages_count}\n";
    echo "\n";
}

echo "=== All Messages for User 218 ===\n\n";

$userConvIds = ChatConversation::where('user_id', 218)->pluck('id');
echo "Conversation IDs: " . $userConvIds->implode(', ') . "\n\n";

$messages = ChatMessage::whereIn('conversation_id', $userConvIds)
    ->orderBy('created_at', 'asc')
    ->get();

echo "Total messages: " . $messages->count() . "\n\n";

foreach ($messages as $msg) {
    echo "Msg ID: {$msg->id} | Conv: {$msg->conversation_id} | Type: {$msg->sender_type}\n";
    echo "  Message: " . substr($msg->message, 0, 80) . "...\n";
    echo "  Time: {$msg->created_at}\n\n";
}
