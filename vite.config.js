import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // Core
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/bootstrap.js',
                
                // Admin
                'resources/css/admin/login.css',
                'resources/css/admin/dashboard.css',
                'resources/css/admin/management-order.css',
                'resources/css/admin/user-management.css',
                'resources/css/admin/product-management.css',
                'resources/js/admin/dashboard-charts.js',
                'resources/js/admin/user-management.js',
                'resources/js/admin/product-management.js',
                
                // Auth
                'resources/css/auth/forgot-password.css',
                
                // Guest/Homepage
                'resources/css/guest/home.css',
                'resources/css/guest/auth-layout.css',
                'resources/css/guest/login.css',
                'resources/css/guest/product-detail.css',
                'resources/css/guest/catalog.css',
                'resources/css/guest/custom-design.css',
                'resources/js/guest/home.js',
                'resources/js/guest/auth-layout.js',
                'resources/js/guest/login.js',
                'resources/js/guest/product-slider.js',
                'resources/js/guest/product-detail.js',
                'resources/js/guest/catalog.js',
                'resources/js/guest/custom-design.js',
                
                // Components
                'resources/css/components/footer.css',
                
                // Customer
                'resources/css/customer/shared.css',
                'resources/css/customer/profile-form.css',
                'resources/js/customer/chatbot.js',
                'resources/js/customer/profile-dropdown.js',
                'resources/js/customer/cart.js',
            ],
            refresh: true,
        }),
    ],
});
