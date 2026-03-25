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

class ATransactionsController extends Controller
{
/**
 * Display transactions page
 */
public function transactions()
{
    $today = Carbon::now('Asia/Manila')->toDateString();
    
    $completedTransactions = TblAppointment::whereDate('date', $today)
                                          ->where(function($query) {
                                              $query->whereIn('status', ['completed', 'cancelled'])
                                                    ->orWhere(function($q) {
                                                        $q->whereNotNull('time_catered')
                                                          ->whereNotIn('status', ['no_show']);
                                                    });
                                          })
                                          ->orderBy('updated_at', 'desc')
                                          ->paginate(10);
    
    return view('screener.transactions', compact('completedTransactions'));
}

}