<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProductManagementController extends Controller
{
    /**
     * Display the product management page
     */
    public function index()
    {
        return view('admin.management-product');
    }

    /**
     * Display all products in grid view
     */
    public function allProducts()
    {
        return view('admin.all-products');
    }

    /**
     * Get all products with filters (for AJAX)
     */
    public function getProducts(Request $request)
    {
        try {
            $query = Product::query();

            // Filter by search query
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('slug', 'like', "%{$search}%");
                });
            }

            // Filter by status (tab)
            if ($request->filled('status') && $request->status !== 'ALL') {
                switch ($request->status) {
                    case 'ACTIVE':
                        $query->where('is_active', true);
                        break;
                    case 'DRAFT':
                    case 'ARCHIVED':
                        $query->where('is_active', false);
                        break;
                    case 'READY':
                        $query->where('stock', '>', 0);
                        break;
                    case 'HABIS':
                        $query->where('stock', '=', 0);
                        break;
                }
            }

            // Filter by category
            if ($request->filled('category')) {
                $query->where('category', $request->category);
            }

            // Sorting
            $sortBy = $request->get('sortBy', 'created_at');
            $sortOrder = $request->get('sortOrder', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
            $perPage = $request->get('perPage', 10);
            $products = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $products->items(),
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                    'from' => $products->firstItem(),
                    'to' => $products->lastItem(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch products: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single product by ID
     */
    public function show($id)
    {
        try {
            $product = Product::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
    }

    /**
     * Store a new product
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|in:topi,kaos,sablon,jaket,jersey,tas',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'subcategory' => 'nullable|string|max:255',
            'original_price' => 'nullable|numeric|min:0',
            'colors' => 'nullable|string', // JSON string from frontend
            'sizes' => 'nullable|string',  // JSON string from frontend
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Prepare data
            $data = [
                'name' => $request->name,
                'category' => $request->category,
                'price' => $request->price,
                'stock' => $request->stock,
                'description' => $request->description,
                'subcategory' => $request->subcategory,
                'original_price' => $request->original_price,
                'is_active' => $request->get('is_active', true),
                'custom_design_allowed' => $request->get('custom_design_allowed', false),
            ];
            
            // Generate slug from name
            $data['slug'] = Str::slug($request->name);
            
            // Ensure unique slug
            $originalSlug = $data['slug'];
            $count = 1;
            while (Product::where('slug', $data['slug'])->exists()) {
                $data['slug'] = $originalSlug . '-' . $count;
                $count++;
            }

            // Parse colors from JSON string
            if ($request->filled('colors')) {
                $colors = $request->colors;
                $data['colors'] = is_string($colors) ? json_decode($colors, true) : $colors;
            }

            // Parse sizes from JSON string
            if ($request->filled('sizes')) {
                $sizes = $request->sizes;
                $data['sizes'] = is_string($sizes) ? json_decode($sizes, true) : $sizes;
            }

            // Handle image upload
            if ($request->hasFile('images')) {
                $imagePaths = [];
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');
                    $imagePaths[] = $path;
                }
                $data['images'] = $imagePaths;
                $data['image'] = $imagePaths[0] ?? null; // First image as main
            }

            // Auto-disable if stock is 0 (Habis)
            if ($data['stock'] == 0) {
                $data['is_active'] = false;
            }

            $product = Product::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully',
                'data' => $product
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create product: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update existing product
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|in:topi,kaos,sablon,jaket,jersey,tas',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'subcategory' => 'nullable|string|max:255',
            'original_price' => 'nullable|numeric|min:0',
            'colors' => 'nullable|string', // JSON string from frontend
            'sizes' => 'nullable|string',  // JSON string from frontend
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $product = Product::findOrFail($id);
            
            // Prepare data
            $data = [
                'name' => $request->name,
                'category' => $request->category,
                'price' => $request->price,
                'stock' => $request->stock,
                'description' => $request->description,
                'subcategory' => $request->subcategory,
                'original_price' => $request->original_price,
                'is_active' => $request->get('is_active', $product->is_active),
                'custom_design_allowed' => $request->get('custom_design_allowed', $product->custom_design_allowed ?? false),
            ];

            // Update slug if name changed
            if ($request->name !== $product->name) {
                $data['slug'] = Str::slug($request->name);
                
                // Ensure unique slug
                $originalSlug = $data['slug'];
                $count = 1;
                while (Product::where('slug', $data['slug'])->where('id', '!=', $id)->exists()) {
                    $data['slug'] = $originalSlug . '-' . $count;
                    $count++;
                }
            }

            // Parse colors from JSON string
            if ($request->filled('colors')) {
                $colors = $request->colors;
                $data['colors'] = is_string($colors) ? json_decode($colors, true) : $colors;
            }

            // Parse sizes from JSON string
            if ($request->filled('sizes')) {
                $sizes = $request->sizes;
                $data['sizes'] = is_string($sizes) ? json_decode($sizes, true) : $sizes;
            }

            // Handle new image uploads
            if ($request->hasFile('images')) {
                // Delete old images
                if ($product->images) {
                    foreach ($product->images as $oldImage) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }

                $imagePaths = [];
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');
                    $imagePaths[] = $path;
                }
                $data['images'] = $imagePaths;
                $data['image'] = $imagePaths[0] ?? null;
            }

            // Auto-disable if stock is 0 (Habis)
            if ($data['stock'] == 0) {
                $data['is_active'] = false;
            }

            $product->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
                'data' => $product->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a product
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            
            // Delete associated images
            if ($product->images) {
                foreach ($product->images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }

            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle product status (Active/Draft)
     */
    public function toggleStatus($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->is_active = !$product->is_active;
            $product->save();

            return response()->json([
                'success' => true,
                'message' => 'Product status updated successfully',
                'data' => [
                    'id' => $product->id,
                    'is_active' => $product->is_active
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk archive products
     */
    public function bulkArchive(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:products,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $updated = Product::whereIn('id', $request->ids)
                ->update(['is_active' => false]);

            return response()->json([
                'success' => true,
                'message' => "{$updated} product(s) archived successfully",
                'count' => $updated
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to archive products: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export products to CSV
     */
    public function export(Request $request)
    {
        try {
            $query = Product::query();

            // Apply same filters as getProducts
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('slug', 'like', "%{$search}%");
                });
            }

            if ($request->filled('status') && $request->status !== 'ALL') {
                switch ($request->status) {
                    case 'ACTIVE':
                        $query->where('is_active', true);
                        break;
                    case 'DRAFT':
                    case 'ARCHIVED':
                        $query->where('is_active', false);
                        break;
                    case 'READY':
                        $query->where('stock', '>', 0);
                        break;
                    case 'HABIS':
                        $query->where('stock', '=', 0);
                        break;
                }
            }

            if ($request->filled('category')) {
                $query->where('category', $request->category);
            }

            $products = $query->orderBy('created_at', 'desc')->get();

            // Generate CSV
            $filename = 'products_' . date('Y-m-d_His') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($products) {
                $file = fopen('php://output', 'w');
                
                // CSV Headers
                fputcsv($file, [
                    'ID',
                    'Name',
                    'Slug',
                    'Category',
                    'Subcategory',
                    'Price',
                    'Original Price',
                    'Stock',
                    'Status',
                    'Colors',
                    'Sizes',
                    'Created At'
                ]);

                // CSV Data
                foreach ($products as $product) {
                    fputcsv($file, [
                        $product->id,
                        $product->name,
                        $product->slug,
                        $product->category,
                        $product->subcategory,
                        $product->price,
                        $product->original_price,
                        $product->stock,
                        $product->is_active ? 'Active' : 'Draft',
                        is_array($product->colors) ? implode(', ', $product->colors) : '',
                        is_array($product->sizes) ? implode(', ', $product->sizes) : '',
                        $product->created_at->format('Y-m-d H:i:s')
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export products: ' . $e->getMessage()
            ], 500);
        }
    }
}
