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
use App\Exports\Admin\ReportExport;
use Carbon\Carbon;

class ExportController extends Controller  // Changed from AdminController to Controller
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
        // You can implement this later
        
        return response()->json([
            'success' => true,
            'message' => 'Logs export started'
        ]);
    }

    /**
     * Export report data.
     */
    public function exportReport(Request $request)
{
    \Log::info('Export Report Request', $request->all());
    
    $type = $request->get('type', 'daily');
    $format = $request->get('format', 'excel');
    $start = $request->get('start');
    $end = $request->get('end');
    $date = $request->get('date');
    $month = $request->get('month');
    $year = $request->get('year');
    
    $appointments = collect();
    $reportType = $request->get('report_type', 'summary');
    $startDate = null;
    $endDate = null;
    
    switch ($type) {
        case 'daily':
            $startDate = $date ? Carbon::parse($date) : Carbon::today();
            $endDate = $startDate;
            $appointments = TblAppointment::whereDate('date', $startDate)
                ->orderBy('time_catered', 'desc')
                ->get();
            break;
        case 'weekly':
            $dateObj = $date ? Carbon::parse($date) : Carbon::today();
            $startDate = $dateObj->copy()->startOfWeek();
            $endDate = $dateObj->copy()->endOfWeek();
            $appointments = TblAppointment::whereBetween('date', [$startDate, $endDate])
                ->orderBy('date', 'desc')
                ->get();
            break;
        case 'monthly':
            $monthNum = $month ?: Carbon::now()->month;
            $yearNum = $year ?: Carbon::now()->year;
            $startDate = Carbon::createFromDate($yearNum, $monthNum, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($yearNum, $monthNum, 1)->endOfMonth();
            $appointments = TblAppointment::whereBetween('date', [$startDate, $endDate])
                ->orderBy('date', 'desc')
                ->get();
            break;
        case 'custom':
            if ($start && $end) {
                $startDate = Carbon::parse($start);
                $endDate = Carbon::parse($end);
                $appointments = TblAppointment::whereBetween('date', [$startDate, $endDate])
                    ->orderBy('date', 'desc')
                    ->get();
            }
            break;
    }
    
    $fileName = $this->generateFileName($type, $startDate, $endDate);
    
    try {
        if ($format === 'csv') {
            return Excel::download(new ReportExport($appointments, $reportType, $startDate, $endDate), $fileName . '.csv', \Maatwebsite\Excel\Excel::CSV);
        }
        
        return Excel::download(new ReportExport($appointments, $reportType, $startDate, $endDate), $fileName . '.xlsx');
    } catch (\Exception $e) {
        \Log::error('Export error: ' . $e->getMessage());
        return back()->with('error', 'Failed to export report: ' . $e->getMessage());
    }
}
    
    /**
     * Generate filename for export
     */
    private function generateFileName($type, $startDate, $endDate)
    {
        $now = Carbon::now()->format('Y-m-d_H-i-s');
        
        switch ($type) {
            case 'daily':
                return 'Daily_Report_' . $startDate->format('Y-m-d') . '_' . $now;
            case 'weekly':
                return 'Weekly_Report_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '_' . $now;
            case 'monthly':
                return 'Monthly_Report_' . $startDate->format('Y-m') . '_' . $now;
            case 'custom':
                return 'Custom_Report_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '_' . $now;
            default:
                return 'Report_' . $now;
        }
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
            return redirect()->back()->with('error', 'Backup file not found');
        }
        
        return response()->download($filePath);
    }
}