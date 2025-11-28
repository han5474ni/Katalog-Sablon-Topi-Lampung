/**
 * Customer Chat Interface with Admin Support
 * Features:
 * - Auto chat with bot
 * - Request admin response trigger
 * - Real-time admin takeover detection
 * - Message read status
 */

class CustomerChatManager {
    constructor(conversationId) {
        this.conversationId = conversationId;
        this.isAdminHandling = false;
        this.pollInterval = null;
        this.init();
    }

    init() {
        this.bindEvents();
        this.pollForUpdates();
    }

    bindEvents() {
        // Form submission
        const chatForm = document.getElementById('chatForm');
        if (chatForm) {
            chatForm.addEventListener('submit', (e) => this.handleFormSubmit(e));
        }

        // Request admin button
        const requestAdminBtn = document.getElementById('requestAdminBtn');
        if (requestAdminBtn) {
            requestAdminBtn.addEventListener('click', () => this.requestAdminResponse());
        }
    }

    async handleFormSubmit(e) {
        e.preventDefault();
        
        const input = document.querySelector('input[name="message"]');
        const message = input.value.trim();

        if (!message) return;

        // Disable send button
        const sendBtn = e.target.querySelector('button[type="submit"]');
        sendBtn.disabled = true;

        try {
            // Add user message to UI immediately
            this.addMessageToUI('user', message);
            input.value = '';

            // Send message
            const response = await fetch('/chat/send-message', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    conversation_id: this.conversationId,
                    message: message
                })
            });

            const data = await response.json();

            if (data.success && data.bot_response) {
                // Check if admin is now handling
                if (data.bot_response.metadata?.admin_takeover) {
                    this.isAdminHandling = true;
                    this.showAdminTakeoverNotification();
                }

                this.addMessageToUI('bot', data.bot_response.message);
            } else {
                this.addMessageToUI('system', 'Gagal mengirim pesan. Silakan coba lagi.');
            }

        } catch (error) {
            console.error('Error sending message:', error);
            this.addMessageToUI('system', 'Terjadi kesalahan. Silakan coba lagi.');
        } finally {
            sendBtn.disabled = false;
        }
    }

    addMessageToUI(sender, message) {
        const chatMessages = document.getElementById('chatMessages');
        if (!chatMessages) return;

        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${sender}-message`;
        
        const content = document.createElement('div');
        content.className = 'message-content';
        content.textContent = message;
        
        const time = document.createElement('small');
        time.className = 'message-time';
        time.textContent = new Date().toLocaleTimeString('id-ID');
        
        messageDiv.appendChild(content);
        messageDiv.appendChild(time);
        chatMessages.appendChild(messageDiv);

        // Scroll to bottom
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    async requestAdminResponse() {
        const reason = prompt('Jelaskan masalah Anda atau alasan meminta bantuan admin:');
        
        if (!reason) return;

        try {
            const response = await fetch(`/chat/conversation/${this.conversationId}/request-admin`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    reason: reason
                })
            });

            const data = await response.json();

            if (data.success) {
                // Show notification
                this.addMessageToUI('system', data.message);
                
                // Update UI to show waiting state
                const requestBtn = document.getElementById('requestAdminBtn');
                if (requestBtn) {
                    requestBtn.disabled = true;
                    requestBtn.textContent = 'Menunggu Admin...';
                }
            } else {
                alert('Gagal mengirim permintaan: ' + data.message);
            }

        } catch (error) {
            console.error('Error requesting admin:', error);
            alert('Gagal mengirim permintaan ke admin');
        }
    }

    async pollForUpdates() {
        this.pollInterval = setInterval(async () => {
            try {
                const response = await fetch(`/chat/conversation/${this.conversationId}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (data.success) {
                    const conversation = data.conversation;

                    // Check if admin has taken over
                    if (conversation.taken_over_by_admin && !this.isAdminHandling) {
                        this.isAdminHandling = true;
                        this.showAdminTakeoverNotification();
                    }

                    // Check if released back to bot
                    if (!conversation.taken_over_by_admin && this.isAdminHandling) {
                        this.isAdminHandling = false;
                        this.showBotResumedNotification();
                    }
                }

            } catch (error) {
                console.error('Error polling for updates:', error);
            }
        }, 5000); // Poll every 5 seconds
    }

    showAdminTakeoverNotification() {
        this.addMessageToUI('system', '👤 Admin sedang membantu Anda');
        
        const requestBtn = document.getElementById('requestAdminBtn');
        if (requestBtn) {
            requestBtn.style.display = 'none';
        }
    }

    showBotResumedNotification() {
        this.addMessageToUI('system', '🤖 Chatbot otomatis telah mengambil alih kembali');
        
        const requestBtn = document.getElementById('requestAdminBtn');
        if (requestBtn) {
            requestBtn.style.display = 'block';
            requestBtn.disabled = false;
            requestBtn.textContent = 'Minta Bantuan Admin';
        }
    }

    destroy() {
        if (this.pollInterval) {
            clearInterval(this.pollInterval);
        }
    }
}

// Initialize when document is ready
document.addEventListener('DOMContentLoaded', () => {
    const conversationId = document.querySelector('input[name="conversation_id"]')?.value;
    if (conversationId) {
        window.customerChatManager = new CustomerChatManager(conversationId);
    }
});

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
    if (window.customerChatManager) {
        window.customerChatManager.destroy();
    }
});
