<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\Product;
use App\Services\ChatBotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    private $chatBotService;

    public function __construct(ChatBotService $chatBotService)
    {
        $this->chatBotService = $chatBotService;
    }

    public function startChat(Request $request, $productId = null)
    {
        $product = null;
        $productData = null;

        if ($productId) {
            $product = Product::find($productId);
            $productData = [
                'id' => $product->id,
                'name' => $product->nama,
                'price' => $product->harga,
                'image' => $product->gambar
            ];
        }

        // Cari atau buat conversation
        $conversation = ChatConversation::firstOrCreate([
            'user_id' => Auth::id(),
            'product_id' => $productId,
            'status' => 'active'
        ], [
            'subject' => $product ? "Tanya tentang {$product->nama}" : "Pertanyaan Umum"
        ]);

        $templateQuestions = $this->chatBotService->getTemplateQuestions($product);

        return view('chat.chat-window', compact('conversation', 'productData', 'templateQuestions'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:chat_conversations,id',
            'message' => 'required|string|max:1000'
        ]);

        $conversation = ChatConversation::find($request->conversation_id);

        // Authorization check
        if ($conversation->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $productData = $conversation->product ? [
            'id' => $conversation->product->id,
            'name' => $conversation->product->nama,
            'price' => $conversation->product->harga
        ] : null;

        $botResponse = $this->chatBotService->processMessage(
            $request->conversation_id, 
            $request->message, 
            $productData
        );

        return response()->json([
            'success' => true,
            'bot_response' => $botResponse,
            'conversation' => $conversation->load('messages')
        ]);
    }

    /**
     * Customer trigger untuk meminta jawaban langsung dari admin
     * Ini akan mengirim notifikasi ke admin dan mark conversation as needs response
     */
    public function requestAdminResponse(Request $request, $conversationId)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500'
        ]);

        $conversation = ChatConversation::find($conversationId);

        if (!$conversation || $conversation->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $result = $this->chatBotService->notifyCustomerNeedsResponse(
                $conversationId, 
                $request->reason ?? 'Customer meminta jawaban langsung admin'
            );

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Admin telah diberitahu dan akan segera membantu Anda'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Error requesting admin response', [
                'conversation_id' => $conversationId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim permintaan ke admin'
            ], 500);
        }
    }

    public function getConversation($conversationId)
    {
        $conversation = ChatConversation::with(['messages' => function($query) {
            $query->orderBy('created_at', 'asc');
        }])->findOrFail($conversationId);

        if ($conversation->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($conversation);
    }

    public function getChatHistory()
    {
        $conversations = ChatConversation::with(['latestMessage', 'product'])
            ->where('user_id', Auth::id())
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('chat.chat-history', compact('conversations'));
    }

    /**
     * TEST METHOD: Untuk testing n8n integration dengan fresh stock data
     * 
     * Enhanced version yang query database untuk mendapatkan stock realtime
     */
    public function testSendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'product_id' => 'nullable|integer',
            'product' => 'nullable|array'
        ]);

        // Get N8N URL from config, fallback to localhost for testing
        $n8nUrl = config('services.n8n.webhook_url') ?? 'http://localhost:5678/webhook/chatbot';

        // DEBUG: Log request data
        \Log::info('testSendMessage Request:', $request->all());

        // Build base payload
        $payload = [
            'message' => $request->message,
            'conversation_id' => $request->conversation_id ?? $request->product_id ?? rand(1000, 9999),
            'user_id' => Auth::check() ? Auth::id() : 1
        ];

        // ===== ENHANCED: Query fresh stock data =====
        if ($request->has('product') && is_array($request->product)) {
            $productData = $request->product;
            $productId = $productData['id'] ?? null;
            
            // Query fresh stock info jika ada product_id
            if ($productId) {
                try {
                    $stockInfo = $this->chatBotService->getProductStockInfo($productId);
                    
                    if ($stockInfo['success']) {
                        // Enhance product data dengan fresh stock info
                        $productData = $this->chatBotService->enrichProductDataWithFreshStock($productData);
                        
                        \Log::info('Fresh stock data loaded:', [
                            'product_id' => $productId,
                            'stock' => $stockInfo['stock'],
                            'available_colors' => $stockInfo['colors'],
                            'available_sizes' => $stockInfo['sizes'],
                            'is_in_stock' => $stockInfo['is_in_stock']
                        ]);
                    } else {
                        \Log::warning('Failed to load fresh stock, using frontend data', [
                            'product_id' => $productId,
                            'error' => $stockInfo['error'] ?? 'Unknown error'
                        ]);
                    }
                } catch (\Exception $e) {
                    \Log::warning('Exception loading fresh stock data: ' . $e->getMessage());
                    // Continue dengan data yang ada
                }
            }
            
            $payload['product'] = $productData;
            \Log::info('Product data prepared for N8N:', $productData);
        } 
        // Fallback: cari dari database jika hanya product_id yang dikirim
        elseif ($request->product_id) {
            try {
                $stockInfo = $this->chatBotService->getProductStockInfo($request->product_id);
                
                if ($stockInfo['success']) {
                    $payload['product'] = [
                        'id' => $stockInfo['product_id'],
                        'name' => $stockInfo['product_name'],
                        'stock' => $stockInfo['stock'],
                        'total_variant_stock' => $stockInfo['total_variant_stock'],
                        'colors' => $stockInfo['colors'],
                        'sizes' => $stockInfo['sizes'],
                        'is_in_stock' => $stockInfo['is_in_stock'],
                        'metadata' => $stockInfo['metadata'] ?? []
                    ];
                    
                    \Log::info('Product data from database:', $payload['product']);
                }
            } catch (\Exception $e) {
                \Log::error('Error fetching product from database: ' . $e->getMessage());
            }
        }

        // DEBUG: Log final payload
        \Log::info('Final payload to N8N:', $payload);

        try {
            $response = Http::timeout(30)->post($n8nUrl, $payload);

            // DEBUG: Log n8n response
            \Log::info('N8N Response:', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                
                // Simpan ke database jika authenticated dan ada product
                if (Auth::check() && isset($payload['product'])) {
                    try {
                        $productId = $payload['product']['id'] ?? null;
                        $productName = $payload['product']['name'] ?? 'Unknown Product';
                        
                        $conversation = ChatConversation::firstOrCreate([
                            'user_id' => Auth::id(),
                            'product_id' => $productId,
                            'status' => 'active'
                        ], [
                            'subject' => "Chat: {$productName}"
                        ]);

                        // Simpan user message
                        ChatMessage::create([
                            'conversation_id' => $conversation->id,
                            'sender_type' => 'user',
                            'message' => $request->message
                        ]);

                        // Simpan bot response
                        ChatMessage::create([
                            'conversation_id' => $conversation->id,
                            'sender_type' => 'bot',
                            'message' => $responseData['message'],
                            'metadata' => $responseData['metadata'] ?? null
                        ]);
                        
                        \Log::info('Chat saved to database', [
                            'conversation_id' => $conversation->id,
                            'product_id' => $productId
                        ]);
                        
                    } catch (\Exception $dbError) {
                        \Log::error('Database save error:', ['error' => $dbError->getMessage()]);
                        // Continue tanpa gagal request
                    }
                }

                return response()->json([
                    'success' => true,
                    'bot_response' => $responseData,
                    'user_message' => $request->message,
                    'metadata' => [
                        'stock_query_executed' => isset($payload['product']['metadata']),
                        'fresh_stock_data' => $payload['product']['metadata'] ?? null
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'n8n service unavailable',
                    'status_code' => $response->status()
                ], 500);
            }

        } catch (\Exception $e) {
            \Log::error('testSendMessage Exception:', [
                'error' => $e->getMessage(),
                'payload' => $payload
            ]);
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * TEST METHOD: Quick test dengan predefined messages
     */
    public function quickTest(Request $request)
    {
        $testType = $request->type ?? 'harga';
        $productId = $request->product_id;
        
        $tests = [
            'harga' => 'berapa harga produk ini?',
            'stok' => 'apakah ready stock?',
            'warna' => 'warna apa yang tersedia?',
            'bahan' => 'bahan apa yang digunakan?',
            'pengiriman' => 'berapa lama pengiriman?'
        ];

        $message = $tests[$testType] ?? 'harga produk berapa?';

        $n8nUrl = 'https://lgistorelampung.app.n8n.cloud/webhook/chatbot';
        
        $payload = [
            'message' => $message,
            'conversation_id' => $productId ?? rand(1000, 9999),
            'user_id' => Auth::id() ?? 1
        ];

        if ($productId) {
            $product = Product::find($productId);
            if ($product) {
                $payload['product'] = [
                    'name' => $product->nama,
                    'price' => $product->harga
                ];
            }
        }

        try {
            $response = Http::timeout(30)->post($n8nUrl, $payload);

            return response()->json([
                'success' => true,
                'test_type' => $testType,
                'message_sent' => $message,
                'bot_response' => $response->json(),
                'product_used' => $product ? $product->nama : 'none'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'test_type' => $testType,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}