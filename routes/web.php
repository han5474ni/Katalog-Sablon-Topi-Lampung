<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

Route::view('/', 'Home Page');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Route untuk homepage (menggunakan Blade template)
Route::get('/homepage', [HomepageController::class, 'index'])
    ->middleware(['auth'])
    ->name('homepage');
    
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::get('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');
});


Route::get('/auth/google', function () {
    // Tambahkan parameter untuk memaksa user memilih akun
    return Socialite::driver('google')
        ->with(['prompt' => 'select_account'])
        ->redirect();
})->middleware('guest')->name('google.login');

Route::get('/auth/google/callback', function () {
    try {
        $googleUser = Socialite::driver('google')->stateless()->user();
        
        // Cek apakah user sudah ada
        $user = User::where('email', $googleUser->email)->first();
        
        if (!$user) {
            // Buat user baru jika belum ada
            $user = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'avatar' => $googleUser->avatar,
                'password' => Hash::make(Str::random(16))
            ]);
        } else {
            // Update google_id dan avatar jika user sudah ada tapi belum punya
            if (!$user->google_id) {
                $user->update([
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                ]);
            }
        }

        // Login user
        Auth::login($user);
        
        // Regenerate session untuk keamanan
        request()->session()->regenerate();
        
        return redirect()->route('homepage');
        
    } catch (\Exception $e) {
        return redirect()->route('login')->with('error', 'Google login gagal: ' . $e->getMessage());
    }
})->name('google.callback');
// Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])
//     ->name('google.login');
// Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Admin Routes
Route::prefix('admin')->group(function () {
    // Routes untuk admin yang belum login
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
    });

    // Routes untuk admin yang sudah login
    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', [AdminAuthController::class, 'dashboard'])->name('admin.dashboard');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    });
});

Route::middleware('guest')->group(function () {
    Route::get('forgot-password', function () {
        return view('livewire.pages.auth.forgot-password');
    })->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');
});

require __DIR__.'/auth.php';