<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TblAppointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Display reports overview.
     */
    public function index()
    {
        // Get real stats for dashboard
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();
        
        $stats = [
            'today_total' => TblAppointment::whereDate('date', $today)->count(),
            'today_completed' => TblAppointment::whereDate('date', $today)
                ->where('status', 'completed')
                ->count(),
            'week_total' => TblAppointment::whereBetween('date', [$thisWeek, Carbon::now()])->count(),
            'month_total' => TblAppointment::whereMonth('date', $thisMonth->month)
                ->whereYear('date', $thisMonth->year)
                ->count(),
            'by_service' => TblAppointment::select('queue_for', DB::raw('count(*) as total'))
                ->whereMonth('date', $thisMonth->month)
                ->groupBy('queue_for')
                ->get(),
            'by_priority' => TblAppointment::select('priority_type', DB::raw('count(*) as total'))
                ->whereMonth('date', $thisMonth->month)
                ->groupBy('priority_type')
                ->get(),
        ];
        
        // Get recent reports from session
        $recentReports = session('recent_reports', []);
        
        return view('admin.reports.index', compact('stats', 'recentReports'));
    }

    /**
     * Generate daily report.
     */
    public function daily(Request $request)
    {
        $date = $request->get('date') ? Carbon::parse($request->date) : Carbon::today();
        
        // Get appointments for the selected date
        $appointments = TblAppointment::whereDate('date', $date)
            ->orderBy('time_catered', 'desc')
            ->paginate(20);
            
        // Calculate real statistics
        $stats = [
            'total' => TblAppointment::whereDate('date', $date)->count(),
            'completed' => TblAppointment::whereDate('date', $date)->where('status', 'completed')->count(),
            'cancelled' => TblAppointment::whereDate('date', $date)->where('status', 'cancelled')->count(),
            'no_show' => TblAppointment::whereDate('date', $date)->where('status', 'no_show')->count(),
            'serving' => TblAppointment::whereDate('date', $date)->where('status', 'serving')->count(),
            'pending' => TblAppointment::whereDate('date', $date)->where('status', 'pending')->count(),
            'by_service' => TblAppointment::whereDate('date', $date)
                ->select('queue_for', DB::raw('count(*) as total'))
                ->groupBy('queue_for')
                ->get(),
            'by_priority' => TblAppointment::whereDate('date', $date)
                ->select('priority_type', DB::raw('count(*) as total'))
                ->groupBy('priority_type')
                ->get(),
            'by_window' => TblAppointment::whereDate('date', $date)
                ->whereNotNull('window_num')
                ->where('window_num', '!=', '0')
                ->select('window_num', DB::raw('count(*) as total'))
                ->groupBy('window_num')
                ->get(),
        ];
        
        return view('admin.reports.show', [
            'reportName' => 'Daily Report - ' . $date->format('F d, Y'),
            'reportType' => 'daily',
            'date' => $date,
            'appointments' => $appointments,
            'stats' => $stats,
            'totalAppointments' => $stats['total'],
            'completedAppointments' => $stats['completed'],
            'pendingAppointments' => $stats['pending'],
            'issuesCount' => $stats['cancelled'] + $stats['no_show']
        ]);
    }

    /**
     * Generate weekly report.
     */
    public function weekly(Request $request)
    {
        $date = $request->get('date') ? Carbon::parse($request->date) : Carbon::today();
        $startOfWeek = $date->copy()->startOfWeek();
        $endOfWeek = $date->copy()->endOfWeek();
        
        $appointments = TblAppointment::whereBetween('date', [$startOfWeek, $endOfWeek])
            ->orderBy('date', 'desc')
            ->paginate(20);
            
        // Calculate daily stats
        $dailyStats = [];
        $currentDay = $startOfWeek->copy();
        for ($i = 0; $i < 7; $i++) {
            $day = $currentDay->copy();
            $dayTotal = TblAppointment::whereDate('date', $day)->count();
            $dayCompleted = TblAppointment::whereDate('date', $day)->where('status', 'completed')->count();
            
            $dailyStats[$day->format('l')] = [
                'date' => $day->format('M d'),
                'total' => $dayTotal,
                'completed' => $dayCompleted,
                'pending' => $dayTotal - $dayCompleted,
            ];
            $currentDay->addDay();
        }
        
        $stats = [
            'total' => TblAppointment::whereBetween('date', [$startOfWeek, $endOfWeek])->count(),
            'completed' => TblAppointment::whereBetween('date', [$startOfWeek, $endOfWeek])->where('status', 'completed')->count(),
            'cancelled' => TblAppointment::whereBetween('date', [$startOfWeek, $endOfWeek])->where('status', 'cancelled')->count(),
            'no_show' => TblAppointment::whereBetween('date', [$startOfWeek, $endOfWeek])->where('status', 'no_show')->count(),
            'pending' => TblAppointment::whereBetween('date', [$startOfWeek, $endOfWeek])->where('status', 'pending')->count(),
            'daily' => $dailyStats,
            'by_service' => TblAppointment::whereBetween('date', [$startOfWeek, $endOfWeek])
                ->select('queue_for', DB::raw('count(*) as total'))
                ->groupBy('queue_for')
                ->get(),
            'by_priority' => TblAppointment::whereBetween('date', [$startOfWeek, $endOfWeek])
                ->select('priority_type', DB::raw('count(*) as total'))
                ->groupBy('priority_type')
                ->get(),
            'avg_daily' => round(TblAppointment::whereBetween('date', [$startOfWeek, $endOfWeek])->count() / 7, 1),
        ];
        
        return view('admin.reports.show', [
            'reportName' => 'Weekly Report - ' . $startOfWeek->format('M d') . ' to ' . $endOfWeek->format('M d, Y'),
            'reportType' => 'weekly',
            'startDate' => $startOfWeek,
            'endDate' => $endOfWeek,
            'appointments' => $appointments,
            'stats' => $stats,
            'dailyStats' => $dailyStats,
            'totalAppointments' => $stats['total'],
            'completedAppointments' => $stats['completed'],
            'pendingAppointments' => $stats['pending'],
            'issuesCount' => $stats['cancelled'] + $stats['no_show']
        ]);
    }

    /**
     * Generate monthly report.
     */
    public function monthly(Request $request)
    {
        $month = $request->get('month') ?: Carbon::now()->month;
        $year = $request->get('year') ?: Carbon::now()->year;
        
        $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        
        $appointments = TblAppointment::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->orderBy('date', 'desc')
            ->paginate(20);
            
        // Calculate weekly stats
        $weeklyStats = [];
        $weekStart = $startOfMonth->copy();
        $weekNumber = 1;
        while ($weekStart <= $endOfMonth) {
            $weekEnd = $weekStart->copy()->addDays(6);
            $weekTotal = TblAppointment::whereBetween('date', [$weekStart, min($weekEnd, $endOfMonth)])->count();
            $weekCompleted = TblAppointment::whereBetween('date', [$weekStart, min($weekEnd, $endOfMonth)])
                ->where('status', 'completed')
                ->count();
            
            $weeklyStats['Week ' . $weekNumber] = [
                'total' => $weekTotal,
                'completed' => $weekCompleted,
                'pending' => $weekTotal - $weekCompleted,
                'range' => $weekStart->format('M d') . ' - ' . min($weekEnd, $endOfMonth)->format('M d')
            ];
            
            $weekStart->addWeek();
            $weekNumber++;
        }
        
        $stats = [
            'total' => TblAppointment::whereBetween('date', [$startOfMonth, $endOfMonth])->count(),
            'completed' => TblAppointment::whereBetween('date', [$startOfMonth, $endOfMonth])->where('status', 'completed')->count(),
            'cancelled' => TblAppointment::whereBetween('date', [$startOfMonth, $endOfMonth])->where('status', 'cancelled')->count(),
            'no_show' => TblAppointment::whereBetween('date', [$startOfMonth, $endOfMonth])->where('status', 'no_show')->count(),
            'pending' => TblAppointment::whereBetween('date', [$startOfMonth, $endOfMonth])->where('status', 'pending')->count(),
            'weekly' => $weeklyStats,
            'by_service' => TblAppointment::whereBetween('date', [$startOfMonth, $endOfMonth])
                ->select('queue_for', DB::raw('count(*) as total'))
                ->groupBy('queue_for')
                ->get(),
            'by_priority' => TblAppointment::whereBetween('date', [$startOfMonth, $endOfMonth])
                ->select('priority_type', DB::raw('count(*) as total'))
                ->groupBy('priority_type')
                ->get(),
            'by_window' => TblAppointment::whereBetween('date', [$startOfMonth, $endOfMonth])
                ->whereNotNull('window_num')
                ->where('window_num', '!=', '0')
                ->select('window_num', DB::raw('count(*) as total'))
                ->groupBy('window_num')
                ->get(),
            'avg_daily' => round(TblAppointment::whereBetween('date', [$startOfMonth, $endOfMonth])->count() / $startOfMonth->daysInMonth, 1),
        ];
        
        return view('admin.reports.show', [
            'reportName' => 'Monthly Report - ' . $startOfMonth->format('F Y'),
            'reportType' => 'monthly',
            'startDate' => $startOfMonth,
            'endDate' => $endOfMonth,
            'appointments' => $appointments,
            'stats' => $stats,
            'weeklyStats' => $weeklyStats,
            'totalAppointments' => $stats['total'],
            'completedAppointments' => $stats['completed'],
            'pendingAppointments' => $stats['pending'],
            'issuesCount' => $stats['cancelled'] + $stats['no_show']
        ]);
    }

    /**
     * Generate custom date range report.
     */
    public function custom(Request $request)
    {
        $request->validate([
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);
        
        $start = Carbon::parse($request->start);
        $end = Carbon::parse($request->end);
        
        $appointments = TblAppointment::whereBetween('date', [$start, $end])
            ->orderBy('date', 'desc')
            ->paginate(20);
        
        // Calculate daily stats for the range
        $dailyStats = [];
        $currentDate = $start->copy();
        while ($currentDate <= $end) {
            $dayTotal = TblAppointment::whereDate('date', $currentDate)->count();
            $dayCompleted = TblAppointment::whereDate('date', $currentDate)->where('status', 'completed')->count();
            
            $dailyStats[$currentDate->format('Y-m-d')] = [
                'date' => $currentDate->format('M d'),
                'total' => $dayTotal,
                'completed' => $dayCompleted,
            ];
            $currentDate->addDay();
        }
        
        $stats = [
            'total' => TblAppointment::whereBetween('date', [$start, $end])->count(),
            'completed' => TblAppointment::whereBetween('date', [$start, $end])->where('status', 'completed')->count(),
            'cancelled' => TblAppointment::whereBetween('date', [$start, $end])->where('status', 'cancelled')->count(),
            'no_show' => TblAppointment::whereBetween('date', [$start, $end])->where('status', 'no_show')->count(),
            'pending' => TblAppointment::whereBetween('date', [$start, $end])->where('status', 'pending')->count(),
            'by_service' => TblAppointment::whereBetween('date', [$start, $end])
                ->select('queue_for', DB::raw('count(*) as total'))
                ->groupBy('queue_for')
                ->get(),
            'by_priority' => TblAppointment::whereBetween('date', [$start, $end])
                ->select('priority_type', DB::raw('count(*) as total'))
                ->groupBy('priority_type')
                ->get(),
            'by_window' => TblAppointment::whereBetween('date', [$start, $end])
                ->whereNotNull('window_num')
                ->where('window_num', '!=', '0')
                ->select('window_num', DB::raw('count(*) as total'))
                ->groupBy('window_num')
                ->get(),
            'daily_stats' => $dailyStats,
            'days_count' => $start->diffInDays($end) + 1,
            'avg_per_day' => round(TblAppointment::whereBetween('date', [$start, $end])->count() / ($start->diffInDays($end) + 1), 1),
        ];
        
        return view('admin.reports.show', [
            'reportName' => 'Custom Report - ' . $start->format('M d, Y') . ' to ' . $end->format('M d, Y'),
            'reportType' => 'custom',
            'startDate' => $start,
            'endDate' => $end,
            'appointments' => $appointments,
            'stats' => $stats,
            'totalAppointments' => $stats['total'],
            'completedAppointments' => $stats['completed'],
            'pendingAppointments' => $stats['pending'],
            'issuesCount' => $stats['cancelled'] + $stats['no_show']
        ]);
    }

    /**
     * Export report to Excel (redirects to ExportController)
     */
    /**
 * Export report to Excel (redirects to ExportController)
 */
public function export(Request $request)
{
    $type = $request->get('type', 'daily');
    $format = $request->get('format', 'excel');
    
    // Build URL for export
    $exportUrl = route('admin.export.report', [
        'type' => $type,
        'format' => $format
    ]);
    
    // Add additional parameters
    if ($request->has('date')) {
        $exportUrl .= '&date=' . $request->date;
    }
    if ($request->has('start')) {
        $exportUrl .= '&start=' . $request->start;
    }
    if ($request->has('end')) {
        $exportUrl .= '&end=' . $request->end;
    }
    if ($request->has('month')) {
        $exportUrl .= '&month=' . $request->month;
    }
    if ($request->has('year')) {
        $exportUrl .= '&year=' . $request->year;
    }
    
    // Log the export URL for debugging
    \Log::info('Export URL: ' . $exportUrl);
    
    // Store in session for recent reports
    $recentReport = [
        'id' => uniqid(),
        'name' => $this->generateReportName($type, $request),
        'generated_at' => Carbon::now()->format('M d, Y H:i'),
        'size' => 'Pending'
    ];
    
    $recentReports = session('recent_reports', []);
    array_unshift($recentReports, $recentReport);
    $recentReports = array_slice($recentReports, 0, 10);
    session(['recent_reports' => $recentReports]);
    
    // Return JSON for AJAX requests, otherwise redirect
    if ($request->ajax() || $request->wantsJson()) {
        return response()->json([
            'success' => true,
            'redirect' => $exportUrl
        ]);
    }
    
    return redirect($exportUrl);
}
    
    /**
     * Get chart data for reports.
     */
    public function getChartData(Request $request)
    {
        $type = $request->get('type', 'daily');
        $period = $request->get('period', 7);
        
        $data = [];
        
        switch ($type) {
            case 'daily':
                for ($i = $period - 1; $i >= 0; $i--) {
                    $date = Carbon::today()->subDays($i);
                    $data[] = [
                        'label' => $date->format('D, M d'),
                        'value' => TblAppointment::whereDate('date', $date)->count(),
                        'completed' => TblAppointment::whereDate('date', $date)->where('status', 'completed')->count(),
                        'pending' => TblAppointment::whereDate('date', $date)->where('status', 'pending')->count(),
                    ];
                }
                break;
                
            case 'weekly':
                for ($i = $period - 1; $i >= 0; $i--) {
                    $start = Carbon::today()->subWeeks($i)->startOfWeek();
                    $end = Carbon::today()->subWeeks($i)->endOfWeek();
                    $data[] = [
                        'label' => 'Week ' . $start->weekOfYear,
                        'value' => TblAppointment::whereBetween('date', [$start, $end])->count(),
                        'completed' => TblAppointment::whereBetween('date', [$start, $end])->where('status', 'completed')->count(),
                    ];
                }
                break;
                
            case 'monthly':
                for ($i = $period - 1; $i >= 0; $i--) {
                    $date = Carbon::today()->subMonths($i);
                    $data[] = [
                        'label' => $date->format('M Y'),
                        'value' => TblAppointment::whereMonth('date', $date->month)
                            ->whereYear('date', $date->year)
                            ->count(),
                        'completed' => TblAppointment::whereMonth('date', $date->month)
                            ->whereYear('date', $date->year)
                            ->where('status', 'completed')
                            ->count(),
                    ];
                }
                break;
        }
        
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
    
    /**
     * Generate report name for session storage
     */
    private function generateReportName($type, $request)
    {
        switch ($type) {
            case 'daily':
                $date = $request->get('date') ? Carbon::parse($request->date) : Carbon::today();
                return 'Daily Report - ' . $date->format('M d, Y');
            case 'weekly':
                $date = $request->get('date') ? Carbon::parse($request->date) : Carbon::today();
                $start = $date->copy()->startOfWeek();
                $end = $date->copy()->endOfWeek();
                return 'Weekly Report - ' . $start->format('M d') . ' to ' . $end->format('M d, Y');
            case 'monthly':
                $month = $request->get('month') ?: Carbon::now()->month;
                $year = $request->get('year') ?: Carbon::now()->year;
                return 'Monthly Report - ' . Carbon::createFromDate($year, $month, 1)->format('F Y');
            case 'custom':
                $start = $request->get('start') ? Carbon::parse($request->start) : Carbon::today();
                $end = $request->get('end') ? Carbon::parse($request->end) : Carbon::today();
                return 'Custom Report - ' . $start->format('M d') . ' to ' . $end->format('M d, Y');
            default:
                return 'Report - ' . Carbon::now()->format('M d, Y H:i');
        }
    }
}