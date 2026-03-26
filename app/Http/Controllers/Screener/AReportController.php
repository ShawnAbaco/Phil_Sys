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

class AReportController extends Controller
{

/**
 * Display reports page
 */
public function reports()
{
    $today = Carbon::now('Asia/Manila')->toDateString();
    
    $totalCompleted = TblAppointment::where('status', 'completed')
                                    ->whereMonth('date', Carbon::now()->month)
                                    ->count();
    
    $totalCancelled = TblAppointment::where('status', 'cancelled')
                                    ->whereMonth('date', Carbon::now()->month)
                                    ->count();
    
    $totalPending = TblAppointment::where('status', 'pending')
                                  ->whereDate('date', $today)
                                  ->count();
    
    $totalServed = TblAppointment::where('status', 'completed')
                                 ->whereDate('date', $today)
                                 ->count();
    
    $transactions = TblAppointment::whereIn('status', ['completed', 'cancelled'])
                                  ->whereMonth('date', Carbon::now()->month)
                                  ->orderBy('updated_at', 'desc')
                                  ->get();
    
    return view('screener.reports', compact(
        'totalCompleted',
        'totalCancelled',
        'totalPending',
        'totalServed',
        'transactions'
    ));
}

}