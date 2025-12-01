<?php

namespace App\Listeners;

use App\Events\CustomDesignUploadedEvent;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendCustomDesignUploadedNotification implements ShouldQueue
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
    public function handle(CustomDesignUploadedEvent $event): void
    {
        $customDesign = $event->customDesignOrder;

        $data = [
            'customer_name' => $customDesign->user->name,
            'design_number' => "CUSTOM-{$customDesign->id}",
            'upload_date' => $customDesign->created_at->format('d M Y H:i'),
            'design_name' => $customDesign->product_name ?? 'Desain Custom',
            'design_notes' => $customDesign->additional_description ?? '-',
            'action_url' => route('order-detail', ['type' => 'custom', 'id' => $customDesign->id]),
        ];

        // Notify customer
        $this->notificationService->send(
            'custom_design_uploaded',
            $customDesign->user,
            $data,
            'low',
            true
        );

        // Notify admins to review custom design
        $adminData = [
            'customer_name' => $customDesign->user->name,
            'customer_email' => $customDesign->user->email,
            'design_number' => "CUSTOM-{$customDesign->id}",
            'upload_date' => $customDesign->created_at->format('d M Y H:i'),
            'product_name' => $customDesign->product_name ?? 'Desain Custom',
            'quantity' => $customDesign->quantity,
            'total_amount' => 'Rp ' . number_format($customDesign->total_price, 0, ',', '.'),
            'design_notes' => $customDesign->additional_description ?? '-',
            'action_url' => route('admin.order.detail', ['id' => $customDesign->id]) . '?type=custom',
        ];

        $admins = \App\Models\Admin::where('status', 'active')->get();
        $this->notificationService->sendToMany(
            'new_custom_design_admin',
            $admins,
            $adminData,
            'high',
            true
        );
    }
}
