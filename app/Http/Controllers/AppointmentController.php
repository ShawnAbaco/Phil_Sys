<?php
// app/Http/Controllers/AppointmentController.php

namespace App\Http\Controllers;

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

        // Get today's appointments
        $appointments = TblAppointment::whereDate('date', $today)
                                      ->orderBy('date', 'asc')
                                      ->get();

        // Get recent transactions (last 10 appointments)
        $recentTransactions = TblAppointment::orderBy('date', 'desc')
                                           ->limit(10)
                                           ->get();

        // Get queue count for today
        $queueCount = TblAppointment::whereDate('date', $today)->count();

        // Get pending appointments count
        $pendingCount = TblAppointment::whereDate('date', $today)
                                      ->whereColumn('date', 'time_catered')
                                      ->count();

        // Get completed appointments count
        $completedCount = TblAppointment::whereDate('date', $today)
                                        ->whereColumn('date', '<', 'time_catered')
                                        ->count();

        return view('appointment.issuance', compact(
            'appointments',
            'recentTransactions',
            'queueCount',
            'pendingCount',
            'completedCount'
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
            // Generate queue ID (format: Q-YYYYMMDD-XXX)
            $today = Carbon::today();
            $todayCount = TblAppointment::whereDate('date', $today)->count() + 1;
            $queueId = 'Q-' . $today->format('Ymd') . '-' . str_pad($todayCount, 3, '0', STR_PAD_LEFT);

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
            $appointment->window_num = Session::get('window_num', '1');
            $appointment->time_catered = now(); // Set to current time initially
            $appointment->save();

            return redirect()->route('appointment.issuance')
                           ->with('success', 'Appointment issued successfully! Queue Number: ' . $queueId);
        } catch (\Exception $e) {
            return redirect()->route('appointment.issuance')
                           ->with('error', 'Failed to issue appointment: ' . $e->getMessage())
                           ->withInput();
        }
    }

    public function serve($id)
    {
        try {
            $appointment = TblAppointment::findOrFail($id);
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
