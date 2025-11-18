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
     * TEST METHOD: Untuk testing n8n integration tanpa authentication
     */
    public function testSendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'product_id' => 'nullable|integer',
            'product' => 'nullable|array' // Tambahkan validasi untuk product object
        ]);

        // Gunakan URL langsung untuk testing (sementara)
        $n8nUrl = 'https://lgistorelampung.app.n8n.cloud/webhook/chatbot';

        // DEBUG: Log request data
        \Log::info('testSendMessage Request:', $request->all());

        // FIXED: Gunakan payload ASLI dari frontend, jangan reconstruct
        $payload = [
            'message' => $request->message,
            'conversation_id' => $request->conversation_id ?? $request->product_id ?? rand(1000, 9999),
            'user_id' => Auth::id() ?? 1
        ];

        // FIXED: Prioritaskan product object dari frontend
        if ($request->has('product') && is_array($request->product)) {
            $payload['product'] = $request->product;
            \Log::info('Using product from frontend:', $request->product);
        } 
        // Fallback: cari dari database jika hanya product_id yang dikirim
        elseif ($request->product_id) {
            $product = Product::find($request->product_id);
            if ($product) {
                $payload['product'] = [
                    'name' => $product->nama,
                    'price' => $product->harga
                ];
                \Log::info('Using product from database:', $payload['product']);
            }
        }

        // DEBUG: Log final payload
        \Log::info('Final payload to n8n:', $payload);

        try {
            $response = Http::timeout(30)->post($n8nUrl, $payload);

            // DEBUG: Log n8n response
            \Log::info('n8n Response:', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                
                // Simpan ke database jika testing berhasil (optional)
                if (Auth::check() && ($request->product_id || $request->has('product'))) {
                    try {
                        $productId = $request->product_id;
                        $productName = $request->product['name'] ?? 'Unknown Product';
                        
                        $conversation = ChatConversation::firstOrCreate([
                            'user_id' => Auth::id(),
                            'product_id' => $productId,
                            'status' => 'active'
                        ], [
                            'subject' => "Test: {$productName}"
                        ]);

                        // Simpan message ke database
                        ChatMessage::create([
                            'conversation_id' => $conversation->id,
                            'sender_type' => 'user',
                            'message' => $request->message
                        ]);

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
                        // Continue without failing the request
                    }
                }

                return response()->json([
                    'success' => true,
                    'bot_response' => $responseData,
                    'user_message' => $request->message,
                    'debug' => [
                        'payload_sent' => $payload,
                        'product_source' => $request->has('product') ? 'frontend' : ($request->product_id ? 'database' : 'none')
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'n8n service unavailable',
                    'status_code' => $response->status(),
                    'debug' => [
                        'payload_sent' => $payload,
                        'n8n_response' => $response->body()
                    ]
                ], 500);
            }

        } catch (\Exception $e) {
            \Log::error('testSendMessage Exception:', [
                'error' => $e->getMessage(),
                'payload' => $payload
            ]);
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'debug' => [
                    'payload_attempted' => $payload
                ]
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