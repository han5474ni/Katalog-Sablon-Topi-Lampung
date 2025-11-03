<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $categoryName }} - LGI Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/guest/catalog.css', 'resources/css/guest/catalog-inline.css', 'resources/css/components/footer.css', 'resources/js/guest/catalog.js'])
</head>
<body>
    <x-navbar />

    <section class="catalog-breadcrumb">
        <div class="breadcrumb-container">
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="breadcrumb-back">
                    <span aria-hidden="true">&lt;</span>
                    Beranda
                </a>
                <li class="breadcrumb-separator"><i class="fas fa-chevron-right"></i></li>
                <span class="breadcrumb-current">{{ $categoryName }}</span>
            </nav>
        </div>
    </section>

    <section class="catalog-section">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">{{ $categoryName }}</h1>
                <div class="sort-info">
                    <span id="products-count">Menampilkan {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} dari {{ $totalProducts }} Produk</span>
                    <span>Urut berdasarkan:</span>
                    <select class="sort-select" id="sort-select">
                        <option value="most_popular" {{ request('sort') == 'most_popular' ? 'selected' : '' }}>Terkait</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga: Rendah ke Tinggi</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga: Tinggi ke Rendah</option>
                    </select>
                </div>
            </div>

            <div class="content-wrapper">
                <!-- ================= FILTER SECTION (UPDATED) ================= -->
                <aside class="sidebar">
                    <div class="filter-container">
                        <div class="filter-header">
                            <h3>Filters</h3>
                            <i class="fas fa-sliders-h"></i>
                        </div>

                        <div class="filter-section">
                            <div class="filter-title-row">
                                <span class="filter-title">Colors</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="color-options">
                                <button class="color-btn" style="background-color:#22c55e"></button>
                                <button class="color-btn" style="background-color:#ef4444"></button>
                                <button class="color-btn" style="background-color:#facc15"></button>
                                <button class="color-btn" style="background-color:#f97316"></button>
                                <button class="color-btn" style="background-color:#06b6d4"></button>
                                <button class="color-btn active" style="background-color:#2563eb"></button>
                                <button class="color-btn" style="background-color:#a855f7"></button>
                                <button class="color-btn" style="background-color:#ec4899"></button>
                                <button class="color-btn" style="background-color:#ffffff; border:1px solid #e5e7eb;"></button>
                                <button class="color-btn" style="background-color:#000000"></button>
                            </div>
                        </div>

                        <div class="filter-section">
                            <div class="filter-title-row">
                                <span class="filter-title">Size</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="size-options">
                                <button class="size-btn">XX-Small</button>
                                <button class="size-btn">X-Small</button>
                                <button class="size-btn">Small</button>
                                <button class="size-btn">Medium</button>
                                <button class="size-btn active">Large</button>
                                <button class="size-btn">X-Large</button>
                                <button class="size-btn">XX-Large</button>
                                <button class="size-btn">3X-Large</button>
                                <button class="size-btn">4X-Large</button>
                            </div>
                        </div>

                        <button class="apply-filter-btn">Apply Filter</button>
                    </div>
                </aside>
                <!-- ============================================================= -->

                <main class="products-section">
                    <div class="products-grid" id="products-grid">
                        @forelse($products as $product)
                            <div class="product-card"
                                 data-product-id="{{ $product->id }}"
                                 data-product-name="{{ $product->name }}"
                                 data-product-price="{{ $product->formatted_price }}"
                                 data-product-image="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300x300?text=No+Image' }}">
                                <div class="product-image-container">
                                    <img class="product-image" src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300x300?text=No+Image' }}" alt="{{ $product->name }}" onerror="this.src='https://via.placeholder.com/300x300?text=No+Image'">
                                    @if(!empty($product->custom_design_allowed) && $product->custom_design_allowed)
                                        <div class="product-ribbon" aria-hidden="true">CUSTOM</div>
                                    @endif
                                </div>
                                <div class="product-info">
                                    <h3 class="product-title">{{ $product->name }}</h3>
                                    <p class="product-price">Rp {{ $product->formatted_price }}</p>
                                    <div class="product-actions" role="group" aria-label="Aksi produk">
                                        <button class="action-btn action-chat" type="button" aria-label="Chat tentang produk">
                                            <i class="fas fa-comments" aria-hidden="true"></i>
                                        </button>
                                        <button class="action-btn action-cart" type="button" aria-label="Tambahkan ke keranjang" data-product-id="{{ $product->id }}">
                                            <i class="fas fa-shopping-cart" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="no-products">
                                <i class="fas fa-inbox"></i>
                                <p>Tidak ada produk dalam kategori ini</p>
                            </div>
                        @endforelse
                    </div>

                    @if($products->hasPages())
                        <div class="pagination" id="pagination-container">
                            @if ($products->onFirstPage())
                                <button class="pagination-btn" disabled>
                                    <i class="fas fa-chevron-left"></i>
                                    Sebelumnya
                                </button>
                            @else
                                <a href="{{ $products->previousPageUrl() }}" class="pagination-btn pagination-link">
                                    <i class="fas fa-chevron-left"></i>
                                    Sebelumnya
                                </a>
                            @endif

                            <div class="pagination-numbers">
                                @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                                    @if ($page == $products->currentPage())
                                        <button class="pagination-number active">{{ $page }}</button>
                                    @else
                                        <a href="{{ $url }}" class="pagination-number pagination-link">{{ $page }}</a>
                                    @endif
                                @endforeach
                            </div>

                            @if ($products->hasMorePages())
                                <a href="{{ $products->nextPageUrl() }}" class="pagination-btn pagination-link">
                                    Selanjutnya
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            @else
                                <button class="pagination-btn" disabled>
                                    Selanjutnya
                                    <i class="fas fa-chevron-right"></i>
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
        // Optional: toggle active state
        document.querySelectorAll('.color-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.color-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
            });
        });

        document.querySelectorAll('.size-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
            });
        });
    </script>
</body>
</html>