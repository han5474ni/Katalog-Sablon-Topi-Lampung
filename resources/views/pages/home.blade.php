<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - LGI Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/guest/home.css', 'resources/css/components/footer.css', 'resources/js/guest/home.js', 'resources/js/guest/product-slider.js'])
</head>
<body>
    <!-- Header -->
    <x-navbar />



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
            <img src="https://i.pinimg.com/originals/e9/04/53/e904533ed00df550bb4fc87064217f18.png" alt="Minimalist Jersey" class="hero-img" width="640" height="640" decoding="async" fetchpriority="high">
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
                         data-product-image="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300' }}"
                         tabindex="0">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300' }}" 
                             alt="{{ $product->name }}" 
                             class="product-image" loading="lazy" decoding="async" width="300" height="300">
                        @if(!empty($product->custom_design_allowed) && $product->custom_design_allowed)
                            <div class="product-ribbon small" aria-hidden="true">CUSTOM</div>
                        @endif
                        <div class="product-name">{{ $product->name }}</div>
                        <div class="product-price">Rp {{ $product->formatted_price }}</div>
                        <div class="product-actions" role="group" aria-label="Aksi produk">
                            <button class="action-btn action-chat" type="button" aria-label="Chat tentang produk">
                                <i class="fas fa-comments" aria-hidden="true"></i>
                            </button>
                            <button class="action-btn action-cart" type="button" aria-label="Tambahkan ke keranjang" data-product-id="{{ $product->id }}">
                                <i class="fas fa-shopping-cart" aria-hidden="true"></i>
                            </button>
                        </div>
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
                         data-product-image="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300' }}"
                         tabindex="0">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300' }}" 
                             alt="{{ $product->name }}" 
                             class="product-image" loading="lazy" decoding="async" width="300" height="300">
                        <div class="product-name">{{ $product->name }}</div>
                        <div class="product-price">Rp {{ $product->formatted_price }}</div>
                        <div class="product-actions" role="group" aria-label="Aksi produk">
                            <button class="action-btn action-chat" type="button" aria-label="Chat tentang produk">
                                <i class="fas fa-comments" aria-hidden="true"></i>
                            </button>
                            <button class="action-btn action-cart" type="button" aria-label="Tambahkan ke keranjang" data-product-id="{{ $product->id }}">
                                <i class="fas fa-shopping-cart" aria-hidden="true"></i>
                            </button>
                        </div>
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

    

    <!-- Footer Component -->
    <x-guest-footer />
</body>
</html>