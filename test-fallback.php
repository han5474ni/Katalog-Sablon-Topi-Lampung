<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Test Fallback Logic ===\n\n";

$variant = App\Models\ProductVariant::find(126);

echo "Original Variant (ID 126):\n";
echo "  Color: {$variant->color}\n";
echo "  Size: {$variant->size}\n";
echo "  Image: " . ($variant->image ?? 'NULL') . "\n\n";

// Test same color fallback
$sameColorVariant = App\Models\ProductVariant::where('product_id', $variant->product_id)
    ->where('color', $variant->color)
    ->whereNotNull('image')
    ->first();

if ($sameColorVariant) {
    echo "✓ Found same color variant with image:\n";
    echo "  Variant ID: {$sameColorVariant->id}\n";
    echo "  Color: {$sameColorVariant->color}\n";
    echo "  Size: {$sameColorVariant->size}\n";
    echo "  Image: {$sameColorVariant->image}\n\n";
} else {
    echo "✗ No same color variant with image found\n\n";
}

// Test any variant fallback
$anyVariant = App\Models\ProductVariant::where('product_id', $variant->product_id)
    ->whereNotNull('image')
    ->first();

if ($anyVariant) {
    echo "✓ Found any variant with image:\n";
    echo "  Variant ID: {$anyVariant->id}\n";
    echo "  Color: {$anyVariant->color}\n";
    echo "  Size: {$anyVariant->size}\n";
    echo "  Image: {$anyVariant->image}\n";
}
