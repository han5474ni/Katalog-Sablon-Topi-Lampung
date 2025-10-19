// Product Management JavaScript
let currentFilters = {
    status: 'ALL',
    search: '',
    category: '',
    page: 1,
    perPage: 10
};

let selectedProducts = new Set();
let selectedColors = [];
let selectedSizes = [];
let currentEditId = null;
let uploadedImages = [];

document.addEventListener('DOMContentLoaded', function() {
    initializeEventListeners();
    loadProducts();
});

function initializeEventListeners() {
    // Tab filters
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentFilters.status = this.dataset.status;
            currentFilters.page = 1;
            loadProducts();
        });
    });

    // Search
    const searchInput = document.getElementById('search-input');
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            currentFilters.search = this.value;
            currentFilters.page = 1;
            loadProducts();
        }, 500);
    });

    // Category filter
    document.getElementById('category-filter').addEventListener('change', function() {
        currentFilters.category = this.value;
        currentFilters.page = 1;
        loadProducts();
    });

    // Select all checkbox
    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.product-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = this.checked;
            if (this.checked) {
                selectedProducts.add(parseInt(cb.value));
            } else {
                selectedProducts.delete(parseInt(cb.value));
            }
        });
        updateBulkActions();
    });

    // Bulk archive button
    document.getElementById('bulk-archive-btn').addEventListener('click', bulkArchiveProducts);

    // Add product button
    document.getElementById('add-product-btn').addEventListener('click', openAddDrawer);

    // Export button
    document.getElementById('export-btn').addEventListener('click', exportProducts);

    // Drawer controls
    document.getElementById('drawer-close').addEventListener('click', closeDrawer);
    document.getElementById('drawer-overlay').addEventListener('click', closeDrawer);
    document.getElementById('cancel-btn').addEventListener('click', closeDrawer);

    // Form submit
    document.getElementById('product-form').addEventListener('submit', handleFormSubmit);

    // Color/Size options
    initializeOptions();

    // Image upload
    document.getElementById('upload-images-btn').addEventListener('click', () => {
        document.getElementById('product-images').click();
    });

    document.getElementById('product-images').addEventListener('change', handleImageUpload);

    // Status toggle
    document.getElementById('product-status').addEventListener('change', function() {
        const label = document.querySelector('.status-label');
        label.textContent = this.checked ? 'Active' : 'Draft';
    });

    // Custom Design toggle
    document.getElementById('custom-design-allowed').addEventListener('change', function() {
        const label = document.querySelector('.custom-design-label');
        label.textContent = this.checked ? 'Custom Design Diizinkan' : 'Izinkan Custom Design';
    });
}

function initializeOptions() {
    // Colors
    document.querySelectorAll('#colors-group .option-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            this.classList.toggle('active');
            const value = this.dataset.value;
            const index = selectedColors.indexOf(value);
            if (index > -1) {
                selectedColors.splice(index, 1);
            } else {
                selectedColors.push(value);
            }
            document.getElementById('colors-input').value = JSON.stringify(selectedColors);
        });
    });

    // Sizes
    document.querySelectorAll('#sizes-group .option-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            this.classList.toggle('active');
            const value = this.dataset.value;
            const index = selectedSizes.indexOf(value);
            if (index > -1) {
                selectedSizes.splice(index, 1);
            } else {
                selectedSizes.push(value);
            }
            document.getElementById('sizes-input').value = JSON.stringify(selectedSizes);
        });
    });
}

async function loadProducts() {
    const tbody = document.getElementById('products-tbody');
    tbody.innerHTML = '<tr><td colspan="8" class="loading-state"><div class="spinner"></div><p>Memuat produk...</p></td></tr>';

    try {
        const params = new URLSearchParams({
            status: currentFilters.status,
            search: currentFilters.search,
            category: currentFilters.category,
            page: currentFilters.page,
            perPage: currentFilters.perPage
        });

        const response = await fetch(`/admin/api/products?${params}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        const result = await response.json();

        if (result.success) {
            renderProducts(result.data);
            renderPagination(result.pagination);
            updateProductCount(result.pagination.total);
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        console.error('Error loading products:', error);
        tbody.innerHTML = `<tr><td colspan="8" class="empty-state"><i class="fas fa-exclamation-circle"></i><p>Error: ${error.message}</p></td></tr>`;
    }
}

function renderProducts(products) {
    const tbody = document.getElementById('products-tbody');
    
    if (products.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" class="empty-state"><i class="fas fa-inbox"></i><p>Tidak ada produk</p></td></tr>';
        return;
    }

    tbody.innerHTML = products.map(product => `
        <tr>
            <td>
                <input type="checkbox" class="product-checkbox" value="${product.id}" ${selectedProducts.has(product.id) ? 'checked' : ''}>
            </td>
            <td>
                <div class="product-image">
                    ${product.image ? `<img src="/storage/${product.image}" alt="${product.name}">` : '<div class="no-image"><i class="fas fa-image"></i></div>'}
                </div>
            </td>
            <td>
                <div class="product-name-cell">
                    <div class="product-name">${product.name}</div>
                    <div class="product-id">ID: ${product.id}</div>
                </div>
            </td>
            <td>${capitalizeFirst(product.category)}</td>
            <td>Rp ${formatPrice(product.price)}</td>
            <td>
                <span class="stock-number ${product.stock > 0 ? 'in-stock' : 'out-of-stock'}">${product.stock}</span>
            </td>
            <td>
                <span class="badge ${product.is_active ? 'badge-success' : 'badge-warning'}">
                    ${product.is_active ? 'Active' : 'Draft'}
                </span>
            </td>
            <td>
                <div class="table-actions">
                    <label class="switch">
                        <input type="checkbox" ${product.is_active ? 'checked' : ''} onchange="toggleProductStatus(${product.id})">
                        <span class="slider"></span>
                    </label>
                    <button class="btn btn-subtle" onclick="editProduct(${product.id})" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-danger" onclick="deleteProduct(${product.id})" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');

    // Attach checkbox listeners
    document.querySelectorAll('.product-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            if (this.checked) {
                selectedProducts.add(parseInt(this.value));
            } else {
                selectedProducts.delete(parseInt(this.value));
            }
            updateBulkActions();
        });
    });
}

function renderPagination(pagination) {
    const container = document.getElementById('pagination-container');
    
    if (pagination.last_page <= 1) {
        container.style.display = 'none';
        return;
    }

    container.style.display = 'flex';
    container.innerHTML = `
        <div class="pagination-info">
            Showing ${pagination.from || 0} - ${pagination.to || 0} of ${pagination.total}
        </div>
        <div class="pagination-buttons">
            <button class="page-btn" onclick="changePage(${pagination.current_page - 1})" ${pagination.current_page === 1 ? 'disabled' : ''}>
                <i class="fas fa-chevron-left"></i> Previous
            </button>
            ${generatePageNumbers(pagination).join('')}
            <button class="page-btn" onclick="changePage(${pagination.current_page + 1})" ${pagination.current_page === pagination.last_page ? 'disabled' : ''}>
                Next <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    `;
}

function generatePageNumbers(pagination) {
    const pages = [];
    for (let i = 1; i <= pagination.last_page; i++) {
        pages.push(`
            <button class="page-btn ${i === pagination.current_page ? 'active' : ''}" onclick="changePage(${i})">
                ${i}
            </button>
        `);
    }
    return pages;
}

function changePage(page) {
    currentFilters.page = page;
    loadProducts();
}

function updateProductCount(total) {
    document.getElementById('product-count').textContent = `${total} produk`;
}

function updateBulkActions() {
    const count = selectedProducts.size;
    const bulkBtn = document.getElementById('bulk-archive-btn');
    const countSpan = document.getElementById('selected-count');
    
    if (count > 0) {
        bulkBtn.style.display = 'inline-flex';
        countSpan.textContent = count;
    } else {
        bulkBtn.style.display = 'none';
    }
}

function openAddDrawer() {
    currentEditId = null;
    resetForm();
    document.getElementById('drawer-title').textContent = 'Tambah Produk';
    openDrawer();
}

function openDrawer() {
    document.getElementById('drawer-overlay').classList.add('active');
    document.getElementById('product-drawer').classList.add('active');
}

function closeDrawer() {
    document.getElementById('drawer-overlay').classList.remove('active');
    document.getElementById('product-drawer').classList.remove('active');
    resetForm();
}

function resetForm() {
    document.getElementById('product-form').reset();
    document.getElementById('product-id').value = '';
    selectedColors = [];
    selectedSizes = [];
    uploadedImages = [];
    document.querySelectorAll('.option-btn').forEach(btn => btn.classList.remove('active'));
    document.getElementById('images-preview').innerHTML = '';
    document.querySelector('.status-label').textContent = 'Active';
    document.querySelector('.custom-design-label').textContent = 'Izinkan Custom Design';
}

async function editProduct(id) {
    try {
        const response = await fetch(`/admin/api/products/${id}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        const result = await response.json();

        if (result.success) {
            currentEditId = id;
            fillForm(result.data);
            document.getElementById('drawer-title').textContent = `Edit: ${result.data.name}`;
            openDrawer();
        } else {
            alert('Failed to load product');
        }
    } catch (error) {
        console.error('Error loading product:', error);
        alert('Error loading product');
    }
}

function fillForm(product) {
    document.getElementById('product-id').value = product.id;
    document.getElementById('product-name').value = product.name;
    document.getElementById('product-category').value = product.category;
    document.getElementById('product-price').value = product.price;
    document.getElementById('product-original-price').value = product.original_price || '';
    document.getElementById('product-stock').value = product.stock;
    document.getElementById('product-subcategory').value = product.subcategory || '';
    document.getElementById('product-description').value = product.description || '';
    document.getElementById('product-status').checked = product.is_active;
    document.querySelector('.status-label').textContent = product.is_active ? 'Active' : 'Draft';
    document.getElementById('custom-design-allowed').checked = product.custom_design_allowed || false;
    document.querySelector('.custom-design-label').textContent = product.custom_design_allowed ? 'Custom Design Diizinkan' : 'Izinkan Custom Design';

    // Set colors
    if (product.colors) {
        selectedColors = product.colors;
        product.colors.forEach(color => {
            const btn = document.querySelector(`#colors-group .option-btn[data-value="${color}"]`);
            if (btn) btn.classList.add('active');
        });
    }

    // Set sizes
    if (product.sizes) {
        selectedSizes = product.sizes;
        product.sizes.forEach(size => {
            const btn = document.querySelector(`#sizes-group .option-btn[data-value="${size}"]`);
            if (btn) btn.classList.add('active');
        });
    }
}

async function handleFormSubmit(e) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData();

    // Basic fields - ensure non-empty values
    formData.append('name', form.name.value.trim());
    formData.append('category', form.category.value);
    formData.append('price', form.price.value || 0);
    formData.append('stock', form.stock.value || 0);
    formData.append('description', form.description.value.trim() || '');
    formData.append('subcategory', form.subcategory.value.trim() || '');
    formData.append('original_price', form.original_price.value || 0);
    formData.append('is_active', form.is_active.checked ? '1' : '0');
    formData.append('custom_design_allowed', form.custom_design_allowed.checked ? '1' : '0');

    // Colors & Sizes - stringify arrays
    formData.append('colors', JSON.stringify(selectedColors));
    formData.append('sizes', JSON.stringify(selectedSizes));

    // Images - only if new files selected
    const imageFiles = document.getElementById('product-images').files;
    if (imageFiles.length > 0) {
        for (let i = 0; i < imageFiles.length; i++) {
            formData.append('images[]', imageFiles[i]);
        }
    }

    // Show loading state
    const saveBtn = document.getElementById('save-btn');
    const originalText = saveBtn.innerHTML;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
    saveBtn.disabled = true;

    try {
        const url = currentEditId 
            ? `/admin/api/products/${currentEditId}`
            : '/admin/api/products';
        
        // For update, append _method
        if (currentEditId) {
            formData.append('_method', 'PUT');
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        if (!csrfToken) {
            throw new Error('CSRF token not found');
        }

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            alert(result.message || 'Product saved successfully');
            closeDrawer();
            loadProducts();
        } else {
            // Show validation errors if any
            if (result.errors) {
                let errorMsg = 'Validation errors:\n';
                for (let field in result.errors) {
                    errorMsg += `- ${result.errors[field].join(', ')}\n`;
                }
                alert(errorMsg);
            } else {
                alert(result.message || 'Failed to save product');
            }
        }
    } catch (error) {
        console.error('Error saving product:', error);
        alert('Error: ' + error.message);
    } finally {
        // Restore button state
        saveBtn.innerHTML = originalText;
        saveBtn.disabled = false;
    }
}

async function deleteProduct(id) {
    if (!confirm('Are you sure you want to delete this product?')) return;

    try {
        const response = await fetch(`/admin/api/products/${id}`, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const result = await response.json();

        if (result.success) {
            alert(result.message);
            selectedProducts.delete(id);
            loadProducts();
        } else {
            alert(result.message || 'Failed to delete product');
        }
    } catch (error) {
        console.error('Error deleting product:', error);
        alert('Error deleting product');
    }
}

async function toggleProductStatus(id) {
    try {
        const response = await fetch(`/admin/api/products/${id}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const result = await response.json();

        if (result.success) {
            loadProducts();
        } else {
            alert(result.message || 'Failed to toggle status');
        }
    } catch (error) {
        console.error('Error toggling status:', error);
        alert('Error toggling status');
    }
}

async function bulkArchiveProducts() {
    if (selectedProducts.size === 0) return;
    if (!confirm(`Archive ${selectedProducts.size} product(s)?`)) return;

    try {
        const response = await fetch('/admin/api/products/bulk-archive', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                ids: Array.from(selectedProducts)
            })
        });

        const result = await response.json();

        if (result.success) {
            alert(result.message);
            selectedProducts.clear();
            updateBulkActions();
            loadProducts();
        } else {
            alert(result.message || 'Failed to archive products');
        }
    } catch (error) {
        console.error('Error archiving products:', error);
        alert('Error archiving products');
    }
}

function handleImageUpload(e) {
    const files = e.target.files;
    const preview = document.getElementById('images-preview');
    preview.innerHTML = '';

    if (files.length > 5) {
        alert('Maximum 5 images allowed');
        e.target.value = '';
        return;
    }

    Array.from(files).forEach((file, index) => {
        if (file.size > 2 * 1024 * 1024) {
            alert(`${file.name} is too large. Max 2MB per file.`);
            return;
        }

        const reader = new FileReader();
        reader.onload = function(event) {
            const div = document.createElement('div');
            div.className = 'image-preview-item';
            div.innerHTML = `
                <img src="${event.target.result}" alt="Preview">
                <button type="button" class="remove-image" onclick="removeImage(${index})">×</button>
            `;
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}

function removeImage(index) {
    const fileInput = document.getElementById('product-images');
    const dt = new DataTransfer();
    const files = fileInput.files;

    for (let i = 0; i < files.length; i++) {
        if (i !== index) dt.items.add(files[i]);
    }

    fileInput.files = dt.files;
    handleImageUpload({ target: fileInput });
}

// Export products
function exportProducts() {
    const filters = new URLSearchParams({
        status: currentFilters.status,
        search: currentFilters.search,
        category: currentFilters.category,
        export: 'csv'
    });

    // Create temporary link to download
    const link = document.createElement('a');
    link.href = `/admin/api/products/export?${filters.toString()}`;
    link.download = `products_${new Date().toISOString().split('T')[0]}.csv`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Utility functions
function formatPrice(price) {
    return new Intl.NumberFormat('id-ID').format(price);
}

function capitalizeFirst(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

// Global functions for onclick handlers
window.editProduct = editProduct;
window.deleteProduct = deleteProduct;
window.toggleProductStatus = toggleProductStatus;
window.changePage = changePage;
window.removeImage = removeImage;
