<?php

use Illuminate\Support\Facades\Route;
Route::view('/', 'Home Page');

Route::view('dashboard', 'dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';