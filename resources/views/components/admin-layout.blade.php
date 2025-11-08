<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>{{ $title ?? 'Admin Dashboard' }} - LGI STORE</title>

    <!-- CSS Files -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    @vite(['resources/css/admin/dashboard.css'])
    @stack('styles')
</head>
<body>
    <!-- Sidebar Toggle Button (Mobile) -->
    <button class="sidebar-toggle" aria-label="Toggle Sidebar" aria-expanded="false">
        <i class="fas fa-bars"></i>
    </button>

    <!-- TOP NAVBAR (Above everything) -->
    <div class="top-navbar">
        <div class="top-navbar__left">
            <div class="top-navbar__logo-container">
                <div class="top-navbar__logo">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="top-navbar__brand">
                    <h1 class="top-navbar__title">LGI STORE</h1>
                    <p class="top-navbar__subtitle">Produk Eksklusif Kaos Berkualitas</p>
                </div>
            </div>
        </div>
        <div class="top-navbar__right">
            <div class="admin-dropdown">
                <button class="admin-dropdown__btn" onclick="toggleAdminDropdown()">
                    @if(auth('admin')->user()->avatar)
                        <img src="{{ asset('storage/' . auth('admin')->user()->avatar) }}" alt="Avatar" class="admin-avatar">
                    @else
                        <div class="admin-avatar-placeholder">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                    <div class="admin-info">
                        <span class="admin-role">{{ auth('admin')->user()->role_name }}</span>
                        <span class="admin-name">{{ auth('admin')->user()->name }}</span>
                    </div>
                    <i class="fas fa-chevron-down admin-dropdown__icon"></i>
                </button>
                <div class="admin-dropdown__menu" id="adminDropdownMenu">
                    <a href="{{ route('admin.profile') }}" class="admin-dropdown__item">
                        <i class="fas fa-user-circle"></i>
                        <span>My Profile</span>
                    </a>
                    <form method="POST" action="{{ route('admin.logout') }}" class="admin-dropdown__form">
                        @csrf
                        <button type="submit" class="admin-dropdown__item admin-dropdown__item--danger">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-container">
        <!-- SIDEBAR -->
        <nav class="sidebar">
            <div class="sidebar__header">
                <!-- Logo moved to top navbar -->
            </div>

            <ul class="sidebar__menu">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar__link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.all-products') }}" class="sidebar__link {{ request()->routeIs('admin.all-products') ? 'active' : '' }}">
                        <i class="fas fa-box-open"></i>
                        <span>All Products</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.order-list') }}" class="sidebar__link {{ request()->routeIs('admin.order-list') ? 'active' : '' }}">
                        <i class="fas fa-list"></i>
                        <span>Order List</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.chatbot') }}" class="sidebar__link {{ request()->routeIs('admin.chatbot') ? 'active' : '' }}">
                        <i class="fas fa-comment"></i>
                        <span>Chatbot</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.management-users') }}" class="sidebar__link {{ request()->routeIs('admin.management-users') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>User Management</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.finance.index') }}" class="sidebar__link {{ request()->routeIs('admin.finance.*') ? 'active' : '' }}">
                        <i class="fas fa-wallet"></i>
                        <span>Finance & Wallet</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="sidebar__link">
                        <i class="fas fa-chart-bar"></i>
                        <span>Analytic Reports</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.history') }}" class="sidebar__link {{ request()->routeIs('admin.history') ? 'active' : '' }}">
                        <i class="fas fa-history"></i>
                        <span>History</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.management-product') }}" class="sidebar__link {{ request()->routeIs('admin.management-product') ? 'active' : '' }}">
                        <i class="fas fa-cube"></i>
                        <span>Product Management</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.custom-design-prices') }}" class="sidebar__link {{ request()->routeIs('admin.custom-design-prices') ? 'active' : '' }}">
                        <i class="fas fa-palette"></i>
                        <span>Custom Design Prices</span>
                    </a>
                </li>
            </ul>

            <!-- Sidebar Footer -->
            <div class="sidebar__footer">
                <form method="POST" action="{{ route('admin.logout') }}" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Log Out</span>
                    </button>
                </form>
            </div>
        </nav>

        <!-- MAIN CONTENT -->
        <main class="main-content">
            <!-- PAGE HEADER -->
            <div class="page-header">
                <div class="page-header__left">
                    <!-- Breadcrumb -->
                    <nav class="breadcrumb">
                        <a href="{{ route('home') }}" class="breadcrumb__item breadcrumb__item--link">
                            <i class="fas fa-home"></i>
                            <span>Home</span>
                        </a>
                        <i class="fas fa-chevron-right breadcrumb__separator"></i>
                        <span class="breadcrumb__item breadcrumb__item--active">{{ $title ?? 'Dashboard' }}</span>
                    </nav>
                </div>
                <div class="page-header__right">
                    <!-- Notification Bell -->
                    <div class="notification-bell">
                        <button class="notification-bell__btn" onclick="toggleNotificationDropdown()">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge">3</span>
                        </button>
                        <div class="notification-dropdown" id="notificationDropdown">
                            <div class="notification-dropdown__header">
                                <h3>Notifikasi</h3>
                                <span class="notification-count">3 baru</span>
                            </div>
                            <div class="notification-dropdown__list">
                                <a href="#" class="notification-item notification-item--unread">
                                    <div class="notification-icon notification-icon--order">
                                        <i class="fas fa-shopping-cart"></i>
                                    </div>
                                    <div class="notification-content">
                                        <p class="notification-title">Pesanan Baru</p>
                                        <p class="notification-text">Ada 2 pesanan baru masuk</p>
                                        <span class="notification-time">5 menit yang lalu</span>
                                    </div>
                                </a>
                                <a href="#" class="notification-item notification-item--unread">
                                    <div class="notification-icon notification-icon--product">
                                        <i class="fas fa-box"></i>
                                    </div>
                                    <div class="notification-content">
                                        <p class="notification-title">Stok Produk Menipis</p>
                                        <p class="notification-text">Jersey Hitam tinggal 5 item</p>
                                        <span class="notification-time">1 jam yang lalu</span>
                                    </div>
                                </a>
                                <a href="#" class="notification-item notification-item--unread">
                                    <div class="notification-icon notification-icon--user">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="notification-content">
                                        <p class="notification-title">Customer Baru</p>
                                        <p class="notification-text">3 customer baru mendaftar</p>
                                        <span class="notification-time">2 jam yang lalu</span>
                                    </div>
                                </a>
                            </div>
                            <a href="#" class="notification-dropdown__footer">
                                Lihat Semua Notifikasi
                            </a>
                        </div>
                    </div>
                    
                    <div class="page-header__date-range">
                        <i class="fas fa-calendar"></i>
                        <span>{{ now()->format('F d, Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- PAGE CONTENT -->
            <div class="page-content">
                {{ $slot }}
            </div>
        </main>
    </div>

    @vite('resources/js/admin/layout.js')
    @stack('scripts')
</body>
</html>