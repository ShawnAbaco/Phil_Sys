<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ActivityLog; // You'll need to create this model
use Carbon\Carbon;

class LogController extends AdminController
{


    /**
     * Display activity logs.
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user');
        
        // Apply user filter
        if ($request->has('user') && !empty($request->user)) {
            $query->where('user_id', $request->user);
        }
        
        // Apply action filter
        if ($request->has('action') && !empty($request->action)) {
            $query->where('action', $request->action);
        }
        
        // Apply date filter
        if ($request->has('date') && !empty($request->date)) {
            switch ($request->date) {
                case 'today':
                    $query->whereDate('created_at', Carbon::today());
                    break;
                case 'yesterday':
                    $query->whereDate('created_at', Carbon::yesterday());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', Carbon::now()->month)
                          ->whereYear('created_at', Carbon::now()->year);
                    break;
            }
        }
        
        // Apply search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('description', 'like', "%{$search}%");
        }
        
        $logs = $query->orderBy('created_at', 'desc')->paginate(50);
        
        // Get filter options
        $users = User::orderBy('name')->get(['id', 'name']);
        $actions = ActivityLog::distinct()->pluck('action');
        
        return view('admin.logs.index', compact('logs', 'users', 'actions'));
    }

    /**
     * Get log details.
     */
    public function show($id)
    {
        $log = ActivityLog::with('user')->findOrFail($id);
        
        return $this->sendSuccess('Log details retrieved', $log);
    }

    /**
     * Clear old logs.
     */
    public function clearOld(Request $request)
    {
        $request->validate([
            'days' => 'required|integer|min:1|max:365'
        ]);
        
        $cutoffDate = Carbon::now()->subDays($request->days);
        
        $count = ActivityLog::where('created_at', '<', $cutoffDate)->delete();
        
        return $this->sendSuccess("{$count} old log entries cleared");
    }

    /**
     * Export logs.
     */
    public function export(Request $request)
    {
        // This will be handled by ExportController
    }

    /**
     * Get log statistics.
     */
    public function getStats()
    {
        $today = Carbon::today();
        
        $stats = [
            'total_today' => ActivityLog::whereDate('created_at', $today)->count(),
            'total_week' => ActivityLog::whereBetween('created_at', [$today->copy()->startOfWeek(), $today->copy()->endOfWeek()])->count(),
            'total_month' => ActivityLog::whereMonth('created_at', $today->month)->whereYear('created_at', $today->year)->count(),
            'by_action' => ActivityLog::select('action', DB::raw('count(*) as count'))
                ->whereDate('created_at', $today)
                ->groupBy('action')
                ->pluck('count', 'action'),
            'peak_hours' => ActivityLog::select(DB::raw('HOUR(created_at) as hour'), DB::raw('count(*) as count'))
                ->whereDate('created_at', $today)
                ->groupBy('hour')
                ->orderBy('hour')
                ->pluck('count', 'hour'),
        ];
        
        return $this->sendSuccess('Log statistics retrieved', $stats);
    }
}