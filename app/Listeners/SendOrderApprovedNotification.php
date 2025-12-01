<?php

namespace App\Listeners;

use App\Events\OrderApprovedEvent;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendOrderApprovedNotification implements ShouldQueue
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
    public function handle(OrderApprovedEvent $event): void
    {
        $order = $event->order;

        $data = [
            'customer_name' => $order->user->name,
            'order_number' => $order->order_number,
            'approved_date' => $order->approved_at ? $order->approved_at->format('d M Y H:i') : now()->format('d M Y H:i'),
            'total_amount' => 'Rp ' . number_format($order->total, 0, ',', '.'),
            'notes' => $order->admin_notes ?? 'Pesanan Anda telah disetujui dan akan segera diproses.',
            'action_url' => route('order-detail', ['type' => 'regular', 'id' => $order->id]),
        ];

        $this->notificationService->send(
            'order_approved',
            $order->user,
            $data,
            'high',
            true
        );
    }
}
