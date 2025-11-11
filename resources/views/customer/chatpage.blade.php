<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat - LGI Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/guest/chatpage.css', 'resources/js/customer/chatbot.js'])
</head>
<body class="chatpage-body">
    <!-- Header -->
    <header class="chatpage-header">
        <div class="chatpage-container">
            <div class="chatpage-header-content">
                <a href="{{ route('home') }}" class="chatpage-back-btn">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Beranda</span>
                </a>
                <div class="chatpage-title">
                    <div class="chatpage-avatar">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div class="chatpage-info">
                        <h1>LGI STORE</h1>
                        <span class="chatpage-status">Online - Balas Cepat</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Chat Container -->
    <div class="chatpage-container">
        <div class="chatpage-chat">
            <!-- Chat Messages -->
            <div class="chatpage-messages" id="chatbotMessages">
                <div class="message bot-message">
                    <div class="message-avatar">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div class="message-content">
                        <div class="message-bubble">
                            <div class="message-text">Hallo! Selamat datang di LGI Store! 👋</div>
                            <div class="message-text">Saya adalah asisten belanja Anda. Ada yang bisa saya bantu hari ini?</div>
                        </div>
                        <div class="message-time">{{ now()->format('H:i') }}</div>
                    </div>
                </div>

                <div class="message bot-message">
                    <div class="message-avatar">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div class="message-content">
                        <div class="message-bubble">
                            <div class="message-text">Kami menyediakan berbagai produk fashion berkualitas dengan harga terjangkau:</div>
                            <div class="message-text">• Kaos polos dan bermotif</div>
                            <div class="message-text">• Jaket dan hoodie</div>
                            <div class="message-text">• Topi dan aksesoris</div>
                            <div class="message-text">• Custom design tersedia</div>
                        </div>
                        <div class="message-time">{{ now()->format('H:i') }}</div>
                    </div>
                </div>

                <div class="quick-replies">
                    <button class="quick-reply-btn" data-reply="Rekomendasi kaos pria">Rekomendasi kaos pria</button>
                    <button class="quick-reply-btn" data-reply="Topi keren">Topi keren</button>
                    <button class="quick-reply-btn" data-reply="Custom design">Custom design</button>
                    <button class="quick-reply-btn" data-reply="Cek promo">Cek promo</button>
                </div>
            </div>

            <!-- Chat Input -->
            <div class="chatpage-input-wrapper">
                <div class="chatpage-input-container">
                    <input
                        type="text"
                        class="chatpage-input"
                        id="chatbotInput"
                        placeholder="Ketik pesan Anda..."
                        maxlength="500"
                    >
                    <button class="chatpage-send" id="chatbotSend" disabled>
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
                <div class="chatpage-input-footer">
                    <span class="chatpage-typing-indicator" id="typingIndicator" style="display: none;">
                        <i class="fas fa-circle"></i>
                        <i class="fas fa-circle"></i>
                        <i class="fas fa-circle"></i>
                        LGI Store sedang mengetik...
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Recommendations Modal -->
    <div class="chatpage-modal" id="productModal" style="display: none;">
        <div class="chatpage-modal-overlay"></div>
        <div class="chatpage-modal-content">
            <div class="chatpage-modal-header">
                <h3>Rekomendasi Produk</h3>
                <button class="chatpage-modal-close" id="modalClose">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="chatpage-modal-body" id="modalBody">
                <!-- Products will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        // Initialize chatbot functionality
        document.addEventListener('DOMContentLoaded', function() {
            const messagesContainer = document.getElementById('chatbotMessages');
            const input = document.getElementById('chatbotInput');
            const sendBtn = document.getElementById('chatbotSend');
            const typingIndicator = document.getElementById('typingIndicator');

            // Enable/disable send button based on input
            input.addEventListener('input', function() {
                sendBtn.disabled = this.value.trim().length === 0;
            });

            // Handle quick replies
            document.querySelectorAll('.quick-reply-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const reply = this.dataset.reply;
                    addUserMessage(reply);
                    processUserMessage(reply);
                });
            });

            // Handle send button
            sendBtn.addEventListener('click', function() {
                const message = input.value.trim();
                if (message) {
                    addUserMessage(message);
                    processUserMessage(message);
                    input.value = '';
                    sendBtn.disabled = true;
                }
            });

            // Handle enter key
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendBtn.click();
                }
            });

            function addUserMessage(text) {
                const messageDiv = document.createElement('div');
                messageDiv.className = 'message user-message';
                messageDiv.innerHTML = `
                    <div class="message-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="message-content">
                        <div class="message-bubble">
                            <div class="message-text">${escapeHtml(text)}</div>
                        </div>
                        <div class="message-time">${new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'})}</div>
                    </div>
                `;
                messagesContainer.appendChild(messageDiv);
                scrollToBottom();
            }

            function addBotMessage(text, showTyping = true) {
                if (showTyping) {
                    typingIndicator.style.display = 'block';
                    scrollToBottom();

                    setTimeout(() => {
                        typingIndicator.style.display = 'none';

                        const messageDiv = document.createElement('div');
                        messageDiv.className = 'message bot-message';
                        messageDiv.innerHTML = `
                            <div class="message-avatar">
                                <i class="fas fa-robot"></i>
                            </div>
                            <div class="message-content">
                                <div class="message-bubble">
                                    <div class="message-text">${text}</div>
                                </div>
                                <div class="message-time">${new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'})}</div>
                            </div>
                        `;
                        messagesContainer.appendChild(messageDiv);
                        scrollToBottom();
                    }, 1000 + Math.random() * 1000);
                } else {
                    const messageDiv = document.createElement('div');
                    messageDiv.className = 'message bot-message';
                    messageDiv.innerHTML = `
                        <div class="message-avatar">
                            <i class="fas fa-robot"></i>
                        </div>
                        <div class="message-content">
                            <div class="message-bubble">
                                <div class="message-text">${text}</div>
                            </div>
                            <div class="message-time">${new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'})}</div>
                        </div>
                    `;
                    messagesContainer.appendChild(messageDiv);
                    scrollToBottom();
                }
            }

            function processUserMessage(message) {
                const lowerMessage = message.toLowerCase();

                // Remove quick replies after user sends message
                document.querySelectorAll('.quick-replies').forEach(el => el.remove());

                if (lowerMessage.includes('kaos') || lowerMessage.includes('baju')) {
                    addBotMessage('Baik, saya akan rekomendasikan beberapa kaos keren untuk Anda! 🎽<br><br>Silakan pilih kategori:<br>• Kaos polos<br>• Kaos motif<br>• Kaos custom<br><br>Atau beri tahu saya budget dan ukuran yang Anda inginkan.');

                    setTimeout(() => {
                        addBotMessage('Kami punya kaos dengan harga mulai dari Rp 45.000,-<br><br>Apakah Anda tertarik dengan:<br>1. Kaos pria<br>2. Kaos wanita<br>3. Kaos anak<br>4. Semua kategori');
                    }, 2000);

                } else if (lowerMessage.includes('topi') || lowerMessage.includes('cap')) {
                    addBotMessage('Topi keren dari LGI Store sangat populer! 🎩<br><br>Kami punya berbagai model:<br>• Topi baseball<br>• Topi snapback<br>• Topi trucker<br>• Topi custom<br><br>Harga mulai dari Rp 35.000,-');

                    setTimeout(() => {
                        addBotMessage('Topi kami menggunakan bahan berkualitas dan desain yang stylish. Cocok untuk daily wear atau koleksi!<br><br>Mau lihat katalog lengkapnya?');
                    }, 2000);

                } else if (lowerMessage.includes('custom') || lowerMessage.includes('desain')) {
                    addBotMessage('Custom design adalah spesialisasi kami! 🎨<br><br>Anda bisa upload gambar/logo Anda sendiri dan kami akan sablon di:<br>• Kaos<br>• Jaket<br>• Topi<br>• Tas<br><br>Proses mudah dan hasil berkualitas!');

                    setTimeout(() => {
                        addBotMessage('Untuk custom design:<br>1. Pilih produk dasar<br>2. Upload desain Anda<br>3. Pilih area sablon<br>4. Tentukan jumlah<br><br>Mulai dari Rp 75.000,- per item tergantung kompleksitas desain.');
                    }, 2000);

                } else if (lowerMessage.includes('promo') || lowerMessage.includes('diskon')) {
                    addBotMessage('Saat ini kami punya beberapa promo menarik! 🎉<br><br>🛍️ <strong>Buy 2 Get 1 Free</strong> untuk kaos polos<br>🛍️ <strong>Diskon 20%</strong> untuk custom design pertama<br>🛍️ <strong>Free ongkir</strong> minimal pembelian Rp 200.000,-');

                    setTimeout(() => {
                        addBotMessage('Promo berlaku untuk pembelian online melalui website kami.<br><br>Mau saya bantu cari produk yang lagi promo?');
                    }, 2000);

                } else if (lowerMessage.includes('harga') || lowerMessage.includes('price')) {
                    addBotMessage('Range harga produk LGI Store:<br><br>🧢 Topi: Rp 35.000 - Rp 85.000<br>👕 Kaos: Rp 45.000 - Rp 125.000<br>🧥 Jaket: Rp 150.000 - Rp 350.000<br>🎨 Custom Design: +Rp 30.000 - Rp 100.000<br><br>Harga belum termasuk ongkos kirim.');

                } else if (lowerMessage.includes('kontak') || lowerMessage.includes('wa') || lowerMessage.includes('whatsapp')) {
                    addBotMessage('Anda bisa menghubungi kami langsung via WhatsApp untuk konsultasi lebih lanjut:<br><br>📱 <strong>WA: 0821-7839-6916</strong><br><br>Kami siap membantu Anda 24/7! 😊');

                } else {
                    // Default response for unrecognized messages
                    const responses = [
                        'Baik, saya mengerti. Ada yang bisa saya bantu lainnya?',
                        'Silakan beri tahu saya lebih detail tentang yang Anda cari.',
                        'Apakah Anda tertarik dengan produk tertentu?',
                        'Saya siap membantu Anda menemukan produk yang tepat!'
                    ];

                    const randomResponse = responses[Math.floor(Math.random() * responses.length)];
                    addBotMessage(randomResponse);
                }
            }

            function scrollToBottom() {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            // Auto scroll to bottom on load
            scrollToBottom();
        });
    </script>
</body>
</html>
