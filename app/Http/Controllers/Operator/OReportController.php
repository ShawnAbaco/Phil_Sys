<?php
// app/Http/Controllers/Operator/OReportController.php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TblAppointment;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OperatorAppointmentsExport;
use App\Exports\OperatorReportExport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class OReportController extends Controller
{

/**
 * Display reports page
 */
public function reports(Request $request)
{
    $userId = Auth::id();
    $today = Carbon::now('Asia/Manila')->toDateString();
    
    // Get totals for the current month
    $totalCompleted = TblAppointment::where('user_id', $userId)
                                    ->where('status', 'completed')
                                    ->whereMonth('date', Carbon::now()->month)
                                    ->count();
    
    $totalCancelled = TblAppointment::where('user_id', $userId)
                                    ->where('status', 'cancelled')
                                    ->whereMonth('date', Carbon::now()->month)
                                    ->count();
    
    $totalPending = TblAppointment::where('user_id', $userId)
                                  ->where('status', 'pending')
                                  ->whereDate('date', $today)
                                  ->count();
    
    $totalServed = TblAppointment::where('user_id', $userId)
                                 ->where('status', 'completed')
                                 ->whereDate('date', $today)
                                 ->count();
    
    // Get transactions for the current month
    $transactions = TblAppointment::where('user_id', $userId)
                                  ->whereIn('status', ['completed', 'cancelled'])
                                  ->whereMonth('date', Carbon::now()->month)
                                  ->orderBy('updated_at', 'desc')
                                  ->get();
    
    return view('operator.reports', compact(
        'totalCompleted',
        'totalCancelled',
        'totalPending',
        'totalServed',
        'transactions'
    ));
}

/**
 * Get report data via AJAX with pagination - UPDATED with No Show in daily trends
 */
public function getReportData(Request $request)
{
    try {
        $userId = Auth::id();
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $serviceType = $request->get('service_type', 'all');
        $statusFilter = $request->get('status', 'all');
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);
        
        // Validate per page
        $perPage = in_array($perPage, [10, 20, 50, 100]) ? $perPage : 10;
        
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();
        
        // Build query for transactions (with pagination)
        $query = TblAppointment::where('user_id', $userId)
                               ->whereBetween('date', [$start, $end]);
        
        if ($serviceType !== 'all') {
            $query->where('queue_for', $serviceType);
        }
        
        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        } else {
            $query->whereIn('status', ['completed', 'cancelled', 'no_show']);
        }
        
        // Get paginated transactions
        $transactionsPaginated = $query->orderBy('updated_at', 'desc')
                                       ->paginate($perPage, ['*'], 'page', $page);
        
        // Get all transactions for stats and charts (without pagination)
        $allQuery = TblAppointment::where('user_id', $userId)
                                  ->whereBetween('date', [$start, $end]);
        
        if ($serviceType !== 'all') {
            $allQuery->where('queue_for', $serviceType);
        }
        
        if ($statusFilter !== 'all') {
            $allQuery->where('status', $statusFilter);
        } else {
            $allQuery->whereIn('status', ['completed', 'cancelled', 'no_show']);
        }
        
        $allTransactions = $allQuery->get();
        
        // Calculate stats
        $stats = [
            'completed' => $allTransactions->where('status', 'completed')->count(),
            'cancelled' => $allTransactions->where('status', 'cancelled')->count(),
            'no_show' => $allTransactions->where('status', 'no_show')->count(),
            'served' => $allTransactions->where('status', 'completed')->count(),
            'senior' => $allTransactions->where('priority_type', 'senior')->count(),
            'infant' => $allTransactions->where('priority_type', 'infant')->count(),
            'pwd' => $allTransactions->where('priority_type', 'pwd')->count(),
            'pregnant' => $allTransactions->where('priority_type', 'pregnant')->count(),
            'regular' => $allTransactions->where('priority_type', 'regular')->count(),
        ];
        
        // Quick stats (today, week, month, year)
        $today = Carbon::now('Asia/Manila')->toDateString();
        $quickStats = [
            'today' => TblAppointment::where('user_id', $userId)->whereDate('date', $today)->count(),
            'week' => TblAppointment::where('user_id', $userId)->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(),
            'month' => TblAppointment::where('user_id', $userId)->whereMonth('date', Carbon::now()->month)->count(),
            'year' => TblAppointment::where('user_id', $userId)->whereYear('date', Carbon::now()->year)->count(),
            'avgDaily' => round(TblAppointment::where('user_id', $userId)->whereMonth('date', Carbon::now()->month)->count() / Carbon::now()->daysInMonth, 1),
            'completionRate' => $stats['completed'] > 0 ? round(($stats['completed'] / ($stats['completed'] + $stats['cancelled'])) * 100) : 0,
        ];
        
        // Prepare chart data - UPDATED to include no_show
        $dailyData = [];
        $period = Carbon::parse($start);
        while ($period <= $end) {
            $date = $period->format('Y-m-d');
            $dailyData[$date] = ['completed' => 0, 'cancelled' => 0, 'no_show' => 0];
            $period->addDay();
        }
        
        foreach ($allTransactions as $transaction) {
            $date = Carbon::parse($transaction->date)->format('Y-m-d');
            if (isset($dailyData[$date])) {
                if ($transaction->status === 'completed') {
                    $dailyData[$date]['completed']++;
                } elseif ($transaction->status === 'cancelled') {
                    $dailyData[$date]['cancelled']++;
                } elseif ($transaction->status === 'no_show') {
                    $dailyData[$date]['no_show']++;
                }
            }
        }
        
        $charts = [
            'daily' => [
                'labels' => array_keys($dailyData),
                'completed' => array_column($dailyData, 'completed'),
                'cancelled' => array_column($dailyData, 'cancelled'),
                'no_show' => array_column($dailyData, 'no_show'),
            ],
            'status' => [$stats['completed'], $stats['cancelled'], $stats['no_show']],
            'priority' => [$stats['senior'], $stats['infant'], $stats['pwd'], $stats['pregnant'], $stats['regular']],
            'services' => [
                $allTransactions->where('queue_for', 'NID Registration')->count(),
                $allTransactions->where('queue_for', 'Status Inquiry')->count(),
                $allTransactions->where('queue_for', 'Updating')->count(),
            ]
        ];
        
        // Calculate trends
        $previousStart = Carbon::parse($start)->subDays($start->diffInDays($end) + 1);
        $previousEnd = Carbon::parse($start)->subDay();
        
        $previousCompleted = TblAppointment::where('user_id', $userId)
                                           ->where('status', 'completed')
                                           ->whereBetween('date', [$previousStart, $previousEnd])
                                           ->count();
        
        $currentCompleted = $stats['completed'];
        $completedTrend = $previousCompleted > 0 
            ? round((($currentCompleted - $previousCompleted) / $previousCompleted) * 100, 1)
            : ($currentCompleted > 0 ? 100 : 0);
        
        $trends = [
            'completed' => ($completedTrend >= 0 ? '↑ ' : '↓ ') . abs($completedTrend) . '%',
            'cancelled' => '→ 0%',
            'no_show' => '→ 0%',
            'served' => '↑ 0%',
        ];
        
        // Format transactions for table
        $formattedTransactions = $transactionsPaginated->map(function($transaction) {
            $fullName = $transaction->lname . ', ' . $transaction->fname;
            if ($transaction->mname && trim($transaction->mname) !== '') {
                $fullName .= ' ' . $transaction->mname;
            }
            if ($transaction->suffix && trim($transaction->suffix) !== '') {
                $fullName .= ' ' . $transaction->suffix;
            }
            
            $servedTime = $transaction->time_catered 
                ? Carbon::parse($transaction->time_catered)->setTimezone('Asia/Manila')
                : Carbon::parse($transaction->updated_at)->setTimezone('Asia/Manila');
            
            return [
                'date' => $servedTime->format('M d, Y'),
                'q_id' => $transaction->q_id,
                'client_name' => $fullName,
                'service' => $transaction->queue_for,
                'priority_type' => $transaction->priority_type ?? 'regular',
                'status' => $transaction->status,
                'window_num' => $transaction->window_num,
                'served_time' => $servedTime->format('h:i A'),
            ];
        });
        
        return response()->json([
            'success' => true,
            'stats' => $stats,
            'quickStats' => $quickStats,
            'charts' => $charts,
            'transactions' => [
                'data' => $formattedTransactions,
                'current_page' => $transactionsPaginated->currentPage(),
                'last_page' => $transactionsPaginated->lastPage(),
                'per_page' => $transactionsPaginated->perPage(),
                'total' => $transactionsPaginated->total(),
                'from' => $transactionsPaginated->firstItem(),
                'to' => $transactionsPaginated->lastItem(),
            ],
            'trends' => $trends,
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

/**
 * Export report as PDF with filters
 */
public function exportReportPDF(Request $request)
{
    try {
        $userId = Auth::id();
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $serviceType = $request->get('service_type', 'all');
        $statusFilter = $request->get('status', 'all');
        
        // If no dates provided, use current month
        if (!$startDate || !$endDate) {
            $startDate = Carbon::now('Asia/Manila')->startOfMonth()->toDateString();
            $endDate = Carbon::now('Asia/Manila')->endOfMonth()->toDateString();
        }
        
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();
        
        // Build query
        $query = TblAppointment::where('user_id', $userId)
                               ->whereBetween('date', [$start, $end]);
        
        if ($serviceType !== 'all') {
            $query->where('queue_for', $serviceType);
        }
        
        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        } else {
            $query->whereIn('status', ['completed', 'cancelled', 'no_show']);
        }
        
        $completedAppointments = $query->orderBy('updated_at', 'desc')->get();
        
        if ($completedAppointments->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No transactions to export for the selected period'
            ], 404);
        }
        
        $completedAppointments = $completedAppointments->map(function($appointment, $index) {
            $appointment->row_number = $index + 1;
            return $appointment;
        });
        
        $totalCompleted = $completedAppointments->count();
        $dateToday = Carbon::now('Asia/Manila')->format('F j, Y');
        $timeGenerated = Carbon::now('Asia/Manila')->format('h:i A');
        $windowNum = Auth::user()->window_num ?? '1';
        
        // Format date range for display
        $dateRangeDisplay = Carbon::parse($startDate)->format('M d, Y') . ' - ' . Carbon::parse($endDate)->format('M d, Y');
        
        // Get service and status display names
        $serviceDisplay = $serviceType === 'all' ? 'All Services' : $serviceType;
        $statusDisplay = $statusFilter === 'all' ? 'All Status' : ucfirst(str_replace('_', ' ', $statusFilter));
        
        $pdf = Pdf::loadView('operator.exports.report-pdf', compact(
            'completedAppointments',
            'totalCompleted',
            'dateToday',
            'timeGenerated',
            'windowNum',
            'userId',
            'dateRangeDisplay',
            'serviceDisplay',
            'statusDisplay',
            'startDate',
            'endDate'
        ));
        
        $pdf->setPaper('A4', 'landscape');
        
        $filename = 'REPORT-' . Carbon::now('Asia/Manila')->format('Y-m-d-His') . '.pdf';
        
        if (ob_get_level()) {
            ob_end_clean();
        }
        
        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Length' => strlen($pdf->output()),
            'Content-Transfer-Encoding' => 'binary',
            'Accept-Ranges' => 'bytes',
            'Cache-Control' => 'private, max-age=0, must-revalidate, no-transform',
            'Pragma' => 'public',
            'Expires' => '0',
            'X-Content-Type-Options' => 'nosniff'
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Report PDF Export Error: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to generate PDF. Please try again.'
        ], 500);
    }
}

/**
 * Export report as Excel with filters
 */
public function exportReportExcel(Request $request)
{
    try {
        $userId = Auth::id();
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $serviceType = $request->get('service_type', 'all');
        $statusFilter = $request->get('status', 'all');
        
        // If no dates provided, use current month
        if (!$startDate || !$endDate) {
            $startDate = Carbon::now('Asia/Manila')->startOfMonth()->toDateString();
            $endDate = Carbon::now('Asia/Manila')->endOfMonth()->toDateString();
        }
        
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();
        
        // Build query
        $query = TblAppointment::where('user_id', $userId)
                               ->whereBetween('date', [$start, $end]);
        
        if ($serviceType !== 'all') {
            $query->where('queue_for', $serviceType);
        }
        
        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        } else {
            $query->whereIn('status', ['completed', 'cancelled', 'no_show']);
        }
        
        $completedAppointments = $query->orderBy('updated_at', 'desc')->get();
        
        if ($completedAppointments->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No transactions to export for the selected period'
            ], 404);
        }
        
        $completedAppointments = $completedAppointments->map(function($appointment, $index) {
            $appointment->row_number = $index + 1;
            return $appointment;
        });
        
        $totalCompleted = $completedAppointments->count();
        $dateToday = Carbon::now('Asia/Manila')->format('F j, Y');
        $timeGenerated = Carbon::now('Asia/Manila')->format('h:i A');
        $windowNum = Auth::user()->window_num ?? '1';
        
        // Format date range for display
        $dateRangeDisplay = Carbon::parse($startDate)->format('M d, Y') . ' - ' . Carbon::parse($endDate)->format('M d, Y');
        
        // Get service and status display names
        $serviceDisplay = $serviceType === 'all' ? 'All Services' : $serviceType;
        $statusDisplay = $statusFilter === 'all' ? 'All Status' : ucfirst(str_replace('_', ' ', $statusFilter));
        
        $export = new OperatorReportExport(
            $completedAppointments,
            $totalCompleted,
            $dateToday,
            $timeGenerated,
            $windowNum,
            $userId,
            $dateRangeDisplay,
            $serviceDisplay,
            $statusDisplay,
            $startDate,
            $endDate
        );
        
        $filename = 'REPORT-' . Carbon::now('Asia/Manila')->format('Y-m-d-His') . '.xlsx';
        
        if (ob_get_level()) {
            ob_end_clean();
        }
        
        return Excel::download($export, $filename, \Maatwebsite\Excel\Excel::XLSX, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'private, max-age=0, must-revalidate, no-transform',
            'Content-Transfer-Encoding' => 'binary',
            'Pragma' => 'public',
            'Expires' => '0',
            'X-Content-Type-Options' => 'nosniff'
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Report Excel Export Error: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to generate Excel file. Please try again.'
        ], 500);
    }
}

}