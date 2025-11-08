<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $categoryName }} - LGI Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/guest/catalog.css', 'resources/css/guest/catalog-inline.css', 'resources/css/components/footer.css', 'resources/css/components/product-card.css', 'resources/js/guest/catalog.js', 'resources/js/guest/product-card-carousel.js'])
</head>
<body>
    @php
        $selectedColors = array_unique($currentFilters['colors'] ?? []);
        $selectedSizes = array_unique($currentFilters['sizes'] ?? []);
        $selectedSubcategories = array_unique($currentFilters['subcategories'] ?? []);
        $isPromo = $currentFilters['promo'] ?? false;
        $isReady = $currentFilters['ready'] ?? false;
        $isCustom = $currentFilters['custom'] ?? false;
        $minPriceValue = request('min_price', 0);
        $maxPriceValue = request('max_price', 2500000);
        $minPriceDisplay = 'Rp ' . number_format($minPriceValue, 0, ',', '.');
        $maxPriceDisplay = 'Rp ' . number_format($maxPriceValue, 0, ',', '.');
    @endphp
    <x-navbar />

    <section class="catalog-breadcrumb">
        <div class="breadcrumb-container">
            <a href="{{ route('home') }}" class="breadcrumb-back">
                <i class="fas fa-chevron-left"></i>
                Kembali ke beranda
            </a>
        </div>
    </section>

    <section class="catalog-section">
        <div class="container">
            <div class="content-wrapper-new">
                <aside class="sidebar">
                    <div class="filter-container">
                        <!-- Quick Filters -->
                        <div class="filter-section">
                            <div class="filter-checkbox-list">
                                <label class="filter-checkbox">
                                    <input type="checkbox" name="promo" id="promo-filter" {{ $isPromo ? 'checked' : '' }}>
                                    <span>Dengan diskon</span>
                                </label>
                                <label class="filter-checkbox">
                                    <input type="checkbox" name="ready" id="ready-filter" {{ $isReady ? 'checked' : '' }}>
                                    <span>Ready stok</span>
                                </label>
                                <label class="filter-checkbox">
                                    <input type="checkbox" name="custom" id="custom-filter" {{ $isCustom ? 'checked' : '' }}>
                                    <span>Kustomisasi</span>
                                </label>
                            </div>
                        </div>

                        <!-- Subcategory Filter -->
                        <div class="filter-section">
                            <div class="filter-title-row">
                                <span class="filter-title">Sub Kategori</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="filter-checkbox-list">
                                @forelse ($availableSubcategories as $subcategory)
                                    <label class="filter-checkbox">
                                        <input type="checkbox" name="subcategories[]" value="{{ $subcategory }}" {{ in_array($subcategory, $selectedSubcategories) ? 'checked' : '' }}>
                                        <span>{{ ucwords(str_replace('-', ' ', $subcategory)) }}</span>
                                    </label>
                                @empty
                                    <span class="no-filter-option">Tidak ada sub kategori tersedia</span>
                                @endforelse
                            </div>
                        </div>

                        <!-- Colors Filter -->
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

                        <!-- Size Filter -->
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

                        <!-- Price Range Filter -->
                        <div class="filter-section">
                            <div class="filter-title-row">
                                <span class="filter-title">Harga</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="price-range-wrapper">
                                <div class="price-inputs">
                                    <input type="text" class="price-input" id="min-price" placeholder="Rp 0" value="{{ $minPriceDisplay }}">
                                    <span class="price-separator">-</span>
                                    <input type="text" class="price-input" id="max-price" placeholder="Rp 2.500.000" value="{{ $maxPriceDisplay }}">
                                </div>
                                <div class="price-slider-container">
                                    <input type="range" id="price-range-min" min="0" max="2500000" value="{{ $minPriceValue }}">
                                    <input type="range" id="price-range-max" min="0" max="2500000" value="{{ $maxPriceValue }}">
                                </div>
                            </div>
                        </div>

                        <button type="button" class="apply-filter-btn">Apply Filter</button>
                    </div>
                </aside>

                <main class="products-section">
                    <div class="products-header-inline">
                        <div class="header-title-section">
                            <h1 class="page-title-inline">{{ $categoryName }}</h1>
                            <span class="products-count-inline" id="products-count">Menampilkan {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} dari {{ $totalProducts }} Produk</span>
                        </div>
                        <div class="header-sort-section">
                            <label for="sort-select">Urut berdasarkan:</label>
                            <select class="sort-select" id="sort-select">
                                <option value="most_popular" {{ request('sort') == 'most_popular' ? 'selected' : '' }}>Paling Populer</option>
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga: Rendah ke Tinggi</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga: Tinggi ke Rendah</option>
                            </select>
                        </div>
                    </div>

                    <div class="products-grid" id="products-grid">
                        @forelse($products as $product)
                            <x-product-card :product="$product" />
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
            const subcategoryCheckboxes = document.querySelectorAll('input[name="subcategories[]"]');
            const quickFilters = {
                promo: document.getElementById('promo-filter'),
                ready: document.getElementById('ready-filter'),
                custom: document.getElementById('custom-filter')
            };
            const applyFilterBtn = document.querySelector('.apply-filter-btn');
            const sortSelect = document.getElementById('sort-select');

            const colorSelections = new Set(@json($selectedColors));
            const sizeSelections = new Set(@json($selectedSizes));
            
            // Price range slider functionality
            const priceRangeMin = document.getElementById('price-range-min');
            const priceRangeMax = document.getElementById('price-range-max');
            const minPriceInput = document.getElementById('min-price');
            const maxPriceInput = document.getElementById('max-price');
            
            function formatRupiah(value) {
                return 'Rp ' + parseInt(value).toLocaleString('id-ID');
            }
            
            function parseRupiah(value) {
                return parseInt(value.replace(/[^0-9]/g, '')) || 0;
            }
            
            if (priceRangeMin && priceRangeMax && minPriceInput && maxPriceInput) {
                // Update input fields when sliders change
                priceRangeMin.addEventListener('input', function() {
                    const minVal = parseInt(this.value);
                    const maxVal = parseInt(priceRangeMax.value);
                    
                    if (minVal > maxVal - 50000) {
                        this.value = maxVal - 50000;
                    }
                    
                    minPriceInput.value = formatRupiah(this.value);
                });
                
                priceRangeMax.addEventListener('input', function() {
                    const minVal = parseInt(priceRangeMin.value);
                    const maxVal = parseInt(this.value);
                    
                    if (maxVal < minVal + 50000) {
                        this.value = minVal + 50000;
                    }
                    
                    maxPriceInput.value = formatRupiah(this.value);
                });
                
                // Update sliders when input fields change
                minPriceInput.addEventListener('blur', function() {
                    const value = parseRupiah(this.value);
                    const maxVal = parseInt(priceRangeMax.value);
                    
                    if (value < 0) this.value = formatRupiah(0);
                    if (value > maxVal - 50000) this.value = formatRupiah(maxVal - 50000);
                    
                    priceRangeMin.value = parseRupiah(this.value);
                });
                
                maxPriceInput.addEventListener('blur', function() {
                    const value = parseRupiah(this.value);
                    const minVal = parseInt(priceRangeMin.value);
                    
                    if (value > 2500000) this.value = formatRupiah(2500000);
                    if (value < minVal + 50000) this.value = formatRupiah(minVal + 50000);
                    
                    priceRangeMax.value = parseRupiah(this.value);
                });
            }

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

                // Handle quick filters
                if (quickFilters.promo?.checked) {
                    params.set('promo', '1');
                } else {
                    params.delete('promo');
                }

                if (quickFilters.ready?.checked) {
                    params.set('ready', '1');
                } else {
                    params.delete('ready');
                }

                if (quickFilters.custom?.checked) {
                    params.set('custom', '1');
                } else {
                    params.delete('custom');
                }

                // Handle subcategories
                const selectedSubcategories = Array.from(subcategoryCheckboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.value);
                
                if (selectedSubcategories.length > 0) {
                    params.set('subcategories', selectedSubcategories.join(','));
                } else {
                    params.delete('subcategories');
                }

                // Handle colors
                if (colorSelections.size > 0) {
                    params.set('colors', Array.from(colorSelections).join(','));
                } else {
                    params.delete('colors');
                }

                // Handle sizes
                if (sizeSelections.size > 0) {
                    params.set('sizes', Array.from(sizeSelections).join(','));
                } else {
                    params.delete('sizes');
                }

                // Handle price range - use slider values
                const minPriceSlider = document.getElementById('price-range-min');
                const maxPriceSlider = document.getElementById('price-range-max');
                
                if (minPriceSlider && maxPriceSlider) {
                    const minPrice = parseInt(minPriceSlider.value) || 0;
                    const maxPrice = parseInt(maxPriceSlider.value) || 2500000;
                    
                    if (minPrice > 0) {
                        params.set('min_price', minPrice);
                    } else {
                        params.delete('min_price');
                    }
                    
                    if (maxPrice < 2500000) {
                        params.set('max_price', maxPrice);
                    } else {
                        params.delete('max_price');
                    }
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