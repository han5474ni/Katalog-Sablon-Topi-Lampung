<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product['name'] ?? 'Detail Produk' }} - LGI Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/guest/product-detail.css', 'resources/css/components/navbar.css', 'resources/css/components/footer.css', 'resources/js/guest/product-detail.js', 'resources/js/components/navbar.js'])
</head>
<body>
    <x-navbar />

    @php
        $primaryImage = $product['image'] ?? 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=500&h=500&fit=crop';
        $gallery = $product['gallery'] ?? [];
        if (is_string($gallery)) {
            $decodedGallery = json_decode($gallery, true);
            if (is_array($decodedGallery)) {
                $gallery = $decodedGallery;
            }
        }
        $fallbackGallery = [
            'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=500&h=500&fit=crop',
            'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=500&h=500&fit=crop',
            'https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=500&h=500&fit=crop',
        ];
        $gallery = collect(array_filter(array_merge([$primaryImage], is_array($gallery) ? $gallery : [])))->unique()->values()->all();
        if (empty($gallery)) {
            $gallery = $fallbackGallery;
        }

        $colors = $product['colors'] ?? [];
        if (is_string($colors)) {
            $decodedColors = json_decode($colors, true);
            if (is_array($decodedColors)) {
                $colors = $decodedColors;
            }
        }
        if (empty($colors)) {
            $colors = ['#6b6b47', '#4a6b6b', '#2a3a5a'];
        }

        $sizes = $product['sizes'] ?? [];
        if (is_string($sizes)) {
            $decodedSizes = json_decode($sizes, true);
            if (is_array($decodedSizes)) {
                $sizes = $decodedSizes;
            }
        }
        if (empty($sizes)) {
            $sizes = ['Small', 'Medium', 'Large', 'X-Large'];
        }

        $price = $product['price'] ?? '0';
        if (is_numeric($price)) {
            $price = number_format((float) $price, 0, ',', '.');
        }

        $description = $product['description'] ?? 'Produk ini dibuat dengan material berkualitas tinggi yang nyaman digunakan sepanjang hari.';
        $category = $product['category'] ?? 'Umum';
        $stock = $product['stock'] ?? 0;
        $customAllowed = (bool)($product['custom_design_allowed'] ?? false);
    @endphp

    <nav class="breadcrumb">
        <div class="breadcrumb-inner">
            <a href="{{ route('home') }}" class="breadcrumb-link">
                <span aria-hidden="true">&lt;</span>
                <span>Beranda</span>
            </a>
            <li class="breadcrumb-separator"><i class="fas fa-chevron-right"></i></li>
            <span class="breadcrumb-current">{{ $product['name'] ?? 'Produk' }}</span>
        </div>
    </nav>

    <main class="product-page" data-product-id="{{ $product['id'] ?? '' }}">
        <section class="product-hero">
            <div class="product-gallery">
                <div class="thumbnail-list" role="tablist" aria-label="Galeri produk">
                    @foreach($gallery as $index => $image)
                        <button type="button" class="thumbnail{{ $loop->first ? ' active' : '' }}" data-image="{{ $image }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}" aria-label="Gambar {{ $loop->iteration }}">
                            <img src="{{ $image }}" alt="Thumbnail {{ $loop->iteration }} {{ $product['name'] ?? 'Produk' }}" loading="lazy" decoding="async" width="110" height="110">
                        </button>
                    @endforeach
                </div>
                <div class="main-image">
                    <img id="mainImage" src="{{ $gallery[0] }}" alt="{{ $product['name'] ?? 'Produk' }}" loading="lazy" decoding="async">
                </div>
            </div>

            <div class="product-info">
                <h1 class="product-title">{{ $product['name'] ?? 'Produk Tanpa Nama' }}</h1>
                <p class="product-price">Rp {{ $price }}</p>
                <p class="product-description">{{ $description }}</p>

                <div class="option-group">
                    <h2 class="option-label">Pilih Warna</h2>
                    <div class="color-options" role="radiogroup" aria-label="Pilihan warna">
                        @foreach($colors as $index => $color)
                            @php
                                $colorValue = is_array($color) ? ($color['value'] ?? $color['hex'] ?? '#000000') : $color;
                                $colorLabel = is_array($color) ? ($color['label'] ?? $colorValue) : $colorValue;
                            @endphp
                            <button type="button" class="color-swatch{{ $loop->first ? ' active' : '' }}" style="--swatch-color: {{ $colorValue }}" data-color="{{ $colorValue }}" aria-label="Warna {{ $colorLabel }}" aria-pressed="{{ $loop->first ? 'true' : 'false' }}"></button>
                        @endforeach
                    </div>
                </div>

                <div class="option-group">
                    <h2 class="option-label">Pilih Ukuran</h2>
                    <div class="size-options" role="radiogroup" aria-label="Pilihan ukuran">
                        @foreach($sizes as $size)
                            <button type="button" class="size-option{{ $loop->first ? ' active' : '' }}" data-size="{{ $size }}" aria-pressed="{{ $loop->first ? 'true' : 'false' }}">{{ $size }}</button>
                        @endforeach
                    </div>
                </div>

                <div class="purchase-actions">
                    <form id="addToCartForm" method="POST" action="{{ route('cart.add') }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product['id'] ?? '' }}">
                        <input type="hidden" name="quantity" id="cartQuantityInput" value="1">
                        <input type="hidden" name="color" id="cartColorInput" value="">
                        <input type="hidden" name="size" id="cartSizeInput" value="">
                    </form>
                    <div class="quantity-selector" aria-label="Pilih kuantitas">
                        <button type="button" class="quantity-btn" data-quantity-action="decrease" aria-label="Kurangi jumlah">−</button>
                        <span class="quantity-value" id="quantityValue" aria-live="polite">1</span>
                        <button type="button" class="quantity-btn" data-quantity-action="increase" aria-label="Tambah jumlah">+</button>
                    </div>

                    <div class="button-row-top">
                        @if($customAllowed)
                            <button type="button" class="custom-design-btn" data-custom-link="{{ route('custom-design', [
                                'id' => $product['id'] ?? null,
                                'name' => $product['name'] ?? 'One Life Graphic T-shirt',
                                'price' => $price,
                                'image' => $gallery[0] ?? 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=500&h=500&fit=crop',
                                'preview_url' => request()->fullUrl(),
                            ]) }}">
                                Custom
                            </button>
                        @else
                            <button type="button" class="custom-design-btn" disabled style="opacity: 0.5; cursor: not-allowed;">
                                Custom
                            </button>
                        @endif
                        
                        <button type="button" class="add-to-cart-btn" data-product-name="{{ $product['name'] ?? '' }}">
                            Tambahkan ke Keranjang
                        </button>
                    </div>

                    <button type="button" class="buy-now-btn" data-product-name="{{ $product['name'] ?? '' }}">
                        Beli Sekarang
                    </button>
                </div>

                <div class="product-meta">
                    <div class="meta-item">
                        <span class="meta-label">Kategori</span>
                        <span class="meta-value">{{ ucfirst($category) }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Stok</span>
                        <span class="meta-value">{{ $stock > 0 ? $stock . ' pcs tersedia' : 'Stok terbatas' }}</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="product-details">
            <div class="tab-controls" role="tablist" aria-label="Informasi produk">
                <button type="button" class="tab-button active" data-tab="details" aria-selected="true">Detail Produk</button>

            </div>

            <div class="tab-panels">
                <div class="tab-panel active" data-tab-panel="details">
                    <div class="detail-block">
                        <h3 class="detail-title">Tentang Produk</h3>
                        <p class="detail-text">{{ $description }}</p>
                    </div>
                    <div class="detail-block">
                        <h3 class="detail-title">Spesifikasi</h3>
                        <ul class="detail-list">
                            <li>Kode produk: {{ $product['id'] ?? 'SKU-0000' }}</li>
                            <li>Kategori: {{ ucfirst($category) }}</li>
                            <li>Material: Polyester breathable premium</li>
                            <li>Teknologi: Quick dry, anti-bau, dan anti-pilling</li>
                            <li>Rekomendasi aktivitas: Olahraga ringan, kegiatan outdoor, dan pemakaian harian</li>
                        </ul>
                    </div>
                </div>
                <div class="tab-panel" data-tab-panel="care">
                    <div class="detail-block">
                        <h3 class="detail-title">Custom Printing</h3>
                        <p class="detail-text">Hubungi tim kami untuk custom desain dengan minimum order fleksibel. Kami menyediakan layanan cetak sublimasi full-color yang tahan lama.</p>
                    </div>
                    <div class="detail-block">
                        <h3 class="detail-title">Perawatan</h3>
                        <ul class="detail-list">
                            <li>Cuci dengan air dingin maksimal 30°C</li>
                            <li>Jangan gunakan pemutih</li>
                            <li>Keringkan di tempat teduh</li>
                            <li>Setrika pada suhu rendah jika diperlukan</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="recommendations">
            <h2 class="recommendations-title">Mungkin Kamu Juga Suka</h2>
            <div class="recommendation-grid">
                @foreach($recommendations as $item)
                    @php
                        $recId = $item['id'] ?? ($item->id ?? null);
                        $recName = $item['name'] ?? ($item->name ?? 'Produk');
                        $recPrice = $item['price'] ?? ($item->formatted_price ?? '0');
                        $recImage = $item['image'] ?? (isset($item->image) ? asset('storage/'.$item->image) : 'https://via.placeholder.com/300');
                    @endphp
                    <a href="{{ route('product.detail', ['id' => $recId, 'name' => $recName, 'price' => $recPrice, 'image' => $recImage]) }}" class="recommendation-card" data-product-id="{{ $recId }}" tabindex="0" aria-label="Lihat {{ $recName }}">
                        <div class="recommendation-image">
                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" loading="lazy" decoding="async" width="240" height="240">
                            @if(!empty($item['custom_design_allowed']) && $item['custom_design_allowed'])
                                <div class="product-ribbon small" aria-hidden="true">CUSTOM</div>
                            @endif
                        </div>
                        <div class="recommendation-info">
                            <h3 class="recommendation-name">{{ $item['name'] }}</h3>
                            <p class="recommendation-price">Rp {{ $item['price'] }}</p>
                            <div class="product-actions" role="group" aria-label="Aksi produk">
                                
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    </main>

    <x-guest-footer />

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Custom design button
            const customBtn = document.querySelector('.custom-design-btn');
            if (customBtn && !customBtn.disabled) {
                customBtn.addEventListener('click', function() {
                    const customLink = this.dataset.customLink;
                    if (customLink) {
                        window.location.href = customLink;
                    }
                });
            }

            // Buy now button
            const buyNowBtn = document.querySelector('.buy-now-btn');
            if (buyNowBtn) {
                buyNowBtn.addEventListener('click', function() {
                    const productName = this.dataset.productName;
                    console.log('Beli sekarang:', productName);
                    // Add your buy now logic here
                    // For example: add to cart and redirect to checkout
                });
            }

            // Add to cart button (existing functionality)
            const addToCartBtn = document.querySelector('.add-to-cart-btn');
            if (addToCartBtn) {
                addToCartBtn.addEventListener('click', function() {
                    const productName = this.dataset.productName;
                    console.log('Tambahkan ke keranjang:', productName);
                    // Add your add to cart logic here
                });
            }
        });
    </script>
</body>
</html>