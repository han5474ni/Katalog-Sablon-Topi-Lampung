<?php

/**
 * Database Fix Script
 * 
 * Script ini membantu memperbaiki masalah database yang menyebabkan error 500
 * 
 * Usage: php database/fix_database.php
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);

// Run migrations
echo "=== Running Database Migrations ===\n";
$kernel->call('migrate', ['--force' => true]);

echo "\n=== Clearing Cache ===\n";
$kernel->call('cache:clear');
$kernel->call('config:clear');
$kernel->call('view:clear');

echo "\n=== Database Fix Complete ===\n";
echo "✓ Migrations executed\n";
echo "✓ Cache cleared\n";
echo "\nPlease verify the application is working correctly.\n";
