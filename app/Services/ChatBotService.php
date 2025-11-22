<?php

namespace App\Services;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatBotService
{
    private $n8nWebhookUrl;

    public function __construct()
    {
        // Gunakan URL langsung untuk testing, fallback ke config
        $this->n8nWebhookUrl = 'http://localhost:5678/webhook/chatbot';
        
        // Jika config tersedia, gunakan config
        if (config('services.n8n.webhook_url')) {
            $this->n8nWebhookUrl = config('services.n8n.webhook_url');
        }
    }

    public function processMessage($conversationId, $userMessage, $productData = null)
    {
        $conversation = ChatConversation::with('product')->find($conversationId);
        
        // Simpan pesan user ke database
        $userMessageRecord = ChatMessage::create([
            'conversation_id' => $conversationId,
            'sender_type' => 'user',
            'message' => $userMessage,
            'metadata' => $productData ? ['product' => $productData] : null
        ]);

        // Cek jika perlu escalate ke admin
        if ($this->shouldEscalateToAdmin($userMessage)) {
            return $this->escalateToAdmin($conversationId, $userMessageRecord);
        }

        // Kirim ke n8n untuk processing
        $botResponse = $this->sendToN8n($conversation, $userMessageRecord, $productData);

        // Simpan response bot ke database
        $botMessage = ChatMessage::create([
            'conversation_id' => $conversationId,
            'sender_type' => 'bot',
            'message' => $botResponse['message'],
            'metadata' => $botResponse['metadata'] ?? null
        ]);

        return $botMessage;
    }

    private function sendToN8n($conversation, $userMessage, $productData = null)
    {
        try {
            // Prepare payload dengan struktur yang sesuai n8n
            $payload = [
                'message' => $userMessage->message,
                'conversation_id' => $conversation->id,
                'user_id' => $conversation->user_id
            ];

            // Tambahkan product data jika ada
            if ($productData) {
                $payload['product'] = [
                    'name' => $productData['name'] ?? null,
                    'price' => $productData['price'] ?? null
                ];
            } elseif ($conversation->product) {
                $payload['product'] = [
                    'name' => $conversation->product->nama,
                    'price' => $conversation->product->harga
                ];
            }

            Log::info('Sending to n8n:', [
                'url' => $this->n8nWebhookUrl,
                'payload' => $payload
            ]);

            $response = Http::timeout(30)->post($this->n8nWebhookUrl, $payload);

            if ($response->successful()) {
                $responseData = $response->json();
                
                Log::info('n8n Response:', $responseData);
                
                return $responseData;
            } else {
                Log::warning('n8n HTTP Error:', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                
                return $this->getDefaultResponse($userMessage->message);
            }

        } catch (\Exception $e) {
            Log::error('N8N Connection Error: ' . $e->getMessage(), [
                'url' => $this->n8nWebhookUrl,
                'conversation_id' => $conversation->id
            ]);
            
            return $this->getDefaultResponse($userMessage->message);
        }
    }

    private function getDefaultResponse($userMessage)
    {
        $message = strtolower($userMessage);
        
        $responses = [
            'harga' => [
                'response' => 'Untuk informasi harga terbaru, silakan hubungi admin kami langsung.',
                'confidence' => 0.8
            ],
            'stok' => [
                'response' => 'Stok produk dapat berubah setiap saat. Mohon konfirmasi ke admin untuk ketersediaan.',
                'confidence' => 0.8
            ],
            'warna' => [
                'response' => 'Produk kami memiliki berbagai variasi warna. Admin akan membantu Anda memilih yang tepat.',
                'confidence' => 0.8
            ],
            'size' => [
                'response' => 'Tersedia berbagai ukuran. Silakan tanyakan ke admin untuk detail lebih lanjut.',
                'confidence' => 0.8
            ],
            'bahan' => [
                'response' => 'Kami menggunakan bahan berkualitas tinggi. Admin dapat memberikan spesifikasi detail.',
                'confidence' => 0.8
            ],
            'pengiriman' => [
                'response' => 'Kami mendukung berbagai metode pengiriman. Admin akan bantu proses pengiriman.',
                'confidence' => 0.8
            ],
            'diskon' => [
                'response' => 'Untuk informasi diskon dan promo terbaru, silakan hubungi admin kami.',
                'confidence' => 0.8
            ],
            'custom' => [
                'response' => 'Kami menerima pesanan custom. Admin akan membantu Anda dengan detailnya.',
                'confidence' => 0.8
            ]
        ];

        // Cari response terbaik berdasarkan keyword
        $bestMatch = [
            'message' => 'Terima kasih atas pertanyaannya. Saya akan menghubungkan Anda dengan admin untuk informasi lebih detail.',
            'metadata' => [
                'type' => 'default_response',
                'confidence' => 0.1,
                'detected_intent' => 'general',
                'should_escalate' => true
            ]
        ];

        foreach ($responses as $key => $data) {
            if (str_contains($message, $key)) {
                $bestMatch = [
                    'message' => $data['response'],
                    'metadata' => [
                        'type' => 'auto_response',
                        'confidence' => $data['confidence'],
                        'detected_intent' => $key,
                        'should_escalate' => false
                    ]
                ];
                break;
            }
        }

        return $bestMatch;
    }

    private function shouldEscalateToAdmin($userMessage)
    {
        $escalateKeywords = [
            'complaint', 'problem', 'issue', 'error', 'wrong', 'broken',
            'keluhan', 'masalah', 'gangguan', 'salah', 'rusak', 'komplain',
            'refund', 'pengembalian', 'garansi', 'warranty', 'sengketa'
        ];

        $message = strtolower($userMessage->message);
        
        foreach ($escalateKeywords as $keyword) {
            if (str_contains($message, $keyword)) {
                Log::info('Escalating to admin due to keyword: ' . $keyword, [
                    'conversation_id' => $userMessage->conversation_id,
                    'message' => $userMessage->message
                ]);
                return true;
            }
        }

        return false;
    }

    private function escalateToAdmin($conversationId, $userMessage)
    {
        $conversation = ChatConversation::find($conversationId);
        $conversation->update(['status' => 'escalated']);

        $adminMessage = ChatMessage::create([
            'conversation_id' => $conversationId,
            'sender_type' => 'bot',
            'message' => 'Pertanyaan Anda telah diarahkan ke admin. Admin akan merespons segera.',
            'metadata' => [
                'escalated' => true,
                'escalation_reason' => 'complex_question',
                'original_message' => $userMessage->message
            ]
        ]);

        // TODO: Notify admin via notification system
        // Broadcast atau notification ke admin panel
        Log::info('Conversation escalated to admin:', [
            'conversation_id' => $conversationId,
            'user_id' => $conversation->user_id,
            'message' => $userMessage->message
        ]);

        return $adminMessage;
    }

    public function getTemplateQuestions($product = null)
    {
        $templates = [
            'Apa harga produk ini?',
            'Apakah produk ini ready stock?',
            'Tersedia warna apa saja?',
            'Berapa lama waktu pengirimannya?',
            'Apa bahan yang digunakan?',
            'Bisa request custom design?',
            'Ada diskon untuk pembelian grosir?',
            'Bagaimana cara perawatan produk?',
            'Apa ukuran yang tersedia?'
        ];

        if ($product) {
            array_unshift($templates, "Tanya tentang {$product->nama}");
        }

        return $templates;
    }

    /**
     * Get fresh stock information including available colors and sizes
     * This method filters colors and sizes to only show those with stock > 0
     * 
     * @param int $productId
     * @return array
     */
    public function getProductStockInfo($productId)
    {
        try {
            $product = Product::with('variants')->findOrFail($productId);
            
            $availableColors = [];
            $availableSizes = [];
            $totalVariantStock = 0;
            $availableVariantCount = 0;
            
            // DEBUG: Log product data
            Log::info('🔍 getProductStockInfo: Querying product', [
                'product_id' => $productId,
                'product_name' => $product->name,
                'has_variants' => $product->variants ? count($product->variants) : 0,
                'base_stock' => $product->stock,
                'base_colors' => $product->colors,
                'base_sizes' => $product->sizes
            ]);
            
            // Process variants to get available colors and sizes
            if ($product->variants && count($product->variants) > 0) {
                foreach ($product->variants as $variant) {
                    if (!isset($variant->stock)) continue; // Skip if no stock value
                    
                    $variantStock = intval($variant->stock);
                    $totalVariantStock += $variantStock;
                    
                    Log::debug('Variant stock check', [
                        'variant_id' => $variant->id,
                        'color' => $variant->color,
                        'size' => $variant->size,
                        'stock' => $variantStock
                    ]);
                    
                    if ($variantStock > 0) {
                        $availableVariantCount++;
                        
                        // Add color if not already in list
                        if ($variant->color) {
                            $cleanColor = trim($variant->color);
                            if (!in_array($cleanColor, $availableColors)) {
                                $availableColors[] = $cleanColor;
                                Log::debug('Color added', ['color' => $cleanColor]);
                            }
                        }
                        
                        // Add size if not already in list
                        if ($variant->size) {
                            $cleanSize = trim($variant->size);
                            if (!in_array($cleanSize, $availableSizes)) {
                                $availableSizes[] = $cleanSize;
                                Log::debug('Size added', ['size' => $cleanSize]);
                            }
                        }
                    }
                }
                
                Log::info('✓ Variants processed successfully', [
                    'total_variant_stock' => $totalVariantStock,
                    'available_variants' => $availableVariantCount,
                    'available_colors_count' => count($availableColors),
                    'available_sizes_count' => count($availableSizes),
                    'available_colors' => $availableColors,
                    'available_sizes' => $availableSizes
                ]);
            } else {
                Log::info('⚠ No variants found, will use fallback colors/sizes');
            }
            
            // If no variants with stock, fall back to product colors/sizes
            if (empty($availableColors) && $product->colors) {
                $availableColors = is_array($product->colors) ? 
                    array_map('trim', $product->colors) : 
                    [trim($product->colors)];
                Log::info('Using fallback colors from Product', ['colors' => $availableColors]);
            }
            
            if (empty($availableSizes) && $product->sizes) {
                $availableSizes = is_array($product->sizes) ? 
                    array_map('trim', $product->sizes) : 
                    [trim($product->sizes)];
                Log::info('Using fallback sizes from Product', ['sizes' => $availableSizes]);
            }
            
            return [
                'success' => true,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'stock' => $product->stock,  // Base product stock
                'total_variant_stock' => $totalVariantStock,  // Total from all variants
                'colors' => $availableColors,  // Only colors with stock > 0
                'sizes' => $availableSizes,  // Only sizes with stock > 0
                'color_count' => count($availableColors),
                'size_count' => count($availableSizes),
                'total_variants' => $product->variants ? count($product->variants) : 0,
                'available_variants' => $availableVariantCount,
                'is_in_stock' => $product->stock > 0 || $totalVariantStock > 0
            ];
            
        } catch (\Exception $e) {
            Log::error('❌ Error getting product stock info: ' . $e->getMessage(), [
                'product_id' => $productId,
                'exception' => $e
            ]);
            
            return [
                'success' => false,
                'error' => 'Produk tidak ditemukan atau terjadi error',
                'product_id' => $productId,
                'exception_message' => $e->getMessage()
            ];
        }
    }

    /**
     * Prepare product data for N8N webhook with fresh stock information
     * 
     * @param array $productData - Basic product data from request
     * @return array - Enhanced product data with fresh stock info
     */
    public function enrichProductDataWithFreshStock(array $productData): array
    {
        // Jika ada product_id, query fresh stock data
        if (isset($productData['id'])) {
            $stockInfo = $this->getProductStockInfo($productData['id']);
            
            if ($stockInfo['success']) {
                // Update product data dengan fresh stock info
                $productData['stock'] = $stockInfo['stock'];
                $productData['total_variant_stock'] = $stockInfo['total_variant_stock'];
                $productData['colors'] = $stockInfo['colors'];
                $productData['sizes'] = $stockInfo['sizes'];
                $productData['is_in_stock'] = $stockInfo['is_in_stock'];
                $productData['metadata'] = [
                    'stock_queried_at' => now()->toIso8601String(),
                    'available_colors_count' => $stockInfo['color_count'],
                    'available_sizes_count' => $stockInfo['size_count'],
                    'available_variants' => $stockInfo['available_variants']
                ];
            }
        }
        
        return $productData;
    }

    /**
     * TEST METHOD: Direct n8n test tanpa database operations
     */
    public function testN8nConnection($testMessage = 'test connection')
    {
        try {
            $payload = [
                'message' => $testMessage,
                'conversation_id' => 999,
                'user_id' => 1,
                'product' => [
                    'name' => 'Test Product',
                    'price' => 50000
                ]
            ];

            $response = Http::timeout(10)->post($this->n8nWebhookUrl, $payload);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'status' => $response->status(),
                    'response' => $response->json(),
                    'n8n_url' => $this->n8nWebhookUrl
                ];
            } else {
                return [
                    'success' => false,
                    'status' => $response->status(),
                    'error' => $response->body(),
                    'n8n_url' => $this->n8nWebhookUrl
                ];
            }

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'n8n_url' => $this->n8nWebhookUrl
            ];
        }
    }

    /**
     * METHOD: Untuk testing dari controller tanpa authentication
     */
    public function processTestMessage($message, $productId = null, $userId = 1)
    {
        $productData = null;
        
        if ($productId) {
            $product = Product::find($productId);
            if ($product) {
                $productData = [
                    'name' => $product->nama,
                    'price' => $product->harga
                ];
            }
        }

        $payload = [
            'message' => $message,
            'conversation_id' => $productId ?? rand(1000, 9999),
            'user_id' => $userId,
            'product' => $productData
        ];

        try {
            $response = Http::timeout(30)->post($this->n8nWebhookUrl, $payload);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'bot_response' => $response->json(),
                    'user_message' => $message
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'n8n service unavailable',
                    'status_code' => $response->status()
                ];
            }

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send message when admin takes over conversation
     * This disables bot responses and uses admin manual responses instead
     */
    public function handleAdminTakeover($conversationId, $adminId)
    {
        try {
            $conversation = ChatConversation::find($conversationId);
            
            if (!$conversation) {
                return [
                    'success' => false,
                    'message' => 'Conversation not found'
                ];
            }

            $conversation->update([
                'taken_over_by_admin' => true,
                'admin_id' => $adminId,
                'is_admin_active' => true,
                'taken_over_at' => now()
            ]);

            // Create system notification
            ChatMessage::create([
                'conversation_id' => $conversationId,
                'sender_type' => 'system',
                'message' => '👤 Admin sedang membantu Anda. Chatbot otomatis dinonaktifkan.',
                'metadata' => [
                    'system_notification' => true,
                    'type' => 'admin_takeover',
                    'admin_id' => $adminId
                ]
            ]);

            Log::info('Admin takeover handled', [
                'conversation_id' => $conversationId,
                'admin_id' => $adminId
            ]);

            return [
                'success' => true,
                'message' => 'Admin telah mengambil alih konversasi'
            ];

        } catch (\Exception $e) {
            Log::error('Error handling admin takeover: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal mengambil alih konversasi'
            ];
        }
    }

    /**
     * Send admin response to customer
     */
    public function sendAdminResponse($conversationId, $adminId, $message)
    {
        try {
            $conversation = ChatConversation::find($conversationId);
            
            if (!$conversation) {
                return [
                    'success' => false,
                    'message' => 'Conversation not found'
                ];
            }

            // Save admin message
            $adminMessage = ChatMessage::create([
                'conversation_id' => $conversationId,
                'sender_type' => 'admin',
                'message' => $message,
                'is_admin_reply' => true,
                'is_read_by_user' => false
            ]);

            // Update conversation
            $conversation->update([
                'taken_over_by_admin' => true,
                'admin_id' => $adminId,
                'is_admin_active' => true,
                'needs_admin_response' => false,
                'needs_response_since' => null
            ]);

            Log::info('Admin response sent', [
                'conversation_id' => $conversationId,
                'message_id' => $adminMessage->id,
                'admin_id' => $adminId
            ]);

            return [
                'success' => true,
                'message' => $adminMessage,
                'conversation' => $conversation
            ];

        } catch (\Exception $e) {
            Log::error('Error sending admin response: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal mengirim pesan'
            ];
        }
    }

    /**
     * Notify customer that they need immediate admin response
     * This shows a notification to admin that customer needs help
     */
    public function notifyCustomerNeedsResponse($conversationId, $reason = null)
    {
        try {
            $conversation = ChatConversation::find($conversationId);
            
            if (!$conversation) {
                return [
                    'success' => false,
                    'message' => 'Conversation not found'
                ];
            }

            $conversation->update([
                'is_escalated' => true,
                'escalated_at' => now(),
                'needs_admin_response' => true,
                'needs_response_since' => now(),
                'escalation_reason' => $reason ?? 'Customer requested immediate admin response'
            ]);

            // Create system notification
            ChatMessage::create([
                'conversation_id' => $conversationId,
                'sender_type' => 'system',
                'message' => '🔔 Admin sedang diberitahu untuk membantu Anda',
                'metadata' => [
                    'system_notification' => true,
                    'type' => 'escalation_notification',
                    'reason' => $reason
                ]
            ]);

            Log::info('Customer needs response notification sent', [
                'conversation_id' => $conversationId,
                'reason' => $reason
            ]);

            return [
                'success' => true,
                'message' => 'Admin telah diberitahu'
            ];

        } catch (\Exception $e) {
            Log::error('Error notifying needs response: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal mengirim notifikasi'
            ];
        }
    }

    /**
     * Toggle chatbot enabled/disabled status for a conversation
     */
    public function toggleChatbotStatus($conversationId, $enabled = true)
    {
        try {
            $conversation = ChatConversation::find($conversationId);
            
            if (!$conversation) {
                return [
                    'success' => false,
                    'message' => 'Conversation not found'
                ];
            }

            $conversation->update([
                'is_admin_active' => !$enabled  // is_admin_active means manual, not bot
            ]);

            $status = $enabled ? 'enabled' : 'disabled';
            
            ChatMessage::create([
                'conversation_id' => $conversationId,
                'sender_type' => 'system',
                'message' => "Chatbot otomatis telah di-{$status}",
                'metadata' => [
                    'system_notification' => true,
                    'type' => 'chatbot_toggle',
                    'status' => $status
                ]
            ]);

            Log::info('Chatbot status toggled', [
                'conversation_id' => $conversationId,
                'enabled' => $enabled
            ]);

            return [
                'success' => true,
                'message' => "Chatbot telah di-{$status}",
                'status' => $status
            ];

        } catch (\Exception $e) {
            Log::error('Error toggling chatbot status: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal mengubah status chatbot'
            ];
        }
    }

    /**
     * Release conversation back to bot
     */
    public function releaseConversationToBot($conversationId)
    {
        try {
            $conversation = ChatConversation::find($conversationId);
            
            if (!$conversation) {
                return [
                    'success' => false,
                    'message' => 'Conversation not found'
                ];
            }

            $conversation->update([
                'taken_over_by_admin' => false,
                'is_admin_active' => false,
                'admin_id' => null,
                'taken_over_at' => null
            ]);

            ChatMessage::create([
                'conversation_id' => $conversationId,
                'sender_type' => 'system',
                'message' => 'Chatbot otomatis telah mengambil alih kembali',
                'metadata' => [
                    'system_notification' => true,
                    'type' => 'bot_resumed'
                ]
            ]);

            Log::info('Conversation released back to bot', [
                'conversation_id' => $conversationId
            ]);

            return [
                'success' => true,
                'message' => 'Conversation released to bot'
            ];

        } catch (\Exception $e) {
            Log::error('Error releasing conversation to bot: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal melepaskan ke bot'
            ];
        }
    }
}