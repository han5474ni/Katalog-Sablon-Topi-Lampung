<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - LGI Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    @vite(['resources/css/components/product-card.css', 'resources/css/guest/home.css', 'resources/css/components/footer.css', 'resources/css/guest/chatbot.css', 'resources/js/guest/home.js', 'resources/js/guest/product-slider.js', 'resources/js/guest/product-card-carousel.js', 'resources/js/guest/chatbot-popup.js'])
</head>
<body>
    <!-- Header -->
    <x-navbar />



    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>CARI STYLE JERSEY FAVORITMU</h1>
            <p>Bukan cuma jersey, topi, celana, dan lain-lain juga ada loh. Kamu juga bisa kustom mereka tanpa minimal pembelian. Buruan, daftarkan akunmu dan checkout sekarang!</p>
            <a href="{{ route('login') }}" class="shop-btn-link"><button class="shop-btn">Shop Now</button></a>

            <div class="stats">
                <div class="stat-item">
                    <div class="stat-number">2</div>
                    <div class="stat-label">Cabang</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">200+</div>
                    <div class="stat-label">Custom Design</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">1,000+</div>
                    <div class="stat-label">Pembelian</div>
                </div>
            </div>
        </div>

        <div class="hero-image">
            <img src="https://i.pinimg.com/originals/e9/04/53/e904533ed00df550bb4fc87064217f18.png" alt="Minimalist Jersey" class="hero-img" width="640" height="640" decoding="async" fetchpriority="high">
        </div>
    </section>

    <!-- New Arrivals -->
    <section class="new-arrivals">
        <h2 class="section-title">NEW ARRIVALS</h2>
        <div class="product-container">
            <button class="slider-nav slider-prev" data-slider="arrivals">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="slider-nav slider-next" data-slider="arrivals">
                <i class="fas fa-chevron-right"></i>
            </button>
            <div class="product-slider" id="arrivals-slider">
                <div class="product-grid slider-track">
                @forelse($newArrivals as $product)
                    <x-product-card :product="$product" />
                @empty
                    <div class="no-products">
                        <p>Belum ada produk baru.</p>
                    </div>
                @endforelse
                </div>
            </div>
            <button class="view-all-btn">View All</button>
        </div>
    </section>

    <!-- Products Grid -->
    <section class="products-section">
        <h2 class="section-title">TOP SELLING</h2>
        <div class="product-container">
            <button class="slider-nav slider-prev" data-slider="selling">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="slider-nav slider-next" data-slider="selling">
                <i class="fas fa-chevron-right"></i>
            </button>
            <div class="product-slider" id="selling-slider">
                <div class="product-grid slider-track">
                @forelse($topSelling as $product)
                    <x-product-card :product="$product" :showRibbon="false" />
                @empty
                    <div class="no-products">
                        <p>Belum ada produk best seller.</p>
                    </div>
                @endforelse
                </div>
            </div>
        </div>
        <button class="view-all-btn top-selling-view-all">View All</button>
    </section>

    

    <!-- Footer Component -->
    <x-guest-footer />

    <!-- Chatbot Trigger Button -->
    <button class="chatbot-trigger" id="chatbotTrigger" aria-label="Buka chat">
        <span class="material-icons" aria-hidden="true">chat</span>
    </button>

    <!-- Chatbot Popup -->
    <div class="chatbot-popup" id="chatbotPopup">
        <!-- Chatbot Header -->
        <div class="chatbot-header">
            <div class="chatbot-avatar">
                <span class="material-icons">support_agent</span>
            </div>
            <div class="chatbot-info">
                <div class="chatbot-name">LGI STORE</div>
                <div class="chatbot-status">Online - Balas Cepat</div>
            </div>
        </div>

        <div class="chatbot-container">
            <!-- Chatbot Messages -->
            <div class="chatbot-messages" id="chatbotMessages">
                <div class="message bot-message">
                    <div class="message-avatar">
                        <span class="material-icons">support_agent</span>
                    </div>
                    <div class="message-content">
                        <div class="message-bubble">
                            <p>Halo! Selamat datang di LGI Store! Ada yang bisa saya bantu hari ini?</p>
                        </div>
                        <span class="message-time">{{ now()->format('H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Replies -->
            <div class="chatbot-input-wrapper" style="border-top:none;padding-bottom:8px;">
                <div class="quick-replies" style="display:flex;flex-wrap:wrap;gap:8px;">
                    <button type="button" class="quick-reply" data-text="Apakah stok produk ini tersedia?" style="padding:6px 12px;border-radius:16px;border:1px solid #e5e7eb;background:#fff;font-size:12px;color:#374151;cursor:pointer;">Cek stok</button>
                    <button type="button" class="quick-reply" data-text="Berapa estimasi harga untuk produk ini?" style="padding:6px 12px;border-radius:16px;border:1px solid #e5e7eb;background:#fff;font-size:12px;color:#374151;cursor:pointer;">Estimasi harga</button>
                    <button type="button" class="quick-reply" data-text="Berapa lama estimasi pengiriman?" style="padding:6px 12px;border-radius:16px;border:1px solid #e5e7eb;background:#fff;font-size:12px;color:#374151;cursor:pointer;">Estimasi kirim</button>
                    <button type="button" class="quick-reply" data-text="Apakah bisa custom desain?" style="padding:6px 12px;border-radius:16px;border:1px solid #e5e7eb;background:#fff;font-size:12px;color:#374151;cursor:pointer;">Custom desain</button>
                    <button type="button" class="quick-reply" data-text="Ada diskon atau promo saat ini?" style="padding:6px 12px;border-radius:16px;border:1px solid #e5e7eb;background:#fff;font-size:12px;color:#374151;cursor:pointer;">Promo</button>
                </div>
            </div>

            <!-- Chatbot Input -->
            <div class="chatbot-input-wrapper">
                <div class="chatbot-input-container">
                    <input
                        type="text"
                        class="chatbot-input"
                        id="chatbotInput"
                        placeholder="Ketik pesan Anda..."
                    >
                    <button class="chatbot-send" id="chatbotSend">Kirim</button>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function(){
                    const container = document.getElementById('chatbotPopup');
                    if(!container) return;
                    const input = container.querySelector('#chatbotInput');
                    const sendBtn = container.querySelector('#chatbotSend');
                    container.querySelectorAll('.quick-reply').forEach(btn => {
                        btn.addEventListener('click', () => {
                            if(!input) return;
                            input.value = btn.getAttribute('data-text') || btn.textContent;
                            input.focus();
                            // Optional: langsung kirim
                            // sendBtn?.click();
                        });
                    });
                });
            </script>
        </div>
    </div>
</body>
</html>
