<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checkout - Pembayaran - LGI Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    @vite(['resources/css/customer/shared.css', 'resources/css/customer/Pembayaran.css'])
</head>
<body class="bg-slate-50">
    <div class="flex h-screen">
        <x-customer-sidebar active="pembayaran" />

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <x-customer-header title="Pembayaran" />

            <!-- Main Content -->
            <main class="main-container">
      

        <!-- Progress Steps -->
        <div class="steps-container">
            <div class="step completed">
                <div class="step-icon completed">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </div>
                <div class="step-text">
                    <div class="step-number">Langkah 1</div>
                    <div class="step-label">Alamat</div>
                </div>
            </div>

            <div class="step completed">
                <div class="step-icon completed">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </div>
                <div class="step-text">
                    <div class="step-number">Langkah 2</div>
                    <div class="step-label">Pengiriman</div>
                </div>
            </div>

            <div class="step active">
                <div class="step-icon active">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                </div>
                <div class="step-text">
                    <div class="step-number">Langkah 3</div>
                    <div class="step-label">Pembayaran</div>
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="payment-grid">
            <!-- Left Section - Order Summary -->
            <div class="order-summary">
                <h2 class="section-title">Ringkasan pesanan</h2>
                
                <!-- Product Items -->
                <div class="product-item">
                    <div class="product-image">
                        <img src="https://via.placeholder.com/60x60/8b7355/ffffff?text=T" alt="Product">
                    </div>
                    <div class="product-info">
                        <span class="product-name">TOPI FEELS GOOD TO BE YOU</span>
                    </div>
                    <div class="product-price">
                        <span>Rp 900.000,00</span>
                    </div>
                </div>

                <div class="product-item">
                    <div class="product-image">
                        <img src="https://via.placeholder.com/60x60/8b7355/ffffff?text=T" alt="Product">
                    </div>
                    <div class="product-info">
                        <span class="product-name">TOPI FEELS GOOD TO BE YOU</span>
                    </div>
                    <div class="product-price">
                        <span>Rp 900.000,00</span>
                    </div>
                </div>

                <div class="product-item">
                    <div class="product-image">
                        <img src="https://via.placeholder.com/60x60/8b7355/ffffff?text=T" alt="Product">
                    </div>
                    <div class="product-info">
                        <span class="product-name">TOPI FEELS GOOD TO BE YOU</span>
                    </div>
                    <div class="product-price">
                        <span>Rp 900.000,00</span>
                    </div>
                </div>

                <!-- Address Info -->
                <div class="info-section">
                    <h3 class="info-title">Alamat</h3>
                    <p class="info-text">2118 Kampung_baru Balam, Lampung 35624</p>
                </div>

                <!-- Shipping Method -->
                <div class="info-section">
                    <h3 class="info-title">Metode Pengiriman</h3>
                    <p class="info-text">Gratis</p>
                </div>

                <!-- Price Summary -->
                <div class="price-summary">
                    <div class="price-row">
                        <span>Subtotal</span>
                        <span>Rp.999.000.00</span>
                    </div>
                    <div class="price-row total">
                        <span>Total</span>
                        <span class="total-price">Rp.999.999.00</span>
                    </div>
                </div>
            </div>

            <!-- Right Section - Payment Form -->
            <div class="payment-form">
                <h2 class="section-title">Pembayaran</h2>
                
                <form>
                    <div class="form-group">
                        <input type="text" class="form-input" placeholder="Nama Pengguna" required>
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-input" placeholder="Nomor Kartu" required>
                    </div>

                    <div class="checkbox-group">
                        <input type="checkbox" id="sameAddress" class="checkbox">
                        <label for="sameAddress" class="checkbox-label">
                            Sama dengan alamat terhubung
                        </label>
                    </div>

                    <!-- Action Buttons -->
                    <div class="form-actions">
                        <button type="button" class="btn btn-orange">Kembali</button>
                        <button type="submit" class="btn btn-primary" id="submitOrder">Lanjut</button>
                    </div>
                </form>
            </div>
        </div>
            </main>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50" style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 transform transition-all">
                <div class="p-6">
                    <div class="text-center">
                        <div class="mx-auto mb-4 h-16 w-16 rounded-full bg-amber-100 flex items-center justify-center">
                            <span class="material-icons text-amber-600 text-3xl">check_circle</span>
                        </div>
                        <h2 class="text-xl font-bold text-slate-900 mb-2">Pesanan sudah masuk!</h2>
                        <p class="text-slate-600 text-sm leading-relaxed">Silahkan cek notifikasi untuk pemberitahuan lebih lanjut</p>
                    </div>
                    <div class="flex gap-3 mt-6">
                        <button id="closeModal" class="flex-1 px-4 py-3 bg-slate-900 hover:bg-slate-800 text-white font-medium rounded-xl transition">
                            OK
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('submitOrder').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('successModal').classList.remove('hidden');
        });

        document.getElementById('closeModal').addEventListener('click', function() {
            document.getElementById('successModal').classList.add('hidden');
        });

        // Close modal when clicking outside
        document.getElementById('successModal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
    </script>

    <script type="module" src="/main.js"></script>
</body>
</html>
