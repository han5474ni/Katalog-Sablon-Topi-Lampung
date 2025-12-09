<x-customer-layout title="Alamat" active="alamat">
    @vite(['resources/css/guest/alamat.css'])

    <div class="mx-auto max-w-4xl">


        <!-- Progress Steps -->
        <div class="steps-container">
            <div class="step active">
                <div class="step-icon active">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <polyline points="20 6 9 17 4 12"></polyline>
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

            @if($user->addresses && $user->addresses->count() > 0)
                @foreach($user->addresses as $address)
                <!-- Address Card {{ $loop->iteration }} -->
                <div class="address-card {{ $loop->first ? 'selected' : '' }}">
                    <input type="radio" name="address" id="address{{ $address->id }}" value="{{ $address->id }}" {{ $loop->first ? 'checked' : '' }}>
                    <label for="address{{ $address->id }}" class="address-label">
                        <div class="address-header">
                            <span class="address-name">{{ $address->label ?? $address->recipient_name }}</span>
                            @if($address->is_primary)
                            <span class="badge">UTAMA</span>
                            @endif
                        </div>
                        <div class="address-details">
                            <p>{{ $address->address }}, {{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}</p>
                            <p>{{ $address->phone }}</p>
                        </div>
                    </label>
                </div>
                @endforeach
                
                <!-- Edit Address Link -->
                <div class="mt-4 text-center">
                    <a href="{{ route('profile') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium inline-flex items-center">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2">
                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                        </svg>
                        Kelola Alamat di Profil
                    </a>
                </div>
            @else
                <div class="text-center py-8">
                    <div class="mx-auto mb-4 h-16 w-16 rounded-full bg-slate-100 flex items-center justify-center">
                        <span class="material-icons text-slate-400 text-3xl">location_off</span>
                    </div>
                    <p class="text-slate-600">Anda belum memiliki alamat pengiriman.</p>
                    <p class="text-sm text-slate-500 mt-2">Silakan tambahkan alamat di halaman profil.</p>
                    <a href="{{ route('profile') }}" class="inline-block mt-4 px-6 py-2 bg-yellow-400 hover:bg-yellow-500 text-slate-900 font-medium rounded-lg transition">
                        Ke Halaman Profil
                    </a>
                </div>
            @endif
        </div>

        <!-- Action Buttons -->

        <div class="action-buttons">
            <button class="btn btn-orange" onclick="window.location.href='/keranjang'">Kembali</button>
            <button class="btn btn-success" id="wa-pay-btn" onclick="payViaWhatsApp()">Bayar ke WA</button>
        </div>

        <script>
            function payViaWhatsApp() {
                const selectedAddress = document.querySelector('input[name="address"]:checked');
                if (!selectedAddress) {
                    alert('Silakan pilih alamat terlebih dahulu');
                    return;
                }
                // Ambil data pesanan dari halaman atau session (sesuaikan dengan kebutuhan)
                const orderId = '{{ request()->get('order_id') }}';
                const orderType = '{{ request()->get('order_type') }}';
                const userName = '{{ $user->name ?? '' }}';
                const addressId = selectedAddress.value;
                
                // Build the formatted WhatsApp message
                let waText = `Halo Admin,%0A`;
                waText += `Saya ingin membayar pesanan berikut:%0A`;
                waText += `%0A`;
                waText += `🧾 Pesanan #${orderId}%0A`;
                waText += `Jenis: ${orderType}%0A`;
                waText += `%0A`;
                waText += `Daftar Produk:%0A`;
                waText += `[Daftar produk akan ditampilkan sesuai pesanan]%0A`;
                waText += `%0A`;
                waText += `Subtotal: [Subtotal]%0A`;
                waText += `Total Pembayaran: [Total]%0A`;
                waText += `%0A`;
                waText += `Alamat Pengiriman:%0A`;
                waText += `ID Alamat: ${addressId}%0A`;
                waText += `%0A`;
                waText += `Mohon info langkah pembayaran. Terima kasih.`;
                
                // Ganti nomor WA admin di bawah ini
                const waNumber = '{{ str_replace('+', '', env('ADMIN_WHATSAPP_NUMBER', '62895085858888')) }}';
                
                // Debug: Log nomor yang digunakan
                console.log('WhatsApp Admin Number:', waNumber);
                console.log('Expected Number: 62895085858888');
                
                const waUrl = `https://wa.me/${waNumber}?text=${waText}`;
                console.log('WhatsApp URL:', waUrl);
                window.open(waUrl, '_blank');
            }

            // Handle address card clicks
            document.querySelectorAll('.address-card').forEach(card => {
                card.addEventListener('click', function() {
                    document.querySelectorAll('.address-card').forEach(c => c.classList.remove('selected'));
                    this.classList.add('selected');
                });
            });
        </script>

    @stack('scripts')
</x-customer-layout>
