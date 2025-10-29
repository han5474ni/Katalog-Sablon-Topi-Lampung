<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Produk - LGI Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/guest/catalog.css', 'resources/css/components/footer.css'])
</head>
<body>
    <x-navbar />

    <section class="catalog-breadcrumb-section">
        <div class="catalog-inner-container">
            <nav aria-label="breadcrumb" class="catalog-breadcrumb-nav">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">
                            <i class="fas fa-chevron-left"></i>
                            Beranda
                        </a>
                    </li>
                    <li class="breadcrumb-separator">
                        <i class="fas fa-chevron-right"></i>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Semua Produk</li>
                </ol>
            </nav>
        </div>
    </section>

    <section class="catalog-content-section">
        <div class="catalog-inner-container">
            <div class="catalog-layout">
                <aside class="sidebar">
                    <div class="filters-card filters-section">
                        <div class="filter-header">
                            <h5>Filter Produk</h5>
                            <i class="fas fa-sliders-h"></i>
                        </div>

                        <div class="filter-section">
                            <h4>Kategori</h4>
                            <i class="fas fa-chevron-right"></i>
                        </div>

                        <div class="filter-section">
                            <h4>Topi</h4>
                            <i class="fas fa-chevron-right"></i>
                        </div>

                        <div class="filter-section">
                            <h4>Kaos</h4>
                            <i class="fas fa-chevron-right"></i>
                        </div>

                        <div class="filter-group">
                            <div class="filter-group-header">
                                <h4>Warna</h4>
                                <i class="fas fa-chevron-up"></i>
                            </div>
                            <div class="color-options">
                                <div class="color-option" style="background-color: #00ff00;" data-color="green"></div>
                                <div class="color-option" style="background-color: #ff0000;" data-color="red"></div>
                                <div class="color-option" style="background-color: #ffff00;" data-color="yellow"></div>
                                <div class="color-option" style="background-color: #ff8800;" data-color="orange"></div>
                                <div class="color-option" style="background-color: #00bfff;" data-color="cyan"></div>
                                <div class="color-option" style="background-color: #0000ff;" data-color="blue"></div>
                                <div class="color-option" style="background-color: #8800ff;" data-color="purple"></div>
                                <div class="color-option" style="background-color: #ff00ff;" data-color="pink"></div>
                                <div class="color-option" style="background-color: #ffffff; border: 1px solid #ddd;" data-color="white"></div>
                                <div class="color-option" style="background-color: #000000;" data-color="black"></div>
                            </div>
                        </div>

                        <div class="filter-group">
                            <div class="filter-group-header">
                                <h4>Ukuran</h4>
                                <i class="fas fa-chevron-up"></i>
                            </div>
                            <div class="size-options">
                                <button class="size-option">XX-Small</button>
                                <button class="size-option">X-Small</button>
                                <button class="size-option">Small</button>
                                <button class="size-option">Medium</button>
                                <button class="size-option active">Large</button>
                                <button class="size-option">X-Large</button>
                                <button class="size-option">2X-Large</button>
                                <button class="size-option">3X-Large</button>
                                <button class="size-option">4X-Large</button>
                            </div>
                        </div>

                        <button class="apply-filter-btn">Terapkan</button>
                    </div>
                </aside>

                <main class="products-main">
                    <div class="products-header">
                        <h2>Katalog Produk</h2>
                        <div class="sort-info">
                            <small id="products-count">{{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} produk</small>
                            <div class="sort-select">
                                <span>Urutkan:</span>
                                <div class="select-wrapper">
                                    <select id="sort-select">
                                        <option value="most_popular" {{ request('sort') == 'most_popular' ? 'selected' : '' }}>Paling Populer</option>
                                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                                    </select>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="products-grid" id="products-grid">
                        @forelse($products as $product)
                            <div class="product-card"
                                 data-product-id="{{ $product->id }}"
                                 data-product-name="{{ $product->name }}"
                                 data-product-price="{{$product->formatted_price}}"
                                 data-product-image="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300x300?text=No+Image' }}">
                                <div class="product-image">
                                    <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300x300?text=No+Image' }}"
                                         alt="{{ $product->name }}"
                                         onerror="this.src='https://via.placeholder.com/300x300?text=No+Image'">
                                    @if(!empty($product->custom_design_allowed) && $product->custom_design_allowed)
                                        <div class="product-ribbon small" aria-hidden="true">CUSTOM</div>
                                    @endif
                                </div>
                                <div class="product-info">
                                    <h6 class="product-name">{{ $product->name }}</h6>
                                    <div class="product-price">Rp {{$product->formatted_price}}</div>
                                </div>
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
                            <div class="no-products" style="grid-column: 1 / -1;">
                                <i class="fas fa-search"></i>
                                <p>Tidak ada produk ditemukan</p>
                                <small>Coba ubah filter atau kata kunci pencarian</small>
                            </div>
                        @endforelse
                    </div>

                    @if($products->hasPages())
                    <div class="pagination" id="pagination-container">
                        @if ($products->onFirstPage())
                            <button class="pagination-btn prev" disabled>
                                <i class="fas fa-chevron-left"></i> Sebelumnya
                            </button>
                        @else
                            <a href="{{ $products->previousPageUrl() }}" class="pagination-btn prev pagination-link">
                                <i class="fas fa-chevron-left"></i> Sebelumnya
                            </a>
                        @endif

                        <div class="pagination-numbers">
                            @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                                @if ($page == $products->currentPage())
                                    <button class="page-number active">{{ $page }}</button>
                                @else
                                    <a href="{{ $url }}" class="page-number pagination-link">{{ $page }}</a>
                                @endif
                            @endforeach
                        </div>

                        @if ($products->hasMorePages())
                            <a href="{{ $products->nextPageUrl() }}" class="pagination-btn next pagination-link">
                                Selanjutnya <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <button class="pagination-btn next" disabled>
                                Selanjutnya <i class="fas fa-chevron-right"></i>
                            </button>
                        @endif
                    </div>
                    @endif
                </main>
            </div>
        </div>
    </section>

    <x-guest-footer />

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const colorOptions = document.querySelectorAll('.color-option');
            colorOptions.forEach(option => {
                option.addEventListener('click', function() {
                    this.classList.toggle('active');
                });
            });

            const sizeOptions = document.querySelectorAll('.size-option');
            sizeOptions.forEach(option => {
                option.addEventListener('click', function() {
                    sizeOptions.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                });
            });

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
        });
    </script>
</body>
</html>