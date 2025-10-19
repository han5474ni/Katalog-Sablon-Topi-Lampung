<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Order List - LGI Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    @vite('resources/css/customer/shared.css')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            window.addEventListener('profile-updated', event => {
                const newAvatarUrl = event.detail.avatarUrl;
                document.querySelectorAll('.header-avatar').forEach(img => {
                    img.src = newAvatarUrl;
                });
            });
        });
    </script>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <x-customer-sidebar active="order-list" />

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <x-customer-header title="Order List" />

            <!-- Order List Content -->
            <div class="p-6">
                <h1 class="text-2xl font-bold mb-6">Daftar Pesanan</h1>
                <p>Daftar pesanan Anda akan muncul di sini.</p>
            </div>
        </div>
    </div>


</body>
</html>