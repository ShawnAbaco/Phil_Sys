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
use Carbon\Carbon;

class OperatorController extends Controller
{
    public function dashboard()
{
    // Get operator's window number from users table
    $windowNum = Auth::user()->window_num ?? '1';
    $userId = Auth::id(); // Get the logged-in user's ID from users table
    
    // Set timezone to Philippine Time
    Carbon::setLocale('en');
    $today = Carbon::now('Asia/Manila')->toDateString();

    // Get today's appointments - ONLY PENDING ones (not assigned to any window and not served)
    $appointments = TblAppointment::whereDate('date', $today)
                                  ->whereNull('window_num')  // Not assigned to any window
                                  ->whereNull('time_catered') // Not served
                                  ->orderBy('date', 'asc')
                                  ->get();

    // Get RECENT COMPLETED TRANSACTIONS - WITH PAGINATION (10 items per page)
    $completedTransactions = TblAppointment::whereNotNull('time_catered')
                                          ->where('user_id', $userId) // Filter by logged-in user
                                          ->orderBy('time_catered', 'desc')
                                          ->paginate(10); // Use paginate() instead of limit()->get()

    // Get queue count for today (all appointments)
    $queueCount = TblAppointment::whereDate('date', $today)->count();

    // Get pending appointments count (not assigned to any window)
    $pendingCount = TblAppointment::whereDate('date', $today)
                                  ->whereNull('window_num')
                                  ->whereNull('time_catered')
                                  ->count();

    // Get completed appointments count - ONLY for this logged-in user
    $completedCount = TblAppointment::whereDate('date', $today)
                                    ->whereNotNull('time_catered')
                                    ->where('user_id', $userId) // Filter by logged-in user
                                    ->count();

    // Get count of appointments at current user's window (served at this window by this user)
    $windowQueueCount = TblAppointment::whereDate('date', $today)
                                     ->where('window_num', $windowNum)
                                     ->whereNotNull('time_catered')
                                     ->where('user_id', $userId) // Filter by logged-in user
                                     ->count();

    return view('operator.dashboard', compact(
        'windowNum',
        'appointments',
        'completedTransactions',
        'queueCount',
        'pendingCount',
        'completedCount',
        'windowQueueCount'
    ));
}


    public function fetchAppointments()
    {
        try {
            $today = Carbon::now('Asia/Manila')->toDateString();
            $userId = Auth::id(); // Get the logged-in user's ID

            // Get today's appointments that are not assigned to any window and not served
            $appointments = TblAppointment::whereDate('date', $today)
                ->whereNull('window_num')
                ->whereNull('time_catered')
                ->orderBy('date', 'asc')
                ->get();

            // Get statistics - completed count only for this user
            $stats = [
                'total' => TblAppointment::whereDate('date', $today)->count(),
                'pending' => TblAppointment::whereDate('date', $today)
                    ->whereNull('window_num')
                    ->whereNull('time_catered')
                    ->count(),
                'completed' => TblAppointment::whereDate('date', $today)
                    ->whereNotNull('time_catered')
                    ->where('user_id', $userId) // Filter by logged-in user
                    ->count(),
            ];

            return response()->json([
                'success' => true,
                'appointments' => $appointments,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching appointments: ' . $e->getMessage()
            ]);
        }
    }

public function recentTransactions()
{
    try {
        $userId = Auth::id(); // Get the logged-in user's ID
        
        // Get RECENT COMPLETED TRANSACTIONS - WITH PAGINATION for AJAX
        $transactions = TblAppointment::whereNotNull('time_catered')
            ->where('user_id', $userId) // Filter by logged-in user
            ->orderBy('time_catered', 'desc')
            ->paginate(10); // Use paginate() instead of limit()->get()

        return response()->json([
            'success' => true,
            'transactions' => $transactions
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error fetching transactions: ' . $e->getMessage()
        ]);
    }
}

    public function updateWindow(Request $request)
    {
        try {
            $validated = $request->validate([
                'n_id' => 'required|integer|exists:tbl_appointment,n_id',
                'window_num' => 'required'
            ]);

            $appointment = TblAppointment::find($validated['n_id']);

            if (!$appointment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Appointment not found.'
                ]);
            }

            // Convert window_num to string to ensure it's stored properly
            $windowNum = (string) $validated['window_num'];

            // Update the appointment - assign to window, mark as served, and associate with logged-in user
            $appointment->window_num = $windowNum;
            $appointment->time_catered = Carbon::now('Asia/Manila');
            $appointment->user_id = Auth::id(); // This stores the users.id
            $appointment->save();

            return response()->json([
                'success' => true,
                'message' => 'Queue number ' . $appointment->q_id . ' served at window #' . $windowNum . ' successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    public function getQueuedAppointments()
    {
        try {
            $today = Carbon::now('Asia/Manila')->toDateString();
            $windowNum = Auth::user()->window_num;
            $userId = Auth::id(); // Get the logged-in user's ID

            if (!$windowNum) {
                return response()->json([
                    'success' => true,
                    'queued' => []
                ]);
            }

            $queued = TblAppointment::whereDate('date', $today)
                ->where('window_num', (string) $windowNum)
                ->whereNotNull('time_catered')
                ->where('user_id', $userId) // Filter by logged-in user
                ->orderBy('time_catered', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'queued' => $queued
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
public function getTransactionsPage(Request $request)
{
    // Log the request for debugging
    \Log::info('getTransactionsPage called', [
        'ajax' => $request->ajax(),
        'wants_json' => $request->wantsJson(),
        'headers' => $request->headers->all(),
        'url' => $request->fullUrl()
    ]);
    
    // Always return JSON for AJAX requests
    if ($request->ajax() || $request->wantsJson()) {
        try {
            $perPage = $request->get('per_page', 10);
            $perPage = in_array($perPage, [10, 20, 50, 100]) ? $perPage : 10;
            $userId = Auth::id();
            
            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }
            
            $completedTransactions = TblAppointment::whereNotNull('time_catered')
                ->where('user_id', $userId)
                ->orderBy('time_catered', 'desc')
                ->paginate($perPage)
                ->withQueryString();
            
            // Check if views exist
            if (!view()->exists('operator.partials.transactions-table')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transactions table view not found'
                ], 500);
            }
            
            if (!view()->exists('operator.partials.pagination-links')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pagination links view not found'
                ], 500);
            }
            
            $tableHtml = view('operator.partials.transactions-table', compact('completedTransactions'))->render();
            $paginationHtml = view('operator.partials.pagination-links', compact('completedTransactions'))->render();
            $showingInfo = 'Showing ' . $completedTransactions->firstItem() . '-' . $completedTransactions->lastItem() . ' of ' . $completedTransactions->total();
            
            return response()->json([
                'success' => true,
                'table' => $tableHtml,
                'pagination' => $paginationHtml,
                'showing' => $showingInfo
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getTransactionsPage: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error loading transactions: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // For non-AJAX requests, return the view with paginator
    $completedTransactions = TblAppointment::whereNotNull('time_catered')
        ->where('user_id', Auth::id())
        ->orderBy('time_catered', 'desc')
        ->paginate(10);
        
    return view('operator.dashboard', compact('completedTransactions'));
}

public function exportPDF()
{
    $userId = Auth::id();
    $windowNum = Auth::user()->window_num ?? '1';
    
    // Get completed transactions for this operator
    $completedAppointments = TblAppointment::whereNotNull('time_catered')
                                          ->where('user_id', $userId)
                                          ->orderBy('time_catered', 'desc')
                                          ->get();
    
    // Add row numbers
    $completedAppointments = $completedAppointments->map(function($appointment, $index) {
        $appointment->row_number = $index + 1;
        return $appointment;
    });
    
    $totalCompleted = $completedAppointments->count();
    $dateToday = Carbon::now('Asia/Manila')->format('F j, Y');
    $timeGenerated = Carbon::now('Asia/Manila')->format('h:i A');
    
    $pdf = Pdf::loadView('operator.exports.operator-pdf', compact(
        'completedAppointments',
        'totalCompleted',
        'dateToday',
        'timeGenerated',
        'windowNum',
        'userId'
    ));
    
    $pdf->setPaper('A4', 'landscape');
    
    // Changed filename format to match appointment style
    return $pdf->download('RECENT TRANSACTIONS - ' . Carbon::now('Asia/Manila')->format('Y-m-d') . '.pdf');
}
public function exportCSV()
{
    $userId = Auth::id();
    $windowNum = Auth::user()->window_num ?? '1';
    
    // Get completed transactions for this operator
    $completedAppointments = TblAppointment::whereNotNull('time_catered')
                                          ->where('user_id', $userId)
                                          ->orderBy('time_catered', 'desc')
                                          ->get();
    
    // Add row numbers
    $completedAppointments = $completedAppointments->map(function($appointment, $index) {
        $appointment->row_number = $index + 1;
        return $appointment;
    });
    
    $totalCompleted = $completedAppointments->count();
    $dateToday = Carbon::now('Asia/Manila')->format('F j, Y');
    $timeGenerated = Carbon::now('Asia/Manila')->format('h:i A');
    
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="RECENT TRANSACTIONS - ' . Carbon::now('Asia/Manila')->format('Y-m-d') . '.csv"',
    ];
    
    $callback = function() use ($completedAppointments, $totalCompleted, $dateToday, $timeGenerated, $windowNum) {
        $file = fopen('php://output', 'w');
        
        // Add UTF-8 BOM for Excel compatibility
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Add header information as comments
        fputcsv($file, ['# PSA PHILSYS - Operator Recent Transactions Report']);
        fputcsv($file, ['# Date: ' . $dateToday]);
        fputcsv($file, ['# Generated: ' . $timeGenerated]);
        fputcsv($file, ['# Window Number: ' . $windowNum]);
        fputcsv($file, ['# Total Records: ' . $totalCompleted]);
        fputcsv($file, ['# Report ID: OPR-CSV-' . date('YmdHis')]);
        fputcsv($file, ['#']);
        
        // Add column headers
        fputcsv($file, [
            '#',
            'Queue #',
            'Client Name',
            'Age Category',
            'Birthdate',
            'TRN',
            'PCN',
            'Service',
            'Served Time',
            'Window'
        ]);
        
        // Add data rows
        foreach ($completedAppointments as $app) {
            $fullName = trim($app->lname . ', ' . $app->fname . 
                        ($app->mname ? ' ' . $app->mname : '') . 
                        ($app->suffix ? ' ' . $app->suffix : ''));
            
            $servedTime = Carbon::parse($app->time_catered)->setTimezone('Asia/Manila')->format('h:i A');
            $birthdate = $app->birthdate ? Carbon::parse($app->birthdate)->format('Y-m-d') : 'N/A';
            
            fputcsv($file, [
                $app->row_number,
                $app->q_id,
                $fullName,
                $app->age_category ?? 'N/A',
                $birthdate,
                $app->trn ?? 'N/A',
                $app->PCN ?? 'N/A',
                $app->queue_for,
                $servedTime,
                $app->window_num ?? 'N/A'
            ]);
        }
        
        fclose($file);
    };
    
    return response()->stream($callback, 200, $headers);
}

}