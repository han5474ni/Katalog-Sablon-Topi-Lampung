<?php

namespace App\Listeners;

use App\Events\OrderCreatedEvent;
use App\Services\NotificationService;

class SendOrderCreatedNotification
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the event.
     */
    public function handle(OrderCreatedEvent $event): void
    {
        $order = $event->order;

        // Send notification to customer
        $this->notificationService->send(
            'order_created',
            $order->user,
            [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'customer_name' => $order->user->name,
                'total_amount' => 'Rp ' . number_format($order->total, 0, ',', '.'),
                'action_url' => route('order-detail', ['type' => 'regular', 'id' => $order->id]),
            ],
            'medium',
            false // no email for now
        );

        // Send notification to all active admins
        $admins = \App\Models\Admin::where('status', 'active')->get();
        $this->notificationService->sendToMany(
            'new_order_admin',
            $admins,
            [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'customer_name' => $order->user->name,
                'total_amount' => 'Rp ' . number_format($order->total, 0, ',', '.'),
                'action_url' => route('admin.order.detail', ['id' => $order->id, 'type' => 'regular']),
            ],
            'high',
            false // no email for now
        );
    }
}
