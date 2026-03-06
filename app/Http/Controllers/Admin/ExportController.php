<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TblAppointment;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Exports\AppointmentsExport;
use Carbon\Carbon;

class ExportController extends AdminController
{


    /**
     * Export users data.
     */
    public function exportUsers(Request $request)
    {
        $format = $request->get('format', 'excel');
        $filename = 'users-export-' . Carbon::now()->format('Y-m-d-His');
        
        $query = User::query();
        
        // Apply filters
        if ($request->has('role') && !empty($request->role)) {
            $query->where('designation', $request->role);
        }
        
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        $users = $query->get();
        
        if ($format === 'pdf') {
            $pdf = Pdf::loadView('admin.exports.users-pdf', compact('users'));
            $pdf->setPaper('A4', 'landscape');
            return $pdf->download($filename . '.pdf');
        }
        
        // Excel export
        return Excel::download(new UsersExport($users), $filename . '.xlsx');
    }

    /**
     * Export appointments data.
     */
    public function exportAppointments(Request $request)
    {
        $format = $request->get('format', 'excel');
        $filename = 'appointments-export-' . Carbon::now()->format('Y-m-d-His');
        
        $query = TblAppointment::with('user');
        
        // Apply date filter
        if ($request->has('filter')) {
            switch ($request->filter) {
                case 'today':
                    $query->whereDate('date', Carbon::today());
                    break;
                case 'yesterday':
                    $query->whereDate('date', Carbon::yesterday());
                    break;
                case 'week':
                    $query->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('date', Carbon::now()->month)
                          ->whereYear('date', Carbon::now()->year);
                    break;
                case 'custom':
                    if ($request->has('start') && $request->has('end')) {
                        $query->whereBetween('date', [$request->start, $request->end]);
                    }
                    break;
            }
        }
        
        // Apply status filter
        if ($request->has('status') && !empty($request->status)) {
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
        
        $appointments = $query->orderBy('date', 'desc')->get();
        
        if ($format === 'pdf') {
            $pdf = Pdf::loadView('admin.exports.appointments-pdf', compact('appointments'));
            $pdf->setPaper('A4', 'landscape');
            return $pdf->download($filename . '.pdf');
        }
        
        // Excel export
        return Excel::download(new AppointmentsExport($appointments), $filename . '.xlsx');
    }

    /**
     * Export logs data.
     */
    public function exportLogs(Request $request)
    {
        $format = $request->get('format', 'excel');
        $filename = 'logs-export-' . Carbon::now()->format('Y-m-d-His');
        
        // Log export logic here
        
        return $this->sendSuccess('Logs export started');
    }

    /**
     * Export report data.
     */
    public function exportReport(Request $request)
    {
        $format = $request->get('format', 'pdf');
        $reportType = $request->get('type', 'daily');
        $filename = $reportType . '-report-' . Carbon::now()->format('Y-m-d-His');
        
        // Report export logic based on type
        
        return $this->sendSuccess('Report export started');
    }

    /**
     * Download backup file.
     */
    public function downloadBackup(Request $request)
    {
        $request->validate([
            'file' => 'required|string'
        ]);
        
        $filePath = storage_path('app/backups/' . $request->file);
        
        if (!file_exists($filePath)) {
            return $this->sendError('Backup file not found');
        }
        
        return response()->download($filePath);
    }
}