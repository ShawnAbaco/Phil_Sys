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

        // Get today's appointments - ONLY PENDING ones (not served/completed)
        $appointments = TblAppointment::whereDate('date', $today)
                                      ->where(function($query) {
                                          // Only show appointments that haven't been served
                                          $query->whereNull('time_catered');
                                      })
                                      ->orderBy('date', 'asc')
                                      ->get();

        // Get RECENT COMPLETED TRANSACTIONS - ONLY FOR TODAY with pagination (10 items per page)
        $completedTransactions = TblAppointment::whereDate('date', $today)
                                              ->whereNotNull('time_catered')
                                              ->orderBy('time_catered', 'desc')
                                              ->paginate(10);

        // Get queue count for today (all appointments)
        $queueCount = TblAppointment::whereDate('date', $today)->count();

        // Get pending appointments count (not served)
        $pendingCount = TblAppointment::whereDate('date', $today)
                                      ->whereNull('time_catered')
                                      ->count();

        // Get completed appointments count (served) - ONLY FOR TODAY
        $completedCount = TblAppointment::whereDate('date', $today)
                                        ->whereNotNull('time_catered')
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

            // Get today's appointments that are NOT served (pending)
            $appointments = TblAppointment::whereDate('date', $today)
                ->whereNull('time_catered')
                ->orderBy('date', 'asc')
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
                    'trn' => $appointment->trn,
                    'time_catered' => $appointment->time_catered
                ];
            });

            // Get statistics - completed count ONLY FOR TODAY
            $stats = [
                'total' => TblAppointment::whereDate('date', $today)->count(),
                'pending' => TblAppointment::whereDate('date', $today)
                    ->whereNull('time_catered')
                    ->count(),
                'completed' => TblAppointment::whereDate('date', $today)
                    ->whereNotNull('time_catered')
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
                'fname_nid' => 'required|string|max:99',
                'mname_nid' => 'nullable|string|max:99',
                'lname_nid' => 'required|string|max:99',
                'suffix_nid' => 'nullable|string|max:3',
                'age_category_nid' => 'required|string|max:99',
                'birthdate_nid' => 'required|date',
            ]);
        } elseif ($category === 'Status Inquiry') {
            $rules = array_merge($rules, [
                'fname_status' => 'required|string|max:99',
                'mname_status' => 'nullable|string|max:99',
                'lname_status' => 'required|string|max:99',
                'suffix_status' => 'nullable|string|max:3',
                'age_category_status' => 'required|string|max:99',
                'birthdate_status' => 'required|date',
                'trn' => 'required|string|max:29',
            ]);
        } elseif ($category === 'Updating') {
            $rules = array_merge($rules, [
                'fname_update' => 'required|string|max:99',
                'mname_update' => 'nullable|string|max:99',
                'lname_update' => 'required|string|max:99',
                'suffix_update' => 'nullable|string|max:3',
                'age_category_update' => 'required|string|max:99',
                'birthdate_update' => 'required|date',
                'PCN' => 'required|string|max:16',
            ]);
        }

        // Validate the request
        $validated = $request->validate($rules);

        try {
            // Set timezone to Philippine Time
            Carbon::setLocale('en');
            $now = Carbon::now('Asia/Manila');
            $today = $now->toDateString();

            // Map category to queue_for display value
            $queueFor = $category;

            // Get the appropriate field values based on category
            $formData = $this->extractFormData($request, $category);

            // Determine the prefix based on category
            $prefix = $this->getQueuePrefix($category);

            // Get today's count for this specific queue type
            $todayCount = TblAppointment::whereDate('date', $today)
                ->where('q_id', 'LIKE', $prefix . '%')
                ->count() + 1;

            // Generate queue ID (format: PREFIX-XXX, resets daily)
            $queueId = $prefix . str_pad($todayCount, 3, '0', STR_PAD_LEFT);

            // Create new appointment - IMPORTANT: time_catered should be NULL for new appointments
            $appointment = new TblAppointment();
            $appointment->q_id = $queueId;
            $appointment->date = $now;
            $appointment->queue_for = $queueFor;
            $appointment->fname = $formData['fname'];
            $appointment->mname = $formData['mname'] ?? '';
            $appointment->lname = $formData['lname'];
            $appointment->suffix = $formData['suffix'] ?? '';
            $appointment->age_category = $formData['age_category'];
            $appointment->birthdate = $formData['birthdate'];
            $appointment->trn = $formData['trn'] ?? '';
            $appointment->PCN = $formData['PCN'] ?? '';
            $appointment->window_num = null; // Not assigned to any window yet
            $appointment->time_catered = null; // Not served yet
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
            $appointment->save();

            return response()->json([
                'success' => true, 
                'message' => 'Appointment marked as served and moved to recent transactions'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 404);
        }
    }

    public function search(Request $request)
    {
        $searchTerm = $request->get('q');

        $appointments = TblAppointment::where('q_id', 'LIKE', "%{$searchTerm}%")
                                      ->orWhere('fname', 'LIKE', "%{$searchTerm}%")
                                      ->orWhere('lname', 'LIKE', "%{$searchTerm}%")
                                      ->orWhere('trn', 'LIKE', "%{$searchTerm}%")
                                      ->orderBy('date', 'desc')
                                      ->limit(20)
                                      ->get();

        return response()->json($appointments);
    }

    public function getTransactionsPage(Request $request)
    {
        $today = Carbon::now('Asia/Manila')->toDateString();
        $perPage = $request->get('per_page', 10);
        $perPage = in_array($perPage, [10, 20, 50, 100]) ? $perPage : 10;
        
        // Get ONLY TODAY'S completed transactions with pagination
        $completedTransactions = TblAppointment::whereDate('date', $today)
                                              ->whereNotNull('time_catered')
                                              ->orderBy('time_catered', 'desc')
                                              ->paginate($perPage)
                                              ->withQueryString();
        
        if ($request->ajax()) {
            // Return only the necessary parts, not the entire page
            $tableHtml = view('appointment.partials.transactions-table', compact('completedTransactions'))->render();
            $paginationHtml = view('appointment.partials.pagination-links', compact('completedTransactions'))->render();
            $showingInfo = 'Showing ' . $completedTransactions->firstItem() . '-' . $completedTransactions->lastItem() . ' of ' . $completedTransactions->total();
            
            return response()->json([
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
        
        // Get ONLY completed appointments for today
        $completedAppointments = TblAppointment::whereDate('date', $today)
                                            ->whereNotNull('time_catered')
                                            ->orderBy('time_catered', 'desc')
                                            ->get();
        
        // Add row numbers to completed appointments
        $completedAppointments = $completedAppointments->map(function($appointment, $index) {
            $appointment->row_number = $index + 1;
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
        
        // Get completed appointments for today
        $completedAppointments = TblAppointment::whereDate('date', $today)
            ->whereNotNull('time_catered')
            ->orderBy('time_catered', 'desc')
            ->get();
        
        // Add row numbers to completed appointments
        $completedAppointments = $completedAppointments->map(function($appointment, $index) {
            $appointment->row_number = $index + 1;
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
}