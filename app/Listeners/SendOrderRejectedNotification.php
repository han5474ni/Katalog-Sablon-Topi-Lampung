<?php

namespace App\Listeners;

use App\Events\OrderRejectedEvent;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendOrderRejectedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the event.
     */
    public function handle(OrderRejectedEvent $event): void
    {
        $order = $event->order;

        $data = [
            'customer_name' => $order->user->name,
            'order_number' => $order->order_number,
            'rejected_date' => $order->rejected_at ? $order->rejected_at->format('d M Y H:i') : now()->format('d M Y H:i'),
            'rejection_reason' => $event->reason ?? $order->admin_notes ?? 'Pesanan tidak dapat diproses.',
            'action_url' => route('order-detail', ['type' => 'regular', 'id' => $order->id]),
        ];

        $this->notificationService->send(
            'order_rejected',
            $order->user,
            $data,
            'high',
            true
        );
    }
}
