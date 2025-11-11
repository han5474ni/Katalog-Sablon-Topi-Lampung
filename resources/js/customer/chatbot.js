/**
 * Chatbot - JavaScript
 * Handles chat functionality and interactions
 */

// DOM Elements
const chatMessages = document.getElementById('chatbotMessages');
const messageInput = document.getElementById('chatbotInput');
const sendButton = document.getElementById('chatbotSend');
const chatbotTrigger = document.getElementById('chatbotTrigger');
const chatbotPopup = document.getElementById('chatbotPopup');

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    initializeEventListeners();
    scrollToBottom();
    autoResizeTextarea();
});

// Initialize Event Listeners
function initializeEventListeners() {
    // Chatbot trigger button
    if (chatbotTrigger) {
        chatbotTrigger.addEventListener('click', toggleChatbot);
    }

    // Send message on button click
    if (sendButton) {
        sendButton.addEventListener('click', handleSendMessage);
    }

    // Send message on Enter key (without Shift)
    if (messageInput) {
        messageInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                handleSendMessage();
            }
        });
    }

    // Quick reply buttons
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('quick-reply-btn')) {
            const reply = e.target.getAttribute('data-reply');
            handleQuickReply(reply);
        }
    });

    // Close chatbot on outside click
    document.addEventListener('click', (e) => {
        if (chatbotPopup && chatbotTrigger && !chatbotPopup.contains(e.target) && !chatbotTrigger.contains(e.target)) {
            chatbotPopup.classList.remove('active');
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && chatbotPopup && chatbotPopup.classList.contains('active')) {
            chatbotPopup.classList.remove('active');
        }
    });
}

// Toggle Chatbot
function toggleChatbot() {
    if (chatbotPopup) {
        chatbotPopup.classList.toggle('active');
    }
}

// Handle Send Message
function handleSendMessage() {
    const message = messageInput?.value?.trim();

    if (!message) return;

    // Add user message
    addMessage(message, 'user');

    // Clear input
    if (messageInput) {
        messageInput.value = '';
    }

    // Simulate bot response
    setTimeout(() => {
        const botResponse = generateBotResponse(message);
        addMessage(botResponse, 'bot');
    }, 1000 + Math.random() * 1000);
}

// Add Message to Chat
function addMessage(text, sender) {
    if (!chatMessages) return;

    const message = document.createElement('div');
    message.className = `message ${sender === 'user' ? 'user-message' : 'bot-message'}`;

    const time = new Date().toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit'
    });

    if (sender === 'bot') {
        message.innerHTML = `
            <div class="message-avatar"></div>
            <div class="message-content">
                <div class="message-bubble">${text}</div>
            </div>
        `;
    } else {
        message.innerHTML = `
            <div class="message-content">
                <div class="message-bubble">${text}</div>
            </div>
            <div class="message-avatar"></div>
        `;
    }

    chatMessages.appendChild(message);
    scrollToBottom();
}

// Generate Bot Response (Mock AI)
function generateBotResponse(userMessage) {
    const lowerMessage = userMessage.toLowerCase();

    // Quick reply responses
    if (lowerMessage.includes('minta ukuran')) {
        return 'Kami menyediakan berbagai ukuran mulai dari S hingga XXL. Untuk ukuran yang lebih akurat, Anda bisa cek tabel ukuran di halaman detail produk. Apakah Anda ingin saya bantu cek ukuran untuk produk tertentu?';
    } else if (lowerMessage.includes('minta budget')) {
        return 'Harga produk kami mulai dari Rp 50.000 hingga Rp 500.000 tergantung jenis dan custom design. Untuk budget spesifik Anda, bisa beritahu saya kisaran harganya dan saya akan rekomendasikan produk yang sesuai!';
    } else if (lowerMessage.includes('rekomendasi lagi')) {
        return 'Berdasarkan produk populer saat ini, saya rekomendasikan jersey klub lokal dan topi snapback custom. Ada kategori produk tertentu yang ingin Anda eksplor lebih lanjut?';
    } else if (lowerMessage.includes('diskon 10%')) {
        return '🎉 Khusus hari ini! Dapatkan diskon 10% untuk semua produk dengan minimal pembelian Rp 200.000. Gunakan kode: WELCOME10 saat checkout. Tertarik dengan produk apa dulu?';

    // Other keyword-based responses
    } else if (lowerMessage.includes('harga') || lowerMessage.includes('berapa')) {
        return 'Untuk informasi harga lengkap, Anda bisa mengunjungi halaman katalog kami atau saya bisa membantu mencari produk spesifik yang Anda cari. Produk apa yang ingin Anda ketahui harganya?';
    } else if (lowerMessage.includes('pesan') || lowerMessage.includes('order')) {
        return 'Untuk melakukan pemesanan, Anda bisa menambahkan produk ke keranjang dan lanjut ke checkout. Apakah Anda memerlukan bantuan dalam proses pemesanan?';
    } else if (lowerMessage.includes('pengiriman') || lowerMessage.includes('kirim')) {
        return 'Kami melayani pengiriman ke seluruh Indonesia melalui berbagai ekspedisi. Estimasi pengiriman 2-4 hari untuk Jawa dan 3-7 hari untuk luar Jawa. Apakah ada yang ingin Anda tanyakan tentang pengiriman?';
    } else if (lowerMessage.includes('terima kasih') || lowerMessage.includes('thanks')) {
        return 'Sama-sama! Senang bisa membantu Anda. Jika ada pertanyaan lain, jangan ragu untuk bertanya ya! 😊';
    } else if (lowerMessage.includes('halo') || lowerMessage.includes('hai') || lowerMessage.includes('hello')) {
        return 'Halo! Ada yang bisa saya bantu hari ini?';
    } else {
        return 'Terima kasih atas pertanyaan Anda! Saya akan membantu Anda sebaik mungkin. Untuk informasi lebih detail, Anda juga bisa menghubungi customer service kami. Ada yang bisa saya bantu lagi?';
    }
}

// Handle Quick Reply
function handleQuickReply(reply) {
    // Add user message
    addMessage(reply, 'user');

    // Simulate bot response based on quick reply
    setTimeout(() => {
        const botResponse = generateBotResponse(reply);
        addMessage(botResponse, 'bot');
    }, 1000 + Math.random() * 1000);
}

// Quick Action Handler
function handleQuickAction(action) {
    let message = '';

    if (action.includes('Keranjang')) {
        message = 'Saya ingin cek keranjang belanja saya';
    } else if (action.includes('Status Pesanan')) {
        message = 'Bagaimana cara cek status pesanan saya?';
    } else if (action.includes('Info Produk')) {
        message = 'Saya ingin tahu informasi tentang produk';
    } else if (action.includes('Bantuan')) {
        message = 'Saya butuh bantuan';
    }

    if (message) {
        messageInput.value = message;
        messageInput.focus();
    }
}

// Scroll to Bottom
function scrollToBottom() {
    if (chatMessages) {
        setTimeout(() => {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }, 100);
    }
}
