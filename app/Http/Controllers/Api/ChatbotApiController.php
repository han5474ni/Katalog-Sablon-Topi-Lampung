<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatbotApiController extends Controller
{
    /**
     * Get or create unified conversation for current user
     * One user = one conversation for all chatbot sources
     */
    protected function getOrCreateUnifiedConversation($userId)
    {
        if (!$userId) return null;
        
        // Find existing conversation for this user with 'chatbot' source
        $conversation = ChatConversation::where('user_id', $userId)
            ->where('chat_source', 'chatbot')
            ->where('status', 'open')
            ->first();
            
        if (!$conversation) {
            $conversation = ChatConversation::create([
                'user_id' => $userId,
                'status' => 'open',
                'chat_source' => 'chatbot',
                'subject' => 'Customer Chatbot',
                'expires_at' => now()->addDays(30)
            ]);
        }
        
        return $conversation;
    }

    /**
     * Get chat history for current user
     */
    public function getHistory(Request $request)
    {
        try {
            $userId = Auth::id();
            
            if (!$userId) {
                return response()->json([
                    'success' => true,
                    'messages' => [],
                    'conversation_id' => null
                ]);
            }
            
            $conversation = $this->getOrCreateUnifiedConversation($userId);
            
            if (!$conversation) {
                return response()->json([
                    'success' => true,
                    'messages' => [],
                    'conversation_id' => null
                ]);
            }
            
            $messages = ChatMessage::where('conversation_id', $conversation->id)
                ->orderBy('created_at', 'asc')
                ->get(['id', 'message', 'sender_type', 'created_at', 'metadata']);
            
            return response()->json([
                'success' => true,
                'messages' => $messages,
                'conversation_id' => $conversation->id
            ]);
            
        } catch (\Exception $e) {
            Log::error('Chatbot getHistory error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to load chat history'
            ], 500);
        }
    }
    
    /**
     * Send message and get bot response
     */
    public function sendMessage(Request $request)
    {
        try {
            $request->validate([
                'message' => 'required|string|max:500'
            ]);
            
            $userId = Auth::id();
            $userMessage = $request->input('message');
            $productContext = $request->input('product_context'); // Optional product info
            
            if (!$userId) {
                // For guest users, just return bot response without saving
                $botResponse = $this->generateBotResponse($userMessage, $productContext);
                return response()->json([
                    'success' => true,
                    'bot_response' => $botResponse,
                    'conversation_id' => null
                ]);
            }
            
            // Get or create unified conversation
            $conversation = $this->getOrCreateUnifiedConversation($userId);
            
            // Build metadata for user message
            $userMetadata = null;
            if ($productContext) {
                $userMetadata = ['product_context' => $productContext];
            }
            
            // Save user message
            ChatMessage::create([
                'conversation_id' => $conversation->id,
                'chat_conversation_id' => $conversation->id,
                'user_id' => $userId,
                'sender_type' => 'user',
                'message' => $userMessage,
                'metadata' => $userMetadata,
                'is_read_by_admin' => false
            ]);
            
            // Generate bot response
            $botResponse = $this->generateBotResponse($userMessage, $productContext);
            
            // Save bot response
            ChatMessage::create([
                'conversation_id' => $conversation->id,
                'chat_conversation_id' => $conversation->id,
                'sender_type' => 'bot',
                'message' => $botResponse,
                'is_read_by_user' => true
            ]);
            
            // Update conversation timestamp
            $conversation->touch();
            
            return response()->json([
                'success' => true,
                'bot_response' => $botResponse,
                'conversation_id' => $conversation->id
            ]);
            
        } catch (\Exception $e) {
            Log::error('Chatbot sendMessage error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to send message'
            ], 500);
        }
    }
    
    /**
     * Generate bot response based on user message
     */
    protected function generateBotResponse(string $userMessage, $productContext = null): string
    {
        $msg = strtolower($userMessage);
        
        // If there's product context, use product-specific responses
        if ($productContext && isset($productContext['name'])) {
            $productName = $productContext['name'];
            $productPrice = $productContext['price'] ?? 0;
            $customAllowed = $productContext['custom_allowed'] ?? false;
            
            if (str_contains($msg, 'harga') || str_contains($msg, 'berapa')) {
                $formattedPrice = number_format($productPrice, 0, ',', '.');
                return "💰 <strong>Harga {$productName}</strong>\n\nHarga: <strong>Rp {$formattedPrice}</strong>\n\nUntuk info lebih lanjut atau pemesanan, silakan hubungi admin kami.";
            }
            
            if (str_contains($msg, 'stok') || str_contains($msg, 'tersedia')) {
                return "📦 <strong>Ketersediaan {$productName}</strong>\n\nProduk ini tersedia dan siap dipesan. Untuk memastikan stok terkini, silakan cek halaman detail produk.";
            }
            
            if (str_contains($msg, 'custom') || str_contains($msg, 'desain')) {
                if ($customAllowed) {
                    return "🎨 <strong>Custom Design untuk {$productName}</strong>\n\nYa! Produk ini mendukung custom design. Anda bisa mengunggah desain Anda sendiri saat checkout.";
                } else {
                    return "❌ Maaf, {$productName} tidak mendukung custom design. Namun tersedia dalam berbagai pilihan warna standar yang menarik!";
                }
            }
        }
        
        // General responses
        if (str_contains($msg, 'harga') || str_contains($msg, 'berapa') || str_contains($msg, 'price')) {
            $products = Product::where('is_active', true)
                ->orderBy('price', 'asc')
                ->take(3)
                ->get(['name', 'price']);
            
            if ($products->count() > 0) {
                $productList = $products->map(function($p) {
                    return "• {$p->name}: Rp " . number_format($p->price, 0, ',', '.');
                })->join("\n");
                
                return "Berikut beberapa produk dengan harga terjangkau:\n\n{$productList}\n\nUntuk katalog lengkap, silakan kunjungi halaman Katalog kami.";
            }
            return 'Untuk informasi harga lengkap, silakan kunjungi halaman katalog atau detail produk.';
        }
        
        if (str_contains($msg, 'stok') || str_contains($msg, 'tersedia') || str_contains($msg, 'ada')) {
            $inStockCount = Product::where('is_active', true)->where('stock', '>', 0)->count();
            return "Kami memiliki {$inStockCount} produk yang tersedia saat ini! Untuk cek ketersediaan produk spesifik, silakan lihat halaman detail produk.";
        }
        
        if (str_contains($msg, 'kirim') || str_contains($msg, 'pengiriman') || str_contains($msg, 'ongkir')) {
            return "📦 Info Pengiriman:\n\n• Jawa: 2-4 hari kerja\n• Luar Jawa: 3-7 hari kerja\n• Pengiriman via JNE, J&T, SiCepat\n\nOngkos kirim dihitung saat checkout berdasarkan lokasi Anda.";
        }
        
        if (str_contains($msg, 'custom') || str_contains($msg, 'desain') || str_contains($msg, 'design')) {
            $customCount = Product::where('is_active', true)->where('custom_design_allowed', true)->count();
            return "🎨 Custom Design:\n\nYa! Kami menerima custom design. Saat ini ada {$customCount} produk yang mendukung custom design.\n\nCara order custom:\n1. Pilih produk dengan label CUSTOM\n2. Upload desain Anda saat checkout\n3. Tim kami akan review dalam 1x24 jam";
        }
        
        if (str_contains($msg, 'promo') || str_contains($msg, 'diskon') || str_contains($msg, 'sale')) {
            return "🎉 Promo Saat Ini:\n\n• Diskon untuk pembelian pertama\n• Free ongkir min. belanja Rp 200.000\n• Potongan harga untuk order custom dalam jumlah besar\n\nKunjungi halaman utama untuk promo terbaru!";
        }
        
        if (str_contains($msg, 'kategori') || str_contains($msg, 'produk') || str_contains($msg, 'jual')) {
            $categories = Product::select('category')
                ->distinct()
                ->whereNotNull('category')
                ->where('is_active', true)
                ->pluck('category')
                ->toArray();
            
            if (count($categories) > 0) {
                $categoryList = array_map(function($c) { return "• " . ucfirst($c); }, $categories);
                return "📂 Kategori Produk:\n\n" . implode("\n", $categoryList) . "\n\nKunjungi halaman Katalog untuk lihat semua produk.";
            }
            return "Kami menyediakan berbagai macam produk fashion. Kunjungi halaman Katalog untuk melihat semua kategori.";
        }
        
        if (str_contains($msg, 'warna') || str_contains($msg, 'color')) {
            return "🎨 Pilihan Warna:\n\nSetiap produk memiliki pilihan warna yang berbeda. Silakan kunjungi halaman detail produk untuk melihat pilihan warna yang tersedia.";
        }
        
        if (str_contains($msg, 'ukuran') || str_contains($msg, 'size')) {
            return "📏 Ukuran:\n\nSetiap produk memiliki pilihan ukuran yang berbeda. Silakan kunjungi halaman detail produk untuk melihat ukuran yang tersedia.";
        }
        
        if (str_contains($msg, 'bahan') || str_contains($msg, 'material')) {
            return "🧵 Material/Bahan:\n\nInformasi detail tentang bahan produk tersedia di halaman detail produk masing-masing. Kami menggunakan bahan berkualitas tinggi untuk semua produk.";
        }
        
        if (str_contains($msg, 'halo') || str_contains($msg, 'hai') || str_contains($msg, 'hello') || str_contains($msg, 'hi')) {
            return "Halo! 👋 Selamat datang di LGI Store. Ada yang bisa saya bantu hari ini?\n\nAnda bisa tanya tentang:\n• Harga produk\n• Ketersediaan stok\n• Custom design\n• Pengiriman\n• Promo";
        }
        
        if (str_contains($msg, 'terima kasih') || str_contains($msg, 'thanks') || str_contains($msg, 'makasih')) {
            return "Sama-sama! 😊 Senang bisa membantu Anda. Jika ada pertanyaan lain, jangan ragu untuk bertanya ya!\n\nSelamat berbelanja di LGI Store!";
        }
        
        return "Terima kasih atas pertanyaan Anda! 😊\n\nUntuk bantuan lebih lanjut, Anda bisa:\n• Kunjungi halaman Chat untuk berbicara dengan tim support\n• Cek halaman FAQ untuk pertanyaan umum\n• Atau tanyakan langsung tentang harga, stok, custom design, atau pengiriman.";
    }
}
