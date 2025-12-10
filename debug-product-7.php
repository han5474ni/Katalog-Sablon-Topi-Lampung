<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Product #7 - All Variants ===\n\n";

$product = \App\Models\Product::with('variants')->find(7);

echo "Product: {$product->name}\n";
echo "Product Image: " . ($product->image ?? 'NULL') . "\n";
echo "Total Variants: {$product->variants->count()}\n\n";

echo "Variants:\n";
echo str_repeat("-", 80) . "\n";

foreach($product->variants as $variant) {
    echo "ID: {$variant->id} | Color: {$variant->color} | Size: {$variant->size}\n";
    echo "  Image: " . ($variant->image ?? 'NULL') . "\n";
    echo "  Stock: {$variant->stock}\n\n";
}

// Check if any variant has image
$hasImage = $product->variants->filter(fn($v) => !empty($v->image))->first();
if ($hasImage) {
    echo "\n✓ Found variant with image: ID {$hasImage->id}\n";
    echo "  Image path: {$hasImage->image}\n";
} else {
    echo "\n✗ No variants have images for this product\n";
}
