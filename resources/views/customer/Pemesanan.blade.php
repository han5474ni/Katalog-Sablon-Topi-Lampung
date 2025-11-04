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
            <div class="progress-steps">
                <!-- Step 1: Alamat (Inactive) -->
                <div class="step step-inactive">
                    <div class="step-icon">
                        <span class="material-icons">location_on</span>
                    </div>
                    <div class="step-info">
                        <div class="step-label">Langkah 1</div>
                        <div class="step-title">Alamat</div>
                    </div>
                </div>

                <!-- Step 2: Pengiriman (Active) -->
                <div class="step step-active">
                    <div class="step-icon">
                        <span class="material-icons">local_shipping</span>
                    </div>
                    <div class="step-info">
                        <div class="step-label">Langkah 2</div>
                        <div class="step-title">Pengiriman</div>
                    </div>
                </div>

                <!-- Step 3: Pembayaran (Inactive) -->
                <div class="step step-inactive">
                    <div class="step-icon">
                        <span class="material-icons">payment</span>
                    </div>
                    <div class="step-info">
                        <div class="step-label">Langkah 3</div>
                        <div class="step-title">Pembayaran</div>
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
                <button class="btn btn-secondary">Kembali</button>
                <button class="btn btn-primary">Lanjut</button>
            </div>
        </div>
    </main>
        </div>
    </div>
</body>
</html>
