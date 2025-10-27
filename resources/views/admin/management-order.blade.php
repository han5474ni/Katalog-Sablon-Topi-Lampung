<x-admin-layout title="Order Management">
    @push('styles')
        {{-- Menyesuaikan path CSS dengan nama file yang baru --}}
        @vite(['resources/css/admin/management-order.css'])
        
        {{-- Diperlukan untuk ikon (seperti kalender, search, export, dll.) --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @endpush

<div class="order-list-container">
    {{-- Header Halaman --}}
    <header class="page-header">
        <h1 class="page-title">Order List</h1>
        <div class="date-range-picker">
            <i class="far fa-calendar-alt"></i>
            <span>Oktober 16, 2025 - November 11, 2025</span>
        </div>
    </header>

    {{-- Filter dan Kontrol --}}
    <div class="controls-section">
        <div class="search-box">
            <i class="fas fa-search search-icon"></i>
            <input type="text" id="search-input" placeholder="Cari Pesanan" />
        </div>
        <select id="range-filter" class="filter-select">
            <option value="30">Rentang : 30 hari</option>
            <option value="7">Rentang : 7 hari</option>
            <option value="60">Rentang : 60 hari</option>
            <option value="90">Rentang : 90 hari</option>
        </select>
        <button id="export-btn" class="btn btn-success">
            <i class="fas fa-file-excel"></i>
            Export Excel
        </button>
    </div>

    {{-- Kartu Daftar Pesanan --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Pesanan Terbaru</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th class="checkbox-col"><input type="checkbox" id="select-all" /></th>
                            <th>Produk</th>
                            <th>ID Pesanan</th>
                            <th>Tanggal</th>
                            <th>Nama Pelanggan</th>
                            <th>Status</th>
                            <th>Jumlah</th>
                            <th>Detail</th>
                            <th class="actions-col">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Contoh Baris Data 1 --}}
                        <tr>
                            <td class="checkbox-col"><input type="checkbox" class="row-checkbox" /></td>
                            <td>Baju</td>
                            <td>#23456</td>
                            <td>Nov 8th, 2023</td>
                            <td>Hakiki</td>
                            <td><span class="status status-pengiriman">Pengiriman</span></td>
                            <td>Rp.99.999</td>
                            <td><i class="fas fa-info-circle detail-icon"></i></td>
                            <td class="actions-col">
                                <div class="action-dropdown">
                                    <button class="action-btn"><i class="fas fa-ellipsis-v"></i></button>
                                </div>
                            </td>
                        </tr>
                        {{-- Contoh Baris Data 2 --}}
                        <tr>
                            <td class="checkbox-col"><input type="checkbox" class="row-checkbox" /></td>
                            <td>Kaos</td>
                            <td>#34567</td>
                            <td>Nov 7th, 2023</td>
                            <td>Blodot</td>
                            <td><span class="status status-dibatalkan">Dibatalkan</span></td>
                            <td>Rp.99.999</td>
                            <td><i class="fas fa-info-circle detail-icon"></i></td>
                            <td class="actions-col">
                                <div class="action-dropdown">
                                    <button class="action-btn"><i class="fas fa-ellipsis-v"></i></button>
                                </div>
                            </td>
                        </tr>
                         {{-- Contoh Baris Data 3 dengan dropdown kompleks --}}
                        <tr>
                            <td class="checkbox-col"><input type="checkbox" class="row-checkbox" /></td>
                            <td>Baju</td>
                            <td>#12345</td>
                            <td>Nov 5th, 2023</td>
                            <td>handayani</td>
                            <td><span class="status status-dibatalkan">Dibatalkan</span></td>
                            <td>Rp.99.999</td>
                            <td><i class="fas fa-info-circle detail-icon"></i></td>
                            <td class="actions-col">
                                <div class="action-dropdown">
                                    <button class="action-btn"><i class="fas fa-ellipsis-v"></i></button>
                                    <div class="dropdown-content">
                                        <a href="#">Disetujui</a>
                                        <div class="dropdown-submenu">
                                            <a href="#" class="submenu-trigger">
                                                Ditolak
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
                                            <div class="submenu-content">
                                                 <div class="submenu-header">Ditolak</div>
                                                 <a href="#">- Gambar tidak PNG</a>
                                                 <a href="#">- Gambar tidak jelas</a>
                                                 <a href="#">- Gambar tidak bisa dibaca</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                         {{-- Contoh Baris Data 4 --}}
                        <tr>
                            <td class="checkbox-col"><input type="checkbox" class="row-checkbox" /></td>
                            <td>Kaos</td>
                            <td>#43214</td>
                            <td>Nov 4th, 2023</td>
                            <td>Anyak</td>
                            <td><span class="status status-pengiriman">Pengiriman</span></td>
                            <td>Rp.99.999</td>
                            <td><i class="fas fa-info-circle detail-icon"></i></td>
                           <td class="actions-col">
                                <div class="action-dropdown">
                                    <button class="action-btn"><i class="fas fa-ellipsis-v"></i></button>
                                </div>
                            </td>
                        </tr>
                        {{-- Contoh Baris Data 5 --}}
                        <tr>
                            <td class="checkbox-col"><input type="checkbox" class="row-checkbox" /></td>
                            <td>Topi</td>
                            <td>#22345</td>
                            <td>Nov 2nd, 2023</td>
                            <td>Elsa Novi</td>
                            <td><span class="status status-dibatalkan">Dibatalkan</span></td>
                            <td>Rp.99.999</td>
                            <td><i class="fas fa-info-circle detail-icon"></i></td>
                            <td class="actions-col">
                                <div class="action-dropdown">
                                    <button class="action-btn"><i class="fas fa-ellipsis-v"></i></button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Pagination Footer --}}
            <div class="pagination-container">
                 <span class="pagination-info">Menampilkan 15 dari 80 item</span>
                 <div class="pagination-controls">
                     <span>Halaman</span>
                     <select id="page-size-select">
                         <option value="10">10</option>
                         <option value="25" selected>25</option>
                         <option value="50">50</option>
                         <option value="100">100</option>
                     </select>
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