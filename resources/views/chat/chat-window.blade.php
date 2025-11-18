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
                    <a href="{{ route('chat.history') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-history"></i> History
                    </a>
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
        
        // Load chat history
        function loadChatHistory() {
            fetch(`/chat/conversation/${conversationId}`)
                .then(response => response.json())
                .then(data => {
                    displayMessages(data.messages);
                });
        }

        // Display messages
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
                messageDiv.className = `message ${message.sender_type}-message`;
                messageDiv.innerHTML = `
                    <div class="message-content">${message.message}</div>
                    <small class="message-time">${new Date(message.created_at).toLocaleTimeString()}</small>
                `;
                chatMessages.appendChild(messageDiv);
            });

            chatMessages.scrollTop = chatMessages.scrollHeight;
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

            // Add user message immediately
            const chatMessages = document.getElementById('chatMessages');
            const userMessageDiv = document.createElement('div');
            userMessageDiv.className = 'message user-message';
            userMessageDiv.innerHTML = `
                <div class="message-content">${message}</div>
                <small class="message-time">${new Date().toLocaleTimeString()}</small>
            `;
            chatMessages.appendChild(userMessageDiv);

            // Clear input
            this.reset();

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
                    // Add bot response
                    const botMessageDiv = document.createElement('div');
                    botMessageDiv.className = 'message bot-message';
                    botMessageDiv.innerHTML = `
                        <div class="message-content">${data.bot_response.message}</div>
                        <small class="message-time">${new Date().toLocaleTimeString()}</small>
                    `;
                    chatMessages.appendChild(botMessageDiv);
                    
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });

        // Load initial chat history
        loadChatHistory();
    </script>
</body>
</html>