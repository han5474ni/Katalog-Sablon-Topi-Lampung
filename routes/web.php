<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

// =============================
// IMPORT CONTROLLERS
// =============================
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomDesignPriceController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerProfileController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ResendWebhookController;

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\ProductManagementController;
use App\Http\Controllers\Admin\OrderManagementController;
use App\Http\Controllers\Admin\AdminNotificationController;

use App\Services\ChatBotService;


// =============================
// PUBLIC ROUTES
// =============================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang-kami', fn() => view('pages.about'))->name('about');

Route::get('/all-products', [ProductController::class, 'allProducts'])->name('all-products');
Route::get('/produk/{id}', [ProductController::class, 'detail'])->name('product.detail');

Route::get('/catalog/{category}', [CatalogController::class, 'index'])->name('catalog');


// =============================
// PUBLIC API
// =============================
Route::get('/api/custom-design-prices', [CustomDesignPriceController::class, 'getPrices']);
Route::get('/api/product-custom-design-prices/{productId}', [CustomDesignPriceController::class, 'getProductPrices']);


// =============================
// AUTH (DEFAULT + GOOGLE)
// =============================
require __DIR__.'/auth.php';

Route::middleware('guest')->group(function () {
    Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])
        ->name('google.login');

    Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])
        ->name('google.callback');
});


// =============================
// CUSTOMER (AUTH REQUIRED)
// =============================
Route::middleware(['auth'])->group(function () {

    // ===== PROFILE =====
    Route::prefix('profile')->group(function () {
        Route::get('/', [CustomerProfileController::class, 'index'])->name('profile');
        Route::post('/', [CustomerProfileController::class, 'update'])->name('profile.update');

        Route::post('/password', [CustomerProfileController::class, 'updatePassword'])->name('profile.update-password');
        Route::post('/avatar', [CustomerProfileController::class, 'updateAvatar'])->name('profile.update-avatar');
        Route::delete('/avatar', [CustomerProfileController::class, 'deleteAvatar'])->name('profile.delete-avatar');

        Route::post('/address', [CustomerProfileController::class, 'storeAddress'])->name('profile.address.store');
        Route::put('/address/{id}', [CustomerProfileController::class, 'updateAddress'])->name('profile.address.update');
        Route::delete('/address/{id}', [CustomerProfileController::class, 'deleteAddress'])->name('profile.address.delete');
        Route::post('/address/{id}/set-primary', [CustomerProfileController::class, 'setPrimaryAddress'])->name('profile.address.set-primary');
    });

    // ===== NOTIFICATIONS =====
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
        Route::post('/{id}/archive', [NotificationController::class, 'archive'])->name('notifications.archive');
    });

    // ===== CUSTOMER DASHBOARD =====
    Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');

    Route::prefix('api/dashboard')->group(function () {
        Route::get('/stats', [CustomerController::class, 'getDashboardStats']);
        Route::get('/recent-orders', [CustomerController::class, 'getRecentOrdersData']);
    });

    // ===== CUSTOMER ONLY (CART, PAYMENT, ORDER) =====
    Route::middleware('customer.only')->group(function () {

        // CART
        Route::get('/keranjang', [CustomerController::class, 'keranjang'])->name('keranjang');
        Route::post('/keranjang', [CustomerController::class, 'addToCart'])->name('cart.add');
        Route::patch('/keranjang/{key}', [CustomerController::class, 'updateCartItem'])->name('cart.update');
        Route::delete('/keranjang/{key}', [CustomerController::class, 'removeCartItem'])->name('cart.remove');
        Route::delete('/keranjang-bulk', [CustomerController::class, 'removeSelected'])->name('cart.bulk-remove');

        // CHECKOUT
        Route::post('/buy-now', [CustomerController::class, 'buyNow'])->name('buy-now');
        Route::post('/checkout', [CustomerController::class, 'checkout'])->name('checkout');

        // ORDER FLOW
        Route::get('/alamat', [CustomerController::class, 'alamat'])->name('alamat');
        Route::post('/alamat/select', [CustomerController::class, 'selectAddress']);

        Route::get('/pemesanan', [CustomerController::class, 'pemesanan'])->name('pemesanan');
        Route::post('/pemesanan/select', [CustomerController::class, 'selectShipping']);

        Route::get('/pembayaran', [CustomerController::class, 'pembayaran'])->name('pembayaran');
        Route::post('/pembayaran/generate-va', [CustomerController::class, 'generateVA'])->name('pembayaran.generate-va');
        Route::post('/pembayaran/process-order', [CustomerController::class, 'processOrder'])->name('pembayaran.process');

        Route::get('/order-list', [CustomerController::class, 'orderList'])->name('order-list');
        Route::get('/order-detail/{type}/{id}', [CustomerController::class, 'orderDetail'])->name('order-detail');
        Route::post('/order/{type}/{id}/cancel', [CustomerController::class, 'cancelOrder'])->name('order.cancel');

        // CUSTOM DESIGN
        Route::get('/custom-design', [CustomerController::class, 'customDesign'])->name('custom-design');
        Route::post('/custom-design', [CustomerController::class, 'storeCustomDesign'])->name('custom-design.store');
        Route::get('/custom-design/download/{uploadId}', [CustomerController::class, 'downloadCustomDesignFile'])->name('custom-design.download');

        // CHATBOT PAGES
        Route::get('/chatpage', [CustomerController::class, 'chatpage'])->name('chatpage');
        Route::get('/notifikasi', [CustomerController::class, 'notifikasi'])->name('notifikasi');
    });

    // ------- LOGOUT -------
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');
});


// =============================
// CHAT (AUTH + VERIFIED)
// =============================
Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('chat')->group(function () {
        Route::get('/product/{productId}', [ChatController::class, 'startChat'])->name('chat.start');
        Route::get('/history', [ChatController::class, 'getChatHistory'])->name('chat.history');
        Route::get('/conversation/{id}', [ChatController::class, 'getConversation'])->name('chat.conversation');
        Route::post('/send-message', [ChatController::class, 'sendMessage'])->name('chat.send');
    });
});


// =============================
// API STOCK
// =============================
Route::get('/api/product/{id}/stock', fn($id) => app(ChatBotService::class)->getProductStockInfo($id));


// =============================
// RESEND WEBHOOK
// =============================
Route::post('/webhooks/resend', [ResendWebhookController::class, 'handle'])
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);


// =============================
// ADMIN ROUTES
// =============================
Route::prefix('admin')->group(function () {

    // GUEST ADMIN
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
    });

    // AUTH ADMIN
    Route::middleware('admin')->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        // PRODUCTS
        Route::get('/products', [ProductManagementController::class, 'index'])->name('admin.products');
        Route::get('/products/{id}', [ProductManagementController::class, 'productDetail'])->name('admin.products.detail');

        // ORDERS
        Route::get('/order-list', [OrderManagementController::class, 'index'])->name('admin.order-list');

        // USERS
        Route::get('/management-users', [UserManagementController::class, 'index'])->name('admin.management-users');

        // ADMIN NOTIFICATIONS
        Route::prefix('notifications')->group(function () {
            Route::get('/', [AdminNotificationController::class, 'index'])->name('admin.notifications.index');
            Route::post('/read-all', [AdminNotificationController::class, 'markAllAsRead']);
        });

        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    });
});


// =============================
// DEBUG ROUTES
// =============================
Route::get('/debug-n8n-config', fn() => [
    'n8n_url' => config('services.n8n.webhook_url'),
    'env' => env('N8N_WEBHOOK_URL'),
]);

Route::get('/test-n8n-simple', fn() =>
    Http::post(config('services.n8n.webhook_url'), ['message' => 'test'])
);
