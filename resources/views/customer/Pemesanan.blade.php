<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pemesanan - LGI Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    @vite(['resources/css/customer/shared.css', 'resources/css/guest/Pemesanan.css'])
</head>
<body class="bg-slate-50">
    <div class="flex h-screen">
        <x-customer-sidebar active="pemesanan" />

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <x-customer-header title="Pemesanan" />
  
    

    <!-- Main Content -->
    <main class="checkout-main">
        <div class="container">
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

                <div class="step active">
                    <div class="step-icon active">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <circle cx="12" cy="12" r="10"></circle>
                        </svg>
                    </div>
                    <div class="step-text">
                        <div class="step-number">Langkah 2</div>
                        <div class="step-label">Pengiriman</div>
                    </div>
                </div>

                <div class="step">
                    <div class="step-icon">
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

            <!-- Payment Method Section -->
            <section class="payment-section">
                <h2 class="section-title">Metode Pembayaran</h2>

                <div class="payment-options">
                    <!-- Option 1: Melalui paket -->
                    <label class="payment-option payment-option-selected">
                        <input type="radio" name="payment" value="paket" checked>
                        <div class="option-content">
                            <span class="option-icon material-icons">credit_card</span>
                            <span class="option-text">Melalui paket</span>
                        </div>
                        <span class="checkmark"></span>
                    </label>

                    <!-- Option 2: Ambil Di toko -->
                    <label class="payment-option">
                        <input type="radio" name="payment" value="toko">
                        <div class="option-content">
                            <span class="option-icon material-icons">store</span>
                            <span class="option-text">Ambil Di toko</span>
                        </div>
                        <span class="checkmark"></span>
                    </label>
                </div>
            </section>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <button class="btn btn-orange" onclick="window.location.href='/alamat'">Kembali</button>
                <button class="btn btn-primary" onclick="window.location.href='/pembayaran'">Lanjut</button>
            </div>
        </div>
    </main>
        </div>
    </div>
</body>
</html>
