document.addEventListener('DOMContentLoaded', function() {
    // Profile popup functionality
    const profileIcon = document.getElementById('profile-icon');
    const profilePopup = document.getElementById('profile-popup');

    function toggleProfilePopup() {
        if (!profilePopup) return;

        const isAuthenticated = profilePopup.dataset.auth === 'true';

        if (!isAuthenticated) {
            window.location.href = profilePopup.dataset.loginUrl;
            return;
        }

        const isVisible = profilePopup.style.display === 'block';
        profilePopup.style.display = isVisible ? 'none' : 'block';
    }

    function closeProfilePopup() {
        if (!profilePopup) return;
        profilePopup.style.display = 'none';
    }

    if (profileIcon) {
        profileIcon.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleProfilePopup();
        });
    }

    // Close popup when clicking outside
    document.addEventListener('click', function(e) {
        if (profilePopup && profilePopup.style.display === 'block') {
            if (!profilePopup.contains(e.target) && e.target !== profileIcon) {
                closeProfilePopup();
            }
        }
    });

    // Prevent popup from closing when clicking inside it
    if (profilePopup) {
        profilePopup.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }

    // Close popup on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && profilePopup && profilePopup.style.display === 'block') {
            closeProfilePopup();
        }
    });

    // Color filter selection
    const colorOptions = document.querySelectorAll('.color-option');
    colorOptions.forEach(option => {
        option.addEventListener('click', function() {
            this.classList.toggle('active');
        });
    });

    // Size filter selection
    const sizeOptions = document.querySelectorAll('.size-option');
    sizeOptions.forEach(option => {
        option.addEventListener('click', function() {
            sizeOptions.forEach(opt => opt.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Apply filter button with AJAX
    const applyFilterBtn = document.querySelector('.apply-filter-btn');
    if (applyFilterBtn) {
        applyFilterBtn.addEventListener('click', function() {
            applyFilters();
        });
    }

    // Function to apply filters via AJAX
    function applyFilters(page = 1) {
        const selectedColors = Array.from(document.querySelectorAll('.color-option.active'))
            .map(el => el.dataset.color);
        const selectedSizes = Array.from(document.querySelectorAll('.size-option.active'))
            .map(el => el.textContent.trim());
        const searchQuery = document.getElementById('search-input')?.value || '';
        const sortBy = document.getElementById('sort-select')?.value || 'most_popular';

        // Build query parameters
        const params = new URLSearchParams(window.location.search);
        
        if (searchQuery) {
            params.set('search', searchQuery);
        } else {
            params.delete('search');
        }
        
        if (selectedColors.length > 0) {
            params.set('colors', selectedColors.join(','));
        } else {
            params.delete('colors');
        }
        
        if (selectedSizes.length > 0) {
            params.set('sizes', selectedSizes.join(','));
        } else {
            params.delete('sizes');
        }
        
        params.set('sort', sortBy);
        params.set('page', page);

        // Update URL without reload
        const newUrl = window.location.pathname + '?' + params.toString();
        window.history.pushState({}, '', newUrl);

        // Fetch filtered products
        fetch(newUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            updateProductsGrid(data.products);
            updatePagination(data.pagination);
            updateProductsCount(data.pagination);
        })
        .catch(error => {
            console.error('Error fetching products:', error);
        });
    }

    // Update products grid
    function updateProductsGrid(products) {
        const grid = document.getElementById('products-grid');
        if (!grid) return;

        if (products.length === 0) {
            grid.innerHTML = `
                <div class="no-products">
                    <i class="fas fa-inbox"></i>
                    <p>No products found with current filters</p>
                </div>
            `;
            return;
        }

        grid.innerHTML = products.map(product => {
            const imageUrl = product.image ? `/storage/${product.image}` : 'https://via.placeholder.com/300x300?text=No+Image';
            return `
                <div class="product-card" 
                     data-product-id="${product.id}" 
                     data-product-name="${product.name}" 
                     data-product-price="${product.formatted_price}" 
                     data-product-image="${imageUrl}">
                    <div class="product-image">
                        <img src="${imageUrl}" 
                             alt="${product.name}"
                             onerror="this.src='https://via.placeholder.com/300x300?text=No+Image'">
                    </div>
                    <div class="product-info">
                        <h3 class="product-name">${product.name}</h3>
                        <p class="product-price">Rp ${product.formatted_price}</p>
                    </div>
                </div>
            `;
        }).join('');
    }

    // Update pagination
    function updatePagination(pagination) {
        const container = document.getElementById('pagination-container');
        if (!container) return;

        if (pagination.last_page <= 1) {
            container.style.display = 'none';
            return;
        }

        container.style.display = 'flex';
        
        let paginationHTML = '';
        
        // Previous button
        if (pagination.current_page > 1) {
            paginationHTML += `<a href="#" class="pagination-btn prev pagination-link" data-page="${pagination.current_page - 1}">
                <i class="fas fa-chevron-left"></i> Previous
            </a>`;
        } else {
            paginationHTML += `<button class="pagination-btn prev" disabled>
                <i class="fas fa-chevron-left"></i> Previous
            </button>`;
        }
        
        // Page numbers
        paginationHTML += '<div class="pagination-numbers">';
        for (let i = 1; i <= pagination.last_page; i++) {
            if (i === pagination.current_page) {
                paginationHTML += `<button class="page-number active">${i}</button>`;
            } else {
                paginationHTML += `<a href="#" class="page-number pagination-link" data-page="${i}">${i}</a>`;
            }
        }
        paginationHTML += '</div>';
        
        // Next button
        if (pagination.current_page < pagination.last_page) {
            paginationHTML += `<a href="#" class="pagination-btn next pagination-link" data-page="${pagination.current_page + 1}">
                Next <i class="fas fa-chevron-right"></i>
            </a>`;
        } else {
            paginationHTML += `<button class="pagination-btn next" disabled>
                Next <i class="fas fa-chevron-right"></i>
            </button>`;
        }
        
        container.innerHTML = paginationHTML;
        
        // Attach pagination click handlers
        attachPaginationHandlers();
    }

    // Update products count
    function updateProductsCount(pagination) {
        const countElement = document.getElementById('products-count');
        if (countElement) {
            countElement.textContent = `Showing ${pagination.from || 0}-${pagination.to || 0} of ${pagination.total} Products`;
        }
    }

    // Attach pagination handlers
    function attachPaginationHandlers() {
        const paginationLinks = document.querySelectorAll('.pagination-link');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const page = this.dataset.page;
                applyFilters(page);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });
    }

    // Product card click handler - redirect to detail page
    document.addEventListener('click', function(e) {
        const productCard = e.target.closest('.product-card');
        
        if (productCard) {
            const productId = productCard.getAttribute('data-product-id');
            const productName = productCard.getAttribute('data-product-name');
            const productPrice = productCard.getAttribute('data-product-price');
            const productImage = productCard.getAttribute('data-product-image');

            if (productId && productName && productPrice && productImage) {
                window.location.href = `/public/detail?id=${productId}&name=${encodeURIComponent(productName)}&price=${productPrice}&image=${encodeURIComponent(productImage)}`;
            }
        }
    });

    // Pagination click handlers
    const pageNumbers = document.querySelectorAll('.page-number');
    pageNumbers.forEach(btn => {
        btn.addEventListener('click', function() {
            pageNumbers.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Scroll to top of products
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    });

    // Search functionality with auto-apply
    const searchBox = document.getElementById('search-input');
    if (searchBox) {
        searchBox.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                applyFilters();
            }
        });
    }

    // Sort by change handler with auto-apply
    const sortSelect = document.getElementById('sort-select');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            applyFilters();
        });
    }
    
    // Initial pagination handlers (if exists on page load)
    attachPaginationHandlers();

    // Filter section toggles
    const filterSections = document.querySelectorAll('.filter-section');
    filterSections.forEach(section => {
        section.addEventListener('click', function() {
            console.log('Filter section clicked:', this.querySelector('h4').textContent);
            // Here you can add subcategory filtering
        });
    });

    // Filter group toggles
    const filterGroupHeaders = document.querySelectorAll('.filter-group-header');
    filterGroupHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-chevron-up');
            icon.classList.toggle('fa-chevron-down');
            
            // Toggle content visibility
            const content = this.nextElementSibling;
            if (content) {
                content.style.display = content.style.display === 'none' ? 'flex' : 'none';
            }
        });
    });
});
