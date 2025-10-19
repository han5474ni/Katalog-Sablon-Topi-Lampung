<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Keranjang - LGI Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    @vite(['resources/css/customer/shared.css', 'resources/js/customer/cart.js'])
</head>
<body class="bg-slate-50">
    <div class="flex h-screen">
        <x-customer-sidebar active="keranjang" />

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <x-customer-header title="Keranjang" />

            <!-- Cart Content -->
            <div class="p-4 md:p-8">
                <div class="mx-auto max-w-6xl">
                    <!-- Header -->
                    <header class="mb-6 flex items-center justify-between">
                        <h1 class="text-2xl font-bold text-slate-900">Keranjang Belanja</h1>
                        <button id="clear-cart-btn" class="hidden px-4 py-2 text-sm font-medium text-slate-800 bg-slate-100 rounded-xl hover:bg-slate-200 transition">
                            Kosongkan
                        </button>
                    </header>

                    <!-- Empty State -->
                    <div id="empty-cart" class="hidden rounded-2xl border border-slate-200 bg-white shadow-sm p-8 text-center">
                        <div class="mx-auto mb-4 h-16 w-16 rounded-2xl bg-slate-100 flex items-center justify-center">
                            <span class="material-icons text-slate-400 text-4xl">shopping_cart</span>
                        </div>
                        <h2 class="text-lg font-semibold text-slate-900">Keranjangmu kosong</h2>
                        <p class="mt-1 text-sm text-slate-500">Mulai belanja dan tambahkan produk favoritmu.</p>
                        <div class="mt-4">
                            <a href="{{ route('catalog', 'all') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-900 text-white text-sm font-medium rounded-xl hover:bg-slate-800 transition">
                                Lihat Produk
                            </a>
                        </div>
                    </div>

                    <!-- Cart Items -->
                    <div id="cart-content" class="grid gap-6 md:grid-cols-3">
                        <!-- Left: Cart List -->
                        <div class="md:col-span-2 space-y-4">
                            <!-- Cart Items Card -->
                            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                                <!-- Header -->
                                <div class="flex items-center justify-between border-b border-slate-100 p-4">
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" id="select-all" class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400">
                                        <label for="select-all" class="text-sm text-slate-700 cursor-pointer">Pilih semua</label>
                                    </div>
                                    <div>
                                        <button id="delete-selected-btn" class="hidden px-3 py-1.5 text-sm font-medium text-white bg-rose-600 rounded-xl hover:bg-rose-700 transition">
                                            Hapus yang dipilih
                                        </button>
                                        <div id="item-count" class="text-sm text-slate-500">0 item</div>
                                    </div>
                                </div>

                                <!-- Items List -->
                                <ul id="cart-items" class="divide-y divide-slate-100">
                                    <!-- Items will be inserted here by JavaScript -->
                                </ul>
                            </div>

                            <!-- Coupon Card -->
                            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-4">
                                <div class="flex flex-wrap items-center gap-3">
                                    <input
                                        id="coupon-input"
                                        type="text"
                                        placeholder="Kode kupon (contoh: HEMAT10)"
                                        class="flex-1 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-100"
                                    />
                                    <button id="apply-coupon-btn" class="px-4 py-2.5 text-sm font-medium text-slate-800 bg-slate-100 rounded-xl hover:bg-slate-200 transition">
                                        Terapkan
                                    </button>
                                    <span id="coupon-badge" class="hidden rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-700">
                                        Kupon <span id="coupon-code"></span> aktif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Order Summary -->
                        <div class="space-y-4">
                            <!-- Summary Card -->
                            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">
                                <h3 class="mb-4 text-base font-semibold text-slate-900">Ringkasan Pesanan</h3>

                                <div class="space-y-3 text-sm">
                                    <div class="flex items-center justify-between">
                                        <span class="text-slate-600">Subtotal</span>
                                        <span id="subtotal" class="font-medium text-slate-900">Rp 0</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-slate-600">Diskon</span>
                                        <span id="discount" class="font-medium text-emerald-700">-Rp 0</span>
                                    </div>

                                    <!-- Shipping Method -->
                                    <div class="pt-2">
                                        <div class="mb-2 text-slate-600">Pengiriman</div>
                                        <div class="space-y-2">
                                            <label class="flex cursor-pointer items-center justify-between rounded-xl border border-slate-200 p-3 text-sm hover:bg-slate-50 transition">
                                                <div>
                                                    <input type="radio" name="shipping" value="STANDARD" class="mr-2" checked>
                                                    <span>Standard (2-4 hari)</span>
                                                </div>
                                                <div class="font-medium">Rp 15.000</div>
                                            </label>
                                            <label class="flex cursor-pointer items-center justify-between rounded-xl border border-slate-200 p-3 text-sm hover:bg-slate-50 transition">
                                                <div>
                                                    <input type="radio" name="shipping" value="EXPRESS" class="mr-2">
                                                    <span>Express (1-2 hari)</span>
                                                </div>
                                                <div class="font-medium">Rp 30.000</div>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between pt-2">
                                        <span class="text-slate-600">PPN (11%)</span>
                                        <span id="tax" class="font-medium text-slate-900">Rp 0</span>
                                    </div>

                                    <div class="flex items-center justify-between border-t border-slate-100 pt-3 text-base">
                                        <span class="font-semibold text-slate-900">Total</span>
                                        <span id="total" class="font-bold text-slate-900">Rp 0</span>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button id="checkout-btn" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-900 text-white text-sm font-medium rounded-xl hover:bg-slate-800 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                        Lanjutkan ke Checkout
                                    </button>
                                </div>
                            </div>

                            <!-- Notes Card -->
                            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">
                                <h3 class="mb-2 text-base font-semibold text-slate-900">Catatan</h3>
                                <p class="text-sm text-slate-600">Biaya pengiriman dapat berubah berdasarkan alamat pada halaman checkout. Kupon tertentu mungkin hanya berlaku untuk kategori/brand tertentu.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>