<?php

namespace App\Console\Commands;

use App\Models\VirtualAccount;
use App\Models\CustomDesignOrder;
use App\Models\Order;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ExpireVirtualAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'va:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire virtual accounts yang sudah melewati batas waktu dan cancel order terkait';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking expired virtual accounts...');
        
        // Get expired VAs that are still pending
        $expiredVAs = VirtualAccount::where('status', 'pending')
            ->where('expired_at', '<=', now())
            ->get();
        
        if ($expiredVAs->isEmpty()) {
            $this->info('No expired virtual accounts found.');
            return 0;
        }
        
        $this->info("Found {$expiredVAs->count()} expired VA(s)");
        
        foreach ($expiredVAs as $va) {
            $this->line("Processing VA #{$va->id} (Order: {$va->order_type} #{$va->order_id})");
            
            // Mark VA as expired
            $va->update(['status' => 'expired']);
            
            // Cancel associated order
            if ($va->order_type === 'custom') {
                $order = CustomDesignOrder::find($va->order_id);
                if ($order && $order->status === 'approved' && $order->payment_status === 'va_active') {
                    $order->update([
                        'status' => 'cancelled',
                        'payment_status' => 'unpaid',
                        'cancelled_at' => now(),
                        'admin_notes' => 'Otomatis dibatalkan karena VA expired pada ' . $va->expired_at->format('d M Y, H:i')
                    ]);
                    $this->info("  → Order #{$order->id} cancelled");
                }
            } else {
                $order = Order::find($va->order_id);
                if ($order && $order->status === 'approved' && $order->payment_status === 'va_active') {
                    $order->update([
                        'status' => 'cancelled',
                        'payment_status' => 'unpaid',
                        'cancelled_at' => now(),
                        'admin_notes' => 'Otomatis dibatalkan karena VA expired pada ' . $va->expired_at->format('d M Y, H:i')
                    ]);
                    $this->info("  → Order #{$order->id} cancelled");
                }
            }
            
            // Expire associated payment transaction
            $transaction = $va->paymentTransaction;
            if ($transaction && $transaction->status === 'pending') {
                $transaction->update([
                    'status' => 'failed',
                    'notes' => 'VA expired'
                ]);
                $this->info("  → Transaction updated");
            }
        }
        
        $this->info('✓ Done! Expired ' . $expiredVAs->count() . ' VA(s)');
        return 0;
    }
}
