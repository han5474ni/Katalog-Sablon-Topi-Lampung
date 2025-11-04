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
    @php
        $selectedColors = array_unique($currentFilters['colors'] ?? []);
        $selectedSizes = array_unique($currentFilters['sizes'] ?? []);
    @endphp
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
                                @forelse ($availableColors as $color)
                                    @php
                                        $normalizedColor = strtolower($color);
                                        $needsBorder = in_array($normalizedColor, ['#fff', '#ffffff', 'white']);
                                    @endphp
                                    <button type="button"
                                        class="color-btn{{ in_array($color, $selectedColors, true) ? ' active' : '' }}"
                                        data-color="{{ $color }}"
                                        style="background-color: {{ $color }};{{ $needsBorder ? ' border:1px solid #e5e7eb;' : '' }}">
                                    </button>
                                @empty
                                    <span class="no-filter-option">Tidak ada warna tersedia</span>
                                @endforelse
                            </div>
                        </div>

                        <div class="filter-section">
                            <div class="filter-title-row">
                                <span class="filter-title">Size</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="size-options">
                                @forelse ($availableSizes as $size)
                                    <button type="button"
                                        class="size-btn{{ in_array($size, $selectedSizes, true) ? ' active' : '' }}"
                                        data-size="{{ $size }}">
                                        {{ $size }}
                                    </button>
                                @empty
                                    <span class="no-filter-option">Tidak ada ukuran tersedia</span>
                                @endforelse
                            </div>
                        </div>

                        <button type="button" class="apply-filter-btn">Apply Filter</button>
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
        document.addEventListener('DOMContentLoaded', () => {
            const colorButtons = document.querySelectorAll('.color-btn');
            const sizeButtons = document.querySelectorAll('.size-btn');
            const applyFilterBtn = document.querySelector('.apply-filter-btn');
            const sortSelect = document.getElementById('sort-select');

            const colorSelections = new Set(@json($selectedColors));
            const sizeSelections = new Set(@json($selectedSizes));

            colorButtons.forEach(btn => {
                const colorValue = btn.dataset.color;

                if (colorSelections.has(colorValue)) {
                    btn.classList.add('active');
                }

                btn.addEventListener('click', () => {
                    if (colorSelections.has(colorValue)) {
                        colorSelections.delete(colorValue);
                        btn.classList.remove('active');
                    } else {
                        colorSelections.add(colorValue);
                        btn.classList.add('active');
                    }
                });
            });

            sizeButtons.forEach(btn => {
                const sizeValue = btn.dataset.size;

                if (sizeSelections.has(sizeValue)) {
                    btn.classList.add('active');
                }

                btn.addEventListener('click', () => {
                    if (sizeSelections.has(sizeValue)) {
                        sizeSelections.delete(sizeValue);
                        btn.classList.remove('active');
                    } else {
                        sizeSelections.add(sizeValue);
                        btn.classList.add('active');
                    }
                });
            });

            applyFilterBtn?.addEventListener('click', () => {
                const url = new URL(window.location.href);
                const params = url.searchParams;

                if (colorSelections.size > 0) {
                    params.set('colors', Array.from(colorSelections).join(','));
                } else {
                    params.delete('colors');
                }

                if (sizeSelections.size > 0) {
                    params.set('sizes', Array.from(sizeSelections).join(','));
                } else {
                    params.delete('sizes');
                }

                params.delete('page');

                window.location.href = `${url.pathname}?${params.toString()}`;
            });

            sortSelect?.addEventListener('change', () => {
                const url = new URL(window.location.href);
                const params = url.searchParams;
                params.set('sort', sortSelect.value);
                params.delete('page');
                window.location.href = `${url.pathname}?${params.toString()}`;
            });
        });
    </script>
</body>
</html>