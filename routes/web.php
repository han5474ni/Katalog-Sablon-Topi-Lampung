<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ProductManagementController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CatalogController;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/all-products', [ProductController::class, 'allProducts'])->name('all-products');

Route::get('/public/detail', [ProductController::class, 'detail'])->name('product.detail');

// Catalog routes
Route::get('/catalog/{category}', [CatalogController::class, 'index'])->name('catalog');

// Customer authenticated routes
Route::middleware(['auth'])->group(function () {
    // Profile routes
    Route::get('/profile', [CustomerProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [CustomerProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/confirm-email/{token}', [CustomerProfileController::class, 'confirmEmailChange'])->name('profile.confirm-email-change');
    Route::post('/profile/avatar', [CustomerProfileController::class, 'updateAvatar'])->name('profile.update-avatar');
    Route::delete('/profile/avatar', [CustomerProfileController::class, 'deleteAvatar'])->name('profile.delete-avatar');
    Route::post('/profile/password', [CustomerProfileController::class, 'updatePassword'])->name('profile.update-password');
    
    Route::get('/dashboard', [CustomerController::class, 'dashboard'])
        ->name('dashboard');

    Route::get('/keranjang', [CustomerController::class, 'keranjang'])
        ->name('keranjang');

    Route::get('/order-list', [CustomerController::class, 'orderList'])
        ->name('order-list');

    Route::get('/chatbot', [CustomerController::class, 'chatbot'])
        ->name('chatbot');

    Route::get('/custom-design', [CustomerController::class, 'customDesign'])
        ->name('custom-design');

    Route::post('/logout', function () {
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
        
        return redirect()->route('dashboard');
        
    } catch (\Exception $e) {
        return redirect()->route('login')->with('error', 'Google login gagal: ' . $e->getMessage());
    }
})->name('google.callback');

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
        Route::view('/order-list', 'admin.management-order')->name('admin.order-list');
        Route::get('/management-users', [UserManagementController::class, 'index'])->name('admin.management-users');
        Route::get('/management-users/customer/{id}', [UserManagementController::class, 'showCustomerDetail'])->name('admin.customer-detail');
        Route::get('/management-users/customer/{id}/export-pdf', [UserManagementController::class, 'exportCustomerPDF'])->name('admin.customer-export-pdf');
        Route::get('/management-users/customer/{id}/export-excel', [UserManagementController::class, 'exportCustomerExcel'])->name('admin.customer-export-excel');
        Route::get('/management-users/export-admins', [UserManagementController::class, 'exportAdmins'])->name('admin.management-users.export-admins');
        Route::get('/management-users/export-customers', [UserManagementController::class, 'exportCustomers'])->name('admin.management-users.export-customers');
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('admin.activity-logs');
        Route::get('/activity-logs/export', [ActivityLogController::class, 'export'])->name('admin.activity-logs.export');
        
        // Product Management
        Route::get('/management-product', [ProductManagementController::class, 'index'])->name('admin.management-product');
        
        Route::get('/profile', [ProfileController::class, 'index'])->name('admin.profile');
        Route::post('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
        Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('admin.profile.update-avatar');
        Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('admin.profile.delete-avatar');
        
        // API routes for user management
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/admins', [UserManagementController::class, 'getAdmins'])->name('admins.index');
            Route::post('/admins', [UserManagementController::class, 'storeAdmin'])->name('admins.store');
            Route::get('/admins/{id}', [UserManagementController::class, 'getAdmin'])->name('admins.show');
            Route::put('/admins/{id}', [UserManagementController::class, 'updateAdmin'])->name('admins.update');
            Route::delete('/admins/{id}', [UserManagementController::class, 'destroyAdmin'])->name('admins.destroy');
            
            Route::get('/customers', [UserManagementController::class, 'getCustomers'])->name('customers.index');
            Route::post('/customers', [UserManagementController::class, 'storeCustomer'])->name('customers.store');
            Route::get('/customers/{id}', [UserManagementController::class, 'getCustomer'])->name('customers.show');
            Route::put('/customers/{id}', [UserManagementController::class, 'updateCustomer'])->name('customers.update');
            Route::delete('/customers/{id}', [UserManagementController::class, 'destroyCustomer'])->name('customers.destroy');
            
            // Product Management API
            Route::get('/products', [ProductManagementController::class, 'getProducts'])->name('products.index');
            Route::get('/products/export', [ProductManagementController::class, 'export'])->name('products.export');
            Route::post('/products', [ProductManagementController::class, 'store'])->name('products.store');
            Route::get('/products/{id}', [ProductManagementController::class, 'show'])->name('products.show');
            Route::put('/products/{id}', [ProductManagementController::class, 'update'])->name('products.update');
            Route::delete('/products/{id}', [ProductManagementController::class, 'destroy'])->name('products.destroy');
            Route::post('/products/bulk-archive', [ProductManagementController::class, 'bulkArchive'])->name('products.bulk-archive');
            Route::post('/products/{id}/toggle-status', [ProductManagementController::class, 'toggleStatus'])->name('products.toggle-status');
        });
        
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    });
});

require __DIR__.'/auth.php';