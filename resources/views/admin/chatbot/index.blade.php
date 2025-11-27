<x-admin-layout title="Chatbot Management">
    @push('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <style>
            .chatbot-container {
                display: flex;
                height: 100vh;
                background: #f8f9fa;
                gap: 20px;
                padding: 20px;
            }

            .conversation-sidebar {
                width: 350px;
                background: white;
                border-radius: 12px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                display: flex;
                flex-direction: column;
                overflow: hidden;
            }

            .sidebar-header {
                padding: 20px;
                border-bottom: 1px solid #e9ecef;
            }

            .sidebar-header h2 {
                margin: 0;
                font-size: 18px;
                font-weight: 600;
            }

            .filter-controls {
                display: flex;
                gap: 8px;
                margin-top: 12px;
                flex-wrap: wrap;
            }

            .filter-btn {
                padding: 6px 12px;
                border: 1px solid #dee2e6;
                background: white;
                border-radius: 20px;
                cursor: pointer;
                font-size: 12px;
                transition: all 0.3s ease;
            }

            .filter-btn.active {
                background: #007bff;
                color: white;
                border-color: #007bff;
            }

            .filter-btn:hover {
                border-color: #007bff;
            }

            .sidebar-search {
                padding: 12px 20px;
                border-bottom: 1px solid #e9ecef;
            }

            .search-input {
                width: 100%;
                padding: 10px 12px;
                border: 1px solid #dee2e6;
                border-radius: 8px;
                font-size: 14px;
            }

            .conversation-list {
                flex: 1;
                overflow-y: auto;
                padding: 0;
            }

            .conversation-item {
                padding: 15px 20px;
                border-bottom: 1px solid #f1f3f5;
                cursor: pointer;
                transition: background 0.2s ease;
                display: flex;
                gap: 12px;
                align-items: flex-start;
            }

            .conversation-item:hover {
                background: #f8f9fa;
            }

            .conversation-item.active {
                background: #e7f3ff;
                border-left: 4px solid #007bff;
            }

            .conversation-avatar {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background: #007bff;
                color: white;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 600;
                flex-shrink: 0;
            }

            .conversation-info {
                flex: 1;
                min-width: 0;
            }

            .conversation-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 6px;
            }

            .conversation-name {
                font-weight: 600;
                font-size: 14px;
                color: #212529;
            }

            .conversation-time {
                font-size: 12px;
                color: #6c757d;
            }

            .conversation-preview {
                font-size: 13px;
                color: #6c757d;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .conversation-badges {
                display: flex;
                gap: 6px;
                margin-top: 6px;
                flex-wrap: wrap;
            }

            .badge-escalated {
                background: #fff3cd;
                color: #856404;
                padding: 3px 8px;
                border-radius: 4px;
                font-size: 11px;
                font-weight: 500;
            }

            .badge-needs-response {
                background: #f8d7da;
                color: #721c24;
                padding: 3px 8px;
                border-radius: 4px;
                font-size: 11px;
                font-weight: 500;
            }

            .badge-admin-handled {
                background: #d4edda;
                color: #155724;
                padding: 3px 8px;
                border-radius: 4px;
                font-size: 11px;
                font-weight: 500;
            }

            .chat-main {
                flex: 1;
                display: flex;
                flex-direction: column;
                background: white;
                border-radius: 12px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                overflow: hidden;
            }

            .chat-header {
                padding: 20px;
                border-bottom: 1px solid #e9ecef;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .chat-header-left {
                display: flex;
                gap: 12px;
                align-items: center;
            }

            .chat-header-info h3 {
                margin: 0;
                font-size: 16px;
                font-weight: 600;
            }

            .chat-header-info p {
                margin: 4px 0 0 0;
                font-size: 12px;
                color: #6c757d;
            }

            .chat-header-actions {
                display: flex;
                gap: 8px;
            }

            .action-btn {
                padding: 8px 16px;
                border: 1px solid #dee2e6;
                background: white;
                border-radius: 6px;
                cursor: pointer;
                font-size: 12px;
                transition: all 0.3s ease;
            }

            .action-btn:hover {
                background: #f8f9fa;
            }

            .action-btn.primary {
                background: #007bff;
                color: white;
                border-color: #007bff;
            }

            .action-btn.primary:hover {
                background: #0056b3;
            }

            .action-btn.danger {
                background: #dc3545;
                color: white;
                border-color: #dc3545;
            }

            .action-btn.danger:hover {
                background: #c82333;
            }

            .chat-messages {
                flex: 1;
                overflow-y: auto;
                padding: 20px;
                display: flex;
                flex-direction: column;
                gap: 12px;
            }

            .message {
                display: flex;
                gap: 8px;
                margin-bottom: 12px;
                animation: slideIn 0.3s ease;
            }

            @keyframes slideIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .message.user {
                flex-direction: row-reverse;
            }

            .message-bubble {
                max-width: 70%;
                padding: 12px 16px;
                border-radius: 12px;
                font-size: 14px;
                line-height: 1.4;
                word-wrap: break-word;
            }

            .message.bot .message-bubble {
                background: #e9ecef;
                color: #212529;
            }

            .message.user .message-bubble {
                background: #007bff;
                color: white;
            }

            .message.admin .message-bubble {
                background: #28a745;
                color: white;
            }

            .message.system .message-bubble {
                background: #f8f9fa;
                color: #6c757d;
                text-align: center;
                max-width: 100%;
                font-size: 12px;
                font-style: italic;
            }

            .message-time {
                font-size: 11px;
                color: #6c757d;
                margin-top: 4px;
            }

            .chat-input-area {
                padding: 20px;
                border-top: 1px solid #e9ecef;
                background: #f8f9fa;
            }

            .input-group {
                display: flex;
                gap: 8px;
            }

            .chat-input {
                flex: 1;
                padding: 10px 14px;
                border: 1px solid #dee2e6;
                border-radius: 8px;
                font-size: 14px;
                resize: none;
                max-height: 100px;
            }

            .chat-input:focus {
                outline: none;
                border-color: #007bff;
                box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
            }

            .send-btn {
                padding: 10px 20px;
                background: #007bff;
                color: white;
                border: none;
                border-radius: 8px;
                cursor: pointer;
                font-weight: 500;
                transition: background 0.3s ease;
            }

            .send-btn:hover {
                background: #0056b3;
            }

            .send-btn:disabled {
                background: #6c757d;
                cursor: not-allowed;
            }

            .empty-state {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                height: 100%;
                color: #6c757d;
            }

            .empty-state-icon {
                font-size: 48px;
                margin-bottom: 12px;
                opacity: 0.5;
            }

            .notification-badge {
                position: absolute;
                top: -8px;
                right: -8px;
                background: #dc3545;
                color: white;
                width: 24px;
                height: 24px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 12px;
                font-weight: 600;
            }

            @media (max-width: 1024px) {
                .chatbot-container {
                    flex-direction: column;
                }

                .conversation-sidebar {
                    width: 100%;
                    height: 200px;
                }
            }
        </style>
    @endpush

    <div class="chatbot-container">
        <!-- Sidebar - Conversation List -->
        <div class="conversation-sidebar">
            <div class="sidebar-header">
                <h2>Percakapan</h2>
                <div class="filter-controls">
                    <button class="filter-btn {{ $filter === 'all' ? 'active' : '' }}" onclick="filterConversations('all')">
                        Semua
                    </button>
                    <button class="filter-btn {{ $filter === 'escalated' ? 'active' : '' }}" onclick="filterConversations('escalated')">
                        Escalated
                    </button>
                    <button class="filter-btn {{ $filter === 'needs_response' ? 'active' : '' }}" onclick="filterConversations('needs_response')">
                        Butuh Respons
                    </button>
                    <button class="filter-btn {{ $filter === 'handled' ? 'active' : '' }}" onclick="filterConversations('handled')">
                        Ditangani
                    </button>
                </div>
            </div>

            <div class="sidebar-search">
                <input type="text" class="search-input" id="searchInput" placeholder="Cari percakapan...">
            </div>

            <div class="conversation-list" id="conversationList">
                @forelse($conversations as $conversation)
                    <div class="conversation-item" onclick="selectConversation({{ $conversation->id }})">
                        <div class="conversation-avatar">
                            {{ strtoupper(substr($conversation->user->name, 0, 1)) }}
                        </div>
                        <div class="conversation-info">
                            <div class="conversation-header">
                                <span class="conversation-name">{{ $conversation->user->name }}</span>
                                <span class="conversation-time">{{ $conversation->updated_at->format('H:i') }}</span>
                            </div>
                            <div class="conversation-preview">
                                {{ $conversation->latestMessage->message ?? 'Tidak ada pesan' }}
                            </div>
                            <div class="conversation-badges">
                                @if($conversation->is_escalated)
                                    <span class="badge-escalated">🔥 Escalated</span>
                                @endif
                                @if($conversation->needs_admin_response)
                                    <span class="badge-needs-response">⚠️ Butuh Respons</span>
                                @endif
                                @if($conversation->taken_over_by_admin)
                                    <span class="badge-admin-handled">✓ Ditangani Admin</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="padding: 20px; text-align: center; color: #6c757d;">
                        <p>Tidak ada percakapan</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Main Chat Area -->
        <div class="chat-main" id="chatMain">
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <p>Pilih percakapan untuk memulai</p>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let currentConversationId = null;
            let pollInterval = null;
            let lastMessageCount = 0;
            let isFirstLoad = true;

            function selectConversation(conversationId) {
                currentConversationId = conversationId;
                isFirstLoad = true;
                lastMessageCount = 0;
                
                // Update active state
                document.querySelectorAll('.conversation-item').forEach(item => {
                    item.classList.remove('active');
                });
                event.currentTarget.classList.add('active');

                // Clear old polling
                if (pollInterval) clearInterval(pollInterval);

                // Load conversation
                loadConversation(conversationId);

                // Start polling for new messages every 2 seconds
                pollInterval = setInterval(() => {
                    pollForNewMessages(conversationId);
                }, 2000);
            }

            function loadConversation(conversationId) {
                fetch(`/admin/chatbot/conversation/${conversationId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            displayConversation(data.conversation, true); // Initial load
                        }
                    })
                    .catch(error => {
                        console.error('Error loading conversation:', error);
                    });
            }

            function pollForNewMessages(conversationId) {
                fetch(`/admin/chatbot/conversation/${conversationId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.conversation.messages) {
                            const messageCount = data.conversation.messages.length;
                            
                            // Only update if there are new messages
                            if (messageCount > lastMessageCount) {
                                addNewMessages(data.conversation.messages);
                                lastMessageCount = messageCount;
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error polling messages:', error);
                    });
            }

            function addNewMessages(allMessages) {
                const chatMessages = document.getElementById('chatMessages');
                if (!chatMessages) return;

                // Get existing message elements
                const existingMessages = chatMessages.querySelectorAll('.message');
                const messagesToAdd = allMessages.slice(lastMessageCount);

                // Add only new messages
                messagesToAdd.forEach(msg => {
                    const messageDiv = createMessageElement(msg);
                    chatMessages.appendChild(messageDiv);
                });

                // Scroll to bottom
                setTimeout(() => {
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }, 100);
            }

            function createMessageElement(msg) {
                const messageDiv = document.createElement('div');
                let messageClass = msg.sender_type;
                if (msg.sender_type === 'admin') messageClass = 'admin';
                if (msg.sender_type === 'system') messageClass = 'system';
                
                messageDiv.className = `message ${messageClass}`;
                messageDiv.innerHTML = `
                    <div class="message-bubble">${msg.message}</div>
                    <div class="message-time">${new Date(msg.created_at).toLocaleTimeString()}</div>
                `;
                
                return messageDiv;
            }

            function displayConversation(conversation, isInitialLoad = false) {
                const chatMain = document.getElementById('chatMain');

                // Only rebuild if it's initial load OR if header info changed
                if (isInitialLoad || !document.getElementById('chatMessages')) {
                    // Build header
                    let headerHTML = `
                        <div class="chat-header">
                            <div class="chat-header-left">
                                <div class="conversation-avatar">${conversation.user.name.charAt(0).toUpperCase()}</div>
                                <div class="chat-header-info">
                                    <h3>${conversation.user.name}</h3>
                                    <p>${conversation.product ? conversation.product.name : conversation.subject}</p>
                                </div>
                            </div>
                            <div class="chat-header-actions">
                                ${conversation.taken_over_by_admin ? `
                                    <button class="action-btn" onclick="releaseConversation(${conversation.id})">Lepas ke Bot</button>
                                    <button class="action-btn danger" onclick="closeConversation(${conversation.id})">Tutup</button>
                                ` : `
                                    <button class="action-btn primary" onclick="takeOverConversation(${conversation.id})">Ambil Alih</button>
                                    <button class="action-btn" onclick="escalateConversation(${conversation.id})">Escalated</button>
                                `}
                            </div>
                        </div>

                        <div class="chat-messages" id="chatMessages">
                    `;

                    // Add messages
                    if (conversation.messages && conversation.messages.length > 0) {
                        conversation.messages.forEach(msg => {
                            let messageClass = msg.sender_type;
                            if (msg.sender_type === 'admin') messageClass = 'admin';
                            if (msg.sender_type === 'system') messageClass = 'system';
                            
                            headerHTML += `
                                <div class="message ${messageClass}">
                                    <div class="message-bubble">${msg.message}</div>
                                    <div class="message-time">${new Date(msg.created_at).toLocaleTimeString()}</div>
                                </div>
                            `;
                        });
                    } else {
                        headerHTML += `<div style="text-align: center; color: #6c757d;">Tidak ada pesan</div>`;
                    }

                    headerHTML += `
                        </div>

                        <div class="chat-input-area">
                            <div class="input-group">
                                <textarea class="chat-input" id="messageInput" placeholder="Ketik pesan Anda..." rows="2"></textarea>
                                <button class="send-btn" onclick="sendAdminMessage(${conversation.id})">
                                    <i class="fas fa-paper-plane"></i> Kirim
                                </button>
                            </div>
                        </div>
                    `;

                    chatMain.innerHTML = headerHTML;
                    lastMessageCount = conversation.messages ? conversation.messages.length : 0;

                    // Scroll to bottom
                    setTimeout(() => {
                        const chatMessages = document.getElementById('chatMessages');
                        if (chatMessages) {
                            chatMessages.scrollTop = chatMessages.scrollHeight;
                        }
                    }, 100);
                }
            }

            function takeOverConversation(conversationId) {
                fetch(`/admin/chatbot/conversation/${conversationId}/take-over`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update button states tanpa reload
                        loadConversation(conversationId);
                        alert('Anda telah mengambil alih konversasi');
                    }
                })
                .catch(error => console.error('Error:', error));
            }

            function sendAdminMessage(conversationId) {
                const messageInput = document.getElementById('messageInput');
                const message = messageInput.value.trim();

                if (!message) return;

                // Disable button
                const sendBtn = event.target;
                sendBtn.disabled = true;

                fetch(`/admin/chatbot/conversation/${conversationId}/send-message`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ message })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        messageInput.value = '';
                        // Don't reload entire conversation, just add the message
                        // It will be picked up by the next poll
                    } else {
                        alert('Gagal mengirim pesan');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi error saat mengirim pesan');
                })
                .finally(() => {
                    sendBtn.disabled = false;
                    messageInput.focus();
                });
            }

            function escalateConversation(conversationId) {
                const reason = prompt('Alasan escalation:');
                if (!reason) return;

                fetch(`/admin/chatbot/conversation/${conversationId}/escalate`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ reason })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Konversasi berhasil di-escalate');
                        loadConversation(conversationId);
                    }
                })
                .catch(error => console.error('Error:', error));
            }

            function releaseConversation(conversationId) {
                if (!confirm('Lepas konversasi ini kembali ke chatbot otomatis?')) return;

                fetch(`/admin/chatbot/conversation/${conversationId}/release`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadConversation(conversationId);
                    }
                })
                .catch(error => console.error('Error:', error));
            }

            function closeConversation(conversationId) {
                const notes = prompt('Catatan penyelesaian (opsional):');
                
                fetch(`/admin/chatbot/conversation/${conversationId}/close`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ resolution_notes: notes })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (pollInterval) clearInterval(pollInterval);
                        alert('Konversasi ditutup');
                        location.reload();
                    }
                })
                .catch(error => console.error('Error:', error));
            }

            function filterConversations(filter) {
                window.location.href = `?filter=${filter}`;
            }

            // Search functionality
            document.getElementById('searchInput').addEventListener('keyup', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                // TODO: Implement actual search
            });

            // Load unread count on page load
            function updateUnreadCount() {
                fetch('/admin/chatbot/api/unread-count')
                    .then(response => response.json())
                    .then(data => {
                        console.log('Unread conversations:', data);
                        // Update UI with badge counts
                    });
            }

            setInterval(updateUnreadCount, 30000); // Update every 30 seconds
            updateUnreadCount();

            // Cleanup on page unload
            window.addEventListener('beforeunload', () => {
                if (pollInterval) clearInterval(pollInterval);
            });
        </script>
    @endpush
</x-admin-layout>
