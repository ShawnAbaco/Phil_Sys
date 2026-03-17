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
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class OperatorController extends Controller
{
   public function dashboard()
{
    // Get operator's window number from users table
    $windowNum = Auth::user()->window_num ?? '1';
    $userId = Auth::id();
    
    // Set timezone to Philippine Time
    Carbon::setLocale('en');
    $today = Carbon::now('Asia/Manila')->toDateString();

    // Get today's appointments with status: pending, serving, or no_show
    // For serving status, only show the ones served by this user
    $allAppointments = TblAppointment::whereDate('date', $today)
                                    ->where(function($query) use ($userId) {
                                        $query->whereIn('status', ['pending', 'no_show'])
                                              ->orWhere(function($q) use ($userId) {
                                                  $q->where('status', 'serving')
                                                    ->where('user_id', $userId);
                                              });
                                    })
                                    ->orderBy('date', 'asc')
                                    ->get();

    // Separate appointments by status
    $serving = $allAppointments->where('status', 'serving')->values();
    
    // Sort pending appointments by priority (senior, infant, pwd, pregnant first, then regular)
    $pending = $allAppointments->where('status', 'pending')
                               ->sortBy(function($app) {
                                   // Priority order: senior/infant/pwd/pregnant first, then regular
                                   switch($app->priority_type) {
                                       case 'senior':
                                       case 'infant':
                                       case 'pwd':
                                       case 'pregnant':
                                           return 1; // High priority
                                       case 'regular':
                                       default:
                                           return 2; // Regular (low priority)
                                   }
                               })
                               ->values();
    
    $noShow = $allAppointments->where('status', 'no_show')->sortBy('updated_at')->values();

    // Custom sorting logic for appointments
    $appointments = collect();

    // 1. Add serving appointments first (should be only one per user)
    foreach ($serving as $app) {
        $appointments->push($app);
    }

    // 2. Add pending appointments (now sorted by priority)
    foreach ($pending as $app) {
        $appointments->push($app);
    }

    // 3. Handle no_show appointments with special positioning
    if ($noShow->count() > 0) {
        // Get the oldest no_show (first in the sorted collection)
        $oldestNoShow = $noShow->shift();
        
        if ($appointments->count() >= 1) {
            // Insert the oldest no_show at position 1 (2nd row, 0-indexed)
            $appointments->splice(1, 0, [$oldestNoShow]);
        } else {
            // If no appointments yet, just push it
            $appointments->push($oldestNoShow);
        }
        
        // Add remaining no_show appointments at the end
        foreach ($noShow as $app) {
            $appointments->push($app);
        }
    }

    // Filter appointments by service type
    $nidRegistrationAppointments = $appointments->filter(function($app) {
        return $app->queue_for === 'NID Registration';
    })->values();

    $statusInquiryAppointments = $appointments->filter(function($app) {
        return $app->queue_for === 'Status Inquiry';
    })->values();

    $nidUpdatingAppointments = $appointments->filter(function($app) {
        return $app->queue_for === 'Updating';
    })->values();

    // Get TODAY'S COMPLETED & CANCELLED TRANSACTIONS
    $completedTransactions = TblAppointment::whereDate('date', $today)
                                          ->whereIn('status', ['completed', 'cancelled'])
                                          ->where('user_id', $userId)
                                          ->orderByRaw("CASE 
                                              WHEN time_catered IS NOT NULL THEN time_catered 
                                              ELSE updated_at 
                                          END DESC")
                                          ->paginate(10);

    // TOTAL QUEUE TODAY - ALL APPOINTMENTS regardless of status or user
    $queueCount = TblAppointment::whereDate('date', $today)
                               ->count();

    // Get pending appointments count (all pending, regardless of user)
    $pendingCount = TblAppointment::whereDate('date', $today)
                                  ->where('status', 'pending')
                                  ->count();

    // Get completed appointments count for this user
    $completedCount = TblAppointment::whereDate('date', $today)
                                    ->where('status', 'completed')
                                    ->where('user_id', $userId)
                                    ->count();

    // Get cancelled appointments count for this user
    $cancelledCount = TblAppointment::whereDate('date', $today)
                                    ->where('status', 'cancelled')
                                    ->where('user_id', $userId)
                                    ->count();

    // Get serving appointments count (only this user's serving)
    $servingCount = TblAppointment::whereDate('date', $today)
                                  ->where('status', 'serving')
                                  ->where('user_id', $userId)
                                  ->count();

    // Get no show appointments count (all no_show, regardless of user)
    $noShowCount = TblAppointment::whereDate('date', $today)
                                 ->where('status', 'no_show')
                                 ->count();

    // Get all completed today (for additional stat)
    $allCompletedToday = TblAppointment::whereDate('date', $today)
                                      ->where('status', 'completed')
                                      ->count();

    // Get all cancelled today (for additional stat)
    $allCancelledToday = TblAppointment::whereDate('date', $today)
                                      ->where('status', 'cancelled')
                                      ->count();

    return view('operator.dashboard', compact(
        'windowNum',
        'appointments',
        'nidRegistrationAppointments',
        'statusInquiryAppointments',
        'nidUpdatingAppointments',
        'completedTransactions',
        'queueCount',
        'pendingCount',
        'completedCount',
        'cancelledCount',
        'servingCount',
        'noShowCount',
        'allCompletedToday',
        'allCancelledToday'
    ));
}

public function fetchAppointments()
{
    try {
        $today = Carbon::now('Asia/Manila')->toDateString();
        $userId = Auth::id();

        // Get today's appointments with status: pending, serving, or no_show
        // For serving status, only show the ones served by this user
        $allAppointments = TblAppointment::whereDate('date', $today)
                                        ->where(function($query) use ($userId) {
                                            $query->whereIn('status', ['pending', 'no_show'])
                                                  ->orWhere(function($q) use ($userId) {
                                                      $q->where('status', 'serving')
                                                        ->where('user_id', $userId);
                                                  });
                                        })
                                        ->orderBy('date', 'asc')
                                        ->get();

        // Separate appointments by status
        $serving = $allAppointments->where('status', 'serving')->values();
        
        // Sort pending appointments by priority (senior, infant, pwd, pregnant first, then regular)
        $pending = $allAppointments->where('status', 'pending')
                                   ->sortBy(function($app) {
                                       // Priority order: senior/infant/pwd/pregnant first, then regular
                                       switch($app->priority_type) {
                                           case 'senior':
                                           case 'infant':
                                           case 'pwd':
                                           case 'pregnant':
                                               return 1; // High priority
                                           case 'regular':
                                           default:
                                               return 2; // Regular (low priority)
                                       }
                                   })
                                   ->values();
        
        $noShow = $allAppointments->where('status', 'no_show')->sortBy('updated_at')->values();

        // Custom sorting logic for appointments
        $appointments = collect();

        // 1. Add serving appointments first (should be only one per user)
        foreach ($serving as $app) {
            $appointments->push($app);
        }

        // 2. Add pending appointments (now sorted by priority)
        foreach ($pending as $app) {
            $appointments->push($app);
        }

        // 3. Handle no_show appointments with special positioning
        if ($noShow->count() > 0) {
            // Get the oldest no_show (first in the sorted collection)
            $oldestNoShow = $noShow->shift();
            
            if ($appointments->count() >= 1) {
                // Insert the oldest no_show at position 1 (2nd row, 0-indexed)
                $appointments->splice(1, 0, [$oldestNoShow]);
            } else {
                // If no appointments yet, just push it
                $appointments->push($oldestNoShow);
            }
            
            // Add remaining no_show appointments at the end
            foreach ($noShow as $app) {
                $appointments->push($app);
            }
        }

        // Filter appointments by service type
        $nidRegistrationAppointments = $appointments->filter(function($app) {
            return $app->queue_for === 'NID Registration';
        })->values();

        $statusInquiryAppointments = $appointments->filter(function($app) {
            return $app->queue_for === 'Status Inquiry';
        })->values();

        $nidUpdatingAppointments = $appointments->filter(function($app) {
            return $app->queue_for === 'Updating';
        })->values();

        // Render each table
        $tableAll = view('operator.partials.appointments-table', [
            'appointments' => $appointments,
            'showAll' => true,
            'tableId' => 'all'
        ])->render();
        
        $tableNidRegistration = view('operator.partials.appointments-table', [
            'appointments' => $nidRegistrationAppointments,
            'serviceType' => 'NID Registration',
            'tableId' => 'nid-registration'
        ])->render();
        
        $tableStatusInquiry = view('operator.partials.appointments-table', [
            'appointments' => $statusInquiryAppointments,
            'serviceType' => 'Status Inquiry',
            'tableId' => 'status-inquiry'
        ])->render();
        
        $tableNidUpdating = view('operator.partials.appointments-table', [
            'appointments' => $nidUpdatingAppointments,
            'serviceType' => 'Updating',
            'tableId' => 'updating'
        ])->render();

        // Get statistics - FIXED TOTAL to show ALL appointments today
        $stats = [
            'total' => TblAppointment::whereDate('date', $today)->count(), // ALL appointments today
            'pending' => TblAppointment::whereDate('date', $today)
                                      ->where('status', 'pending')
                                      ->count(),
            'serving' => TblAppointment::whereDate('date', $today)
                                      ->where('status', 'serving')
                                      ->where('user_id', $userId)
                                      ->count(),
            'no_show' => TblAppointment::whereDate('date', $today)
                                      ->where('status', 'no_show')
                                      ->count(),
            'completed' => TblAppointment::whereDate('date', $today)
                                        ->where('status', 'completed')
                                        ->where('user_id', $userId)
                                        ->count(),
            'cancelled' => TblAppointment::whereDate('date', $today)
                                        ->where('status', 'cancelled')
                                        ->where('user_id', $userId)
                                        ->count(),
            'all_completed' => TblAppointment::whereDate('date', $today)
                                            ->where('status', 'completed')
                                            ->count(),
            'all_cancelled' => TblAppointment::whereDate('date', $today)
                                            ->where('status', 'cancelled')
                                            ->count(),
        ];

        return response()->json([
            'success' => true,
            'tableAll' => $tableAll,
            'tableNidRegistration' => $tableNidRegistration,
            'tableStatusInquiry' => $tableStatusInquiry,
            'tableNidUpdating' => $tableNidUpdating,
            'stats' => $stats
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error fetching appointments: ' . $e->getMessage()
        ]);
    }
}

   public function updateWindow(Request $request)
{
    try {
        $validated = $request->validate([
            'n_id' => 'required|integer|exists:tbl_appointment,n_id',
            'window_num' => 'required',
            'status' => 'sometimes|required|in:serving,completed,no_show,cancelled'
        ]);

        $appointment = TblAppointment::find($validated['n_id']);

        if (!$appointment) {
            return response()->json([
                'success' => false,
                'message' => 'Appointment not found.'
            ]);
        }

        $windowNum = (string) $validated['window_num'];
        
        // ALWAYS set the user_id and window_num for ANY status change
        $appointment->user_id = Auth::id();
        $appointment->window_num = $windowNum;
        
        // Set status
        $appointment->status = $validated['status'];
        
        // If completed, set time_catered
        if ($validated['status'] === 'completed') {
            $appointment->time_catered = Carbon::now('Asia/Manila');
        }
        
        // IMPORTANT: For no_show, explicitly update the timestamp to ensure proper sorting
        // This ensures the most recently marked no_show appears in the 2nd row
        if ($validated['status'] === 'no_show') {
            $appointment->updated_at = Carbon::now('Asia/Manila');
        }
        
        // For status changes from no_show to serving/completed, also update timestamp
        // This helps with sorting when a no_show is finally served
        if ($appointment->getOriginal('status') === 'no_show' && in_array($validated['status'], ['serving', 'completed'])) {
            $appointment->updated_at = Carbon::now('Asia/Manila');
        }
        
        $appointment->save();

        $statusMessage = [
            'serving' => 'is now being served',
            'completed' => 'has been completed',
            'no_show' => 'marked as no show',
            'cancelled' => 'has been cancelled'
        ];

        return response()->json([
            'success' => true,
            'message' => 'Queue number ' . $appointment->q_id . ' ' . ($statusMessage[$validated['status']] ?? 'updated') . ' successfully!'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}

    public function recentTransactions()
{
    try {
        $userId = Auth::id();
        $today = Carbon::now('Asia/Manila')->toDateString();
        
        $transactions = TblAppointment::whereDate('date', $today)
                                      ->whereIn('status', ['completed', 'cancelled'])
                                      ->where('user_id', $userId)
                                      ->orderByRaw("CASE 
                                          WHEN time_catered IS NOT NULL THEN time_catered 
                                          ELSE updated_at 
                                      END DESC")
                                      ->paginate(10);

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

    public function getQueuedAppointments()
    {
        try {
            $today = Carbon::now('Asia/Manila')->toDateString();
            $windowNum = Auth::user()->window_num;
            $userId = Auth::id();

            if (!$windowNum) {
                return response()->json([
                    'success' => true,
                    'queued' => []
                ]);
            }

            $queued = TblAppointment::whereDate('date', $today)
                ->where('window_num', (string) $windowNum)
                ->whereIn('status', ['completed', 'cancelled'])
                ->where('user_id', $userId)
                ->orderByRaw("CASE 
                    WHEN time_catered IS NOT NULL THEN time_catered 
                    ELSE updated_at 
                END DESC")
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
    if ($request->ajax() || $request->wantsJson()) {
        try {
            $perPage = $request->get('per_page', 10);
            $perPage = in_array($perPage, [10, 20, 50, 100]) ? $perPage : 10;
            $userId = Auth::id();
            $today = Carbon::now('Asia/Manila')->toDateString();
            
            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }
            
            // Explicitly select all fields including priority_type
            $completedTransactions = TblAppointment::whereDate('date', $today)
                                                  ->whereIn('status', ['completed', 'cancelled'])
                                                  ->where('user_id', $userId)
                                                  ->select([
                                                      'n_id', 'q_id', 'fname', 'mname', 'lname', 'suffix',
                                                      'queue_for', 'time_catered', 'updated_at', 'window_num',
                                                      'status', 'trn', 'PCN', 'priority_type', 'created_at', 'date'
                                                  ])
                                                  ->orderByRaw("CASE 
                                                      WHEN time_catered IS NOT NULL THEN time_catered 
                                                      ELSE updated_at 
                                                  END DESC")
                                                  ->paginate($perPage)
                                                  ->withQueryString();
            
            // Debug: Log the first transaction to check if priority_type exists
            \Log::info('First transaction priority_type: ' . ($completedTransactions->first()->priority_type ?? 'null'));
            
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
            \Log::error('Error in getTransactionsPage: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error loading transactions: ' . $e->getMessage()
            ], 500);
        }
    }
    
    $today = Carbon::now('Asia/Manila')->toDateString();
    
    // Also update the non-AJAX query to explicitly select fields
    $completedTransactions = TblAppointment::whereDate('date', $today)
                                          ->whereIn('status', ['completed', 'cancelled'])
                                          ->where('user_id', Auth::id())
                                          ->select([
                                              'n_id', 'q_id', 'fname', 'mname', 'lname', 'suffix',
                                              'queue_for', 'time_catered', 'updated_at', 'window_num',
                                              'status', 'trn', 'PCN', 'priority_type', 'created_at', 'date'
                                          ])
                                          ->orderByRaw("CASE 
                                              WHEN time_catered IS NOT NULL THEN time_catered 
                                              ELSE updated_at 
                                          END DESC")
                                          ->paginate(10);
        
    return view('operator.dashboard', compact('completedTransactions'));
}

    public function exportPDF()
{
    try {
        $userId = Auth::id();
        $windowNum = Auth::user()->window_num ?? '1';
        $today = Carbon::now('Asia/Manila')->toDateString();
        
        $completedAppointments = TblAppointment::whereDate('date', $today)
                                              ->whereIn('status', ['completed', 'cancelled'])
                                              ->where('user_id', $userId)
                                              ->orderByRaw("CASE 
                                                  WHEN time_catered IS NOT NULL THEN time_catered 
                                                  ELSE updated_at 
                                              END DESC")
                                              ->get();
        
        if ($completedAppointments->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No transactions to export for today'
            ], 404);
        }
        
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
        
        $filename = 'TODAYS-TRANSACTIONS-' . Carbon::now('Asia/Manila')->format('Y-m-d') . '.pdf';
        
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
        \Log::error('Operator PDF Export Error: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to generate PDF. Please try again.'
        ], 500);
    }
}

    public function exportExcel()
{
    try {
        $userId = Auth::id();
        $windowNum = Auth::user()->window_num ?? '1';
        $now = Carbon::now('Asia/Manila');
        $today = $now->toDateString();
        
        $completedAppointments = TblAppointment::whereDate('date', $today)
                                              ->whereIn('status', ['completed', 'cancelled'])
                                              ->where('user_id', $userId)
                                              ->orderByRaw("CASE 
                                                  WHEN time_catered IS NOT NULL THEN time_catered 
                                                  ELSE updated_at 
                                              END DESC")
                                              ->get();
        
        if ($completedAppointments->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No transactions to export for today'
            ], 404);
        }
        
        $completedAppointments = $completedAppointments->map(function($appointment, $index) {
            $appointment->row_number = $index + 1;
            return $appointment;
        });
        
        $totalCompleted = $completedAppointments->count();
        $dateToday = $now->format('F j, Y');
        $timeGenerated = $now->format('h:i A');
        
        $export = new OperatorAppointmentsExport(
            $completedAppointments,
            $totalCompleted,
            $dateToday,
            $timeGenerated,
            $windowNum,
            $userId
        );
        
        $filename = 'TODAYS-TRANSACTIONS-' . $now->format('Y-m-d-H-i') . '.xlsx';
        
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
        \Log::error('Operator Excel Export Error: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to generate Excel file. Please try again.'
        ], 500);
    }
}
}