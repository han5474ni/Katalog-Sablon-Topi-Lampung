<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $categoryName }} - LGI Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/guest/catalog.css', 'resources/css/components/footer.css', 'resources/js/guest/catalog.js'])
</head>
<body>
    <x-navbar />

    <section class="catalog-breadcrumb">
        <div class="breadcrumb-container">
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="breadcrumb-back">
                    <span aria-hidden="true">&lt;</span>
                    Kembali ke beranda
                </a>
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
                <aside class="sidebar">
                    <div class="filter-section">
                        <div class="filter-header">
                            <span class="filter-title">Filters</span>
                            <i class="fas fa-sliders-h"></i>
                        </div>
                        <div class="filter-links">
                            <button class="filter-link" type="button">{{ $categoryName }} Anak</button>
                            <button class="filter-link" type="button">{{ $categoryName }} Lengan Panjang</button>
                            <button class="filter-link" type="button">{{ $categoryName }} Lengan Pendek</button>
                        </div>
                    </div>

                    <div class="filter-section">
                        <div class="filter-header">
                            <span class="filter-title">Warna</span>
                            <i class="fas fa-chevron-up"></i>
                        </div>
                        <div class="color-grid">
                            <button class="color-option color-green" type="button" aria-label="Hijau" data-color="green"></button>
                            <button class="color-option color-red" type="button" aria-label="Merah" data-color="red"></button>
                            <button class="color-option color-yellow" type="button" aria-label="Kuning" data-color="yellow"></button>
                            <button class="color-option color-orange" type="button" aria-label="Oranye" data-color="orange"></button>
                            <button class="color-option color-cyan" type="button" aria-label="Cyan" data-color="cyan"></button>
                            <button class="color-option color-blue" type="button" aria-label="Biru" data-color="blue"></button>
                            <button class="color-option color-purple" type="button" aria-label="Ungu" data-color="purple"></button>
                            <button class="color-option color-pink" type="button" aria-label="Pink" data-color="pink"></button>
                            <button class="color-option color-black" type="button" aria-label="Hitam" data-color="black"></button>
                        </div>
                    </div>

                    <div class="filter-section">
                        <div class="filter-header">
                            <span class="filter-title">Ukuran</span>
                            <i class="fas fa-chevron-up"></i>
                        </div>
                        <div class="size-grid">
                            <button class="size-option" type="button">XX-Small</button>
                            <button class="size-option" type="button">X-Small</button>
                            <button class="size-option" type="button">Small</button>
                            <button class="size-option" type="button">Medium</button>
                            <button class="size-option" type="button">Large</button>
                            <button class="size-option" type="button">X-Large</button>
                            <button class="size-option" type="button">2X-Large</button>
                            <button class="size-option" type="button">3X-Large</button>
                        </div>
                    </div>

                    <button class="apply-filter" type="button">Terapkan Filter</button>
                </aside>

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
                                    <button class="wishlist-btn" type="button" aria-label="Tambah ke favorit">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                </div>
                                <div class="product-info">
                                    <h3 class="product-title">{{ $product->name }}</h3>
                                    <p class="product-price">Rp {{ $product->formatted_price }}</p>
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
</body>
</html>
