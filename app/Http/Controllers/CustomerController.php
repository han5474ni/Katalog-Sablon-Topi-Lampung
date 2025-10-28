<?php

namespace App\Http\Controllers;

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
    public function keranjang()
    {
        return view('customer.keranjang');
    }

    /**
     * Display order list page
     */
    public function orderList()
    {
        return view('customer.order-list');
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
}
