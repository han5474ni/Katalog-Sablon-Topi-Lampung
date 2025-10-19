<x-admin-layout title="Dashboard">
    @push('styles')
    @vite(['resources/css/admin/dashboard.css'])
    @endpush

    <div class="dashboard-content">
        <!-- Stats Cards -->
        <div class="stats-grid">
            <!-- Product Sales Card -->
            <div class="stats-card">
                <div class="flex items-start">
                    <div class="stat-icon-wrapper stat-icon-blue">
                        <i class="fas fa-box-open stat-icon"></i>
                    </div>
                    <div>
                        <p class="stat-content">Produk Terjual</p>
                        <h3 class="stat-value">123.456</h3>
                        <div class="stat-trend">
                            <span class="stat-badge-up">
                                <i class="fas fa-arrow-up"></i> 26.7%
                            </span>
                            <span class="stat-label-small">Dibandingkan dengan Oktober 2025</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revenue Card -->
            <div class="stats-card">
                <div class="flex items-start">
                    <div class="stat-icon-wrapper stat-icon-green">
                        <i class="fas fa-chart-line stat-icon"></i>
                    </div>
                    <div>
                        <p class="stat-content">Hasil Pendapatan</p>
                        <h3 class="stat-value">678.908</h3>
                        <div class="stat-trend">
                            <span class="stat-badge-up">
                                <i class="fas fa-arrow-up"></i> 33.7%
                            </span>
                            <span class="stat-label-small">Dibandingkan dengan Oktober 2025</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customers Card -->
            <div class="stats-card">
                <div class="flex items-start">
                    <div class="stat-icon-wrapper stat-icon-purple">
                        <i class="fas fa-users stat-icon"></i>
                    </div>
                    <div>
                        <p class="stat-content">Pembeli</p>
                        <h3 class="stat-value">234.654</h3>
                        <div class="stat-trend">
                            <span class="stat-badge-up">
                                <i class="fas fa-arrow-up"></i> 14.7%
                            </span>
                            <span class="stat-label-small">Dibandingkan dengan Oktober 2025</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="charts-row">
            <!-- Sales Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <h2 class="chart-title">Grafik Penjualan</h2>
                    <button class="chart-menu-btn">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                </div>
                <div class="chart-wrapper">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <!-- Top Products -->
            <div class="chart-card">
                <div class="chart-header">
                    <h2 class="chart-title">Produk Terlaris</h2>
                    <button class="chart-menu-btn">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                </div>
                <div class="product-list">
                    <!-- Product 1 -->
                    <div class="product-item">
                        <div class="product-icon-wrapper product-icon-blue">
                            <i class="fas fa-shirt product-icon icon-blue"></i>
                        </div>
                        <div class="product-info">
                            <p class="product-name">Jersey</p>
                            <p class="product-sales">999 pembelian</p>
                        </div>
                        <p class="product-price">Rp 123.456</p>
                    </div>

                    <!-- Product 2 -->
                    <div class="product-item">
                        <div class="product-icon-wrapper product-icon-green">
                            <i class="fas fa-shirt product-icon icon-green"></i>
                        </div>
                        <div class="product-info">
                            <p class="product-name">Kaos</p>
                            <p class="product-sales">999 pembelian</p>
                        </div>
                        <p class="product-price">Rp 234.567</p>
                    </div>

                    <!-- Product 3 -->
                    <div class="product-item">
                        <div class="product-icon-wrapper product-icon-yellow">
                            <i class="fas fa-hat-cowboy product-icon" style="color: #b45309;"></i>
                        </div>
                        <div class="product-info">
                            <p class="product-name">Topi</p>
                            <p class="product-sales">999 pembelian</p>
                        </div>
                        <p class="product-price">Rp 231.321</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders Table -->
        <div class="table-card">
            <div class="table-header">
                <h2 class="chart-title">Pesanan Terbaru</h2>
                <button class="chart-menu-btn">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
            </div>

            <div style="overflow-x: auto;">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th class="table-checkbox-cell">
                                <input type="checkbox">
                            </th>
                            <th class="table-th">Produk</th>
                            <th class="table-th">ID Pesanan</th>
                            <th class="table-th">Tanggal</th>
                            <th class="table-th">Nama Pelanggan</th>
                            <th class="table-th">Status</th>
                            <th class="table-th">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="table-row">
                            <td class="table-td">
                                <input type="checkbox">
                            </td>
                            <td class="table-td table-td-product">Baju</td>
                            <td class="table-td table-td-text">#23456</td>
                            <td class="table-td table-td-text">Nov 8th, 2023</td>
                            <td class="table-td table-td-customer">Hakiki</td>
                            <td class="table-td">
                                <span class="status-badge status-pending">Pengiriman</span>
                            </td>
                            <td class="table-td table-td-amount">Rp.99.999</td>
                        </tr>
                        <tr class="table-row">
                            <td class="table-td">
                                <input type="checkbox">
                            </td>
                            <td class="table-td table-td-product">Kaos</td>
                            <td class="table-td table-td-text">#34567</td>
                            <td class="table-td table-td-text">Nov 7th, 2023</td>
                            <td class="table-td table-td-customer">Blodot</td>
                            <td class="table-td">
                                <span class="status-badge status-cancelled">Dibatalkan</span>
                            </td>
                            <td class="table-td table-td-amount">Rp.99.999</td>
                        </tr>
                        <tr class="table-row">
                            <td class="table-td">
                                <input type="checkbox">
                            </td>
                            <td class="table-td table-td-product">Topi</td>
                            <td class="table-td table-td-text">#23457</td>
                            <td class="table-td table-td-text">Nov 6th, 2023</td>
                            <td class="table-td table-td-customer">Kaisar</td>
                            <td class="table-td">
                                <span class="status-badge status-pending">Pengiriman</span>
                            </td>
                            <td class="table-td table-td-amount">Rp.99.999</td>
                        </tr>
                        <tr class="table-row">
                            <td class="table-td">
                                <input type="checkbox">
                            </td>
                            <td class="table-td table-td-product">Kaos</td>
                            <td class="table-td table-td-text">#43214</td>
                            <td class="table-td table-td-text">Nov 4th, 2023</td>
                            <td class="table-td table-td-customer">Anyak</td>
                            <td class="table-td">
                                <span class="status-badge status-completed">Selesai</span>
                            </td>
                            <td class="table-td table-td-amount">Rp.99.999</td>
                        </tr>
                        <tr class="table-row">
                            <td class="table-td">
                                <input type="checkbox">
                            </td>
                            <td class="table-td table-td-product">Jersey</td>
                            <td class="table-td table-td-text">#23345</td>
                            <td class="table-td table-td-text">Nov 2nd, 2023</td>
                            <td class="table-td table-td-customer">Elisa Novia</td>
                            <td class="table-td">
                                <span class="status-badge status-pending">Pengiriman</span>
                            </td>
                            <td class="table-td table-td-amount">Rp.99.999</td>
                        </tr>
                        <tr class="table-row">
                            <td class="table-td">
                                <input type="checkbox">
                            </td>
                            <td class="table-td table-td-product">Kaos</td>
                            <td class="table-td table-td-text">#23567</td>
                            <td class="table-td table-td-text">Nov 1st, 2023</td>
                            <td class="table-td table-td-customer">Kaisar</td>
                            <td class="table-td">
                                <span class="status-badge status-completed">Selesai</span>
                            </td>
                            <td class="table-td table-td-amount">Rp.99.999</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite('resources/js/admin/dashboard-charts.js')
    @endpush
</x-admin-layout>
