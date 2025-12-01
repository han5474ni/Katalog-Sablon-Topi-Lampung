<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Get all notifications for authenticated user
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $notifications = $this->notificationService->getUserNotifications($userId);
        $unreadCount = $this->notificationService->getUnreadCount($userId);

        return response()->json([
            'success' => true,
            'data' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Get unread count
     */
    public function unreadCount()
    {
        $userId = Auth::id();
        $count = $this->notificationService->getUnreadCount($userId);

        return response()->json([
            'success' => true,
            'count' => $count,
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $notification = $this->notificationService->markAsRead($id);

        return response()->json([
            'success' => true,
            'data' => $notification,
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $userId = Auth::id();
        $count = $this->notificationService->markAllAsRead($userId);

        return response()->json([
            'success' => true,
            'message' => "Semua notifikasi telah ditandai sebagai dibaca",
            'count' => $count,
        ]);
    }

    /**
     * Mark selected notifications as read
     */
    public function markSelectedAsRead(Request $request)
    {
        $request->validate([
            'notification_ids' => 'required|array',
            'notification_ids.*' => 'integer|exists:notifications,id',
        ]);

        $userId = Auth::id();
        $count = $this->notificationService->markSelectedAsRead(
            $request->notification_ids,
            $userId
        );

        return response()->json([
            'success' => true,
            'message' => "{$count} notifikasi telah ditandai sebagai dibaca",
            'count' => $count,
        ]);
    }
}
