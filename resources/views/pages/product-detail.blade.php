<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product['name'] }} - LGI Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/guest/product-detail.css', 'resources/css/components/footer.css', 'resources/js/guest/product-detail.js'])
</head>
<body>
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
            
            @if(!$isAdmin)
                <a href="{{ route('keranjang') }}" aria-label="Buka Keranjang" class="cart-link">
                    <i class="fas fa-shopping-cart cart-icon"></i>
                </a>
            @endif
            
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

    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span>></span>
        <a href="#">Kaos</a>
        <span>></span>
        <span>{{ $product['name'] }}</span>
    </div>

    <!-- Product Detail Section -->
    <section class="product-detail">
        <div class="product-images">
            <div class="thumbnail-images">
                <img src="{{ $product['image'] }}" alt="Thumbnail 1" class="thumbnail active" data-image="{{ $product['image'] }}">
                <img src="https://i.pinimg.com/736x/45/4c/92/454c92dc87e9774bed336c9ea9d132ed.jpg" alt="Thumbnail 2" class="thumbnail" data-image="https://i.pinimg.com/736x/45/4c/92/454c92dc87e9774bed336c9ea9d132ed.jpg">
                <img src="https://i.pinimg.com/736x/4f/7e/bf/4f7ebfdc234afefe71d5f4a8ec8ba408.jpg" alt="Thumbnail 3" class="thumbnail" data-image="https://i.pinimg.com/736x/4f/7e/bf/4f7ebfdc234afefe71d5f4a8ec8ba408.jpg">
            </div>
            <div class="main-image">
                <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" id="mainImage">
            </div>
        </div>

        <div class="product-info">
            <h1 class="product-title">{{ $product['name'] }}</h1>
            <div class="product-price">Rp {{ $product['price'] }}</div>
            <p class="product-description">{{ $product['description'] }}</p>

            <div class="product-options">
                <div class="option-group">
                    <label>Select Colors</label>
                    <div class="color-options">
                        @foreach($product['colors'] as $index => $color)
                            <div class="color-option {{ $index === 0 ? 'active' : '' }}" style="background-color: {{ $color }};" data-color="{{ $color }}"></div>
                        @endforeach
                    </div>
                </div>

                <div class="option-group">
                    <label>Choose Size</label>
                    <div class="size-options">
                        @foreach($product['sizes'] as $index => $size)
                            <button class="size-option {{ $index === 2 ? 'active' : '' }}" data-size="{{ $size }}">{{ $size }}</button>
                        @endforeach
                    </div>
                </div>

                <div class="option-group">
                    <div class="quantity-selector">
                        <button class="qty-btn" id="decreaseQty">-</button>
                        <input type="number" id="quantity" value="1" min="1" max="99" readonly>
                        <button class="qty-btn" id="increaseQty">+</button>
                    </div>
                    <button class="add-to-cart-btn">Add to Cart</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Details Tab -->
    <section class="product-details-section">
        <div class="details-tabs">
            <button class="tab-btn active" data-tab="details">Detail Produk</button>
        </div>
        <div class="tab-content active" id="details">
            <div class="details-content">
                <p><strong>Nama Produk:</strong> Jersey Eps Balocc - Edition Dummy</p>
                <p><strong>Keterangan:</strong> Jersey Eps balocc adalah sebaga kustoman rugun dan bawefashla, direcormg untuk atleticis tas, btilac, dan casual tegas. Tempilan modern dengan pertenaan ergenomilc membuttua gyanu fne tahan 9ra seima aktiftas</p>
                
                <p><strong>Rated Produk:</strong></p>
                <ul>
                    <li><strong>Bahan:</strong> Polyester microfiler berkualitas (100% polyester) dengan sirkulasi ade dan tapa.</li>
                    <li><strong>Teknologi fabric:</strong> Dry-fil technology untuk menjaga tubuh tetup kering dan nyaman</li>
                    <li><strong>Desain:</strong> Modern sleek design dengan kacut & full cutting material yang lebis, dan full nyaman, dtls plus emter fish hayennerals untuk, dtla plus umber tarhan inayamenerals nyaman</li>
                </ul>

                <p><strong>Ukuran (approx. t0.5 cm):</strong></p>
                <ul>
                    <li>M: Dada 98 - 104 cm</li>
                    <li>L: Dada 104 - 112 cm</li>
                    <li>XL: Dada 110 - 114 cm</li>
                    <li>XXL: Dada 116 - 119 cm</li>
                </ul>

                <p><strong>Hanya tersedia di dummy:</strong> Jersey Eps. Whosgy Etag, Choopy, Stanl, Riding Rat, Formel Stil</p>

                <p><strong>Fitur tambahan:</strong></p>
                <ul>
                    <li>Material breathable berbahan Anti-bakteria</li>
                    <li>Teknologi buatahle quick-drying notodulis</li>
                    <li>Jersey ruangn disetall dengan jahitan flatlock (Mala riama saja stacia, latue miata sama kuhu), sehin tamto etm be kurser</li>
                </ul>

                <p><strong>Perawatan:</strong> Cuci secara asan, diringin 0-MCUA0 ringin granas voui dun hlo, petgan distansum dengan yanat hingu, sehins main ester tuto kalo haps, petget</p>

                <p><strong>Konsisten:</strong> (Membagi tangas arathda; tenteng dengen yarmat, d tra plus arbum hittor haysmenerals nyaman)</p>

                <p><strong>Garansi: 14% MONEY BACK</strong> (Harga no refund. If produtts defect & impairment if kesuuadaan-kuritan agiln terr, ober berkat mutor)</p>

                <ul>
                    <li>Untuk keperlun kacauli, perukaan ordess rugun dan terketahut atau te ulence fuin, uhan terket mutor.</li>
                    <li>Untuk appentan dan tersedia agre tenteng parint oper & u vonce foun, ather perlor utter.</li>
                    <li>Wajib Replay &agudarify &atropurist &amurphysins64</li>
                </ul>

                <p><strong>Garansi : 100%</strong></p>

                <p>Wajib dicatat satu terkering ergany tetr tag</p>
            </div>
        </div>
    </section>

    <!-- You Might Also Like -->
    <section class="recommendations">
        <h2 class="section-title">YOU MIGHT ALSO LIKE</h2>
        <div class="product-grid">
            <div class="product-card" data-product-id="1" data-product-name="Polo with Contrast Trims" data-product-price="60.000" data-product-image="https://i.pinimg.com/736x/3e/6b/f5/3e6bf5378b6ae4d43263dfb626d37588.jpg">
                <img src="https://i.pinimg.com/736x/3e/6b/f5/3e6bf5378b6ae4d43263dfb626d37588.jpg" alt="Polo with Contrast Trims">
                <div class="product-name">Polo with Contrast Trims</div>
                <div class="product-price">Rp 60.000</div>
            </div>
            <div class="product-card" data-product-id="2" data-product-name="Gradient Graphic T-shirt" data-product-price="65.000" data-product-image="https://i.pinimg.com/736x/45/4c/92/454c92dc87e9774bed336c9ea9d132ed.jpg">
                <img src="https://i.pinimg.com/736x/45/4c/92/454c92dc87e9774bed336c9ea9d132ed.jpg" alt="Gradient Graphic T-shirt">
                <div class="product-name">Gradient Graphic T-shirt</div>
                <div class="product-price">Rp 65.000</div>
            </div>
            <div class="product-card" data-product-id="3" data-product-name="Polo with Tipping Details" data-product-price="50.000" data-product-image="https://i.pinimg.com/736x/4f/7e/bf/4f7ebfdc234afefe71d5f4a8ec8ba408.jpg">
                <img src="https://i.pinimg.com/736x/4f/7e/bf/4f7ebfdc234afefe71d5f4a8ec8ba408.jpg" alt="Polo with Tipping Details">
                <div class="product-name">Polo with Tipping Details</div>
                <div class="product-price">Rp 50.000</div>
            </div>
            <div class="product-card" data-product-id="4" data-product-name="Black Striped T-shirt" data-product-price="45.000" data-product-image="https://i.pinimg.com/736x/69/92/5a/69925a28d7d2cbb1caacb62ad74c4206.jpg">
                <img src="https://i.pinimg.com/736x/69/92/5a/69925a28d7d2cbb1caacb62ad74c4206.jpg" alt="Black Striped T-shirt">
                <div class="product-name">Black Striped T-shirt</div>
                <div class="product-price">Rp 45.000</div>
            </div>
        </div>
    </section>

    <!-- Footer Component -->
    <x-guest-footer />
</body>
</html>
