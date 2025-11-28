<x-admin-layout title="Analytic Reports">
    @push('styles')
        {{-- Pastikan path ini sesuai dengan struktur proyek Anda --}}
        @vite(['resources/css/admin/analytic-reports.css'])
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @endpush

<div class="report-container">
    {{-- Header Halaman --}}
    <header class="page-header">
        <h1 class="page-title">Analytic Reports</h1>
        <div class="date-range-picker">
            <i class="far fa-calendar-alt"></i>
            <span>Oktober 16, 2025 - November 11, 2025</span>
        </div>
    </header>

    {{-- Filter Bar --}}
    <div class="filters-bar">
        <div class="tabs-container">
            <button class="tab-btn active">Penjualan</button>
            <button class="tab-btn">Pendapatan</button>
            <button class="tab-btn">Terlaris</button>
        </div>
        <div class="filter-controls">
            <input type="text" class="date-input" placeholder="dd/mm/yy">
            <input type="text" class="date-input" placeholder="dd/mm/yy">
            <select class="filter-select">
                <option>Rentang : 6 bulan</option>
                <option>Rentang : 3 bulan</option>
                <option>Rentang : 1 bulan</option>
            </select>
            <button class="btn btn-success">
                <i class="fas fa-file-excel"></i>
                Export Excel
            </button>
        </div>
    </div>

    {{-- Layout Utama Laporan --}}
    <div class="report-layout">
        
        {{-- Kolom Konten Utama (Kiri) --}}
        <main class="main-content">
            
            {{-- Grafik Penjualan --}}
            <div class="card chart-card">
                <div class="card-header">
                    <h3 class="card-title">Grafik Penjualan</h3>
                </div>
                <div class="card-body">
                    {{-- Placeholder untuk chart. Anda bisa ganti dengan canvas Chart.js --}}
                    <div class="chart-placeholder">
                        <img src="https://i.ibb.co/3s034pJ/chart-placeholder.png" alt="Grafik Penjualan">
                    </div>
                </div>
            </div>

            {{-- Grid KPI --}}
            <div class="kpi-grid">
                <div class="card kpi-card">
                    <div class="kpi-icon"><i class="fas fa-shopping-bag"></i></div>
                    <span class="kpi-title">Total Penjualan</span>
                    <span class="kpi-value">123.456</span>
                    <span class="kpi-stats positive">
                        <i class="fas fa-arrow-up"></i> 26,7%
                        <span class="kpi-comparison">Dibandingkan dengan Oktober 2025</span>
                    </span>
                </div>
                <div class="card kpi-card">
                    <div class="kpi-icon"><i class="fas fa-box-open"></i></div>
                    <span class="kpi-title">Jumlah Order</span>
                    <span class="kpi-value">123.456</span>
                    <span class="kpi-stats positive">
                        <i class="fas fa-arrow-up"></i> 26,7%
                        <span class="kpi-comparison">Dibandingkan dengan Oktober 2025</span>
                    </span>
                </div>
                <div class="card kpi-card">
                    <div class="kpi-icon"><i class="fas fa-credit-card"></i></div>
                    <span class="kpi-title">Tingkat Pembayaran</span>
                    <span class="kpi-value">123.456</span>
                    <span class="kpi-stats positive">
                        <i class="fas fa-arrow-up"></i> 26,7%
                        <span class="kpi-comparison">Dibandingkan dengan Oktober 2025</span>
                    </span>
                </div>
                <div class="card kpi-card">
                    <div class="kpi-icon"><i class="fas fa-headset"></i></div>
                    <span class="kpi-title">Resolusi Chatbot</span>
                    <span class="kpi-value">123.456</span>
                    <span class="kpi-stats positive">
                        <i class="fas fa-arrow-up"></i> 26,7%
                        <span class="kpi-comparison">Dibandingkan dengan Oktober 2025</span>
                    </span>
                </div>
            </div>

            {{-- Deskripsi Penjualan --}}
            <div class="card deskripsi-card">
                <h3 class="deskripsi-title">Deskripsi Penjualan:</h3>
                <p class="deskripsi-text">
                    Pada periode 16 Oktober - 11 November 2025, terjadi peningkatan penjualan yang signifikan dibandingkan bulan sebelumnya. Grafik penjualan menunjukkan tren naik drastis memasuki bulan November hingga Desember, yang menandakan adanya peningkatan permintaan menjelang akhir tahun. 
                    Total produk terjual mencapai 123.456 unit, mengalami kenaikan sebesar 26,7% dibandingkan dengan bulan Oktober 2025.
                </p>
            </div>
        </main>

        {{-- Sidebar Laporan (Kanan) --}}
        <aside class="right-sidebar">
            
            {{-- Produk Penjualan --}}
            <div class="card produk-penjualan-card">
                <div class="card-header">
                    <h3 class="card-title">Produk Penjualan</h3>
                </div>
                <div class="card-body">
                    <ul class="produk-list">
                        <li class="produk-item">
                            <img src="httpspre-logo.jpg" alt="Jersey" class="produk-img">
                            <div class="produk-info">
                                <span class="produk-nama">Jersey</span>
                                <span class="produk-harga">Rp 123.456</span>
                            </div>
                            <span class="produk-terjual">999 Terjual</span>
                        </li>
                        <li class="produk-item">
                            <img src="httpspre-logo.jpg" alt="Kaos" class="produk-img">
                            <div class="produk-info">
                                <span class="produk-nama">Kaos</span>
                                <span class="produk-harga">Rp 234.567</span>
                            </div>
                            <span class="produk-terjual">999 Terjual</span>
                        </li>
                        <li class="produk-item">
                            <img src="httpspre-logo.jpg" alt="Topi" class="produk-img">
                            <div class="produk-info">
                                <span class="produk-nama">Topi</span>
                                <span class="produk-harga">Rp 231.321</span>
                            </div>
                            <span class="produk-terjual">999 Terjual</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Peforma Chatbot --}}
            <div class="card peforma-chatbot-card">
                <div class="card-header">
                    <h3 class="card-title">Peforma Chatbot</h3>
                </div>
                <div class="card-body">
                    <table class="chatbot-table">
                        <thead>
                            <tr>
                                <th>Metric</th>
                                <th>Nilai</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Jumlah Percakapan</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Jumlah Pertanyaan</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Jumlah Tanggapan</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </aside>
    </div>
</div>

</x-admin-layout>