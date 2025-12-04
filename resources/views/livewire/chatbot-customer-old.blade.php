<div wire:poll.3s="$refresh">
<div class="chatbot-customer-wrapper">
    <!-- Chat Header -->
    <div class="chatbot-header-bar">
        <div class="chatbot-header-avatar">
            <img src="{{ asset('images/logo.png') }}" alt="LGI Store" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <span class="avatar-fallback" style="display:none;"><i class="fas fa-store"></i></span>
        </div>
        <div class="chatbot-header-info">
            <div class="chatbot-header-name">LGI STORE</div>
            <div class="chatbot-header-status">
                <span class="status-dot {{ $conversation && $conversation->taken_over_by_admin ? 'admin-active' : '' }}"></span>
                @if($conversation && $conversation->taken_over_by_admin)
                    <span class="admin-handling-text">Admin sedang merespons</span>
                @else
                    Online - Balas Cepat
                @endif
            </div>
        </div>
    </div>

    <!-- Chat Messages -->
    <div class="chatpage-messages" id="chatbotMessages" wire:ignore.self>
        @foreach($messages as $message)
            @if($message->sender_type === 'user')
                {{-- User Message --}}
                <div class="message user-message">
                    <div class="message-content">
                        @if($message->metadata && isset($message->metadata['product_context']))
                            @php $product = $message->metadata['product_context']; @endphp
                            <a href="{{ route('product.detail', ['id' => $product['id'] ?? 0]) }}" 
                               target="_blank" 
                               class="product-context-preview-customer">
                                <div class="product-context-header-customer">
                                    <i class="fas fa-box"></i> Produk yang ditanyakan:
                                </div>
                                <div class="product-context-body-customer">
                                    <div class="product-context-name-customer">{{ $product['name'] ?? 'Produk' }}</div>
                                    <div class="product-context-price-customer">Rp {{ number_format($product['price'] ?? 0, 0, ',', '.') }}</div>
                                    @if(isset($product['custom_allowed']) && $product['custom_allowed'])
                                        <span class="product-context-badge-customer">Custom Design</span>
                                    @endif
                                </div>
                                <div class="product-context-link-customer"><i class="fas fa-external-link-alt"></i> Lihat Detail</div>
                            </a>
                        @endif
                        <div class="message-bubble">
                            <div class="message-text">{!! nl2br(e($message->message)) !!}</div>
                        </div>
                        <div class="message-time">{{ $message->created_at->format('H:i') }}</div>
                    </div>
                    <div class="message-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
            @elseif($message->sender_type === 'admin')
                {{-- Admin Message - PENTING: harus ditampilkan dengan styling khusus --}}
                <div class="message admin-message">
                    <div class="message-avatar admin-avatar">
                        <i class="fas fa-headset"></i>
                    </div>
                    <div class="message-content">
                        <div class="message-sender-label">
                            <i class="fas fa-user-shield"></i> Admin
                        </div>
                        <div class="message-bubble admin-bubble">
                            <div class="message-text">{!! nl2br(e($message->message)) !!}</div>
                        </div>
                        <div class="message-time">{{ $message->created_at->format('H:i') }}</div>
                    </div>
                </div>
            @elseif($message->sender_type === 'system')
                {{-- System Message --}}
                <div class="message system-message">
                    <div class="system-bubble">
                        {!! nl2br(e($message->message)) !!}
                    </div>
                </div>
            @else
                {{-- Bot Message --}}
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
    </div>

    <style>
        .chatbot-customer-wrapper {
            display: flex;
            flex-direction: column;
            height: calc(100vh - 65px);
            max-height: calc(100vh - 65px);
            overflow: hidden;
            background: #f8f9fa;
        }
        
        /* Header Bar */
        .chatbot-header-bar {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 20px;
            background: linear-gradient(135deg, #0f1e3d 0%, #1a2d5a 100%);
            color: white;
            flex-shrink: 0;
        }
        
        .chatbot-header-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        .chatbot-header-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .chatbot-header-avatar .avatar-fallback {
            color: #0f1e3d;
            font-size: 20px;
        }
        
        .chatbot-header-info {
            flex: 1;
        }
        
        .chatbot-header-name {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 2px;
        }
        
        .chatbot-header-status {
            font-size: 12px;
            color: rgba(255,255,255,0.8);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .chatbot-header-status .status-dot {
            width: 8px;
            height: 8px;
            background: #22c55e;
            border-radius: 50%;
        }
        
        .chatbot-header-status .status-dot.admin-active {
            background: #f59e0b;
            animation: adminPulse 1.5s infinite;
        }
        
        @keyframes adminPulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.2); }
        }
        
        .admin-handling-text {
            color: #f59e0b;
            font-weight: 500;
        }
        
        .chatpage-messages {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
            min-height: 0; /* Penting untuk flex item agar bisa scroll */
        }
        
        /* Scrollbar hanya muncul jika ada konten yang overflow */
        .chatpage-messages::-webkit-scrollbar {
            width: 6px;
        }
        
        .chatpage-messages::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .chatpage-messages::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 3px;
        }
        
        .chatpage-messages::-webkit-scrollbar-thumb:hover {
            background: #aaa;
        }
        
        /* Quick Replies - posisi tetap di atas input */
        .quick-replies {
            flex-shrink: 0;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            padding: 12px 16px;
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
            justify-content: center;
        }
        
        .quick-reply-btn {
            padding: 8px 16px;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 20px;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .quick-reply-btn:hover {
            background: #0f1e3d;
            color: white;
            border-color: #0f1e3d;
        }
        
        /* Input Wrapper - TIDAK PAKAI ABSOLUTE */
        .chatpage-input-wrapper {
            flex-shrink: 0;
            background: white;
            border-top: 1px solid #e9ecef;
            padding: 12px 16px;
        }
        
        .chatpage-input-container {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .chatpage-input {
            flex: 1;
            padding: 12px 16px;
            border: 1px solid #dee2e6;
            border-radius: 24px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s;
        }
        
        .chatpage-input:focus {
            border-color: #0f1e3d;
        }
        
        .chatpage-send {
            padding: 12px 20px;
            background: linear-gradient(135deg, #0f1e3d 0%, #1a2d5a 100%);
            color: white;
            border: none;
            border-radius: 24px;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
            white-space: nowrap;
        }
        
        .chatpage-send:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(15, 30, 61, 0.3);
        }
        
        .chatpage-send:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        
        /* Message Styles */
        .message {
            display: flex;
            gap: 10px;
            margin-bottom: 16px;
            animation: messageSlideIn 0.3s ease;
        }
        
        @keyframes messageSlideIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .message-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .bot-message .message-avatar {
            background: linear-gradient(135deg, #007bff 0%, #6610f2 100%);
            color: white;
        }
        
        .user-message .message-avatar {
            background: #e9ecef;
            color: #495057;
        }
        
        /* ADMIN MESSAGE STYLES - PENTING */
        .admin-message {
            flex-direction: row;
        }
        
        .admin-message .message-avatar,
        .admin-avatar {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        
        .message-sender-label {
            font-size: 11px;
            color: #28a745;
            font-weight: 600;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .admin-bubble {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
            color: white !important;
            border-bottom-left-radius: 4px !important;
        }
        
        .admin-bubble .message-text {
            color: white !important;
        }
        
        /* System Message */
        .system-message {
            justify-content: center;
            margin: 12px 0;
        }
        
        .system-bubble {
            background: #fff3e0;
            color: #e65100;
            padding: 8px 16px;
            border-radius: 16px;
            font-size: 12px;
            font-weight: 500;
            text-align: center;
        }
        
        .message-content {
            max-width: 75%;
        }
        
        .message-bubble {
            padding: 12px 16px;
            border-radius: 18px;
            background: white;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        
        .bot-message .message-bubble {
            background: linear-gradient(135deg, #007bff 0%, #6610f2 100%);
            color: white;
            border-bottom-left-radius: 4px;
        }
        
        .user-message .message-bubble {
            background: #e9ecef;
            color: #212529;
            border-bottom-right-radius: 4px;
        }
        
        .message-text {
            font-size: 14px;
            line-height: 1.5;
            word-wrap: break-word;
        }
        
        .message-time {
            font-size: 11px;
            color: #999;
            margin-top: 4px;
        }
        
        .admin-message .message-time {
            color: #28a745;
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
    
    /* Product Context Preview - Customer Side */
    .product-context-preview-customer {
        display: block;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 1px solid #dee2e6;
        border-radius: 12px;
        padding: 12px 14px;
        margin-bottom: 8px;
        text-decoration: none;
        color: inherit;
        transition: all 0.2s ease;
        max-width: 100%;
    }
    
    .product-context-preview-customer:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.2);
        border-color: #007bff;
    }
    
    .product-context-header-customer {
        font-size: 10px;
        color: #6c757d;
        font-weight: 600;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .product-context-header-customer i {
        color: #007bff;
    }
    
    .product-context-body-customer {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    
    .product-context-name-customer {
        font-size: 13px;
        font-weight: 600;
        color: #212529;
        line-height: 1.3;
    }
    
    .product-context-price-customer {
        font-size: 12px;
        color: #28a745;
        font-weight: 600;
    }
    
    .product-context-badge-customer {
        display: inline-block;
        background: linear-gradient(135deg, #007bff 0%, #6610f2 100%);
        color: white;
        font-size: 9px;
        padding: 2px 8px;
        border-radius: 10px;
        font-weight: 500;
        margin-top: 4px;
        width: fit-content;
    }
    
    .product-context-link-customer {
        font-size: 10px;
        color: #007bff;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 4px;
        font-weight: 500;
    }
    
    .product-context-preview-customer:hover .product-context-link-customer {
        color: #0056b3;
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
</div>