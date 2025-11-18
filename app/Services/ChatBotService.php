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
}