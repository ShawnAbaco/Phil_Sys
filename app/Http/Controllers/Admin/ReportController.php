<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TblAppointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends AdminController
{


    /**
     * Display reports overview.
     */
    public function index()
    {
        return view('admin.reports.index');
    }

    /**
     * Generate daily report.
     */
    public function daily(Request $request)
    {
        $date = $request->get('date') ? Carbon::parse($request->date) : Carbon::today();
        
        $appointments = TblAppointment::whereDate('date', $date)
            ->orderBy('time_catered', 'desc')
            ->get();
            
        $stats = [
            'total' => $appointments->count(),
            'completed' => $appointments->whereNotNull('time_catered')->count(),
            'pending' => $appointments->whereNull('time_catered')->count(),
            'by_service' => $appointments->groupBy('queue_for')->map->count(),
            'by_window' => $appointments->groupBy('window_num')->map->count(),
        ];
        
        return view('admin.reports.show', [
            'reportName' => 'Daily Report - ' . $date->format('F d, Y'),
            'date' => $date,
            'appointments' => $appointments,
            'stats' => $stats
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
            ->get();
            
        $dailyStats = [];
        for ($i = 0; $i < 7; $i++) {
            $day = $startOfWeek->copy()->addDays($i);
            $dayAppointments = $appointments->filter(function($app) use ($day) {
                return Carbon::parse($app->date)->isSameDay($day);
            });
            
            $dailyStats[$day->format('l')] = [
                'total' => $dayAppointments->count(),
                'completed' => $dayAppointments->whereNotNull('time_catered')->count(),
                'pending' => $dayAppointments->whereNull('time_catered')->count(),
            ];
        }
        
        $stats = [
            'total' => $appointments->count(),
            'completed' => $appointments->whereNotNull('time_catered')->count(),
            'pending' => $appointments->whereNull('time_catered')->count(),
            'daily' => $dailyStats,
            'by_service' => $appointments->groupBy('queue_for')->map->count(),
        ];
        
        return view('admin.reports.show', [
            'reportName' => 'Weekly Report - ' . $startOfWeek->format('M d') . ' to ' . $endOfWeek->format('M d, Y'),
            'startDate' => $startOfWeek,
            'endDate' => $endOfWeek,
            'appointments' => $appointments,
            'stats' => $stats,
            'dailyStats' => $dailyStats
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
            ->get();
            
        $weeklyStats = [];
        $weekStart = $startOfMonth->copy();
        while ($weekStart <= $endOfMonth) {
            $weekEnd = $weekStart->copy()->addDays(6);
            $weekAppointments = $appointments->filter(function($app) use ($weekStart, $weekEnd) {
                $date = Carbon::parse($app->date);
                return $date->between($weekStart, $weekEnd);
            });
            
            $weeklyStats['Week ' . $weekStart->weekOfMonth] = [
                'total' => $weekAppointments->count(),
                'completed' => $weekAppointments->whereNotNull('time_catered')->count(),
                'range' => $weekStart->format('M d') . ' - ' . min($weekEnd, $endOfMonth)->format('M d')
            ];
            
            $weekStart->addWeek();
        }
        
        $stats = [
            'total' => $appointments->count(),
            'completed' => $appointments->whereNotNull('time_catered')->count(),
            'pending' => $appointments->whereNull('time_catered')->count(),
            'weekly' => $weeklyStats,
            'by_service' => $appointments->groupBy('queue_for')->map->count(),
            'by_window' => $appointments->groupBy('window_num')->map->count(),
        ];
        
        return view('admin.reports.show', [
            'reportName' => 'Monthly Report - ' . $startOfMonth->format('F Y'),
            'startDate' => $startOfMonth,
            'endDate' => $endOfMonth,
            'appointments' => $appointments,
            'stats' => $stats,
            'weeklyStats' => $weeklyStats
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
            ->get();
            
        $stats = [
            'total' => $appointments->count(),
            'completed' => $appointments->whereNotNull('time_catered')->count(),
            'pending' => $appointments->whereNull('time_catered')->count(),
            'by_service' => $appointments->groupBy('queue_for')->map->count(),
            'by_window' => $appointments->groupBy('window_num')->map->count(),
        ];
        
        return view('admin.reports.show', [
            'reportName' => 'Custom Report - ' . $start->format('M d, Y') . ' to ' . $end->format('M d, Y'),
            'startDate' => $start,
            'endDate' => $end,
            'appointments' => $appointments,
            'stats' => $stats
        ]);
    }

    /**
     * Export report data.
     */
    public function export(Request $request)
    {
        // This will be handled by ExportController
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
                        'label' => $date->format('D'),
                        'value' => TblAppointment::whereDate('date', $date)->count()
                    ];
                }
                break;
                
            case 'weekly':
                for ($i = $period - 1; $i >= 0; $i--) {
                    $start = Carbon::today()->subWeeks($i)->startOfWeek();
                    $end = Carbon::today()->subWeeks($i)->endOfWeek();
                    $data[] = [
                        'label' => 'Week ' . $start->weekOfYear,
                        'value' => TblAppointment::whereBetween('date', [$start, $end])->count()
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
                            ->count()
                    ];
                }
                break;
        }
        
        return $this->sendSuccess('Chart data retrieved', $data);
    }
}