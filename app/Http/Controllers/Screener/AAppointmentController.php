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

        return redirect()->route('screener.dashboard')
                       ->with('success', 'Appointment issued successfully! Queue Number: ' . $queueId)
                       ->with('printSlip', $printData);
                       
    } catch (\Exception $e) {
        return redirect()->route('screener.dashboard')
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

    
}