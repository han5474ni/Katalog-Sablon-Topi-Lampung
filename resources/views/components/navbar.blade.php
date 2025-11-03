<!-- Navbar Component -->
@php
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Storage;

    $currentRoute = Route::currentRouteName();
    $currentCategory = $currentRoute === 'catalog' ? Route::current()->parameter('category') : null;
    $currentUser = auth()->user() ?? auth('admin')->user();
    $userAvatar = null; // Force using icon across all pages
    $isAdmin = auth('admin')->check();
@endphp

<div class="navbar-wrapper">
    <div class="top-bar">
        <div class="top-bar-inner">
            <div class="top-bar-message">
                Lebih banyak item + promo user baru ➜
            </div>
            <div class="top-bar-actions">
                <a href="#" class="top-link"><i class="fas fa-info-circle"></i> <span>Tentang LGI</span></a>
                <a href="#" class="top-link"><i class="fas fa-question-circle"></i> <span>Kontak</span></a>

                @if($currentUser)
                    <span class="top-user">Hi, {{ \Illuminate\Support\Str::limit($currentUser->name, 18) }}</span>
                    <a href="{{ $isAdmin ? route('admin.dashboard') : route('dashboard') }}" class="top-btn top-btn-outline">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="top-btn top-btn-outline">Masuk</a>
                    <a href="{{ route('register') }}" class="top-btn top-btn-filled">Daftar</a>
                @endif
            </div>
        </div>
    </div>

    <header class="main-navbar">
        <div class="main-navbar-inner">
            <a href="{{ route('home') }}" class="brand" aria-label="LGI Store">
                <div class="brand-logo">L</div>
                <div class="brand-text">
                    <span class="brand-name">LGI STORE</span>
                    <span class="brand-tagline">Peduli Kualitas, Bukan Kuantitas</span>
                </div>
            </a>

            <div class="navbar-center">
                <div class="search-area">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-bar" id="search-input" placeholder="Cari produk..." value="{{ request('search') }}">
                </div>
                <div class="category-strip">
                    <nav class="category-nav" aria-label="Kategori Produk">
                        <a href="{{ route('all-products') }}" class="nav-link {{ $currentRoute === 'all-products' ? 'active' : '' }}">Semua Produk</a>
                        <a href="{{ route('catalog', 'jersey') }}" class="nav-link {{ $currentCategory === 'jersey' ? 'active' : '' }}">Jersey</a>
                        <a href="{{ route('catalog', 'topi') }}" class="nav-link {{ $currentCategory === 'topi' ? 'active' : '' }}">Topi</a>
                        <a href="{{ route('catalog', 'kaos') }}" class="nav-link {{ $currentCategory === 'kaos' ? 'active' : '' }}">Kaos</a>
                        <a href="{{ route('catalog', 'polo') }}" class="nav-link {{ $currentCategory === 'polo' ? 'active' : '' }}">Polo</a>
                        <a href="{{ route('catalog', 'celana') }}" class="nav-link {{ $currentCategory === 'celana' ? 'active' : '' }}">Celana</a>
                        <a href="{{ route('catalog', 'jaket') }}" class="nav-link {{ $currentCategory === 'jaket' ? 'active' : '' }}">Jaket</a>
                    </nav>
                </div>
            </div>

            <div class="header-actions">
                @if(!$isAdmin && auth()->check())
                    <a href="{{ route('keranjang') }}" aria-label="Buka Keranjang" class="action-button">
                        <i class="fas fa-shopping-cart"></i>
                    </a>
                @endif

                <a href="#" aria-label="Notifikasi" class="action-button notification-link">
                    <i class="fas fa-bell"></i>
                </a>

                <i id="profile-icon" class="fas fa-user-circle profile-icon-btn" aria-label="Profil"></i>
            </div>
        </div>
    </header>

    @if($currentUser)
        <div class="profile-popup" id="profile-popup" data-auth="true">
            <div class="profile-popup-content">
                <div class="profile-header">
                    <div class="profile-avatar" aria-hidden="true"><i class="fas fa-user"></i></div>
                    <div class="profile-info">
                        <span class="profile-name">{{ $currentUser->name }}</span>
                        <span class="profile-email">{{ $currentUser->email }}</span>
                    </div>
                </div>
                <div class="profile-menu">
                    @if($isAdmin)
                        <a href="{{ route('admin.dashboard') }}" class="profile-menu-item">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard Admin</span>
                        </a>
                        <form method="POST" action="{{ route('admin.logout') }}" class="profile-logout-form">
                            @csrf
                            <button type="submit">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Keluar</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('dashboard') }}" class="profile-menu-item">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('profile') }}" class="profile-menu-item">
                            <i class="fas fa-user"></i>
                            <span>Profil Saya</span>
                        </a>
                        <a href="{{ route('order-list') }}" class="profile-menu-item">
                            <i class="fas fa-box"></i>
                            <span>Pesanan Saya</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="profile-logout-form">
                            @csrf
                            <button type="submit">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Keluar</span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .navbar-wrapper {
        position: sticky;
        top: 0;
        z-index: 100;
        width: 100%;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .top-bar {
        background-color: #ffc727;
        color: #061434;
        font-weight: 500;
        padding: 10px 24px;
    }

    .top-bar-inner {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
    }

    .top-bar-message {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }

    .top-bar-actions {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .top-link {
        color: #061434;
        text-decoration: none;
        font-size: 14px;
    }

    .top-link i {
        margin-right: 6px;
    }

    .top-link:hover {
        text-decoration: underline;
    }

    .top-link-strong {
        color: inherit;
        text-decoration: none;
    }

    .top-link-strong:hover {
        text-decoration: underline;
    }

    .top-user {
        font-size: 14px;
        font-weight: 600;
        color: #061434;
    }

    .top-btn {
        padding: 6px 16px;
        border-radius: 999px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .top-btn-outline {
        border: 1px solid #061434;
        color: #061434;
        background: transparent;
    }

    .top-btn-outline:hover {
        background: rgba(6, 20, 52, 0.1);
    }

    .top-btn-filled {
        background: #061434;
        color: #ffc727;
        border: 1px solid #061434;
    }

    .top-btn-filled:hover {
        background: #0a1d42;
    }

    .main-navbar {
        background-color: #061434;
        color: #ffffff;
        padding: 16px 24px;
    }

    .main-navbar-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 32px;
        flex-wrap: wrap;
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding-left: 0;
    }

    .navbar-center {
        flex: 1 1 720px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 5px;
        min-width: 360px;
    }

    .brand {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        margin-right: auto;
        margin-left: -96px;
    }

    .brand-logo {
        width: 50px;
        height: 50px;
        background-color: #ffffff;
        color: #061434;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 20px;
    }

    .brand-text {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .brand-name {
        color: #ffc727;
        font-size: 18px;
        font-weight: 700;
    }

    .brand-tagline {
        color: rgba(255, 255, 255, 0.7);
        font-size: 10px;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .search-area {
        position: relative;
        width: 100%;
        max-width: 760px;
        min-width: 320px;
    }

    .search-bar {
        width: 100%;
        background-color: #f5f5f5;
        border-radius: 999px;
        border: none;
        padding: 10px 18px 10px 42px;
        color: #061434;
        font-size: 14px;
    }

    .search-bar:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(255, 199, 39, 0.35);
    }

    .search-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #8a8a8a;
        font-size: 14px;
    }

    .header-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
        flex-shrink: 0;
        margin-left: auto;
        padding-right: 0;
        height: 40px;
    }

    .action-button {
        color: #ffffff;
        font-size: 18px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.08);
        transition: background-color 0.2s ease, color 0.2s ease, transform 0.2s ease;
    }

    .action-button:hover {
        color: #ffc727;
        background-color: rgba(255, 199, 39, 0.15);
        transform: translateY(-2px);
    }

    .profile-icon-btn {
        font-size: 22px;
        color: #ffffff;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(255, 255, 255, 0.08);
        cursor: pointer;
        transition: background-color 0.2s ease, color 0.2s ease, transform 0.2s ease;
    }

    .profile-icon-btn:hover {
        color: #ffc727;
        background-color: rgba(255, 199, 39, 0.15);
        transform: translateY(-2px);
    }

    .profile-avatar-header {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #ffc727;
        cursor: pointer;
        background-color: rgba(255, 255, 255, 0.08);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .profile-avatar-header:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.25);
    }

    .category-strip {
        width: 100%;
        display: flex;
        justify-content: center;
        padding-top: 8px;
    }

    .category-nav {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 40px;
        flex-wrap: wrap;
    }

    .category-nav .nav-link {
        color: #dcdcdc;
        font-weight: 500;
        font-size: 14px;
        text-decoration: none;
        padding: 6px 0;
        transition: color 0.2s ease;
    }

    .category-nav .nav-link:hover,
    .category-nav .nav-link.active {
        color: #ffc727 !important;
    }

    .profile-popup {
        position: fixed;
        top: 110px;
        right: 24px;
        background-color: #0f1f46;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
        min-width: 280px;
        display: none;
        z-index: 120;
    }

    .profile-popup.show {
        display: block;
    }

    .profile-popup-content {
        padding: 20px;
    }

    .profile-header {
        display: flex;
        gap: 12px;
        padding-bottom: 16px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .profile-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background-color: #ffc727;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 18px;
        color: #061434;
    }

    .profile-avatar-img {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        object-fit: cover;
    }

    .profile-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .profile-name {
        color: #ffffff;
        font-weight: 600;
        font-size: 14px;
    }

    .profile-email {
        color: rgba(255, 255, 255, 0.65);
        font-size: 12px;
    }

    .profile-menu {
        padding-top: 12px;
        display: flex;
        flex-direction: column;
    }

    .profile-menu-item,
    .profile-logout-form button {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        color: #dcdcdc;
        text-decoration: none;
        font-size: 13px;
        border: none;
        background: transparent;
        cursor: pointer;
        transition: background 0.2s ease, color 0.2s ease;
        width: 100%;
        text-align: left;
    }

    .profile-menu-item i,
    .profile-logout-form button i {
        width: 18px;
    }

    .profile-menu-item:hover,
    .profile-logout-form button:hover {
        background-color: rgba(255, 199, 39, 0.1);
        color: #ffc727;
    }

    .profile-logout-form {
        margin: 0;
        width: 100%;
    }

    @media (max-width: 992px) {
        .top-bar-actions {
            justify-content: flex-start;
        }

        .main-navbar-inner {
            flex-direction: column;
            align-items: stretch;
        }

        .header-actions {
            justify-content: flex-end;
        }
    }

    @media (max-width: 600px) {
        .top-bar {
            padding: 10px 16px;
        }

        .main-navbar {
            padding: 16px;
        }

        .category-bar {
            padding: 8px 16px;
        }
    }
</style>

<script>
    document.getElementById('profile-icon')?.addEventListener('click', function(e) {
        e.stopPropagation();
        const popup = document.getElementById('profile-popup');
        if (popup) {
            popup.classList.toggle('show');
        }
    });

    document.addEventListener('click', function() {
        const popup = document.getElementById('profile-popup');
        if (popup && popup.classList.contains('show')) {
            popup.classList.remove('show');
        }
    });

    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const query = this.value.trim();
                const targetUrl = new URL('{{ route('all-products') }}', window.location.origin);
                if (query) {
                    targetUrl.searchParams.set('search', query);
                }
                window.location.href = targetUrl.toString();
            }
        });
    }
</script>
