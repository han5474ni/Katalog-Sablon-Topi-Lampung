<?php

namespace App\Listeners;

use App\Events\PaymentReceivedEvent;
use App\Models\Order;
use App\Models\CustomDesignOrder;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPaymentReceivedNotification implements ShouldQueue
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
    public function handle(PaymentReceivedEvent $event): void
    {
        $transaction = $event->paymentTransaction;
        
        // Get order number based on order type
        $orderNumber = $this->getOrderNumber($transaction);
        
        $data = [
            'customer_name' => $transaction->user->name,
            'order_number' => $orderNumber,
            'transaction_number' => $transaction->transaction_id,
            'payment_method' => $this->formatPaymentMethod($transaction),
            'amount' => 'Rp ' . number_format($transaction->amount, 0, ',', '.'),
            'payment_date' => $transaction->paid_at ? $transaction->paid_at->format('d M Y H:i') : now()->format('d M Y H:i'),
            'action_url' => $this->getOrderUrl($transaction),
        ];

        $this->notificationService->send(
            'payment_received',
            $transaction->user,
            $data,
            'medium',
            true
        );
    }
    
    /**
     * Get order number from transaction
     */
    private function getOrderNumber($transaction): string
    {
        if ($transaction->order_type === 'order' && $transaction->order_id) {
            $order = Order::find($transaction->order_id);
            return $order?->order_number ?? 'N/A';
        }
        
        if ($transaction->order_type === 'custom_design' && $transaction->order_id) {
            $customOrder = CustomDesignOrder::find($transaction->order_id);
            return $customOrder ? "CUSTOM-{$customOrder->id}" : 'N/A';
        }
        
        return $transaction->transaction_id;
    }
    
    /**
     * Format payment method for display
     */
    private function formatPaymentMethod($transaction): string
    {
        $method = $transaction->payment_method ?? 'Transfer Bank';
        $channel = $transaction->payment_channel ?? '';
        
        if ($channel) {
            return "{$method} - {$channel}";
        }
        
        return $method;
    }
    
    /**
     * Get order detail URL
     */
    private function getOrderUrl($transaction): string
    {
        if ($transaction->order_type === 'order' && $transaction->order_id) {
            return route('order-detail', ['type' => 'regular', 'id' => $transaction->order_id]);
        }
        
        if ($transaction->order_type === 'custom_design' && $transaction->order_id) {
            return route('order-detail', ['type' => 'custom', 'id' => $transaction->order_id]);
        }
        
        return route('order-list');
    }
}
