<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Debug Order Images ===\n\n";

// Check recent regular orders
$orders = \App\Models\Order::orderBy('created_at', 'desc')->limit(5)->get();

echo "Recent Regular Orders:\n";
echo str_repeat("-", 80) . "\n";

foreach ($orders as $order) {
    echo "Order ID: {$order->id} | Number: {$order->order_number}\n";
    
    $items = $order->items;
    if (is_string($items)) {
        $items = json_decode($items, true);
    }
    
    if (!is_array($items)) {
        echo "  ❌ Items is not an array\n";
        continue;
    }
    
    foreach ($items as $key => $item) {
        $productId = $item['product_id'] ?? 'N/A';
        $variantId = $item['variant_id'] ?? 'N/A';
        $image = $item['image'] ?? 'NULL';
        
        echo "  Item #{$key}:\n";
        echo "    Product ID: {$productId}\n";
        echo "    Variant ID: {$variantId}\n";
        echo "    Image: {$image}\n";
        
        // Check if product exists
        if (is_numeric($productId)) {
            $product = \App\Models\Product::find($productId);
            if ($product) {
                echo "    Product Image: " . ($product->image ?? 'NULL') . "\n";
            } else {
                echo "    ⚠️  Product not found\n";
            }
        }
        
        // Check if variant exists
        if (is_numeric($variantId)) {
            $variant = \App\Models\ProductVariant::find($variantId);
            if ($variant) {
                echo "    Variant Image: " . ($variant->image ?? 'NULL') . "\n";
            } else {
                echo "    ⚠️  Variant not found\n";
            }
        }
        
        echo "\n";
    }
    
    echo str_repeat("-", 80) . "\n";
}

// Check recent custom design orders
echo "\n\nRecent Custom Design Orders:\n";
echo str_repeat("-", 80) . "\n";

$customOrders = \App\Models\CustomDesignOrder::with(['uploads', 'product', 'variant'])
    ->orderBy('created_at', 'desc')
    ->limit(5)
    ->get();

foreach ($customOrders as $order) {
    echo "Custom Order ID: {$order->id} | Product: {$order->product_name}\n";
    echo "  Uploads: " . $order->uploads->count() . "\n";
    
    if ($order->uploads->count() > 0) {
        foreach ($order->uploads as $upload) {
            echo "    - {$upload->section_name}: {$upload->file_path}\n";
        }
    }
    
    if ($order->product) {
        echo "  Product Image: " . ($order->product->image ?? 'NULL') . "\n";
    }
    
    if ($order->variant) {
        echo "  Variant Image: " . ($order->variant->image ?? 'NULL') . "\n";
    }
    
    echo str_repeat("-", 80) . "\n";
}

echo "\nDone!\n";
