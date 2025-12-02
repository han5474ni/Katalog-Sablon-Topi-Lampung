<div class="chatbot-container" wire:poll.5s="$refresh">
    <!-- Chat Messages -->
    <div class="chatpage-messages" id="chatbotMessages" wire:ignore.self>
        @foreach($messages as $message)
            @if($message->sender_type === 'user')
                <div class="message user-message">
                    <div class="message-content">
                        <div class="message-bubble">
                            <div class="message-text">{!! nl2br(e($message->message)) !!}</div>
                        </div>
                        <div class="message-time">{{ $message->created_at->format('H:i') }}</div>
                    </div>
                    <div class="message-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
            @else
                <div class="message bot-message">
                    <div class="message-avatar">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div class="message-content">
                        <div class="message-bubble">
                            <div class="message-text">{!! nl2br(e($message->message)) !!}</div>
                            
                            @if($message->metadata && isset($message->metadata['products']))
                                <div class="product-recommendations">
                                    @foreach($message->metadata['products'] as $product)
                                        <a href="{{ route('product.detail', ['id' => $product['id']]) }}" 
                                           class="product-card-mini" 
                                           target="_blank">
                                            <div class="product-info">
                                                <span class="product-name">{{ $product['name'] }}</span>
                                                <span class="product-price">Rp {{ number_format($product['price'], 0, ',', '.') }}</span>
                                            </div>
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="message-time">{{ $message->created_at->format('H:i') }}</div>
                    </div>
                </div>
            @endif
        @endforeach

        @if($isTyping)
            <div class="message bot-message typing-message">
                <div class="message-avatar">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="message-content">
                    <div class="message-bubble">
                        <div class="typing-indicator">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Quick Replies -->
    @if(count($quickReplies) > 0)
        <div class="quick-replies">
            @foreach($quickReplies as $reply)
                <button class="quick-reply-btn" wire:click="selectQuickReply('{{ $reply }}')">
                    {{ $reply }}
                </button>
            @endforeach
        </div>
    @endif

    <!-- Chat Input -->
    <div class="chatpage-input-wrapper">
        <div class="chatpage-input-container">
            <input
                type="text"
                class="chatpage-input"
                wire:model.live="message"
                wire:keydown.enter="sendMessage"
                placeholder="Ketik pesan Anda..."
                maxlength="500"
                @if($isTyping) disabled @endif
            >
            <button 
                class="chatpage-send" 
                wire:click="sendMessage"
                @if(empty(trim($message)) || $isTyping) disabled @endif
            >
                <i class="fas fa-paper-plane"></i> Kirim
            </button>
        </div>
        <div class="chatpage-input-footer">
            <div class="chatpage-footer-content">
                <span class="chatpage-char-count">{{ strlen($message) }}/500</span>
                <button class="chatpage-clear-btn" wire:click="clearHistory" wire:confirm="Apakah Anda yakin ingin menghapus riwayat chat?">
                    <i class="fas fa-trash-alt"></i> Hapus Riwayat
                </button>
            </div>
        </div>
    </div>

    <style>
        .chatbot-container {
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    
    .product-recommendations {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid rgba(0,0,0,0.1);
    }
    
    .product-card-mini {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 14px;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        text-decoration: none;
        color: inherit;
        transition: all 0.2s ease;
    }
    
    .product-card-mini:hover {
        background: #e9ecef;
        transform: translateX(5px);
        border-color: #0f1e3d;
    }
    
    .product-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    
    .product-name {
        font-weight: 600;
        font-size: 13px;
        color: #0f1e3d;
    }
    
    .product-price {
        font-size: 12px;
        color: #28a745;
        font-weight: 500;
    }
    
    .product-card-mini > i {
        color: #999;
        font-size: 12px;
    }
    
    .typing-indicator {
        display: flex;
        gap: 4px;
        padding: 8px 0;
    }
    
    .typing-indicator span {
        width: 8px;
        height: 8px;
        background: #666;
        border-radius: 50%;
        animation: typingBounce 1.4s infinite ease-in-out;
    }
    
    .typing-indicator span:nth-child(1) {
        animation-delay: 0s;
    }
    
    .typing-indicator span:nth-child(2) {
        animation-delay: 0.2s;
    }
    
    .typing-indicator span:nth-child(3) {
        animation-delay: 0.4s;
    }
    
    @keyframes typingBounce {
        0%, 60%, 100% {
            transform: translateY(0);
            opacity: 0.4;
        }
        30% {
            transform: translateY(-8px);
            opacity: 1;
        }
    }
    
    .chatpage-footer-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .chatpage-char-count {
        color: #888;
        font-size: 12px;
    }
    
    .chatpage-clear-btn {
        background: transparent;
        border: 1px solid #dc3545;
        color: #dc3545;
        cursor: pointer;
        font-size: 12px;
        padding: 6px 12px;
        border-radius: 16px;
        transition: all 0.2s;
    }
    
    .chatpage-clear-btn:hover {
        background: #dc3545;
        color: white;
    }

    /* Override for user message order (avatar on right) */
    .user-message {
        flex-direction: row;
    }
    
    .user-message .message-content {
        order: 1;
    }
    
    .user-message .message-avatar {
        order: 2;
    }
    </style>

    @script
    <script>
    // Auto scroll to bottom when new message arrives
    $wire.on('messageAdded', () => {
        setTimeout(() => {
            const container = document.getElementById('chatbotMessages');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        }, 100);
    });
    
    // Scroll to bottom on initial load and after Livewire updates
    const scrollToBottom = () => {
        const container = document.getElementById('chatbotMessages');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    };
    
    // Initial scroll
    setTimeout(scrollToBottom, 300);
    
    // Watch for DOM changes (new messages)
    const observer = new MutationObserver(scrollToBottom);
    const messagesContainer = document.getElementById('chatbotMessages');
    if (messagesContainer) {
        observer.observe(messagesContainer, { childList: true, subtree: true });
    }
    </script>
    @endscript
</div>