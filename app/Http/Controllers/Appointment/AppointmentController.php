<?php
// app/Http/Controllers/Appointment/AppointmentController.php

namespace App\Http\Controllers\Appointment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TblAppointment;
use App\Models\TblUser;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function issuance()
    {
        // Get today's date
        $today = Carbon::today();

        // Sorted NEWEST FIRST (most recent at the top)
        $appointments = TblAppointment::whereDate('date', $today)
                                      ->where(function($query) {
                                          $query->whereIn('window_num', ['0', ''])  // window_num is '0' or empty string
                                                ->orWhereNull('window_num');        // or window_num is NULL
                                      })
                                      ->orderBy('date', 'desc')  // DESCENDING = newest first
                                      ->get();

        // Get recent transactions (last 10 appointments) sorted by newest first
        $recentTransactions = TblAppointment::orderBy('date', 'desc')
                                           ->limit(10)
                                           ->get();

        // Get queue count for today
        $queueCount = TblAppointment::whereDate('date', $today)->count();

        // Get pending appointments count (window_num is '0' or empty)
        $pendingCount = TblAppointment::whereDate('date', $today)
                                      ->whereIn('window_num', ['0', '', null])
                                      ->count();

        // Get completed appointments count (window_num is assigned to a real window)
        $completedCount = TblAppointment::whereDate('date', $today)
                                        ->whereNotIn('window_num', ['0', '', null])
                                        ->count();

        // Get count of appointments at current user's window (if logged in)
        $windowQueueCount = 0;
        if (Session::has('window_num') && Session::get('window_num') != '0') {
            $windowQueueCount = TblAppointment::whereDate('date', $today)
                                             ->where('window_num', Session::get('window_num'))
                                             ->count();
        }

        return view('appointment.issuance', compact(
            'appointments',
            'recentTransactions',
            'queueCount',
            'pendingCount',
            'completedCount',
            'windowQueueCount'
        ));
    }

    public function issue(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'queue_for' => 'required|string',
            'fname' => 'required|string|max:99',
            'mname' => 'nullable|string|max:99',
            'lname' => 'required|string|max:99',
            'suffix' => 'nullable|string|max:3',
            'age_category' => 'required|string|max:99',
            'trn' => 'required|string|max:29',
            'birthdate' => 'required|date',
            'PCN' => 'required|string|max:16',
        ]);

        try {
            $today = Carbon::today();

            // Determine the prefix based on queue_for
            $prefix = $this->getQueuePrefix($request->queue_for);

            // Get today's count for this specific queue type
            $todayCount = TblAppointment::whereDate('date', $today)
                ->where('q_id', 'LIKE', $prefix . '%')
                ->count() + 1;

            // Generate queue ID (format: PREFIX-XXX, resets daily)
            $queueId = $prefix . str_pad($todayCount, 3, '0', STR_PAD_LEFT);

            // Create new appointment
            $appointment = new TblAppointment();
            $appointment->q_id = $queueId;
            $appointment->date = now();
            $appointment->queue_for = $request->queue_for;
            $appointment->fname = $request->fname;
            $appointment->mname = $request->mname ?? '';
            $appointment->lname = $request->lname;
            $appointment->suffix = $request->suffix ?? '';
            $appointment->age_category = $request->age_category;
            $appointment->trn = $request->trn;
            $appointment->birthdate = $request->birthdate;
            $appointment->PCN = $request->PCN;
            $appointment->window_num = '0'; // Use '0' as string to indicate not assigned
            $appointment->time_catered = now();
            $appointment->save();

            return redirect()->route('appointment.issuance')
                           ->with('success', 'Appointment issued successfully! Queue Number: ' . $queueId);
        } catch (\Exception $e) {
            return redirect()->route('appointment.issuance')
                           ->with('error', 'Failed to issue appointment: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Get the queue prefix based on the service type
     */
    private function getQueuePrefix($queueFor)
    {
        switch ($queueFor) {
            case 'status-inquiry':
                return 'S';
            case 'nid_registration':
                return 'R';
            case 'updating':
                return 'U';
            default:
                return 'O'; // O for Other
        }
    }

    public function serve($id)
    {
        try {
            $appointment = TblAppointment::findOrFail($id);
            $appointment->window_num = Session::get('window_num', '1');
            $appointment->time_catered = now();
            $appointment->save();

            return response()->json(['success' => true, 'message' => 'Appointment marked as served']);
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
