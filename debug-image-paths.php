<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Debug Image Paths ===\n\n";

// Get specific orders shown in screenshot
$orders = \App\Models\Order::whereIn('id', [40, 39])->get();

foreach ($orders as $order) {
    echo "Order #{$order->id} - {$order->order_number}\n";
    echo str_repeat("-", 80) . "\n";
    
    $items = $order->items;
    if (is_string($items)) {
        $items = json_decode($items, true);
    }
    
    if (!is_array($items)) {
        echo "❌ Items is not an array\n\n";
        continue;
    }
    
    foreach ($items as $idx => $item) {
        echo "Item #{$idx}:\n";
        echo "  Name: " . ($item['name'] ?? 'N/A') . "\n";
        echo "  Product ID: " . ($item['product_id'] ?? 'N/A') . "\n";
        echo "  Variant ID: " . ($item['variant_id'] ?? 'N/A') . "\n";
        echo "  Image in items: " . ($item['image'] ?? 'NULL') . "\n";
        
        // Check product
        if (isset($item['product_id'])) {
            $product = \App\Models\Product::find($item['product_id']);
            if ($product) {
                echo "  Product exists: YES\n";
                echo "  Product name: {$product->name}\n";
                echo "  Product image field: " . ($product->image ?? 'NULL') . "\n";
                
                // Check if file exists
                if ($product->image) {
                    $storagePath = storage_path('app/public/' . ltrim($product->image, '/'));
                    $exists = file_exists($storagePath);
                    echo "  File exists at storage: " . ($exists ? 'YES' : 'NO') . "\n";
                    echo "  Storage path: {$storagePath}\n";
                }
            } else {
                echo "  Product exists: NO\n";
            }
        }
        
        // Check variant
        if (isset($item['variant_id'])) {
            $variant = \App\Models\ProductVariant::find($item['variant_id']);
            if ($variant) {
                echo "  Variant exists: YES\n";
                echo "  Variant color: " . ($variant->color ?? 'N/A') . "\n";
                echo "  Variant size: " . ($variant->size ?? 'N/A') . "\n";
                echo "  Variant image field: " . ($variant->image ?? 'NULL') . "\n";
                
                // Check if file exists
                if ($variant->image) {
                    $storagePath = storage_path('app/public/' . ltrim($variant->image, '/'));
                    $exists = file_exists($storagePath);
                    echo "  File exists at storage: " . ($exists ? 'YES' : 'NO') . "\n";
                    echo "  Storage path: {$storagePath}\n";
                }
            } else {
                echo "  Variant exists: NO\n";
            }
        }
        
        echo "\n";
    }
    
    echo str_repeat("=", 80) . "\n\n";
}

// List actual files in storage
echo "\nFiles in storage/app/public/variants/:\n";
echo str_repeat("-", 80) . "\n";
$variantsDir = storage_path('app/public/variants');
if (is_dir($variantsDir)) {
    $files = scandir($variantsDir);
    $imageFiles = array_filter($files, fn($f) => !in_array($f, ['.', '..']));
    foreach ($imageFiles as $file) {
        echo "  - {$file}\n";
    }
    echo "\nTotal files: " . count($imageFiles) . "\n";
} else {
    echo "Directory does not exist!\n";
}

echo "\nDone!\n";
