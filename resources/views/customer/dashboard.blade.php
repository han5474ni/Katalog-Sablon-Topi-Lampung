<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - LGI Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    @vite('resources/css/customer/shared.css')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            window.addEventListener('profile-updated', event => {
                const newAvatarUrl = event.detail.avatarUrl;
                document.querySelectorAll('.header-avatar').forEach(img => {
                    img.src = newAvatarUrl;
                });
            });
        });
    </script>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <x-customer-sidebar active="dashboard" />

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <x-customer-header title="Dashboard" />

            <!-- Dashboard Content -->
            <div class="p-6">
                <!-- Statistics Cards -->
                <div class="grid grid-cols-4 gap-6 mb-6">
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm text-gray-500 mb-2">Total Akumulasi</h3>
                        <p class="text-2xl font-bold mb-1">Rp 5.725.000</p>
                        <p class="text-xs text-gray-500">*Akumulasi belanja selama 365 hari</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm text-gray-500 mb-2">Total Barang</h3>
                        <p class="text-2xl font-bold mb-1">1000</p>
                        <p class="text-xs text-gray-500">*Akumulasi belanja selama 365 hari</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm text-gray-500 mb-2">Barang Selesai</h3>
                        <p class="text-2xl font-bold mb-1">980</p>
                        <p class="text-xs text-gray-500">*Akumulasi belanja selama 365 hari</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-sm text-gray-500 mb-2">Barang Dibatalkan</h3>
                        <p class="text-2xl font-bold mb-1">20</p>
                        <p class="text-xs text-gray-500">*Akumulasi belanja selama 365 hari</p>
                    </div>
                </div>

                <!-- Customer Info and Orders -->
                <div class="grid grid-cols-3 gap-6">
                    <!-- Customer Info -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h2 class="text-lg font-bold mb-4">Info Customer</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm text-gray-500">Name</label>
                                <input type="text" value="{{ auth()->user()->name }}" class="w-full border rounded-lg p-2" readonly>
                            </div>
                            <div>
                                <label class="text-sm text-gray-500">Email</label>
                                <input type="email" value="{{ auth()->user()->email }}" class="w-full border rounded-lg p-2" readonly>
                            </div>
                            <div>
                                <label class="text-sm text-gray-500">Telepon</label>
                                <input type="tel" value="{{ auth()->user()->phone ?? '08123456789' }}" class="w-full border rounded-lg p-2" readonly>
                            </div>
                            <div>
                                <label class="text-sm text-gray-500">Alamat pengiriman</label>
                                <textarea class="w-full border rounded-lg p-2" readonly>{{ auth()->user()->address ?? 'TERA' }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Orders -->
                    <div class="col-span-2">
                        <div class="bg-white p-6 rounded-lg shadow">
                            <h2 class="text-lg font-bold mb-4">Orders</h2>
                            <div class="flex gap-2 mb-4">
                                <button class="bg-yellow-400 px-4 py-2 rounded-lg">All orders</button>
                                <button class="text-gray-500 px-4 py-2 rounded-lg hover:bg-gray-100">Dalam Proses</button>
                                <button class="text-gray-500 px-4 py-2 rounded-lg hover:bg-gray-100">Selesai</button>
                                <button class="text-gray-500 px-4 py-2 rounded-lg hover:bg-gray-100">Dibatalkan</button>
                            </div>
                            
                            <!-- Orders Table -->
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr class="text-left">
                                            <th class="pb-3">ID</th>
                                            <th class="pb-3">Status</th>
                                            <th class="pb-3">Nama Produk</th>
                                            <th class="pb-3">Tanggal</th>
                                            <th class="pb-3">Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y">
                                        @php
                                            $orders = [
                                                ['id' => '#35423', 'status' => 'Selesai', 'product' => 'Kaos sablon custom kuning-merah', 'date' => '14 Oktober 2025', 'price' => 'Rp 120.000'],
                                                ['id' => '#35423', 'status' => 'Dalam proses', 'product' => 'Kaos sablon custom kuning-merah', 'date' => '14 Oktober 2025', 'price' => 'Rp 120.000'],
                                                ['id' => '#35423', 'status' => 'Selesai', 'product' => 'Kaos sablon custom kuning-merah', 'date' => '14 Oktober 2025', 'price' => 'Rp 120.000'],
                                                ['id' => '#35423', 'status' => 'Dibatalkan', 'product' => 'Kaos sablon custom kuning-merah', 'date' => '14 Oktober 2025', 'price' => 'Rp 120.000'],
                                                ['id' => '#35423', 'status' => 'Selesai', 'product' => 'Kaos sablon custom kuning-merah', 'date' => '14 Oktober 2025', 'price' => 'Rp 120.000'],
                                            ];
                                        @endphp
                                        
                                        @foreach($orders as $order)
                                        <tr class="text-sm">
                                            <td class="py-3">{{ $order['id'] }}</td>
                                            <td class="py-3">
                                                <span class="px-2 py-1 rounded-full text-xs
                                                    {{ $order['status'] === 'Dalam proses' ? 'bg-yellow-100 text-yellow-800' :
                                                       ($order['status'] === 'Selesai' ? 'bg-green-100 text-green-800' :
                                                       'bg-red-100 text-red-800') }}">
                                                    {{ $order['status'] }}
                                                </span>
                                            </td>
                                            <td class="py-3">{{ $order['product'] }}</td>
                                            <td class="py-3">{{ $order['date'] }}</td>
                                            <td class="py-3">{{ $order['price'] }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</body>
</html>