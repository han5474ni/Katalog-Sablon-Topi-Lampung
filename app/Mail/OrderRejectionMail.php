<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderRejectionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $rejectionReason;
    
    public function __construct(Order $order, ?string $rejectionReason = null)
    {
        $this->order = $order;
        $this->rejectionReason = $rejectionReason;
    }

    public function build()
    {
        return $this->subject('Pesanan Anda Tidak Dapat Diproses')
            ->markdown('emails.orders.rejected', [
                'order' => $this->order,
                'rejectionReason' => $this->rejectionReason ?? 'Mohon maaf, pesanan Anda tidak dapat diproses saat ini.'
            ]);
    }
}