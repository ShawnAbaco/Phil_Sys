@props(['appointments', 'serviceType' => null, 'showAll' => false, 'tableId' => ''])

<div class="table-responsive">
    <table class="table" data-table="{{ $tableId }}">
        <thead>
            <tr>
                <th style="width: 40px;"></th>
                <th>Queue #</th>
                <th>Name</th>
                <th>Age</th>
                <th>Birthdate</th>
                <th>TRN</th>
                <th>PCN</th>
                <th>Service</th>
                <th>Time</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $filteredAppointments = $showAll 
                    ? $appointments 
                    : $appointments->filter(function($app) use ($serviceType) {
                        return $app->queue_for === $serviceType;
                    });
                
                // Check if there's any serving appointment
                $hasServing = $filteredAppointments->contains(function($app) {
                    return $app->status === 'serving';
                });
            @endphp
            
            @forelse($filteredAppointments as $appointment)
                @php
                    $createdTime = \Carbon\Carbon::parse($appointment->date)->setTimezone('Asia/Manila');
                    $fullName = $appointment->lname . ', ' . $appointment->fname;
                    if ($appointment->mname && trim($appointment->mname) !== '') {
                        $fullName .= ' ' . $appointment->mname;
                    }
                    if ($appointment->suffix && trim($appointment->suffix) !== '') {
                        $fullName .= ' ' . $appointment->suffix;
                    }
                    
                    // Determine status display and class based on database status
                    $statusDisplay = ucfirst(str_replace('_', ' ', $appointment->status ?? 'pending'));
                    $statusClass = $appointment->status ?? 'pending';
                    
                    // Map status to appropriate CSS class
                    switch($appointment->status) {
                        case 'pending':
                            $statusClass = 'pending';
                            break;
                        case 'serving':
                            $statusClass = 'serving';
                            break;
                        case 'completed':
                            $statusClass = 'completed';
                            break;
                        case 'cancelled':
                            $statusClass = 'cancelled';
                            break;
                        case 'no_show':
                            $statusClass = 'no-show';
                            break;
                        default:
                            $statusClass = 'pending';
                    }
                    
                    // Determine if serve button should be disabled
                    // Only disable if there's a serving appointment AND this is not the serving one
                    $serveDisabled = $hasServing && $appointment->status !== 'serving';
                    
                    // Determine if checkbox should be disabled
                    // Only pending appointments can be selected
                    // Determine if checkbox should be disabled
                    // Disable only completed and cancelled appointments
                    $checkboxDisabled = $appointment->status === 'completed' || $appointment->status === 'cancelled' || $appointment->status === 'serving';
                @endphp
                <tr data-search="{{ strtolower($appointment->lname . ' ' . $appointment->fname . ' ' . ($appointment->trn ?? '')) }}">
                    <td>
                        <input type="checkbox" class="row-checkbox" 
                               data-id="{{ $appointment->n_id }}" 
                               data-name="{{ $appointment->fname }} {{ $appointment->lname }}"
                               data-table="{{ $tableId }}"
                               data-status="{{ $appointment->status }}"
                               {{ $checkboxDisabled ? 'disabled' : '' }}>
                    </td>
                    <td><span class="queue-number">{{ $appointment->q_id }}</span></td>
                    <td class="client-name">{{ $fullName }}</td>
                    <td>{{ $appointment->age_category ?? 'N/A' }}</td>
                    <td>{{ $appointment->birthdate ? \Carbon\Carbon::parse($appointment->birthdate)->format('M d, Y') : 'N/A' }}</td>
                    <td>{{ $appointment->trn ?? 'N/A' }}</td>
                    <td>{{ $appointment->PCN ?? 'N/A' }}</td>
                    <td><span class="service-tag">{{ $appointment->queue_for }}</span></td>
                    <td>{{ $createdTime->format('h:i A') }}</td>
                    <td>
                        <span class="status-badge {{ $statusClass }}">
                            <span class="status-dot"></span>
                            {{ $statusDisplay }}
                        </span>
                    </td>
                    <td>
                        @if($appointment->status === 'pending')
                            <button class="btn-action serve-btn" 
                                    data-id="{{ $appointment->n_id }}"
                                    data-name="{{ $appointment->fname }} {{ $appointment->lname }}"
                                    data-status="pending"
                                    {{ $serveDisabled ? 'disabled' : '' }}
                                    title="{{ $serveDisabled ? 'Cannot serve while another appointment is being served' : 'Call this appointment to your window' }}">
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Serve
                            </button>
                        @elseif($appointment->status === 'serving')
                            <div class="action-button-group">
                                <button class="btn-action complete-btn" 
                                        data-id="{{ $appointment->n_id }}"
                                        data-name="{{ $appointment->fname }} {{ $appointment->lname }}"
                                        data-status="serving">
                                    <svg viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Done
                                </button>
                                <button class="btn-action secondary no-show-btn" 
                                        data-id="{{ $appointment->n_id }}"
                                        data-name="{{ $appointment->fname }} {{ $appointment->lname }}"
                                        data-status="serving">
                                    No Show
                                </button>
                                <button class="btn-action secondary cancel-btn" 
                                        data-id="{{ $appointment->n_id }}"
                                        data-name="{{ $appointment->fname }} {{ $appointment->lname }}"
                                        data-status="serving">
                                    Cancel
                                </button>
                            </div>
                        @elseif($appointment->status === 'no_show')
                            <button class="btn-action serve-btn" 
                                    data-id="{{ $appointment->n_id }}"
                                    data-name="{{ $appointment->fname }} {{ $appointment->lname }}"
                                    data-status="no_show"
                                    {{ $serveDisabled ? 'disabled' : '' }}
                                    title="{{ $serveDisabled ? 'Cannot serve while another appointment is being served' : 'Call this no-show appointment to your window' }}">
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Serve Again
                            </button>
                        @else
                            <span class="status-text">{{ $statusDisplay }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="empty-state">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                        </svg>
                        <p>No appointments to display</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>