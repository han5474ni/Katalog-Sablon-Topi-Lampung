<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        // Get NEW ARRIVALS - Latest products added (ready: active + stock > 0)
        $newArrivals = Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->latest('created_at')
            ->take(8)
            ->get();

        // Get TOP SELLING - Best selling products
        $topSelling = Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->orderBy('sales', 'desc')
            ->orderBy('views', 'desc')
            ->take(8)
            ->get();

        // Get products by category (for homepage sections)
        $topiProducts = Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->where('category', 'topi')
            ->latest()
            ->take(4)
            ->get();

        $kaosProducts = Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->where('category', 'kaos')
            ->latest()
            ->take(4)
            ->get();

        return view('pages.home', compact('newArrivals', 'topSelling', 'topiProducts', 'kaosProducts'));
    }
}
