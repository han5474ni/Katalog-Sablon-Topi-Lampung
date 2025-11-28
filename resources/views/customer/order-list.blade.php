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
                            <h3 class="text-lg font-semibold text-gray-900">
                                @if($order instanceof \App\Models\CustomDesignOrder)
                                    <span class="inline-flex items-center px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded mr-2">
                                        <i class="fas fa-palette mr-1"></i> Custom Design
                                    </span>
                                @endif
                                Pesanan #{{ $order->id }}
                            </h3>
                            <p class="text-sm text-gray-600">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status === 'approved') bg-green-100 text-green-800
                            @elseif($order->status === 'rejected') bg-red-100 text-red-800
                            @elseif($order->status === 'cancelled') bg-gray-100 text-gray-800
                            @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                            @elseif($order->status === 'completed') bg-purple-100 text-purple-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            @if($order->status === 'pending')
                                Menunggu Konfirmasi
                            @elseif($order->status === 'approved')
                                Disetujui
                            @elseif($order->status === 'rejected')
                                Ditolak
                            @elseif($order->status === 'cancelled')
                                Dibatalkan
                            @elseif($order->status === 'processing')
                                Diproses
                            @elseif($order->status === 'completed')
                                Selesai
                            @else
                                {{ ucfirst($order->status) }}
                            @endif
                        </span>
                    </div>

                    @if($order instanceof \App\Models\CustomDesignOrder)
                        {{-- Custom Design Order --}}
                        <div class="space-y-3 mb-4">
                            <div class="flex items-start space-x-3 py-3 border-b border-gray-100">
                                @php
                                    // Priority: variant->image > product->image > placeholder
                                    $orderImage = null;
                                    if ($order->variant && !empty($order->variant->image)) {
                                        $orderImage = str_starts_with($order->variant->image, 'http') 
                                            ? $order->variant->image 
                                            : asset('storage/' . $order->variant->image);
                                    } elseif ($order->product && !empty($order->product->image)) {
                                        $orderImage = str_starts_with($order->product->image, 'http') 
                                            ? $order->product->image 
                                            : asset('storage/' . $order->product->image);
                                    }
                                @endphp
                                
                                @if($orderImage)
                                    <img src="{{ $orderImage }}" alt="{{ $order->product_name }}" class="w-16 h-16 object-cover rounded">
                                @else
                                    <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                        <span class="text-gray-500 text-xs">No Image</span>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">{{ $order->product_name }}</h4>
                                    <p class="text-sm text-gray-600">
                                        Custom Design
                                        @if($order->variant)
                                            @if($order->variant->color) • {{ $order->variant->color }}@endif
                                            @if($order->variant->size) • {{ $order->variant->size }}@endif
                                        @endif
                                    </p>
                                    <div class="mt-2 text-sm">
                                        <p class="text-gray-600">
                                            <i class="fas fa-cut"></i> Jenis Cutting: <span class="font-medium">{{ $order->cutting_type }}</span>
                                        </p>
                                        <p class="text-gray-600">
                                            <i class="fas fa-images"></i> Bagian Desain: <span class="font-medium">{{ $order->uploads->count() }} bagian</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            @if($order->uploads->count() > 0)
                            <div class="bg-gray-50 p-3 rounded">
                                <p class="text-sm font-medium text-gray-700 mb-2">File Desain Anda:</p>
                                <div class="grid grid-cols-2 gap-2">
                                    @foreach($order->uploads as $upload)
                                    <div class="flex items-center space-x-2 bg-white p-2 rounded border border-gray-200">
                                        <i class="fas fa-file-image text-blue-500"></i>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-medium text-gray-900 truncate">{{ $upload->section_name }}</p>
                                            <p class="text-xs text-gray-500">{{ number_format($upload->file_size / 1024, 1) }} KB</p>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="pt-4 border-t border-gray-200">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <p class="text-sm text-gray-600">Total Pesanan</p>
                                    <p class="text-lg font-bold text-gray-900">Rp {{ number_format((float) $order->total_price, 0, ',', '.') }}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Produk (Rp {{ number_format($order->product_price, 0, ',', '.') }}) + Custom Design
                                    </p>
                                </div>
                                @if($order->status === 'pending')
                                <div class="text-right">
                                    <p class="text-sm text-yellow-600 font-medium">
                                        <i class="fas fa-clock"></i> Menunggu konfirmasi admin
                                    </p>
                                </div>
                                @elseif($order->status === 'rejected')
                                <div class="text-right">
                                    <p class="text-sm text-red-600 font-medium">Alasan Penolakan:</p>
                                    <p class="text-sm text-red-800">{{ $order->admin_notes ?? 'Tidak ada catatan' }}</p>
                                </div>
                                @endif
                            </div>
                            
                            {{-- Action Buttons --}}
                            <div class="flex justify-end gap-2 mt-3">
                                @php
                                    // Check if order has active VA
                                    $hasActiveVA = \App\Models\VirtualAccount::where('user_id', auth()->id())
                                        ->where('order_type', 'custom')
                                        ->where('order_id', $order->id)
                                        ->where('status', 'pending')
                                        ->where('expired_at', '>', now())
                                        ->exists();
                                @endphp
                                
                                @if($order->status === 'approved' && $order->payment_deadline && $order->payment_deadline->isFuture())
                                    @if($hasActiveVA)
                                        {{-- Has VA: Only show "Status Pembayaran" button --}}
                                        <a href="{{ route('payment-status', ['type' => 'custom', 'order_id' => $order->id]) }}" 
                                           class="px-4 py-2 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700 transition">
                                            <i class="fas fa-receipt mr-1"></i> Status Pembayaran
                                        </a>
                                    @else
                                        {{-- No VA: Show "Detail" and "Bayar" buttons --}}
                                        <a href="{{ route('order-detail', ['type' => 'custom', 'id' => $order->id]) }}" 
                                           class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                                            <i class="fas fa-eye mr-1"></i> Detail
                                        </a>
                                        <a href="{{ route('alamat') }}?order_type=custom&order_id={{ $order->id }}" 
                                           class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition">
                                            <i class="fas fa-credit-card mr-1"></i> Bayar
                                        </a>
                                    @endif
                                @elseif(in_array($order->status, ['processing', 'completed']))
                                    <a href="{{ route('payment-status', ['type' => 'custom', 'order_id' => $order->id]) }}" 
                                       class="px-4 py-2 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700 transition">
                                        <i class="fas fa-receipt mr-1"></i> Status Pembayaran
                                    </a>
                                @endif
                                
                                @if($order->status === 'pending')
                                <button onclick="cancelOrder('custom', {{ $order->id }})" 
                                        class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition">
                                    <i class="fas fa-times mr-1"></i> Batalkan
                                </button>
                                @endif
                            </div>
                        </div>
                    @else
                        {{-- Regular Order --}}
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

                        <div class="pt-4 border-t border-gray-200">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <p class="text-sm text-gray-600">Total Pesanan</p>
                                    <p class="text-lg font-bold text-gray-900">Rp {{ number_format((float) $order->total, 0, ',', '.') }}</p>
                                </div>
                                @if($order->status === 'pending')
                                <div class="text-right">
                                    <p class="text-sm text-yellow-600 font-medium">
                                        <i class="fas fa-clock"></i> Menunggu konfirmasi admin
                                    </p>
                                </div>
                                @elseif($order->status === 'rejected' && $order->admin_notes)
                                <div class="text-right">
                                    <p class="text-sm text-red-600 font-medium">Alasan Penolakan:</p>
                                    <p class="text-sm text-red-800">{{ $order->admin_notes }}</p>
                                </div>
                                @endif
                            </div>
                            
                            {{-- Action Buttons --}}
                            <div class="flex justify-end gap-2 mt-3">
                                @php
                                    // Check if order has active VA
                                    $hasActiveVA = \App\Models\VirtualAccount::where('user_id', auth()->id())
                                        ->where('order_type', 'regular')
                                        ->where('order_id', $order->id)
                                        ->where('status', 'pending')
                                        ->where('expired_at', '>', now())
                                        ->exists();
                                @endphp
                                
                                @if($order->status === 'approved' && $order->payment_deadline && $order->payment_deadline->isFuture())
                                    @if($hasActiveVA)
                                        {{-- Has VA: Only show "Status Pembayaran" button --}}
                                        <a href="{{ route('payment-status', ['type' => 'regular', 'order_id' => $order->id]) }}" 
                                           class="px-4 py-2 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700 transition">
                                            <i class="fas fa-receipt mr-1"></i> Status Pembayaran
                                        </a>
                                    @else
                                        {{-- No VA: Show "Detail" and "Bayar" buttons --}}
                                        <a href="{{ route('order-detail', ['type' => 'regular', 'id' => $order->id]) }}" 
                                           class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                                            <i class="fas fa-eye mr-1"></i> Detail
                                        </a>
                                        <a href="{{ route('alamat') }}?order_type=regular&order_id={{ $order->id }}" 
                                           class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition">
                                            <i class="fas fa-credit-card mr-1"></i> Bayar
                                        </a>
                                    @endif
                                @elseif(in_array($order->status, ['processing', 'completed']))
                                    <a href="{{ route('payment-status', ['type' => 'regular', 'order_id' => $order->id]) }}" 
                                       class="px-4 py-2 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700 transition">
                                        <i class="fas fa-receipt mr-1"></i> Status Pembayaran
                                    </a>
                                @endif
                                
                                @if($order->status === 'pending')
                                <button onclick="cancelOrder('regular', {{ $order->id }})" 
                                        class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition">
                                    <i class="fas fa-times mr-1"></i> Batalkan
                                </button>
                                @endif
                            </div>
                        </div>
                    @endif
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

    <script>
        // Cancel order function
        async function cancelOrder(type, orderId) {
            if (!confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')) {
                return;
            }
            
            try {
                const response = await fetch(`/order/${type}/${orderId}/cancel`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('Pesanan berhasil dibatalkan');
                    location.reload();
                } else {
                    alert(data.message || 'Gagal membatalkan pesanan');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat membatalkan pesanan');
            }
        }
    </script>

</body>
</html>