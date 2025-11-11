<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LGI Store</title>
</head>
<body>
    <!-- Main Page Content -->
    <div class="main-content">
        <div class="demo-section">
            <h1>🛍️ Welcome to LGI Store</h1>
            <p>Ini adalah halaman utama website Anda. Chatbot akan muncul sebagai popup di kanan bawah tanpa mengganggu konten utama.</p>
            <p><strong>👉 Klik tombol chat di kanan bawah</strong> untuk membuka chatbot assistant!</p>
        </div>

        <div class="demo-section">
            <h2>Tentang LGI Store</h2>
            <p>LGI Store adalah toko online yang menyediakan berbagai macam produk fashion berkualitas dengan harga terjangkau.</p>
            <p>Kami berkomitmen untuk memberikan pelayanan terbaik kepada pelanggan kami dengan dukungan chatbot yang siap membantu Anda 24/7.</p>
        </div>

        <div class="demo-section">
            <h2>Fitur Chatbot</h2>
            <p>✅ Rekomendasi produk berdasarkan budget</p>
            <p>✅ Quick replies untuk pertanyaan umum</p>
            <p>✅ Product cards dengan detail lengkap</p>
            <p>✅ Tambah ke keranjang langsung dari chat</p>
            <p>✅ Responsif dan mobile-friendly</p>
        </div>
    </div>

    <!-- Chatbot Trigger Button -->
    <button class="chatbot-trigger" id="chatbotTrigger">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
        </svg>
    </button>

    <!-- Chatbot Popup -->
    <div class="chatbot-popup" id="chatbotPopup">
        <!-- Chatbot Header -->
        <div class="chatbot-header">
            <div class="chatbot-avatar"></div>
            <div class="chatbot-info">
                <div class="chatbot-name">LGI STORE</div>
                <div class="chatbot-status">Online - Balas Cepat</div>
            </div>
        </div>
        
        <div class="chatbot-container">
            <!-- Chatbot Messages -->
            <div class="chatbot-messages" id="chatbotMessages">
                <div class="message bot-message">
                    <div class="message-avatar"></div>
                    <div class="message-content">
                        <div class="message-bubble">Hallo saya asisten belanja, mau cari produk apa hari ini?</div>
                    </div>
                </div>

                <div class="message user-message">
                    <div class="message-avatar"></div>
                    <div class="message-content">
                        <div class="message-bubble">Rekomendasi baju &lt;100k dong min</div>
                    </div>
                </div>

                <div class="message bot-message">
                    <div class="message-avatar"></div>
                    <div class="message-content">
                        <div class="message-bubble">Oke! ini 3 pilihan untuk mu</div>
                        
                        <div class="product-card">
                            <div class="product-info">
                                <div class="product-name">Baju Kaos Keren</div>
                                <div class="product-category">Pakaian | M, L, S, XL</div>
                                <div class="product-price">Rp. 98.000.00</div>
                                <div class="product-actions">
                                    <button class="product-btn product-btn-primary">Pilih</button>
                                    <button class="product-btn product-btn-secondary">Detail</button>
                                </div>
                            </div>
                            <div class="product-image">
                                <img src="https://via.placeholder.com/100x100/556B2F/FFFFFF?text=TOPI" alt="Product">
                            </div>
                        </div>

                        <div class="product-card">
                            <div class="product-info">
                                <div class="product-name">Baju Kaos Keren</div>
                                <div class="product-category">Pakaian | M, L, S, XL</div>
                                <div class="product-price">Rp. 98.000.00</div>
                                <div class="product-actions">
                                    <button class="product-btn product-btn-primary">Pilih</button>
                                    <button class="product-btn product-btn-secondary">Detail</button>
                                </div>
                            </div>
                            <div class="product-image">
                                <img src="https://via.placeholder.com/100x100/556B2F/FFFFFF?text=TOPI" alt="Product">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="quick-replies">
                    <button class="quick-reply-btn">Tawarkan diskon 10 %</button>
                    <button class="quick-reply-btn">Minta ukuran</button>
                    <button class="quick-reply-btn">Minta budget</button>
                    <button class="quick-reply-btn">Rekomendasi lagi</button>
                </div>
            </div>

            <!-- Chatbot Input -->
            <div class="chatbot-input-wrapper">
                <div class="chatbot-input-container">
                    <input 
                        type="text" 
                        class="chatbot-input" 
                        id="chatbotInput"
                        placeholder="Ketik balasan..."
                    >
                    <button class="chatbot-send" id="chatbotSend">Kirim</button>
                </div>
            </div>
        </div>
    </div>

    <script type="module" src="/chatbot.js"></script>
</body>
</html>