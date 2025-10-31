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
                <div class="mx-auto max-w-full">
                    

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

                    <!-- Cart Items Table -->
                    <div id="cart-content" class="bg-white rounded-lg shadow-sm border border-slate-200">
                        <!-- Table Header -->
<div class="grid grid-cols-12 gap-4 p-4 border-b border-slate-200 bg-slate-50 text-sm font-semibold text-slate-700">
    <div class="col-span-1 flex items-center justify-center">
        <input type="checkbox" id="select-all-top" class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400">
    </div>
    <div class="col-span-4 flex items-center">Semua Produk</div>
    <div class="col-span-2 text-center">Harga Satuan</div>
    <div class="col-span-2 text-center">Kuantitas</div>
    <div class="col-span-2 text-center">Total Harga</div>
    <div class="col-span-1 text-center">Aksi</div>
</div>


                        <!-- Cart Items -->
                        <div id="cart-items" class="divide-y divide-slate-200">
                           

                            <!-- Sample Item 2 -->
                            <div class="grid grid-cols-12 gap-4 p-4 hover:bg-slate-50 transition items-center">
                                <div class="col-span-1 flex items-center">
                                    <input type="checkbox" class="item-checkbox h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400" onchange="updateCheckoutCount()">
                                </div>
                                <div class="col-span-4 flex items-center gap-4">
                                    <div class="w-16 h-16 bg-amber-900 rounded-lg flex items-center justify-center">
                                        <svg class="w-10 h-10 text-amber-700" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M16 18v2H8v-2h8M12 2a7 7 0 0 1 7 7c0 2.38-1.19 4.47-3 5.74V17a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1v-2.26C6.19 13.47 5 11.38 5 9a7 7 0 0 1 7-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-slate-900">T-Shirt Premium</span>
                                        <div class="text-xs text-slate-500 mt-1">
                                            Variasi:
                                            <button class="text-slate-600 hover:text-slate-900 inline-flex items-center gap-1">
                                                Hijau, XL
                                                <span class="material-icons text-xs">expand_more</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-2 text-center">
                                    <span class="text-sm text-slate-900">Rp 90.000</span>
                                </div>
                                <div class="col-span-2 flex justify-center">
                                    <span class="text-sm text-slate-900">3</span>
                                </div>
                                <div class="col-span-2 text-center">
                                    <span class="text-sm font-medium text-slate-900">Rp 180.000</span>
                                </div>
                                <div class="col-span-1">
                                    <div class="flex flex-col items-center justify-center gap-1 text-center">
                                        <button class="text-sm text-rose-600 hover:text-rose-700 font-medium" onclick="removeItem(2)">
                                            Hapus
                                        </button>
                                       <div class="flex items-center justify-center gap-1">
    <span class="text-xs text-center whitespace-nowrap" style="color: #FFC633;">
        Produk Serupa
    </span>
    <span class="material-icons text-xs cursor-pointer" style="color: #FFC633;">
        expand_more
    </span>
</div>

                                    </div>
                                </div>
                            </div>

                            <!-- Sample Item 3 (Checked) -->
                            <div class="grid grid-cols-12 gap-4 p-4 hover:bg-slate-50 transition items-center bg-blue-50">
                                <div class="col-span-1 flex items-center">
                                    <input type="checkbox" checked class="item-checkbox h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400" onchange="updateCheckoutCount()">
                                </div>
                                <div class="col-span-4 flex items-center gap-4">
                                    <div class="w-16 h-16 bg-amber-900 rounded-lg flex items-center justify-center">
                                        <svg class="w-10 h-10 text-amber-700" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M16 18v2H8v-2h8M12 2a7 7 0 0 1 7 7c0 2.38-1.19 4.47-3 5.74V17a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1v-2.26C6.19 13.47 5 11.38 5 9a7 7 0 0 1 7-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-slate-900">T-Shirt Premium</span>
                                        <div class="text-xs text-slate-500 mt-1">
                                            Variasi:
                                            <button class="text-slate-600 hover:text-slate-900 inline-flex items-center gap-1">
                                                Hijau, XL
                                                <span class="material-icons text-xs">expand_more</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-2 text-center">
                                    <span class="text-sm text-slate-900">Rp 90.000</span>
                                </div>
                                <div class="col-span-2 flex justify-center">
                                    <span class="text-sm text-slate-900">3</span>
                                </div>
                                <div class="col-span-2 text-center">
                                    <span class="text-sm font-medium text-slate-900">Rp 180.000</span>
                                </div>
                                <div class="col-span-1">
                                    <div class="flex flex-col items-center justify-center gap-1 text-center">
                                        <button class="text-sm text-rose-600 hover:text-rose-700 font-medium" onclick="removeItem(3)">
                                            Hapus
                                        </button>
                                        <div class="flex items-center justify-center gap-1">
                                            <span class="text-xs text-yellow-500 text-center whitespace-nowrap">
                                                Produk Serupa
                                            </span>
                                            <span class="material-icons text-xs text-yellow-500 cursor-pointer">expand_more</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sample Item 4 -->
                            <div class="grid grid-cols-12 gap-4 p-4 hover:bg-slate-50 transition items-center">
                                <div class="col-span-1 flex items-center">
                                    <input type="checkbox" class="item-checkbox h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400" onchange="updateCheckoutCount()">
                                </div>
                                <div class="col-span-4 flex items-center gap-4">
                                    <div class="w-16 h-16 bg-amber-900 rounded-lg flex items-center justify-center">
                                        <svg class="w-10 h-10 text-amber-700" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M16 18v2H8v-2h8M12 2a7 7 0 0 1 7 7c0 2.38-1.19 4.47-3 5.74V17a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1v-2.26C6.19 13.47 5 11.38 5 9a7 7 0 0 1 7-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-slate-900">T-Shirt Premium</span>
                                        <div class="text-xs text-slate-500 mt-1">
                                            Variasi:
                                            <button class="text-slate-600 hover:text-slate-900 inline-flex items-center gap-1">
                                                Hijau, XL
                                                <span class="material-icons text-xs">expand_more</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-2 text-center">
                                    <span class="text-sm text-slate-900">Rp 90.000</span>
                                </div>
                                <div class="col-span-2 flex justify-center">
                                    <span class="text-sm text-slate-900">3</span>
                                </div>
                                <div class="col-span-2 text-center">
                                    <span class="text-sm font-medium text-slate-900">Rp 180.000</span>
                                </div>
                                <div class="col-span-1">
                                    <div class="flex flex-col items-center justify-center gap-1 text-center">
                                        <button class="text-sm text-rose-600 hover:text-rose-700 font-medium" onclick="removeItem(4)">
                                            Hapus
                                        </button>
                                        <div class="flex items-center justify-center gap-1">
                                            <span class="text-xs text-yellow-500 text-center whitespace-nowrap">
                                                Produk Serupa
                                            </span>
                                            <span class="material-icons text-xs text-yellow-500 cursor-pointer">expand_more</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sample Item 5 (Custom variant) -->
                            <div class="grid grid-cols-12 gap-4 p-4 hover:bg-slate-50 transition items-center">
                                <div class="col-span-1 flex items-center">
                                    <input type="checkbox" class="item-checkbox h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400" onchange="updateCheckoutCount()">
                                </div>
                                <div class="col-span-4 flex items-center gap-4">
                                    <div class="w-16 h-16 bg-amber-900 rounded-lg flex items-center justify-center">
                                        <svg class="w-10 h-10 text-amber-700" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M16 18v2H8v-2h8M12 2a7 7 0 0 1 7 7c0 2.38-1.19 4.47-3 5.74V17a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1v-2.26C6.19 13.47 5 11.38 5 9a7 7 0 0 1 7-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-slate-900">T-Shirt Premium</span>
                                        <div class="text-xs text-slate-500 mt-1">
                                            Variasi:
                                            <button class="text-slate-600 hover:text-slate-900 inline-flex items-center gap-1">
                                                Custom, XL, Foil
                                                <span class="material-icons text-xs">expand_more</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-2 text-center">
                                    <span class="text-sm text-slate-900">Rp 90.000</span>
                                </div>
                                <div class="col-span-2 flex justify-center">
                                    <span class="text-sm text-slate-900">3</span>
                                </div>
                                <div class="col-span-2 text-center">
                                    <span class="text-sm font-medium text-slate-900">Rp 180.000</span>
                                </div>
                                <div class="col-span-1">
                                    <div class="flex flex-col items-center justify-center gap-1 text-center">
                                        <button class="text-sm text-rose-600 hover:text-rose-700 font-medium" onclick="removeItem(5)">
                                            Hapus
                                        </button>
                                        <div class="flex items-center justify-center gap-1">
                                            <span class="text-xs text-yellow-500 text-center whitespace-nowrap">
                                                Produk Serupa
                                            </span>
                                            <span class="material-icons text-xs text-yellow-500 cursor-pointer">expand_more</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Voucher Section -->
                        <div class="p-4 border-t border-slate-200 bg-slate-50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <input type="checkbox" id="select-voucher" class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400">
                                    <span class="text-sm font-medium text-slate-900">Voucher</span>
                                </div>
                                <div class="flex items-center gap-4">
                                    <select id="voucher-select" class="px-3 py-2 text-sm border border-slate-300 rounded-lg bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-400">
                                        <option value="">Pilih voucher</option>
                                        <option value="5000" selected>-Rp 5.000</option>
                                        <option value="10000">-Rp 10.000</option>
                                        <option value="15000">-Rp 15.000</option>
                                    </select>
                                    <span class="material-icons text-slate-400 cursor-pointer">chevron_right</span>
                                </div>
                            </div>
                        </div>

                        <!-- Bottom Summary -->
                        <div class="p-4 border-t border-slate-200 bg-white">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <input type="checkbox" id="select-all-bottom" class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400">
                                    <label for="select-all-bottom" class="text-sm text-slate-700">
                                        Pilih semua <span id="item-count-text">(5)</span>
                                    </label>
                                    <button id="delete-selected-btn" class="text-sm text-rose-600 hover:text-rose-700 font-medium">
                                        Hapus
                                    </button>
                                </div>
                                <div class="flex items-center gap-8">
                                    <div class="text-right">
                                        <div class="text-xs text-slate-500 mb-1">
                                            Total <span id="subtotal-label">(Hemat Rp 5.000)</span>
                                        </div>
                                        <div class="text-lg font-bold text-slate-900" id="total-price">
                                            Rp 90.000 <span class="text-xs line-through text-slate-400">~ Rp 95.000</span>
                                        </div>
                                    </div>
                                    <button id="checkout-btn" class="px-8 py-3 bg-yellow-400 hover:bg-yellow-500 text-slate-900 font-semibold rounded-lg transition flex items-center gap-2">
                                        Checkout <span id="checkout-count" class="bg-slate-900 text-white text-xs px-2 py-0.5 rounded">(1)</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>