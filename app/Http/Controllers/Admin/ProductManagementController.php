<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

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
            'images.*' => 'image|mimes:jpeg,jpg,png,webp|max:10240', // Naikkan limit ke 10MB, akan dikompres otomatis
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

            // === HANDLE IMAGE UPLOAD DENGAN KOMPRESI ===
            if ($request->hasFile('images')) {
                $imagePaths = [];
                
                // Loop setiap gambar yang diupload
                foreach ($request->file('images') as $image) {
                    // 🎯 PANGGIL METHOD KOMPRESI
                    // Method ini akan:
                    // 1. Resize jika terlalu besar
                    // 2. Kompres dengan quality 85%
                    // 3. Convert ke WebP (lebih kecil 30-50%)
                    // 4. Return path file yang sudah dikompresi
                    $path = $this->compressAndStoreImage($image, 'products');
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
            'images.*' => 'image|mimes:jpeg,jpg,png,webp|max:10240', // Naikkan limit ke 10MB, akan dikompres otomatis
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

            // === HANDLE NEW IMAGE UPLOADS DENGAN KOMPRESI ===
            if ($request->hasFile('images')) {
                // Delete old images
                if ($product->images) {
                    foreach ($product->images as $oldImage) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }

                $imagePaths = [];
                
                // Loop setiap gambar baru yang diupload
                foreach ($request->file('images') as $image) {
                    // 🎯 PANGGIL METHOD KOMPRESI
                    $path = $this->compressAndStoreImage($image, 'products');
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

    /**
     * 🎯 HELPER METHOD: Compress and optimize uploaded image
     * 
     * LOGIKA KOMPRESI AGRESIF:
     * 1. Baca file yang diupload
     * 2. Cek dimensi dan resize proportional
     * 3. Kompres dengan quality dinamis hingga ukuran < 2MB
     * 4. Convert ke WebP untuk ukuran lebih kecil
     * 5. Jika masih > 2MB, turunkan quality secara bertahap
     * 6. Simpan ke storage
     * 
     * @param \Illuminate\Http\UploadedFile $file - File yang diupload
     * @param string $directory - Folder tujuan (default: 'products')
     * @return string - Path file yang sudah dikompresi
     */
    private function compressAndStoreImage($file, $directory = 'products')
    {
        // === KONFIGURASI ===
        $maxWidth = 1500;         // Lebar maksimal dalam pixel
        $maxHeight = 1500;        // Tinggi maksimal dalam pixel
        $targetSizeKB = 2048;     // Target ukuran maksimal 2MB (2048 KB)
        $initialQuality = 85;     // Quality awal
        $minQuality = 50;         // Quality minimal (jangan terlalu rendah agar tetap bagus)
        $convertToWebp = true;    // Set false jika tidak mau convert ke WebP
        
        try {
            // === STEP 1: Initialize ImageManager dengan GD driver ===
            $manager = new ImageManager(new Driver());
            
            // === STEP 2: Load gambar ===
            $image = $manager->read($file);
            
            // === STEP 3: Cek dimensi gambar ===
            $width = $image->width();
            $height = $image->height();
            
            // === STEP 4: Resize jika melebihi batas ===
            if ($width > $maxWidth || $height > $maxHeight) {
                $image->scale(width: $maxWidth, height: $maxHeight);
            }
            
            // === STEP 5: Generate nama file unik ===
            $filename = Str::random(40);
            
            // === STEP 6: Kompresi dengan quality dinamis hingga ukuran < 2MB ===
            $quality = $initialQuality;
            $encodedImage = null;
            $attempts = 0;
            $maxAttempts = 10; // Maksimal 10 percobaan
            
            do {
                $attempts++;
                
                if ($convertToWebp) {
                    $filename = str_replace(['.jpg', '.jpeg', '.png', '.webp'], '', $filename) . '.webp';
                    $encodedImage = $image->toWebp($quality);
                } else {
                    $extension = $file->getClientOriginalExtension();
                    $filename = str_replace(['.jpg', '.jpeg', '.png', '.webp'], '', $filename) . '.' . $extension;
                    
                    if (in_array($extension, ['jpg', 'jpeg'])) {
                        $encodedImage = $image->toJpeg($quality);
                    } elseif ($extension === 'png') {
                        // PNG tidak support quality, convert ke JPG jika terlalu besar
                        if ($attempts > 1) {
                            $filename = str_replace('.png', '.jpg', $filename);
                            $encodedImage = $image->toJpeg($quality);
                        } else {
                            $encodedImage = $image->toPng();
                        }
                    } elseif ($extension === 'webp') {
                        $encodedImage = $image->toWebp($quality);
                    } else {
                        $encodedImage = $image->toJpeg($quality);
                    }
                }
                
                // Cek ukuran hasil kompresi
                $sizeKB = strlen($encodedImage) / 1024; // Convert bytes ke KB
                
                // Jika ukuran sudah < 2MB, selesai
                if ($sizeKB <= $targetSizeKB) {
                    break;
                }
                
                // Jika masih terlalu besar, turunkan quality
                // Rumus: turunkan 5-10 poin per iterasi tergantung seberapa besar file
                if ($sizeKB > $targetSizeKB * 2) {
                    $quality -= 15; // File sangat besar, turunkan drastis
                } elseif ($sizeKB > $targetSizeKB * 1.5) {
                    $quality -= 10; // File cukup besar
                } else {
                    $quality -= 5;  // File sedikit besar
                }
                
                // Pastikan quality tidak di bawah minimum
                if ($quality < $minQuality) {
                    $quality = $minQuality;
                }
                
                // Jika sudah di quality minimal tapi masih besar, resize lebih kecil lagi
                if ($quality === $minQuality && $sizeKB > $targetSizeKB) {
                    $maxWidth = (int)($maxWidth * 0.8); // Kurangi 20%
                    $maxHeight = (int)($maxHeight * 0.8);
                    $image->scale(width: $maxWidth, height: $maxHeight);
                }
                
            } while ($sizeKB > $targetSizeKB && $attempts < $maxAttempts);
            
            // === STEP 7: Simpan ke storage ===
            $path = $directory . '/' . $filename;
            Storage::disk('public')->put($path, (string) $encodedImage);
            
            // Log info untuk monitoring
            $finalSizeKB = round(strlen($encodedImage) / 1024, 2);
            \Log::info("Image compressed: {$file->getClientOriginalName()} | Original: " . 
                      round($file->getSize() / 1024, 2) . "KB | Compressed: {$finalSizeKB}KB | Quality: {$quality}");
            
            // === STEP 8: Return path untuk disimpan ke database ===
            return $path;
            
        } catch (\Exception $e) {
            // Jika kompresi gagal, fallback ke upload biasa
            \Log::error('Image compression failed: ' . $e->getMessage());
            return $file->store($directory, 'public');
        }
    }
}
