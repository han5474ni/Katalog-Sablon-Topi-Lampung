<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\User;
use App\Services\ChatBotService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminChatController extends Controller
{
    private $chatBotService;

    public function __construct(ChatBotService $chatBotService)
    {
        $this->chatBotService = $chatBotService;
    }

    /**
     * Display chatbot management page
     * Shows all conversations that need admin attention
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all'); // all, escalated, needs_response, handled
        $search = $request->get('search', '');
        $page = $request->get('page', 1);
        $perPage = 20;

        $query = ChatConversation::with(['user', 'product', 'messages', 'admin'])
            ->orderBy('updated_at', 'desc');

        // Apply filters
        if ($filter === 'escalated') {
            $query->where('is_escalated', true);
        } elseif ($filter === 'needs_response') {
            $query->where('needs_admin_response', true);
        } elseif ($filter === 'handled') {
            $query->where('taken_over_by_admin', true);
        }

        // Apply search
        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhere('subject', 'like', "%{$search}%");
        }

        $conversations = $query->paginate($perPage);

        return view('admin.chatbot.index', compact('conversations', 'filter', 'search'));
    }

    /**
     * Get conversation detail - API endpoint
     */
    public function getConversation($conversationId)
    {
        $conversation = ChatConversation::with(['user', 'product', 'messages' => function($q) {
            $q->orderBy('created_at', 'asc');
        }, 'admin'])->findOrFail($conversationId);

        // Mark messages as read by admin
        $conversation->messages()
            ->where('sender_type', 'user')
            ->where('is_read_by_admin', false)
            ->update(['is_read_by_admin' => true]);

        return response()->json([
            'success' => true,
            'conversation' => $conversation
        ]);
    }

    /**
     * Ambil alih konversasi dari bot/auto-response
     */
    public function takeOverConversation(Request $request, $conversationId)
    {
        $conversation = ChatConversation::findOrFail($conversationId);
        $adminId = Auth::guard('admin')->id();

        try {
            $conversation->update([
                'taken_over_by_admin' => true,
                'taken_over_at' => now(),
                'admin_id' => $adminId,
                'is_admin_active' => true
            ]);

            // Log activity
            Log::info('Admin took over conversation', [
                'conversation_id' => $conversationId,
                'admin_id' => $adminId,
                'customer_id' => $conversation->user_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Anda telah mengambil alih konversasi',
                'conversation' => $conversation
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to take over conversation', [
                'conversation_id' => $conversationId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil alih konversasi'
            ], 500);
        }
    }

    /**
     * Kirim pesan dari admin ke customer
     */
    public function sendAdminMessage(Request $request, $conversationId)
    {
        $request->validate([
            'message' => 'required|string|max:2000'
        ]);

        $conversation = ChatConversation::findOrFail($conversationId);
        $adminId = Auth::guard('admin')->id();

        // Ensure admin has taken over or is the handler
        if ($conversation->admin_id !== $adminId && !$conversation->taken_over_by_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menjawab konversasi ini'
            ], 403);
        }

        try {
            // Create admin message
            $message = ChatMessage::create([
                'conversation_id' => $conversationId,
                'chat_conversation_id' => $conversationId,
                'sender_type' => 'admin',
                'message' => $request->message,
                'is_admin_reply' => true,
                'is_read_by_user' => false
            ]);

            // Update conversation status
            $conversation->update([
                'taken_over_by_admin' => true,
                'admin_id' => $adminId,
                'is_admin_active' => true,
                'needs_admin_response' => false,
                'needs_response_since' => null,
                'updated_at' => now()
            ]);

            // Log activity
            Log::info('Admin sent message', [
                'conversation_id' => $conversationId,
                'message_id' => $message->id,
                'admin_id' => $adminId,
                'customer_id' => $conversation->user_id
            ]);

            // Send notification to customer (popup badge only, not in notification page)
            $adminName = Auth::guard('admin')->user()->name ?? 'Admin';
            app(NotificationService::class)->notifyCustomerChatReply(
                $conversationId,
                $conversation->user_id,
                $adminName
            );

            return response()->json([
                'success' => true,
                'message' => 'Pesan terkirim',
                'data' => $message
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send admin message', [
                'conversation_id' => $conversationId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim pesan'
            ], 500);
        }
    }

    /**
     * Tandai bahwa customer perlu jawaban langsung dari admin
     * Trigger ini bisa dari customer atau dari system
     */
    public function markNeedsAdminResponse(Request $request, $conversationId)
    {
        $conversation = ChatConversation::findOrFail($conversationId);

        try {
            $conversation->update([
                'is_escalated' => true,
                'escalated_at' => now(),
                'needs_admin_response' => true,
                'needs_response_since' => now(),
                'escalation_reason' => $request->get('reason', 'Customer meminta jawaban langsung admin')
            ]);

            // Create system notification message
            ChatMessage::create([
                'conversation_id' => $conversationId,
                'sender_type' => 'system',
                'message' => '⚠️ Customer meminta jawaban langsung dari admin',
                'metadata' => [
                    'system_notification' => true,
                    'type' => 'escalation'
                ]
            ]);

            // TODO: Broadcast notification ke admin
            // TODO: Send notification email ke admin

            Log::info('Conversation marked as needing admin response', [
                'conversation_id' => $conversationId,
                'customer_id' => $conversation->user_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Admin telah diberitahu bahwa Anda memerlukan jawaban langsung'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to mark needs admin response', [
                'conversation_id' => $conversationId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim notifikasi ke admin'
            ], 500);
        }
    }

    /**
     * Escalate conversation ke admin dengan alasan
     */
    public function escalateConversation(Request $request, $conversationId)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        $conversation = ChatConversation::findOrFail($conversationId);

        try {
            $conversation->update([
                'is_escalated' => true,
                'escalated_at' => now(),
                'escalation_reason' => $request->reason
            ]);

            // Create system message
            ChatMessage::create([
                'conversation_id' => $conversationId,
                'sender_type' => 'system',
                'message' => "Konversasi di-escalate: {$request->reason}",
                'metadata' => [
                    'system_notification' => true,
                    'type' => 'escalation'
                ]
            ]);

            Log::info('Conversation escalated', [
                'conversation_id' => $conversationId,
                'reason' => $request->reason
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Konversasi berhasil di-escalate ke admin'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to escalate conversation', [
                'conversation_id' => $conversationId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan escalation'
            ], 500);
        }
    }

    /**
     * Close/resolve conversation
     */
    public function closeConversation(Request $request, $conversationId)
    {
        $request->validate([
            'resolution_notes' => 'nullable|string|max:500'
        ]);

        $conversation = ChatConversation::findOrFail($conversationId);

        try {
            $conversation->update([
                'status' => 'closed',
                'taken_over_by_admin' => false,
                'is_admin_active' => false
            ]);

            // Create system message
            if ($request->resolution_notes) {
                ChatMessage::create([
                    'conversation_id' => $conversationId,
                    'sender_type' => 'system',
                    'message' => "Chat ditutup. Catatan: {$request->resolution_notes}",
                    'metadata' => ['type' => 'closure']
                ]);
            }

            Log::info('Conversation closed', [
                'conversation_id' => $conversationId,
                'resolution_notes' => $request->resolution_notes
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Konversasi berhasil ditutup'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to close conversation', [
                'conversation_id' => $conversationId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menutup konversasi'
            ], 500);
        }
    }

    /**
     * Release conversation back to bot
     */
    public function releaseConversation($conversationId)
    {
        $conversation = ChatConversation::findOrFail($conversationId);

        try {
            $conversation->update([
                'taken_over_by_admin' => false,
                'is_admin_active' => false,
                'admin_id' => null,
                'taken_over_at' => null
            ]);

            ChatMessage::create([
                'conversation_id' => $conversationId,
                'sender_type' => 'system',
                'message' => 'Admin melepaskan konversasi kembali ke chatbot otomatis',
                'metadata' => ['type' => 'release']
            ]);

            Log::info('Conversation released back to bot', [
                'conversation_id' => $conversationId
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Konversasi dilepas kembali ke chatbot'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to release conversation', [
                'conversation_id' => $conversationId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal melepas konversasi'
            ], 500);
        }
    }

    /**
     * Get unread conversations count
     */
    public function getUnreadCount()
    {
        $unreadEscalated = ChatConversation::where('is_escalated', true)
            ->where('taken_over_by_admin', false)
            ->count();

        $unreadNeedsResponse = ChatConversation::where('needs_admin_response', true)
            ->where('taken_over_by_admin', false)
            ->count();

        $unreadMessages = ChatMessage::where('sender_type', 'user')
            ->where('is_read_by_admin', false)
            ->count();

        return response()->json([
            'escalated' => $unreadEscalated,
            'needs_response' => $unreadNeedsResponse,
            'unread_messages' => $unreadMessages,
            'total' => $unreadEscalated + $unreadNeedsResponse
        ]);
    }

    /**
     * Get conversations that need attention
     */
    public function getConversationsNeedingAttention()
    {
        $conversations = ChatConversation::where(function ($query) {
            $query->where('is_escalated', true)
                  ->orWhere('needs_admin_response', true);
        })
        ->where('taken_over_by_admin', false)
        ->with(['user', 'product', 'latestMessage'])
        ->orderBy('updated_at', 'desc')
        ->limit(10)
        ->get();

        return response()->json([
            'success' => true,
            'conversations' => $conversations,
            'count' => $conversations->count()
        ]);
    }

    /**
     * Mark conversation as read by admin
     */
    public function markConversationAsRead($conversationId)
    {
        $conversation = ChatConversation::findOrFail($conversationId);

        try {
            $conversation->messages()
                ->where('sender_type', 'user')
                ->where('is_read_by_admin', false)
                ->update(['is_read_by_admin' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Konversasi ditandai sudah dibaca'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menandai sebagai dibaca'
            ], 500);
        }
    }
}
