<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TblAppointment;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Get statistics
        $totalUsers = User::count();
        $appointmentsToday = TblAppointment::whereDate('date', Carbon::today())->count();
        $pendingQueues = TblAppointment::whereDate('date', Carbon::today())
            ->whereNull('window_num')
            ->count();
        $completedToday = TblAppointment::whereDate('date', Carbon::today())
            ->whereNotNull('window_num')
            ->count();

        // Get recent users
        $recentUsers = User::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'appointmentsToday',
            'pendingQueues',
            'completedToday',
            'recentUsers'
        ));
    }

    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function appointments()
    {
        $appointments = TblAppointment::with('user')
            ->orderBy('date', 'desc')
            ->paginate(20);
        return view('admin.appointments', compact('appointments'));
    }

    public function reports()
    {
        return view('admin.reports');
    }

    public function settings()
    {
        return view('admin.settings');
    }
}
