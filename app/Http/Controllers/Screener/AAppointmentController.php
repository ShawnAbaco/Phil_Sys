<?php
// app/Http/Controllers/Screener/AAppointmentController.php

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

class AAppointmentController extends Controller
{
    /**
     * Display appointments page
     */
    public function appointments()
    {
        $today = Carbon::now('Asia/Manila')->toDateString();
        
        $appointments = TblAppointment::whereDate('date', $today)
                                      ->where(function($query) {
                                          $query->whereIn('status', ['pending', 'serving'])
                                                ->orWhereNull('status')
                                                ->orWhere(function($q) {
                                                    $q->whereNull('time_catered')
                                                      ->whereNotIn('status', ['completed', 'cancelled', 'no_show']);
                                                });
                                      })
                                      ->orderBy('created_at', 'desc')
                                      ->get();
        
        $queueCount = TblAppointment::whereDate('date', $today)->count();
        $pendingCount = TblAppointment::whereDate('date', $today)
                                      ->where('status', 'pending')
                                      ->count();
        $completedCount = TblAppointment::whereDate('date', $today)
                                        ->where('status', 'completed')
                                        ->count();
        
        return view('screener.appointments', compact(
            'appointments',
            'queueCount',
            'pendingCount',
            'completedCount'
        ));
    }

    /**
     * Update appointment (EDIT FUNCTIONALITY)
     */
    public function update(Request $request, $id)
    {
        try {
            // Find the appointment
            $appointment = TblAppointment::findOrFail($id);
            
            // Validate the request
            $validated = $request->validate([
                'fname' => 'required|string|max:99|regex:/^[A-Za-zÑñ\s\-]+$/',
                'mname' => 'nullable|string|max:99|regex:/^[A-Za-zÑñ\s\-]*$/',
                'lname' => 'required|string|max:99|regex:/^[A-Za-zÑñ\s\-]+$/',
                'suffix' => 'nullable|string|max:3|regex:/^[A-Za-zÑñ\s\-\.]*$/',
                'priority_type' => 'required|string|in:regular,senior,infant,pwd,pregnant',
                'birthdate' => 'required|date',
                'age_category' => 'nullable|string',
                'PCN' => 'nullable|string|max:16',
                'trn' => 'nullable|string|max:29',
            ], [
                'fname.regex' => 'First name may only contain letters, spaces, and hyphens.',
                'mname.regex' => 'Middle name may only contain letters, spaces, and hyphens.',
                'lname.regex' => 'Last name may only contain letters, spaces, and hyphens.',
                'suffix.regex' => 'Suffix may only contain letters, spaces, and hyphens.',
            ]);
            
            // Update appointment fields
            $appointment->fname = $request->fname;
            $appointment->mname = $request->mname ?? '';
            $appointment->lname = $request->lname;
            $appointment->suffix = $request->suffix ?? '';
            $appointment->priority_type = $request->priority_type;
            $appointment->birthdate = $request->birthdate;
            $appointment->age_category = $request->age_category;
            
            // Update service-specific fields
            if ($appointment->queue_for === 'Updating') {
                $appointment->PCN = $request->PCN ?? '';
            }
            
            if ($appointment->queue_for === 'Status Inquiry') {
                $appointment->trn = $request->trn ?? '';
            }
            
            $appointment->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Appointment updated successfully',
                'appointment' => $appointment
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Appointment not found'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update appointment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update appointment status (for cancel)
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $appointment = TblAppointment::findOrFail($id);
            $appointment->status = $request->status;
            $appointment->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Appointment status updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get today's appointments via AJAX (for auto-refresh)
     */
    public function getTodayAppointments()
    {
        try {
            $today = Carbon::now('Asia/Manila')->toDateString();
            
            $appointments = TblAppointment::whereDate('date', $today)
                                          ->where(function($query) {
                                              $query->whereIn('status', ['pending', 'serving'])
                                                    ->orWhereNull('status')
                                                    ->orWhere(function($q) {
                                                        $q->whereNull('time_catered')
                                                          ->whereNotIn('status', ['completed', 'cancelled', 'no_show']);
                                                    });
                                          })
                                          ->orderBy('created_at', 'desc')
                                          ->get();
            
            $stats = [
                'total' => TblAppointment::whereDate('date', $today)->count(),
                'pending' => TblAppointment::whereDate('date', $today)->where('status', 'pending')->count(),
                'completed' => TblAppointment::whereDate('date', $today)->where('status', 'completed')->count()
            ];
            
            return response()->json([
                'success' => true,
                'appointments' => $appointments,
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
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

        // Custom validation messages
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
            $appointment->window_num = null;
            $appointment->time_catered = null;
            $appointment->status = 'pending';
            
            $appointment->save();

            // Prepare print slip data with PH time
            $printData = [
                'header' => 'PSA PHILSYS',
                'queueNumber' => $queueId,
                'dateTime' => $now->format('M d, Y h:i A'),
                'name' => $formData['lname'] . ', ' . $formData['fname'],
                'service' => $queueFor
            ];

            return redirect()->route('screener.appointments')
                           ->with('success', 'Appointment issued successfully! Queue Number: ' . $queueId)
                           ->with('printSlip', $printData);
                           
        } catch (\Exception $e) {
            return redirect()->route('screener.appointments')
                           ->with('error', 'Failed to issue appointment: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Store category selection in session
     */
    public function storeCategory(Request $request)
    {
        Session::put('last_category', $request->category);
        return response()->json(['success' => true]);
    }

    /**
     * Store priority type in session
     */
    public function storePriority(Request $request)
    {
        $formType = $request->form_type;
        $priorityType = $request->priority_type;
        
        if ($formType === 'nid') {
            Session::put('last_priority_nid', $priorityType);
        } elseif ($formType === 'status') {
            Session::put('last_priority_status', $priorityType);
        } elseif ($formType === 'update') {
            Session::put('last_priority_update', $priorityType);
        }
        
        return response()->json(['success' => true]);
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
    /**
 * Delete appointment permanently
 */
public function delete($id)
{
    try {
        $appointment = TblAppointment::findOrFail($id);
        
        // Store info for response
        $queueNumber = $appointment->q_id;
        $clientName = $appointment->lname . ', ' . $appointment->fname;
        
        // Delete the appointment
        $appointment->delete();
        
        return response()->json([
            'success' => true,
            'message' => "Appointment {$queueNumber} for {$clientName} has been deleted permanently",
            'queue' => $queueNumber,
            'name' => $clientName
        ]);
        
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Appointment not found'
        ], 404);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete appointment: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Print appointment slip
 */
public function printAppointment($id)
{
    try {
        $appointment = TblAppointment::findOrFail($id);
        
        // Set timezone to Philippine Time
        Carbon::setLocale('en');
        $now = Carbon::now('Asia/Manila');
        
        // Format the full name
        $fullName = $appointment->lname . ', ' . $appointment->fname;
        if ($appointment->mname && trim($appointment->mname) !== '') {
            $fullName .= ' ' . $appointment->mname;
        }
        if ($appointment->suffix && trim($appointment->suffix) !== '') {
            $fullName .= ' ' . $appointment->suffix;
        }
        
        // Prepare print slip data with PH time (same format as issue method)
        $printData = [
            'header' => 'PSA PHILSYS',
            'queueNumber' => $appointment->q_id,
            'dateTime' => $now->format('M d, Y h:i A'),
            'name' => $fullName,
            'service' => $appointment->queue_for
        ];
        
        // Return JSON for AJAX request
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'printData' => $printData
            ]);
        }
        
        // For direct access, return a view
        return view('screener.print-slip', compact('printData'));
        
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        if (request()->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Appointment not found'
            ], 404);
        }
        return redirect()->route('screener.appointments')->with('error', 'Appointment not found');
    } catch (\Exception $e) {
        if (request()->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to print appointment: ' . $e->getMessage()
            ], 500);
        }
        return redirect()->route('screener.appointments')->with('error', 'Failed to print appointment');
    }
}
}	