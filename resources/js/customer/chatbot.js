/**
 * Chatbot Page - JavaScript
 * Handles chat functionality and interactions
 */

// DOM Elements
const chatMessages = document.getElementById('chat-messages');
const messageInput = document.getElementById('message-input');
const sendButton = document.getElementById('send-button');
const typingIndicator = document.getElementById('typing-indicator');
const menuToggle = document.getElementById('menu-toggle');
const sidebar = document.querySelector('.chat-sidebar');

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    initializeEventListeners();
    scrollToBottom();
    autoResizeTextarea();
});

// Initialize Event Listeners
function initializeEventListeners() {
    // Send message on button click
    sendButton.addEventListener('click', handleSendMessage);

    // Send message on Enter key (without Shift)
    messageInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            handleSendMessage();
        }
    });

    // Auto-resize textarea
    messageInput.addEventListener('input', autoResizeTextarea);

    // Mobile menu toggle
    menuToggle?.addEventListener('click', () => {
        sidebar.classList.toggle('active');
    });

    // Quick action buttons
    document.querySelectorAll('.quick-action-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const text = e.currentTarget.textContent.trim();
            handleQuickAction(text);
        });
    });

    // History items
    document.querySelectorAll('.history-item').forEach(item => {
        item.addEventListener('click', (e) => {
            document.querySelectorAll('.history-item').forEach(i => {
                i.classList.remove('history-item-active');
            });
            e.currentTarget.classList.add('history-item-active');
        });
    });

    // New chat button
    document.querySelector('.new-chat-btn')?.addEventListener('click', () => {
        if (confirm('Mulai percakapan baru? Chat saat ini akan disimpan ke riwayat.')) {
            startNewChat();
        }
    });
}

// Handle Send Message
function handleSendMessage() {
    const message = messageInput.value.trim();
    
    if (!message) return;

    // Add user message
    addMessage(message, 'user');

    // Clear input
    messageInput.value = '';
    autoResizeTextarea();

    // Show typing indicator
    showTypingIndicator();

    // Simulate bot response
    setTimeout(() => {
        hideTypingIndicator();
        const botResponse = generateBotResponse(message);
        addMessage(botResponse, 'bot');
    }, 1500 + Math.random() * 1000);
}

// Add Message to Chat
function addMessage(text, sender) {
    const messageGroup = document.createElement('div');
    messageGroup.className = 'message-group';

    const message = document.createElement('div');
    message.className = `message message-${sender}`;

    const time = new Date().toLocaleTimeString('id-ID', { 
        hour: '2-digit', 
        minute: '2-digit' 
    });

    if (sender === 'bot') {
        message.innerHTML = `
            <div class="message-avatar">
                <span class="material-icons">support_agent</span>
            </div>
            <div class="message-content">
                <div class="message-bubble">
                    <p>${text}</p>
                </div>
                <span class="message-time">${time}</span>
            </div>
        `;
    } else {
        message.innerHTML = `
            <div class="message-content">
                <div class="message-bubble">
                    <p>${text}</p>
                </div>
                <span class="message-time">${time}</span>
            </div>
            <div class="message-avatar">
                <span class="material-icons">account_circle</span>
            </div>
        `;
    }

    messageGroup.appendChild(message);
    
    // Insert before typing indicator
    const typingIndicator = document.getElementById('typing-indicator');
    chatMessages.insertBefore(messageGroup, typingIndicator);

    scrollToBottom();
}

// Generate Bot Response (Mock AI)
function generateBotResponse(userMessage) {
    const lowerMessage = userMessage.toLowerCase();

    // Simple keyword-based responses
    if (lowerMessage.includes('harga') || lowerMessage.includes('berapa')) {
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

// Show Typing Indicator
function showTypingIndicator() {
    if (typingIndicator) {
        typingIndicator.style.display = 'block';
        scrollToBottom();
    }
}

// Hide Typing Indicator
function hideTypingIndicator() {
    if (typingIndicator) {
        typingIndicator.style.display = 'none';
    }
}

// Auto Resize Textarea
function autoResizeTextarea() {
    messageInput.style.height = 'auto';
    messageInput.style.height = messageInput.scrollHeight + 'px';
}

// Scroll to Bottom
function scrollToBottom() {
    setTimeout(() => {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }, 100);
}

// Start New Chat
function startNewChat() {
    // Clear messages (keep welcome message)
    const messageGroups = chatMessages.querySelectorAll('.message-group');
    messageGroups.forEach((group, index) => {
        if (index > 0) { // Keep first welcome message
            group.remove();
        }
    });

    // Clear input
    messageInput.value = '';
    autoResizeTextarea();

    // Add to history
    const historyList = document.querySelector('.history-list');
    const now = new Date();
    const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    
    const newHistoryItem = document.createElement('div');
    newHistoryItem.className = 'history-item';
    newHistoryItem.innerHTML = `
        <span class="material-icons">chat_bubble_outline</span>
        <div class="history-info">
            <div class="history-title">Percakapan ${timeStr}</div>
            <div class="history-time">Baru saja</div>
        </div>
    `;
    
    // Remove active from all
    document.querySelectorAll('.history-item').forEach(item => {
        item.classList.remove('history-item-active');
    });
    
    // Add new item
    historyList.insertBefore(newHistoryItem, historyList.firstChild);
    
    // Focus input
    messageInput.focus();
}

// Close sidebar on outside click (mobile)
document.addEventListener('click', (e) => {
    if (window.innerWidth <= 1024) {
        if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
            sidebar.classList.remove('active');
        }
    }
});