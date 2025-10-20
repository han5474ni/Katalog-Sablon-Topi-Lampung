<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LGI Store - Semua Produk</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Arial', sans-serif;
            background-color: #0a1628;
            color: #ffffff;
        }

        /* Breadcrumb (adjust top padding for navbar) */
        .breadcrumb {
            background-color: #0f1b2e;
            padding: 15px 40px;
            font-size: 14px;
            color: #888;
        }

        .breadcrumb a {
            color: #888;
            text-decoration: none;
            transition: color 0.3s;
        }

        .breadcrumb a:hover {
            color: #ffa500;
        }

        .breadcrumb span {
            color: #fff;
            font-weight: 600;
        }

        /* Main Content */
        .main-content {
            display: flex;
            padding: 40px;
            gap: 30px;
            max-width: 1600px;
            margin: 0 auto;
        }

        /* Sidebar Filter */
        .sidebar {
            width: 280px;
            flex-shrink: 0;
        }

        .filter-section {
            background-color: #152238;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
        }

        .filter-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .filter-title i {
            font-size: 14px;
            cursor: pointer;
        }

        .filter-group {
            margin-bottom: 20px;
        }

        .filter-group-title {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 12px;
            color: #fff;
        }

        .filter-option {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            cursor: pointer;
        }

        .filter-option input[type="checkbox"] {
            margin-right: 10px;
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .filter-option label {
            color: #ccc;
            font-size: 14px;
            cursor: pointer;
            flex: 1;
        }

        .filter-option:hover label {
            color: #ffa500;
        }

        .price-range {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .price-input {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            padding: 8px;
            color: #fff;
            width: 100%;
            outline: none;
        }

        .price-input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .apply-filter-btn {
            background-color: #ffa500;
            color: #fff;
            border: none;
            padding: 12px;
            border-radius: 8px;
            width: 100%;
            font-weight: 600;
            cursor: pointer;
            margin-top: 20px;
            transition: all 0.3s;
        }

        .apply-filter-btn:hover {
            background-color: #ff8c00;
            transform: translateY(-2px);
        }

        /* Products Area */
        .products-area {
            flex: 1;
        }

        .products-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .products-title {
            font-size: 32px;
            font-weight: 800;
            color: #fff;
        }

        .products-count {
            color: #888;
            font-size: 14px;
            margin-top: 5px;
        }

        .sort-filter {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .view-toggle {
            display: flex;
            gap: 10px;
        }

        .view-btn {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .view-btn.active,
        .view-btn:hover {
            background-color: #ffa500;
            border-color: #ffa500;
        }

        .sort-select {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            padding: 10px 15px;
            border-radius: 8px;
            outline: none;
            cursor: pointer;
            font-size: 14px;
        }

        .sort-select option {
            background-color: #152238;
            color: #fff;
        }

        /* Product Grid */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .product-card {
            background-color: #e8e8e8;
            border-radius: 15px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(255, 165, 0, 0.3);
        }

        .product-card-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background-color: #ff4444;
            color: #fff;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            z-index: 1;
        }

        .product-card-badge.new {
            background-color: #ffa500;
        }

        .product-card-badge.sale {
            background-color: #ff4444;
        }

        .product-image-container {
            position: relative;
            overflow: hidden;
            height: 280px;
            background-color: #f5f5f5;
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .product-card:hover .product-image {
            transform: scale(1.1);
        }

        .product-actions {
            position: absolute;
            top: 15px;
            right: 15px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            opacity: 0;
            transform: translateX(20px);
            transition: all 0.3s;
        }

        .product-card:hover .product-actions {
            opacity: 1;
            transform: translateX(0);
        }

        .action-btn {
            background-color: #fff;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            border: none;
            color: #333;
        }

        .action-btn:hover {
            background-color: #ffa500;
            color: #fff;
        }

        .product-info {
            padding: 20px;
        }

        .product-category {
            color: #888;
            font-size: 12px;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .product-name {
            color: #333;
            font-size: 16px;
            margin-bottom: 10px;
            font-weight: 600;
            line-height: 1.4;
        }

        .product-rating {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 10px;
        }

        .stars {
            color: #ffa500;
            font-size: 14px;
        }

        .rating-count {
            color: #888;
            font-size: 12px;
        }

        .product-price {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .current-price {
            color: #ffa500;
            font-weight: 700;
            font-size: 16px;
        }

        .original-price {
            color: #888;
            font-size: 14px;
            text-decoration: line-through;
        }

        /* No Products */
        .no-products {
            text-align: center;
            padding: 60px 20px;
            color: #888;
        }

        .no-products i {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .no-products p {
            font-size: 18px;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 40px;
            flex-wrap: wrap;
        }

        .pagination-btn,
        .page-number {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
        }

        .pagination-btn:hover:not(:disabled),
        .page-number:hover {
            background-color: #ffa500;
            border-color: #ffa500;
        }

        .page-number.active {
            background-color: #ffa500;
            border-color: #ffa500;
        }

        .pagination-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Footer */
        footer {
            background-color: #0f1b2e;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 40px;
            margin-top: 40px;
        }

        @media (max-width: 768px) {
            header {
                flex-wrap: wrap;
                padding: 10px 20px;
            }

            .main-nav {
                order: 3;
                flex-basis: 100%;
                margin-top: 10px;
                gap: 15px;
                justify-content: flex-start;
                font-size: 12px;
            }

            .search-container {
                order: 2;
                margin-right: 10px;
            }

            .search-box {
                width: 200px;
                padding: 8px 15px 8px 35px;
            }

            .main-content {
                flex-direction: column;
                padding: 20px;
            }

            .sidebar {
                width: 100%;
            }

            .product-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 15px;
            }

            .products-header {
                flex-direction: column;
                gap: 15px;
            }

            .sort-filter {
                flex-direction: column;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar Component -->
    <x-navbar />

    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span> > </span>
        <span>Semua Produk</span>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Sidebar Filters -->
        <aside class="sidebar">
            <div class="filter-section">
                <div class="filter-title">
                    <span>Filters</span>
                    <i class="fas fa-sliders-h"></i>
                </div>

                <div class="filter-group">
                    <div class="filter-group-title">Kategori</div>
                    <div>
                        <div class="filter-option">
                            <input type="checkbox" id="cat-all">
                            <label for="cat-all">Semua Kategori</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" id="cat-topi">
                            <label for="cat-topi">Topi</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" id="cat-kaos">
                            <label for="cat-kaos">Kaos</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" id="cat-sablon">
                            <label for="cat-sablon">Sablon</label>
                        </div>
                    </div>
                </div>

                <div class="filter-group">
                    <div class="filter-group-title">Harga</div>
                    <div class="price-range">
                        <input type="number" class="price-input" id="price-min" placeholder="Min">
                        <span>-</span>
                        <input type="number" class="price-input" id="price-max" placeholder="Max">
                    </div>
                </div>

                <button class="apply-filter-btn">Terapkan Filter</button>
            </div>
        </aside>

        <!-- Products Area -->
        <div class="products-area">
            <div class="products-header">
                <div>
                    <h1 class="products-title">Semua Produk</h1>
                    <p class="products-count">Menampilkan {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} Produk</p>
                </div>
                <div class="sort-filter">
                    <select class="sort-select" id="sort-select">
                        <option value="most_popular" {{ request('sort') == 'most_popular' ? 'selected' : '' }}>Paling Populer</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga: Terendah ke Tertinggi</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga: Tertinggi ke Terendah</option>
                    </select>
                </div>
            </div>

            <div class="product-grid" id="products-grid">
                @forelse($products as $product)
                    <div class="product-card">
                        <div class="product-image-container">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/280x280?text=No+Image' }}" 
                                 alt="{{ $product->name }}"
                                 class="product-image"
                                 onerror="this.src='https://via.placeholder.com/280x280?text=No+Image'">
                            <div class="product-actions">
                                <button class="action-btn" title="Tambah ke Wishlist">
                                    <i class="fas fa-heart"></i>
                                </button>
                                <button class="action-btn" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="product-info">
                            <p class="product-category">Produk</p>
                            <h3 class="product-name">{{ $product->name }}</h3>
                            <div class="product-price">
                                <span class="current-price">Rp {{ number_format($product->formatted_price ?? $product->price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="no-products" style="grid-column: 1 / -1;">
                        <i class="fas fa-inbox"></i>
                        <p>Produk tidak ditemukan</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
            <div class="pagination" id="pagination-container">
                @if ($products->onFirstPage())
                    <button class="pagination-btn prev" disabled>
                        <i class="fas fa-chevron-left"></i> Sebelumnya
                    </button>
                @else
                    <a href="{{ $products->previousPageUrl() }}" class="pagination-btn prev">
                        <i class="fas fa-chevron-left"></i> Sebelumnya
                    </a>
                @endif
                
                <div style="display: flex; gap: 5px; flex-wrap: wrap; justify-content: center;">
                    @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                        @if ($page == $products->currentPage())
                            <button class="page-number active">{{ $page }}</button>
                        @else
                            <a href="{{ $url }}" class="page-number">{{ $page }}</a>
                        @endif
                    @endforeach
                </div>
                
                @if ($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}" class="pagination-btn next">
                        Selanjutnya <i class="fas fa-chevron-right"></i>
                    </a>
                @else
                    <button class="pagination-btn next" disabled>
                        Selanjutnya <i class="fas fa-chevron-right"></i>
                    </button>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Footer Component -->
    <x-guest-footer />

    <script>
        // Profile Popup Toggle
        const profileIcon = document.getElementById('profile-icon');
        const profilePopup = document.getElementById('profile-popup');

        if (profileIcon && profilePopup) {
            profileIcon.addEventListener('click', function() {
                profilePopup.classList.toggle('show');
            });

            document.addEventListener('click', function(e) {
                if (!profileIcon.contains(e.target) && !profilePopup.contains(e.target)) {
                    profilePopup.classList.remove('show');
                }
            });
        }

        // Search functionality
        const searchInput = document.getElementById('search-input');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    // Tambahkan parameter search ke URL
                    const searchValue = this.value;
                    const currentUrl = new URL(window.location);
                    if (searchValue) {
                        currentUrl.searchParams.set('search', searchValue);
                    } else {
                        currentUrl.searchParams.delete('search');
                    }
                    window.location.href = currentUrl.toString();
                }, 500);
            });
        }

        // Sort functionality
        const sortSelect = document.getElementById('sort-select');
        if (sortSelect) {
            sortSelect.addEventListener('change', function() {
                const currentUrl = new URL(window.location);
                if (this.value) {
                    currentUrl.searchParams.set('sort', this.value);
                }
                window.location.href = currentUrl.toString();
            });
        }
    </script>
</body>
</html>