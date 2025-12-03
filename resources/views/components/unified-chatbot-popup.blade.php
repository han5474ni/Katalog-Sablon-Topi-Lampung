{{-- Unified Chatbot Popup Component --}}
{{-- Gunakan di semua halaman customer: home, catalog, all-products --}}

<!-- Chatbot Trigger Button -->
<button class="unified-chatbot-trigger" id="unifiedChatbotTrigger" aria-label="Buka chat">
    <i class="fas fa-comment" aria-hidden="true"></i>
</button>

<!-- Chatbot Popup -->
<div class="unified-chatbot-popup" id="unifiedChatbotPopup">
    <!-- Chatbot Header -->
    <div class="unified-chatbot-header">
        <div class="unified-chatbot-avatar">
            <span class="material-icons">support_agent</span>
        </div>
        <div class="unified-chatbot-info">
            <div class="unified-chatbot-name">LGI STORE</div>
            <div class="unified-chatbot-status">Online - Balas Cepat</div>
        </div>
        <button class="unified-chatbot-close" id="unifiedChatbotClose" aria-label="Tutup chat">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="unified-chatbot-body">
        <!-- Chatbot Messages -->
        <div class="unified-chatbot-messages" id="unifiedChatbotMessages">
            <div class="unified-message bot-message">
                <div class="unified-message-avatar">
                    <span class="material-icons">support_agent</span>
                </div>
                <div class="unified-message-content">
                    <div class="unified-message-bubble">
                        <p>Halo! Selamat datang di LGI Store! Ada yang bisa saya bantu hari ini?</p>
                    </div>
                    <span class="unified-message-time" id="welcomeTime"></span>
                </div>
            </div>
        </div>

        <!-- Quick Replies -->
        <div class="unified-quick-replies">
            <button type="button" class="unified-quick-reply" data-question="stok">Cek stok</button>
            <button type="button" class="unified-quick-reply" data-question="harga">Estimasi harga</button>
            <button type="button" class="unified-quick-reply" data-question="kirim">Estimasi kirim</button>
            <button type="button" class="unified-quick-reply" data-question="custom">Custom desain</button>
            <button type="button" class="unified-quick-reply" data-question="promo">Promo</button>
        </div>

        <!-- Chatbot Input -->
        <div class="unified-chatbot-input-wrapper">
            <div class="unified-chatbot-input-container">
                <input
                    type="text"
                    class="unified-chatbot-input"
                    id="unifiedChatbotInput"
                    placeholder="Ketik pesan Anda..."
                    maxlength="500"
                >
                <button class="unified-chatbot-send" id="unifiedChatbotSend">Kirim</button>
            </div>
        </div>
    </div>
</div>

<style>
/* Unified Chatbot Popup Styles */
.unified-chatbot-trigger {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 60px;
    height: 60px;
    background-color: #fbbf24;
    color: #1a2332;
    border: none;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    cursor: pointer;
    transition: all 0.3s ease;
    z-index: 9998;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.unified-chatbot-trigger:hover {
    background-color: #e0a800;
    transform: scale(1.1);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
}

.unified-chatbot-trigger.hidden {
    display: none;
}

.unified-chatbot-popup {
    position: fixed;
    bottom: 100px;
    right: 20px;
    width: 370px;
    height: 500px;
    background-color: #ffffff;
    border-radius: 16px;
    display: none;
    flex-direction: column;
    overflow: hidden;
    z-index: 9999;
    animation: unifiedSlideUp 0.3s ease;
    box-shadow: 0 12px 48px rgba(0, 0, 0, 0.25), 0 4px 16px rgba(0, 0, 0, 0.15);
}

.unified-chatbot-popup.active {
    display: flex;
}

@keyframes unifiedSlideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.unified-chatbot-header {
    background-color: #f3f4f6;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    border-bottom: 1px solid #e5e7eb;
}

.unified-chatbot-avatar {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    flex-shrink: 0;
}

.unified-chatbot-avatar .material-icons {
    font-size: 28px;
}

.unified-chatbot-info {
    flex: 1;
}

.unified-chatbot-name {
    font-size: 16px;
    font-weight: 600;
    color: #0f1e3d;
    margin-bottom: 2px;
}

.unified-chatbot-status {
    font-size: 13px;
    color: #666;
}

.unified-chatbot-close {
    background: transparent;
    border: none;
    color: #666;
    cursor: pointer;
    padding: 8px;
    border-radius: 50%;
    transition: background 0.2s;
}

.unified-chatbot-close:hover {
    background: #e5e7eb;
}

.unified-chatbot-body {
    display: flex;
    flex-direction: column;
    flex: 1;
    overflow: hidden;
}

.unified-chatbot-messages {
    flex: 1;
    padding: 16px;
    overflow-y: auto;
    background-color: #f9fafb;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.unified-chatbot-messages::-webkit-scrollbar {
    width: 6px;
}

.unified-chatbot-messages::-webkit-scrollbar-track {
    background: transparent;
}

.unified-chatbot-messages::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 3px;
}

.unified-message {
    display: flex;
    gap: 10px;
    max-width: 85%;
    animation: unifiedFadeIn 0.3s ease;
}

@keyframes unifiedFadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.unified-message.bot-message {
    align-self: flex-start;
}

.unified-message.user-message {
    align-self: flex-end;
    flex-direction: row-reverse;
}

.unified-message-avatar {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    flex-shrink: 0;
}

.unified-message-avatar .material-icons {
    font-size: 20px;
}

.unified-message.user-message .unified-message-avatar {
    background: linear-gradient(135deg, #0f1e3d 0%, #1a3a6e 100%);
}

.unified-message-content {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.unified-message-bubble {
    padding: 12px 16px;
    border-radius: 16px;
    font-size: 14px;
    line-height: 1.5;
}

.unified-message.bot-message .unified-message-bubble {
    background: #ffffff;
    color: #333;
    border: 1px solid #e5e7eb;
    border-bottom-left-radius: 4px;
}

.unified-message.user-message .unified-message-bubble {
    background: linear-gradient(135deg, #0f1e3d 0%, #1a3a6e 100%);
    color: white;
    border-bottom-right-radius: 4px;
}

.unified-message-bubble p {
    margin: 0;
}

.unified-message-time {
    font-size: 11px;
    color: #9ca3af;
    padding: 0 4px;
}

.unified-message.user-message .unified-message-time {
    text-align: right;
}

.unified-quick-replies {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    padding: 12px 16px;
    border-top: 1px solid #e5e7eb;
    background: #fff;
}

.unified-quick-reply {
    padding: 8px 14px;
    border-radius: 20px;
    border: 1px solid #e5e7eb;
    background: #fff;
    font-size: 13px;
    color: #374151;
    cursor: pointer;
    transition: all 0.2s;
}

.unified-quick-reply:hover {
    background: #0f1e3d;
    color: #fff;
    border-color: #0f1e3d;
}

.unified-chatbot-input-wrapper {
    padding: 12px 16px;
    border-top: 1px solid #e5e7eb;
    background: #fff;
}

.unified-chatbot-input-container {
    display: flex;
    gap: 8px;
}

.unified-chatbot-input {
    flex: 1;
    padding: 12px 16px;
    border: 1px solid #e5e7eb;
    border-radius: 24px;
    font-size: 14px;
    outline: none;
    transition: border-color 0.2s;
}

.unified-chatbot-input:focus {
    border-color: #0f1e3d;
}

.unified-chatbot-send {
    background: linear-gradient(135deg, #0f1e3d 0%, #1a3a6e 100%);
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 24px;
    cursor: pointer;
    font-weight: 500;
    transition: transform 0.2s, box-shadow 0.2s;
}

.unified-chatbot-send:hover {
    transform: scale(1.02);
    box-shadow: 0 4px 12px rgba(15, 30, 61, 0.3);
}

/* Typing indicator */
.unified-typing-indicator {
    display: flex;
    gap: 4px;
    padding: 12px 16px;
}

.unified-typing-indicator span {
    width: 8px;
    height: 8px;
    background: #9ca3af;
    border-radius: 50%;
    animation: unifiedTypingBounce 1.4s infinite ease-in-out;
}

.unified-typing-indicator span:nth-child(1) { animation-delay: 0s; }
.unified-typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
.unified-typing-indicator span:nth-child(3) { animation-delay: 0.4s; }

@keyframes unifiedTypingBounce {
    0%, 60%, 100% { transform: translateY(0); opacity: 0.4; }
    30% { transform: translateY(-8px); opacity: 1; }
}

/* Responsive */
@media (max-width: 480px) {
    .unified-chatbot-popup {
        width: calc(100% - 20px);
        right: 10px;
        bottom: 80px;
        height: 70vh;
    }
    
    .unified-quick-reply {
        font-size: 12px;
        padding: 6px 12px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const trigger = document.getElementById('unifiedChatbotTrigger');
    const popup = document.getElementById('unifiedChatbotPopup');
    const closeBtn = document.getElementById('unifiedChatbotClose');
    const messages = document.getElementById('unifiedChatbotMessages');
    const input = document.getElementById('unifiedChatbotInput');
    const sendBtn = document.getElementById('unifiedChatbotSend');
    const quickReplies = document.querySelectorAll('.unified-quick-reply');
    const welcomeTime = document.getElementById('welcomeTime');
    
    // Set welcome time
    if (welcomeTime) {
        welcomeTime.textContent = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    }
    
    // Conversation ID for database integration
    let conversationId = null;
    
    // Toggle popup
    if (trigger) {
        trigger.addEventListener('click', function() {
            popup.classList.add('active');
            trigger.classList.add('hidden');
            input.focus();
            loadChatHistory();
        });
    }
    
    // Close popup
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            popup.classList.remove('active');
            trigger.classList.remove('hidden');
        });
    }
    
    // Click outside to close
    document.addEventListener('click', function(e) {
        if (popup && trigger && !popup.contains(e.target) && !trigger.contains(e.target) && popup.classList.contains('active')) {
            popup.classList.remove('active');
            trigger.classList.remove('hidden');
        }
    });
    
    // Send message on button click
    if (sendBtn) {
        sendBtn.addEventListener('click', sendMessage);
    }
    
    // Send message on Enter
    if (input) {
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
    }
    
    // Quick replies
    quickReplies.forEach(btn => {
        btn.addEventListener('click', function() {
            const questionType = this.dataset.question;
            handleQuickReply(questionType);
        });
    });
    
    // Load chat history from database
    async function loadChatHistory() {
        try {
            const response = await fetch('/api/chatbot/history', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                if (data.success && data.messages) {
                    conversationId = data.conversation_id;
                    // Clear existing messages except welcome
                    const welcomeMsg = messages.querySelector('.bot-message');
                    messages.innerHTML = '';
                    
                    if (data.messages.length === 0 && welcomeMsg) {
                        messages.appendChild(welcomeMsg);
                    } else {
                        data.messages.forEach(msg => {
                            addMessageToUI(msg.message, msg.sender_type === 'user' ? 'user' : 'bot', msg.created_at);
                        });
                    }
                    scrollToBottom();
                }
            }
        } catch (error) {
            console.log('Chat history not available:', error);
        }
    }
    
    // Send message
    async function sendMessage() {
        const text = input.value.trim();
        if (!text) return;
        
        // Add user message to UI
        addMessageToUI(text, 'user');
        input.value = '';
        
        // Show typing indicator
        showTyping();
        
        // Save to database and get response
        try {
            const response = await fetch('/api/chatbot/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                },
                body: JSON.stringify({
                    message: text,
                    conversation_id: conversationId
                })
            });
            
            hideTyping();
            
            if (response.ok) {
                const data = await response.json();
                if (data.success) {
                    conversationId = data.conversation_id;
                    addMessageToUI(data.bot_response, 'bot');
                }
            } else {
                // Fallback to local response
                const botResponse = generateLocalResponse(text);
                addMessageToUI(botResponse, 'bot');
            }
        } catch (error) {
            hideTyping();
            // Fallback to local response
            const botResponse = generateLocalResponse(text);
            addMessageToUI(botResponse, 'bot');
        }
        
        scrollToBottom();
    }
    
    // Handle quick reply
    function handleQuickReply(type) {
        const questions = {
            'stok': 'Apakah stok produk ini tersedia?',
            'harga': 'Berapa estimasi harga untuk produk ini?',
            'kirim': 'Berapa lama estimasi pengiriman?',
            'custom': 'Apakah bisa custom desain?',
            'promo': 'Ada diskon atau promo saat ini?'
        };
        
        const text = questions[type] || 'Halo';
        input.value = text;
        sendMessage();
    }
    
    // Add message to UI
    function addMessageToUI(text, sender, timestamp = null) {
        const time = timestamp ? new Date(timestamp).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) 
                              : new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `unified-message ${sender === 'user' ? 'user-message' : 'bot-message'}`;
        
        const avatarIcon = sender === 'user' ? 'account_circle' : 'support_agent';
        
        messageDiv.innerHTML = `
            <div class="unified-message-avatar">
                <span class="material-icons">${avatarIcon}</span>
            </div>
            <div class="unified-message-content">
                <div class="unified-message-bubble">
                    <p>${text}</p>
                </div>
                <span class="unified-message-time">${time}</span>
            </div>
        `;
        
        messages.appendChild(messageDiv);
        scrollToBottom();
    }
    
    // Show typing indicator
    function showTyping() {
        const typingDiv = document.createElement('div');
        typingDiv.className = 'unified-message bot-message';
        typingDiv.id = 'unifiedTypingIndicator';
        typingDiv.innerHTML = `
            <div class="unified-message-avatar">
                <span class="material-icons">support_agent</span>
            </div>
            <div class="unified-message-content">
                <div class="unified-message-bubble">
                    <div class="unified-typing-indicator">
                        <span></span><span></span><span></span>
                    </div>
                </div>
            </div>
        `;
        messages.appendChild(typingDiv);
        scrollToBottom();
    }
    
    // Hide typing indicator
    function hideTyping() {
        const typing = document.getElementById('unifiedTypingIndicator');
        if (typing) typing.remove();
    }
    
    // Generate local response (fallback)
    function generateLocalResponse(userMessage) {
        const msg = userMessage.toLowerCase();
        
        if (msg.includes('harga') || msg.includes('berapa')) {
            return 'Untuk informasi harga lengkap, silakan kunjungi halaman katalog atau detail produk. Harga bervariasi tergantung produk dan ukuran yang dipilih.';
        } else if (msg.includes('stok') || msg.includes('tersedia')) {
            return 'Untuk cek ketersediaan stok, silakan lihat detail produk atau hubungi admin kami. Kebanyakan produk kami ready stock!';
        } else if (msg.includes('kirim') || msg.includes('pengiriman')) {
            return 'Kami melayani pengiriman ke seluruh Indonesia. Estimasi 2-4 hari untuk Jawa dan 3-7 hari untuk luar Jawa.';
        } else if (msg.includes('custom') || msg.includes('desain')) {
            return 'Ya, kami menerima custom design! Produk dengan label CUSTOM bisa didesain sesuai keinginan Anda. Silakan upload desain saat checkout.';
        } else if (msg.includes('promo') || msg.includes('diskon')) {
            return 'Untuk promo terbaru, silakan cek halaman utama atau katalog kami. Kami sering mengadakan diskon menarik!';
        } else if (msg.includes('terima kasih') || msg.includes('thanks')) {
            return 'Sama-sama! Senang bisa membantu. Jika ada pertanyaan lain, jangan ragu untuk bertanya ya! 😊';
        } else if (msg.includes('halo') || msg.includes('hai') || msg.includes('hello')) {
            return 'Halo! Ada yang bisa saya bantu hari ini?';
        }
        
        return 'Terima kasih atas pertanyaan Anda! Untuk informasi lebih detail, silakan hubungi admin kami atau kunjungi halaman chat untuk berbicara dengan tim support.';
    }
    
    // Scroll to bottom
    function scrollToBottom() {
        if (messages) {
            messages.scrollTop = messages.scrollHeight;
        }
    }
});
</script>
