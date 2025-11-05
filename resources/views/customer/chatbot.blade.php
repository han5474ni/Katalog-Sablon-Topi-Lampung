<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat Assistant - LGI Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/guest/chatbot.css', 'resources/js/customer/chatbot.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <x-navbar />

    <div class="chat-container">
        <!-- Sidebar -->
        <aside class="chat-sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <span class="material-icons">shopping_bag</span>
                    <span class="logo-text">LGI Store</span>
                </div>
            </div>

            <div class="sidebar-content">
                <button class="new-chat-btn">
                    <span class="material-icons">add</span>
                    Percakapan Baru
                </button>

                <div class="chat-history">
                    <div class="history-header">
                        <span class="material-icons">history</span>
                        <span>Riwayat Chat</span>
                    </div>
                    
                    <div class="history-list">
                        <div class="history-item history-item-active">
                            <span class="material-icons">chat_bubble</span>
                            <div class="history-info">
                                <div class="history-title">Tanya produk kaos</div>
                                <div class="history-time">5 menit lalu</div>
                            </div>
                        </div>
                        <div class="history-item">
                            <span class="material-icons">chat_bubble_outline</span>
                            <div class="history-info">
                                <div class="history-title">Status pesanan #12345</div>
                                <div class="history-time">2 jam lalu</div>
                            </div>
                        </div>
                        <div class="history-item">
                            <span class="material-icons">chat_bubble_outline</span>
                            <div class="history-info">
                                <div class="history-title">Info pengiriman</div>
                                <div class="history-time">Kemarin</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sidebar-footer">
                <a href="#" class="sidebar-link">
                    <span class="material-icons">home</span>
                    Kembali ke Beranda
                </a>
            </div>
        </aside>

        <!-- Main Chat Area -->
        <main class="chat-main">
            <!-- Chat Header -->
            <header class="chat-header">
                <div class="chat-header-left">
                    <button class="menu-toggle" id="menu-toggle">
                        <span class="material-icons">menu</span>
                    </button>
                    <div class="bot-info">
                        <div class="bot-avatar">
                            <span class="material-icons">support_agent</span>
                        </div>
                        <div class="bot-details">
                            <h2 class="bot-name">LGI Assistant</h2>
                            <span class="bot-status">
                                <span class="status-dot"></span>
                                Online
                            </span>
                        </div>
                    </div>
                </div>
                <div class="chat-header-right">
                    <button class="header-action">
                        <span class="material-icons">more_vert</span>
                    </button>
                </div>
            </header>

            <!-- Chat Messages -->
            <div class="chat-messages" id="chat-messages">
                <!-- Welcome Message -->
                <div class="message-group">
                    <div class="message message-bot">
                        <div class="message-avatar">
                            <span class="material-icons">support_agent</span>
                        </div>
                        <div class="message-content">
                            <div class="message-bubble">
                                <p>Halo! 👋 Selamat datang di LGI Store. Saya adalah asisten virtual yang siap membantu Anda.</p>
                                <p>Ada yang bisa saya bantu hari ini?</p>
                            </div>
                            <span class="message-time">10:30</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="quick-actions">
                    <button class="quick-action-btn">
                        <span class="material-icons">shopping_cart</span>
                        Cek Keranjang
                    </button>
                    <button class="quick-action-btn">
                        <span class="material-icons">local_shipping</span>
                        Status Pesanan
                    </button>
                    <button class="quick-action-btn">
                        <span class="material-icons">inventory_2</span>
                        Info Produk
                    </button>
                    <button class="quick-action-btn">
                        <span class="material-icons">help</span>
                        Bantuan
                    </button>
                </div>

                <!-- User Message -->
                <div class="message-group">
                    <div class="message message-user">
                        <div class="message-content">
                            <div class="message-bubble">
                                <p>Saya mau tanya tentang produk kaos sablon custom</p>
                            </div>
                            <span class="message-time">10:32</span>
                        </div>
                        <div class="message-avatar">
                            <span class="material-icons">account_circle</span>
                        </div>
                    </div>
                </div>

                <!-- Bot Response -->
                <div class="message-group">
                    <div class="message message-bot">
                        <div class="message-avatar">
                            <span class="material-icons">support_agent</span>
                        </div>
                        <div class="message-content">
                            <div class="message-bubble">
                                <p>Baik! Saya akan bantu Anda dengan informasi tentang kaos sablon custom kami. 👕</p>
                                <p>Kami menyediakan berbagai pilihan:</p>
                                <ul>
                                    <li>Kaos cotton combed 24s & 30s</li>
                                    <li>Sablon DTG, Polyflex, atau Plastisol</li>
                                    <li>Minimal order 24 pcs</li>
                                    <li>Harga mulai dari Rp 45.000/pcs</li>
                                </ul>
                                <p>Apakah Anda ingin informasi lebih detail atau langsung membuat pesanan?</p>
                            </div>
                            <span class="message-time">10:33</span>
                        </div>
                    </div>
                </div>

                <!-- Typing Indicator (Hidden by default) -->
                <div class="message-group typing-indicator" id="typing-indicator" style="display: none;">
                    <div class="message message-bot">
                        <div class="message-avatar">
                            <span class="material-icons">support_agent</span>
                        </div>
                        <div class="message-content">
                            <div class="message-bubble typing-bubble">
                                <div class="typing-dots">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chat Input -->
            <div class="chat-input-container">
                <div class="chat-input-wrapper">
                    <button class="input-action" title="Lampiran">
                        <span class="material-icons">attach_file</span>
                    </button>
                    <textarea 
                        id="message-input" 
                        class="chat-input" 
                        placeholder="Ketik pesan Anda di sini..."
                        rows="1"
                    ></textarea>
                    <button class="input-action" title="Emoji">
                        <span class="material-icons">emoji_emotions</span>
                    </button>
                    <button class="send-button" id="send-button">
                        <span class="material-icons">send</span>
                    </button>
                </div>
                <div class="input-footer">
                    <span class="input-hint">
                        <span class="material-icons">info</span>
                        Tekan Enter untuk kirim, Shift+Enter untuk baris baru
                    </span>
                </div>
            </div>
        </main>
    </div>

</body>
</html>
