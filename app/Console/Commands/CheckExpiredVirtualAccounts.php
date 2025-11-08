<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\VirtualAccount;
use App\Models\Order;
use App\Models\CustomDesignOrder;
use App\Models\Product;
use App\Models\ProductVariant;

class CheckExpiredVirtualAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'va:check-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for expired Virtual Accounts and restore stock for unpaid orders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expired Virtual Accounts...');
        
        // Find all VAs that are expired but still marked as pending
        $expiredVAs = VirtualAccount::where('status', 'pending')
            ->where('expired_at', '<=', now())
            ->get();
        
        if ($expiredVAs->isEmpty()) {
            $this->info('No expired Virtual Accounts found.');
            return 0;
        }
        
        $this->info("Found {$expiredVAs->count()} expired Virtual Accounts.");
        $processedCount = 0;
        $stockRestoredCount = 0;
        
        foreach ($expiredVAs as $va) {
            $this->line("Processing VA #{$va->id} for user #{$va->user_id}...");
            
            try {
                // Get the specific order linked to this VA
                if ($va->order_type && $va->order_id) {
                    if ($va->order_type === 'custom') {
                        $order = CustomDesignOrder::find($va->order_id);
                        if ($order && $order->payment_status != 'paid') {
                            $this->restoreStockForOrder($order, 'custom');
                            $stockRestoredCount++;
                            $this->line("  → Stock restored for custom order #{$order->id}");
                        }
                    } else {
                        $order = Order::find($va->order_id);
                        if ($order && $order->payment_status != 'paid') {
                            $this->restoreStockForOrder($order, 'regular');
                            $stockRestoredCount++;
                            $this->line("  → Stock restored for regular order #{$order->id}");
                        }
                    }
                    
                    // Mark VA as expired
                    $va->update(['status' => 'expired']);
                    $processedCount++;
                    
                    \Log::info("VA #{$va->id} expired - stock restored for Order #{$va->order_id} ({$va->order_type})");
                } else {
                    // Old VA without order link, just mark as expired
                    $va->update(['status' => 'expired']);
                    $this->line("  → Old VA without order link, marked as expired");
                }
                
            } catch (\Exception $e) {
                $this->error("✗ Error processing VA #{$va->id}: " . $e->getMessage());
                \Log::error("Error processing expired VA #{$va->id}: " . $e->getMessage());
            }
        }
        
        $this->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("Summary:");
        $this->info("  VAs processed: {$processedCount}");
        $this->info("  Stock restored for: {$stockRestoredCount} orders");
        $this->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        
        return 0;
    }
    
    /**
     * Restore stock for an order
     */
    private function restoreStockForOrder($order, $orderType)
    {
        try {
            if ($orderType === 'custom') {
                // Custom order: single product with variant
                if ($order->variant_id) {
                    $variant = ProductVariant::find($order->variant_id);
                    if ($variant) {
                        $variant->increment('stock', $order->quantity);
                        \Log::info("Stock restored (VA expired) for custom order #{$order->id}: Variant #{$variant->id}, qty: {$order->quantity}");
                    }
                } else {
                    // No variant, restore to product
                    $product = Product::find($order->product_id);
                    if ($product) {
                        $product->increment('stock', $order->quantity);
                        \Log::info("Stock restored (VA expired) for custom order #{$order->id}: Product #{$product->id}, qty: {$order->quantity}");
                    }
                }
            } else {
                // Regular order: multiple items
                foreach ($order->items as $item) {
                    if (isset($item['variant_id']) && $item['variant_id']) {
                        $variant = ProductVariant::find($item['variant_id']);
                        if ($variant) {
                            $variant->increment('stock', $item['quantity']);
                            \Log::info("Stock restored (VA expired) for order #{$order->id}: Variant #{$variant->id}, qty: {$item['quantity']}");
                        }
                    } else {
                        // No variant, restore to product
                        $product = Product::find($item['product_id']);
                        if ($product) {
                            $product->increment('stock', $item['quantity']);
                            \Log::info("Stock restored (VA expired) for order #{$order->id}: Product #{$product->id}, qty: {$item['quantity']}");
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error("Error restoring stock (VA expired) for order #{$order->id}: " . $e->getMessage());
            throw $e;
        }
    }
}
