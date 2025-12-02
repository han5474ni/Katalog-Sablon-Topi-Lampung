// Product ChatBot Functionality - Fixed Version with Database Integration & Admin Takeover Support
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing Product ChatBot...');
    
    // Initialize chatbot
    window.chatBotInstance = new ProductChatBot();
});

class ProductChatBot {
    constructor() {
        this.modal = document.getElementById('chatbotModal');
        this.chatMessages = document.getElementById('chatMessages');
        this.chatForm = document.getElementById('chatForm');
        this.conversationIdInput = document.getElementById('conversationId');
        this.productName = document.getElementById('chatProductName');
        
        // Track actual conversation ID from database for session continuity
        this.actualConversationId = null;
        
        // Track last message ID for polling
        this.lastMessageId = 0;
        
        // Polling interval for admin messages
        this.pollingInterval = null;
        this.isAdminActive = false;
        
        console.log('Product ChatBot constructor called');
        console.log('Modal element:', this.modal);
        console.log('Chat form:', this.chatForm);
        
        this.init();
    }

    init() {
        console.log('Initializing Product ChatBot with database integration...');
        this.bindEvents();
    }

    bindEvents() {
        console.log('Binding events...');
        
        // Cari button chat dengan selector yang lebih spesifik
        const chatButton = document.querySelector('.chat-btn');
        console.log('Chat button found:', chatButton);
        
        if (chatButton) {
            // Hapus event listener lama jika ada
            chatButton.replaceWith(chatButton.cloneNode(true));
            
            // Tambahkan event listener baru
            const newChatButton = document.querySelector('.chat-btn');
            newChatButton.addEventListener('click', (e) => {
                console.log('Chat button clicked!');
                e.preventDefault();
                e.stopPropagation();
                this.openChat();
            });
            
            console.log('Chat button event bound successfully');
        } else {
            console.error('Chat button not found!');
        }

        // Modal close events
        document.querySelectorAll('[data-close-modal]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.closeChat();
            });
        });

        // Template questions
        document.querySelectorAll('.template-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                const questionType = e.target.dataset.question;
                this.sendTemplateQuestion(questionType);
            });
        });

        // Chat form submission
        if (this.chatForm) {
            this.chatForm.addEventListener('submit', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.sendMessage();
            });
        }

        // Close modal on ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.modal.classList.contains('show')) {
                this.closeChat();
            }
        });

        // Prevent modal close when clicking inside modal content
        document.querySelector('.chatbot-modal-content')?.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    }

    openChat() {
        console.log('Opening chat modal...');
        this.modal.classList.add('show');
        document.body.style.overflow = 'hidden'; // Prevent background scroll
        this.scrollToBottom();
        
        // Set product name in header
        this.productName.textContent = productData.name;
        
        // Start polling for admin messages
        this.startPolling();
        
        console.log('Chat modal opened successfully');
    }

    closeChat() {
        console.log('Closing chat modal...');
        this.modal.classList.remove('show');
        document.body.style.overflow = ''; // Restore scroll
        
        // Stop polling
        this.stopPolling();
    }

    // Start polling for new messages from admin
    startPolling() {
        if (this.pollingInterval) {
            clearInterval(this.pollingInterval);
        }
        
        // Poll every 3 seconds
        this.pollingInterval = setInterval(() => {
            this.pollNewMessages();
        }, 3000);
        
        console.log('Started polling for admin messages');
    }

    stopPolling() {
        if (this.pollingInterval) {
            clearInterval(this.pollingInterval);
            this.pollingInterval = null;
        }
        console.log('Stopped polling');
    }

    async pollNewMessages() {
        if (!this.actualConversationId) return;
        
        try {
            const response = await fetch(`/chat/new-messages?conversation_id=${this.actualConversationId}&last_message_id=${this.lastMessageId}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            const data = await response.json();
            
            if (data.success && data.messages && data.messages.length > 0) {
                data.messages.forEach(msg => {
                    // Only add messages from admin that we haven't shown yet
                    if (msg.is_from_admin || msg.sender_type === 'admin') {
                        this.addMessage(msg.message, false, true); // isAdmin = true
                    }
                    // Update lastMessageId
                    if (msg.id > this.lastMessageId) {
                        this.lastMessageId = msg.id;
                    }
                });
            }
            
            // Update admin status
            if (data.taken_over_by_admin !== this.isAdminActive) {
                this.isAdminActive = data.taken_over_by_admin;
                this.updateAdminStatus(data.taken_over_by_admin, data.admin_name);
            }
            
        } catch (error) {
            console.log('Polling error (non-critical):', error.message);
        }
    }

    updateAdminStatus(isActive, adminName) {
        const headerTitle = document.querySelector('.chatbot-title h3');
        if (headerTitle) {
            if (isActive) {
                headerTitle.innerHTML = `<i class="fas fa-headset"></i> Live Chat dengan Admin`;
                // Show notification
                this.addSystemMessage(`🎯 Admin ${adminName || ''} sedang menangani chat Anda`);
            } else {
                headerTitle.innerHTML = `<i class="fas fa-robot"></i> Chat Support`;
            }
        }
    }

    addSystemMessage(message) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'system-message';
        messageDiv.innerHTML = `
            <div class="message-content system">${message}</div>
        `;
        this.chatMessages.appendChild(messageDiv);
        this.scrollToBottom();
    }

    addMessage(message, isUser = false, isFromAdmin = false) {
        const messageDiv = document.createElement('div');
        
        if (isUser) {
            messageDiv.className = 'user-message';
        } else if (isFromAdmin) {
            messageDiv.className = 'bot-message admin-message';
        } else {
            messageDiv.className = 'bot-message';
        }
        
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', { 
            hour: '2-digit', 
            minute: '2-digit' 
        });

        const senderLabel = isFromAdmin ? '<small class="admin-badge">👨‍💼 Admin</small>' : '';

        messageDiv.innerHTML = `
            ${senderLabel}
            <div class="message-content">${message}</div>
            <small class="message-time">${timeString}</small>
        `;

        this.chatMessages.appendChild(messageDiv);
        this.scrollToBottom();
    }

    scrollToBottom() {
        this.chatMessages.scrollTop = this.chatMessages.scrollHeight;
    }

    sendTemplateQuestion(questionType) {
        console.log('Sending template question:', questionType);
        
        const questions = {
            harga: `Berapa harga ${productData.name}?`,
            stok: `Apakah ${productData.name} ready stock?`,
            warna: `Warna apa saja yang tersedia untuk ${productData.name}?`,
            ukuran: `Ukuran apa yang tersedia untuk ${productData.name}?`,
            custom: `Bisakah ${productData.name} dibuat custom design?`,
            bahan: `Bahan apa yang digunakan untuk ${productData.name}?`,
            pengiriman: `Berapa lama pengiriman ${productData.name}?`
        };

        const message = questions[questionType] || questions.harga;
        document.querySelector('.chat-input').value = message;
        this.sendMessage();
    }

    async sendMessage() {
        const input = this.chatForm.querySelector('input[name="message"]');
        const message = input.value.trim();

        if (!message) return;

        console.log('Sending message:', message);

        // Add user message immediately
        this.addMessage(message, true);
        input.value = '';

        // Disable send button while processing
        const sendButton = this.chatForm.querySelector('.chat-send-btn');
        sendButton.disabled = true;

        try {
            // Build payload with product data for backend processing
            const payload = {
                message: message,
                product: {
                    id: productData.id,
                    name: productData.name,
                    price: productData.price,
                    price_min: productData.price_min,
                    price_max: productData.price_max
                }
            };
            
            // Include existing conversation_id if we have one from previous messages
            if (this.actualConversationId) {
                payload.conversation_id = this.actualConversationId;
            }

            console.log('Sending payload to backend:', payload);

            const response = await fetch('/test-chat-send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(payload)
            });

            const data = await response.json();
            console.log('Received response:', data);

            if (data.success) {
                // Store the conversation_id for future messages in this session
                if (data.conversation_id) {
                    this.actualConversationId = data.conversation_id;
                    console.log('✓ Conversation ID stored:', this.actualConversationId);
                }
                
                // Check if admin has taken over
                if (data.admin_active) {
                    this.isAdminActive = true;
                    this.updateAdminStatus(true, data.admin_name);
                    // Show waiting message
                    this.addMessage(data.bot_response.message, false, true);
                } else {
                    const botMessage = data.bot_response.message || 'Maaf, ada kesalahan saat memproses respons.';
                    this.addMessage(botMessage);
                }
                
                // Log if saved to database (visible to admin)
                if (data.metadata?.saved_to_database) {
                    console.log('✓ Chat saved to database - Admin can see this conversation');
                } else {
                    console.log('⚠ Chat not saved to database - User may not be logged in');
                }
                
                // Log if fresh stock data was queried
                if (data.metadata?.stock_query_executed) {
                    console.log('✓ Fresh stock data was queried');
                }
            } else {
                this.addMessage('Maaf, sedang ada gangguan. Silakan coba lagi nanti.');
                console.error('Response error:', data.error);
            }

        } catch (error) {
            console.error('ChatBot Error:', error);
            this.addMessage('Error koneksi. Silakan refresh halaman.');
        } finally {
            // Re-enable send button
            sendButton.disabled = false;
        }
    }
}

// Global function untuk testing dari console
window.openChatBot = function() {
    if (window.chatBotInstance) {
        window.chatBotInstance.openChat();
    } else {
        console.error('ChatBot not initialized');
    }
};

console.log('Product ChatBot script loaded successfully');