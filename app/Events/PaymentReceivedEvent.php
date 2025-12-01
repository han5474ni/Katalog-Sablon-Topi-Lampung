<?php

namespace App\Events;

use App\Models\PaymentTransaction;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentReceivedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public PaymentTransaction $paymentTransaction;

    /**
     * Create a new event instance.
     */
    public function __construct(PaymentTransaction $paymentTransaction)
    {
        $this->paymentTransaction = $paymentTransaction;
    }
}
