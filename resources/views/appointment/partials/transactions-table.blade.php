<table class="table">
    <thead>
        <tr>
            <th>Queue #</th>
            <th>Client</th>
            <th>Service</th>
            <th>Served Time</th>
            <th>Window</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($completedTransactions as $index => $transaction)
    @php
        $servedTime = \Carbon\Carbon::parse($transaction->time_catered)->setTimezone('Asia/Manila');
        $serviceDisplay = $transaction->queue_for;
        $rowNumber = ($completedTransactions->currentPage() - 1) * $completedTransactions->perPage() + $loop->iteration;
    @endphp
    <tr>
        <td><span class="row-number">{{ $rowNumber }}</span></td>
        <td><span class="queue-number small">{{ $transaction->q_id }}</span></td>
        <td>
            <div class="client-name">
                {{ $transaction->lname }}, {{ $transaction->fname }}
                @if ($transaction->mname || $transaction->suffix)
                    <small>{{ $transaction->mname }} {{ $transaction->suffix }}</small>
                @endif
            </div>
        </td>
        <td>{{ $serviceDisplay }}</td>
        <td>{{ $servedTime->format('M d, h:i A') }}</td>
        <td>
            <span class="window-indicator">Window {{ $transaction->window_num }}</span>
        </td>
        <td>
            <span class="status-badge status-completed">
                <span class="status-dot"></span>
                Completed
            </span>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="empty-state">
            <svg viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                    clip-rule="evenodd" />
            </svg>
            <p>No completed transactions yet</p>
        </td>
    </tr>
@endforelse
    </tbody>
</table>