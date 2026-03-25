<?php
// app/Http/Controllers/Appointment/AppointmentController.php

namespace App\Http\Controllers\Appointment;

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

class AppointmentController extends Controller
{
    public function issuance(Request $request)
    {
        Carbon::setLocale('en');
        $today = Carbon::now('Asia/Manila')->toDateString();

        // Get today's appointments - Show pending and serving appointments
        // SORT BY NEWEST FIRST (DESCENDING) based on created_at
        $appointments = TblAppointment::whereDate('date', $today)
                                      ->where(function($query) {
                                          // Show appointments that are pending or serving
                                          $query->whereIn('status', ['pending', 'serving'])
                                                ->orWhereNull('status') // For backward compatibility
                                                ->orWhere(function($q) {
                                                    $q->whereNull('time_catered')
                                                      ->whereNotIn('status', ['completed', 'cancelled', 'no_show']);
                                                });
                                      })
                                      ->orderBy('created_at', 'desc')  // Newest first
                                      ->get();

        // Get RECENT TRANSACTIONS - ONLY completed and cancelled statuses for today (EXCLUDE no_show)
        $completedTransactions = TblAppointment::whereDate('date', $today)
                                              ->where(function($query) {
                                                  $query->whereIn('status', ['completed', 'cancelled'])
                                                        ->orWhere(function($q) {
                                                            $q->whereNotNull('time_catered')
                                                              ->whereNotIn('status', ['no_show']);
                                                        });
                                              })
                                              ->orderBy('updated_at', 'desc')
                                              ->paginate(10);

        // Get queue count for today (all appointments)
        $queueCount = TblAppointment::whereDate('date', $today)->count();

        // Get pending appointments count (pending status)
        $pendingCount = TblAppointment::whereDate('date', $today)
                                      ->where(function($query) {
                                          $query->where('status', 'pending')
                                                ->orWhere(function($q) {
                                                    $q->whereNull('status')
                                                      ->whereNull('time_catered');
                                                });
                                      })
                                      ->count();

        // Get completed appointments count (completed status only - EXCLUDE cancelled)
        $completedCount = TblAppointment::whereDate('date', $today)
                                        ->where(function($query) {
                                            $query->where('status', 'completed')
                                                  ->orWhere(function($q) {
                                                      $q->whereNull('status')
                                                        ->whereNotNull('time_catered');
                                                  });
                                        })
                                        ->count();

        return view('appointment.issuance', compact(
            'appointments',
            'completedTransactions',
            'queueCount',
            'pendingCount',
            'completedCount'
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

    public function issue(Request $request)
{
    // Get the selected category
    $category = $request->input('category');
    
    // Define validation rules based on category
    $rules = [
        'category' => 'required|string|in:NID Registration,Status Inquiry,Updating',
    ];

    // Add conditional validation rules based on category
    if ($category === 'NID Registration') {
        $rules = array_merge($rules, [
            'fname_nid' => 'required|string|max:99|regex:/^[A-Za-zÑñ\s\-]+$/',
            'mname_nid' => 'nullable|string|max:99|regex:/^[A-Za-zÑñ\s\-]*$/',
            'lname_nid' => 'required|string|max:99|regex:/^[A-Za-zÑñ\s\-]+$/',
            'suffix_nid' => 'nullable|string|max:3|regex:/^[A-Za-zÑñ\s\-\.]*$/',
            'age_category_nid' => 'required|string|max:99',
            'birthdate_nid' => 'required|date',
            'priority_type_nid' => 'required|string|in:regular,senior,infant,pwd,pregnant',
        ]);
    } elseif ($category === 'Status Inquiry') {
        $rules = array_merge($rules, [
            'fname_status' => 'required|string|max:99|regex:/^[A-Za-z\s\-]+$/',
            'mname_status' => 'nullable|string|max:99|regex:/^[A-Za-z\s\-]*$/',
            'lname_status' => 'required|string|max:99|regex:/^[A-Za-z\s\-]+$/',
            'suffix_status' => 'nullable|string|max:3|regex:/^[A-Za-z\s\-]*$/',
            'age_category_status' => 'required|string|max:99',
            'birthdate_status' => 'required|date',
            'trn' => 'nullable|string|max:29',
            'priority_type_status' => 'required|string|in:regular,senior,infant,pwd,pregnant',
        ]);
    } elseif ($category === 'Updating') {
        $rules = array_merge($rules, [
            'fname_update' => 'required|string|max:99|regex:/^[A-Za-z\s\-]+$/',
            'mname_update' => 'nullable|string|max:99|regex:/^[A-Za-z\s\-]*$/',
            'lname_update' => 'required|string|max:99|regex:/^[A-Za-z\s\-]+$/',
            'suffix_update' => 'nullable|string|max:3|regex:/^[A-Za-z\s\-]*$/',
            'age_category_update' => 'required|string|max:99',
            'birthdate_update' => 'required|date',
            'PCN' => 'nullable|string|max:16',
            'priority_type_update' => 'required|string|in:regular,senior,infant,pwd,pregnant',
        ]);
    }

    // ===== INSERT CUSTOM MESSAGES HERE =====
    // After validation rules, add custom messages
    $messages = [
        'fname_nid.regex' => 'First name may only contain letters, spaces, and hyphens.',
        'mname_nid.regex' => 'Middle name may only contain letters, spaces, and hyphens.',
        'lname_nid.regex' => 'Last name may only contain letters, spaces, and hyphens.',
        'suffix_nid.regex' => 'Suffix may only contain letters, spaces, and hyphens.',
        'fname_status.regex' => 'First name may only contain letters, spaces, and hyphens.',
        'mname_status.regex' => 'Middle name may only contain letters, spaces, and hyphens.',
        'lname_status.regex' => 'Last name may only contain letters, spaces, and hyphens.',
        'suffix_status.regex' => 'Suffix may only contain letters, spaces, and hyphens.',
        'fname_update.regex' => 'First name may only contain letters, spaces, and hyphens.',
        'mname_update.regex' => 'Middle name may only contain letters, spaces, and hyphens.',
        'lname_update.regex' => 'Last name may only contain letters, spaces, and hyphens.',
        'suffix_update.regex' => 'Suffix may only contain letters, spaces, and hyphens.',
    ];

    // Validate the request with custom messages
    $validated = $request->validate($rules, $messages);

    try {
        // Rest of your method remains the same...
        // Set timezone to Philippine Time
        Carbon::setLocale('en');
        $now = Carbon::now('Asia/Manila');
        $today = $now->toDateString();

        // Map category to queue_for display value
        $queueFor = $category;

        // Get the appropriate field values based on category
        $formData = $this->extractFormData($request, $category);
        
        // Get priority type based on category
        $priorityType = $this->extractPriorityType($request, $category);

        // Determine the base prefix based on category
        $basePrefix = $this->getQueuePrefix($category);
        
        // For priority appointments, add 'P' prefix
        $prefix = $basePrefix;
        if ($priorityType !== 'regular') {
            $prefix = 'P' . $basePrefix;
        }

        // Get today's count for this specific queue type (with the same prefix)
        $todayCount = TblAppointment::whereDate('date', $today)
            ->where('q_id', 'LIKE', $prefix . '%')
            ->count() + 1;

        // Generate queue ID (format: PREFIX-XXX, resets daily)
        $queueId = $prefix . str_pad($todayCount, 3, '0', STR_PAD_LEFT);

        // Create new appointment
        $appointment = new TblAppointment();
        $appointment->q_id = $queueId;
        $appointment->date = $now;
        $appointment->queue_for = $queueFor;
        $appointment->fname = $formData['fname'];
        $appointment->mname = $formData['mname'] ?? '';
        $appointment->lname = $formData['lname'];
        $appointment->suffix = $formData['suffix'] ?? '';
        $appointment->age_category = $formData['age_category'];
        $appointment->priority_type = $priorityType;
        $appointment->birthdate = $formData['birthdate'];
        $appointment->trn = $formData['trn'] ?? '';
        $appointment->PCN = $formData['PCN'] ?? '';
        $appointment->window_num = null; // Not assigned to any window yet
        $appointment->time_catered = null; // Not served yet
        $appointment->status = 'pending'; // Set initial status to pending
        
        $appointment->save();

        // Prepare print slip data with PH time
        $printData = [
            'header' => 'PSA PHILSYS',
            'queueNumber' => $queueId,
            'dateTime' => $now->format('M d, Y h:i A'),
            'name' => $formData['lname'] . ', ' . $formData['fname'],
            'service' => $queueFor
        ];

        return redirect()->route('appointment.issuance')
                       ->with('success', 'Appointment issued successfully! Queue Number: ' . $queueId)
                       ->with('printSlip', $printData);
                       
    } catch (\Exception $e) {
        return redirect()->route('appointment.issuance')
                       ->with('error', 'Failed to issue appointment: ' . $e->getMessage())
                       ->withInput();
    }
}

    // Method to extract priority type
    private function extractPriorityType(Request $request, $category)
    {
        if ($category === 'NID Registration') {
            return $request->input('priority_type_nid', 'regular');
        } elseif ($category === 'Status Inquiry') {
            return $request->input('priority_type_status', 'regular');
        } elseif ($category === 'Updating') {
            return $request->input('priority_type_update', 'regular');
        }
        
        return 'regular';
    }

    private function extractFormData(Request $request, $category)
    {
        $data = [
            'fname' => '',
            'mname' => '',
            'lname' => '',
            'suffix' => '',
            'age_category' => '',
            'birthdate' => '',
            'trn' => '',
            'PCN' => ''
        ];

        if ($category === 'NID Registration') {
            $data['fname'] = $request->input('fname_nid');
            $data['mname'] = $request->input('mname_nid');
            $data['lname'] = $request->input('lname_nid');
            $data['suffix'] = $request->input('suffix_nid');
            $data['age_category'] = $request->input('age_category_nid');
            $data['birthdate'] = $request->input('birthdate_nid');
        } elseif ($category === 'Status Inquiry') {
            $data['fname'] = $request->input('fname_status');
            $data['mname'] = $request->input('mname_status');
            $data['lname'] = $request->input('lname_status');
            $data['suffix'] = $request->input('suffix_status');
            $data['age_category'] = $request->input('age_category_status');
            $data['birthdate'] = $request->input('birthdate_status');
            $data['trn'] = $request->input('trn');
        } elseif ($category === 'Updating') {
            $data['fname'] = $request->input('fname_update');
            $data['mname'] = $request->input('mname_update');
            $data['lname'] = $request->input('lname_update');
            $data['suffix'] = $request->input('suffix_update');
            $data['age_category'] = $request->input('age_category_update');
            $data['birthdate'] = $request->input('birthdate_update');
            $data['PCN'] = $request->input('PCN');
        }

        return $data;
    }

    private function getQueuePrefix($category)
    {
        switch ($category) {
            case 'Status Inquiry':
                return 'S';
            case 'NID Registration':
                return 'R';
            case 'Updating':
                return 'U';
            default:
                return 'O';
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
            $tableHtml = view('appointment.partials.transactions-table', compact('completedTransactions'))->render();
            $paginationHtml = view('appointment.partials.pagination-links', compact('completedTransactions'))->render();
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
            
            $pdf = Pdf::loadView('appointment.exports.appointments-pdf', compact(
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