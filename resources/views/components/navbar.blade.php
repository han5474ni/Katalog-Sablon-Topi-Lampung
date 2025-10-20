<!-- Navbar Component -->
<div class="navbar-wrapper">
    <!-- Top Banner -->
    <div class="top-banner">
        <a href="#">🎉 Dapatkan diskon hingga 50% untuk produk pilihan!</a>
        <span class="close-btn" onclick="this.parentElement.style.display='none';">×</span>
    </div>

    <!-- Header -->
    <header>
        <a href="{{ route('home') }}" class="logo" style="text-decoration: none;">
            <div class="logo-circle">L</div>
            <div class="logo-text-container">
                <span class="logo-text">LGI STORE</span>
                <span class="logo-tagline">PEDULI KUALITAS, BUKAN KUANTITAS</span>
            </div>
        </a>
        
        <!-- Main Navigation -->
        @php
            use Illuminate\Support\Facades\Route;
            $currentRoute = Route::currentRouteName();
            $currentCategory = null;
            if ($currentRoute === 'catalog') {
                $currentCategory = Route::current()->parameter('category');
            }
        @endphp
        <nav class="main-nav">
            <a href="{{ route('all-products') }}" class="{{ $currentRoute === 'all-products' ? 'active' : '' }}">Semua Produk</a>
            <a href="{{ route('catalog', 'topi') }}" class="{{ $currentCategory === 'topi' ? 'active' : '' }}">Topi</a>
            <a href="{{ route('catalog', 'kaos') }}" class="{{ $currentCategory === 'kaos' ? 'active' : '' }}">Kaos</a>
            <a href="{{ route('catalog', 'sablon') }}" class="{{ $currentCategory === 'sablon' ? 'active' : '' }}">Sablon</a>
            <a href="{{ route('catalog', 'jaket') }}" class="{{ $currentCategory === 'jaket' ? 'active' : '' }}">Jaket</a>
            <a href="{{ route('catalog', 'jersey') }}" class="{{ $currentCategory === 'jersey' ? 'active' : '' }}">Jersey</a>
            <a href="{{ route('catalog', 'tas') }}" class="{{ $currentCategory === 'tas' ? 'active' : '' }}">Tas</a>
        </nav>

        <!-- Search Container -->
        <div class="search-container">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="search-box" id="search-input" placeholder="Cari produk..." value="{{ request('search') }}">
        </div>
        
        <!-- Header Actions -->
        <div class="header-actions">
            @php
                $currentUser = auth()->user() ?? auth('admin')->user();
                $userAvatar = $currentUser?->avatar ? Storage::url($currentUser->avatar) : null;
                $isAdmin = auth('admin')->check();
            @endphp
            
            <!-- Notification Bell -->
            <a href="#" aria-label="Notifikasi" class="notification-link">
                <i class="fas fa-bell notification-icon"></i>
            </a>
            
            <!-- Cart (Customer only) -->
            @if(!$isAdmin && auth()->check())
                <a href="{{ route('keranjang') }}" aria-label="Buka Keranjang" class="cart-link">
                    <i class="fas fa-shopping-cart cart-icon"></i>
                </a>
            @endif
            
            <!-- Profile Icon -->
            @if($userAvatar)
                <img src="{{ $userAvatar }}" alt="Profile" id="profile-icon" class="profile-avatar-header">
            @else
                <i id="profile-icon" class="fas fa-user-circle profile-icon-btn"></i>
            @endif
        </div>
    </header>

    <!-- Profile Popup -->
    @php
        $authenticatedUser = auth()->user() ?? auth('admin')->user();
        $userInitial = $authenticatedUser ? mb_strtoupper(mb_substr($authenticatedUser->name, 0, 1)) : 'U';
        $userAvatar = $authenticatedUser?->avatar ? Storage::url($authenticatedUser->avatar) : null;
    @endphp
    <div id="profile-popup" class="profile-popup" data-auth="{{ $authenticatedUser ? 'true' : 'false' }}" data-login-url="{{ route('login') }}">
        <div class="profile-popup-content">
            <div class="profile-header">
                @if($userAvatar)
                    <img src="{{ $userAvatar }}" alt="{{ $authenticatedUser->name }}" class="profile-avatar-img">
                @else
                    <div class="profile-avatar">{{ $userInitial }}</div>
                @endif
                <div class="profile-info">
                    <div class="profile-name">{{ $authenticatedUser?->name ?? 'Tamu' }}</div>
                    <div class="profile-email">{{ $authenticatedUser?->email ?? 'Masuk untuk melihat detail profil' }}</div>
                </div>
            </div>
            <div class="profile-menu">
                @if ($authenticatedUser)
                    @if(auth()->check())
                        <a href="{{ route('profile') }}" class="profile-menu-item">
                            <i class="fas fa-user"></i>
                            <span>Profil Saya</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="profile-logout-form">
                            @csrf
                            <button type="submit" class="profile-menu-item">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Keluar</span>
                            </button>
                        </form>
                    @elseif(auth('admin')->check())
                        <a href="{{ route('admin.profile') }}" class="profile-menu-item">
                            <i class="fas fa-user-shield"></i>
                            <span>Admin Profile</span>
                        </a>
                        <a href="{{ route('admin.dashboard') }}" class="profile-menu-item">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                        <form method="POST" action="{{ route('admin.logout') }}" class="profile-logout-form">
                            @csrf
                            <button type="submit" class="profile-menu-item">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="profile-menu-item">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Masuk</span>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    /* Top Banner */
    .top-banner {
        background-color: #1a2942;
        padding: 12px 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .top-banner a {
        color: #ffa500;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
    }

    .close-btn {
        position: absolute;
        right: 40px;
        cursor: pointer;
        color: #fff;
        font-size: 18px;
    }

    /* Header Styles */
    header {
        background-color: #152238;
        padding: 15px 40px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        position: sticky;
        top: 0;
        z-index: 100;
    }

    .logo {
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
    }

    .logo-circle {
        width: 45px;
        height: 45px;
        background-color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: #0a1628;
        font-size: 16px;
    }

    .logo-text-container {
        display: flex;
        flex-direction: column;
    }

    .logo-text {
        color: #fff;
        font-weight: 800;
        font-size: 16px;
        letter-spacing: 2px;
    }

    .logo-tagline {
        color: #888;
        font-size: 8px;
        letter-spacing: 1px;
    }

    .main-nav {
        display: flex;
        gap: 30px;
        flex: 1;
        justify-content: center;
    }

    .main-nav a {
        color: #fff;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: color 0.3s;
        position: relative;
    }

    .main-nav a:hover,
    .main-nav a.active {
        color: #ffa500;
    }

    .main-nav a.active::after {
        content: '';
        position: absolute;
        bottom: -20px;
        left: 0;
        right: 0;
        height: 3px;
        background-color: #ffa500;
    }

    .search-container {
        position: relative;
        margin-right: 20px;
    }

    .search-box {
        background-color: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 25px;
        padding: 10px 20px 10px 40px;
        color: #fff;
        width: 300px;
        outline: none;
        transition: all 0.3s;
    }

    .search-box:focus {
        background-color: rgba(255, 255, 255, 0.15);
        border-color: #ffa500;
    }

    .search-box::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }

    .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(255, 255, 255, 0.5);
    }

    .header-actions {
        display: flex;
        gap: 25px;
        align-items: center;
    }

    .header-actions i,
    .header-actions img {
        color: #fff;
        font-size: 22px;
        cursor: pointer;
        transition: color 0.3s;
    }

    .header-actions i:hover {
        color: #ffa500;
    }

    .profile-avatar-header {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        cursor: pointer;
    }

    /* Profile Popup */
    .profile-popup {
        position: fixed;
        top: 70px;
        right: 40px;
        background-color: #152238;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        min-width: 300px;
        display: none;
        z-index: 1000;
    }

    .profile-popup.show {
        display: block;
    }

    .profile-popup-content {
        padding: 20px;
    }

    .profile-header {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .profile-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: #ffa500;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 18px;
        color: #fff;
        flex-shrink: 0;
    }

    .profile-avatar-img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
    }

    .profile-info {
        flex: 1;
    }

    .profile-name {
        color: #fff;
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 5px;
    }

    .profile-email {
        color: #888;
        font-size: 12px;
    }

    .profile-menu {
        display: flex;
        flex-direction: column;
        gap: 0;
    }

    .profile-menu-item,
    .profile-logout-form button {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 15px;
        color: #ccc;
        text-decoration: none;
        font-size: 13px;
        border: none;
        background: none;
        cursor: pointer;
        transition: all 0.3s;
        width: 100%;
        text-align: left;
        font-family: 'Inter', sans-serif;
    }

    .profile-menu-item:hover,
    .profile-logout-form button:hover {
        color: #ffa500;
        background-color: rgba(255, 165, 0, 0.1);
    }

    .profile-logout-form {
        width: 100%;
    }
</style>

<script>
    // Profile popup toggle
    document.getElementById('profile-icon')?.addEventListener('click', function(e) {
        e.stopPropagation();
        const popup = document.getElementById('profile-popup');
        popup.classList.toggle('show');
    });

    // Close popup when clicking outside
    document.addEventListener('click', function() {
        const popup = document.getElementById('profile-popup');
        if (popup && popup.classList.contains('show')) {
            popup.classList.remove('show');
        }
    });

    // Search functionality
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const query = this.value.trim();
                if (query) {
                    // Redirect to all-products with search parameter
                    window.location.href = '{{ route("all-products") }}?search=' + encodeURIComponent(query);
                }
            }
        });
    }
</script>