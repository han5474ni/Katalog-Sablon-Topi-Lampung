<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Traits\ImageResolutionTrait;
use App\Traits\StockManagementTrait;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    use ImageResolutionTrait, StockManagementTrait;
    /**
     * Display customer dashboard
     */
    public function dashboard()
    {
        try {
            if (!auth()->check()) {
                return redirect()->route('login');
            }
            
            return view('customer.dashboard', [
                'user' => auth()->user()
            ]);
        } catch (\Exception $e) {
            \Log::error('Dashboard Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Unable to access dashboard. Please try again.');
        }
    }

    /**
     * Display shopping cart page
     */
    public function keranjang(Request $request)
    {
        $cart = $request->session()->get('cart', []);
        $items = collect($cart)->map(function ($item) {
            $product = Product::find($item['product_id']);
            $price = $product ? (float) $product->price : (float) $item['price'];
            
            // Use trait method for image resolution
            $image = $this->resolveItemImage($item, $product);

            return [
                'key' => $item['key'],
                'product_id' => $item['product_id'],
                'variant_id' => $item['variant_id'] ?? null,
                'name' => $product ? $product->name : $item['name'],
                'price' => $price,
                'quantity' => $item['quantity'],
                'color' => $item['color'] ?? null,
                'size' => $item['size'] ?? null,
                'image' => $image,
                'stock' => $product ? $product->stock : null,
            ];
        });

        $subtotal = $items->reduce(function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        return view('customer.keranjang', [
            'cartItems' => $items,
            'subtotal' => $subtotal,
        ]);
    }

    /**
     * Display chatbot page
     */
    public function chatbot()
    {
        return view('customer.chatbot');
    }

    /**
     * Display chatpage page
     */
    public function chatpage()
    {
        return view('customer.chatpage');
    }

    /**
     * Buy Now - Create order directly without cart
     */
    public function buyNow(Request $request)
    {
        // Validate request
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1|max:99',
            'color' => 'nullable|string',
            'size' => 'nullable|string',
        ]);

        $user = auth()->user();

        try {
            // Load product
            $product = Product::findOrFail($request->product_id);

            // Check if product is active
            if (!$product->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak tersedia'
                ], 400);
            }

            // Load variant if provided
            $variant = null;
            if ($request->variant_id) {
                $variant = \App\Models\ProductVariant::find($request->variant_id);
            }

            // Check stock availability (STRICT VALIDATION)
            $availableStock = $variant ? (int) $variant->stock : (int) $product->stock;
            
            if ($availableStock <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Maaf, stok produk habis. Tidak dapat melakukan pembelian.'
                ], 400);
            }
            
            // Check if requested quantity exceeds stock
            if ($request->quantity > $availableStock) {
                return response()->json([
                    'success' => false,
                    'message' => "Maaf, stok tersedia hanya {$availableStock} item."
                ], 400);
            }

            // Determine price (from variant or product)
            $price = $variant ? (float) $variant->price : (float) $product->price;

            // Prepare order item
            $item = [
                'product_id' => $product->id,
                'variant_id' => $variant ? $variant->id : null,
                'name' => $product->name,
                'price' => $price,
                'quantity' => (int) $request->quantity,
                'color' => $request->color,
                'size' => $request->size,
                'image' => $this->resolveProductImage($product->id, $variant ? $variant->id : null),
            ];

            // Calculate totals
            $subtotal = $price * $item['quantity'];
            $discount = 0;
            $total = $subtotal - $discount;

            // Create order with status 'pending' (waiting for admin approval)
            $order = \App\Models\Order::create([
                'user_id' => $user->id,
                'items' => [$item], // Single item array
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'status' => 'pending', // Waiting for admin confirmation
                'payment_status' => 'unpaid',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat! Menunggu konfirmasi admin.',
                'order_id' => $order->id,
                'redirect_url' => route('order-list')
            ]);

        } catch (\Exception $e) {
            \Log::error('Buy Now Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pesanan. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Display custom design page
     */
    public function customDesign(Request $request)
    {
        // Enforce that only products with custom_design_allowed can access this page
        $productId = $request->query('id');
        $variantId = $request->query('variant_id');

        if ($productId) {
            try {
                $product = \App\Models\Product::findOrFail($productId);

                if (!($product->custom_design_allowed ?? false)) {
                    return redirect()->back()->with('error', 'Custom desain tidak tersedia untuk produk ini.');
                }

                // Load variant if variant_id is provided
                $variant = null;
                if ($variantId) {
                    $variant = \App\Models\ProductVariant::where('id', $variantId)
                        ->where('product_id', $productId)
                        ->first();
                }

                // Pass product and variant to view
                return view('customer.custom-design', [
                    'product' => $product,
                    'variant' => $variant,
                ]);
            } catch (\Exception $e) {
                return redirect()->route('catalog', ['category' => 'kaos'])->with('error', 'Produk tidak ditemukan untuk custom desain.');
            }
        }

        // Fallback: if no product id provided, redirect to catalog with info
        return redirect()->route('catalog', ['category' => 'kaos'])->with('error', 'Pilih produk terlebih dahulu untuk custom desain.');
    }

    /**
     * Display address page
     */
    public function alamat(Request $request)
    {
        $user = auth()->user();
        $user->load('addresses');
        
        // Check if coming from order payment
        $orderType = $request->query('order_type');
        $orderId = $request->query('order_id');
        
        if ($orderType && $orderId) {
            // Store in session with unique key per order to avoid conflicts
            $sessionKey = "payment_flow_{$orderType}_{$orderId}";
            $request->session()->put($sessionKey, [
                'order_type' => $orderType,
                'order_id' => $orderId,
                'started_at' => now()->toDateTimeString()
            ]);
            
            // Also store in global session for backward compatibility
            $request->session()->put('payment_order_type', $orderType);
            $request->session()->put('payment_order_id', $orderId);
            $request->session()->put('current_payment_session', $sessionKey);
            
            // Verify order exists and belongs to user
            if ($orderType === 'custom') {
                $order = \App\Models\CustomDesignOrder::where('user_id', $user->id)
                    ->where('id', $orderId)
                    ->where('status', 'approved')
                    ->first();
            } else {
                $order = \App\Models\Order::where('user_id', $user->id)
                    ->where('id', $orderId)
                    ->where('status', 'approved')
                    ->first();
            }
            
            if (!$order) {
                return redirect()->route('order-list')->with('error', 'Pesanan tidak ditemukan atau belum disetujui');
            }
        }
        
        return view('customer.alamat', compact('user'));
    }

    /**
     * Save selected address to session
     */
    public function selectAddress(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:customer_addresses,id',
        ]);
        
        $user = auth()->user();
        $address = \App\Models\CustomerAddress::where('user_id', $user->id)
            ->where('id', $request->address_id)
            ->first();
            
        if (!$address) {
            return response()->json(['success' => false, 'message' => 'Alamat tidak ditemukan'], 404);
        }
        
        $request->session()->put('selected_address_id', $request->address_id);
        
        return response()->json(['success' => true]);
    }

    /**
     * Display pemesanan page
     */
    public function pemesanan(Request $request)
    {
        // Get selected address from session
        $selectedAddressId = $request->session()->get('selected_address_id');
        
        if (!$selectedAddressId) {
            return redirect()->route('alamat')->with('error', 'Silakan pilih alamat terlebih dahulu');
        }
        
        $user = auth()->user();
        $address = \App\Models\CustomerAddress::where('user_id', $user->id)
            ->where('id', $selectedAddressId)
            ->first();
            
        if (!$address) {
            return redirect()->route('alamat')->with('error', 'Alamat tidak ditemukan');
        }
        
        // Check if this is for order payment
        $orderType = $request->session()->get('payment_order_type');
        $orderId = $request->session()->get('payment_order_id');
        
        return view('customer.pemesanan', compact('address', 'orderType', 'orderId'));
    }

    /**
     * Save selected shipping method to session
     */
    public function selectShipping(Request $request)
    {
        $request->validate([
            'shipping_method' => 'required|in:delivery,pickup',
        ]);
        
        $request->session()->put('shipping_method', $request->shipping_method);
        
        return response()->json(['success' => true]);
    }

    /**
     * Generate Virtual Account (1 VA = 1 Order)
     */
    public function generateVA(Request $request)
    {
        $request->validate([
            'bank_code' => 'required|in:bca,bni,bri,permata',
            'order_type' => 'required|in:regular,custom',
            'order_id' => 'required|integer',
        ]);
        
        $user = auth()->user();
        $orderType = $request->order_type;
        $orderId = $request->order_id;
        
        // Get and verify order
        if ($orderType === 'custom') {
            $order = \App\Models\CustomDesignOrder::where('user_id', $user->id)
                ->where('id', $orderId)
                ->where('status', 'approved')
                ->first();
            
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan tidak ditemukan atau belum disetujui'
                ], 404);
            }
            
            $amount = $order->total_price;
        } else {
            $order = \App\Models\Order::where('user_id', $user->id)
                ->where('id', $orderId)
                ->where('status', 'approved')
                ->first();
            
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan tidak ditemukan atau belum disetujui'
                ], 404);
            }
            
            $amount = $order->total;
        }
        
        // Check if this order already has an active VA
        $existingVA = \App\Models\VirtualAccount::where('user_id', $user->id)
            ->where('order_type', $orderType)
            ->where('order_id', $orderId)
            ->where('status', 'pending')
            ->where('expired_at', '>', now())
            ->first();
        
        if ($existingVA) {
            // Return existing VA for this order
            return response()->json([
                'success' => true,
                'message' => 'VA untuk pesanan ini sudah ada dan masih aktif.',
                'va' => [
                    'va_number' => $existingVA->va_number,
                    'bank_code' => $existingVA->bank_code,
                    'bank_name' => $this->getBankName($existingVA->bank_code),
                    'amount' => number_format($amount, 0, ',', '.'),
                    'amount_raw' => $amount,
                    'expired_at' => $existingVA->expired_at->format('d M Y, H:i'),
                    'expired_at_iso' => $existingVA->expired_at->toISOString(),
                ]
            ]);
        }
        
        // Generate VA number
        $vaNumber = \App\Models\VirtualAccount::generateVANumber($request->bank_code, $user->id);
        
        // Create VA for this specific order
        $va = \App\Models\VirtualAccount::create([
            'user_id' => $user->id,
            'order_type' => $orderType,
            'order_id' => $orderId,
            'bank_code' => $request->bank_code,
            'va_number' => $vaNumber,
            'amount' => $amount,
            'status' => 'pending',
            'expired_at' => now()->addHour(), // 1 hour expiry
        ]);
        
        // Update this specific order payment_status to 'va_active'
        $order->update(['payment_status' => 'va_active']);
        
        // Create payment transaction for this specific order
        \App\Models\PaymentTransaction::create([
            'user_id' => $user->id,
            'order_type' => $orderType,
            'order_id' => $orderId,
            'payment_method' => 'virtual_account',
            'amount' => $amount,
            'status' => 'pending',
            'transaction_id' => 'VA-' . $vaNumber,
        ]);
        
        \Log::info("VA generated for Order #{$orderId} ({$orderType}): VA #{$va->id}, Amount: {$amount}");
        
        return response()->json([
            'success' => true,
            'message' => 'Virtual Account berhasil dibuat untuk pesanan ini',
            'va' => [
                'va_number' => $va->va_number,
                'bank_code' => $va->bank_code,
                'bank_name' => $this->getBankName($va->bank_code),
                'amount' => number_format($amount, 0, ',', '.'),
                'amount_raw' => $amount,
                'expired_at' => $va->expired_at->format('d M Y, H:i'),
                'expired_at_iso' => $va->expired_at->toISOString(),
            ]
        ]);
    }

    /**
     * Calculate total amount for all unpaid approved orders
     */
    private function calculateTotalUnpaidOrders($userId)
    {
        // Get all approved orders that are not paid yet (regular orders)
        // Include: unpaid, va_active, and null payment_status
        $regularOrders = \App\Models\Order::where('user_id', $userId)
            ->where('status', 'approved')
            ->where(function($q) {
                $q->where('payment_status', '!=', 'paid')
                  ->orWhereNull('payment_status');
            })
            ->get();
        
        // Get all approved custom orders that are not paid yet
        // Include: unpaid, va_active, and null payment_status
        $customOrders = \App\Models\CustomDesignOrder::where('user_id', $userId)
            ->where('status', 'approved')
            ->where(function($q) {
                $q->where('payment_status', '!=', 'paid')
                  ->orWhereNull('payment_status');
            })
            ->get();
        
        // Calculate total
        $regularTotal = $regularOrders->sum('total');
        $customTotal = $customOrders->sum('total_price');
        
        \Log::info("Calculate Total Unpaid Orders for User #{$userId}:", [
            'regular_orders_count' => $regularOrders->count(),
            'regular_total' => $regularTotal,
            'custom_orders_count' => $customOrders->count(),
            'custom_total' => $customTotal,
            'grand_total' => $regularTotal + $customTotal
        ]);
        
        return $regularTotal + $customTotal;
    }

    /**
     * Get bank name from code
     */
    private function getBankName($code)
    {
        $banks = [
            'bca' => 'BCA',
            'bni' => 'BNI',
            'bri' => 'BRI',
            'permata' => 'Permata',
        ];
        
        return $banks[$code] ?? $code;
    }

    /**
     * Display pembayaran page with direct access (skip alamat/pengiriman if VA exists)
     */
    public function pembayaranDirect(Request $request)
    {
        $user = auth()->user();
        $orderType = $request->query('order_type');
        $orderId = $request->query('order_id');
        
        if (!$orderType || !$orderId) {
            return redirect()->route('order-list')->with('error', 'Data pesanan tidak valid');
        }
        
        // Check if this specific order has active VA
        $activeVA = \App\Models\VirtualAccount::where('user_id', $user->id)
            ->where('order_type', $orderType)
            ->where('order_id', $orderId)
            ->where('status', 'pending')
            ->where('expired_at', '>', now())
            ->first();
        
        if (!$activeVA) {
            // No active VA for this order, redirect to normal flow
            return redirect()->route('alamat')->with([
                'info' => 'Silakan lengkapi data alamat dan pengiriman',
            ])->withInput(['order_type' => $orderType, 'order_id' => $orderId]);
        }
        
        // Get order data
        if ($orderType === 'custom') {
            $order = \App\Models\CustomDesignOrder::where('user_id', $user->id)
                ->where('id', $orderId)
                ->where('status', 'approved')
                ->first();
            
            if (!$order) {
                return redirect()->route('order-list')->with('error', 'Pesanan tidak ditemukan atau belum disetujui');
            }
            
            $items = collect([[
                'name' => $order->product_name,
                'price' => $order->total_price,
                'quantity' => $order->quantity,
                'image' => $order->variant ? $order->variant->image : ($order->product ? $order->product->image : null),
            ]]);
            
            $subtotal = $order->total_price;
        } else {
            $order = \App\Models\Order::where('user_id', $user->id)
                ->where('id', $orderId)
                ->where('status', 'approved')
                ->first();
            
            if (!$order) {
                return redirect()->route('order-list')->with('error', 'Pesanan tidak ditemukan atau belum disetujui');
            }
            
            $items = collect($order->items);
            $subtotal = $order->subtotal;
        }
        
        // Use dummy address and shipping for display (since we're skipping those steps)
        $address = $user->addresses()->where('is_primary', true)->first() ?? $user->addresses()->first();
        $shippingMethod = 'delivery'; // Default
        
        // Store in session for processOrder
        $request->session()->put('payment_order_type', $orderType);
        $request->session()->put('payment_order_id', $orderId);
        if ($address) {
            $request->session()->put('selected_address_id', $address->id);
        }
        $request->session()->put('shipping_method', $shippingMethod);
        
        return view('customer.pembayaran', compact('address', 'shippingMethod', 'items', 'subtotal', 'orderType', 'orderId', 'activeVA'));
    }

    /**
     * Display pembayaran page
     */
    public function pembayaran(Request $request)
    {
        // Get data from session
        $selectedAddressId = $request->session()->get('selected_address_id');
        $shippingMethod = $request->session()->get('shipping_method');
        
        if (!$selectedAddressId || !$shippingMethod) {
            return redirect()->route('alamat')->with('error', 'Silakan lengkapi alamat dan metode pengiriman');
        }
        
        $user = auth()->user();
        $address = \App\Models\CustomerAddress::where('user_id', $user->id)
            ->where('id', $selectedAddressId)
            ->first();
            
        if (!$address) {
            return redirect()->route('alamat')->with('error', 'Alamat tidak ditemukan');
        }
        
        // Check if this is for order payment or cart checkout
        $orderType = $request->session()->get('payment_order_type');
        $orderId = $request->session()->get('payment_order_id');
        
        if ($orderType && $orderId) {
            // Payment for existing order
            if ($orderType === 'custom') {
                $order = \App\Models\CustomDesignOrder::where('user_id', $user->id)
                    ->with(['product', 'variant'])
                    ->findOrFail($orderId);
                
                $items = collect([[
                    'name' => $order->product_name,
                    'price' => $order->total_price,
                    'quantity' => $order->quantity,
                    'image' => $order->variant ? $order->variant->image : ($order->product ? $order->product->image : null),
                ]]);
                
                $subtotal = $order->total_price;
            } else {
                $order = \App\Models\Order::where('user_id', $user->id)
                    ->findOrFail($orderId);
                
                $items = collect($order->items);
                $subtotal = $order->subtotal;
            }
            
            // Check if THIS SPECIFIC ORDER has active VA
            $activeVA = \App\Models\VirtualAccount::where('user_id', $user->id)
                ->where('order_type', $orderType)
                ->where('order_id', $orderId)
                ->where('status', 'pending')
                ->where('expired_at', '>', now())
                ->first();
            
            return view('customer.pembayaran', compact('address', 'shippingMethod', 'items', 'subtotal', 'orderType', 'orderId', 'activeVA'));
        }
        
        // Cart checkout (create new order)
        $cart = $request->session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('keranjang')->with('error', 'Keranjang kosong');
        }
        
        $items = collect($cart)->map(function ($item) {
            $product = \App\Models\Product::find($item['product_id']);
            
            return [
                'product_id' => $item['product_id'],
                'variant_id' => $item['variant_id'] ?? null,
                'name' => $product ? $product->name : $item['name'],
                'price' => (float) ($product ? $product->price : $item['price']),
                'quantity' => $item['quantity'],
                'color' => $item['color'] ?? null,
                'size' => $item['size'] ?? null,
                'image' => $this->resolveItemImage($item, $product),
            ];
        });
        
        $subtotal = $items->sum(fn($item) => $item['price'] * $item['quantity']);
        
        // For cart checkout, no active VA (VA is per order, not per cart)
        $activeVA = null;
        
        // Set default values for orderType and orderId for cart checkout
        $orderType = null;
        $orderId = null;
        
        return view('customer.pembayaran', compact('address', 'shippingMethod', 'items', 'subtotal', 'activeVA', 'orderType', 'orderId'));
    }

    /**
     * Process final order from pembayaran page
     */
    public function processOrder(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:bca,bni,bri,permata,gopay,dana,shopeepay',
        ]);
        
        $selectedAddressId = $request->session()->get('selected_address_id');
        $shippingMethod = $request->session()->get('shipping_method');
        
        // Check if this is for existing order payment or new cart order
        $orderType = $request->session()->get('payment_order_type');
        $orderId = $request->session()->get('payment_order_id');
        
        $user = auth()->user();
        
        if ($orderType && $orderId) {
            // Update existing order with payment info
            try {
                if ($orderType === 'custom') {
                    $order = \App\Models\CustomDesignOrder::where('user_id', $user->id)
                        ->findOrFail($orderId);
                } else {
                    $order = \App\Models\Order::where('user_id', $user->id)
                        ->findOrFail($orderId);
                }
                
                $order->update([
                    'customer_address_id' => $selectedAddressId,
                    'shipping_service' => $shippingMethod,
                    'payment_method_id' => $request->payment_method,
                    'status' => 'paid', // Update status to paid
                    'paid_at' => now(),
                ]);
                
                // Clear session data
                $request->session()->forget(['selected_address_id', 'shipping_method', 'payment_order_type', 'payment_order_id']);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Pembayaran berhasil',
                    'order_id' => $order->id,
                    'redirect_url' => route('order-list')
                ]);
            } catch (\Exception $e) {
                \Log::error('Order Payment Error: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memproses pembayaran. Silakan coba lagi.'
                ], 500);
            }
        }
        
        // Create new order from cart
        $cart = $request->session()->get('cart', []);
        
        if (!$selectedAddressId || !$shippingMethod || empty($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak lengkap. Silakan ulangi proses checkout.'
            ], 400);
        }
        
        $items = collect($cart)->map(function ($item) {
            $product = \App\Models\Product::find($item['product_id']);
            
            return [
                'product_id' => $item['product_id'],
                'variant_id' => $item['variant_id'] ?? null,
                'name' => $product ? $product->name : $item['name'],
                'price' => (float) ($product ? $product->price : $item['price']),
                'quantity' => $item['quantity'],
                'color' => $item['color'] ?? null,
                'size' => $item['size'] ?? null,
                'image' => $this->resolveItemImage($item, $product),
            ];
        });

        $subtotal = $items->sum(fn($item) => $item['price'] * $item['quantity']);
        $discount = 0;
        $total = $subtotal - $discount;

        try {
            $order = \App\Models\Order::create([
                'user_id' => $user->id,
                'customer_address_id' => $selectedAddressId,
                'items' => $items->toArray(),
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'status' => 'pending',
                'shipping_service' => $shippingMethod,
                'payment_method_id' => $request->payment_method,
            ]);

            // Clear session data
            $request->session()->forget(['cart', 'selected_address_id', 'shipping_method']);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat',
                'order_id' => $order->id,
                'redirect_url' => route('order-list')
            ]);
        } catch (\Exception $e) {
            \Log::error('Order Creation Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pesanan. Silakan coba lagi.'
            ], 500);
        }
    }

    // Deprecated Midtrans payment methods removed - System now uses Virtual Account
    // See CLEANUP_REPORT.md for details

    /**
     * Handle custom design order submission and file uploads
     */
    public function storeCustomDesign(Request $request)
    {
        \Log::info('=== CUSTOM DESIGN ORDER SUBMISSION ===');
        \Log::info('Request Data:', $request->all());
        
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'variant_id' => 'nullable|exists:product_variants,id',
                'quantity' => 'required|integer|min:1|max:99',
                'cutting_type' => 'required|string',
                'special_materials' => 'nullable|string',
                'additional_description' => 'nullable|string',
                'uploads' => 'required|array|min:1',
                'uploads.*.section_name' => 'required|string',
                'uploads.*.file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240', // max 10MB
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation Error:', $e->errors());
            return response()->json([
                'success' => false, 
                'message' => 'Validasi gagal: ' . json_encode($e->errors())
            ], 422);
        }

        \DB::beginTransaction();
        try {
            $user = auth()->user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User tidak terautentikasi'], 401);
            }
            
            $product = \App\Models\Product::with('customDesignPrices')->findOrFail($request->product_id);
            
            $quantity = (int) $request->quantity;
            
            // Calculate price per item
            $pricePerItem = $product->price;
            \Log::info('Base Price per Item: ' . $pricePerItem);
            
            // Get product-specific custom design prices
            $productCustomPrices = $product->customDesignPrices->keyBy('code');
            
            // Add upload section prices (only for uploaded sections)
            foreach ($request->uploads as $upload) {
                $sectionName = $upload['section_name'];
                // Extract code from name like "A (Dada depan...)"
                if (preg_match('/^([A-J])\s/', $sectionName, $matches)) {
                    $code = $matches[1];
                    if (isset($productCustomPrices[$code])) {
                        $price = $productCustomPrices[$code]->pivot->custom_price ?? $productCustomPrices[$code]->price;
                        $pricePerItem += $price;
                        \Log::info("Added price for section $code: $price");
                    }
                }
            }
            
            // Add cutting type price
            $cuttingType = $request->cutting_type;
            $cuttingPrice = \App\Models\CustomDesignPrice::where('type', 'cutting_type')
                ->where('name', $cuttingType)
                ->first();
            
            if ($cuttingPrice) {
                // Check if product has custom price for this cutting type
                $productCutting = $product->customDesignPrices()
                    ->where('custom_design_price_id', $cuttingPrice->id)
                    ->first();
                
                $price = $productCutting ? ($productCutting->pivot->custom_price ?? $cuttingPrice->price) : $cuttingPrice->price;
                $pricePerItem += $price;
                \Log::info("Added cutting price: $price");
            }
            
            // Calculate total price with quantity
            $totalPrice = $pricePerItem * $quantity;
            \Log::info('Price per Item: ' . $pricePerItem);
            \Log::info('Quantity: ' . $quantity);
            \Log::info('Total Price: ' . $totalPrice);

            // Parse special_materials if it's JSON string
            $specialMaterials = [];
            if ($request->special_materials) {
                $decoded = json_decode($request->special_materials, true);
                $specialMaterials = is_array($decoded) ? $decoded : [];
            }

            $order = \App\Models\CustomDesignOrder::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'variant_id' => $request->variant_id,
                'product_name' => $product->name,
                'product_price' => $product->price,
                'quantity' => $quantity,
                'cutting_type' => $cuttingType,
                'special_materials' => $specialMaterials,
                'additional_description' => $request->additional_description,
                'status' => 'pending',
                'total_price' => $totalPrice,
            ]);
            
            \Log::info('Order created: ' . $order->id);

            $uploadRecords = [];
            foreach ($request->uploads as $upload) {
                $file = $upload['file'];
                $section = $upload['section_name'];
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('public/custom-designs/' . $order->id, $filename);
                $uploadRecords[] = [
                    'custom_design_order_id' => $order->id,
                    'section_name' => $section,
                    'file_path' => str_replace('public/', '', $path),
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            \App\Models\CustomDesignUpload::insert($uploadRecords);
            
            \Log::info('Uploads saved: ' . count($uploadRecords));

            \DB::commit();
            \Log::info('✅ Order completed successfully');
            
            return response()->json([
                'success' => true, 
                'order_id' => $order->id,
                'redirect_url' => route('order-list')
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('❌ CustomDesignOrder Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false, 
                'message' => 'Gagal menyimpan pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1|max:99',
            'color' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        
        // Check if variant_id is provided
        if (!empty($validated['variant_id'])) {
            $variant = \App\Models\ProductVariant::find($validated['variant_id']);
            $availableStock = $variant ? (int) $variant->stock : 0;
        } else {
            $availableStock = (int) $product->stock;
        }
        
        // Strict validation: Reject if stock is 0
        if ($availableStock <= 0) {
            return back()->with('error', 'Maaf, stok produk habis. Tidak dapat menambahkan ke keranjang.');
        }
        
        $quantity = min((int) $validated['quantity'], $availableStock);
        $key = md5($product->id . '|' . ($validated['color'] ?? '') . '|' . ($validated['size'] ?? ''));

        $cart = $request->session()->get('cart', []);

        if (isset($cart[$key])) {
            // Check if adding more would exceed stock
            $newQuantity = $cart[$key]['quantity'] + $quantity;
            if ($newQuantity > $availableStock) {
                return back()->with('error', "Maaf, stok tersedia hanya {$availableStock} item. Anda sudah memiliki {$cart[$key]['quantity']} item di keranjang.");
            }
            $cart[$key]['quantity'] = min($newQuantity, $availableStock);
        } else {
            $cart[$key] = [
                'key' => $key,
                'product_id' => $product->id,
                'variant_id' => $validated['variant_id'] ?? null,
                'name' => $product->name,
                'price' => (float) $product->price,
                'quantity' => $quantity,
                'color' => $validated['color'] ?? null,
                'size' => $validated['size'] ?? null,
                'image' => $product->image,
            ];
        }

        $request->session()->put('cart', $cart);

        return redirect()->route('keranjang')->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function updateCartItem(Request $request, string $key)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $cart = $request->session()->get('cart', []);

        if (!isset($cart[$key])) {
            return redirect()->route('keranjang')->with('error', 'Produk tidak ditemukan di keranjang.');
        }

        $product = Product::find($cart[$key]['product_id']);
        $maxStock = $product ? max(1, (int) $product->stock) : 99;
        $cart[$key]['quantity'] = min((int) $validated['quantity'], $maxStock);

        $request->session()->put('cart', $cart);

        return redirect()->route('keranjang')->with('success', 'Keranjang berhasil diperbarui.');
    }

    public function removeCartItem(Request $request, string $key)
    {
        $cart = $request->session()->get('cart', []);

        if (isset($cart[$key])) {
            unset($cart[$key]);
            $request->session()->put('cart', $cart);
        }

        return redirect()->route('keranjang')->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    public function removeSelected(Request $request)
    {
        $validated = $request->validate([
            'keys' => 'required|array|min:1',
            'keys.*' => 'string',
        ]);

        $cart = $request->session()->get('cart', []);

        foreach ($validated['keys'] as $key) {
            unset($cart[$key]);
        }

        $request->session()->put('cart', $cart);

        return redirect()->route('keranjang')->with('success', 'Produk terpilih berhasil dihapus dari keranjang.');
    }

    /**
     * Handle checkout process
     */
    public function checkout(Request $request)
    {
        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('keranjang')->with('error', 'Keranjang kosong, tidak dapat melakukan checkout.');
        }

        $user = auth()->user();
        $items = collect($cart)->map(function ($item) {
            $product = \App\Models\Product::find($item['product_id']);
            
            return [
                'product_id' => $item['product_id'],
                'variant_id' => $item['variant_id'] ?? null,
                'name' => $product ? $product->name : $item['name'],
                'price' => (float) ($product ? $product->price : $item['price']),
                'quantity' => $item['quantity'],
                'color' => $item['color'] ?? null,
                'size' => $item['size'] ?? null,
                'image' => $this->resolveItemImage($item, $product),
            ];
        });

        $subtotal = $items->sum(fn($item) => $item['price'] * $item['quantity']);
        $discount = 0; // Could be calculated from vouchers
        $total = $subtotal - $discount;

        try {
            $order = \App\Models\Order::create([
                'user_id' => $user->id,
                'items' => $items->toArray(),
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'status' => 'pending',
            ]);

            // Clear cart after successful order creation
            $request->session()->forget('cart');

            return redirect()->route('order-list')->with('success', 'Pesanan berhasil dibuat dan menunggu konfirmasi admin.');
        } catch (\Exception $e) {
            \Log::error('Checkout Error: ' . $e->getMessage());
            return redirect()->route('keranjang')->with('error', 'Gagal membuat pesanan. Silakan coba lagi.');
        }
    }

    /**
     * Display order list page with user's orders
     * Includes both regular orders and custom design orders
     */
    public function orderList()
    {
        $user = auth()->user();
        
        // Get regular orders
        $regularOrders = \App\Models\Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get custom design orders
        $customOrders = \App\Models\CustomDesignOrder::where('user_id', $user->id)
            ->with(['uploads', 'product', 'variant'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Merge and sort by created_at
        $allOrders = $regularOrders->concat($customOrders)
            ->sortByDesc('created_at')
            ->values();
        
        // Manual pagination
        $perPage = 10;
        $currentPage = request()->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        
        $orders = new \Illuminate\Pagination\LengthAwarePaginator(
            $allOrders->slice($offset, $perPage),
            $allOrders->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('customer.order-list', compact('orders'));
    }

    /**
     * Display order detail page
     */
    public function orderDetail($type, $id)
    {
        $user = auth()->user();
        
        if ($type === 'custom') {
            $order = \App\Models\CustomDesignOrder::where('user_id', $user->id)
                ->with(['uploads', 'product', 'variant'])
                ->findOrFail($id);
        } else {
            $order = \App\Models\Order::where('user_id', $user->id)
                ->findOrFail($id);
            
            // Enhance items array with product and variant data for image display
            // Note: Order items use hash keys, so we need to use values() to reset to numeric keys
            $items = collect($order->items)->values()->map(function ($item) {
                // Load product data
                $product = \App\Models\Product::find($item['product_id'] ?? null);
                
                // Update item with the resolved image using trait
                $item['image'] = $this->resolveItemImage($item, $product);
                
                return $item;
            })->toArray();
            
            // Update order items with enhanced data
            $order->items = $items;
        }
        
        return view('customer.order-detail', compact('order', 'type'));
    }

    /**
     * Cancel an order
     */
    public function cancelOrder($type, $id)
    {
        try {
            $user = auth()->user();
            
            if ($type === 'custom') {
                $order = \App\Models\CustomDesignOrder::where('user_id', $user->id)
                    ->findOrFail($id);
            } else {
                $order = \App\Models\Order::where('user_id', $user->id)
                    ->findOrFail($id);
            }
            
            // Can only cancel pending or approved (unpaid) orders
            if (!in_array($order->status, ['pending', 'approved'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya pesanan dengan status pending atau approved yang belum dibayar dapat dibatalkan'
                ], 400);
            }
            
            // Check if order has been paid
            if (isset($order->payment_status) && $order->payment_status === 'paid') {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat membatalkan pesanan yang sudah dibayar'
                ], 400);
            }
            
            // Check if THIS specific order has active VA
            $activeVA = \App\Models\VirtualAccount::where('user_id', $user->id)
                ->where('order_id', $id)
                ->where('order_type', $type)
                ->where('status', 'pending')
                ->where('expired_at', '>', now())
                ->first();
            
            if ($activeVA) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat membatalkan pesanan karena pesanan ini memiliki Virtual Account aktif. Harap batalkan VA atau tunggu VA expired terlebih dahulu.'
                ], 400);
            }
            
            // If order was approved, restore stock
            if ($order->status === 'approved') {
                $this->restoreStockForOrder($order, $type);
            }
            
            // Update status to cancelled
            $order->status = 'cancelled';
            $order->cancelled_at = now();
            $order->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibatalkan'
            ]);
        } catch (\Exception $e) {
            \Log::error('Cancel Order Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan pesanan'
            ], 500);
        }
    }

    /**
     * Download custom design file
     * Accessible by both customer (owner) and admin
     */
    public function downloadCustomDesignFile($uploadId)
    {
        try {
            // Find the upload
            $upload = \App\Models\CustomDesignUpload::findOrFail($uploadId);
            
            // Authorization check:
            // - If customer (web guard), must own the order
            // - If admin (admin guard), allow access to any file
            if (auth()->guard('web')->check()) {
                // Customer must own the order
                $user = auth()->user();
                $order = \App\Models\CustomDesignOrder::where('id', $upload->custom_design_order_id)
                    ->where('user_id', $user->id)
                    ->firstOrFail();
            } elseif (auth()->guard('admin')->check()) {
                // Admin can download any file, just verify order exists
                $order = \App\Models\CustomDesignOrder::findOrFail($upload->custom_design_order_id);
            } else {
                // Not authenticated
                abort(403, 'Unauthorized access');
            }
            
            // Get the file path
            $filePath = storage_path('app/public/' . $upload->file_path);
            
            // Check if file exists
            if (!file_exists($filePath)) {
                return redirect()->back()->with('error', 'File tidak ditemukan.');
            }
            
            // Get original filename from path or use section name
            $fileName = basename($upload->file_path);
            $extension = pathinfo($fileName, PATHINFO_EXTENSION);
            
            // Create a user-friendly filename
            $downloadName = $upload->section_name . '.' . $extension;
            
            // Return file download response
            return response()->download($filePath, $downloadName);
            
        } catch (\Exception $e) {
            \Log::error('Download Custom Design File Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengunduh file.');
        }
    }

    /**
     * Check payment status page
     */
    public function paymentStatus(Request $request)
    {
        $user = auth()->user();
        $orderType = $request->query('type', 'regular');
        $orderId = $request->query('order_id');

        if (!$orderId) {
            return redirect()->route('order-list')->with('error', 'Order ID tidak ditemukan');
        }

        try {
            if ($orderType === 'custom') {
                $order = \App\Models\CustomDesignOrder::with(['product', 'variant', 'uploads'])
                    ->where('user_id', $user->id)
                    ->where('id', $orderId)
                    ->firstOrFail();
                
                $orderData = [
                    'id' => $order->id,
                    'type' => 'custom',
                    'product_name' => $order->product_name,
                    'quantity' => $order->quantity,
                    'total_price' => $order->total_price,
                    'product_price' => $order->product_price,
                    'custom_price' => $order->total_price - $order->product_price,
                    'status' => $order->status,
                    'created_at' => $order->created_at,
                    'approved_at' => $order->approved_at,
                    'image' => $order->variant ? $order->variant->image : ($order->product ? $order->product->image : null),
                    'cutting_type' => $order->cutting_type,
                    'special_materials' => $order->special_materials,
                    'description' => $order->additional_description,
                ];
            } else {
                $order = \App\Models\Order::with(['user'])
                    ->where('user_id', $user->id)
                    ->where('id', $orderId)
                    ->firstOrFail();
                
                $orderData = [
                    'id' => $order->id,
                    'type' => 'regular',
                    'items' => $order->items,
                    'subtotal' => $order->subtotal,
                    'discount' => $order->discount,
                    'total_price' => $order->total,
                    'status' => $order->status,
                    'created_at' => $order->created_at,
                    'approved_at' => $order->approved_at ?? null,
                ];
            }

            // Get Virtual Account for this user
            $virtualAccount = \App\Models\VirtualAccount::where('user_id', $user->id)
                ->where('status', 'pending')
                ->where('expired_at', '>', now())
                ->latest()
                ->first();

            // Get Payment Transaction
            $paymentTransaction = \App\Models\PaymentTransaction::where('user_id', $user->id)
                ->where(function($q) use ($orderId, $orderType) {
                    $q->where('order_id', $orderId)
                      ->where('order_type', $orderType);
                })
                ->orWhere(function($q) use ($virtualAccount) {
                    if ($virtualAccount) {
                        $q->where('virtual_account_id', $virtualAccount->id);
                    }
                })
                ->latest()
                ->first();

            // Get payment history
            $paymentHistory = \App\Models\PaymentTransaction::where('user_id', $user->id)
                ->latest()
                ->take(5)
                ->get();

            return view('customer.payment-status', compact(
                'orderData',
                'virtualAccount',
                'paymentTransaction',
                'paymentHistory'
            ));

        } catch (\Exception $e) {
            \Log::error('Payment Status Error: ' . $e->getMessage());
            return redirect()->route('order-list')->with('error', 'Gagal memuat status pembayaran');
        }
    }

}
