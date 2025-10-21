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
            <a href="{{ route('catalog', 'jersey') }}" class="{{ $currentCategory === 'jersey' ? 'active' : '' }}">Jersey</a>
            <a href="{{ route('catalog', 'topi') }}" class="{{ $currentCategory === 'topi' ? 'active' : '' }}">Topi</a>
            <a href="{{ route('catalog', 'kaos') }}" class="{{ $currentCategory === 'kaos' ? 'active' : '' }}">Kaos</a>
            <a href="{{ route('catalog', 'celana') }}" class="{{ $currentCategory === 'celana' ? 'active' : '' }}">Celana</a>
            <a href="{{ route('catalog', 'jaket') }}" class="{{ $currentCategory === 'jaket' ? 'active' : '' }}">Jaket</a>
            <a href="{{ route('catalog', 'polo') }}" class="{{ $currentCategory === 'polo' ? 'active' : '' }}">Polo</a>
            <a href="#contact-info">Contact Info</a>
            <a href="#about">About</a>
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
</div>

<style>
    /* Top Banner */
    .navbar-wrapper .top-banner {
        background: #1a2947;
        color: #ffffff;
        padding: 10px 24px;
        text-align: center;
        font-size: 14px;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 20px;
        position: relative;
        width: 100%;
        box-sizing: border-box;
    }

    .navbar-wrapper .top-banner a {
        color: #fbbf24;
        font-weight: 600;
        text-decoration: underline;
    }

    .navbar-wrapper .top-banner .close-btn {
        position: absolute;
        right: 24px;
        cursor: pointer;
        font-size: 18px;
        opacity: 0.8;
        transition: opacity 0.3s ease;
        color: #ffffff;
    }

    .navbar-wrapper .top-banner .close-btn:hover {
        opacity: 1;
    }

    /* Header Styles */
    .navbar-wrapper header {
        background: #1a2947;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 0;
        z-index: 100;
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        margin: 0;
        padding: 12px 24px;
        box-sizing: border-box;
    }

    .navbar-wrapper .logo {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
    }

    .navbar-wrapper .logo-circle {
        width: 45px;
        height: 45px;
        background: #ffffff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: #1a2947;
        flex-shrink: 0;
    }

    .navbar-wrapper .logo-text-container {
        display: flex;
        flex-direction: column;
    }

    .navbar-wrapper .logo-text {
        color: #ffffff;
        font-weight: 700;
        font-size: 16px;
        letter-spacing: 0.5px;
        line-height: 1.2;
    }

    .navbar-wrapper .logo-tagline {
        color: rgba(255, 255, 255, 0.65);
        font-size: 8px;
        letter-spacing: 1px;
        text-transform: uppercase;
        line-height: 1;
    }

    .navbar-wrapper .main-nav {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-left: 32px;
    }

    .navbar-wrapper .main-nav a {
        color: #ffffff;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        padding: 10px 20px;
        border-radius: 8px;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .navbar-wrapper .main-nav a:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .navbar-wrapper .main-nav a.active {
        background: #fbbf24;
        color: #1a2947;
        font-weight: 600;
    }

    .navbar-wrapper .search-container {
        flex: 1;
        max-width: 500px;
        position: relative;
        margin: 0 24px 0 auto;
    }

    .navbar-wrapper .search-box {
        width: 100%;
        padding: 11px 16px 11px 44px;
        border: none;
        border-radius: 24px;
        background: #b3bac4;
        color: #1f2937;
        font-size: 14px;
        transition: background 0.2s ease;
    }

    .navbar-wrapper .search-box:focus {
        outline: none;
        background: #c4cad1;
    }

    .navbar-wrapper .search-box::placeholder {
        color: #6b7280;
    }

    .navbar-wrapper .search-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7280;
        font-size: 14px;
        pointer-events: none;
    }

    .navbar-wrapper .header-actions {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-left: 16px;
    }

    .navbar-wrapper .header-actions i,
    .navbar-wrapper .header-actions a,
    .navbar-wrapper .header-actions img {
        color: #ffffff;
        font-size: 20px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .navbar-wrapper .header-actions i:hover,
    .navbar-wrapper .header-actions a:hover {
        color: #fbbf24;
        transform: scale(1.05);
    }

    .navbar-wrapper .profile-avatar-header {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
        cursor: pointer;
        border: 2px solid #ffffff;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .navbar-wrapper .profile-avatar-header:hover {
        border-color: #fbbf24;
        box-shadow: 0 0 12px rgba(255, 193, 7, 0.6);
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