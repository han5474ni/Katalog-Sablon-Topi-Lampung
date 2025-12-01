<?php

namespace App\Listeners;

use App\Events\OrderCompletedEvent;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendOrderCompletedNotification implements ShouldQueue
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
    public function handle(OrderCompletedEvent $event): void
    {
        $order = $event->order;

        $data = [
            'customer_name' => $order->user->name,
            'order_number' => $order->order_number,
            'completed_date' => $order->completed_at ? $order->completed_at->format('d M Y H:i') : now()->format('d M Y H:i'),
            'total_amount' => 'Rp ' . number_format($order->total, 0, ',', '.'),
            'delivery_info' => $this->getDeliveryInfo($order),
            'action_url' => route('order-detail', ['type' => 'regular', 'id' => $order->id]),
        ];

        $this->notificationService->send(
            'order_completed',
            $order->user,
            $data,
            'medium',
            true
        );
    }
    
    /**
     * Get delivery/pickup information
     */
    private function getDeliveryInfo($order): string
    {
        if (!empty($order->admin_notes)) {
            return $order->admin_notes;
        }
        
        if (!empty($order->shipping_service)) {
            return "Pesanan Anda telah dikirim melalui {$order->shipping_service}.";
        }
        
        return 'Pesanan Anda telah selesai dan siap diambil.';
    }
}
