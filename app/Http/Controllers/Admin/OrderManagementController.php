<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomDesignOrder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class OrderManagementController extends Controller
{
    /**
     * Display order list page
     */
    public function index(Request $request)
    {
        $query = CustomDesignOrder::with(['user', 'product'])
            ->orderBy('created_at', 'desc');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range (last N days)
        if ($request->filled('days')) {
            $days = (int) $request->days;
            $query->whereDate('created_at', '>=', now()->subDays($days));
        }

        $orders = $query->paginate(25);

        return view('admin.management-order', compact('orders'));
    }

    /**
     * Export orders to Excel
     */
    public function export(Request $request)
    {
        // TODO: Implement export
        return Excel::download(
            new \App\Exports\CustomDesignOrderExport(),
            'orders_' . date('Y-m-d_His') . '.xlsx'
        );
    }
}