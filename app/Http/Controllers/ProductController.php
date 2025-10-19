<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
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
                    'description' => $product->description ?: 'This product is crafted with superior quality and attention to detail.',
                    'colors' => $product->colors ?: [],
                    'sizes' => $product->sizes ?: [],
                    'stock' => $product->stock,
                    'category' => $product->category,
                ];
                
                return view('pages.product-detail', ['product' => $productData]);
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
            'category' => 'kaos'
        ];

        return view('pages.product-detail', compact('product'));
    }
}
