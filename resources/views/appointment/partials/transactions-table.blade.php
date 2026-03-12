@forelse($completedTransactions as $index => $transaction)
    @php
        // Use time_catered for completed, updated_at for cancelled/no_show if no time_catered
        $displayTime = $transaction->time_catered 
            ? \Carbon\Carbon::parse($transaction->time_catered)->setTimezone('Asia/Manila')
            : \Carbon\Carbon::parse($transaction->updated_at)->setTimezone('Asia/Manila');
        
        $serviceDisplay = $transaction->queue_for;
        $rowNumber = ($completedTransactions->currentPage() - 1) * $completedTransactions->perPage() + $loop->iteration;

        // Format name properly with FULL middle name
        $fullName = $transaction->lname . ', ' . $transaction->fname;
        if ($transaction->mname && trim($transaction->mname) !== '') {
            $fullName .= ' ' . $transaction->mname;
        }
        if ($transaction->suffix && trim($transaction->suffix) !== '') {
            $fullName .= ' ' . $transaction->suffix;
        }
        
        // Get priority type for display
        $priorityType = !empty($transaction->priority_type) ? $transaction->priority_type : 'regular';
        $priorityDisplay = ucfirst($priorityType);
        
        // Determine status class and display based on ACTUAL database status
        $status = $transaction->status ?? 'completed'; // Default to completed if not set
        $statusClass = $status;
        $statusDisplay = ucfirst(str_replace('_', ' ', $status));
        
        // Set appropriate time label based on status
        if ($status === 'completed') {
            $timeLabel = 'Served';
        } elseif ($status === 'cancelled') {
            $timeLabel = 'Cancelled';
        } elseif ($status === 'no_show') {
            $timeLabel = 'No Show';
        } else {
            $timeLabel = 'Processed';
        }
    @endphp
    <tr data-service="{{ $serviceDisplay }}" data-priority="{{ $priorityType }}" data-status="{{ $status }}">
        <td><span class="row-number">{{ $rowNumber }}</span></td>
        <td><span class="queue-number small">{{ $transaction->q_id }}</span></td>
        <td>
            <div class="client-name">
                {{ $fullName }}
            </div>
        </td>
        <td>
            <span class="priority-badge priority-{{ $priorityType }}" data-priority="{{ $priorityType }}">
                {{ strtoupper($priorityDisplay) }}
            </span>
        </td>
        <td>{{ $serviceDisplay }}</td>
        <td>
            <span class="time-badge" title="{{ $timeLabel }} at this time">
                {{ $displayTime->format('M d, h:i A') }}
            </span>
        </td>
        <td>
            @if($transaction->window_num)
                <span class="window-indicator">Window {{ $transaction->window_num }}</span>
            @else
                <span class="window-indicator">—</span>
            @endif
        </td>
        <td>
            <span class="status-badge status-{{ $statusClass }}">
                <span class="status-dot"></span>
                {{ $statusDisplay }}
            </span>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="empty-state">
            <svg viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                    clip-rule="evenodd" />
            </svg>
            <p>No transactions yet</p>
        </td>
    </tr>
@endforelse