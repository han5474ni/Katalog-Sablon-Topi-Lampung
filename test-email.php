<?php

require __DIR__.'/vendor/autoload.php';

use Illuminate\Support\Facades\Mail;

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    Mail::raw('Ini adalah test email dari LGI Store', function ($message) {
        $message->to('muhammad.122140058@student.itera.ac.id')
                ->subject('Test Email - LGI Store');
    });
    
    echo "✅ Email berhasil dikirim!\n";
    echo "Cek inbox Anda: muhammad.122140058@student.itera.ac.id\n";
    echo "Jika tidak ada, cek folder Spam/Junk\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
