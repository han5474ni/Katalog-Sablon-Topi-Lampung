<?php
    // Variabel PHP untuk data dinamis
    $page_title = "Admin Dashboard - LGI STORE";
    
    // Set locale ke Indonesia untuk format tanggal
    setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'Indonesian');

    // Menghasilkan rentang tanggal dinamis (misal: 1 bulan terakhir)
    $end_date = time();
    $start_date = strtotime("-1 month", $end_date);
    $date_range_display = strftime('%B %d, %Y', $start_date) . ' - ' . strftime('%B %d, %Y', $end_date);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* --- CSS Starts Here --- */
        :root {
            --primary-color: #4361EE;
            --secondary-color: #0A1D37;
            --background-color: #F1F5F9;
            --card-background: #FFFFFF;
            --text-primary: #1E293B;
            --text-secondary: #64748B;
            --sidebar-text: #CBD5E1;
            --border-color: #E2E8F0;
            --success-color: #10B981;
            --warning-color: #F59E0B;
            --danger-color: #EF4444;

            --sidebar-width: 260px;
            --border-radius: 8px;
            --box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--background-color);
            color: var(--text-primary);
        }

        .dashboard-container {
            display: flex;
        }

        /* --- Sidebar --- */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--secondary-color);
            color: var(--sidebar-text);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
        }

        .sidebar__header {
            padding: 1.5rem;
            border-bottom: 1px solid #1e293b;
        }

        .sidebar__logo {
            width: 120px;
        }

        .sidebar__menu {
            flex-grow: 1;
            list-style: none;
            padding: 1rem 0;
        }

        .sidebar__link {
            display: flex;
            align-items: center;
            padding: 0.9rem 1.5rem;
            color: var(--sidebar-text);
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.3s, color 0.3s;
        }

        .sidebar__link i {
            width: 20px;
            margin-right: 1rem;
            font-size: 1.1rem;
        }

        .sidebar__link:hover, .sidebar__link.active {
            background-color: var(--primary-color);
            color: white;
        }

        .sidebar__footer {
            padding: 1.5rem;
            border-top: 1px solid #1e293b;
        }

        /* --- Main Content --- */
        .main-content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            padding: 1.5rem;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: var(--card-background);
            padding: 1rem 1.5rem;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 1.5rem;
        }

        .header__title {
            font-size: 1.5rem;
        }

        .header__actions {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .date-range {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
        }

        .user-dropdown__button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0.6rem 1rem;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-family: inherit;
            font-weight: 500;
        }

        /* General Card Styling */
        .card {
            background-color: var(--card-background);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 1.5rem;
        }

        .card__header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .card__header h3 {
            font-size: 1.1rem;
        }

        .card__options {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-secondary);
            font-size: 1rem;
        }

        /* Content Body Grids */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .main-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        /* Stat Card */
        .stat-card {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stat-card__icon {
            font-size: 1.5rem;
            padding: 1rem;
            border-radius: 50%;
            background-color: #e0e7ff;
            color: var(--primary-color);
        }

        .stat-card__info {
            display: flex;
            flex-direction: column;
        }

        .stat-card__title {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .stat-card__value {
            font-size: 1.75rem;
            font-weight: 600;
        }

        .stat-card__growth {
            font-size: 0.8rem;
            font-weight: 500;
        }

        .stat-card__growth.increase { color: var(--success-color); }
        .stat-card__growth.decrease { color: var(--danger-color); }

        /* Chart Card */
        .chart-container img {
            width: 100%;
            height: auto;
        }

        /* Top Products Card */
        .top-products-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .top-products-list li {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .top-products-list img {
            width: 40px;
            height: 40px;
            border-radius: var(--border-radius);
        }

        .product-info {
            flex-grow: 1;
        }
        .product-info span { display: block; font-weight: 500; }
        .product-info small { color: var(--text-secondary); }

        .product-sales {
            font-weight: 600;
        }

        /* Recent Orders Table */
        .orders-table {
            width: 100%;
            border-collapse: collapse;
        }

        .orders-table th, .orders-table td {
            padding: 0.8rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.9rem;
        }

        .orders-table th {
            color: var(--text-secondary);
            font-weight: 500;
        }

        .status {
            padding: 0.25rem 0.6rem;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 500;
            display: inline-block;
        }
        .status::before {
            content: '•';
            margin-right: 0.4rem;
        }

        .status--pengiriman {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status--dibatalkan {
            background-color: #fef3c7;
            color: #92400e;
        }
        /* --- CSS Ends Here --- */
    </style>
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
                <a href="#" class="sidebar__link"><i class="fa-solid fa-right-from-bracket"></i><span>Log Out</span></a>
            </div>
        </nav>

        <main class="main-content">
            <header class="header">
                <h1 class="header__title">Dashboard</h1>
                <div class="header__actions">
                    <div class="date-range">
                        <i class="fa-solid fa-calendar-days"></i>
                        <span><?= htmlspecialchars($date_range_display) ?></span>
                    </div>
                    <div class="user-dropdown">
                        <button class="user-dropdown__button">
                            Admin <i class="fa-solid fa-chevron-down"></i>
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

    <script>
        // --- JavaScript Starts Here ---
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarLinks = document.querySelectorAll('.sidebar__link');

            sidebarLinks.forEach(link => {
                link.addEventListener('click', function(event) {
                    // Cek jika yang diklik bukan link logout
                    if (!this.parentElement.parentElement.classList.contains('sidebar__footer')) {
                        event.preventDefault(); // Mencegah pindah halaman untuk demo
                        
                        // Hapus class 'active' dari semua link
                        sidebarLinks.forEach(l => l.classList.remove('active'));
                        
                        // Tambahkan class 'active' ke link yang diklik
                        this.classList.add('active');
                    }
                });
            });
        });
        // --- JavaScript Ends Here ---
    </script>
</body>
</html>