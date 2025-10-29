<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>One Life Graphic T-Shirt - Product Detail</title>
    @vite(['resources/css/guest/catalog.css', 'resources/css/components/footer.css'])
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }

        .breadcrumb {
            padding: 20px 40px;
            background-color: white;
        }

        .breadcrumb a {
            color: #666;
            text-decoration: none;
            font-size: 14px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px;
            background-color: white;
        }

        .product-section {
            display: flex;
            gap: 60px;
            margin-bottom: 60px;
        }

        .product-images {
            flex: 1;
            display: flex;
            gap: 20px;
        }

        .thumbnail-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .thumbnail {
            width: 100px;
            height: 100px;
            border: 2px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            overflow: hidden;
            transition: border-color 0.3s;
        }

        .thumbnail:hover,
        .thumbnail.active {
            border-color: #0a1f44;
        }

        .thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .main-image {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f8f8;
            border-radius: 12px;
            padding: 40px;
        }

        .main-image img {
            max-width: 100%;
            height: auto;
        }

        .product-info {
            flex: 1;
        }

        .product-title {
            font-size: 36px;
            font-weight: bold;
            color: #0a1f44;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .product-price {
            font-size: 32px;
            font-weight: bold;
            color: #d4af37;
            margin-bottom: 20px;
        }

        .product-description {
            color: #666;
            line-height: 1.6;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .option-group {
            margin-bottom: 30px;
        }

        .option-label {
            font-weight: 600;
            margin-bottom: 12px;
            color: #333;
            font-size: 14px;
        }

        .color-options {
            display: flex;
            gap: 12px;
        }

        .color-option {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            border: 3px solid transparent;
            transition: all 0.3s;
        }

        .color-option:hover,
        .color-option.active {
            border-color: #0a1f44;
            transform: scale(1.1);
        }

        .color-olive {
            background-color: #6b6b47;
        }

        .color-teal {
            background-color: #4a6b6b;
        }

        .color-navy {
            background-color: #2a3a5a;
        }

        .size-options {
            display: flex;
            gap: 12px;
        }

        .size-option {
            padding: 12px 24px;
            border: 2px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            background-color: white;
            font-weight: 500;
            transition: all 0.3s;
            font-size: 14px;
        }

        .size-option:hover {
            border-color: #666;
        }

        .size-option.active {
            background-color: #0a1f44;
            color: white;
            border-color: #0a1f44;
        }

        .quantity-cart {
            display: flex;
            gap: 20px;
            align-items: center;
            margin-top: 30px;
        }

        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 20px;
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 8px 20px;
        }

        .quantity-btn {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #333;
            padding: 4px 8px;
        }

        .quantity-value {
            font-size: 16px;
            font-weight: 600;
            min-width: 30px;
            text-align: center;
        }

        .add-to-cart {
            flex: 1;
            background-color: #0a1f44;
            color: white;
            border: none;
            padding: 16px 40px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .add-to-cart:hover {
            background-color: #152d5a;
        }

        .tabs {
            border-bottom: 2px solid #ddd;
            display: flex;
            gap: 40px;
            margin-bottom: 30px;
        }

        .tab {
            padding: 15px 0;
            cursor: pointer;
            font-weight: 600;
            color: #666;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
        }

        .tab.active {
            color: #0a1f44;
            border-bottom-color: #0a1f44;
        }

        .tab-content {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 40px;
            margin-bottom: 60px;
        }

        .detail-section {
            margin-bottom: 25px;
        }

        .detail-title {
            font-weight: bold;
            margin-bottom: 10px;
            color: #0a1f44;
        }

        .detail-text {
            color: #555;
            line-height: 1.8;
            font-size: 14px;
        }

        .detail-list {
            list-style-position: inside;
            color: #555;
            line-height: 1.8;
            margin-left: 20px;
            font-size: 14px;
        }

        .recommendations {
            background-color: #0a1f44;
            padding: 60px 40px;
        }

        .recommendations-title {
            text-align: center;
            color: white;
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 40px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .product-card {
            background-color: #f5f5f5;
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.3s;
            cursor: pointer;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-card-image {
            width: 100%;
            aspect-ratio: 1;
            object-fit: cover;
            background-color: white;
        }

        .product-card-info {
            padding: 20px;
        }

        .product-card-title {
            font-weight: 600;
            margin-bottom: 10px;
            color: #0a1f44;
            font-size: 16px;
        }

        .product-card-price {
            color: #d4af37;
            font-weight: bold;
            font-size: 18px;
        }

        @media (max-width: 768px) {
            .product-section {
                flex-direction: column;
            }

            .product-images {
                flex-direction: column-reverse;
            }

            .thumbnail-list {
                flex-direction: row;
            }

            .quantity-cart {
                flex-direction: column;
            }

            .container {
                padding: 20px;
            }

            .product-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
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
