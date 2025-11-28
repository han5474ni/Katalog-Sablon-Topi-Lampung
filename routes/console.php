<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Auto-expire VA setiap 5 menit
Schedule::command('va:expire')->everyFiveMinutes();

// Check expired VAs and restore stock every minute
Schedule::command('va:check-expired')->everyMinute();
