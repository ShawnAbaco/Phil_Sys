<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TblAppointment;
use App\Models\User;
use Carbon\Carbon;

class AppointmentsController extends AdminController
{


    /**
     * Display a listing of appointments.
     */
    public function index(Request $request)
    {
        $query = TblAppointment::with('user');
        
        // Apply date filter
        if ($request->has('filter')) {
            $today = Carbon::today();
            
            switch ($request->filter) {
                case 'today':
                    $query->whereDate('date', $today);
                    break;
                case 'yesterday':
                    $query->whereDate('date', Carbon::yesterday());
                    break;
                case 'week':
                    $query->whereBetween('date', [$today->copy()->startOfWeek(), $today->copy()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('date', $today->month)->whereYear('date', $today->year);
                    break;
                case 'custom':
                    if ($request->has('start') && $request->has('end')) {
                        $query->whereBetween('date', [$request->start, $request->end]);
                    }
                    break;
            }
        }
        
        // Apply status filter
        if ($request->has('status')) {
            if ($request->status === 'completed') {
                $query->whereNotNull('time_catered');
            } elseif ($request->status === 'pending') {
                $query->whereNull('time_catered');
            }
        }
        
        // Apply service filter
        if ($request->has('service') && !empty($request->service)) {
            $query->where('queue_for', $request->service);
        }
        
        // Apply search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('q_id', 'like', "%{$search}%")
                  ->orWhere('fname', 'like', "%{$search}%")
                  ->orWhere('lname', 'like', "%{$search}%")
                  ->orWhere('trn', 'like', "%{$search}%");
            });
        }
        
        $appointments = $query->orderBy('date', 'desc')->paginate(20);
        
        // Get summary statistics
        $today = Carbon::today();
        $totalAppointments = TblAppointment::whereDate('date', $today)->count();
        $pendingAppointments = TblAppointment::whereDate('date', $today)->whereNull('time_catered')->count();
        $completedAppointments = TblAppointment::whereDate('date', $today)->whereNotNull('time_catered')->count();
        $cancelledAppointments = 0; // Implement if you have cancelled status
        
        return view('admin.appointments.index', compact(
            'appointments',
            'totalAppointments',
            'pendingAppointments',
            'completedAppointments',
            'cancelledAppointments'
        ));
    }

    /**
     * Display the specified appointment.
     */
    public function show($id)
    {
        $appointment = TblAppointment::with('user')->findOrFail($id);
        return view('admin.appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified appointment.
     */
    public function edit($id)
    {
        $appointment = TblAppointment::findOrFail($id);
        return view('admin.appointments.edit', compact('appointment'));
    }

    /**
     * Update the specified appointment in storage.
     */
    public function update(Request $request, $id)
    {
        $appointment = TblAppointment::findOrFail($id);
        
        $validated = $request->validate([
            'fname' => 'required|string|max:99',
            'mname' => 'nullable|string|max:99',
            'lname' => 'required|string|max:99',
            'suffix' => 'nullable|string|max:3',
            'age_category' => 'required|string|max:99',
            'birthdate' => 'required|date',
            'queue_for' => 'required|string',
            'trn' => 'nullable|string|max:29',
            'PCN' => 'nullable|string|max:16',
            'window_num' => 'nullable|integer',
            'status' => 'required|string|in:pending,completed',
        ]);
        
        // Update appointment
        $appointment->fname = $validated['fname'];
        $appointment->mname = $validated['mname'];
        $appointment->lname = $validated['lname'];
        $appointment->suffix = $validated['suffix'];
        $appointment->age_category = $validated['age_category'];
        $appointment->birthdate = $validated['birthdate'];
        $appointment->queue_for = $validated['queue_for'];
        $appointment->trn = $validated['trn'];
        $appointment->PCN = $validated['PCN'];
        $appointment->window_num = $validated['window_num'];
        
        // Handle status change
        if ($validated['status'] === 'completed' && !$appointment->time_catered) {
            $appointment->time_catered = Carbon::now('Asia/Manila');
        } elseif ($validated['status'] === 'pending' && $appointment->time_catered) {
            $appointment->time_catered = null;
        }
        
        $appointment->save();
        
        return redirect()->route('admin.appointments.show', $id)
            ->with('success', 'Appointment updated successfully');
    }

    /**
     * Remove the specified appointment from storage.
     */
    public function destroy($id)
    {
        try {
            $appointment = TblAppointment::findOrFail($id);
            $appointment->delete();
            
            return $this->sendSuccess('Appointment deleted successfully');
            
        } catch (\Exception $e) {
            return $this->sendError('Failed to delete appointment', $e->getMessage(), 500);
        }
    }

    /**
     * Mark appointment as served.
     */
    public function markAsServed($id)
    {
        try {
            $appointment = TblAppointment::findOrFail($id);
            
            if ($appointment->time_catered) {
                return $this->sendError('Appointment already served');
            }
            
            $appointment->time_catered = Carbon::now('Asia/Manila');
            $appointment->save();
            
            return $this->sendSuccess('Appointment marked as served');
            
        } catch (\Exception $e) {
            return $this->sendError('Failed to mark appointment as served', $e->getMessage(), 500);
        }
    }

    /**
     * Get appointments for calendar view.
     */
    public function getCalendarEvents(Request $request)
    {
        $start = $request->get('start', Carbon::now()->startOfMonth());
        $end = $request->get('end', Carbon::now()->endOfMonth());
        
        $appointments = TblAppointment::whereBetween('date', [$start, $end])
            ->get()
            ->map(function($appointment) {
                return [
                    'id' => $appointment->n_id,
                    'title' => $appointment->q_id . ' - ' . $appointment->lname,
                    'start' => $appointment->date,
                    'end' => $appointment->date,
                    'color' => $appointment->time_catered ? '#10b981' : '#f59e0b',
                    'url' => route('admin.appointments.show', $appointment->n_id)
                ];
            });
            
        return response()->json($appointments);
    }

    /**
     * Export appointments data.
     */
    public function export(Request $request)
    {
        // This will be handled by ExportController
    }
}