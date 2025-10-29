<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>One Life Graphic T-Shirt - Product Detail</title>
    @vite(['resources/css/guest/catalog.css','resources/css/guest/all-products.css', 'resources/css/components/footer.css'])
</head>
<body>
    <x-navbar />

    <div class="breadcrumb">
        <a href="#">< Kembali ke Polo</a>
    </div>

    <div class="container">
        <div class="product-section">
            <div class="product-images">
                <div class="thumbnail-list">
                    <div class="thumbnail active">
                        <img src="https://via.placeholder.com/100x100/6b6b47/ffffff?text=1" alt="Thumbnail 1">
                    </div>
                    <div class="thumbnail">
                        <img src="https://via.placeholder.com/100x100/6b6b47/ffffff?text=2" alt="Thumbnail 2">
                    </div>
                    <div class="thumbnail">
                        <img src="https://via.placeholder.com/100x100/6b6b47/ffffff?text=3" alt="Thumbnail 3">
                    </div>
                </div>
                <div class="main-image">
                    <img src="https://via.placeholder.com/400x400/6b6b47/ffffff?text=One+Life" alt="One Life Graphic T-Shirt">
                </div>
            </div>

            <div class="product-info">
                <h1 class="product-title">One Life Graphic T-Shirt</h1>
                <p class="product-price">Rp 375.000</p>
                <p class="product-description">Kaos eksklusif dengan desain grafis "One Life" yang terinspirasi dari gaya streetwear modern. Dibuat dengan bahan katun premium 24s, memberikan kenyamanan maksimal sekaligus tampilan yang stylish untuk segala kesempatan.</p>

                <div class="option-group">
                    <p class="option-label">Pilih Warna</p>
                    <div class="color-options">
                        <div class="color-option color-olive active" title="Olive"></div>
                        <div class="color-option color-teal" title="Teal"></div>
                        <div class="color-option color-navy" title="Navy"></div>
                    </div>
                </div>

                <div class="option-group">
                    <p class="option-label">Pilih Ukuran</p>
                    <div class="size-options">
                        <div class="size-option">S</div>
                        <div class="size-option active">M</div>
                        <div class="size-option">L</div>
                        <div class="size-option">XL</div>
                    </div>
                </div>

                <div class="quantity-cart">
                    <div class="quantity-selector">
                        <button class="quantity-btn" type="button">-</button>
                        <span class="quantity-value">1</span>
                        <button class="quantity-btn" type="button">+</button>
                    </div>
                    <button class="add-to-cart" type="button">Tambahkan ke Keranjang</button>
                </div>
            </div>
        </div>

        <div class="tabs">
            <div class="tab active">Deskripsi</div>
            <div class="tab">Detail Produk</div>
            <div class="tab">Ulasan</div>
        </div>

        <div class="tab-content">
            <div class="detail-section">
                <h3 class="detail-title">Deskripsi Produk</h3>
                <p class="detail-text">Kaos ini dirancang untuk para pecinta gaya hidup aktif dengan sentuhan urban. Grafis "One Life" menunjukkan sikap berani dan optimis, membuat Anda tampil percaya diri di setiap momen.</p>
            </div>

            <div class="detail-section">
                <h3 class="detail-title">Material dan Perawatan</h3>
                <ul class="detail-list">
                    <li>100% katun combed 24s premium</li>
                    <li>Tahan lama dan tidak mudah melar</li>
                    <li>Teknik sablon plastisol berkualitas tinggi</li>
                    <li>Disarankan cuci tangan dengan air dingin</li>
                </ul>
            </div>

            <div class="detail-section">
                <h3 class="detail-title">Panduan Ukuran</h3>
                <p class="detail-text">Tersedia dalam ukuran S hingga XL. Silakan cek tabel ukuran untuk memastikan kenyamanan terbaik bagi Anda.</p>
            </div>
        </div>
    </div>

    <div class="recommendations">
        <h2 class="recommendations-title">Rekomendasi Produk Lain</h2>
        <div class="product-grid">
            <div class="product-card">
                <img class="product-card-image" src="https://via.placeholder.com/300x300/2a3a5a/ffffff?text=Streetwear" alt="Streetwear Series">
                <div class="product-card-info">
                    <p class="product-card-title">Streetwear Series Hoodie</p>
                    <p class="product-card-price">Rp 425.000</p>
                    <div class="product-actions" role="group" aria-label="Aksi produk">
                        <button class="action-btn action-chat" type="button" aria-label="Chat tentang produk">
                            <i class="fas fa-comments" aria-hidden="true"></i>
                        </button>
                        <button class="action-btn action-cart" type="button" aria-label="Tambahkan ke keranjang">
                            <i class="fas fa-shopping-cart" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="product-card">
                <img class="product-card-image" src="https://via.placeholder.com/300x300/4a6b6b/ffffff?text=Minimal" alt="Minimal Logo Tee">
                <div class="product-card-info">
                    <p class="product-card-title">Minimal Logo Tee</p>
                    <p class="product-card-price">Rp 295.000</p>
                    <div class="product-actions" role="group" aria-label="Aksi produk">
                        <button class="action-btn action-chat" type="button" aria-label="Chat tentang produk">
                            <i class="fas fa-comments" aria-hidden="true"></i>
                        </button>
                        <button class="action-btn action-cart" type="button" aria-label="Tambahkan ke keranjang">
                            <i class="fas fa-shopping-cart" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="product-card">
                <img class="product-card-image" src="https://via.placeholder.com/300x300/6b6b47/ffffff?text=Classic" alt="Classic Varsity Jacket">
                <div class="product-card-info">
                    <p class="product-card-title">Classic Varsity Jacket</p>
                    <p class="product-card-price">Rp 650.000</p>
                    <div class="product-actions" role="group" aria-label="Aksi produk">
                        <button class="action-btn action-chat" type="button" aria-label="Chat tentang produk">
                            <i class="fas fa-comments" aria-hidden="true"></i>
                        </button>
                        <button class="action-btn action-cart" type="button" aria-label="Tambahkan ke keranjang">
                            <i class="fas fa-shopping-cart" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="product-card">
                <img class="product-card-image" src="https://via.placeholder.com/300x300/152d5a/ffffff?text=Limited" alt="Limited Edition Cap">
                <div class="product-card-info">
                    <p class="product-card-title">Limited Edition Cap</p>
                    <p class="product-card-price">Rp 175.000</p>
                    <div class="product-actions" role="group" aria-label="Aksi produk">
                        <button class="action-btn action-chat" type="button" aria-label="Chat tentang produk">
                            <i class="fas fa-comments" aria-hidden="true"></i>
                        </button>
                        <button class="action-btn action-cart" type="button" aria-label="Tambahkan ke keranjang">
                            <i class="fas fa-shopping-cart" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-guest-footer />
</body>
</html>