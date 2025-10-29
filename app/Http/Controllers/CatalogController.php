<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CatalogController extends Controller
{
    // Categories mapping
    private $categories = [
        'jersey' => 'Jersey',
        'topi' => 'Topi',
        'kaos' => 'Kaos',
        'polo' => 'Polo',
        'celana' => 'Celana',
        //'sablon' => 'Sablon',
        'jaket' => 'Jaket',
        //'tas' => 'Tas'
    ];

    public function index(Request $request, $category)
    {
        // Validate category
        if (!array_key_exists($category, $this->categories)) {
            abort(404);
        }

        $categoryName = $this->categories[$category];
        
        // Start building query - Get ALL active products (not limited by stock)
        $query = Product::active()->category($category);

        // Apply search filter
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Apply subcategory filter
        if ($request->filled('subcategory')) {
            $query->subcategory($request->subcategory);
        }

        // Apply color filter
        if ($request->filled('colors')) {
            $colors = is_array($request->colors) ? $request->colors : explode(',', $request->colors);
            $query->filterByColors($colors);
        }

        // Apply size filter
        if ($request->filled('sizes')) {
            $sizes = is_array($request->sizes) ? $request->sizes : explode(',', $request->sizes);
            $query->filterBySizes($sizes);
        }

        // Apply price range filter
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $query->priceRange($request->min_price, $request->max_price);
        }

        // Apply sorting
        $sort = $request->get('sort', 'most_popular');
        $query->sortBy($sort);

        // Get total count before pagination
        $totalProducts = $query->count();

        // Paginate results
        $perPage = $request->get('per_page', 9);
        $products = $query->paginate($perPage)->appends($request->except('page'));

        // Get available filters for sidebar
        $availableColors = $this->getAvailableColors($category);
        $availableSizes = $this->getAvailableSizes($category);
        $availableSubcategories = $this->getAvailableSubcategories($category);

        // If AJAX request, return JSON
        if ($request->ajax()) {
            return response()->json([
                'products' => $products->items(),
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                    'from' => $products->firstItem(),
                    'to' => $products->lastItem(),
                ],
                'total_products' => $totalProducts,
            ]);
        }

        return view('pages.catalog', [
            'category' => $category,
            'categoryName' => $categoryName,
            'products' => $products,
            'totalProducts' => $totalProducts,
            'availableColors' => $availableColors,
            'availableSizes' => $availableSizes,
            'availableSubcategories' => $availableSubcategories,
            'currentFilters' => [
                'search' => $request->search,
                'subcategory' => $request->subcategory,
                'colors' => $request->colors,
                'sizes' => $request->sizes,
                'sort' => $sort,
            ],
        ]);
    }

    private function getAvailableColors($category)
    {
        $products = Product::active()->category($category)->get();
        $colors = [];
        
        foreach ($products as $product) {
            if ($product->colors) {
                $colors = array_merge($colors, $product->colors);
            }
        }
        
        return array_unique($colors);
    }

    private function getAvailableSizes($category)
    {
        $products = Product::active()->category($category)->get();
        $sizes = [];
        
        foreach ($products as $product) {
            if ($product->sizes) {
                $sizes = array_merge($sizes, $product->sizes);
            }
        }
        
        return array_unique($sizes);
    }

    private function getAvailableSubcategories($category)
    {
        return Product::active()
            ->category($category)
            ->whereNotNull('subcategory')
            ->distinct()
            ->pluck('subcategory')
            ->toArray();
    }
}
