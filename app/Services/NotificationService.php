<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Admin;

class NotificationService
{
    /**
     * Create a new notification
     */
    public function create(array $data)
    {
        return Notification::create($data);
    }

    /**
     * Notify when order is approved
     */
    public function notifyOrderApproved($order, $userId)
    {
        $orderType = get_class($order) === 'App\Models\CustomDesignOrder' ? 'Custom Design' : 'Regular';
        
        return $this->create([
            'type' => 'order_approved',
            'user_id' => $userId,
            'notifiable_type' => get_class($order),
            'notifiable_id' => $order->id,
            'title' => 'Pesanan Disetujui',
            'message' => "Pesanan {$orderType} #{$order->id} Anda telah disetujui! Pesanan sedang diproses.",
            'data' => [
                'order_id' => $order->id,
                'order_type' => $orderType,
                'status' => 'approved',
            ],
        ]);
    }

    /**
     * Notify when order is rejected
     */
    public function notifyOrderRejected($order, $userId, $reason = null)
    {
        $orderType = get_class($order) === 'App\Models\CustomDesignOrder' ? 'Custom Design' : 'Regular';
        $message = "Pesanan {$orderType} #{$order->id} Anda ditolak.";
        if ($reason) {
            $message .= " Alasan: {$reason}";
        }
        
        return $this->create([
            'type' => 'order_rejected',
            'user_id' => $userId,
            'notifiable_type' => get_class($order),
            'notifiable_id' => $order->id,
            'title' => 'Pesanan Ditolak',
            'message' => $message,
            'data' => [
                'order_id' => $order->id,
                'order_type' => $orderType,
                'status' => 'rejected',
                'reason' => $reason,
            ],
        ]);
    }

    /**
     * Notify when order status is updated
     */
    public function notifyOrderStatusUpdate($order, $userId, $oldStatus, $newStatus)
    {
        $orderType = get_class($order) === 'App\Models\CustomDesignOrder' ? 'Custom Design' : 'Regular';
        $statusLabels = [
            'pending' => 'Menunggu Konfirmasi',
            'approved' => 'Disetujui',
            'processing' => 'Sedang Diproses',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'rejected' => 'Ditolak',
        ];
        
        return $this->create([
            'type' => 'order_status_update',
            'user_id' => $userId,
            'notifiable_type' => get_class($order),
            'notifiable_id' => $order->id,
            'title' => 'Status Pesanan Diperbarui',
            'message' => "Pesanan {$orderType} #{$order->id} diperbarui dari {$statusLabels[$oldStatus]} menjadi {$statusLabels[$newStatus]}.",
            'data' => [
                'order_id' => $order->id,
                'order_type' => $orderType,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ],
        ]);
    }

    /**
     * Notify admin when new order is created
     */
    public function notifyAdminNewOrder($order, $customer)
    {
        $orderType = get_class($order) === 'App\Models\CustomDesignOrder' ? 'Custom Design' : 'Regular';
        $admins = Admin::where('status', 'active')->get();
        
        foreach ($admins as $admin) {
            $this->create([
                'type' => 'new_order',
                'user_id' => $admin->id,
                'notifiable_type' => get_class($order),
                'notifiable_id' => $order->id,
                'title' => 'Pesanan Baru',
                'message' => "Pesanan {$orderType} baru #{$order->id} dari {$customer->name}.",
                'data' => [
                    'order_id' => $order->id,
                    'order_type' => $orderType,
                    'customer_id' => $customer->id,
                    'customer_name' => $customer->name,
                ],
            ]);
        }
    }

    /**
     * Notify admin when VA is activated
     */
    public function notifyAdminVAActivated($order, $vaNumber)
    {
        $orderType = get_class($order) === 'App\Models\CustomDesignOrder' ? 'Custom Design' : 'Regular';
        $admins = Admin::where('status', 'active')->get();
        
        foreach ($admins as $admin) {
            $this->create([
                'type' => 'va_activated',
                'user_id' => $admin->id,
                'notifiable_type' => get_class($order),
                'notifiable_id' => $order->id,
                'title' => 'Virtual Account Aktif',
                'message' => "VA #{$vaNumber} untuk pesanan {$orderType} #{$order->id} telah diaktifkan.",
                'data' => [
                    'order_id' => $order->id,
                    'order_type' => $orderType,
                    'va_number' => $vaNumber,
                ],
            ]);
        }
    }

    /**
     * Notify customer when admin replies to chat
     */
    public function notifyCustomerChatReply($conversationId, $customerId, $adminName)
    {
        return $this->create([
            'type' => 'chat_reply',
            'user_id' => $customerId,
            'notifiable_type' => 'App\Models\ChatConversation',
            'notifiable_id' => $conversationId,
            'title' => 'Balasan Baru',
            'message' => "{$adminName} membalas chat Anda.",
            'data' => [
                'conversation_id' => $conversationId,
                'admin_name' => $adminName,
            ],
        ]);
    }

    /**
     * Get unread count for user
     */
    public function getUnreadCount($userId)
    {
        return Notification::forUser($userId)->unread()->count();
    }

    /**
     * Get notifications for user
     */
    public function getUserNotifications($userId, $limit = 20)
    {
        return Notification::forUser($userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);
        if ($notification) {
            $notification->markAsRead();
        }
        return $notification;
    }

    /**
     * Mark all notifications as read for user
     */
    public function markAllAsRead($userId)
    {
        return Notification::forUser($userId)
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    /**
     * Mark selected notifications as read
     */
    public function markSelectedAsRead(array $notificationIds, $userId)
    {
        return Notification::whereIn('id', $notificationIds)
            ->forUser($userId)
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }
}
