<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Pesanan - LGI Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    @vite(['resources/css/customer/shared.css', 'resources/js/customer/notifications.js'])
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
            <x-customer-header title="Daftar Pesanan" />

            <!-- Order List Content -->
            <div class="p-6">
                <h1 class="text-2xl font-bold mb-6">Daftar Pesanan</h1>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                @forelse($orders as $order)
                <div class="bg-white rounded-lg shadow-md p-6 mb-4">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Pesanan #{{ $order->id }}</h3>
                            <p class="text-sm text-gray-600">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status === 'approved') bg-green-100 text-green-800
                            @elseif($order->status === 'rejected') bg-red-100 text-red-800
                            @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                            @elseif($order->status === 'completed') bg-purple-100 text-purple-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ $order->status_label }}
                        </span>
                    </div>

                    <div class="space-y-3 mb-4">
                        @foreach($order->items as $item)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-b-0">
                            <div class="flex items-center space-x-3">
                                @if($item['image'])
                                    <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="w-12 h-12 object-cover rounded">
                                @else
                                    <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                        <span class="text-gray-500 text-xs">No Image</span>
                                    </div>
                                @endif
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $item['name'] }}</h4>
                                    <p class="text-sm text-gray-600">
                                        @if($item['color']) Warna: {{ $item['color'] }} @endif
                                        @if($item['size']) Ukuran: {{ $item['size'] }} @endif
                                        Qty: {{ $item['quantity'] }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-gray-900">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</p>
                                <p class="text-sm text-gray-600">Rp {{ number_format($item['price'], 0, ',', '.') }} x {{ $item['quantity'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                        <div>
                            <p class="text-sm text-gray-600">Total Pesanan</p>
                            <p class="text-lg font-bold text-gray-900">Rp {{ number_format((float) $order->total, 0, ',', '.') }}</p>
                        </div>
                        @if($order->status === 'rejected' && $order->admin_notes)
                        <div class="text-right">
                            <p class="text-sm text-red-600 font-medium">Alasan Penolakan:</p>
                            <p class="text-sm text-red-800">{{ $order->admin_notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="bg-white rounded-lg shadow-md p-8 text-center">
                    <div class="text-gray-400 mb-4">
                        <i class="fas fa-shopping-bag text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada pesanan</h3>
                    <p class="text-gray-600 mb-4">Anda belum memiliki pesanan apapun.</p>
                    <a href="{{ route('catalog', ['category' => 'kaos']) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        Mulai Belanja
                    </a>
                </div>
                @endforelse

                @if($orders->hasPages())
                <div class="mt-6">
                    {{ $orders->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>


</body>
</html>