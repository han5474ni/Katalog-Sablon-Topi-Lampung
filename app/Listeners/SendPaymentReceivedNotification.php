<?php

namespace App\Listeners;

use App\Events\PaymentReceivedEvent;
use App\Models\Order;
use App\Models\CustomDesignOrder;
use App\Services\NotificationService;

class SendPaymentReceivedNotification
{
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
        
        $orderNumber = $this->getOrderNumber($transaction);
        $actionUrl = $this->getOrderUrl($transaction);

        $this->notificationService->send(
            'payment_received',
            $transaction->user,
            [
                'order_number' => $orderNumber,
                'customer_name' => $transaction->user->name,
                'transaction_number' => $transaction->transaction_id,
                'amount' => 'Rp ' . number_format($transaction->amount, 0, ',', '.'),
                'action_url' => $actionUrl,
            ],
            'medium',
            true // send email
        );
    }
    
    private function getOrderNumber($transaction): string
    {
        if ($transaction->order_type === 'order' && $transaction->order_id) {
            $order = Order::find($transaction->order_id);
            return $order?->order_number ?? 'N/A';
        }
        
        if ($transaction->order_type === 'custom_design' && $transaction->order_id) {
            return "CUSTOM-{$transaction->order_id}";
        }
        
        return $transaction->transaction_id ?? 'N/A';
    }
    
    private function getOrderUrl($transaction): string
    {
        if ($transaction->order_type === 'order' && $transaction->order_id) {
            return route('order-detail', ['type' => 'regular', 'id' => $transaction->order_id]);
        }
        
        if ($transaction->order_type === 'custom_design' && $transaction->order_id) {
            return route('order-detail', ['type' => 'custom', 'id' => $transaction->order_id]);
        }
        
        return route('order-history');
    }
}
