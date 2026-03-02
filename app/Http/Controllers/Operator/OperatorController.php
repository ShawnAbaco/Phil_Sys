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
        
        // Set timezone to Philippine Time
        Carbon::setLocale('en');
        $today = Carbon::now('Asia/Manila')->toDateString();

        // Get today's appointments - ONLY PENDING ones (not assigned to any window and not served)
        $appointments = TblAppointment::whereDate('date', $today)
                                      ->whereNull('window_num')  // Not assigned to any window
                                      ->whereNull('time_catered') // Not served
                                      ->orderBy('date', 'asc')
                                      ->get();

        // Get RECENT COMPLETED TRANSACTIONS (served appointments)
        $completedTransactions = TblAppointment::whereNotNull('time_catered')
                                              ->orderBy('time_catered', 'desc')
                                              ->limit(10)
                                              ->get();

        // Get queue count for today (all appointments)
        $queueCount = TblAppointment::whereDate('date', $today)->count();

        // Get pending appointments count (not assigned to any window)
        $pendingCount = TblAppointment::whereDate('date', $today)
                                      ->whereNull('window_num')
                                      ->whereNull('time_catered')
                                      ->count();

        // Get completed appointments count (served)
        $completedCount = TblAppointment::whereDate('date', $today)
                                        ->whereNotNull('time_catered')
                                        ->count();

        // Get count of appointments at current user's window (served at this window)
        $windowQueueCount = TblAppointment::whereDate('date', $today)
                                         ->where('window_num', $windowNum)
                                         ->whereNotNull('time_catered')
                                         ->count();

        return view('operator.dashboard', compact(
            'windowNum',
            'appointments',
            'completedTransactions',
            'queueCount',
            'pendingCount',
            'completedCount',
            'windowQueueCount'
        ));
    }

    public function fetchAppointments()
    {
        try {
            $today = Carbon::now('Asia/Manila')->toDateString();

            // Get today's appointments that are not assigned to any window
            $appointments = TblAppointment::whereDate('date', $today)
                ->whereNull('window_num')
                ->whereNull('time_catered')
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
                'window_num' => 'required' // Remove string validation, accept any type
            ]);

            $appointment = TblAppointment::find($validated['n_id']);

            if (!$appointment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Appointment not found.'
                ]);
            }

            // Convert window_num to string to ensure it's stored properly
            $windowNum = (string) $validated['window_num'];

            // Update the appointment - assign to window and mark as served
            $appointment->window_num = $windowNum;
            $appointment->time_catered = Carbon::now('Asia/Manila');
            $appointment->user_id = Auth::id();
            $appointment->save();

            return response()->json([
                'success' => true,
                'message' => 'Queue number ' . $appointment->q_id . ' served at window #' . $windowNum . ' successfully!'
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
            $today = Carbon::now('Asia/Manila')->toDateString();
            $windowNum = Auth::user()->window_num;

            if (!$windowNum) {
                return response()->json([
                    'success' => true,
                    'queued' => []
                ]);
            }

            $queued = TblAppointment::whereDate('date', $today)
                ->where('window_num', (string) $windowNum)
                ->whereNotNull('time_catered')
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