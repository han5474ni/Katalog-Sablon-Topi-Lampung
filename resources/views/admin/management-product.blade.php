<x-admin-layout title="Product Management">
    @push('styles')
        @vite(['resources/css/admin/product-management.css'])
    @endpush

<div class="product-management-container">
    <!-- Header -->
    <header class="page-header">
        <div class="header-content">
            <div>
                <h1 class="page-title">Product Management</h1>
                <p class="page-subtitle">Kelola katalog produk LGI Store: tambah, edit, arsipkan, dan kontrol stok.</p>
            </div>
            <div class="header-actions">
                <button id="bulk-archive-btn" class="btn btn-warning" style="display: none;">
                    <i class="fas fa-archive"></i>
                    Arsipkan (<span id="selected-count">0</span>)
                </button>
                <button id="export-btn" class="btn btn-subtle">
                    <i class="fas fa-download"></i>
                    Export
                </button>
                <button id="add-product-btn" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Tambah Produk
                </button>
            </div>
        </div>
    </header>

    <!-- Filters & Controls -->
    <div class="controls-section">
        <!-- Tabs -->
        <div class="tabs-container">
            <button class="tab-btn active" data-status="ALL">Semua</button>
            <button class="tab-btn" data-status="ACTIVE">Aktif</button>
            <button class="tab-btn" data-status="DRAFT">Draft</button>
            <button class="tab-btn" data-status="ARCHIVED">Arsip</button>
            <button class="tab-btn" data-status="READY">Ready</button>
            <button class="tab-btn" data-status="HABIS">Habis</button>
        </div>

        <!-- Search -->
        <div class="search-box">
            <i class="fas fa-search search-icon"></i>
            <input type="text" id="search-input" placeholder="Cari nama produk / SKU..." />
        </div>

        <!-- Category Filter -->
        <select id="category-filter" class="filter-select">
            <option value="">Semua Kategori</option>
            <option value="kaos">Kaos</option>
            <option value="jaket">Jaket</option>
            <option value="jersey">Jersey</option>
            <option value="tas">Tas</option>
            <option value="topi">Topi</option>
        </select>
    </div>

    <!-- Products Table -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Produk</h3>
            <span class="product-count" id="product-count">0 produk</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="products-table">
                    <thead>
                        <tr>
                            <th class="checkbox-col">
                                <input type="checkbox" id="select-all" />
                            </th>
                            <th>Gambar</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th class="actions-col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="products-tbody">
                        <!-- Products will be loaded here -->
                        <tr>
                            <td colspan="8" class="loading-state">
                                <div class="spinner"></div>
                                <p>Memuat produk...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination-container" id="pagination-container" style="display: none;">
                <!-- Pagination will be rendered here -->
            </div>
        </div>
    </div>
</div>

<!-- Drawer for Add/Edit Product -->
<div class="drawer-overlay" id="drawer-overlay"></div>
<div class="drawer" id="product-drawer">
    <div class="drawer-header">
        <h3 class="drawer-title" id="drawer-title">Tambah Produk</h3>
        <button class="btn-close" id="drawer-close">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="drawer-body">
        <form id="product-form" enctype="multipart/form-data">
            <input type="hidden" id="product-id" name="id">

            <!-- Product Info -->
            <div class="form-grid">
                <div class="form-group">
                    <label for="product-name">Nama Produk <span class="required">*</span></label>
                    <input type="text" id="product-name" name="name" required placeholder="Contoh: Kaos Lengan Panjang">
                </div>

                <div class="form-group">
                    <label for="product-category">Kategori <span class="required">*</span></label>
                    <select id="product-category" name="category" required>
                        <option value="">Pilih Kategori</option>
                        <option value="kaos">Kaos</option>
                        <option value="jaket">Jaket</option>
                        <option value="jersey">Jersey</option>
                        <option value="tas">Tas</option>
                        <option value="topi">Topi</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="product-price">Harga (Rp) <span class="required">*</span></label>
                    <input type="number" id="product-price" name="price" required min="0" step="1000">
                </div>

                <div class="form-group">
                    <label for="product-original-price">Harga Coret (Opsional)</label>
                    <input type="number" id="product-original-price" name="original_price" min="0" step="1000">
                </div>

                <div class="form-group">
                    <label for="product-stock">Stok <span class="required">*</span></label>
                    <input type="number" id="product-stock" name="stock" required min="0">
                </div>

                <div class="form-group">
                    <label for="product-subcategory">Sub Kategori</label>
                    <input type="text" id="product-subcategory" name="subcategory" placeholder="Contoh: lengan pendek">
                </div>
            </div>

            <!-- Colors & Sizes -->
            <div class="form-grid form-grid-2">
                <div class="form-group">
                    <label>Warna</label>
                    <div class="options-group" id="colors-group">
                        <button type="button" class="option-btn" data-value="hitam">Hitam</button>
                        <button type="button" class="option-btn" data-value="putih">Putih</button>
                        <button type="button" class="option-btn" data-value="biru">Biru</button>
                        <button type="button" class="option-btn" data-value="hijau">Hijau</button>
                        <button type="button" class="option-btn" data-value="merah">Merah</button>
                        <button type="button" class="option-btn" data-value="kuning">Kuning</button>
                    </div>
                    <input type="hidden" name="colors" id="colors-input">
                </div>

                <div class="form-group">
                    <label>Ukuran</label>
                    <div class="options-group" id="sizes-group">
                        <button type="button" class="option-btn" data-value="S">S</button>
                        <button type="button" class="option-btn" data-value="M">M</button>
                        <button type="button" class="option-btn" data-value="L">L</button>
                        <button type="button" class="option-btn" data-value="XL">XL</button>
                        <button type="button" class="option-btn" data-value="XXL">XXL</button>
                    </div>
                    <input type="hidden" name="sizes" id="sizes-input">
                </div>
            </div>

            <!-- Description -->
            <div class="form-group">
                <label for="product-description">Deskripsi</label>
                <textarea id="product-description" name="description" rows="5" placeholder="Detail bahan, cutting, panduan perawatan, dll."></textarea>
            </div>

            <!-- Images -->
            <div class="form-group">
                <label>Gambar Produk</label>
                <div class="image-upload-container">
                    <div class="images-preview" id="images-preview">
                        <!-- Image previews will appear here -->
                    </div>
                    <input type="file" id="product-images" name="images[]" multiple accept="image/*" style="display: none;">
                    <button type="button" class="btn btn-subtle" id="upload-images-btn">
                        <i class="fas fa-upload"></i>
                        Upload Gambar
                    </button>
                    <p class="help-text">Maksimal 5 gambar. JPG/PNG/WEBP hingga 2MB/tiap file.</p>
                </div>
            </div>

            <!-- Status -->
            <div class="form-group">
                <label>Status</label>
                <div class="status-toggle">
                    <label class="switch">
                        <input type="checkbox" id="product-status" name="is_active" checked>
                        <span class="slider"></span>
                    </label>
                    <span class="status-label">Active</span>
                </div>
            </div>

            <!-- Custom Design -->
            <div class="form-group">
                <label>Custom Design</label>
                <div class="status-toggle">
                    <label class="switch">
                        <input type="checkbox" id="custom-design-allowed" name="custom_design_allowed">
                        <span class="slider"></span>
                    </label>
                    <span class="custom-design-label">Izinkan Custom Design</span>
                </div>
                <p class="help-text">Centang jika produk ini bisa dipesan dengan design custom oleh customer</p>
            </div>
        </form>
    </div>

    <div class="drawer-footer">
        <button type="button" class="btn btn-subtle" id="cancel-btn">Batal</button>
        <button type="submit" form="product-form" class="btn btn-primary" id="save-btn">
            <i class="fas fa-save"></i>
            Simpan Produk
        </button>
    </div>
</div>

    @push('scripts')
        @vite(['resources/js/admin/product-management.js'])
    @endpush
</x-admin-layout>
