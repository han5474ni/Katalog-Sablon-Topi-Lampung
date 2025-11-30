<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductVariant;

class DashboardController
{
    /**
     * Display the admin dashboard with dynamic data
     */
    public function index()
    {
        // Calculate statistics
        $stats = [
            'total_sold' => Order::where('status', 'completed')->count(),
            'revenue' => Order::where('status', 'completed')->sum('total') ?? 0,
            'customers' => User::count(), // Count all registered users (customers)
            'pending_orders' => Order::where('status', 'pending')->count(),
        ];

        // Trend calculation (compared to previous month)
        $currentMonth = now()->startOfMonth();
        $previousMonth = now()->subMonth()->startOfMonth();
        
        $currentMonthSold = Order::where('status', 'completed')
            ->whereBetween('completed_at', [$currentMonth, now()])
            ->count();
        
        $previousMonthSold = Order::where('status', 'completed')
            ->whereBetween('completed_at', [$previousMonth, $previousMonth->copy()->endOfMonth()])
            ->count();

        $soldTrend = $previousMonthSold > 0 
            ? round((($currentMonthSold - $previousMonthSold) / $previousMonthSold) * 100, 1)
            : 0;

        // Top products (by order count)
        $topProducts = Product::select('products.id', 'products.name')
            ->leftJoin('product_variants', 'products.id', '=', 'product_variants.product_id')
            ->leftJoin('orders', function($join) {
                $join->on('product_variants.id', '=', DB::raw('JSON_EXTRACT(orders.items, "$[*].variant_id")'))
                    ->orWhereRaw('orders.items LIKE CONCAT("%\"", product_variants.id, "%")');
            })
            ->groupBy('products.id', 'products.name')
            ->selectRaw('COUNT(orders.id) as order_count, COUNT(DISTINCT product_variants.id) as variant_count')
            ->orderByDesc('order_count')
            ->limit(5)
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'order_count' => $product->order_count ?? 0,
                    'variant_count' => $product->variant_count ?? 0
                ];
            });

        // Recent orders with customer details
        $recentOrders = Order::with('user')
            ->latest('created_at')
            ->limit(10)
            ->get();

        // Order status breakdown
        $orderStatusBreakdown = Order::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        // Sales data for chart (last 6 months)
        $sixMonthsAgo = now()->subMonths(6);
        $salesByMonth = Order::where('status', 'completed')
            ->whereBetween('created_at', [$sixMonthsAgo, now()])
            ->get()
            ->groupBy(function($order) {
                return $order->created_at->format('Y-m');
            })
            ->map(function($group) {
                return [
                    'month' => $group->first()->created_at->format('M'),
                    'count' => $group->count(),
                    'revenue' => $group->sum('total')
                ];
            })
            ->values();

        return view('admin.dashboard', [
            'stats' => $stats,
            'soldTrend' => $soldTrend,
            'topProducts' => $topProducts,
            'recentOrders' => $recentOrders,
            'orderStatusBreakdown' => $orderStatusBreakdown,
            'salesByMonth' => $salesByMonth,
        ]);
    }

    /**
     * Get dashboard stats via API (for real-time updates)
     */
    public function getStats(Request $request)
    {
        $stats = [
            'total_sold' => Order::where('status', 'completed')->count(),
            'revenue' => Order::where('status', 'completed')->sum('total') ?? 0,
            'customers' => User::count(), // Count all registered users (customers)
            'pending_orders' => Order::where('status', 'pending')->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Get sales chart data via API
     */
    public function getSalesData(Request $request)
    {
        $months = $request->query('months', 6);

        $salesData = Order::where('status', 'completed')
            ->whereBetween('created_at', [now()->subMonths($months), now()])
            ->get()
            ->groupBy(function($order) {
                return $order->created_at->format('M');
            })
            ->map(function($group, $label) {
                return [
                    'label' => $label,
                    'sales' => $group->count(),
                    'revenue' => (int) $group->sum('total')
                ];
            })
            ->values()
            ->sortBy('label');

        return response()->json([
            'labels' => $salesData->pluck('label')->toArray(),
            'datasets' => [
                [
                    'label' => 'Penjualan',
                    'data' => $salesData->pluck('sales')->toArray(),
                    'borderColor' => '#0a1d37',
                    'backgroundColor' => 'rgba(10, 29, 55, 0.05)',
                ]
            ]
        ]);
    }

    /**
     * Get recent orders via API
     */
    public function getRecentOrders(Request $request)
    {
        $limit = $request->query('limit', 10);

        $orders = Order::with('user')
            ->latest('created_at')
            ->limit($limit)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->order_number,
                    'product' => $order->items[0]['name'] ?? 'N/A',
                    'customer' => $order->user->name,
                    'date' => $order->created_at->format('M d, Y'),
                    'status' => $order->status,
                    'amount' => 'Rp ' . number_format((float) $order->total, 0, ',', '.'),
                ];
            });

        return response()->json($orders);
    }

    /**
     * Get top products via API (by order count)
     */
    public function getTopProducts(Request $request)
    {
        $limit = $request->query('limit', 5);

        $products = Product::select('products.id', 'products.name')
            ->leftJoin('product_variants', 'products.id', '=', 'product_variants.product_id')
            ->leftJoin('orders', function($join) {
                $join->on('product_variants.id', '=', DB::raw('JSON_EXTRACT(orders.items, "$[*].variant_id")'))
                    ->orWhereRaw('orders.items LIKE CONCAT("%\"", product_variants.id, "%")');
            })
            ->groupBy('products.id', 'products.name')
            ->selectRaw('COUNT(orders.id) as order_count, COUNT(DISTINCT product_variants.id) as variant_count')
            ->orderByDesc('order_count')
            ->limit($limit)
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'order_count' => $product->order_count ?? 0,
                    'variant_count' => $product->variant_count ?? 0
                ];
            });

        return response()->json($products);
    }
}
