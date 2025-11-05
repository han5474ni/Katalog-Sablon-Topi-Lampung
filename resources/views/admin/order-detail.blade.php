<x-admin-layout title="Order Detail">
    @push('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        @vite(['resources/css/admin/management-order.css'])
    @endpush

    <div class="order-detail-container">
        {{-- Back Button --}}
        <div class="mb-4">
            <a href="{{ route('admin.order-list', request()->only(['search', 'type', 'status', 'days'])) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pesanan
            </a>
        </div>

        {{-- Order Detail Card --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Detail Pesanan #{{ $order->id }}</h3>
                <span class="status {{ $order->status_color }}">{{ $order->status_label }}</span>
            </div>
            <div class="card-body">
                @if($orderType === 'regular')
                    {{-- Regular Order Detail --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        {{-- Order Information --}}
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-3">Informasi Pesanan</h4>
                            <div class="space-y-2">
                                <p><strong>ID Pesanan:</strong> #{{ $order->id }}</p>
                                <p><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y, H:i') }}</p>
                                <p><strong>Status:</strong> <span class="px-2 py-1 rounded text-xs font-medium {{ $order->status_color }}">{{ $order->status_label }}</span></p>
                                @if($order->admin_notes)
                                    <p><strong>Catatan Admin:</strong> {{ $order->admin_notes }}</p>
                                @endif
                            </div>
                        </div>

                        {{-- Customer Information --}}
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-3">Informasi Customer</h4>
                            <div class="space-y-2">
                                <p><strong>Nama:</strong> {{ $order->user->name }}</p>
                                <p><strong>Email:</strong> {{ $order->user->email }}</p>
                                @if($order->user->phone)
                                    <p><strong>Telepon:</strong> {{ $order->user->phone }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Order Items --}}
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Detail Produk</h4>
                        <div class="space-y-3">
                            @foreach($order->items as $item)
                            <div class="flex items-center gap-4 p-4 border border-gray-200 rounded-lg">
                                <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : 'https://via.placeholder.com/60' }}" alt="{{ $item['name'] }}" class="w-16 h-16 object-cover rounded">
                                <div class="flex-1">
                                    <h5 class="font-medium text-gray-900">{{ $item['name'] }}</h5>
                                    <div class="text-sm text-gray-600 space-y-1">
                                        <p>Warna: {{ $item['color'] ?? 'N/A' }}</p>
                                        <p>Ukuran: {{ $item['size'] ?? 'N/A' }}</p>
                                        <p>Qty: {{ $item['quantity'] }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    @php
                                        $product = \App\Models\Product::find($item['product_id']);
                                        $formattedPrice = $product ? $product->formatted_price : number_format($item['price'], 0, ',', '.');
                                    @endphp
                                    <p class="font-medium text-gray-900">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</p>
                                    <p class="text-sm text-gray-600">Rp {{ $formattedPrice }} x {{ $item['quantity'] }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Order Summary --}}
                    <div class="border-t pt-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm text-gray-600">Subtotal</p>
                                    <p class="text-sm text-gray-600">Diskon</p>
                                    <p class="text-lg font-bold text-gray-900">Total Pesanan</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-600">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</p>
                                    <p class="text-sm text-gray-600">Rp {{ number_format($order->discount, 0, ',', '.') }}</p>
                                    <p class="text-lg font-bold text-gray-900">Rp {{ number_format((float) $order->total, 0, ',', '.') }}</p>
                                </div>
                            </div>
                    </div>
                @else
                    {{-- Custom Design Order Detail --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        {{-- Order Information --}}
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-3">Informasi Pesanan</h4>
                            <div class="space-y-2">
                                <p><strong>ID Pesanan:</strong> #{{ $order->id }}</p>
                                <p><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y, H:i') }}</p>
                                <p><strong>Status:</strong> <span class="px-2 py-1 rounded text-xs font-medium {{ $order->status_color }}">{{ $order->status_label }}</span></p>
                                @if($order->admin_notes)
                                    <p><strong>Catatan Admin:</strong> {{ $order->admin_notes }}</p>
                                @endif
                            </div>
                        </div>

                        {{-- Customer Information --}}
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-3">Informasi Customer</h4>
                            <div class="space-y-2">
                                <p><strong>Nama:</strong> {{ $order->user->name }}</p>
                                <p><strong>Email:</strong> {{ $order->user->email }}</p>
                                @if($order->user->phone)
                                    <p><strong>Telepon:</strong> {{ $order->user->phone }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Custom Design Details --}}
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Detail Custom Design</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 border border-gray-200 rounded-lg">
                            <div>
                                <p><strong>Produk:</strong> {{ $order->product_name }}</p>
                                <p><strong>Jenis Potongan:</strong> {{ $order->cutting_type }}</p>
                            </div>
                            <div>
                                <p><strong>Bahan Tambahan:</strong> {{ is_array($order->special_materials) ? implode(', ', $order->special_materials) : 'Tidak ada' }}</p>
                                <p><strong>Deskripsi Tambahan:</strong> {{ $order->additional_description ?: 'Tidak ada' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- File Uploads --}}
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-900 mb-3">File Upload</h4>
                        <div class="space-y-3">
                            @forelse($uploads as $upload)
                            <div class="flex items-center gap-4 p-3 border border-gray-200 rounded-lg">
                                <i class="fas fa-file-image text-blue-500 text-2xl"></i>
                                <div class="flex-1">
                                    <p class="font-medium">{{ $upload->section_name }}</p>
                                    <p class="text-sm text-gray-600">{{ $upload->file_name }} ({{ number_format($upload->file_size / 1024, 1) }} KB)</p>
                                </div>
                                <a href="{{ asset('storage/custom-designs/' . $order->id . '/' . basename($upload->file_path)) }}" target="_blank" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                            </div>
                            @empty
                            <p class="text-gray-500">Tidak ada file upload.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Order Summary --}}
                    <div class="border-t pt-4">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-gray-900">Total Harga:</span>
                            <span class="text-lg font-bold text-gray-900">Rp {{ number_format($order->total_price ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Action Buttons --}}
        @if($order->status === 'pending')
        <div class="mt-6 flex gap-3">
            <form method="POST" action="{{ route('admin.order.approve', ['id' => $order->id, 'type' => $orderType]) }}" class="inline">
                @csrf
                <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda yakin ingin menyetujui pesanan ini?')">
                    <i class="fas fa-check"></i> Setujui Pesanan
                </button>
            </form>
            <button type="button" class="btn btn-danger" onclick="showRejectModal()">
                <i class="fas fa-times"></i> Tolak Pesanan
            </button>
        </div>
        @elseif(in_array($order->status, ['approved', 'processing']))
        <div class="mt-6 flex gap-3">
            <button type="button" class="btn btn-info" onclick="changeStatus('processing')" @if($order->status === 'processing') disabled @endif>
                <i class="fas fa-cog"></i> Proses Pesanan
            </button>
            <button type="button" class="btn btn-success" onclick="changeStatus('completed')">
                <i class="fas fa-check-circle"></i> Selesai
            </button>
            <button type="button" class="btn btn-warning" onclick="changeStatus('cancelled')">
                <i class="fas fa-ban"></i> Batalkan
            </button>
        </div>
        @endif
    </div>

    {{-- Modal untuk reject reason --}}
    <div id="rejectModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Tolak Pesanan</h3>
                <span class="close" onclick="closeRejectModal()">&times;</span>
            </div>
            <form id="rejectForm" method="POST" action="{{ route('admin.order.reject', ['id' => $order->id, 'type' => $orderType]) }}">
                @csrf
                <div class="modal-body">
                    <label for="rejectReason">Alasan Penolakan:</label>
                    <textarea id="rejectReason" name="reason" required maxlength="500" placeholder="Masukkan alasan penolakan pesanan..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeRejectModal()" class="btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Pesanan</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function showRejectModal() {
            document.getElementById('rejectModal').style.display = 'block';
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').style.display = 'none';
            document.getElementById('rejectReason').value = '';
        }

        function changeStatus(status) {
            if (confirm('Apakah Anda yakin ingin mengubah status pesanan ini?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/order-list/${{ $order->id }}/status?type={{ $orderType }}`;

                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'PATCH';
                form.appendChild(methodField);

                const statusField = document.createElement('input');
                statusField.type = 'hidden';
                statusField.name = 'status';
                statusField.value = status;
                form.appendChild(statusField);

                const csrfField = document.createElement('input');
                csrfField.type = 'hidden';
                csrfField.name = '_token';
                csrfField.value = '{{ csrf_token() }}';
                form.appendChild(csrfField);

                document.body.appendChild(form);
                form.submit();
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('rejectModal');
            if (event.target == modal) {
                closeRejectModal();
            }
        }
    </script>
    @endpush

    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 0;
            border: 1px solid #888;
            width: 90%;
            max-width: 500px;
            border-radius: 8px;
        }

        .modal-header {
            padding: 15px 20px;
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            border-radius: 8px 8px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            margin: 0;
            color: #333;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: #000;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-body label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        .modal-body textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            resize: vertical;
            min-height: 100px;
        }

        .modal-footer {
            padding: 15px 20px;
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
            border-radius: 0 0 8px 8px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-success {
            background-color: #28a745;
            color: white;
        }

        .btn-info {
            background-color: #17a2b8;
            color: white;
        }

        .btn-warning {
            background-color: #ffc107;
            color: black;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .btn-sm {
            padding: 4px 8px;
            font-size: 12px;
        }
    </style>
</x-admin-layout>
