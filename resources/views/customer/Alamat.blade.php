<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Alamat - LGI Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    @vite(['resources/css/customer/shared.css', 'resources/css/guest/alamat.css'])
</head>
<body class="bg-slate-50">
    <div class="flex h-screen">
        <x-customer-sidebar active="alamat" />

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <x-customer-header title="Alamat" />

            <!-- Address Content -->
            <div class="p-4 md:p-8">
                <div class="mx-auto max-w-4xl">


        <!-- Progress Steps -->
        <div class="steps-container">
            <div class="step active">
                <div class="step-icon active">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                </div>
                <div class="step-text">
                    <div class="step-number">Langkah 1</div>
                    <div class="step-label">Alamat</div>
                </div>
            </div>

            <div class="step">
                <div class="step-icon">
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

        <!-- Address Selection -->
        <div class="content-section">
            <h2 class="section-title">Pilih alamat</h2>

            <!-- Address Card 1 -->
            <div class="address-card selected">
                <input type="radio" name="address" id="address1" checked>
                <label for="address1" class="address-label">
                    <div class="address-header">
                        <span class="address-name">2118 Kampung baru</span>
                    </div>
                    <div class="address-details">
                        <p>2118 Kampung_baru Balam, Lampung 35624</p>
                        <p>(209) 555-0104</p>
                    </div>
                    <div class="address-actions">
                        <button class="action-btn edit-btn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                            </svg>
                        </button>
                        <button class="action-btn delete-btn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                </label>
            </div>

            <!-- Address Card 2 -->
            <div class="address-card">
                <input type="radio" name="address" id="address2">
                <label for="address2" class="address-label">
                    <div class="address-header">
                        <span class="address-name">Sukarame</span>
                        <span class="badge">KANTOR</span>
                    </div>
                    <div class="address-details">
                        <p>2715 Sukarame Balam, Lampung 83475</p>
                        <p>(704) 555-0127</p>
                    </div>
                    <div class="address-actions">
                        <button class="action-btn edit-btn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                            </svg>
                        </button>
                        <button class="action-btn delete-btn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                </label>
            </div>

            <!-- Add Address Button -->
            <button class="add-address-btn">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>Tambah Alamat</span>
            </button>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <button class="btn btn-secondary">Kembali</button>
            <button class="btn btn-primary" onclick="window.location.href='/pemesanan'">Lanjut</button>
        </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
