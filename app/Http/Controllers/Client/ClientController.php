<?php
// app/Http/Controllers/ClientController.php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TblAppointment;
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
        // Get called queues for windows 1-6
        $calledQueues = [];
        for ($w = 1; $w <= 6; $w++) {
            // Get the latest appointment for this window today
            // Look for window_num that matches the current window number as string
            $appointment = TblAppointment::where('window_num', (string)$w)
                ->whereDate('date', $today)
                ->orderBy('time_catered', 'desc')
                ->first();

            if ($appointment) {
                $calledQueues[(string)$w] = [
                    'q_id' => $appointment->q_id,
                    'lname' => $this->sentenceCase($appointment->lname)
                ];
            } else {
                $calledQueues[(string)$w] = [
                    'q_id' => '-',
                    'lname' => ''
                ];
            }
        }

        // Get waiting queues - where window_num is '0', empty string, or NULL
        $nextQueuesRaw = TblAppointment::where(function($query) {
                $query->whereNull('window_num')
                      ->orWhere('window_num', '')
                      ->orWhere('window_num', '0');
            })
            ->whereDate('date', $today)
            ->orderBy('date', 'asc')
            ->limit(50)
            ->get(['q_id', 'lname']);

        // Separate next queues by prefix
        $nextQueues = [
            'statusInquiry' => [],
            'registrationUpdating' => []
        ];

        foreach ($nextQueuesRaw as $item) {
            $q = $item->q_id;
            $lname = $item->lname;

            if (stripos($q, 'S') === 0) {
                if (count($nextQueues['statusInquiry']) < 15) {
                    $nextQueues['statusInquiry'][] = ['q_id' => $q, 'lname' => $lname];
                }
            } elseif (stripos($q, 'R') === 0 || stripos($q, 'U') === 0) {
                if (count($nextQueues['registrationUpdating']) < 15) {
                    $nextQueues['registrationUpdating'][] = ['q_id' => $q, 'lname' => $lname];
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
}
