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
    <!-- Navbar Component -->
    <x-navbar />

    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span>></span>
        <span>{{ $categoryName }}</span>
    </div>

    <!-- Main Content -->
    <div class="catalog-container">
        <!-- Sidebar Filters -->
        <aside class="sidebar">
            <div class="filter-header">
                <h3>Filters</h3>
                <button class="filter-toggle">
                    <i class="fas fa-sliders-h"></i>
                </button>
            </div>

            <div class="filter-section">
                <h4>{{ $categoryName }} anak</h4>
                <i class="fas fa-chevron-right"></i>
            </div>

            <div class="filter-section">
                <h4>{{ $categoryName }} lengan panjang</h4>
                <i class="fas fa-chevron-right"></i>
            </div>

            <div class="filter-section">
                <h4>{{ $categoryName }} lengan pendek</h4>
                <i class="fas fa-chevron-right"></i>
            </div>

            <div class="filter-group">
                <div class="filter-group-header">
                    <h4>Colors</h4>
                    <i class="fas fa-chevron-up"></i>
                </div>
                <div class="color-options">
                    <div class="color-option" style="background-color: #4CAF50;" data-color="green"></div>
                    <div class="color-option" style="background-color: #FF0000;" data-color="red"></div>
                    <div class="color-option" style="background-color: #FFC107;" data-color="yellow"></div>
                    <div class="color-option" style="background-color: #FF9800;" data-color="orange"></div>
                    <div class="color-option" style="background-color: #00BCD4;" data-color="cyan"></div>
                    <div class="color-option" style="background-color: #2196F3;" data-color="blue"></div>
                    <div class="color-option" style="background-color: #9C27B0;" data-color="purple"></div>
                    <div class="color-option" style="background-color: #E91E63;" data-color="pink"></div>
                    <div class="color-option" style="background-color: #FFFFFF; border: 1px solid #ddd;" data-color="white"></div>
                    <div class="color-option" style="background-color: #000000;" data-color="black"></div>
                </div>
            </div>

            <div class="filter-group">
                <div class="filter-group-header">
                    <h4>Size</h4>
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

            <button class="apply-filter-btn">Apply Filter</button>
        </aside>

        <!-- Products Grid -->
        <main class="products-main">
            <div class="products-header">
                <h1>{{ $categoryName }}</h1>
                <div class="products-info">
                    <span id="products-count">Showing {{ $products->firstItem() ?? 0}}-{{ $products->lastItem() ?? 0 }} of {{ $totalProducts }} Products</span>
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
                        <p>No products found in this category</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
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

    <!-- Footer Component -->
    <x-guest-footer />
</body>
</html>
