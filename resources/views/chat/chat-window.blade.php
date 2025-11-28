<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - Katalog Sablon Topi Lampung</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .chat-container {
            max-width: 800px;
            margin: 0 auto;
            height: 80vh;
        }
        .chat-messages {
            height: 60vh;
            overflow-y: auto;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 10px;
            background: #f8f9fa;
        }
        .message {
            margin-bottom: 15px;
            padding: 10px 15px;
            border-radius: 15px;
            max-width: 70%;
        }
        .user-message {
            background: #007bff;
            color: white;
            margin-left: auto;
        }
        .bot-message {
            background: #e9ecef;
            color: #333;
        }
        .admin-message {
            background: #28a745;
            color: white;
            margin-left: auto;
        }
        .system-message {
            background: #f1c40f;
            color: #333;
            text-align: center;
            margin: 10px auto;
            font-size: 0.85em;
        }
        .template-question {
            cursor: pointer;
            padding: 8px 12px;
            margin: 5px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 20px;
            font-size: 0.9em;
        }
        .template-question:hover {
            background: #007bff;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="chat-container">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-robot"></i> Chat Support
                        @if(isset($productData))
                            - {{ $productData['name'] }}
                        @endif
                    </h5>
                    <div>
                        <a href="{{ route('chat.history') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-history"></i> History
                        </a>
                        <button type="button" class="btn btn-sm btn-warning" id="requestAdminBtn">
                            <i class="fas fa-headset"></i> Minta Bantuan Admin
                        </button>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Template Questions -->
                    <div class="template-questions mb-3">
                        <small class="text-muted">Pertanyaan Cepat:</small><br>
                        @foreach($templateQuestions as $question)
                            <span class="template-question d-inline-block" onclick="sendTemplateQuestion('{{ $question }}')">
                                {{ $question }}
                            </span>
                        @endforeach
                    </div>

                    <!-- Chat Messages -->
                    <div class="chat-messages" id="chatMessages">
                        <div class="text-center text-muted">
                            <i class="fas fa-comments"></i><br>
                            Mulai percakapan dengan chatbot...
                        </div>
                    </div>

                    <!-- Chat Input -->
                    <div class="chat-input mt-3">
                        <form id="chatForm">
                            @csrf
                            <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
                            <div class="input-group">
                                <input type="text" name="message" class="form-control" placeholder="Ketik pesan Anda..." required>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i> Kirim
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const conversationId = {{ $conversation->id }};
        let pollInterval = null;
        let lastMessageTime = null;
        
        // Load chat history dari server dan localStorage
        function loadChatHistory() {
            fetch(`/chat/conversation/${conversationId}`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.messages) {
                        // Store in localStorage
                        localStorage.setItem(`chat_history_${conversationId}`, JSON.stringify(data.messages));
                        displayMessages(data.messages);
                        
                        // Update last message time
                        if (data.messages.length > 0) {
                            lastMessageTime = new Date(data.messages[data.messages.length - 1].created_at);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error loading history:', error);
                    // Fallback to localStorage
                    const cached = localStorage.getItem(`chat_history_${conversationId}`);
                    if (cached) {
                        displayMessages(JSON.parse(cached));
                    }
                });
        }

        // Display messages with support for all sender types
        function displayMessages(messages) {
            const chatMessages = document.getElementById('chatMessages');
            chatMessages.innerHTML = '';

            if (messages.length === 0) {
                chatMessages.innerHTML = `
                    <div class="text-center text-muted">
                        <i class="fas fa-comments"></i><br>
                        Mulai percakapan dengan chatbot...
                    </div>
                `;
                return;
            }

            messages.forEach(message => {
                const messageDiv = document.createElement('div');
                
                // Determine message class based on sender_type
                let senderClass = 'bot-message'; // default
                if (message.sender_type === 'user') {
                    senderClass = 'user-message';
                } else if (message.sender_type === 'admin') {
                    senderClass = 'admin-message';
                } else if (message.sender_type === 'system') {
                    senderClass = 'system-message';
                }
                
                messageDiv.className = `message ${senderClass}`;
                
                // Add sender label for admin messages
                let senderLabel = '';
                if (message.sender_type === 'admin') {
                    senderLabel = '<small style="display: block; margin-bottom: 5px; opacity: 0.8;">👤 Admin</small>';
                } else if (message.sender_type === 'system') {
                    senderLabel = '<small style="display: block; margin-bottom: 5px; opacity: 0.8;">ℹ️ Sistem</small>';
                }
                
                messageDiv.innerHTML = `
                    ${senderLabel}
                    <div class="message-content">${message.message}</div>
                    <small class="message-time">${new Date(message.created_at).toLocaleTimeString('id-ID')}</small>
                `;
                chatMessages.appendChild(messageDiv);
            });

            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Poll for new messages
        function pollForNewMessages() {
            fetch(`/chat/conversation/${conversationId}`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.messages) {
                        // Store in localStorage
                        localStorage.setItem(`chat_history_${conversationId}`, JSON.stringify(data.messages));
                        
                        // Check if there are new messages
                        const currentMessages = document.querySelectorAll('.message');
                        if (data.messages.length > currentMessages.length) {
                            displayMessages(data.messages);
                        }
                    }
                })
                .catch(error => console.error('Error polling messages:', error));
        }

        // Send template question
        function sendTemplateQuestion(question) {
            document.querySelector('input[name="message"]').value = question;
            document.getElementById('chatForm').dispatchEvent(new Event('submit'));
        }

        // Handle form submission
        document.getElementById('chatForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const message = formData.get('message');

            if (!message.trim()) return;

            // Add user message immediately
            const chatMessages = document.getElementById('chatMessages');
            const userMessageDiv = document.createElement('div');
            userMessageDiv.className = 'message user-message';
            userMessageDiv.innerHTML = `
                <div class="message-content">${message}</div>
                <small class="message-time">${new Date().toLocaleTimeString('id-ID')}</small>
            `;
            chatMessages.appendChild(userMessageDiv);

            // Clear input
            this.reset();
            chatMessages.scrollTop = chatMessages.scrollHeight;

            // Send to server
            fetch('/chat/send-message', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    conversation_id: conversationId,
                    message: message
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload chat history to get all messages including bot/admin responses
                    setTimeout(() => loadChatHistory(), 500);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const errorDiv = document.createElement('div');
                errorDiv.className = 'message system-message';
                errorDiv.innerHTML = `
                    <div class="message-content">❌ Gagal mengirim pesan. Silakan coba lagi.</div>
                `;
                chatMessages.appendChild(errorDiv);
            });
        });

        // Load initial chat history
        loadChatHistory();

        // Poll for new messages every 1.5 seconds (faster for real-time feel)
        pollInterval = setInterval(pollForNewMessages, 1500);

        // Cleanup on page unload
        window.addEventListener('beforeunload', () => {
            if (pollInterval) clearInterval(pollInterval);
        });
    </script>
</body>
</html>