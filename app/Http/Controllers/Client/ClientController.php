<?php
// app/Http/Controllers/ClientController.php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TblAppointment;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class ClientController extends Controller
{
    public function dashboard()
    {
        $today = Carbon::today()->toDateString();
        $data = $this->getQueueData($today);

        return view('client.dashboard', $data);
    }

    public function getQueues()
    {
        $today = Carbon::today()->toDateString();
        $data = $this->getQueueData($today);

        return response()->json($data);
    }

    private function getQueueData($today)
{
    // Get called queues for windows 1-6 - ONLY SHOW SERVING STATUS
    $calledQueues = [];
    for ($w = 1; $w <= 6; $w++) {
        // Get the latest SERVING appointment for this window today
        $appointment = TblAppointment::where('window_num', (string)$w)
            ->whereDate('date', $today)
            ->where('status', 'serving')  // Only show serving status
            ->orderBy('time_catered', 'desc')
            ->first();

        if ($appointment) {
            $calledQueues[(string)$w] = [
                'q_id' => $appointment->q_id,
                'priority_type' => $appointment->priority_type ?? 'regular',
                'status' => $appointment->status
            ];
        } else {
            $calledQueues[(string)$w] = [
                'q_id' => '-',
                'priority_type' => '',
                'status' => 'none'
            ];
        }
    }

    // Get pending waiting queues - where window_num is '0', empty string, or NULL
    $pendingQueues = TblAppointment::where(function($query) {
            $query->whereNull('window_num')
                  ->orWhere('window_num', '')
                  ->orWhere('window_num', '0');
        })
        ->whereDate('date', $today)
        ->where('status', 'pending')
        ->orderBy('date', 'asc')
        ->limit(100)
        ->get(['q_id', 'queue_for', 'status', 'priority_type']);

    // Get no_show queues - they have window_num but status is no_show
    $noShowQueues = TblAppointment::whereDate('date', $today)
        ->where('status', 'no_show')
        ->orderBy('date', 'asc')
        ->limit(100)
        ->get(['q_id', 'queue_for', 'status', 'priority_type']);

    // Merge both collections
    $nextQueuesRaw = $pendingQueues->concat($noShowQueues);

    // Sort by date and prioritize no_show
    $nextQueuesRaw = $nextQueuesRaw->sortBy(function($item) {
        // Return a sortable value: no_show first, then by date
        $priority = ($item->status === 'no_show') ? 0 : 1;
        return $priority . $item->date;
    })->values();

    // Initialize separate arrays for each service type
    $nextQueues = [
        'statusInquiry' => [],
        'registration' => [],
        'updating' => []
    ];

    foreach ($nextQueuesRaw as $item) {
        $q = $item->q_id;
        $status = $item->status;
        $priorityType = $item->priority_type ?? 'regular';
        
        // Create array with status and priority information
        $queueItem = [
            'q_id' => $q, 
            'status' => $status,
            'priority_type' => $priorityType
        ];
        
        // Filter by queue_for column
        if ($item->queue_for === 'Status Inquiry') {
            if (count($nextQueues['statusInquiry']) < 27) {
                $nextQueues['statusInquiry'][] = $queueItem;
            }
        } elseif ($item->queue_for === 'NID Registration') {
            if (count($nextQueues['registration']) < 27) {
                $nextQueues['registration'][] = $queueItem;
            }
        } elseif ($item->queue_for === 'Updating') {
            if (count($nextQueues['updating']) < 27) {
                $nextQueues['updating'][] = $queueItem;
            }
        }
        // Fallback to q_id prefix if queue_for doesn't work
        else if (stripos($q, 'S') === 0) {
            if (count($nextQueues['statusInquiry']) < 27) {
                $nextQueues['statusInquiry'][] = $queueItem;
            }
        } elseif (stripos($q, 'R') === 0) {
            if (count($nextQueues['registration']) < 27) {
                $nextQueues['registration'][] = $queueItem;
            }
        } elseif (stripos($q, 'U') === 0) {
            if (count($nextQueues['updating']) < 27) {
                $nextQueues['updating'][] = $queueItem;
            }
        }
    }

    return [
        'calledQueues' => $calledQueues,
        'nextQueues' => $nextQueues
    ];
}

    private function sentenceCase($string)
    {
        if (!$string) return '';
        $string = strtolower($string);
        return ucfirst($string);
    }

    /**
 * Check for triggered announcements
 */
public function checkAnnouncement()
{
    try {
        // Check cache first
        $announcement = Cache::get('last_announcement');
        
        // If not in cache, check session
        if (!$announcement) {
            $announcement = session('last_announcement');
        }
        
        // Clear after retrieving (prevent replay)
        if ($announcement) {
            Cache::forget('last_announcement');
            session()->forget('last_announcement');
        }
        
        return response()->json([
            'success' => true,
            'announcement' => $announcement
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
}