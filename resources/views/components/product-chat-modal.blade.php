<!-- Product Chat Modal - Global Component -->
<div id="productChatModal" class="product-chat-modal" aria-hidden="true">
    <div class="product-chat-overlay" onclick="closeProductChatModal()"></div>
    <div class="product-chat-content">
        <div class="product-chat-header">
            <div class="product-chat-title">
                <i class="fas fa-robot"></i>
                <h3>Chat Support</h3>
                <span class="product-chat-badge" id="modalProductName">Produk</span>
            </div>
            <button class="product-chat-close" onclick="closeProductChatModal()" aria-label="Tutup chat">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="product-chat-body">
            <div class="product-chat-messages" id="modalChatMessages">
                <div class="bot-message welcome-message">
                    <div class="message-content">
                        <strong>Halo! 👋</strong><br>
                        Saya siap membantu Anda dengan produk <strong id="welcomeProductName">ini</strong>. 
                        Ada yang bisa saya bantu?
                    </div>
                    <small class="message-time" id="welcomeTime"></small>
                </div>
            </div>

            <!-- Template Questions -->
            <div class="product-chat-templates">
                <div class="template-title">Pertanyaan Cepat:</div>
                <div class="template-buttons">
                    <button class="template-btn" onclick="sendModalTemplateQuestion('harga')">💰 Tanya Harga</button>
                    <button class="template-btn" onclick="sendModalTemplateQuestion('stok')">📦 Cek Stok</button>
                    <button class="template-btn" onclick="sendModalTemplateQuestion('warna')">🎨 Pilihan Warna</button>
                    <button class="template-btn" onclick="sendModalTemplateQuestion('ukuran')">📏 Ukuran Tersedia</button>
                    <button class="template-btn custom-design-btn-modal" onclick="sendModalTemplateQuestion('custom')" style="display: none;">🎨 Custom Design</button>
                    <button class="template-btn" onclick="sendModalTemplateQuestion('bahan')">🧵 Material/Bahan</button>
                    <button class="template-btn" onclick="sendModalTemplateQuestion('pengiriman')">🚚 Info Pengiriman</button>
                </div>
            </div>

            <!-- Chat Input -->
            <div class="product-chat-input-container">
                <form id="modalChatForm" class="product-chat-form" onsubmit="sendModalMessage(event)">
                    @csrf
                    <input type="hidden" name="product_id" id="modalProductId" value="">
                    <div class="input-group">
                        <input type="text" name="message" id="modalMessageInput" class="product-chat-input" placeholder="Ketik pertanyaan Anda..." required>
                        <button type="submit" class="product-chat-send-btn">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* Product Chat Modal Styles */
.product-chat-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    align-items: center;
    justify-content: center;
}

.product-chat-modal.show {
    display: flex;
}

.product-chat-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
}

.product-chat-content {
    position: relative;
    width: 90%;
    max-width: 450px;
    max-height: 80vh;
    background: white;
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    animation: slideUp 0.3s ease;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.product-chat-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 20px;
    background: linear-gradient(135deg, #0f1e3d 0%, #1a3a6e 100%);
    color: white;
}

.product-chat-title {
    display: flex;
    align-items: center;
    gap: 10px;
}

.product-chat-title i {
    font-size: 24px;
}

.product-chat-title h3 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
}

.product-chat-badge {
    background: rgba(255, 255, 255, 0.2);
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 11px;
    max-width: 150px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.product-chat-close {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
}

.product-chat-close:hover {
    background: rgba(255, 255, 255, 0.3);
}

.product-chat-body {
    display: flex;
    flex-direction: column;
    flex: 1;
    overflow: hidden;
}

.product-chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    max-height: 300px;
    min-height: 200px;
}

.bot-message, .user-message {
    display: flex;
    flex-direction: column;
    max-width: 85%;
}

.bot-message {
    align-self: flex-start;
}

.user-message {
    align-self: flex-end;
}

.message-content {
    padding: 12px 16px;
    border-radius: 16px;
    font-size: 14px;
    line-height: 1.5;
}

.bot-message .message-content {
    background: #f0f2f5;
    color: #333;
    border-bottom-left-radius: 4px;
}

.user-message .message-content {
    background: linear-gradient(135deg, #0f1e3d 0%, #1a3a6e 100%);
    color: white;
    border-bottom-right-radius: 4px;
}

.message-time {
    font-size: 11px;
    color: #888;
    margin-top: 4px;
    padding: 0 4px;
}

.user-message .message-time {
    text-align: right;
}

.product-chat-templates {
    padding: 12px 16px;
    border-top: 1px solid #eee;
    background: #fafafa;
}

.template-title {
    font-size: 12px;
    color: #666;
    margin-bottom: 8px;
    font-weight: 500;
}

.template-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.template-btn {
    background: white;
    border: 1px solid #ddd;
    padding: 6px 12px;
    border-radius: 16px;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.2s;
    white-space: nowrap;
}

.template-btn:hover {
    background: #0f1e3d;
    color: white;
    border-color: #0f1e3d;
}

.product-chat-input-container {
    padding: 12px 16px;
    border-top: 1px solid #eee;
    background: white;
}

.product-chat-form .input-group {
    display: flex;
    gap: 8px;
}

.product-chat-input {
    flex: 1;
    padding: 12px 16px;
    border: 1px solid #ddd;
    border-radius: 24px;
    font-size: 14px;
    outline: none;
    transition: border-color 0.2s;
}

.product-chat-input:focus {
    border-color: #0f1e3d;
}

.product-chat-send-btn {
    background: linear-gradient(135deg, #0f1e3d 0%, #1a3a6e 100%);
    color: white;
    border: none;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.2s;
}

.product-chat-send-btn:hover {
    transform: scale(1.05);
}

/* Typing indicator */
.typing-indicator {
    display: flex;
    gap: 4px;
    padding: 8px 16px;
    background: #f0f2f5;
    border-radius: 16px;
    align-self: flex-start;
}

.typing-indicator span {
    width: 8px;
    height: 8px;
    background: #666;
    border-radius: 50%;
    animation: typingBounce 1.4s infinite ease-in-out;
}

.typing-indicator span:nth-child(1) { animation-delay: 0s; }
.typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
.typing-indicator span:nth-child(3) { animation-delay: 0.4s; }

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

/* Responsive */
@media (max-width: 480px) {
    .product-chat-content {
        width: 95%;
        max-height: 90vh;
    }
    
    .template-buttons {
        gap: 4px;
    }
    
    .template-btn {
        font-size: 11px;
        padding: 5px 10px;
    }
}
</style>

<script>
// Global product chat modal functionality
let currentModalProduct = null;
let modalConversationId = null;

function openProductChatModal(product) {
    console.log('Opening product chat modal for:', product);
    currentModalProduct = product;
    
    const modal = document.getElementById('productChatModal');
    const productName = document.getElementById('modalProductName');
    const welcomeProductName = document.getElementById('welcomeProductName');
    const welcomeTime = document.getElementById('welcomeTime');
    const productIdInput = document.getElementById('modalProductId');
    const customDesignBtn = document.querySelector('.custom-design-btn-modal');
    const messagesContainer = document.getElementById('modalChatMessages');
    
    // Set product info
    productName.textContent = product.name;
    welcomeProductName.textContent = product.name;
    welcomeTime.textContent = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    productIdInput.value = product.id;
    
    // Show/hide custom design button
    if (customDesignBtn) {
        customDesignBtn.style.display = product.custom_allowed ? 'inline-block' : 'none';
    }
    
    // Clear previous messages except welcome
    const welcomeMessage = messagesContainer.querySelector('.welcome-message');
    messagesContainer.innerHTML = '';
    if (welcomeMessage) {
        messagesContainer.appendChild(welcomeMessage);
    }
    
    // Show modal
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
    
    // Focus input
    setTimeout(() => {
        document.getElementById('modalMessageInput').focus();
    }, 300);
}

function closeProductChatModal() {
    const modal = document.getElementById('productChatModal');
    modal.classList.remove('show');
    document.body.style.overflow = '';
    currentModalProduct = null;
}

function sendModalTemplateQuestion(type) {
    if (!currentModalProduct) return;
    
    const questions = {
        'harga': `Berapa harga ${currentModalProduct.name}?`,
        'stok': `Apakah ${currentModalProduct.name} masih tersedia?`,
        'warna': `Warna apa saja yang tersedia untuk ${currentModalProduct.name}?`,
        'ukuran': `Ukuran apa saja yang tersedia untuk ${currentModalProduct.name}?`,
        'custom': `Apakah ${currentModalProduct.name} bisa custom design?`,
        'bahan': `Bahan/material ${currentModalProduct.name} apa?`,
        'pengiriman': `Bagaimana pengiriman untuk ${currentModalProduct.name}?`
    };
    
    const question = questions[type] || `Pertanyaan tentang ${currentModalProduct.name}`;
    
    // Add user message
    addModalMessage(question, 'user');
    
    // Show typing indicator
    showModalTyping();
    
    // Get bot response
    setTimeout(() => {
        hideModalTyping();
        const response = getModalBotResponse(type);
        addModalMessage(response, 'bot');
    }, 800);
}

function sendModalMessage(event) {
    event.preventDefault();
    
    const input = document.getElementById('modalMessageInput');
    const message = input.value.trim();
    
    if (!message) return;
    
    // Add user message
    addModalMessage(message, 'user');
    input.value = '';
    
    // Show typing indicator
    showModalTyping();
    
    // Process and respond
    setTimeout(() => {
        hideModalTyping();
        const response = processModalUserMessage(message);
        addModalMessage(response, 'bot');
    }, 800);
}

function addModalMessage(text, type) {
    const messagesContainer = document.getElementById('modalChatMessages');
    const now = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    
    const messageDiv = document.createElement('div');
    messageDiv.className = type === 'user' ? 'user-message' : 'bot-message';
    messageDiv.innerHTML = `
        <div class="message-content">${text}</div>
        <small class="message-time">${now}</small>
    `;
    
    messagesContainer.appendChild(messageDiv);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

function showModalTyping() {
    const messagesContainer = document.getElementById('modalChatMessages');
    const typingDiv = document.createElement('div');
    typingDiv.className = 'typing-indicator';
    typingDiv.id = 'modalTypingIndicator';
    typingDiv.innerHTML = '<span></span><span></span><span></span>';
    messagesContainer.appendChild(typingDiv);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

function hideModalTyping() {
    const typing = document.getElementById('modalTypingIndicator');
    if (typing) typing.remove();
}

function getModalBotResponse(type) {
    if (!currentModalProduct) return 'Maaf, terjadi kesalahan.';
    
    const p = currentModalProduct;
    
    const responses = {
        'harga': `💰 <strong>Harga ${p.name}</strong><br><br>Harga: <strong>Rp ${p.formatted_price}</strong><br><br>Untuk info lebih lanjut atau pemesanan, silakan hubungi admin kami.`,
        'stok': `📦 <strong>Ketersediaan ${p.name}</strong><br><br>Produk ini tersedia dan siap dipesan. Untuk memastikan stok terkini, silakan lanjutkan ke halaman detail produk atau hubungi admin.`,
        'warna': `🎨 <strong>Pilihan Warna ${p.name}</strong><br><br>Untuk melihat pilihan warna yang tersedia, silakan kunjungi halaman detail produk. Berbagai pilihan warna menarik tersedia untuk Anda!`,
        'ukuran': `📏 <strong>Ukuran ${p.name}</strong><br><br>Berbagai ukuran tersedia untuk produk ini. Silakan cek halaman detail produk untuk melihat ukuran yang tersedia.`,
        'custom': p.custom_allowed 
            ? `🎨 <strong>Custom Design untuk ${p.name}</strong><br><br>Ya! Produk ini mendukung custom design. Anda bisa mengunggah desain Anda sendiri. Kunjungi halaman detail produk untuk memulai.`
            : `❌ Maaf, ${p.name} tidak mendukung custom design. Namun tersedia dalam berbagai pilihan warna standar yang menarik!`,
        'bahan': `🧵 <strong>Material ${p.name}</strong><br><br>Untuk informasi detail tentang bahan/material, silakan lihat deskripsi di halaman detail produk atau hubungi admin kami.`,
        'pengiriman': `🚚 <strong>Info Pengiriman</strong><br><br>Kami melayani pengiriman ke seluruh Indonesia. Estimasi pengiriman 2-5 hari kerja tergantung lokasi. Untuk detail lebih lanjut, hubungi admin.`
    };
    
    return responses[type] || `Terima kasih atas pertanyaan Anda tentang ${p.name}. Silakan hubungi admin untuk informasi lebih lanjut.`;
}

function processModalUserMessage(message) {
    if (!currentModalProduct) return 'Maaf, terjadi kesalahan.';
    
    const msg = message.toLowerCase();
    const p = currentModalProduct;
    
    // Check for keywords
    if (msg.includes('harga') || msg.includes('berapa') || msg.includes('price')) {
        return getModalBotResponse('harga');
    }
    if (msg.includes('stok') || msg.includes('tersedia') || msg.includes('ada')) {
        return getModalBotResponse('stok');
    }
    if (msg.includes('warna') || msg.includes('color')) {
        return getModalBotResponse('warna');
    }
    if (msg.includes('ukuran') || msg.includes('size')) {
        return getModalBotResponse('ukuran');
    }
    if (msg.includes('custom') || msg.includes('desain') || msg.includes('design')) {
        return getModalBotResponse('custom');
    }
    if (msg.includes('bahan') || msg.includes('material')) {
        return getModalBotResponse('bahan');
    }
    if (msg.includes('kirim') || msg.includes('pengiriman') || msg.includes('ongkir')) {
        return getModalBotResponse('pengiriman');
    }
    
    // Default response
    return `Terima kasih atas pertanyaan Anda tentang <strong>${p.name}</strong>! 😊<br><br>Untuk informasi lebih detail, Anda bisa:<br>• Mengunjungi halaman detail produk<br>• Menghubungi admin kami<br>• Menggunakan tombol pertanyaan cepat di atas`;
}

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeProductChatModal();
    }
});
</script>
