<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Exports\ActivityLogsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ActivityLogController extends Controller
{
    /**
     * Display activity logs
     */
    public function index(Request $request)
    {
        $query = ActivityLog::orderBy('created_at', 'desc');

        // Filter by user type and user id
        if ($request->has('user_type') && $request->has('user_id')) {
            $userType = $request->user_type === 'admin' ? 'App\\Models\\Admin' : 'App\\Models\\User';
            $userId = $request->user_id;
            
            $query->where('user_type', $userType)
                  ->where('user_id', $userId);
        }

        // Filter by action type
        if ($request->has('action')) {
            $query->where('action', $request->action);
        }

        // Filter by date range
        if ($request->has('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->has('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $logs = $query->paginate(20);

        return view('admin.activity-logs', compact('logs'));
    }

    /**
     * Export activity logs to Excel
     */
    public function export(Request $request)
    {
        $userType = $request->get('type');
        $filename = 'activity-logs-' . date('Y-m-d-His') . '.xlsx';
        
        return Excel::download(new ActivityLogsExport($userType), $filename);
    }
}
