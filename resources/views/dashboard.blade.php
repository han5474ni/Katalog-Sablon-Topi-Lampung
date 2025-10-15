<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Admin Dashboard - LGI STORE</title>
    <link rel="stylesheet" href="{{ asset('dashboard/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard-container">
        <nav class="sidebar">
            <div class="sidebar__header">
                <img src="https://placehold.co/150x40/FFFFFF/0A1D37?text=LGI+STORE" alt="Logo LGI Store" class="sidebar__logo">
            </div>
            <ul class="sidebar__menu">
                <li><a href="#" class="sidebar__link active"><i class="fa-solid fa-tachometer-alt"></i><span>Dashboard</span></a></li>
                <li><a href="#" class="sidebar__link"><i class="fa-solid fa-box-archive"></i><span>All Products</span></a></li>
                <li><a href="#" class="sidebar__link"><i class="fa-solid fa-list-ul"></i><span>Order List</span></a></li>
                <li><a href="#" class="sidebar__link"><i class="fa-solid fa-comments"></i><span>Chatbot</span></a></li>
                <li><a href="#" class="sidebar__link"><i class="fa-solid fa-users"></i><span>User Management</span></a></li>
                <li><a href="#" class="sidebar__link"><i class="fa-solid fa-chart-line"></i><span>Analytic Reports</span></a></li>
                <li><a href="#" class="sidebar__link"><i class="fa-solid fa-clock-rotate-left"></i><span>History</span></a></li>
                <li><a href="#" class="sidebar__link"><i class="fa-solid fa-cog"></i><span>Product Management</span></a></li>
                <li><a href="#" class="sidebar__link"><i class="fa-solid fa-wallet"></i><span>Finance and Wallet</span></a></li>
            </ul>
            <div class="sidebar__footer">
                <form method="POST" action="{{ route('admin.logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </button>
            </form>
            </div>
        </nav>

        <main class="main-content">
            <header class="header">
                <h1 class="header__title">Dashboard</h1>
                <div class="header__actions">
                    <div class="date-range">
                        <i class="fa-solid fa-calendar-days"></i>
                        <span>{{ now()->format('F d, Y') }} - {{ now()->addDays(25)->format('F d, Y') }}</span>
                    </div>
                    <div class="user-dropdown">
                        <button class="user-dropdown__button">
                            {{ Auth::user()->name ?? 'Admin' }} <i class="fa-solid fa-chevron-down"></i>
                        </button>
                    </div>
                </div>
            </header>

            <div class="content-body">
                <div class="stats-grid">
                    <div class="card stat-card">
                        <div class="stat-card__icon"><i class="fa-solid fa-box"></i></div>
                        <div class="stat-card__info">
                            <span class="stat-card__title">Produk Terjual</span>
                            <span class="stat-card__value">123.456</span>
                            <span class="stat-card__growth increase"><i class="fa-solid fa-arrow-up"></i> 26.7%</span>
                        </div>
                    </div>
                    <div class="card stat-card">
                        <div class="stat-card__icon"><i class="fa-solid fa-wallet"></i></div>
                        <div class="stat-card__info">
                            <span class="stat-card__title">Hasil Pendapatan</span>
                            <span class="stat-card__value">678.908</span>
                            <span class="stat-card__growth increase"><i class="fa-solid fa-arrow-up"></i> 33.7%</span>
                        </div>
                    </div>
                    <div class="card stat-card">
                        <div class="stat-card__icon"><i class="fa-solid fa-users"></i></div>
                        <div class="stat-card__info">
                            <span class="stat-card__title">Pembeli</span>
                            <span class="stat-card__value">234.654</span>
                            <span class="stat-card__growth increase"><i class="fa-solid fa-arrow-up"></i> 14.7%</span>
                        </div>
                    </div>
                </div>

                <div class="main-grid">
                    <div class="card chart-card">
                        <div class="card__header">
                            <h3>Grafik Penjualan</h3>
                            <button class="card__options"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                        </div>
                        <div class="chart-container">
                            <img src="https://placehold.co/600x250/FFFFFF/E0E0E0?text=Chart.js+Goes+Here" alt="Sales Chart Placeholder">
                        </div>
                    </div>
                    <div class="card top-products-card">
                         <div class="card__header">
                            <h3>Terlaris</h3>
                            <button class="card__options"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                        </div>
                        <ul class="top-products-list">
                            <li>
                                <img src="https://placehold.co/40x40/EFEFEF/333?text=J" alt="Jersey">
                                <div class="product-info">
                                    <span>Jersey</span>
                                    <small>999 pembelian</small>
                                </div>
                                <span class="product-sales">Rp 123.456</span>
                            </li>
                            <li>
                                <img src="https://placehold.co/40x40/EFEFEF/333?text=K" alt="Kaos">
                                <div class="product-info">
                                    <span>Kaos</span>
                                    <small>999 pembelian</small>
                                </div>
                                <span class="product-sales">Rp 234.567</span>
                            </li>
                             <li>
                                <img src="https://placehold.co/40x40/EFEFEF/333?text=T" alt="Topi">
                                <div class="product-info">
                                    <span>Topi</span>
                                    <small>999 pembelian</small>
                                </div>
                                <span class="product-sales">Rp 231.321</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card recent-orders-card">
                    <div class="card__header">
                        <h3>Pesanan Terbaru</h3>
                        <button class="card__options"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                    </div>
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th><input type="checkbox"></th>
                                <th>Produk</th>
                                <th>ID Pesanan</th>
                                <th>Tanggal</th>
                                <th>Nama Pelanggan</th>
                                <th>Status</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="checkbox"></td>
                                <td>Baju</td>
                                <td>#23456</td>
                                <td>Nov 8th, 2023</td>
                                <td>Hakiki</td>
                                <td><span class="status status--pengiriman">Pengiriman</span></td>
                                <td>Rp.99.999</td>
                            </tr>
                            <tr>
                                <td><input type="checkbox"></td>
                                <td>Kaos</td>
                                <td>#34567</td>
                                <td>Nov 7th, 2023</td>
                                <td>Blodot</td>
                                <td><span class="status status--dibatalkan">Dibatalkan</span></td>
                                <td>Rp.99.999</td>
                            </tr>
                            <tr>
                                <td><input type="checkbox"></td>
                                <td>Topi</td>
                                <td>#23457</td>
                                <td>Nov 6th, 2023</td>
                                <td>Kaisar</td>
                                <td><span class="status status--pengiriman">Pengiriman</span></td>
                                <td>Rp.99.999</td>
                            </tr>
                            <tr>
                                <td><input type="checkbox"></td>
                                <td>Kaos</td>
                                <td>#43214</td>
                                <td>Nov 4th, 2023</td>
                                <td>Anyak</td>
                                <td><span class="status status--pengiriman">Pengiriman</span></td>
                                <td>Rp.99.999</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
    <script src="{{ asset('dashboard/script.js') }}"></script>
</body>
</html>