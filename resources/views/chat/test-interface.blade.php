<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test ChatBot Interface</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .chat-container {
            max-width: 800px;
            margin: 20px auto;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
        }
        .chat-messages {
            height: 400px;
            overflow-y: auto;
            border: 1px solid #eee;
            padding: 15px;
            margin-bottom: 15px;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="chat-container">
            <h3><i class="fas fa-robot"></i> Test ChatBot Interface</h3>
            

            <div class="mb-3">
                <label class="form-label">Pilih Produk (Optional):</label>
                <select id="productSelect" class="form-select">
                    <option value="">Pilih Produk...</option>
                    <option value="1" data-name="Topi Sablon Bintang" data-price="65000">
                        Topi Sablon Bintang - Rp 65.000
                    </option>
                    <option value="2" data-name="Topi Sablon Custom" data-price="75000">
                        Topi Sablon Custom - Rp 75.000
                    </option>
                    <option value="3" data-name="Topi Basic Hitam" data-price="45000">
                        Topi Basic Hitam - Rp 45.000
                    </option>
                </select>
            </div>
            
            <div class="chat-messages" id="chatMessages">
                <div class="text-center text-muted">
                    <i class="fas fa-comments"></i><br>
                    Mulai percakapan dengan chatbot...
                </div>
            </div>
            
            <div class="input-group">
                <input type="text" id="messageInput" class="form-control" placeholder="Ketik pesan Anda..." onkeypress="if(event.key=='Enter') sendMessage()">
                <button onclick="sendMessage()" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Kirim
                </button>
            </div>
            
            <div class="mt-3">
                <small class="text-muted">Quick Test:</small>
                <button onclick="quickTest('harga')" class="btn btn-sm btn-outline-primary">Tanya Harga</button>
                <button onclick="quickTest('stok')" class="btn btn-sm btn-outline-primary">Tanya Stok</button>
                <button onclick="quickTest('warna')" class="btn btn-sm btn-outline-primary">Tanya Warna</button>
                <button onclick="quickTest('bahan')" class="btn btn-sm btn-outline-primary">Tanya Bahan</button>
            </div>
        </div>
    </div>

    <script>
        // Get CSRF token safely
        function getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        }

        console.log('CSRF Token:', getCsrfToken());
        console.log('Chat interface loaded');

        // Simple products data
        const products = [
            {id: 1, name: 'Topi Sablon Bintang', price: 65000},
            {id: 2, name: 'Topi Sablon Custom', price: 75000},
            {id: 3, name: 'Topi Basic Hitam', price: 45000}
        ];

        // Load products to dropdown
        function loadProducts() {
            const select = document.getElementById('productSelect');
            products.forEach(product => {
                const option = document.createElement('option');
                option.value = product.id;
                option.textContent = `${product.name} - Rp ${product.price.toLocaleString('id-ID')}`;
                option.dataset.name = product.name;
                option.dataset.price = product.price;
                select.appendChild(option);
            });
        }

        function addMessage(message, isUser = false) {
            const chatMessages = document.getElementById('chatMessages');
            
            // Clear initial message
            if (chatMessages.children.length === 1 && chatMessages.children[0].classList.contains('text-center')) {
                chatMessages.innerHTML = '';
            }
            
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${isUser ? 'user-message' : 'bot-message'}`;
            messageDiv.innerHTML = `
                <div class="message-content">${message}</div>
                <small class="d-block mt-1 opacity-75">${new Date().toLocaleTimeString()}</small>
            `;
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
            
            console.log('Message added:', message, 'isUser:', isUser);
        }

        function sendMessage() {
            const input = document.getElementById('messageInput');
            const message = input.value.trim();
            const productSelect = document.getElementById('productSelect');
            const selectedProduct = productSelect.options[productSelect.selectedIndex];
            
            if (!message) return;
            
            addMessage(message, true);
            input.value = '';
            
            // DEBUG: Log product data
            console.log('=== PRODUCT DEBUG ===');
            console.log('Selected product element:', selectedProduct);
            console.log('Product value:', selectedProduct.value);
            console.log('Product dataset:', selectedProduct.dataset);
            console.log('Product name:', selectedProduct.dataset?.name);
            console.log('Product price:', selectedProduct.dataset?.price);
            
            // FIXED: Payload structure
            const payload = {
                message: message,
                conversation_id: selectedProduct.value || Math.floor(Math.random() * 1000),
                user_id: 1
            };
            
            // FIXED: Proper product data structure
            if (selectedProduct.value && selectedProduct.dataset.name) {
                payload.product = {
                    name: selectedProduct.dataset.name,
                    price: parseInt(selectedProduct.dataset.price) || 0
                };
                console.log('✅ Product data added:', payload.product);
            } else {
                console.log('❌ No product data available');
            }
            
            console.log('=== FINAL PAYLOAD ===');
            console.log(JSON.stringify(payload, null, 2));
            
            fetch('/test-chat-send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                body: JSON.stringify(payload)
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('=== BOT RESPONSE ===');
                console.log(JSON.stringify(data, null, 2));
                
                if (data.success) {
                    addMessage(data.bot_response.message);
                } else {
                    addMessage('Error: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                addMessage('Error: ' + error.message);
            });
        }
        
        function quickTest(type) {
            const productSelect = document.getElementById('productSelect');
            const selectedProduct = productSelect.options[productSelect.selectedIndex];
            
            // Gunakan kalimat yang lebih natural dengan keyword yang jelas
            const tests = {
                harga: 'berapa harga produk ini?',
                stok: 'apakah produk ini ready stock?', 
                warna: 'warna apa saja yang tersedia?',
                bahan: 'bahan apa yang digunakan untuk produk ini?'
            };
            
            let message = tests[type];
            
            // Jika product dipilih, tambahkan nama product ke message
            if (selectedProduct.value && selectedProduct.dataset.name) {
                message = message.replace('produk ini', selectedProduct.dataset.name);
            }
            
            console.log('Quick test message:', message);
            document.getElementById('messageInput').value = message;
            sendMessage();
        }
        
        // Enter key support
        document.getElementById('messageInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') sendMessage();
        });
        
        // Initialize
        loadProducts();
        console.log('Chat interface initialized successfully');
    </script>
</body>
</html>