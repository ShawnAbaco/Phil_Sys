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
                    // Disable only completed and cancelled appointments
                    $checkboxDisabled = $appointment->status === 'completed' || $appointment->status === 'cancelled' || $appointment->status === 'serving';
                @endphp
                <tr class="clickable-row {{ $checkboxDisabled ? 'disabled-row' : '' }}" 
                    data-id="{{ $appointment->n_id }}" 
                    data-search="{{ strtolower($appointment->lname . ' ' . $appointment->fname . ' ' . ($appointment->trn ?? '')) }}">
                    <td class="checkbox-cell">
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
                    <td class="action-cell">
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

<style>
/* Clickable row styles */
.clickable-row {
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.clickable-row:hover {
    background-color: rgba(37, 99, 235, 0.03);
}

.clickable-row.disabled-row {
    cursor: default;
}

.clickable-row.disabled-row:hover {
    background-color: transparent;
}

/* Ensure action buttons don't trigger row click */
.action-cell button,
.action-cell .action-button-group,
.status-text {
    position: relative;
    z-index: 2;
}

/* Keep existing selected row style */
.table tr.selected {
    background-color: rgba(37, 99, 235, 0.08);
    border-left: 3px solid var(--psa-blue);
}

.table tr.selected:hover {
    background-color: rgba(37, 99, 235, 0.12);
}
</style>

<script>
// Add this to handle row clicks
document.addEventListener('DOMContentLoaded', function() {
    initializeClickableRows();
    
    // Re-initialize after AJAX updates
    document.addEventListener('clickableRowsUpdate', function() {
        initializeClickableRows();
    });
});

function initializeClickableRows() {
    document.querySelectorAll('.clickable-row').forEach(row => {
        // Remove existing listener to prevent duplicates
        row.removeEventListener('click', handleRowClick);
        row.addEventListener('click', handleRowClick);
    });
}

function handleRowClick(e) {
    // Don't toggle if clicking on button or action elements
    if (e.target.closest('button') || 
        e.target.closest('.btn-action') || 
        e.target.closest('.action-button-group') || 
        e.target.closest('.status-text')) {
        return;
    }
    
    const checkbox = this.querySelector('.row-checkbox');
    if (checkbox && !checkbox.disabled) {
        checkbox.checked = !checkbox.checked;
        
        // Trigger change event to update selection
        const event = new Event('change', { bubbles: true });
        checkbox.dispatchEvent(event);
    }
}

// Update your existing updateAppointmentsTables function to trigger the event
function updateAppointmentsTables(data) {
    if (document.getElementById('table-all')) {
        document.getElementById('table-all').innerHTML = data.tableAll || '';
    }
    
    if (document.getElementById('table-nid-registration')) {
        document.getElementById('table-nid-registration').innerHTML = data.tableNidRegistration || '';
    }
    
    if (document.getElementById('table-status-inquiry')) {
        document.getElementById('table-status-inquiry').innerHTML = data.tableStatusInquiry || '';
    }
    
    if (document.getElementById('table-updating')) {
        document.getElementById('table-updating').innerHTML = data.tableUpdating || '';
    }
    
    attachServeButtonListeners();
    initializeAllTableCheckboxes();
    
    // Trigger clickable rows re-initialization
    document.dispatchEvent(new Event('clickableRowsUpdate'));
    
    if (currentSearchTerm) {
        filterTableRows();
    }
}
</script>