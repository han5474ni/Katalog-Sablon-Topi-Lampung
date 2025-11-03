<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomDesignOrder;
use App\Models\Order;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class OrderManagementController extends Controller
{
    /**
     * Display order list page
     */
    public function index(Request $request)
    {
        $orderType = $request->get('type', 'regular'); // 'regular' or 'custom'

        if ($orderType === 'custom') {
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
            $viewData = ['orders' => $orders, 'orderType' => 'custom'];
        } else {
            $query = Order::with(['user'])
                ->orderBy('created_at', 'desc');

            // Search
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('id', 'like', "%{$search}%")
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
            $viewData = ['orders' => $orders, 'orderType' => 'regular'];
        }

        return view('admin.management-order', $viewData);
    }

    /**
     * Approve an order
     */
    public function approve(Request $request, $id)
    {
        $orderType = $request->get('type', 'regular');

        if ($orderType === 'custom') {
            $order = CustomDesignOrder::findOrFail($id);
        } else {
            $order = Order::findOrFail($id);
        }

        $order->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Pesanan berhasil disetujui.');
    }

    /**
     * Reject an order with reason
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $orderType = $request->get('type', 'regular');

        if ($orderType === 'custom') {
            $order = CustomDesignOrder::findOrFail($id);
        } else {
            $order = Order::findOrFail($id);
        }

        $order->update([
            'status' => 'rejected',
            'admin_notes' => $request->reason,
            'rejected_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Pesanan berhasil ditolak.');
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,processing,completed,cancelled',
        ]);

        $orderType = $request->get('type', 'regular');

        if ($orderType === 'custom') {
            $order = CustomDesignOrder::findOrFail($id);
        } else {
            $order = Order::findOrFail($id);
        }

        $updateData = ['status' => $request->status];

        if ($request->status === 'approved') {
            $updateData['approved_at'] = now();
        } elseif ($request->status === 'rejected') {
            $updateData['rejected_at'] = now();
        }

        $order->update($updateData);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    /**
     * Get order detail for modal (legacy)
     */
    public function getDetail(Request $request, $id)
    {
        $orderType = $request->get('type', 'regular');

        if ($orderType === 'custom') {
            $order = CustomDesignOrder::with(['user', 'product', 'uploads'])->findOrFail($id);
            $uploads = $order->uploads ?? [];
        } else {
            $order = Order::with(['user'])->findOrFail($id);
            $uploads = [];

            // Add status_label to the order object
            $order->status_label = $order->status_label;
        }

        return response()->json([
            'order' => $order,
            'orderType' => $orderType,
            'uploads' => $uploads,
        ]);
    }

    /**
     * Show order detail page
     */
    public function showDetail(Request $request, $id)
    {
        $orderType = $request->get('type', 'regular');

        if ($orderType === 'custom') {
            $order = CustomDesignOrder::with(['user', 'product', 'uploads'])->findOrFail($id);
            $uploads = $order->uploads ?? [];
        } else {
            $order = Order::with(['user'])->findOrFail($id);
            $uploads = [];
        }

        return view('admin.order-detail', compact('order', 'orderType', 'uploads'));
    }

    /**
     * Export orders to Excel
     */
    public function export(Request $request)
    {
        $orderType = $request->get('type', 'regular');

        if ($orderType === 'custom') {
            return Excel::download(
                new \App\Exports\CustomDesignOrderExport(),
                'custom_orders_' . date('Y-m-d_His') . '.xlsx'
            );
        } else {
            // TODO: Create OrderExport class
            return Excel::download(
                new \App\Exports\OrderExport(),
                'orders_' . date('Y-m-d_His') . '.xlsx'
            );
        }
    }
}
