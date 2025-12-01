<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\NotificationLog;
use App\Models\NotificationTemplate;
use App\Models\User;
use App\Models\Admin;
use App\Jobs\SendEmailNotificationJob;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send notification to a single recipient
     * Used by Event Listeners
     * 
     * @param string $type - notification template type
     * @param User|Admin $recipient - the recipient model
     * @param array $data - data to replace in templates
     * @param string $priority - low, medium, high, urgent
     * @param bool $sendEmail - whether to send email notification
     */
    public function send(string $type, $recipient, array $data = [], string $priority = 'medium', bool $sendEmail = true): ?Notification
    {
        try {
            // Get template for in-app notification
            $inAppTemplate = NotificationTemplate::active()
                ->ofType($type)
                ->channel('in_app')
                ->first();

            // Get template for email
            $emailTemplate = NotificationTemplate::active()
                ->ofType($type)
                ->channel('email')
                ->first();

            // Determine notifiable type
            $notifiableType = get_class($recipient);
            
            // Replace variables in template
            $title = $inAppTemplate ? $this->replacePlaceholders($inAppTemplate->title_template ?? $inAppTemplate->name, $data) : ($data['title'] ?? 'Notifikasi');
            $message = $inAppTemplate ? $this->replacePlaceholders($inAppTemplate->message_template ?? '', $data) : ($data['message'] ?? '');
            $actionUrl = $data['action_url'] ?? null;
            $actionText = $inAppTemplate->action_text ?? 'Lihat Detail';

            // Create notification in database
            $notification = Notification::create([
                'type' => $type,
                'notifiable_type' => $notifiableType,
                'notifiable_id' => $recipient->id,
                'title' => $title,
                'message' => $message,
                'data' => $data,
                'action_url' => $actionUrl,
                'action_text' => $actionText,
                'priority' => $priority,
            ]);

            // Send email if enabled and template exists
            if ($sendEmail && $emailTemplate && $recipient->email) {
                $this->queueEmail($notification, $emailTemplate, $recipient, $data);
            }

            return $notification;

        } catch (\Exception $e) {
            Log::error('Failed to send notification', [
                'type' => $type,
                'recipient_id' => $recipient->id ?? null,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Send notification to multiple recipients
     * Used for sending to all admins
     */
    public function sendToMany(string $type, $recipients, array $data = [], string $priority = 'medium', bool $sendEmail = true): array
    {
        $notifications = [];
        
        foreach ($recipients as $recipient) {
            $notification = $this->send($type, $recipient, $data, $priority, $sendEmail);
            if ($notification) {
                $notifications[] = $notification;
            }
        }

        return $notifications;
    }

    /**
     * Queue email notification
     */
    protected function queueEmail(Notification $notification, NotificationTemplate $template, $recipient, array $data): void
    {
        try {
            $subject = $this->replacePlaceholders($template->subject ?? $template->name, $data);
            
            // Create notification log
            $log = NotificationLog::create([
                'notification_id' => $notification->id,
                'channel' => 'email',
                'recipient_type' => get_class($recipient),
                'recipient_id' => $recipient->id,
                'recipient_email' => $recipient->email,
                'subject' => $subject,
                'status' => 'pending',
            ]);

            // Dispatch email job
            SendEmailNotificationJob::dispatch($log->id, $data);

        } catch (\Exception $e) {
            Log::error('Failed to queue email notification', [
                'notification_id' => $notification->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Replace placeholders in template string
     */
    protected function replacePlaceholders(string $template, array $data): string
    {
        foreach ($data as $key => $value) {
            if (is_string($value) || is_numeric($value)) {
                $template = str_replace("{{$key}}", $value, $template);
                $template = str_replace("{{ $key }}", $value, $template);
            }
        }
        return $template;
    }

    /**
     * Create a new notification (legacy method)
     */
    public function create(array $data)
    {
        return Notification::create($data);
    }

    /**
     * Notify when order is approved (to User)
     */
    public function notifyOrderApproved($order, $userId)
    {
        $orderType = get_class($order) === 'App\Models\CustomDesignOrder' ? 'Custom Design' : 'Regular';
        
        return $this->create([
            'type' => 'order_approved',
            'notifiable_type' => 'App\\Models\\User',
            'notifiable_id' => $userId,
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
     * Notify when order is rejected (to User)
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
            'notifiable_type' => 'App\\Models\\User',
            'notifiable_id' => $userId,
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
     * Notify when order status is updated (to User)
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
            'notifiable_type' => 'App\\Models\\User',
            'notifiable_id' => $userId,
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
     * Notify admin when new order is created (to Admins)
     */
    public function notifyAdminNewOrder($order, $customer)
    {
        $orderType = get_class($order) === 'App\Models\CustomDesignOrder' ? 'Custom Design' : 'Regular';
        $admins = Admin::where('status', 'active')->get();
        
        foreach ($admins as $admin) {
            $this->create([
                'type' => 'new_order',
                'notifiable_type' => 'App\\Models\\Admin',
                'notifiable_id' => $admin->id,
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
     * Notify admin when VA is activated (to Admins)
     */
    public function notifyAdminVAActivated($order, $vaNumber)
    {
        $orderType = get_class($order) === 'App\Models\CustomDesignOrder' ? 'Custom Design' : 'Regular';
        $admins = Admin::where('status', 'active')->get();
        
        foreach ($admins as $admin) {
            $this->create([
                'type' => 'va_activated',
                'notifiable_type' => 'App\\Models\\Admin',
                'notifiable_id' => $admin->id,
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
     * Notify customer when admin replies to chat (to User)
     */
    public function notifyCustomerChatReply($conversationId, $customerId, $adminName)
    {
        return $this->create([
            'type' => 'chat_reply',
            'notifiable_type' => 'App\\Models\\User',
            'notifiable_id' => $customerId,
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
    public function getUnreadCount($userId, $type = 'user')
    {
        if ($type === 'admin') {
            return Notification::forAdmin($userId)->unread()->count();
        }
        return Notification::forUser($userId)->unread()->count();
    }

    /**
     * Get notifications query builder for user or admin
     * Used by controllers for pagination
     */
    public function getNotifications($recipient, $limit = null)
    {
        $notifiableType = get_class($recipient);
        
        $query = Notification::where('notifiable_type', $notifiableType)
            ->where('notifiable_id', $recipient->id)
            ->orderBy('created_at', 'desc');
            
        if ($limit) {
            $query->limit($limit);
        }
        
        return $query;
    }

    /**
     * Get notifications for user or admin (returns collection)
     */
    public function getUserNotifications($userId, $limit = 20, $type = 'user')
    {
        $query = $type === 'admin' 
            ? Notification::forAdmin($userId) 
            : Notification::forUser($userId);
            
        return $query->orderBy('created_at', 'desc')
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
     * Mark all notifications as read for user or admin
     */
    public function markAllAsRead($userId, $type = 'user')
    {
        $query = $type === 'admin' 
            ? Notification::forAdmin($userId) 
            : Notification::forUser($userId);
            
        return $query->unread()
            ->update([
                'read_at' => now(),
            ]);
    }

    /**
     * Mark selected notifications as read
     */
    public function markSelectedAsRead(array $notificationIds, $userId, $type = 'user')
    {
        $query = Notification::whereIn('id', $notificationIds);
        
        if ($type === 'admin') {
            $query->forAdmin($userId);
        } else {
            $query->forUser($userId);
        }
        
        return $query->unread()
            ->update([
                'read_at' => now(),
            ]);
    }
}
