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
        $appointments = TblAppointment::whereDate('date', $today)
                                      ->whereIn('status', ['pending', 'serving', 'no_show'])
                                      ->orderByRaw("
                                          CASE 
                                              WHEN status = 'serving' THEN 1
                                              WHEN status = 'pending' THEN 2
                                              WHEN status = 'no_show' THEN 3
                                              ELSE 4
                                          END
                                      ")
                                      ->orderBy('date', 'asc')
                                      ->get();

        // Filter appointments by service type
        $nidRegistrationAppointments = $appointments->filter(function($app) {
            return $app->queue_for === 'NID Registration';
        });

        $statusInquiryAppointments = $appointments->filter(function($app) {
            return $app->queue_for === 'Status Inquiry';
        });

        $nidUpdatingAppointments = $appointments->filter(function($app) {
            return $app->queue_for === 'Updating';
        });

        // Get RECENT COMPLETED & CANCELLED TRANSACTIONS
        $completedTransactions = TblAppointment::whereIn('status', ['completed', 'cancelled'])
                                              ->where('user_id', $userId)
                                              ->orderByRaw("CASE 
                                                  WHEN time_catered IS NOT NULL THEN time_catered 
                                                  ELSE updated_at 
                                              END DESC")
                                              ->paginate(10);

        // Get queue count for today (all appointments except cancelled)
        $queueCount = TblAppointment::whereDate('date', $today)
                                   ->whereNotIn('status', ['cancelled'])
                                   ->count();

        // Get pending appointments count
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

        // Get serving appointments count
        $servingCount = TblAppointment::whereDate('date', $today)
                                      ->where('status', 'serving')
                                      ->count();

        // Get no show appointments count
        $noShowCount = TblAppointment::whereDate('date', $today)
                                     ->where('status', 'no_show')
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
            'noShowCount'
        ));
    }

    public function fetchAppointments()
    {
        try {
            $today = Carbon::now('Asia/Manila')->toDateString();
            $userId = Auth::id();

            // Get today's appointments with status: pending, serving, or no_show
            $appointments = TblAppointment::whereDate('date', $today)
                                          ->whereIn('status', ['pending', 'serving', 'no_show'])
                                          ->orderByRaw("
                                              CASE 
                                                  WHEN status = 'serving' THEN 1
                                                  WHEN status = 'pending' THEN 2
                                                  WHEN status = 'no_show' THEN 3
                                                  ELSE 4
                                              END
                                          ")
                                          ->orderBy('date', 'asc')
                                          ->get();

            // Filter appointments by service type
            $nidRegistrationAppointments = $appointments->filter(function($app) {
                return $app->queue_for === 'NID Registration';
            });

            $statusInquiryAppointments = $appointments->filter(function($app) {
                return $app->queue_for === 'Status Inquiry';
            });

            $nidUpdatingAppointments = $appointments->filter(function($app) {
                return $app->queue_for === 'Updating';
            });

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
                'tableId' => 'nid-updating'
            ])->render();

            // Get statistics
            $stats = [
                'total' => TblAppointment::whereDate('date', $today)
                                         ->whereNotIn('status', ['cancelled'])
                                         ->count(),
                'pending' => TblAppointment::whereDate('date', $today)
                                          ->where('status', 'pending')
                                          ->count(),
                'serving' => TblAppointment::whereDate('date', $today)
                                          ->where('status', 'serving')
                                          ->count(),
                'no_show' => TblAppointment::whereDate('date', $today)
                                          ->where('status', 'no_show')
                                          ->count(),
                'completed' => TblAppointment::whereDate('date', $today)
                                            ->where('status', 'completed')
                                            ->where('user_id', $userId)
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

            // Check if this appointment belongs to the current user's window
            if ($appointment->user_id && $appointment->user_id != Auth::id() && $validated['status'] !== 'serving') {
                return response()->json([
                    'success' => false,
                    'message' => 'You can only update your own appointments.'
                ]);
            }

            $windowNum = (string) $validated['window_num'];
            
            // Determine which fields to update based on status
            if ($validated['status'] === 'serving') {
                $appointment->window_num = $windowNum;
                $appointment->status = 'serving';
                // Don't set time_catered yet, only when completed
            } else {
                $appointment->status = $validated['status'];
                
                // If completed, set time_catered and user_id
                if ($validated['status'] === 'completed') {
                    $appointment->time_catered = Carbon::now('Asia/Manila');
                    $appointment->user_id = Auth::id();
                }
                
                // If cancelled or no_show, just update status
                // window_num might already be set from serving
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
            
            $transactions = TblAppointment::whereIn('status', ['completed', 'cancelled'])
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
                
                if (!$userId) {
                    return response()->json([
                        'success' => false,
                        'message' => 'User not authenticated'
                    ], 401);
                }
                
                $completedTransactions = TblAppointment::whereIn('status', ['completed', 'cancelled'])
                    ->where('user_id', $userId)
                    ->orderByRaw("CASE 
                        WHEN time_catered IS NOT NULL THEN time_catered 
                        ELSE updated_at 
                    END DESC")
                    ->paginate($perPage)
                    ->withQueryString();
                
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
        
        $completedTransactions = TblAppointment::whereIn('status', ['completed', 'cancelled'])
            ->where('user_id', Auth::id())
            ->orderByRaw("CASE 
                WHEN time_catered IS NOT NULL THEN time_catered 
                ELSE updated_at 
            END DESC")
            ->paginate(10);
            
        return view('operator.dashboard', compact('completedTransactions'));
    }

    /**
     * Export appointments as PDF
     */
    public function exportPDF()
    {
        try {
            $userId = Auth::id();
            $windowNum = Auth::user()->window_num ?? '1';
            
            $completedAppointments = TblAppointment::whereIn('status', ['completed', 'cancelled'])
                                                  ->where('user_id', $userId)
                                                  ->orderByRaw("CASE 
                                                      WHEN time_catered IS NOT NULL THEN time_catered 
                                                      ELSE updated_at 
                                                  END DESC")
                                                  ->get();
            
            if ($completedAppointments->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No transactions to export'
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
            
            $filename = 'TRANSACTIONS-' . Carbon::now('Asia/Manila')->format('Y-m-d') . '.pdf';
            
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

    /**
     * Export appointments as Excel
     */
    public function exportExcel()
    {
        try {
            $userId = Auth::id();
            $windowNum = Auth::user()->window_num ?? '1';
            $now = Carbon::now('Asia/Manila');
            
            $completedAppointments = TblAppointment::whereIn('status', ['completed', 'cancelled'])
                                                  ->where('user_id', $userId)
                                                  ->orderByRaw("CASE 
                                                      WHEN time_catered IS NOT NULL THEN time_catered 
                                                      ELSE updated_at 
                                                  END DESC")
                                                  ->get();
            
            if ($completedAppointments->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No transactions to export'
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
            
            $filename = 'TRANSACTIONS-' . $now->format('Y-m-d-H-i') . '.xlsx';
            
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