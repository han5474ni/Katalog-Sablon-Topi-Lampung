<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Beranda - LGI Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    @vite(['resources/css/components/product-card.css', 'resources/css/guest/home.css', 'resources/css/components/footer.css', 'resources/css/guest/chatbot.css', 'resources/js/guest/home.js', 'resources/js/guest/product-slider.js', 'resources/js/guest/product-card-carousel.js', 'resources/js/guest/chatbot-popup.js'])
</head>
<body>
    <!-- Header -->
    <x-navbar />



    <!-- Hero Section -->
    <section class="hero hero-elegant">
        <div class="hero-background">
            <img src="{{ asset('images/hero-products.png') }}" alt="Koleksi Produk Fashion" class="hero-bg-img">
            <div class="hero-overlay"></div>
        </div>
        <div class="hero-content-elegant">
            <span class="hero-badge">✨ Custom Design Available</span>
            <h1 class="hero-title">Ekspresikan <span class="highlight">Gayamu</span></h1>
            <p class="hero-subtitle">Kaos, Hoodie, Polo & Topi berkualitas dengan desain custom tanpa minimal order</p>
            <div class="hero-cta">
                <a href="{{ route('login') }}" class="btn-primary-hero">
                    <span>Mulai Belanja</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
                <a href="{{ url('/all-products') }}" class="btn-secondary-hero">
                    <span>Lihat Koleksi</span>
                </a>
            </div>
            <div class="hero-features">
                <div class="feature-item">
                    <i class="fas fa-check-circle"></i>
                    <span>Custom Design</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-check-circle"></i>
                    <span>Kualitas Premium</span>
                </div>
            </div>
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

    <!-- Chatbot Popup -->
    <!-- Chatbot Trigger Button -->
    <button class="chatbot-trigger" id="chatbotTrigger" aria-label="Buka chat">
        <i class="fas fa-comment" aria-hidden="true"></i>
    </button>

    
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

    <!-- Product Chat Modal Component -->
    <x-product-chat-modal />
</body>
</html>