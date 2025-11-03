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

        <div class="flex-1 overflow-auto">
            <x-customer-header title="Keranjang" />

            <div class="p-4 md:p-8">
                <div class="mx-auto max-w-full">
                    @if(session('success'))
                        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-800">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($cartItems->isEmpty())
                        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-8 text-center">
                            <div class="mx-auto mb-4 h-16 w-16 rounded-2xl bg-slate-100 flex items-center justify-center">
                                <span class="material-icons text-slate-400 text-4xl">shopping_cart</span>
                            </div>
                            <h2 class="text-lg font-semibold text-slate-900">Keranjangmu kosong</h2>
                            <p class="mt-1 text-sm text-slate-500">Mulai belanja dan tambahkan produk favoritmu.</p>
                            <div class="mt-4">
                                <a href="{{ route('all-products') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-900 text-white text-sm font-medium rounded-xl hover:bg-slate-800 transition">
                                    Lihat Produk
                                </a>
                            </div>
                        </div>
                    @else
                        @php
                            $formatCurrency = fn($value) => 'Rp ' . number_format($value, 0, ',', '.');
                            $totalItems = $cartItems->count();
                            $subtotal = $cartItems->sum(fn($item) => $item['price'] * $item['quantity']);
                        @endphp

                        <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                                <div>
                                    <h2 class="text-lg font-semibold text-slate-900">Keranjangmu</h2>
                                    <p class="text-sm text-slate-500">Kelola produk pilihanmu sebelum lanjut ke checkout.</p>
                                </div>
                                <div class="flex items-center gap-6">
                                    <div class="text-right">
                                        <p class="text-xs text-slate-500">Subtotal</p>
                                        <p class="text-base font-semibold text-slate-900" id="summary-subtotal">{{ $formatCurrency($subtotal) }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-slate-500">Jumlah Produk</p>
                                        <p class="text-base font-semibold text-slate-900" id="summary-count">{{ $totalItems }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="cart-content" class="bg-white rounded-lg shadow-sm border border-slate-200" data-subtotal="{{ $subtotal }}">
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

                            <div id="cart-items" class="divide-y divide-slate-200">
                                @foreach($cartItems as $item)
                                    @php
                                        $imageUrl = $item['image'] ? (filter_var($item['image'], FILTER_VALIDATE_URL) ? $item['image'] : asset('storage/' . $item['image'])) : 'https://via.placeholder.com/160';
                                        $lineTotal = $item['price'] * $item['quantity'];
                                    @endphp
                                    <div class="cart-item grid grid-cols-12 gap-4 p-4 hover:bg-slate-50 transition items-center" data-key="{{ $item['key'] }}" data-price="{{ $item['price'] }}" data-quantity="{{ $item['quantity'] }}">
                                        <div class="col-span-1 flex items-center justify-center">
                                            <input type="checkbox" class="item-checkbox h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400" checked>
                                        </div>
                                        <div class="col-span-4 flex items-center gap-4">
                                            <div class="w-16 h-16 bg-slate-100 rounded-lg overflow-hidden">
                                                <img src="{{ $imageUrl }}" alt="{{ $item['name'] }}" class="h-full w-full object-cover">
                                            </div>
                                            <div>
                                                <span class="text-sm font-medium text-slate-900">{{ $item['name'] }}</span>
                                                <div class="text-xs text-slate-500 mt-1 space-x-1">
                                                    @if($item['color'])
                                                        <span>Warna: {{ $item['color'] }}</span>
                                                    @endif
                                                    @if($item['size'])
                                                        <span>Ukuran: {{ $item['size'] }}</span>
                                                    @endif
                                                </div>
                                                @if(!is_null($item['stock']))
                                                    <div class="mt-1 text-xs text-slate-400">Stok tersedia: {{ $item['stock'] }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-span-2 text-center">
                                            <span class="text-sm text-slate-900">{{ $formatCurrency($item['price']) }}</span>
                                        </div>
                                        <div class="col-span-2 flex justify-center">
                                            <form method="POST" action="{{ route('cart.update', $item['key']) }}" class="inline-flex items-center rounded-xl border border-slate-200 bg-white">
                                                @csrf
                                                @method('PATCH')
                                                <button type="button" class="quantity-btn px-2 py-1 text-slate-600 hover:bg-slate-50" data-action="decrease">−</button>
                                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="99" class="quantity-input w-12 px-2 py-1 text-center text-sm outline-none" data-initial="{{ $item['quantity'] }}">
                                                <button type="button" class="quantity-btn px-2 py-1 text-slate-600 hover:bg-slate-50" data-action="increase">+</button>
                                                <button type="submit" class="ml-2 hidden rounded-lg bg-slate-900 px-3 py-1 text-xs font-medium text-white hover:bg-slate-800 transition save-quantity">Simpan</button>
                                                <button type="button" class="ml-2 hidden rounded-lg border border-slate-300 px-3 py-1 text-xs font-medium text-slate-600 hover:bg-slate-100 transition cancel-quantity">Batal</button>
                                            </form>
                                        </div>
                                        <div class="col-span-2 text-center">
                                            <span class="text-sm font-medium text-slate-900 line-total">{{ $formatCurrency($lineTotal) }}</span>
                                        </div>
                                        <div class="col-span-1 text-center">
                                            <form method="POST" action="{{ route('cart.remove', $item['key']) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm text-rose-600 hover:text-rose-700 font-medium">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="p-4 border-t border-slate-200 bg-slate-50">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <input type="checkbox" id="select-voucher" class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400">
                                        <span class="text-sm font-medium text-slate-900">Voucher</span>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <select id="voucher-select" class="px-3 py-2 text-sm border border-slate-300 rounded-lg bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-400">
                                            <option value="0">Pilih voucher</option>
                                            <option value="5000">-Rp 5.000</option>
                                            <option value="10000">-Rp 10.000</option>
                                            <option value="15000">-Rp 15.000</option>
                                        </select>
                                        <span class="material-icons text-slate-400 cursor-pointer">chevron_right</span>
                                    </div>
                                </div>
                            </div>

                            <div class="p-4 border-t border-slate-200 bg-white">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <input type="checkbox" id="select-all-bottom" class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400">
                                        <label for="select-all-bottom" class="text-sm text-slate-700">
                                            Pilih semua <span id="item-count-text">({{ $totalItems }})</span>
                                        </label>
                                        <button id="delete-selected-btn" type="button" class="text-sm text-rose-600 hover:text-rose-700 font-medium">
                                            Hapus
                                        </button>
                                    </div>
                                    <div class="flex items-center gap-8">
                                        <div class="text-right">
                                            <div class="text-xs text-slate-500 mb-1">
                                                Total <span id="subtotal-label"></span>
                                            </div>
                                            <div class="text-lg font-bold text-slate-900" id="total-price">
                                                {{ $formatCurrency($subtotal) }}
                                            </div>
                                        </div>
                                        <form method="POST" action="{{ route('checkout') }}" class="inline">
                                            @csrf
                                            <button id="checkout-btn" type="submit" class="px-8 py-3 bg-yellow-400 hover:bg-yellow-500 text-slate-900 font-semibold rounded-lg transition flex items-center gap-2">
                                            Checkout <span id="checkout-count" class="bg-slate-900 text-white text-xs px-2 py-0.5 rounded">({{ $totalItems }})</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form id="bulk-delete-form" method="POST" action="{{ route('cart.bulk-remove') }}" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>
