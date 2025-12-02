<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Services\ChatBotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatConversationController extends Controller
{
    protected $chatBotService;

    public function __construct(ChatBotService $chatBotService)
    {
        $this->chatBotService = $chatBotService;
    }

    /**
     * Get or create a conversation for the authenticated user
     */
    public function getOrCreateConversation(Request $request)
    {
        $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'chat_source' => 'nullable|string'
        ]);

        $userId = Auth::id();
        $productId = $request->input('product_id');
        $chatSource = $request->input('chat_source', 'help_page');

        // Find existing open conversation or create new one
        $conversation = ChatConversation::where('user_id', $userId)
            ->when($productId, function ($query) use ($productId) {
                return $query->where('product_id', $productId);
            })
            ->where('status', 'open')
            ->first();

        if (!$conversation) {
            $conversation = ChatConversation::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'status' => 'open',
                'chat_source' => $chatSource,
                'subject' => $productId ? 'Product Inquiry' : 'General Inquiry',
                'expires_at' => now()->addDays(30)
            ]);
        }

        return response()->json([
            'success' => true,
            'conversation' => $conversation->load('product')
        ]);
    }

    /**
     * Send a message in a conversation
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:chat_conversations,id',
            'message' => 'required|string|max:1000'
        ]);

        $conversationId = $request->input('conversation_id');
        $messageText = $request->input('message');

        // Verify user owns the conversation
        $conversation = ChatConversation::where('id', $conversationId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$conversation) {
            return response()->json([
                'success' => false,
                'error' => 'Conversation not found'
            ], 404);
        }

        // Save user message
        $userMessage = ChatMessage::create([
            'conversation_id' => $conversationId,
            'user_id' => Auth::id(),
            'sender_type' => 'user',
            'message' => $messageText,
            'is_read_by_admin' => false
        ]);

        // Get bot response using ChatBotService
        try {
            $productData = null;
            if ($conversation->product) {
                $productData = [
                    'id' => $conversation->product->id,
                    'name' => $conversation->product->name,
                    'price' => $conversation->product->price
                ];
            }

            $botResponse = $this->chatBotService->processMessage(
                $conversationId,
                $messageText,
                $productData
            );

            return response()->json([
                'success' => true,
                'user_message' => $userMessage,
                'bot_message' => $botResponse
            ]);

        } catch (\Exception $e) {
            Log::error('Chat error: ' . $e->getMessage());
            
            return response()->json([
                'success' => true,
                'user_message' => $userMessage,
                'bot_message' => null,
                'error' => 'Bot response failed'
            ]);
        }
    }

    /**
     * Get chat history for a conversation
     */
    public function getChatHistory(Request $request, $conversationId)
    {
        $conversation = ChatConversation::where('id', $conversationId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$conversation) {
            return response()->json([
                'success' => false,
                'error' => 'Conversation not found'
            ], 404);
        }

        $messages = ChatMessage::where('conversation_id', $conversationId)
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read by user
        ChatMessage::where('conversation_id', $conversationId)
            ->where('sender_type', '!=', 'user')
            ->update(['is_read_by_user' => true]);

        return response()->json([
            'success' => true,
            'conversation' => $conversation,
            'messages' => $messages
        ]);
    }

    /**
     * Mark a message as read
     */
    public function markMessageAsRead(Request $request, $messageId)
    {
        $message = ChatMessage::findOrFail($messageId);
        
        // Verify user owns the conversation
        $conversation = ChatConversation::where('id', $message->conversation_id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$conversation) {
            return response()->json([
                'success' => false,
                'error' => 'Message not found'
            ], 404);
        }

        $message->update(['is_read_by_user' => true]);

        return response()->json([
            'success' => true
        ]);
    }
}
