// Product ChatBot Functionality - Fixed Version
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing Product ChatBot...');
    
    // Initialize chatbot
    new ProductChatBot();
});

class ProductChatBot {
    constructor() {
        this.modal = document.getElementById('chatbotModal');
        this.chatMessages = document.getElementById('chatMessages');
        this.chatForm = document.getElementById('chatForm');
        this.conversationId = document.getElementById('conversationId');
        this.productName = document.getElementById('chatProductName');
        
        console.log('Product ChatBot constructor called');
        console.log('Modal element:', this.modal);
        console.log('Chat form:', this.chatForm);
        
        this.init();
    }

    init() {
        console.log('Initializing Product ChatBot...');
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
        
        console.log('Chat modal opened successfully');
    }

    closeChat() {
        console.log('Closing chat modal...');
        this.modal.classList.remove('show');
        document.body.style.overflow = ''; // Restore scroll
    }

    addMessage(message, isUser = false) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `${isUser ? 'user' : 'bot'}-message`;
        
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', { 
            hour: '2-digit', 
            minute: '2-digit' 
        });

        messageDiv.innerHTML = `
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
            // ENHANCED: Send only product ID, let backend query fresh stock data
            const payload = {
                message: message,
                conversation_id: productData.id,
                user_id: 1,
                product: {
                    id: productData.id,
                    name: productData.name,
                    price: productData.price,
                    price_min: productData.price_min,
                    price_max: productData.price_max
                    // ← Jangan kirim colors, sizes, stock - biarkan backend query fresh data!
                }
            };

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
                const botMessage = data.bot_response.message || 'Maaf, ada kesalahan saat memproses respons.';
                this.addMessage(botMessage);
                
                // Log if fresh stock data was queried
                if (data.metadata?.stock_query_executed) {
                    console.log('✓ Fresh stock data was queried and included in response');
                    console.log('Stock metadata:', data.metadata.fresh_stock_data);
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