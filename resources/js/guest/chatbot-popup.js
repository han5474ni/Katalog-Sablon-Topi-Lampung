/**
 * Chatbot Popup - JavaScript
 * Handles chat functionality and interactions for homepage popup
 */

// DOM Elements
let chatbotTrigger = document.getElementById('chatbotTrigger');
const chatbotPopup = document.getElementById('chatbotPopup');
const chatbotMessages = document.getElementById('chatbotMessages');
const chatbotInput = document.getElementById('chatbotInput');
const chatbotSend = document.getElementById('chatbotSend');

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    initializeChatbot();
});

// Initialize Chatbot
function initializeChatbot() {
    if (!chatbotTrigger || !chatbotPopup) return;

    // Toggle popup on trigger click
    // Support footer floating button on all pages
    if (!chatbotTrigger) {
        chatbotTrigger = document.querySelector('.chat-btn');
    }
    if (chatbotTrigger) {
        chatbotTrigger.addEventListener('click', toggleChatbot);
    }

    // Send message on button click
    chatbotSend.addEventListener('click', handleSendMessage);

    // Send message on Enter key
    chatbotInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            handleSendMessage();
        }
    });

    // Close popup when clicking outside
    document.addEventListener('click', (e) => {
        if (!chatbotPopup.contains(e.target) && !chatbotTrigger.contains(e.target)) {
            closeChatbot();
        }
    });
}

// Toggle Chatbot Popup
function toggleChatbot() {
    if (chatbotPopup.classList.contains('active')) {
        closeChatbot();
    } else {
        openChatbot();
    }
}

// Open Chatbot
function openChatbot() {
    chatbotPopup.classList.add('active');
    if (chatbotTrigger) chatbotTrigger.classList.add('hidden');
    chatbotInput && chatbotInput.focus();
}

// Close Chatbot
function closeChatbot() {
    chatbotPopup.classList.remove('active');
    if (chatbotTrigger) chatbotTrigger.classList.remove('hidden');
}

// Handle Send Message
function handleSendMessage() {
    const message = chatbotInput.value.trim();

    if (!message) return;

    // Add user message
    addMessage(message, 'user');

    // Clear input
    chatbotInput.value = '';

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
    const message = document.createElement('div');
    message.className = `message ${sender === 'bot' ? 'bot-message' : 'user-message'}`;

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

    chatbotMessages.appendChild(message);
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

// Show Typing Indicator
function showTypingIndicator() {
    const typingIndicator = document.createElement('div');
    typingIndicator.className = 'message bot-message';
    typingIndicator.id = 'typing-indicator';
    typingIndicator.innerHTML = `
        <div class="message-avatar">
            <span class="material-icons">support_agent</span>
        </div>
        <div class="message-content">
            <div class="message-bubble">
                <div class="typing-dots">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
    `;

    chatbotMessages.appendChild(typingIndicator);
    scrollToBottom();
}

// Hide Typing Indicator
function hideTypingIndicator() {
    const typingIndicator = document.getElementById('typing-indicator');
    if (typingIndicator) {
        typingIndicator.remove();
    }
}

// Scroll to Bottom
function scrollToBottom() {
    setTimeout(() => {
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    }, 100);
}
