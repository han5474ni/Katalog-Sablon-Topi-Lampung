<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Produk - LGI Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/guest/catalog.css', 'resources/css/components/footer.css'])
</head>
<body>
    <x-navbar />

    <div class="breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span>></span>
        <span>Semua Produk</span>
    </div>

    <div class="catalog-container">
        <main class="products-main" style="width: 100%;">
            <section class="category-summary">
                <div class="category-info">
                    <span class="category-title">Semua Produk</span>
                    <span class="category-meta">Menampilkan {{ $products->firstItem() ?? 0}}-{{ $products->lastItem() ?? 0 }} dari {{ $totalProducts }} produk aktif</span>
                </div>
                <div class="category-filters">
                    <span class="tag"><i class="fas fa-list"></i> Kategori: Semua Produk</span>
                    <span class="tag"><i class="fas fa-sort-amount-down"></i> Urutkan: {{ ucfirst(str_replace('_', ' ', request('sort') ?? 'most_popular')) }}</span>
                </div>
            </section>

            <div class="products-wrapper">
                <div class="products-header">
                    <h1>Semua Produk</h1>
                    <div class="products-info">
                        <span id="products-count">Menampilkan {{ $products->firstItem() ?? 0}}-{{ $products->lastItem() ?? 0 }} dari {{ $totalProducts }} Produk</span>
                        <div class="sort-by">
                            <label>Sort by:</label>
                            <select id="sort-select">
                                <option value="most_popular" {{ request('sort') == 'most_popular' ? 'selected' : '' }}>Most Popular</option>
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="products-grid" id="products-grid">
                @forelse($products as $product)
                    <div class="product-card"
                        data-product-id="{{ $product->id }}"
                        data-product-name="{{ $product->name }}"
                        data-product-price="{{ $product->formatted_price }}"
                        data-product-image="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300x300?text=No+Image' }}">
                        <div class="product-image">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300x300?text=No+Image' }}"
                                 alt="{{ $product->name }}"
                                 onerror="this.src='https://via.placeholder.com/300x300?text=No+Image'">
                        </div>
                        <div class="product-info">
                            <h3 class="product-name">{{ $product->name }}</h3>
                            <p class="product-price">Rp {{ $product->formatted_price }}</p>
                        </div>
                    </div>
                @empty
                    <div class="no-products">
                        <i class="fas fa-inbox"></i>
                        <p>Belum ada produk tersedia.</p>
                    </div>
                @endforelse
            </div>

            @if($products->hasPages())
                <div class="pagination" id="pagination-container">
                    @if ($products->onFirstPage())
                        <button class="pagination-btn prev" disabled>
                            <i class="fas fa-chevron-left"></i> Previous
                        </button>
                    @else
                        <a href="{{ $products->previousPageUrl() }}" class="pagination-btn prev pagination-link">
                            <i class="fas fa-chevron-left"></i> Previous
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
                            Next <i class="fas fa-chevron-right"></i>
                        </a>
                    @else
                        <button class="pagination-btn next" disabled>
                            Next <i class="fas fa-chevron-right"></i>
                        </button>
                    @endif
                </div>
            @endif
        </main>
    </div>

    <x-guest-footer />
</body>
</html>