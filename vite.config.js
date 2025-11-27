import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                // Admin CSS
                'resources/css/admin/all-products.css',
                'resources/css/admin/analytics.css',
                'resources/css/admin/chatbot.css',
                'resources/css/admin/customer-detail.css',
                'resources/css/admin/dashboard.css',
                'resources/css/admin/history.css',
                'resources/css/admin/login.css',
                'resources/css/admin/management-order.css',
                'resources/css/admin/modern-add-product.css',
                'resources/css/admin/order-list.css',
                'resources/css/admin/product-management.css',
                'resources/css/admin/profile.css',
                'resources/css/admin/user-management.css',
                // Auth CSS
                'resources/css/auth/auth-layout.css',
                'resources/css/auth/forgot-password.css',
                // Component CSS
                'resources/css/components/chatbot.css',
                'resources/css/components/footer.css',
                'resources/css/components/navbar.css',
                'resources/css/components/product-card.css',
                // Customer CSS
                'resources/css/customer/Pembayaran.css',
                'resources/css/customer/all-product.css',
                'resources/css/customer/profile-form.css',
                'resources/css/customer/shared.css',
                // Guest CSS
                'resources/css/guest/Pemesanan.css',
                'resources/css/guest/alamat.css',
                'resources/css/guest/auth-layout.css',
                'resources/css/guest/catalog-inline.css',
                'resources/css/guest/catalog.css',
                'resources/css/guest/chatbot.css',
                'resources/css/guest/custom-design.css',
                'resources/css/guest/home.css',
                'resources/css/guest/login.css',
                'resources/css/guest/other-info.css',
                'resources/css/guest/product-detail.css',
                // Admin JS
                'resources/js/admin/activity-logs.js',
                'resources/js/admin/all-products.js',
                'resources/js/admin/analytics.js',
                'resources/js/admin/dashboard.js',
                'resources/js/admin/dashboard-charts.js',
                'resources/js/admin/layout.js',
                'resources/js/admin/login.js',
                'resources/js/admin/modern-add-product.js',
                'resources/js/admin/product-management.js',
                'resources/js/admin/user-management.js',
                // Chat JS
                'resources/js/chat/customer-chat.js',
                // Chatbot JS
                'resources/js/chatbot/product-chatbot.js',
                // Components JS
                'resources/js/components/navbar.js',
                // Customer JS
                'resources/js/customer/cart.js',
                'resources/js/customer/chatbot.js',
                'resources/js/customer/notifications.js',
                'resources/js/customer/profile-dropdown.js',
                // Guest JS
                'resources/js/guest/auth-layout.js',
                'resources/js/guest/catalog.js',
                'resources/js/guest/custom-design.js',
                'resources/js/guest/home.js',
                'resources/js/guest/login.js',
                'resources/js/guest/product-card-carousel.js',
                'resources/js/guest/product-detail.js',
                'resources/js/guest/product-slider.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
