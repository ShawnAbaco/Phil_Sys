<?php
// app/Http/Controllers/Operator/OperatorController.php

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
 * Get report data via AJAX
 */
public function getReportData(Request $request)
{
    try {
        $userId = Auth::id();
        $dateRange = $request->get('date_range', 'this_month');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $serviceType = $request->get('service_type', 'all');
        $statusFilter = $request->get('status', 'all');
        
        // Set date range
        $start = null;
        $end = null;
        
        switch ($dateRange) {
            case 'today':
                $start = Carbon::now('Asia/Manila')->startOfDay();
                $end = Carbon::now('Asia/Manila')->endOfDay();
                break;
            case 'yesterday':
                $start = Carbon::now('Asia/Manila')->subDay()->startOfDay();
                $end = Carbon::now('Asia/Manila')->subDay()->endOfDay();
                break;
            case 'this_week':
                $start = Carbon::now('Asia/Manila')->startOfWeek();
                $end = Carbon::now('Asia/Manila')->endOfWeek();
                break;
            case 'last_week':
                $start = Carbon::now('Asia/Manila')->subWeek()->startOfWeek();
                $end = Carbon::now('Asia/Manila')->subWeek()->endOfWeek();
                break;
            case 'this_month':
                $start = Carbon::now('Asia/Manila')->startOfMonth();
                $end = Carbon::now('Asia/Manila')->endOfMonth();
                break;
            case 'last_month':
                $start = Carbon::now('Asia/Manila')->subMonth()->startOfMonth();
                $end = Carbon::now('Asia/Manila')->subMonth()->endOfMonth();
                break;
            case 'custom':
                $start = Carbon::parse($startDate)->startOfDay();
                $end = Carbon::parse($endDate)->endOfDay();
                break;
        }
        
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
        
        $transactions = $query->orderBy('updated_at', 'desc')->get();
        
        // Calculate stats
        $stats = [
            'completed' => $transactions->where('status', 'completed')->count(),
            'cancelled' => $transactions->where('status', 'cancelled')->count(),
            'no_show' => $transactions->where('status', 'no_show')->count(),
            'pending' => TblAppointment::where('user_id', $userId)
                                       ->where('status', 'pending')
                                       ->whereDate('date', Carbon::now('Asia/Manila')->toDateString())
                                       ->count(),
            'served' => TblAppointment::where('user_id', $userId)
                                      ->where('status', 'completed')
                                      ->whereDate('date', Carbon::now('Asia/Manila')->toDateString())
                                      ->count(),
        ];
        
        // Prepare chart data
        $dailyData = [];
        $period = Carbon::parse($start);
        while ($period <= $end) {
            $date = $period->format('Y-m-d');
            $dailyData[$date] = ['completed' => 0, 'cancelled' => 0];
            $period->addDay();
        }
        
        foreach ($transactions as $transaction) {
            $date = Carbon::parse($transaction->date)->format('Y-m-d');
            if (isset($dailyData[$date])) {
                if ($transaction->status === 'completed') {
                    $dailyData[$date]['completed']++;
                } elseif ($transaction->status === 'cancelled') {
                    $dailyData[$date]['cancelled']++;
                }
            }
        }
        
        $charts = [
            'daily' => [
                'labels' => array_keys($dailyData),
                'completed' => array_column($dailyData, 'completed'),
                'cancelled' => array_column($dailyData, 'cancelled'),
            ],
            'status' => [
                $stats['completed'],
                $stats['cancelled'],
                $stats['no_show']
            ]
        ];
        
        // Format transactions for table
        $formattedTransactions = $transactions->map(function($transaction) {
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
        
        // Calculate trends (compare with previous period)
        $previousStart = Carbon::parse($start)->subDays($start->diffInDays($end) + 1);
        $previousEnd = Carbon::parse($start)->subDay();
        
        $previousCompleted = TblAppointment::where('user_id', Auth::id())
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
            'pending' => '→ 0%',
            'served' => '↑ 0%',
        ];
        
        return response()->json([
            'success' => true,
            'stats' => $stats,
            'charts' => $charts,
            'transactions' => $formattedTransactions,
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
 * Export report as PDF
 */
public function exportReportPDF(Request $request)
{
    try {
        $userId = Auth::id();
        $dateRange = $request->get('date_range', 'this_month');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $serviceType = $request->get('service_type', 'all');
        $statusFilter = $request->get('status', 'all');
        
        // Build query similar to getReportData
        $start = null;
        $end = null;
        
        switch ($dateRange) {
            case 'today':
                $start = Carbon::now('Asia/Manila')->startOfDay();
                $end = Carbon::now('Asia/Manila')->endOfDay();
                break;
            case 'yesterday':
                $start = Carbon::now('Asia/Manila')->subDay()->startOfDay();
                $end = Carbon::now('Asia/Manila')->subDay()->endOfDay();
                break;
            case 'this_week':
                $start = Carbon::now('Asia/Manila')->startOfWeek();
                $end = Carbon::now('Asia/Manila')->endOfWeek();
                break;
            case 'last_week':
                $start = Carbon::now('Asia/Manila')->subWeek()->startOfWeek();
                $end = Carbon::now('Asia/Manila')->subWeek()->endOfWeek();
                break;
            case 'this_month':
                $start = Carbon::now('Asia/Manila')->startOfMonth();
                $end = Carbon::now('Asia/Manila')->endOfMonth();
                break;
            case 'last_month':
                $start = Carbon::now('Asia/Manila')->subMonth()->startOfMonth();
                $end = Carbon::now('Asia/Manila')->subMonth()->endOfMonth();
                break;
            case 'custom':
                $start = Carbon::parse($startDate)->startOfDay();
                $end = Carbon::parse($endDate)->endOfDay();
                break;
        }
        
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
        
        $transactions = $query->orderBy('updated_at', 'desc')->get();
        
        $dateRangeText = '';
        switch ($dateRange) {
            case 'today': $dateRangeText = 'Today'; break;
            case 'yesterday': $dateRangeText = 'Yesterday'; break;
            case 'this_week': $dateRangeText = 'This Week'; break;
            case 'last_week': $dateRangeText = 'Last Week'; break;
            case 'this_month': $dateRangeText = 'This Month'; break;
            case 'last_month': $dateRangeText = 'Last Month'; break;
            case 'custom': $dateRangeText = Carbon::parse($startDate)->format('M d, Y') . ' - ' . Carbon::parse($endDate)->format('M d, Y'); break;
        }
        
        $pdf = Pdf::loadView('operator.exports.report-pdf', compact(
            'transactions',
            'dateRangeText',
            'serviceType',
            'statusFilter'
        ));
        
        $pdf->setPaper('A4', 'landscape');
        
        $filename = 'REPORT-' . Carbon::now('Asia/Manila')->format('Y-m-d-H-i') . '.pdf';
        
        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

/**
 * Export report as Excel
 */
public function exportReportExcel(Request $request)
{
    try {
        $userId = Auth::id();
        $dateRange = $request->get('date_range', 'this_month');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $serviceType = $request->get('service_type', 'all');
        $statusFilter = $request->get('status', 'all');
        
        // Build query similarly...
        $start = null;
        $end = null;
        
        switch ($dateRange) {
            case 'today':
                $start = Carbon::now('Asia/Manila')->startOfDay();
                $end = Carbon::now('Asia/Manila')->endOfDay();
                break;
            case 'yesterday':
                $start = Carbon::now('Asia/Manila')->subDay()->startOfDay();
                $end = Carbon::now('Asia/Manila')->subDay()->endOfDay();
                break;
            case 'this_week':
                $start = Carbon::now('Asia/Manila')->startOfWeek();
                $end = Carbon::now('Asia/Manila')->endOfWeek();
                break;
            case 'last_week':
                $start = Carbon::now('Asia/Manila')->subWeek()->startOfWeek();
                $end = Carbon::now('Asia/Manila')->subWeek()->endOfWeek();
                break;
            case 'this_month':
                $start = Carbon::now('Asia/Manila')->startOfMonth();
                $end = Carbon::now('Asia/Manila')->endOfMonth();
                break;
            case 'last_month':
                $start = Carbon::now('Asia/Manila')->subMonth()->startOfMonth();
                $end = Carbon::now('Asia/Manila')->subMonth()->endOfMonth();
                break;
            case 'custom':
                $start = Carbon::parse($startDate)->startOfDay();
                $end = Carbon::parse($endDate)->endOfDay();
                break;
        }
        
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
        
        $transactions = $query->orderBy('updated_at', 'desc')->get();
        
        $export = new OperatorReportExport($transactions);
        $filename = 'REPORT-' . Carbon::now('Asia/Manila')->format('Y-m-d-H-i') . '.xlsx';
        
        return Excel::download($export, $filename);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
            }