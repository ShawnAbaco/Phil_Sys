<?php
// app/Http/Controllers/Appointment/AppointmentController.php

namespace App\Http\Controllers\Screener;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TblAppointment;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AppointmentsExport;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ADashboardController extends Controller
{
   public function dashboard()
{
    $today = Carbon::now('Asia/Manila')->toDateString();
    
    $totalToday = TblAppointment::whereDate('date', $today)->count();
    $pendingCount = TblAppointment::whereDate('date', $today)->where('status', 'pending')->count();
    $completedCount = TblAppointment::whereDate('date', $today)->where('status', 'completed')->count();
    $cancelledCount = TblAppointment::whereDate('date', $today)->where('status', 'cancelled')->count();
    
    // Daily data for last 7 days
    $dailyLabels = [];
    $dailyIssued = [];
    $dailyCompleted = [];
    
    for ($i = 6; $i >= 0; $i--) {
        $date = Carbon::now('Asia/Manila')->subDays($i)->toDateString();
        $dailyLabels[] = Carbon::now('Asia/Manila')->subDays($i)->format('M d');
        $dailyIssued[] = TblAppointment::whereDate('date', $date)->count();
        $dailyCompleted[] = TblAppointment::whereDate('date', $date)->where('status', 'completed')->count();
    }
    
    // Status distribution
    $statusData = [
        'pending' => TblAppointment::whereDate('date', $today)->where('status', 'pending')->count(),
        'serving' => TblAppointment::whereDate('date', $today)->where('status', 'serving')->count(),
        'completed' => $completedCount,
        'cancelled' => $cancelledCount,
        'no_show' => TblAppointment::whereDate('date', $today)->where('status', 'no_show')->count(),
    ];
    // Priority counts
        $priorityCounts = [
            'senior' => TblAppointment::whereDate('date', $today)->where('priority_type', 'senior')->count(),
            'infant' => TblAppointment::whereDate('date', $today)->where('priority_type', 'infant')->count(),
            'pwd' => TblAppointment::whereDate('date', $today)->where('priority_type', 'pwd')->count(),
            'pregnant' => TblAppointment::whereDate('date', $today)->where('priority_type', 'pregnant')->count(),
            'regular' => TblAppointment::whereDate('date', $today)->where('priority_type', 'regular')->count(),
        ];
    
    // Recent activities
    $recentActivities = TblAppointment::whereDate('date', $today)
        ->whereIn('status', ['completed', 'cancelled'])
        ->orderBy('updated_at', 'desc')
        ->limit(5)
        ->get()
        ->map(function($appointment) {
            return [
                'type' => $appointment->status,
                'title' => $appointment->status === 'completed' ? 'Appointment Completed' : 'Appointment Cancelled',
                'description' => $appointment->q_id . ' - ' . $appointment->lname . ', ' . $appointment->fname,
                'time' => Carbon::parse($appointment->updated_at)->setTimezone('Asia/Manila')->diffForHumans(),
            ];
        });
    
    return view('screener.dashboard', compact(
        'totalToday', 'pendingCount', 'completedCount', 'cancelledCount',
        'dailyLabels', 'dailyIssued', 'dailyCompleted', 'statusData','priorityCounts', 'recentActivities'
    ));
}

    public function getTodayAppointments()
    {
        try {
            $today = Carbon::now('Asia/Manila')->toDateString();

            // Get today's appointments that are pending or serving
            $appointments = TblAppointment::whereDate('date', $today)
                ->where(function($query) {
                    $query->whereIn('status', ['pending', 'serving'])
                          ->orWhereNull('status')
                          ->orWhere(function($q) {
                              $q->whereNull('time_catered')
                                ->whereNotIn('status', ['completed', 'cancelled', 'no_show']);
                          });
                })
                ->select(['n_id', 'q_id', 'fname', 'mname', 'lname', 'suffix', 'queue_for', 
                         'date', 'created_at', 'trn', 'time_catered', 'priority_type', 'status', 'window_num'])
                ->orderBy('created_at', 'desc')
                ->get();

            // Format appointments for JSON response
            $formattedAppointments = $appointments->map(function($appointment) {
                return [
                    'n_id' => $appointment->n_id,
                    'q_id' => $appointment->q_id,
                    'fname' => $appointment->fname,
                    'mname' => $appointment->mname,
                    'lname' => $appointment->lname,
                    'suffix' => $appointment->suffix,
                    'queue_for' => $appointment->queue_for,
                    'date' => $appointment->date,
                    'created_at' => $appointment->created_at ? $appointment->created_at->format('Y-m-d H:i:s') : null,
                    'trn' => $appointment->trn,
                    'time_catered' => $appointment->time_catered,
                    'priority_type' => $appointment->priority_type ?? 'regular',
                    'status' => $appointment->status ?? 'pending',
                    'window_num' => $appointment->window_num
                ];
            });

            // Get statistics
            $stats = [
                'total' => TblAppointment::whereDate('date', $today)->count(),
                'pending' => TblAppointment::whereDate('date', $today)
                    ->where(function($query) {
                        $query->where('status', 'pending')
                              ->orWhere(function($q) {
                                  $q->whereNull('status')
                                    ->whereNull('time_catered');
                              });
                    })
                    ->count(),
                'completed' => TblAppointment::whereDate('date', $today)
                    ->where(function($query) {
                        $query->where('status', 'completed')
                              ->orWhere(function($q) {
                                  $q->whereNull('status')
                                    ->whereNotNull('time_catered');
                              });
                    })
                    ->count(),
            ];

            return response()->json([
                'success' => true,
                'appointments' => $formattedAppointments,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching appointments: ' . $e->getMessage()
            ], 500);
        }
    }

    public function serve($id)
    {
        try {
            $now = Carbon::now('Asia/Manila');
            
            $appointment = TblAppointment::findOrFail($id);
            $appointment->window_num = Session::get('window_num', '1');
            $appointment->time_catered = $now;
            $appointment->status = 'serving'; // Update status to serving
            $appointment->save();

            return response()->json([
                'success' => true, 
                'message' => 'Appointment marked as serving'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 404);
        }
    }

    /**
     * Update appointment status (for cancel, complete, no-show actions)
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,serving,completed,cancelled,no_show'
            ]);

            $appointment = TblAppointment::findOrFail($id);
            $appointment->status = $request->status;
            
            // If status is completed, also set time_catered if not already set
            if ($request->status === 'completed' && !$appointment->time_catered) {
                $appointment->time_catered = Carbon::now('Asia/Manila');
            }
            
            // If status is cancelled or no_show, we might want to record the time
            if (in_array($request->status, ['cancelled', 'no_show']) && !$appointment->time_catered) {
                $appointment->time_catered = Carbon::now('Asia/Manila');
            }
            
            $appointment->save();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'new_status' => $request->status
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function search(Request $request)
    {
        $searchTerm = $request->get('q');

        $appointments = TblAppointment::where('q_id', 'LIKE', "%{$searchTerm}%")
                                      ->orWhere('fname', 'LIKE', "%{$searchTerm}%")
                                      ->orWhere('lname', 'LIKE', "%{$searchTerm}%")
                                      ->orWhere('trn', 'LIKE', "%{$searchTerm}%")
                                      ->orderBy('created_at', 'desc')
                                      ->limit(20)
                                      ->get();

        return response()->json($appointments);
    }

    public function getTransactionsPage(Request $request)
    {
        $today = Carbon::now('Asia/Manila')->toDateString();
        $perPage = $request->get('per_page', 10);
        $perPage = in_array($perPage, [10, 20, 50, 100]) ? $perPage : 10;
        
        // Get TODAY'S transactions that are completed or cancelled (EXCLUDE no_show)
        $completedTransactions = TblAppointment::whereDate('date', $today)
                                              ->where(function($query) {
                                                  $query->whereIn('status', ['completed', 'cancelled'])
                                                        ->orWhere(function($q) {
                                                            $q->whereNotNull('time_catered')
                                                              ->whereNotIn('status', ['no_show', 'pending', 'serving']);
                                                        });
                                              })
                                              ->select(['n_id', 'q_id', 'fname', 'mname', 'lname', 'suffix',
                                                       'queue_for', 'time_catered', 'window_num', 
                                                       'priority_type', 'status', 'updated_at'])
                                              ->orderBy('updated_at', 'desc')
                                              ->paginate($perPage)
                                              ->withQueryString();
        
        if ($request->ajax()) {
            $tableHtml = view('screener.partials.transactions-table', compact('completedTransactions'))->render();
            $paginationHtml = view('screener.partials.pagination-links', compact('completedTransactions'))->render();
            $showingInfo = 'Showing ' . $completedTransactions->firstItem() . '-' . $completedTransactions->lastItem() . ' of ' . $completedTransactions->total();
            
            return response()->json([
                'success' => true,
                'table' => $tableHtml,
                'pagination' => $paginationHtml,
                'showing' => $showingInfo
            ]);
        }
        
        return $completedTransactions;
    }
    
    /**
     * Export completed appointments as PDF
     */
    public function exportPDF()
    {
        try {
            $today = Carbon::now('Asia/Manila')->toDateString();
            
            // Get completed and cancelled appointments for today (EXCLUDE no_show)
            $completedAppointments = TblAppointment::whereDate('date', $today)
                                                ->where(function($query) {
                                                    $query->whereIn('status', ['completed', 'cancelled'])
                                                          ->orWhere(function($q) {
                                                              $q->whereNotNull('time_catered')
                                                                ->whereNotIn('status', ['no_show', 'pending', 'serving']);
                                                          });
                                                })
                                                ->orderBy('time_catered', 'desc')
                                                ->get();
            
            // Add row numbers and format created_at
            $completedAppointments = $completedAppointments->map(function($appointment, $index) {
                $appointment->row_number = $index + 1;
                $appointment->formatted_created = $appointment->created_at 
                    ? Carbon::parse($appointment->created_at)->setTimezone('Asia/Manila')->format('M d, Y h:i A')
                    : 'N/A';
                return $appointment;
            });
            
            // Get summary statistics
            $totalToday = TblAppointment::whereDate('date', $today)->count();
            $completedCount = $completedAppointments->count();
            $pendingCount = $totalToday - $completedCount;
            
            $dateToday = Carbon::now('Asia/Manila')->format('F j, Y');
            $timeGenerated = Carbon::now('Asia/Manila')->format('h:i A');
            
            $pdf = Pdf::loadView('screener.exports.appointments-pdf', compact(
                'completedAppointments',
                'completedCount',
                'pendingCount',
                'totalToday',
                'dateToday',
                'timeGenerated'
            ));
            
            $pdf->setPaper('A4', 'landscape');
            
            // Generate filename
            $filename = 'RECENT-TRANSACTIONS-' . Carbon::now('Asia/Manila')->format('Y-m-d') . '.pdf';
            
            // Set headers to force download and correct MIME type
            return response($pdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Content-Transfer-Encoding' => 'binary',
                'Accept-Ranges' => 'bytes',
                'Cache-Control' => 'private, max-age=0, must-revalidate',
                'Pragma' => 'public'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('PDF Export Error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to generate PDF'], 500);
        }
    }

    /**
     * Export completed appointments as Excel
     */
    public function exportExcel()
    {
        try {
            $today = Carbon::now('Asia/Manila')->toDateString();
            $now = Carbon::now('Asia/Manila');
            
            // Get completed and cancelled appointments for today (EXCLUDE no_show)
            $completedAppointments = TblAppointment::whereDate('date', $today)
                ->where(function($query) {
                    $query->whereIn('status', ['completed', 'cancelled'])
                          ->orWhere(function($q) {
                              $q->whereNotNull('time_catered')
                                ->whereNotIn('status', ['no_show', 'pending', 'serving']);
                          });
                })
                ->orderBy('time_catered', 'desc')
                ->get();
            
            // Add row numbers and format created_at
            $completedAppointments = $completedAppointments->map(function($appointment, $index) {
                $appointment->row_number = $index + 1;
                $appointment->formatted_created = $appointment->created_at 
                    ? Carbon::parse($appointment->created_at)->setTimezone('Asia/Manila')->format('M d, Y h:i A')
                    : 'N/A';
                return $appointment;
            });
            
            // Get statistics
            $totalToday = TblAppointment::whereDate('date', $today)->count();
            $completedCount = $completedAppointments->count();
            $dateToday = $now->format('F j, Y');
            
            $export = new AppointmentsExport(
                $completedAppointments, 
                $totalToday, 
                $completedCount,
                $dateToday
            );
            
            $filename = 'RECENT-TRANSACTIONS-' . $now->format('Y-m-d-H-i') . '.xlsx';
            
            // For Excel - using the package but with explicit headers
            return Excel::download($export, $filename, \Maatwebsite\Excel\Excel::XLSX, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'max-age=0',
                'Content-Transfer-Encoding' => 'binary',
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Excel Export Error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to generate Excel file'], 500);
        }
    }

    public function storeCategory(Request $request)
    {
        session(['last_category' => $request->category]);
        return response()->json(['success' => true]);
    }

    public function storePriority(Request $request)
    {
        $request->validate([
            'priority_type' => 'required|string',
            'form_type' => 'required|string|in:nid,status,update'
        ]);
                    
        // Store in session with form-specific key
        session(['last_priority_' . $request->form_type => $request->priority_type]);
        
        // Also store the last used priority for the current form type
        session(['last_priority' => $request->priority_type]);
        session(['last_priority_form' => $request->form_type]);
        
        return response()->json(['success' => true]);
    }


}