<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TblAppointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{


    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        
        // Get statistics
        $totalUsers = User::count();
        $appointmentsToday = TblAppointment::whereDate('date', $today)->count();
        $appointmentsYesterday = TblAppointment::whereDate('date', $yesterday)->count();
        $appointmentChange = $appointmentsToday - $appointmentsYesterday;
        
        $pendingQueues = TblAppointment::whereDate('date', $today)
            ->whereNull('window_num')
            ->whereNull('time_catered')
            ->count();
            
        $completedToday = TblAppointment::whereDate('date', $today)
            ->whereNotNull('time_catered')
            ->count();
            
        $completionRate = $appointmentsToday > 0 
            ? round(($completedToday / $appointmentsToday) * 100, 1) 
            : 0;
            
        $pendingChange = $pendingQueues - TblAppointment::whereDate('date', $yesterday)
            ->whereNull('window_num')
            ->whereNull('time_catered')
            ->count();

        // Get weekly stats for chart
        $weeklyStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $count = TblAppointment::whereDate('date', $date)->count();
            $weeklyStats[] = [
                'day' => $date->format('D'),
                'count' => $count
            ];
        }

        // Get distribution by service type
        $totalByService = TblAppointment::whereDate('date', $today)
            ->select('queue_for', DB::raw('count(*) as total'))
            ->groupBy('queue_for')
            ->get();
            
        $total = $totalByService->sum('total') ?: 1; // Prevent division by zero
        
        $distribution = [
            'status' => round(($totalByService->where('queue_for', 'Status Inquiry')->sum('total') / $total) * 100),
            'registration' => round(($totalByService->where('queue_for', 'NID Registration')->sum('total') / $total) * 100),
            'updating' => round(($totalByService->where('queue_for', 'Updating')->sum('total') / $total) * 100),
            'other' => 0
        ];
        
        $distribution['other'] = 100 - ($distribution['status'] + $distribution['registration'] + $distribution['updating']);

        // Get recent users
        $recentUsers = User::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // User growth (last 7 days vs previous 7 days)
        $lastWeek = User::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        $previousWeek = User::whereBetween('created_at', [Carbon::now()->subDays(14), Carbon::now()->subDays(7)])->count();
        $userGrowth = $previousWeek > 0 ? round((($lastWeek - $previousWeek) / $previousWeek) * 100) : 0;

        return view('admin.dashboard', compact(
            'totalUsers',
            'appointmentsToday',
            'pendingQueues',
            'completedToday',
            'recentUsers',
            'weeklyStats',
            'distribution',
            'userGrowth',
            'appointmentChange',
            'pendingChange',
            'completionRate'
        ));
    }

    /**
     * Get notifications for the admin.
     */
    /**
 * Get notifications for the admin.
 */
public function getNotifications()
{
    try {
        $pendingCount = TblAppointment::whereNull('window_num')
            ->whereNull('time_catered')
            ->count();
            
        $notifications = [];
        
        if ($pendingCount > 10) {
            $notifications[] = [
                'message' => "High queue volume: {$pendingCount} pending appointments",
                'time' => 'Just now',
                'type' => 'warning'
            ];
        }
        
        // Check for system alerts
        $inactiveUsers = User::where('last_login_at', '<', Carbon::now()->subDays(7))
            ->where('status', 'active')
            ->count();
            
        if ($inactiveUsers > 5) {
            $notifications[] = [
                'message' => "{$inactiveUsers} users haven't logged in for over a week",
                'time' => '5 minutes ago',
                'type' => 'info'
            ];
        }
        
        // Get today's appointments
        $todayAppointments = TblAppointment::whereDate('date', Carbon::today())->count();
        if ($todayAppointments > 50) {
            $notifications[] = [
                'message' => "High volume today: {$todayAppointments} appointments",
                'time' => 'Just now',
                'type' => 'info'
            ];
        }
        
        return response()->json($notifications);
        
    } catch (\Exception $e) {
        \Log::error('Notifications error: ' . $e->getMessage());
        return response()->json([], 200);
    }
}

    /**
 * Global search functionality.
 */
public function search(Request $request)
{
    try {
        $query = $request->get('q');
        
        if (strlen($query) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Search query too short'
            ], 400);
        }
        
        $users = User::where('name', 'like', "%{$query}%")
            ->orWhere('username', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->limit(5)
            ->get(['id', 'name', 'username', 'designation']);
            
        $appointments = TblAppointment::where('q_id', 'like', "%{$query}%")
            ->orWhere('fname', 'like', "%{$query}%")
            ->orWhere('lname', 'like', "%{$query}%")
            ->orWhere('trn', 'like', "%{$query}%")
            ->limit(5)
            ->get(['n_id', 'q_id', 'fname', 'lname', 'queue_for']);
            
        return response()->json([
            'success' => true,
            'data' => [
                'users' => $users,
                'appointments' => $appointments
            ]
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Search error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Search failed'
        ], 500);
    }
}

    /**
     * Get quick stats for the dashboard.
     */
    /**
 * Get quick stats for the dashboard.
 */
public function getQuickStats()
{
    try {
        $today = Carbon::today();
        
        $stats = [
            'total_users' => User::count(),
            'active_today' => User::where('last_login_at', '>=', $today)->count(),
            'appointments_today' => TblAppointment::whereDate('date', $today)->count(),
            'completed_today' => TblAppointment::whereDate('date', $today)->whereNotNull('time_catered')->count(),
            'pending_today' => TblAppointment::whereDate('date', $today)->whereNull('time_catered')->count(),
            'windows_active' => User::whereNotNull('window_num')->where('status', 'active')->count(),
        ];
        
        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Quick stats error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to load stats'
        ], 500);
    }
}
}