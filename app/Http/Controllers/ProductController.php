<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function allProducts()
    {
        $products = Product::paginate(12);
        return view('customer.all-product', compact('products'));
    }

    public function detail(Request $request)
    {
        // Try to get product from database first
        if ($request->has('id')) {
            try {
                $product = Product::findOrFail($request->id);
                
                // Increment views
                $product->incrementViews();
                
                // Format for view
                $productData = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->formatted_price,
                    'original_price' => $product->original_price ? number_format($product->original_price, 0, ',', '.') : null,
                    'image' => $product->image ? asset('storage/' . $product->image) : $request->query('image', 'https://via.placeholder.com/400'),
                    // Provide gallery/images when available
                    'gallery' => $product->images ?? [],
                    'description' => $product->description ?: 'This product is crafted with superior quality and attention to detail.',
                    'colors' => $product->colors ?: [],
                    'sizes' => $product->sizes ?: [],
                    'stock' => $product->stock,
                    'category' => $product->category,
                    // Expose flag whether custom design is allowed for this product
                    'custom_design_allowed' => (bool) ($product->custom_design_allowed ?? false),
                ];

                // Ambil rekomendasi produk (produk lain dari kategori yang sama)
                $recommendations = Product::where('category', $product->category)
                    ->where('id', '!=', $product->id)
                    ->limit(4)
                    ->get()
                    ->map(function ($rec) {
                        return [
                            'id' => $rec->id,
                            'name' => $rec->name,
                            'price' => $rec->formatted_price,
                            'image' => $rec->image ? asset('storage/' . $rec->image) : 'https://via.placeholder.com/300',
                            'custom_design_allowed' => (bool) $rec->custom_design_allowed,
                        ];
                    });

                return view('pages.product-detail', ['product' => $productData, 'recommendations' => $recommendations]);
            } catch (\Exception $e) {
                // Fall back to query parameters if product not found
            }
        }
        
        // Fallback: use query parameters (backward compatibility)
        $product = [
            'id' => $request->query('id', 1),
            'name' => $request->query('name', 'ONE LIFE GRAPHIC T-SHIRT'),
            'price' => $request->query('price', '70.000'),
            'image' => $request->query('image', 'https://i.pinimg.com/1200x/3e/6b/f5/3e6bf5378b6ae4d43263dfb626d37588.jpg'),
            'description' => 'This graphic t-shirt which is perfect for any occasion. Crafted from a soft and breathable fabric, it offers superior comfort and style.',
            'colors' => ['#4A5F4A', '#2C3E50', '#1A1A1A'],
            'sizes' => ['Small', 'Medium', 'Large', 'X-Large'],
            'stock' => 0,
            'category' => 'kaos',
            'custom_design_allowed' => false,
        ];

        // Ambil rekomendasi produk untuk fallback (produk populer)
        $recommendations = Product::limit(4)->get()->map(function ($rec) {
            return [
                'id' => $rec->id,
                'name' => $rec->name,
                'price' => $rec->formatted_price,
                'image' => $rec->image ? asset('storage/' . $rec->image) : 'https://via.placeholder.com/300',
                'custom_design_allowed' => (bool) $rec->custom_design_allowed,
            ];
        });

        return view('pages.product-detail', compact('product', 'recommendations'));
    }
}
