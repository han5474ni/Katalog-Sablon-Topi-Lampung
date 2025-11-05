<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ProductManagementController;
use App\Http\Controllers\Admin\OrderManagementController;
use App\Http\Controllers\Admin\ColorManagementController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\GoogleAuthController;
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
    Route::post('/keranjang', [CustomerController::class, 'addToCart'])
        ->name('cart.add');
    Route::patch('/keranjang/{key}', [CustomerController::class, 'updateCartItem'])
        ->name('cart.update');
    Route::delete('/keranjang/{key}', [CustomerController::class, 'removeCartItem'])
        ->name('cart.remove');
    Route::delete('/keranjang-bulk', [CustomerController::class, 'removeSelected'])
        ->name('cart.bulk-remove');

    Route::post('/checkout', [CustomerController::class, 'checkout'])
        ->name('checkout');

    Route::get('/alamat', [CustomerController::class, 'alamat'])
        ->name('alamat');

    Route::get('/pemesanan', [CustomerController::class, 'pemesanan'])
        ->name('pemesanan');

    Route::get('/pembayaran', [CustomerController::class, 'pembayaran'])
        ->name('pembayaran');

    Route::get('/order-list', [CustomerController::class, 'orderList'])
        ->name('order-list');

    Route::get('/chatbot', [CustomerController::class, 'chatbot'])
        ->name('chatbot');

    Route::get('/custom-design', [CustomerController::class, 'customDesign'])
        ->name('custom-design');

        // Route untuk submit custom design order
        Route::post('/custom-design', [CustomerController::class, 'storeCustomDesign'])
            ->name('custom-design.store');

    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');
});


Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])
    ->middleware('guest')
    ->name('google.login');

Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])
    ->name('google.callback');

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
        Route::get('/order-list', [OrderManagementController::class, 'index'])->name('admin.order-list');
        Route::get('/order-list/{id}/detail', [OrderManagementController::class, 'showDetail'])->name('admin.order.detail');
        Route::get('/order-list/export', [OrderManagementController::class, 'export'])->name('admin.order-list.export');
        Route::post('/order-list/{id}/approve', [OrderManagementController::class, 'approve'])->name('admin.order.approve');
        Route::post('/order-list/{id}/reject', [OrderManagementController::class, 'reject'])->name('admin.order.reject');
        Route::patch('/order-list/{id}/status', [OrderManagementController::class, 'updateStatus'])->name('admin.order.update-status');
        Route::get('/management-users', [UserManagementController::class, 'index'])->name('admin.management-users');
        Route::get('/management-users/customer/{id}', [UserManagementController::class, 'showCustomerDetail'])->name('admin.customer-detail');
        Route::get('/management-users/customer/{id}/export-pdf', [UserManagementController::class, 'exportCustomerPDF'])->name('admin.customer-export-pdf');
        Route::get('/management-users/customer/{id}/export-excel', [UserManagementController::class, 'exportCustomerExcel'])->name('admin.customer-export-excel');
        Route::get('/management-users/export-admins', [UserManagementController::class, 'exportAdmins'])->name('admin.management-users.export-admins');
        Route::get('/management-users/export-customers', [UserManagementController::class, 'exportCustomers'])->name('admin.management-users.export-customers');
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('admin.activity-logs');
        Route::get('/activity-logs/export', [ActivityLogController::class, 'export'])->name('admin.activity-logs.export');
        Route::get('/history', [ActivityLogController::class, 'history'])->name('admin.history');
        
        // Product Management
        Route::get('/management-product', [ProductManagementController::class, 'index'])->name('admin.management-product');
        Route::get('/all-products', [ProductManagementController::class, 'allProducts'])->name('admin.all-products');
        
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
            
            // Color Management API
            Route::get('/colors', [ColorManagementController::class, 'index'])->name('colors.index');
            Route::post('/colors', [ColorManagementController::class, 'store'])->name('colors.store');
            Route::delete('/colors/{color}', [ColorManagementController::class, 'destroy'])->name('colors.destroy');
            Route::delete('/colors', [ColorManagementController::class, 'clear'])->name('colors.clear');
        });
        
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    });
});

require __DIR__.'/auth.php';