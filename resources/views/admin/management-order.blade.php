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
                            <td>{{ $order->product_name ?? 'Produk Tidak Tersedia' }}</td>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                            <td>{{ $order->user->name ?? 'Customer' }}</td>
                            <td>
                                @php
                                    $statusClass = 'status-' . strtolower(str_replace(' ', '-', $order->status));
                                @endphp
                                <span class="status {{ $statusClass }}">{{ ucfirst($order->status) }}</span>
                            </td>
                            <td>Rp.{{ number_format($order->total_price ?? 0, 0, ',', '.') }}</td>
                            <td><i class="fas fa-info-circle detail-icon"></i></td>
                            <td class="actions-col">
                                <div class="action-dropdown">
                                    <button class="action-btn"><i class="fas fa-ellipsis-v"></i></button>
                                    <div class="dropdown-content">
                                        <a href="#">Disetujui</a>
                                        <a href="#">Ditolak</a>
                                        <a href="#">Diproses</a>
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

    {{-- Di sini Anda bisa menambahkan script JS jika diperlukan nanti --}}
    @push('scripts')
        {{-- @vite(['resources/js/admin/management-order.js']) --}}
    @endpush
</x-admin-layout>