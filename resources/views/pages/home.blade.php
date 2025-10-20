<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LGI Store - Homepage</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/guest/home.css', 'resources/css/components/footer.css', 'resources/js/guest/home.js', 'resources/js/guest/product-slider.js'])
</head>
<body>
    <!-- Top Banner -->
    <div class="top-banner">
        <a href="{{ route('login') }}">Sign In now!</a>
        <span class="close-btn">✕</span>
    </div>

    <!-- Header -->
    <header>
        <a href="{{ route('home') }}" class="logo" style="text-decoration: none;">
            <div class="logo-circle"></div>
            <div class="logo-text-container">
                <span class="logo-text">LGI STORE</span>
                <span class="logo-tagline">PEDULI KUALITAS, BUKAN KUANTITAS</span>
            </div>
        </a>
        
        <nav class="main-nav">
            <a href="{{ route('all-products') }}">Semua Produk</a>
            <a href="{{ route('catalog', 'topi') }}">Topi</a>
            <a href="{{ route('catalog', 'kaos') }}">Kaos</a>
            <a href="{{ route('catalog', 'sablon') }}">Sablon</a>
            <a href="{{ route('catalog', 'jaket') }}">Jaket</a>
            <a href="{{ route('catalog', 'jersey') }}">Jersey</a>
            <a href="{{ route('catalog', 'tas') }}">Tas</a>
        </nav>

        <div class="search-container">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="search-box" placeholder="Search for products...">
        </div>
        
        <div class="header-actions">
            @php
                $currentUser = auth()->user() ?? auth('admin')->user();
                $userAvatar = $currentUser?->avatar ? Storage::url($currentUser->avatar) : null;
                $isAdmin = auth('admin')->check();
            @endphp
            
            {{-- Notification Bell --}}
            <a href="#" aria-label="Notifikasi" class="notification-link">
                <i class="fas fa-bell notification-icon"></i>
            </a>
            
            {{-- Cart (Customer only) --}}
            @if(!$isAdmin)
                <a href="{{ route('keranjang') }}" aria-label="Buka Keranjang" class="cart-link">
                    <i class="fas fa-shopping-cart cart-icon"></i>
                </a>
            @endif
            
            {{-- Profile --}}
            @if($userAvatar)
                <img src="{{ $userAvatar }}" alt="Profile" id="profile-icon" class="profile-avatar-header">
            @else
                <i id="profile-icon" class="fas fa-user-circle profile-icon-btn"></i>
            @endif
        </div>
    </header>

    <!-- Profile Popup -->
    @php
        // Check both customer and admin authentication
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
                        {{-- Customer logged in --}}
                        <a href="{{ route('profile') }}" class="profile-menu-item active">
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
                        {{-- Admin logged in --}}
                        <a href="{{ route('admin.profile') }}" class="profile-menu-item active">
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
                    <a href="{{ route('login') }}" class="profile-menu-item active">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Masuk</span>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>CARI STYLE JERSEY FAVORITMU</h1>
            <p>Bukan cuma jersey, topi, celana, dan lain-lain juga ada loh. Kamu juga bisa kustom mereka tanpa minimal pembelian. Buruan, daftarkan akunmu dan checkout sekarang!</p>
            <a href="{{ route('login') }}" class="shop-btn-link"><button class="shop-btn">Shop Now</button></a>

            <div class="stats">
                <div class="stat-item">
                    <div class="stat-number">2</div>
                    <div class="stat-label">Cabang</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">200+</div>
                    <div class="stat-label">Custom Design</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">1,000+</div>
                    <div class="stat-label">Pembelian</div>
                </div>
            </div>
        </div>

        <div class="hero-image">
            <img src="https://i.pinimg.com/originals/e9/04/53/e904533ed00df550bb4fc87064217f18.png" alt="Minimalist Jersey" class="hero-img">
        </div>
    </section>

    <!-- New Arrivals -->
    <section class="new-arrivals">
        <h2 class="section-title">NEW ARRIVALS</h2>
        <div class="product-container">
            <button class="slider-nav slider-prev" data-slider="arrivals">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="slider-nav slider-next" data-slider="arrivals">
                <i class="fas fa-chevron-right"></i>
            </button>
            <div class="product-slider" id="arrivals-slider">
                <div class="product-grid slider-track">
                @forelse($newArrivals as $product)
                    <div class="product-card" 
                         data-product-id="{{ $product->id }}" 
                         data-product-name="{{ $product->name }}" 
                         data-product-price="{{ $product->formatted_price }}" 
                         data-product-image="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300' }}">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300' }}" 
                             alt="{{ $product->name }}" 
                             class="product-image">
                        <div class="product-name">{{ $product->name }}</div>
                        <div class="product-price">Rp {{ $product->formatted_price }}</div>
                    </div>
                @empty
                    <div class="no-products">
                        <p>Belum ada produk baru.</p>
                    </div>
                @endforelse
                </div>
            </div>
            <button class="view-all-btn">View All</button>
        </div>
    </section>

    <!-- Products Grid -->
    <section class="products-section">
        <h2 class="section-title">TOP SELLING</h2>
        <div class="product-container">
            <button class="slider-nav slider-prev" data-slider="selling">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="slider-nav slider-next" data-slider="selling">
                <i class="fas fa-chevron-right"></i>
            </button>
            <div class="product-slider" id="selling-slider">
                <div class="product-grid slider-track">
                @forelse($topSelling as $product)
                    <div class="product-card" 
                         data-product-id="{{ $product->id }}" 
                         data-product-name="{{ $product->name }}" 
                         data-product-price="{{ $product->formatted_price }}" 
                         data-product-image="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300' }}">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300' }}" 
                             alt="{{ $product->name }}" 
                             class="product-image">
                        <div class="product-name">{{ $product->name }}</div>
                        <div class="product-price">Rp {{ $product->formatted_price }}</div>
                    </div>
                @empty
                    <div class="no-products">
                        <p>Belum ada produk best seller.</p>
                    </div>
                @endforelse
                </div>
            </div>
        </div>
        <button class="view-all-btn top-selling-view-all">View All</button>
    </section>

    <!-- Custom Design Section -->
    <section class="custom-design">
        <h2>DESIGN BAJUMU SENDIRI</h2>
        <div class="design-blue-container">
            <div class="design-container">
            <div class="jersey-preview">
                <img src="https://i.pinimg.com/1200x/e0/62/b6/e062b626075c2d7191d6dbee36b5b697.jpg" alt="Custom Jersey" class="jersey-image">
            </div>

            <div class="customization-panel">
                <div class="jersey-options">
                    <div class="jersey-option">
                        <div style="width: 100%; height: 100%; background: #333; display: flex; align-items: center; justify-content: center; color: #fff;">
                            <i class="fas fa-tshirt" style="font-size: 30px;"></i>
                        </div>
                    </div>
                    <div class="jersey-option active">
                        <div style="width: 100%; height: 100%; background: #dc143c; display: flex; align-items: center; justify-content: center; color: #fff;">
                            <i class="fas fa-tshirt" style="font-size: 30px;"></i>
                        </div>
                    </div>
                    <div class="jersey-option">
                        <div style="width: 100%; height: 100%; background: #fff; border: 1px solid #ddd; display: flex; align-items: center; justify-content: center; color: #333;">
                            <i class="fas fa-tshirt" style="font-size: 30px;"></i>
                        </div>
                    </div>
                </div>

                <div class="option-group">
                    <div class="option-label">Select Color</div>
                    <div class="color-options">
                        <div class="color-btn" style="background-color: #8b6f47;"></div>
                        <div class="color-btn active" style="background-color: #333;"></div>
                        <div class="color-btn" style="background-color: #1e3a5f;"></div>
                    </div>
                </div>

                <div class="option-group">
                    <div class="option-label">Choose Size</div>
                    <div class="size-options">
                        <button class="size-btn">Small</button>
                        <button class="size-btn">Medium</button>
                        <button class="size-btn active">Large</button>
                        <button class="size-btn">X-Large</button>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>

    <!-- Footer Component -->
    <x-guest-footer />
</body>
</html>