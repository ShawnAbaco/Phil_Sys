<?php

namespace App\Http\Controllers\Appointment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TblAppointment;
use App\Models\TblUser;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function issuance()
    {
        Carbon::setLocale('en');
        $today = Carbon::now('Asia/Manila')->toDateString();

        // Get today's appointments - ONLY PENDING ones (not served/completed)
        $appointments = TblAppointment::whereDate('date', $today)
                                      ->where(function($query) {
                                          // Only show appointments that haven't been served
                                          $query->whereNull('time_catered')
                                                ->orWhere('time_catered', '<=', DB::raw('date'));
                                      })
                                      ->orderBy('date', 'asc')
                                      ->get();

        // Get RECENT COMPLETED TRANSACTIONS - ONLY those that have been served
        $completedTransactions = TblAppointment::whereNotNull('time_catered')
                                              ->where('time_catered', '>', DB::raw('date'))
                                              ->orderBy('time_catered', 'desc')
                                              ->limit(20)
                                              ->get();

        // Get queue count for today (all appointments)
        $queueCount = TblAppointment::whereDate('date', $today)->count();

        // Get pending appointments count (not served)
        $pendingCount = TblAppointment::whereDate('date', $today)
                                      ->where(function($query) {
                                          $query->whereNull('time_catered')
                                                ->orWhere('time_catered', '<=', DB::raw('date'));
                                      })
                                      ->count();

        // Get completed appointments count (served)
        $completedCount = TblAppointment::whereDate('date', $today)
                                        ->whereNotNull('time_catered')
                                        ->where('time_catered', '>', DB::raw('date'))
                                        ->count();

        // Get count of appointments at current user's window (only pending ones)
        $windowQueueCount = 0;
        if (Session::has('window_num') && Session::get('window_num') != '0') {
            $windowQueueCount = TblAppointment::whereDate('date', $today)
                                             ->where('window_num', Session::get('window_num'))
                                             ->where(function($query) {
                                                 $query->whereNull('time_catered')
                                                       ->orWhere('time_catered', '<=', DB::raw('date'));
                                             })
                                             ->count();
        }

        return view('appointment.issuance', compact(
            'appointments',
            'completedTransactions',
            'queueCount',
            'pendingCount',
            'completedCount',
            'windowQueueCount'
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
                'pcn' => 'required|string|max:16',
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
            $appointment->birthdate = $formData['birthdate'];
            $appointment->trn = $formData['trn'] ?? '';
            $appointment->PCN = $formData['pcn'] ?? '';
            $appointment->window_num = '0';
            $appointment->time_catered = $now;
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
            'pcn' => ''
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
            $data['pcn'] = $request->input('pcn');
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
}