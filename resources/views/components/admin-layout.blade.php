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
    @vite([
        'resources/css/admin/dashboard.css',
        'resources/css/components/notification-dropdown.css',
        'resources/js/components/notification-dropdown.js'
    ])
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
            <!-- Notification Bell for Admin -->
            <div class="notification-wrapper" style="margin-right: 20px; display: inline-flex; align-items: center;">
                <a href="#" aria-label="Notifikasi" class="action-button notification-link" id="notification-bell" style="background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); padding: 10px 12px; border-radius: 8px; position: relative; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; text-decoration: none; transition: all 0.3s ease;">
                    <i class="fas fa-bell" style="font-size: 18px; color: #fff;"></i>
                    <span class="notification-badge" id="notification-badge" style="display: none; position: absolute; top: -5px; right: -5px; background: #ef4444; color: white; font-size: 11px; padding: 2px 6px; border-radius: 10px; font-weight: bold; min-width: 18px; text-align: center;">0</span>
                </a>
                
                <!-- Notification Dropdown -->
                <div class="notification-dropdown" id="notification-dropdown" style="display: none;">
                    <div class="notification-dropdown__header">
                        <h3>Notifikasi</h3>
                        <button class="mark-all-read-btn" id="mark-all-read" style="display: none;">
                            Tandai Semua Dibaca
                        </button>
                    </div>
                    
                    <div class="notification-dropdown__body" id="notification-list">
                        <div class="notification-loading">
                            <i class="fas fa-spinner fa-spin"></i>
                            <p>Memuat notifikasi...</p>
                        </div>
                    </div>
                    
                    <div class="notification-dropdown__footer">
                        <a href="{{ route('admin.notifications.index') }}">Lihat Semua Notifikasi</a>
                    </div>
                </div>
            </div>
            
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
                    <a href="{{ route('admin.products') }}" class="sidebar__link {{ request()->routeIs('admin.products') ? 'active' : '' }}">
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
                </li>
                <li>
                    <a href="{{ route('admin.management-users') }}" class="sidebar__link {{ request()->routeIs('admin.management-users') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>User Management</span>
                    </a>
                </li>
                <li>
                </li>
                <li>
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
    {{-- notifications.js removed - using notification-dropdown.js instead --}}
    @stack('scripts')
</body>
</html>