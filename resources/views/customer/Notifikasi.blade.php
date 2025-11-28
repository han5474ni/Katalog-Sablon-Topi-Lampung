<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Notifikasi - LGI Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    @vite(['resources/css/customer/shared.css', 'resources/css/customer/Notifikasi.css'])
</head>
<body class="bg-slate-50">
    <div class="flex h-screen">
        <x-customer-sidebar active="notifikasi" />

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <x-customer-header title="Notifikasi" />

            <!-- Bridge legacy markup into the new layout -->
            <div class="p-6">
                <div class="container main-content">
                    <!-- Sidebar placeholder removed; using global sidebar -->
                    <main class="order-section w-full">
                        <div class="order-header">
                            <label class="checkbox-label">
                                <input type="checkbox" id="selectAll">
                                <span>Pilih semua</span>
                            </label>
                            <button class="read-btn">Tandai sudah dibaca</button>
                        </div>

                        <div class="order-list">
                            <div class="order-item">
                                <input type="checkbox" class="order-checkbox">
                                <div class="order-image"></div>
                                <div class="order-info">
                                    <h3>Pesanan disetujui penjual</h3>
                                    <p>Hi, User! Pesanan kamu telah disetujui penjual untuk dibuat! Harap ditunggu hasil akhirnya, ya!</p>
                                </div>
                                <button class="detail-btn">Tampilkan rincian</button>
                            </div>

                            <div class="order-item">
                                <input type="checkbox" class="order-checkbox">
                                <div class="order-image"></div>
                                <div class="order-info">
                                    <h3>Pesanan disetujui penjual</h3>
                                    <p>Hi, User! Pesanan kamu telah disetujui penjual untuk dibuat! Harap ditunggu hasil akhirnya, ya!</p>
                                </div>
                                <button class="detail-btn">Tampilkan rincian</button>
                            </div>

                            <div class="order-item">
                                <input type="checkbox" class="order-checkbox">
                                <div class="order-image"></div>
                                <div class="order-info">
                                    <h3>Pesanan disetujui penjual</h3>
                                    <p>Hi, User! Pesanan kamu telah disetujui penjual untuk dibuat! Harap ditunggu hasil akhirnya, ya!</p>
                                </div>
                                <button class="detail-btn">Tampilkan rincian</button>
                            </div>

                            <div class="order-item">
                                <input type="checkbox" class="order-checkbox">
                                <div class="order-image"></div>
                                <div class="order-info">
                                    <h3>Pesanan disetujui penjual</h3>
                                    <p>Hi, User! Pesanan kamu telah disetujui penjual untuk dibuat! Harap ditunggu hasil akhirnya, ya!</p>
                                </div>
                                <button class="detail-btn">Tampilkan rincian</button>
                            </div>

                            <div class="order-item">
                                <input type="checkbox" class="order-checkbox">
                                <div class="order-image"></div>
                                <div class="order-info">
                                    <h3>Pesanan disetujui penjual</h3>
                                    <p>Hi, User! Pesanan kamu telah disetujui penjual untuk dibuat! Harap ditunggu hasil akhirnya, ya!</p>
                                </div>
                                <button class="detail-btn">Tampilkan rincian</button>
                            </div>

                            <div class="order-item">
                                <input type="checkbox" class="order-checkbox">
                                <div class="order-image"></div>
                                <div class="order-info">
                                    <h3>Pesanan disetujui penjual</h3>
                                    <p>Hi, User! Pesanan kamu telah disetujui penjual untuk dibuat! Harap ditunggu hasil akhirnya, ya!</p>
                                </div>
                                <button class="detail-btn">Tampilkan rincian</button>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </div>
    </div>
</body>
</html>