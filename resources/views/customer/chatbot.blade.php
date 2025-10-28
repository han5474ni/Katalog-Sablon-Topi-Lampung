<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bantuan - LGI Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    @vite('resources/css/customer/shared.css')
    @vite('resources/js/customer/chatbot.js')
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <x-customer-sidebar active="chatbot" />

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <x-customer-header title="Bantuan" />

            <!-- Chatbot Content -->
            <div class="p-6">
                <h1 class="text-2xl font-bold mb-6">Bantuan</h1>
                <p>Chatbot akan membantu Anda dengan pertanyaan tentang produk dan layanan kami.</p>
            </div>
        </div>
    </div>
</body>
</html>