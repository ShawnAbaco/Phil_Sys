<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TblAppointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class WindowController extends AdminController
{


    /**
     * Display window management page.
     */
    public function index()
    {
        $totalWindows = 12; // Total number of windows
        
        $activeWindows = User::whereNotNull('window_num')
            ->where('status', 'active')
            ->distinct('window_num')
            ->count('window_num');
            
        $inactiveWindows = $totalWindows - $activeWindows;
        
        $operatorsToday = User::whereNotNull('window_num')
            ->whereHas('appointments', function($query) {
                $query->whereDate('time_catered', Carbon::today());
            })
            ->count();
            
        $availableOperators = User::whereNotNull('window_num')
            ->where('status', 'active')
            ->whereNotIn('id', function($query) {
                $query->select('user_id')
                    ->from('tbl_appointment')
                    ->whereDate('time_catered', Carbon::today())
                    ->whereNotNull('user_id');
            })
            ->get();
        
        return view('admin.windows.index', compact(
            'totalWindows',
            'activeWindows',
            'inactiveWindows',
            'operatorsToday',
            'availableOperators'
        ));
    }

    /**
     * Get window details.
     */
    public function show($id)
    {
        $windowNum = $id;
        
        $operator = User::where('window_num', $windowNum)
            ->where('status', 'active')
            ->first();
            
        $todayAppointments = TblAppointment::where('window_num', $windowNum)
            ->whereDate('date', Carbon::today())
            ->count();
            
        $todayServed = TblAppointment::where('window_num', $windowNum)
            ->whereDate('time_catered', Carbon::today())
            ->count();
            
        $avgWaitTime = TblAppointment::where('window_num', $windowNum)
            ->whereNotNull('time_catered')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, date, time_catered)) as avg_time')
            ->first();
            
        $currentQueue = TblAppointment::where('window_num', $windowNum)
            ->whereNull('time_catered')
            ->count();
            
        $performance = [
            'today' => $todayServed,
            'weekly' => TblAppointment::where('window_num', $windowNum)
                ->whereBetween('time_catered', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->count(),
            'monthly' => TblAppointment::where('window_num', $windowNum)
                ->whereMonth('time_catered', Carbon::now()->month)
                ->whereYear('time_catered', Carbon::now()->year)
                ->count(),
        ];
        
        $windowConfig = Cache::get('window_' . $windowNum, [
            'enabled' => true,
            'service_type' => 'all',
            'priority' => 'normal',
            'max_queue' => 50,
        ]);
        
        return $this->sendSuccess('Window details retrieved', [
            'window_num' => $windowNum,
            'operator' => $operator,
            'stats' => [
                'today_appointments' => $todayAppointments,
                'today_served' => $todayServed,
                'avg_wait_time' => round($avgWaitTime->avg_time ?? 0),
                'current_queue' => $currentQueue,
            ],
            'performance' => $performance,
            'config' => $windowConfig
        ]);
    }

    /**
     * Update window configuration.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'enabled' => 'required|boolean',
            'service_type' => 'required|string|in:all,registration,inquiry,updating',
            'priority' => 'required|string|in:normal,priority,express',
            'max_queue' => 'required|integer|min:1|max:999',
        ]);
        
        Cache::forever('window_' . $id, $validated);
        
        return $this->sendSuccess('Window configuration updated');
    }

    /**
     * Assign operator to window.
     */
    public function assignOperator(Request $request, $id)
    {
        $validated = $request->validate([
            'operator_id' => 'required|exists:users,id'
        ]);
        
        $operator = User::find($validated['operator_id']);
        
        // Remove operator from previous window
        User::where('window_num', $id)->update(['window_num' => null]);
        
        // Assign to new window
        $operator->window_num = $id;
        $operator->save();
        
        return $this->sendSuccess('Operator assigned to window successfully');
    }

    /**
     * Remove operator from window.
     */
    public function removeOperator($id)
    {
        User::where('window_num', $id)->update(['window_num' => null]);
        
        return $this->sendSuccess('Operator removed from window');
    }

    /**
     * Toggle window status.
     */
    public function toggleStatus($id)
    {
        $config = Cache::get('window_' . $id, ['enabled' => true]);
        $config['enabled'] = !$config['enabled'];
        Cache::forever('window_' . $id, $config);
        
        return $this->sendSuccess('Window status toggled', [
            'enabled' => $config['enabled']
        ]);
    }

    /**
     * Get window statistics.
     */
    public function getStats($id)
    {
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();
        
        $dailyStats = [];
        $current = $startDate->copy();
        
        while ($current <= $endDate) {
            $count = TblAppointment::where('window_num', $id)
                ->whereDate('time_catered', $current)
                ->count();
                
            $dailyStats[] = [
                'date' => $current->format('Y-m-d'),
                'count' => $count
            ];
            
            $current->addDay();
        }
        
        $serviceDistribution = TblAppointment::where('window_num', $id)
            ->select('queue_for', DB::raw('count(*) as count'))
            ->groupBy('queue_for')
            ->get();
            
        return $this->sendSuccess('Window statistics retrieved', [
            'daily' => $dailyStats,
            'service_distribution' => $serviceDistribution,
            'total_served' => TblAppointment::where('window_num', $id)->count(),
            'avg_wait_time' => TblAppointment::where('window_num', $id)
                ->whereNotNull('time_catered')
                ->avg(DB::raw('TIMESTAMPDIFF(MINUTE, date, time_catered)')),
        ]);
    }

    /**
     * Get all windows status.
     */
    public function getAllStatus()
    {
        $windows = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $operator = User::where('window_num', $i)->first();
            $config = Cache::get('window_' . $i, ['enabled' => true]);
            
            $windows[] = [
                'window_num' => $i,
                'enabled' => $config['enabled'],
                'operator' => $operator ? [
                    'id' => $operator->id,
                    'name' => $operator->name,
                    'status' => $operator->status
                ] : null,
                'current_queue' => TblAppointment::where('window_num', $i)
                    ->whereNull('time_catered')
                    ->count(),
                'today_served' => TblAppointment::where('window_num', $i)
                    ->whereDate('time_catered', Carbon::today())
                    ->count(),
            ];
        }
        
        return $this->sendSuccess('Window status retrieved', $windows);
    }
}