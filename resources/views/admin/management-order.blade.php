<x-admin-layout title="Order Management">
    @push('styles')
        {{-- Menyesuaikan path CSS dengan nama file yang baru --}}
        @vite(['resources/css/admin/management-order.css'])
        
        {{-- Diperlukan untuk ikon (seperti kalender, search, export, dll.) --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @endpush

<div class="order-list-container">
    {{-- Filter dan Kontrol --}}
    <form method="GET" action="{{ route('admin.order-list') }}" class="controls-section">
        <div class="search-box">
            <i class="fas fa-search search-icon"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Pesanan" />
        </div>
        <select name="type" class="filter-select" onchange="this.form.submit()">
            <option value="regular" {{ request('type', 'regular') == 'regular' ? 'selected' : '' }}>Pesanan Reguler</option>
            <option value="custom" {{ request('type', 'regular') == 'custom' ? 'selected' : '' }}>Custom Design</option>
        </select>
        <select name="status" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Diproses</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
        </select>
        <select name="days" class="filter-select" onchange="this.form.submit()">
            <option value="7" {{ request('days', 30) == 7 ? 'selected' : '' }}>Rentang : 7 hari</option>
            <option value="30" {{ request('days', 30) == 30 ? 'selected' : '' }}>Rentang : 30 hari</option>
            <option value="60" {{ request('days', 30) == 60 ? 'selected' : '' }}>Rentang : 60 hari</option>
            <option value="90" {{ request('days', 30) == 90 ? 'selected' : '' }}>Rentang : 90 hari</option>
        </select>
    </form>

    {{-- Kartu Daftar Pesanan --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Pesanan Terbaru</h3>
            <a href="{{ route('admin.order-list.export', request()->all()) }}" class="btn btn-success">
                <i class="fas fa-file-excel"></i>
                Export Excel
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th class="checkbox-col"><input type="checkbox" id="select-all" /></th>
                            <th>PRODUK</th>
                            <th>ID PESANAN</th>
                            <th>TANGGAL</th>
                            <th>NAMA PELANGGAN</th>
                            <th>STATUS</th>
                            <th>JUMLAH</th>
                            <th>Detail</th>
                            <th class="actions-col">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td class="checkbox-col"><input type="checkbox" class="row-checkbox" /></td>
                            @if(isset($orderType) && $orderType === 'regular')
                                <td>
                                    @if($order->items && is_array($order->items) && count($order->items) > 0)
                                        @php
                                            $productNames = collect($order->items)->pluck('name')->filter()->unique()->take(2);
                                        @endphp
                                        {{ $productNames->implode(', ') }}
                                        @if(count($order->items) > 2)
                                            <small class="text-muted">+{{ count($order->items) - 2 }} item lainnya</small>
                                        @endif
                                    @else
                                        Produk Tidak Tersedia
                                    @endif
                                </td>
                            @else
                                <td>{{ $order->product_name ?? 'Produk Tidak Tersedia' }}</td>
                            @endif
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                            <td>{{ $order->user->name ?? 'Customer' }}</td>
                            <td>
                                @php
                                    $statusClass = 'status-' . strtolower(str_replace(' ', '-', $order->status));
                                @endphp
                                <span class="status {{ $statusClass }}">{{ ucfirst($order->status) }}</span>
                            </td>
                            @if(isset($orderType) && $orderType === 'regular')
                                <td>Rp.{{ $order->formatted_price }}</td>
                            @else
                                <td>Rp.{{ $order->formatted_price }}</td>
                            @endif
                            <td><a href="{{ route('admin.order.detail', ['id' => $order->id]) }}?type={{ $orderType ?? 'regular' }}" class="detail-link"><i class="fas fa-info-circle detail-icon"></i></a></td>
                            <td class="actions-col">
                                <div class="action-dropdown">
                                    <button class="action-btn"><i class="fas fa-ellipsis-v"></i></button>
                                    <div class="dropdown-content">
                                        @if($order->status === 'pending')
                                            <form method="POST" action="{{ route('admin.order.approve', ['id' => $order->id, 'type' => $orderType ?? 'regular']) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="dropdown-item" onclick="return confirm('Apakah Anda yakin ingin menyetujui pesanan ini?')">Disetujui</button>
                                            </form>
                                            <a href="#" onclick="showRejectModal({{ $order->id }}, '{{ $orderType ?? 'regular' }}')" class="dropdown-item">Ditolak</a>
                                        @else
                                            <a href="#" onclick="changeStatus({{ $order->id }}, 'processing', '{{ $orderType ?? 'regular' }}')" class="dropdown-item">Diproses</a>
                                            <a href="#" onclick="changeStatus({{ $order->id }}, 'completed', '{{ $orderType ?? 'regular' }}')" class="dropdown-item">Selesai</a>
                                            <a href="#" onclick="changeStatus({{ $order->id }}, 'cancelled', '{{ $orderType ?? 'regular' }}')" class="dropdown-item">Dibatalkan</a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" style="text-align:center;padding:40px">
                                <p style="color:#999">Belum ada pesanan</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Footer --}}
            <div class="pagination-container">
                 <span class="pagination-info">Menampilkan {{ $orders->firstItem() ?? 0 }}–{{ $orders->lastItem() ?? 0 }} dari {{ $orders->total() }} item</span>
                 <div class="pagination-controls">
                     @if($orders->onFirstPage())
                        <button class="pagination-btn pagination-btn-disabled" disabled>
                            <span>Previous</span>
                        </button>
                     @else
                        <a href="{{ $orders->previousPageUrl() }}" class="pagination-btn">
                            <span>Previous</span>
                        </a>
                     @endif
                     
                     @if($orders->hasMorePages())
                        <a href="{{ $orders->nextPageUrl() }}" class="pagination-btn">
                            <span>Next</span>
                        </a>
                     @else
                        <button class="pagination-btn pagination-btn-disabled" disabled>
                            <span>Next</span>
                        </button>
                     @endif
                 </div>
            </div>
        </div>
    </div>
</div>

    {{-- Modal untuk reject reason --}}
    <div id="rejectModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Tolak Pesanan</h3>
                <span class="close" onclick="closeRejectModal()">&times;</span>
            </div>
            <form id="rejectForm" method="POST">
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

    {{-- Modal untuk detail pesanan --}}
    <div id="orderDetailModal" class="modal" style="display: none;">
        <div class="modal-content" style="max-width: 800px;">
            <div class="modal-header">
                <h3>Detail Pesanan #<span id="detailOrderId"></span></h3>
                <span class="close" onclick="closeOrderDetailModal()">&times;</span>
            </div>
            <div class="modal-body" id="orderDetailContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>

    {{-- Di sini Anda bisa menambahkan script JS jika diperlukan nanti --}}
    @push('scripts')
        <script>
            function showRejectModal(orderId, orderType) {
                document.getElementById('rejectForm').action = `/admin/order-list/${orderId}/reject?type=${orderType}`;
                document.getElementById('rejectModal').style.display = 'block';
            }

            function closeRejectModal() {
                document.getElementById('rejectModal').style.display = 'none';
                document.getElementById('rejectReason').value = '';
            }

            function changeStatus(orderId, status, orderType) {
                if (confirm('Apakah Anda yakin ingin mengubah status pesanan ini?')) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/order-list/${orderId}/status?type=${orderType}`;

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

            async function showOrderDetail(orderId, orderType) {
                document.getElementById('detailOrderId').textContent = orderId;
                document.getElementById('orderDetailModal').style.display = 'block';

                try {
                    const response = await fetch(`/admin/order-list/${orderId}/detail?type=${orderType}`, {
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        displayOrderDetail(data);
                    } else {
                        document.getElementById('orderDetailContent').innerHTML = '<p class="text-red-500">Gagal memuat detail pesanan.</p>';
                    }
                } catch (error) {
                    console.error('Error loading order detail:', error);
                    document.getElementById('orderDetailContent').innerHTML = '<p class="text-red-500">Terjadi kesalahan saat memuat detail pesanan.</p>';
                }
            }

            function displayOrderDetail(data) {
                let html = '';

                if (data.orderType === 'regular') {
                    html = `
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <h4 class="font-semibold text-gray-900">Informasi Pesanan</h4>
                                    <p><strong>ID Pesanan:</strong> #${data.order.id}</p>
                                    <p><strong>Tanggal:</strong> ${new Date(data.order.created_at).toLocaleDateString('id-ID')}</p>
                                    <p><strong>Status:</strong> <span class="px-2 py-1 rounded text-xs font-medium ${getStatusClass(data.order.status)}">${data.order.status_label}</span></p>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Informasi Customer</h4>
                                    <p><strong>Nama:</strong> ${data.order.user.name}</p>
                                    <p><strong>Email:</strong> ${data.order.user.email}</p>
                                </div>
                            </div>

                            <div>
                                <h4 class="font-semibold text-gray-900 mb-3">Detail Produk</h4>
                                <div class="space-y-3">
                    `;

                    data.order.items.forEach(item => {
                        html += `
                            <div class="flex items-center gap-4 p-3 border border-gray-200 rounded-lg">
                                <img src="${item.image ? '/storage/' + item.image : 'https://via.placeholder.com/60'}" alt="${item.name}" class="w-12 h-12 object-cover rounded">
                                <div class="flex-1">
                                    <h5 class="font-medium text-gray-900">${item.name}</h5>
                                    <div class="text-sm text-gray-600">
                                        <span>Warna: ${item.color || 'N/A'}</span> |
                                        <span>Ukuran: ${item.size || 'N/A'}</span> |
                                        <span>Qty: ${item.quantity}</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium text-gray-900">Rp ${new Intl.NumberFormat('id-ID').format(item.price * item.quantity)}</p>
                                    <p class="text-sm text-gray-600">Rp ${new Intl.NumberFormat('id-ID').format(item.price)} x ${item.quantity}</p>
                                </div>
                            </div>
                        `;
                    });

                    html += `
                                </div>
                            </div>

                            <div class="border-t pt-4">
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold text-gray-900">Total Pesanan:</span>
                                    <span class="text-lg font-bold text-gray-900">Rp ${new Intl.NumberFormat('id-ID').format(data.order.total)}</span>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    // Custom design order detail
                    html = `
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <h4 class="font-semibold text-gray-900">Informasi Pesanan</h4>
                                    <p><strong>ID Pesanan:</strong> #${data.order.id}</p>
                                    <p><strong>Tanggal:</strong> ${new Date(data.order.created_at).toLocaleDateString('id-ID')}</p>
                                    <p><strong>Status:</strong> <span class="px-2 py-1 rounded text-xs font-medium ${getStatusClass(data.order.status)}">${data.order.status_label}</span></p>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Informasi Customer</h4>
                                    <p><strong>Nama:</strong> ${data.order.user.name}</p>
                                    <p><strong>Email:</strong> ${data.order.user.email}</p>
                                </div>
                            </div>

                            <div>
                                <h4 class="font-semibold text-gray-900 mb-3">Detail Custom Design</h4>
                                <div class="space-y-3">
                                    <div class="p-3 border border-gray-200 rounded-lg">
                                        <p><strong>Produk:</strong> ${data.order.product_name}</p>
                                        <p><strong>Jenis Potongan:</strong> ${data.order.cutting_type}</p>
                                        <p><strong>Bahan Tambahan:</strong> ${Array.isArray(data.order.special_materials) ? data.order.special_materials.join(', ') : 'Tidak ada'}</p>
                                        <p><strong>Deskripsi Tambahan:</strong> ${data.order.additional_description || 'Tidak ada'}</p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h4 class="font-semibold text-gray-900 mb-3">File Upload</h4>
                                <div class="space-y-2">
                    `;

                    if (data.uploads && data.uploads.length > 0) {
                        data.uploads.forEach(upload => {
                            html += `
                                <div class="flex items-center gap-3 p-2 border border-gray-200 rounded">
                                    <i class="fas fa-file-image text-blue-500"></i>
                                    <div>
                                        <p class="font-medium">${upload.section_name}</p>
                                        <p class="text-sm text-gray-600">${upload.file_name} (${upload.file_size} bytes)</p>
                                    </div>
                                    <a href="/storage/custom-designs/${data.order.id}/${upload.file_path.split('/').pop()}" target="_blank" class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                </div>
                            `;
                        });
                    } else {
                        html += '<p class="text-gray-500">Tidak ada file upload.</p>';
                    }

                    html += `
                                </div>
                            </div>

                            <div class="border-t pt-4">
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold text-gray-900">Total Harga:</span>
                                    <span class="text-lg font-bold text-gray-900">Rp ${new Intl.NumberFormat('id-ID').format(data.order.total_price)}</span>
                                </div>
                            </div>
                        </div>
                    `;
                }

                document.getElementById('orderDetailContent').innerHTML = html;
            }

            function getStatusClass(status) {
                const classes = {
                    'pending': 'bg-yellow-100 text-yellow-800',
                    'approved': 'bg-green-100 text-green-800',
                    'rejected': 'bg-red-100 text-red-800',
                    'processing': 'bg-blue-100 text-blue-800',
                    'completed': 'bg-emerald-100 text-emerald-800',
                    'cancelled': 'bg-gray-100 text-gray-800'
                };
                return classes[status] || 'bg-gray-100 text-gray-800';
            }

            function closeOrderDetailModal() {
                document.getElementById('orderDetailModal').style.display = 'none';
            }

            // Close modal when clicking outside
            window.onclick = function(event) {
                const rejectModal = document.getElementById('rejectModal');
                const detailModal = document.getElementById('orderDetailModal');
                if (event.target == rejectModal) {
                    closeRejectModal();
                }
                if (event.target == detailModal) {
                    closeOrderDetailModal();
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

        .dropdown-item {
            display: block;
            padding: 8px 16px;
            text-decoration: none;
            color: #333;
            border: none;
            background: none;
            cursor: pointer;
            width: 100%;
            text-align: left;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        .detail-link {
            color: inherit;
            text-decoration: none;
        }

        .detail-link:hover {
            color: inherit;
        }
    </style>
</x-admin-layout>
