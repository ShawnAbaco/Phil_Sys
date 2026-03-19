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

    // ===== REUSABLE SORTING FUNCTION =====
    $applySortingLogic = function($collection) {
        // Separate appointments by status
        $serving = $collection->where('status', 'serving')->values();
        
        // Separate pending by priority - using updated_at
        $highPriorityPending = $collection->where('status', 'pending')
                                         ->filter(function($app) {
                                             return in_array($app->priority_type, ['senior', 'infant', 'pwd', 'pregnant']);
                                         })
                                         ->sortBy('updated_at')
                                         ->values();
        
        $regularPending = $collection->where('status', 'pending')
                                    ->where('priority_type', 'regular')
                                    ->sortBy('updated_at')
                                    ->values();
        
        $noShow = $collection->where('status', 'no_show')
                            ->sortBy('updated_at')
                            ->values();

        // Custom sorting logic for appointments
        $sorted = collect();

        // 1. Add serving appointments first (should be only one per user)
        foreach ($serving as $app) {
            $sorted->push($app);
        }

        // 2. Handle high priority pending with special positioning
        if ($highPriorityPending->count() > 0) {
            // Get the oldest high priority pending (based on updated_at)
            $oldestHighPriority = $highPriorityPending->shift();
            
            // Insert the oldest high priority at the next position
            $sorted->push($oldestHighPriority);
            
            // The rest of high priority pending will be added at the end
        }

        // 3. Handle no_show appointments with special positioning
        if ($noShow->count() > 0) {
            // Get the oldest no_show (first in the sorted collection)
            $oldestNoShow = $noShow->shift();
            
            // Insert the oldest no_show after high priority
            $sorted->push($oldestNoShow);
            
            // The rest of no_show will be added at the end
        }

        // 4. Add regular pending appointments (sorted by updated_at)
        foreach ($regularPending as $app) {
            $sorted->push($app);
        }

        // 5. Add remaining high priority pending at the end
        foreach ($highPriorityPending as $app) {
            $sorted->push($app);
        }

        // 6. Add remaining no_show appointments at the very end
        foreach ($noShow as $app) {
            $sorted->push($app);
        }

        return $sorted;
    };

    // Apply sorting to ALL appointments (for All Services tab)
    $appointments = $applySortingLogic($allAppointments);

    // Filter appointments by service type FIRST, THEN apply sorting to each service-specific collection
    $nidRegistrationCollection = $allAppointments->filter(function($app) {
        return $app->queue_for === 'NID Registration';
    })->values();
    $nidRegistrationAppointments = $applySortingLogic($nidRegistrationCollection);

    $statusInquiryCollection = $allAppointments->filter(function($app) {
        return $app->queue_for === 'Status Inquiry';
    })->values();
    $statusInquiryAppointments = $applySortingLogic($statusInquiryCollection);

    $nidUpdatingCollection = $allAppointments->filter(function($app) {
        return $app->queue_for === 'Updating';
    })->values();
    $nidUpdatingAppointments = $applySortingLogic($nidUpdatingCollection);

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
    $queueCount = TblAppointment::whereDate('date', $today)->count();

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

        // Get today's appointments
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

        // ===== FUNCTION TO APPLY SORTING LOGIC TO ANY COLLECTION =====
        $applySortingLogic = function($collection) use ($userId) {
            // Separate appointments by status
            $serving = $collection->where('status', 'serving')->values();
            
            $highPriorityPending = $collection->where('status', 'pending')
                                             ->filter(function($app) {
                                                 return in_array($app->priority_type, ['senior', 'infant', 'pwd', 'pregnant']);
                                             })
                                             ->sortBy('updated_at')
                                             ->values();
            
            $regularPending = $collection->where('status', 'pending')
                                        ->where('priority_type', 'regular')
                                        ->sortBy('updated_at')
                                        ->values();
            
            $noShow = $collection->where('status', 'no_show')
                                ->sortBy('updated_at')
                                ->values();

            // Custom sorting logic
            $sorted = collect();

            foreach ($serving as $app) {
                $sorted->push($app);
            }

            if ($highPriorityPending->count() > 0) {
                $oldestHighPriority = $highPriorityPending->shift();
                $sorted->push($oldestHighPriority);
            }

            if ($noShow->count() > 0) {
                $oldestNoShow = $noShow->shift();
                $sorted->push($oldestNoShow);
            }

            foreach ($regularPending as $app) {
                $sorted->push($app);
            }

            foreach ($highPriorityPending as $app) {
                $sorted->push($app);
            }

            foreach ($noShow as $app) {
                $sorted->push($app);
            }

            return $sorted;
        };

        // Apply sorting to ALL appointments
        $appointments = $applySortingLogic($allAppointments);

        // Filter by service type FIRST, THEN apply sorting
        $nidRegistrationCollection = $allAppointments->filter(function($app) {
            return $app->queue_for === 'NID Registration';
        })->values();
        $nidRegistrationAppointments = $applySortingLogic($nidRegistrationCollection);

        $statusInquiryCollection = $allAppointments->filter(function($app) {
            return $app->queue_for === 'Status Inquiry';
        })->values();
        $statusInquiryAppointments = $applySortingLogic($statusInquiryCollection);

        $nidUpdatingCollection = $allAppointments->filter(function($app) {
            return $app->queue_for === 'Updating';
        })->values();
        $nidUpdatingAppointments = $applySortingLogic($nidUpdatingCollection);

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

        // Get statistics
        $stats = [
            'total' => TblAppointment::whereDate('date', $today)->count(),
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

/**
 * Trigger announcement on client display
 */
public function triggerAnnouncement(Request $request)
{
    try {
        $request->validate([
            'window_num' => 'required|string',
            'queue_number' => 'required|string',
            'client_name' => 'nullable|string'
        ]);

        // Store announcement in cache for 10 seconds
        $announcement = [
            'window' => $request->window_num,
            'queue' => $request->queue_number,
            'name' => $request->client_name,
            'timestamp' => now()->timestamp
        ];
        
        Cache::put('last_announcement', $announcement, 10);
        
        // Also store in session as backup
        session(['last_announcement' => $announcement]);
        
        return response()->json([
            'success' => true,
            'message' => 'Announcement triggered'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}


}