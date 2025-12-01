<?php

namespace App\Listeners;

use App\Events\OrderCreatedEvent;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendOrderCreatedNotification implements ShouldQueue
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
    public function handle(OrderCreatedEvent $event): void
    {
        $order = $event->order;

        // Prepare customer notification data
        $customerData = [
            'customer_name' => $order->user->name,
            'order_number' => $order->order_number,
            'order_date' => $order->created_at->format('d M Y H:i'),
            'total_amount' => 'Rp ' . number_format($order->total, 0, ',', '.'),
            'action_url' => route('order-detail', ['type' => 'regular', 'id' => $order->id]),
        ];

        // Send to customer
        $this->notificationService->send(
            'order_created',
            $order->user,
            $customerData,
            'medium',
            true
        );

        // Prepare admin notification data
        $adminData = [
            'order_number' => $order->order_number,
            'customer_name' => $order->user->name,
            'customer_email' => $order->user->email,
            'customer_phone' => $order->user->phone ?? '-',
            'total_amount' => 'Rp ' . number_format($order->total, 0, ',', '.'),
            'order_date' => $order->created_at->format('d M Y H:i'),
            'order_items' => $this->formatOrderItems($order),
            'customer_notes' => $order->customer_notes ?? '-',
            'action_url' => route('admin.order.detail', ['id' => $order->id]),
        ];

        // Send to admins
        $admins = \App\Models\Admin::where('status', 'active')->get();
        $this->notificationService->sendToMany(
            'new_order_admin',
            $admins,
            $adminData,
            'high',
            true
        );
    }
    
    /**
     * Format order items for email display
     */
    private function formatOrderItems($order): string
    {
        if (empty($order->items)) {
            return '<p>-</p>';
        }
        
        $html = '<ul style="margin: 0; padding-left: 20px;">';
        foreach ($order->items as $item) {
            $html .= '<li>';
            $html .= e($item['product_name'] ?? 'Produk');
            if (!empty($item['variant_name'])) {
                $html .= ' - ' . e($item['variant_name']);
            }
            $html .= ' (' . ($item['quantity'] ?? 1) . ' pcs)';
            $html .= '</li>';
        }
        $html .= '</ul>';
        
        return $html;
    }
}
