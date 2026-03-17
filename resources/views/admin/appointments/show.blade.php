@extends('layouts.admin')

@section('title', 'Appointment Details')
@section('pageTitle', 'Appointment Details')
@section('pageSubtitle', 'View complete appointment information')

@php $activeMenu = 'appointments'; @endphp

@section('content')
<div class="appointment-details-container">
    <!-- Header with Actions -->
    <div class="details-header">
        <div class="header-left">
            <a href="{{ route('admin.appointments') }}" class="btn-back">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back to Appointments
            </a>
            <div class="queue-number-display">
                <span class="queue-label">Queue Number</span>
                <span class="queue-value">{{ $appointment->q_id }}</span>
            </div>
        </div>
        <div class="header-actions">
            <button class="btn btn-outline" onclick="printAppointment()">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
                </svg>
                Print
            </button>
            <a href="{{ route('admin.appointments.edit', $appointment->n_id) }}" class="btn btn-primary">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                </svg>
                Edit Appointment
            </a>
        </div>
    </div>

    <!-- Status Banner -->
    <div class="status-banner status-{{ $appointment->time_catered ? 'completed' : 'pending' }}">
        <div class="status-icon">
            @if($appointment->time_catered)
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
            @else
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                </svg>
            @endif
        </div>
        <div class="status-text">
            <h3>{{ $appointment->time_catered ? 'Completed' : 'Pending' }} Appointment</h3>
            <p>{{ $appointment->time_catered ? 'This appointment has been served' : 'This appointment is waiting to be served' }}</p>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="details-grid">
        <!-- Client Information Card -->
        <div class="detail-card">
            <div class="card-header">
                <h3>
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                    </svg>
                    Client Information
                </h3>
            </div>
            <div class="card-body">
                <div class="info-row">
                    <span class="info-label">Full Name:</span>
                    <span class="info-value">
                        {{ $appointment->lname }}, {{ $appointment->fname }}
                        @if($appointment->mname || $appointment->suffix)
                            {{ trim($appointment->mname . ' ' . $appointment->suffix) }}
                        @endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Age Category:</span>
                    <span class="info-value">{{ $appointment->age_category ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Birthdate:</span>
                    <span class="info-value">
                        {{ $appointment->birthdate ? \Carbon\Carbon::parse($appointment->birthdate)->format('F d, Y') : 'N/A' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Appointment Details Card -->
        <div class="detail-card">
            <div class="card-header">
                <h3>
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                    </svg>
                    Appointment Details
                </h3>
            </div>
            <div class="card-body">
                <div class="info-row">
                    <span class="info-label">Queue Number:</span>
                    <span class="info-value"><strong>{{ $appointment->q_id }}</strong></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Service Type:</span>
                    <span class="info-value">{{ $appointment->queue_for }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Date Created:</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($appointment->date)->format('F d, Y h:i A') }}</span>
                </div>
            </div>
        </div>

        <!-- Service Details Card -->
        <div class="detail-card">
            <div class="card-header">
                <h3>
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V8a2 2 0 00-2-2h-5L9 4H4zm7 4a1 1 0 10-2 0v3.586l-.293-.293a1 1 0 10-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 10-1.414-1.414l-.293.293V8z" clip-rule="evenodd" />
                    </svg>
                    Service Details
                </h3>
            </div>
            <div class="card-body">
                <div class="info-row">
                    <span class="info-label">TRN:</span>
                    <span class="info-value">{{ $appointment->trn ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">PCN:</span>
                    <span class="info-value">{{ $appointment->PCN ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Service Status Card -->
        <div class="detail-card">
            <div class="card-header">
                <h3>
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                    </svg>
                    Service Status
                </h3>
            </div>
            <div class="card-body">
                <div class="info-row">
                    <span class="info-label">Window Assigned:</span>
                    <span class="info-value">
                        @if($appointment->window_num)
                            <span class="window-badge">Window {{ $appointment->window_num }}</span>
                        @else
                            <span class="text-muted">Not assigned</span>
                        @endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Time Served:</span>
                    <span class="info-value">
                        {{ $appointment->time_catered ? \Carbon\Carbon::parse($appointment->time_catered)->format('F d, Y h:i A') : 'Not served yet' }}
                    </span>
                </div>
                @if($appointment->user_id)
                <div class="info-row">
                    <span class="info-label">Served By:</span>
                    <span class="info-value">
                        @php
                            $operator = \App\Models\User::find($appointment->user_id);
                        @endphp
                        {{ $operator->name ?? 'Unknown Operator' }}
                    </span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Timeline Section -->
    <div class="timeline-card">
        <h3>Appointment Timeline</h3>
        <div class="timeline">
            <div class="timeline-item">
                <div class="timeline-marker created"></div>
                <div class="timeline-content">
                    <h4>Appointment Created</h4>
                    <p>{{ \Carbon\Carbon::parse($appointment->date)->format('F d, Y h:i A') }}</p>
                </div>
            </div>
            
            @if($appointment->window_num)
            <div class="timeline-item">
                <div class="timeline-marker assigned"></div>
                <div class="timeline-content">
                    <h4>Window Assigned</h4>
                    <p>Window {{ $appointment->window_num }}</p>
                </div>
            </div>
            @endif
            
            @if($appointment->time_catered)
            <div class="timeline-item">
                <div class="timeline-marker completed"></div>
                <div class="timeline-content">
                    <h4>Service Completed</h4>
                    <p>{{ \Carbon\Carbon::parse($appointment->time_catered)->format('F d, Y h:i A') }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        @if(!$appointment->time_catered)
        <button class="btn btn-success" onclick="markAsServed({{ $appointment->n_id }})">
            <svg viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
            Mark as Served
        </button>
        @endif
        
        <button class="btn btn-danger" onclick="deleteAppointment({{ $appointment->n_id }})">
            <svg viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            Delete Appointment
        </button>
    </div>
</div>

@push('styles')
<style>
    .appointment-details-container {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    
    .details-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: white;
        border-radius: 16px;
        padding: 20px;
        border: 2px solid var(--gray-200);
    }
    
    .header-left {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    
    .btn-back {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border: 2px solid var(--gray-200);
        border-radius: 30px;
        color: var(--gray-600);
        text-decoration: none;
        transition: all 0.2s ease;
    }
    
    .btn-back:hover {
        border-color: var(--psa-blue);
        color: var(--psa-blue);
        background: var(--psa-blue-light);
    }
    
    .btn-back svg {
        width: 18px;
        height: 18px;
    }
    
    .queue-number-display {
        text-align: center;
    }
    
    .queue-label {
        display: block;
        font-size: 0.8rem;
        color: var(--gray-500);
        margin-bottom: 2px;
    }
    
    .queue-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--psa-blue);
        line-height: 1.2;
    }
    
    .header-actions {
        display: flex;
        gap: 10px;
    }
    
    .status-banner {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 24px;
        border-radius: 16px;
        color: white;
    }
    
    .status-banner.pending {
        background: linear-gradient(135deg, var(--warning), #fbbf24);
    }
    
    .status-banner.completed {
        background: linear-gradient(135deg, var(--success), #34d399);
    }
    
    .status-icon {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .status-icon svg {
        width: 30px;
        height: 30px;
    }
    
    .status-text h3 {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 4px;
    }
    
    .status-text p {
        opacity: 0.9;
    }
    
    .details-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    
    .detail-card {
        background: white;
        border-radius: 16px;
        border: 2px solid var(--gray-200);
        overflow: hidden;
    }
    
    .card-header {
        padding: 16px 20px;
        background: var(--gray-50);
        border-bottom: 2px solid var(--gray-200);
    }
    
    .card-header h3 {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 1rem;
        font-weight: 600;
        color: var(--psa-blue);
    }
    
    .card-header svg {
        width: 20px;
        height: 20px;
    }
    
    .card-body {
        padding: 20px;
    }
    
    .info-row {
        display: flex;
        padding: 10px 0;
        border-bottom: 1px solid var(--gray-100);
    }
    
    .info-row:last-child {
        border-bottom: none;
    }
    
    .info-label {
        width: 120px;
        font-weight: 500;
        color: var(--gray-600);
    }
    
    .info-value {
        flex: 1;
        color: var(--gray-800);
    }
    
    .window-badge {
        display: inline-block;
        padding: 4px 12px;
        background: var(--psa-yellow-light);
        color: #b45309;
        border-radius: 30px;
        font-weight: 500;
    }
    
    .text-muted {
        color: var(--gray-400);
    }
    
    .timeline-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        border: 2px solid var(--gray-200);
    }
    
    .timeline-card h3 {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--psa-blue);
        margin-bottom: 20px;
    }
    
    .timeline {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    
    .timeline-item {
        display: flex;
        gap: 15px;
        align-items: flex-start;
    }
    
    .timeline-marker {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        margin-top: 4px;
        position: relative;
    }
    
    .timeline-marker::after {
        content: '';
        position: absolute;
        top: 16px;
        left: 7px;
        width: 2px;
        height: 40px;
        background: var(--gray-300);
    }
    
    .timeline-item:last-child .timeline-marker::after {
        display: none;
    }
    
    .timeline-marker.created {
        background: var(--psa-blue);
        box-shadow: 0 0 0 4px var(--psa-blue-light);
    }
    
    .timeline-marker.assigned {
        background: var(--warning);
        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.2);
    }
    
    .timeline-marker.completed {
        background: var(--success);
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.2);
    }
    
    .timeline-content h4 {
        font-size: 1rem;
        font-weight: 600;
        color: var(--gray-800);
        margin-bottom: 4px;
    }
    
    .timeline-content p {
        color: var(--gray-500);
        font-size: 0.9rem;
    }
    
    .action-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 10px;
    }
    
    .btn-success {
        background: var(--success);
        color: white;
    }
    
    .btn-success:hover {
        background: #0d9488;
    }
    
    @media (max-width: 768px) {
        .details-header {
            flex-direction: column;
            gap: 15px;
        }
        
        .header-left {
            flex-direction: column;
            text-align: center;
        }
        
        .details-grid {
            grid-template-columns: 1fr;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .action-buttons button {
            width: 100%;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    function printAppointment() {
        window.print();
    }
    
    function markAsServed(id) {
        Swal.fire({
            title: 'Mark as Served?',
            text: 'This appointment will be marked as completed.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#CE1126',
            confirmButtonText: 'Yes, mark as served',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();
                fetch(`/admin/appointments/${id}/serve`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Appointment marked as served.',
                            icon: 'success',
                            confirmButtonColor: '#0038A8'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: data.message || 'Failed to mark as served.',
                            icon: 'error',
                            confirmButtonColor: '#CE1126'
                        });
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred. Please try again.',
                        icon: 'error',
                        confirmButtonColor: '#CE1126'
                    });
                });
            }
        });
    }
    
    function deleteAppointment(id) {
        Swal.fire({
            title: 'Delete Appointment?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#CE1126',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();
                fetch(`/admin/appointments/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        window.location.href = '{{ route("admin.appointments") }}';
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: data.message || 'Failed to delete appointment.',
                            icon: 'error',
                            confirmButtonColor: '#CE1126'
                        });
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred. Please try again.',
                        icon: 'error',
                        confirmButtonColor: '#CE1126'
                    });
                });
            }
        });
    }
</script>
@endpush
@endsection