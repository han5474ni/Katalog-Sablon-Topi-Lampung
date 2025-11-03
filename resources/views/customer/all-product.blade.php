<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LGI Store - Semua Produk</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/guest/catalog.css', 'resources/css/components/footer.css'])
</head>
<body>
    <x-navbar />

    <section class="catalog-breadcrumb-section">
        <div class="catalog-inner-container">
            <nav aria-label="breadcrumb" class="catalog-breadcrumb-nav">
                <nav class="breadcrumb">
    <a href="{{ route('home') }}">
        <i class="fas fa-chevron-left"></i>
        Kembali ke beranda
    </a>
</nav>
            </nav>
        </div>
    </section>

    <section class="catalog-content-section">
        <div class="catalog-inner-container">
            <div class="catalog-layout">
                <aside class="sidebar">
                    <div class="filters-card filters-section">
                        <div class="filter-header">
                            <h5>Filters</h5>
                            <i class="fas fa-sliders-h"></i>
                        </div>

                        <!-- Promo dan lainnya Section -->
                        <div class="filter-checkbox-section">
                            <div class="checkbox-item">
                                <input type="checkbox" id="promo-diskon" name="promo">
                                <label for="promo-diskon">Dengan diskon</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="ready-stok" name="ready">
                                <label for="ready-stok">Ready stok</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="kustomisasi" name="custom">
                                <label for="kustomisasi">Kustomisasi</label>
                            </div>
                        </div>

                        <!-- Kategori Section -->
                        <div class="filter-group-section">
                            <div class="filter-group-title">
                                <h4>Kategori</h4>
                            </div>
                            <div class="filter-checkbox-list">
                                <div class="checkbox-item">
                                    <input type="checkbox" id="kategori-topi" name="kategori" value="topi">
                                    <label for="kategori-topi">Topi</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="kategori-celana" name="kategori" value="celana">
                                    <label for="kategori-celana">Celana</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="kategori-polo" name="kategori" value="polo">
                                    <label for="kategori-polo">Polo</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="kategori-jaket" name="kategori" value="jaket">
                                    <label for="kategori-jaket">Jaket</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="kategori-jersey" name="kategori" value="jersey">
                                    <label for="kategori-jersey">Jersey</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="kategori-lainlain" name="kategori" value="lain-lain">
                                    <label for="kategori-lainlain">Lain-lain</label>
                                </div>
                            </div>
                        </div>

                        <!-- Harga Section -->
                        <div class="filter-group-section">
                            <div class="filter-group-title">
                                <h4>Harga</h4>
                            </div>
                            <div class="price-range-wrapper">
                                <div class="price-inputs">
                                    <input type="text" class="price-input" id="min-price" placeholder="Rp 0" value="Rp 0">
                                    <span class="price-separator">-</span>
                                    <input type="text" class="price-input" id="max-price" placeholder="Rp 5.000.000" value="Rp 5.000.000">
                                </div>
                                <div class="price-slider-container">
                                    <div class="price-slider-track"></div>
                                    <div class="price-slider-range"></div>
                                </div>
                            </div>
                        </div>

                        <button class="apply-filter-btn">Apply Filter</button>
                    </div>
                </aside>

                <main class="products-main">
                    <div class="products-header">
                        <h2>Semua Produk</h2>
                        <div class="sort-info">
                            <small id="products-count">Menampilkan {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} Produk.</small>
                            <div class="sort-select">
                                <span>Urut berdasarkan:</span>
                                <div class="select-wrapper">
                                    <select id="sort-select">
                                        <option value="most_popular" {{ request('sort') == 'most_popular' ? 'selected' : '' }}>Paling Populer</option>
                                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga: Terendah ke Tertinggi</option>
                                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga: Tertinggi ke Terendah</option>
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
                                 data-product-price="{{ $product->formatted_price ?? number_format($product->price, 0, ',', '.') }}"
                                 data-product-image="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300x300?text=No+Image' }}">
                                <div class="product-image">
                                    <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300x300?text=No+Image' }}"
                                         alt="{{ $product->name }}"
                                         onerror="this.src='https://via.placeholder.com/300x300?text=No+Image'">
                                </div>
                                <div class="product-info">
                                    <h6 class="product-name">{{ $product->name }}</h6>
                                    <div class="product-price">Rp {{ number_format($product->formatted_price ?? $product->price, 0, ',', '.') }}</div>
                                </div>
                                <div class="compare-icon">
                                    <i class="fas fa-comments"></i>
                                </div>
                            </div>
                        @empty
                            <div class="no-products" style="grid-column: 1 / -1;">
                                <i class="fas fa-inbox"></i>
                                <p>Produk tidak ditemukan</p>
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

    <style>
        /* Filter Sidebar Container */
        .sidebar {
            width: 100%;
            max-width: 280px;
        }

        .filters-card {
            width: 100%;
            box-sizing: border-box;
        }

        /* Filter Sidebar Styles - Updated Design */
        .filter-checkbox-section {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e5e7eb;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 8px;
            width: 100%;
        }

        .checkbox-item input[type="checkbox"] {
            width: 16px;
            height: 16px;
            min-width: 16px;
            cursor: pointer;
            accent-color: #1a1a1a;
            border-radius: 3px;
            flex-shrink: 0;
        }

        .checkbox-item label {
            font-size: 13px;
            font-weight: 400;
            color: #374151;
            cursor: pointer;
            user-select: none;
            line-height: 1.4;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .filter-group-section {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e5e7eb;
            width: 100%;
        }

        .filter-group-section:last-of-type {
            border-bottom: none;
            padding-bottom: 0;
        }

        .filter-group-title {
            margin-bottom: 12px;
        }

        .filter-group-title h4 {
            font-size: 14px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0;
        }

        .filter-checkbox-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            width: 100%;
        }

        /* Price Range Styles */
        .price-range-wrapper {
            display: flex;
            flex-direction: column;
            gap: 12px;
            width: 100%;
        }

        .price-inputs {
            display: flex;
            align-items: center;
            gap: 6px;
            width: 100%;
        }

        .price-input {
            flex: 1;
            min-width: 0;
            padding: 8px 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 400;
            color: #374151;
            background: #ffffff;
            transition: all 0.2s;
            box-sizing: border-box;
        }

        .price-input:focus {
            outline: none;
            border-color: #1a1a1a;
            box-shadow: 0 0 0 2px rgba(26, 26, 26, 0.08);
        }

        .price-input::placeholder {
            color: #9ca3af;
            font-size: 11px;
        }

        .price-separator {
            color: #6b7280;
            font-size: 13px;
            font-weight: 500;
            flex-shrink: 0;
        }

        .price-slider-container {
            position: relative;
            height: 6px;
            margin-top: 4px;
            width: 100%;
        }

        .price-slider-track {
            position: absolute;
            width: 100%;
            height: 6px;
            background: #e5e7eb;
            border-radius: 3px;
        }

        .price-slider-range {
            position: absolute;
            height: 6px;
            background: #1a1a1a;
            border-radius: 3px;
            left: 0%;
            width: 100%;
        }

        /* Apply Filter Button */
        .apply-filter-btn {
            width: 100%;
            padding: 11px 16px;
            background: #1a1a1a;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 20px;
            box-sizing: border-box;
        }

        .apply-filter-btn:hover {
            background: #2d2d2d;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .apply-filter-btn:active {
            transform: translateY(0);
        }

        /* Responsive adjustments */
        @media (max-width: 1200px) {
            .sidebar {
                max-width: 260px;
            }
            
            .checkbox-item label {
                font-size: 12px;
            }
            
            .price-input {
                font-size: 11px;
                padding: 7px 8px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                max-width: 100%;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Checkbox functionality
            const checkboxes = document.querySelectorAll('.checkbox-item input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    console.log(`${this.id} is ${this.checked ? 'checked' : 'unchecked'}`);
                });
            });

            // Price input formatting
            const priceInputs = document.querySelectorAll('.price-input');
            priceInputs.forEach(input => {
                input.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/[^\d]/g, '');
                    if (value) {
                        e.target.value = 'Rp ' + parseInt(value).toLocaleString('id-ID');
                    }
                });

                input.addEventListener('blur', function(e) {
                    if (!e.target.value || e.target.value === 'Rp ') {
                        if (e.target.id === 'min-price') {
                            e.target.value = 'Rp 0';
                        } else {
                            e.target.value = 'Rp 5.000.000';
                        }
                    }
                });
            });

            // Apply Filter Button
            const applyFilterBtn = document.querySelector('.apply-filter-btn');
            applyFilterBtn.addEventListener('click', function() {
                const selectedFilters = {
                    promo: document.getElementById('promo-diskon')?.checked,
                    ready: document.getElementById('ready-stok')?.checked,
                    custom: document.getElementById('kustomisasi')?.checked,
                    categories: [],
                    priceRange: {
                        min: document.getElementById('min-price')?.value,
                        max: document.getElementById('max-price')?.value
                    }
                };

                // Get selected categories
                const categoryCheckboxes = document.querySelectorAll('input[name="kategori"]:checked');
                categoryCheckboxes.forEach(cb => {
                    selectedFilters.categories.push(cb.value);
                });

                console.log('Applying filters:', selectedFilters);
                
                // Here you can add your filter logic
                // For example, redirect to filtered URL or make AJAX request
            });

            // Sort select functionality (existing code)
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