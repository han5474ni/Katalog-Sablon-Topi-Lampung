<x-customer-layout title="Detail Pesanan" active="order-list">

            <!-- Order Detail Content -->
            <div class="p-6">
                <div class="mb-4">
                    <a href="{{ route('order-list') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Pesanan
                    </a>
                </div>

                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <!-- Tabs -->
                    <div class="flex border-b border-gray-200">
                        <button onclick="showTab('detail')" id="tab-detail" class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-blue-600 text-blue-600">
                            Detail
                        </button>
                    </div>

                    <!-- Tab Content -->
                    <div class="p-6">
                        <!-- Tab Detail -->
                        <div id="content-detail" class="tab-content">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Left Column: Product Information -->
                                <div>
                                    <h3 class="text-lg font-semibold mb-4">Informasi Produk</h3>
                                    
                                    @if($type === 'custom')
                                        {{-- Custom Design Order --}}
                                        <div class="space-y-4">
                                            <div class="pb-4 border-b">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded-full">Custom Design</span>
                                                    <span class="px-2 py-1 
                                                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                                        @elseif($order->status === 'approved') bg-green-100 text-green-800
                                                        @elseif($order->status === 'rejected') bg-red-100 text-red-800
                                                        @elseif($order->status === 'cancelled') bg-gray-100 text-gray-800
                                                        @else bg-blue-100 text-blue-800
                                                        @endif
                                                        text-xs rounded-full">
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                                </div>
                                                <h4 class="font-bold text-gray-900 text-lg mb-1">{{ $order->product_name }}</h4>
                                                <p class="text-sm text-gray-600 mb-2">Custom Design</p>
                                                <p class="text-gray-700"><span class="font-medium">Jenis Cutting:</span> {{ $order->cutting_type }}</p>
                                            </div>
                                            
                                            <div>
                                                <p class="font-medium text-gray-900 mb-2">Ukuran & Qty</p>
                                                <p class="text-gray-700">Quantity: 1</p>
                                            </div>
                                            
                                            <div>
                                                <p class="font-medium text-gray-900 mb-2">Harga Produk Dasar</p>
                                                <p class="text-gray-700">Rp {{ number_format($order->product_price, 0, ',', '.') }}</p>
                                            </div>
                                            
                                            @if($order->uploads && $order->uploads->count() > 0)
                                            <div>
                                                <p class="font-medium text-gray-900 mb-2">File Desain ({{ $order->uploads->count() }} bagian)</p>
                                                <div class="grid grid-cols-1 gap-2">
                                                    @foreach($order->uploads as $upload)
                                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                                        <div class="flex items-center gap-2">
                                                            <i class="fas fa-file-image text-blue-500"></i>
                                                            <div>
                                                                <p class="text-sm font-medium text-gray-900">{{ $upload->section_name }}</p>
                                                                <p class="text-xs text-gray-500">{{ number_format($upload->file_size / 1024, 1) }} KB</p>
                                                            </div>
                                                        </div>
                                                        <a href="{{ route('custom-design.download', $upload->id) }}" 
                                                           class="inline-flex items-center gap-1 px-3 py-1.5 text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-md transition text-sm font-medium"
                                                           title="Unduh file {{ $upload->section_name }}">
                                                            <i class="fas fa-download"></i> Unduh
                                                        </a>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    @else
                                        {{-- Regular Order --}}
                                        <div class="space-y-4">
                                            @foreach($order->items as $item)
                                            <div class="pb-4 border-b">
                                                <h4 class="font-bold text-gray-900 text-lg mb-2">{{ $item['name'] }}</h4>
                                                @if($item['color'])
                                                <p class="text-sm text-gray-600"><span class="font-medium">Warna:</span> {{ $item['color'] }}</p>
                                                @endif
                                                <p class="text-sm text-gray-600"><span class="font-medium">Ukuran:</span> {{ $item['size'] ?? '-' }}</p>
                                                <p class="text-sm text-gray-600"><span class="font-medium">Qty:</span> {{ $item['quantity'] }}</p>
                                                <p class="text-gray-900 font-semibold mt-2">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                            </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <!-- Right Column: Preview & Price Summary -->
                                <div class="space-y-6">
                                    <!-- Product Preview Image -->
                                    <div>
                                        <h3 class="text-lg font-semibold mb-4">Preview Desain</h3>
                                        <div class="bg-gray-100 rounded-lg p-4 flex items-center justify-center" style="min-height: 300px;">
                                            @if($type === 'custom')
                                                {{-- Custom Design: Show first uploaded design image --}}
                                                @php
                                                    $customOrderImage = null;
                                                    // Priority: first uploaded design > product image > placeholder
                                                    if ($order->uploads && $order->uploads->count() > 0) {
                                                        $firstUpload = $order->uploads->first();
                                                        $customOrderImage = asset('storage/' . $firstUpload->file_path);
                                                    } elseif ($order->product && !empty($order->product->image)) {
                                                        $customOrderImage = str_starts_with($order->product->image, 'http') 
                                                            ? $order->product->image 
                                                            : asset('storage/' . $order->product->image);
                                                    } elseif ($order->variant && !empty($order->variant->image)) {
                                                        $customOrderImage = str_starts_with($order->variant->image, 'http') 
                                                            ? $order->variant->image 
                                                            : asset('storage/' . $order->variant->image);
                                                    }
                                                @endphp
                                                
                                                @if($customOrderImage)
                                                    <img src="{{ $customOrderImage }}" 
                                                         alt="{{ $order->product_name }}" 
                                                         class="max-w-full max-h-[300px] object-contain rounded-lg"
                                                         onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                    <div class="text-center" style="display:none;">
                                                        <i class="fas fa-image text-gray-400 text-5xl mb-3"></i>
                                                        <p class="text-gray-500">Desain tidak dapat ditampilkan</p>
                                                    </div>
                                                @else
                                                    <div class="text-center">
                                                        <i class="fas fa-image text-gray-400 text-5xl mb-3"></i>
                                                        <p class="text-gray-500">Belum ada file desain</p>
                                                    </div>
                                                @endif
                                            @else
                                                {{-- Regular Order: Show variant image or product image --}}
                                                @php
                                                    // Order items uses hash keys, not numeric index
                                                    // Get first item using array_values to reset keys
                                                    $itemsArray = is_array($order->items) ? array_values($order->items) : [];
                                                    $firstItem = $itemsArray[0] ?? null;
                                                    $imageUrl = $firstItem['image'] ?? null;
                                                    
                                                    // Handle different image path formats
                                                    $imageSrc = null;
                                                    if ($imageUrl) {
                                                        if (str_starts_with($imageUrl, 'http://') || str_starts_with($imageUrl, 'https://')) {
                                                            $imageSrc = $imageUrl;
                                                        } else {
                                                            $imageSrc = asset('storage/' . $imageUrl);
                                                        }
                                                    }
                                                @endphp
                                                
                                                @if($imageSrc)
                                                    <img src="{{ $imageSrc }}" 
                                                         alt="{{ $firstItem['name'] ?? 'Product' }}" 
                                                         class="max-w-full max-h-[300px] object-contain rounded-lg"
                                                         onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                    <div class="text-center" style="display:none;">
                                                        <i class="fas fa-image text-gray-400 text-5xl mb-3"></i>
                                                        <p class="text-gray-500">No Image Available</p>
                                                    </div>
                                                @else
                                                    <div class="text-center">
                                                        <i class="fas fa-image text-gray-400 text-5xl mb-3"></i>
                                                        <p class="text-gray-500">No Image Available</p>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                        
                                        @if($type === 'regular' && isset($firstItem))
                                        <div class="mt-3 text-center">
                                            <p class="text-sm text-gray-600">
                                                @if(isset($firstItem['color']))
                                                    Warna: <span class="font-medium">{{ $firstItem['color'] }}</span>
                                                @endif
                                                @if(isset($firstItem['size']))
                                                    @if(isset($firstItem['color'])) | @endif
                                                    Ukuran: <span class="font-medium">{{ $firstItem['size'] }}</span>
                                                @endif
                                            </p>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Price Summary -->
                                    <div>
                                        <h3 class="text-lg font-semibold mb-4">Ringkasan Harga</h3>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <table class="w-full text-sm">
                                            <tr class="border-b border-gray-200">
                                                <td class="py-2 text-gray-600">Kategori</td>
                                                <td class="py-2 text-right font-medium">Detail</td>
                                            </tr>
                                            @if($type === 'custom')
                                            <tr class="border-b border-gray-200">
                                                <td class="py-2 text-gray-600">Biaya Produk</td>
                                                <td class="py-2 text-right">Rp {{ number_format($order->product_price, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr class="border-b border-gray-200">
                                                <td class="py-2 text-gray-600">Biaya Custom</td>
                                                <td class="py-2 text-right">Rp {{ number_format($order->total_price - $order->product_price, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr class="border-b border-gray-200">
                                                <td class="py-2 text-gray-600">Subtotal</td>
                                                <td class="py-2 text-right">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                            </tr>
                                            @else
                                            <tr class="border-b border-gray-200">
                                                <td class="py-2 text-gray-600">Biaya Produk</td>
                                                <td class="py-2 text-right">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr class="border-b border-gray-200">
                                                <td class="py-2 text-gray-600">Subtotal</td>
                                                <td class="py-2 text-right">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                            </tr>
                                            @endif
                                            <tr class="border-b border-gray-200">
                                                <td class="py-2 text-gray-600">Diskon</td>
                                                <td class="py-2 text-right">Rp 0</td>
                                            </tr>
                                            <tr class="pt-2">
                                                <td class="py-2 text-gray-900 font-bold text-base">Total</td>
                                                <td class="py-2 text-right text-gray-900 font-bold text-base">
                                                    Rp {{ $type === 'custom' ? number_format($order->total_price, 0, ',', '.') : number_format($order->total, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Reset all tab buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('border-blue-600', 'text-blue-600');
                button.classList.add('border-transparent', 'text-gray-600');
            });
            
            // Show selected tab content
            document.getElementById('content-' + tabName).classList.remove('hidden');
            
            // Highlight selected tab button
            const activeTab = document.getElementById('tab-' + tabName);
            activeTab.classList.remove('border-transparent', 'text-gray-600');
            activeTab.classList.add('border-blue-600', 'text-blue-600');
        }
        
        async function cancelThisOrder() {
            if (!confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')) {
                return;
            }
            
            try {
                const response = await fetch(`/order/{{ $type }}/{{ $order->id }}/cancel`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('Pesanan berhasil dibatalkan');
                    window.location.href = '{{ route("order-list") }}';
                } else {
                    alert(data.message || 'Gagal membatalkan pesanan');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat membatalkan pesanan');
            }
        }
    </script>

    @stack('scripts')
</x-customer-layout>
