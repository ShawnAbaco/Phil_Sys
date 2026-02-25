<?php
// app/Http/Controllers/AppointmentController.php

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
        // Check if user is logged in using Auth
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        $today = Carbon::today();

        $appointments = TblAppointment::whereDate('date', $today)
                                      ->orderBy('date', 'asc')
                                      ->get();

        $recentTransactions = TblAppointment::orderBy('date', 'desc')
                                           ->limit(10)
                                           ->get();

        return view('appointment.issuance', compact('appointments', 'recentTransactions', 'user'));
    }

    public function issue(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        $request->validate([
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
            $todayCount = TblAppointment::whereDate('date', $today)->count() + 1;
            $queueId = 'Q-' . $today->format('Ymd') . '-' . str_pad($todayCount, 3, '0', STR_PAD_LEFT);

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
            $appointment->window_num = $user->window_num;
            $appointment->time_catered = now();
            $appointment->save();

            return redirect('/appointment-issuance')->with('success', 'Appointment issued! Queue: ' . $queueId);
        } catch (\Exception $e) {
            return redirect('/appointment-issuance')->with('error', 'Failed to issue appointment')->withInput();
        }
    }
}
