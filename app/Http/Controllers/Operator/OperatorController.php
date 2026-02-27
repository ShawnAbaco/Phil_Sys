<?php
// app/Http/Controllers/Operator/OperatorController.php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TblAppointment;
use Carbon\Carbon;

class OperatorController extends Controller
{
    public function dashboard()
    {
        // Get operator's window number from users table
        $windowNum = Auth::user()->window_num ?? '1';
        return view('operator.dashboard', compact('windowNum'));
    }

    public function fetchAppointments()
    {
        try {
            $today = Carbon::today();
            $windowNum = Auth::user()->window_num ?? '1';

            // Get today's appointments that are not assigned to any window
            $appointments = TblAppointment::whereDate('date', $today)
                ->whereNull('window_num')
                ->orderBy('date', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'appointments' => $appointments
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
                'window_num' => 'required|string'
            ]);

            $appointment = TblAppointment::find($validated['n_id']);

            if (!$appointment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Appointment not found.'
                ]);
            }

            // Update the appointment
            $appointment->window_num = $validated['window_num'];
            $appointment->time_catered = Carbon::now();
            $appointment->user_id = Auth::id();
            $appointment->save();

            return response()->json([
                'success' => true,
                'message' => 'Queue number assigned to window #' . $validated['window_num'] . ' successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    public function getQueuedAppointments()
    {
        try {
            $today = Carbon::today();
            $windowNum = Auth::user()->window_num;

            if (!$windowNum) {
                return response()->json([
                    'success' => true,
                    'queued' => []
                ]);
            }

            $queued = TblAppointment::whereDate('date', $today)
                ->where('window_num', $windowNum)
                ->orderBy('time_catered', 'desc')
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
}
