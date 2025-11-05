<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
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
            $image = $product && $product->image ? $product->image : ($item['image'] ?? null);

            return [
                'key' => $item['key'],
                'product_id' => $item['product_id'],
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
     * Display custom design page
     */
    public function customDesign(Request $request)
    {
        // Enforce that only products with custom_design_allowed can access this page
        $productId = $request->query('id');

        if ($productId) {
            try {
                $product = \App\Models\Product::findOrFail($productId);

                if (!($product->custom_design_allowed ?? false)) {
                    return redirect()->back()->with('error', 'Custom desain tidak tersedia untuk produk ini.');
                }

                // Pass product to view to avoid trusting query params entirely
                return view('customer.custom-design', [
                    'product' => $product,
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
    public function alamat()
    {
        return view('customer.alamat');
    }

    /**
     * Display pemesanan page
     */
    public function pemesanan()
    {
        return view('customer.pemesanan');
    }

    /**
     * Display pembayaran page
     */
    public function pembayaran()
    {
        return view('customer.pembayaran');
    }

    /**
     * Handle custom design order submission and file uploads
     */
    public function storeCustomDesign(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'cutting_type' => 'required|string',
            'special_materials' => 'nullable|array',
            'special_materials.*' => 'string',
            'additional_description' => 'nullable|string',
            'uploads' => 'required|array',
            'uploads.*.section_name' => 'required|string',
            'uploads.*.file' => 'required|file|mimes:jpg,jpeg,png|max:5120', // max 5MB
        ]);

        \DB::beginTransaction();
        try {
            $user = auth()->user();
            $product = \App\Models\Product::findOrFail($request->product_id);

            $order = \App\Models\CustomDesignOrder::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_price' => $product->price,
                'cutting_type' => $request->cutting_type,
                'special_materials' => $request->special_materials ?? [],
                'additional_description' => $request->additional_description,
                'status' => 'pending',
                'total_price' => $product->price, // bisa diupdate jika ada kalkulasi harga custom
            ]);

            $uploadRecords = [];
            foreach ($request->uploads as $upload) {
                $file = $upload['file'];
                $section = $upload['section_name'];
                $filename = $section . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('public/custom-designs/' . $order->id, $filename);
                $uploadRecords[] = [
                    'custom_design_order_id' => $order->id,
                    'section_name' => $section,
                    'file_path' => str_replace('public/', '', $path),
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                ];
            }
            \App\Models\CustomDesignUpload::insert($uploadRecords);

            \DB::commit();
            return response()->json(['success' => true, 'order_id' => $order->id]);
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('CustomDesignOrder Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan pesanan custom desain. Silakan coba lagi.'], 500);
        }
    }

    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:99',
            'color' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $quantity = min((int) $validated['quantity'], max(1, (int) $product->stock ?: 1));
        $key = md5($product->id . '|' . ($validated['color'] ?? '') . '|' . ($validated['size'] ?? ''));

        $cart = $request->session()->get('cart', []);

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] = min($cart[$key]['quantity'] + $quantity, (int) $product->stock ?: 99);
        } else {
            $cart[$key] = [
                'key' => $key,
                'product_id' => $product->id,
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
                'name' => $product ? $product->name : $item['name'],
                'price' => (float) ($product ? $product->price : $item['price']),
                'quantity' => $item['quantity'],
                'color' => $item['color'] ?? null,
                'size' => $item['size'] ?? null,
                'image' => $product ? $product->image : ($item['image'] ?? null),
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
     */
    public function orderList()
    {
        $user = auth()->user();
        $orders = \App\Models\Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('customer.order-list', compact('orders'));
    }
}
