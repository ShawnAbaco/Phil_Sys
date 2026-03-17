@extends('layouts.admin')

@section('title', 'Edit Appointment')
@section('pageTitle', 'Edit Appointment')
@section('pageSubtitle', 'Update appointment information')

@php $activeMenu = 'appointments'; @endphp

@section('content')
<div class="form-container">
    <div class="form-card">
        <div class="form-card-header">
            <h3>Edit Appointment: {{ $appointment->q_id }}</h3>
            <p>Update appointment details and information</p>
        </div>
        
        <form method="POST" action="{{ route('admin.appointments.update', $appointment->n_id) }}" id="editAppointmentForm">
            @csrf
            @method('PUT')
            
            <div class="form-section">
                <h4>Client Information</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label for="fname">First Name <span class="required">*</span></label>
                        <input type="text" id="fname" name="fname" class="form-control @error('fname') is-invalid @enderror" value="{{ old('fname', $appointment->fname) }}" required>
                        @error('fname')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="mname">Middle Name</label>
                        <input type="text" id="mname" name="mname" class="form-control" value="{{ old('mname', $appointment->mname) }}">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="lname">Last Name <span class="required">*</span></label>
                        <input type="text" id="lname" name="lname" class="form-control @error('lname') is-invalid @enderror" value="{{ old('lname', $appointment->lname) }}" required>
                        @error('lname')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="suffix">Suffix</label>
                        <input type="text" id="suffix" name="suffix" class="form-control" value="{{ old('suffix', $appointment->suffix) }}" placeholder="Jr., Sr., III">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="age_category">Age Category <span class="required">*</span></label>
                        <select id="age_category" name="age_category" class="form-control @error('age_category') is-invalid @enderror" required>
                            <option value="">Select Category</option>
                            <option value="0-4 years old" {{ old('age_category', $appointment->age_category) == '0-4 years old' ? 'selected' : '' }}>0-4 years old</option>
                            <option value="5 years old and above" {{ old('age_category', $appointment->age_category) == '5 years old and above' ? 'selected' : '' }}>5 years old and above</option>
                        </select>
                        @error('age_category')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="birthdate">Birthdate <span class="required">*</span></label>
                        <input type="date" id="birthdate" name="birthdate" class="form-control @error('birthdate') is-invalid @enderror" value="{{ old('birthdate', $appointment->birthdate ? \Carbon\Carbon::parse($appointment->birthdate)->format('Y-m-d') : '') }}" required>
                        @error('birthdate')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h4>Service Details</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label for="queue_for">Service Type <span class="required">*</span></label>
                        <select id="queue_for" name="queue_for" class="form-control @error('queue_for') is-invalid @enderror" required>
                            <option value="">Select Service</option>
                            <option value="NID Registration" {{ old('queue_for', $appointment->queue_for) == 'NID Registration' ? 'selected' : '' }}>NID Registration</option>
                            <option value="Status Inquiry" {{ old('queue_for', $appointment->queue_for) == 'Status Inquiry' ? 'selected' : '' }}>Status Inquiry</option>
                            <option value="Updating" {{ old('queue_for', $appointment->queue_for) == 'Updating' ? 'selected' : '' }}>Updating</option>
                        </select>
                        @error('queue_for')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="queue_number">Queue Number</label>
                        <input type="text" id="queue_number" class="form-control" value="{{ $appointment->q_id }}" readonly disabled>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="trn">TRN (Transaction Reference Number)</label>
                        <input type="text" id="trn" name="trn" class="form-control" value="{{ old('trn', $appointment->trn) }}" maxlength="29">
                        @error('trn')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="PCN">PCN (PhilSys Card Number)</label>
                        <input type="text" id="PCN" name="PCN" class="form-control" value="{{ old('PCN', $appointment->PCN) }}" maxlength="16">
                        @error('PCN')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h4>Service Status</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label for="window_num">Window Number</label>
                        <select id="window_num" name="window_num" class="form-control">
                            <option value="">Not Assigned</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ old('window_num', $appointment->window_num) == $i ? 'selected' : '' }}>Window {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="pending" {{ !$appointment->time_catered ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ $appointment->time_catered ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                </div>
                
                @if($appointment->time_catered)
                <div class="form-row">
                    <div class="form-group">
                        <label for="time_catered">Served Time</label>
                        <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($appointment->time_catered)->format('F d, Y h:i A') }}" readonly disabled>
                    </div>
                    <div class="form-group">
                        <label>Served By</label>
                        <input type="text" class="form-control" value="{{ $appointment->user ? $appointment->user->name : 'N/A' }}" readonly disabled>
                    </div>
                </div>
                @endif
            </div>
            
            <div class="form-actions">
                <a href="{{ route('admin.appointments.show', $appointment->n_id) }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Appointment</button>
            </div>
        </form>
    </div>
    
    <!-- History Card -->
    <div class="history-card">
        <h4>Appointment History</h4>
        <div class="history-list">
            <div class="history-item">
                <div class="history-icon" style="background: var(--psa-blue-light);">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="history-content">
                    <p>Created on {{ \Carbon\Carbon::parse($appointment->date)->format('F d, Y h:i A') }}</p>
                </div>
            </div>
            
            @if($appointment->window_num)
            <div class="history-item">
                <div class="history-icon" style="background: var(--psa-yellow-light);">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path d="M7 8a3 3 0 100-6 3 3 0 000 6zM14.5 9a2.5 2.5 0 100-5 2.5 2.5 0 000 5zM1.615 16.428a1.224 1.224 0 01-.569-1.175 6.002 6.002 0 0111.908 0c.058.467-.172.92-.57 1.174A9.953 9.953 0 017 18a9.953 9.953 0 01-5.385-1.572zM14.5 16h-.106c.07-.297.088-.611.048-.933a7.47 7.47 0 00-1.588-3.755 4.502 4.502 0 015.874 2.636.818.818 0 01-.36.98A7.963 7.963 0 0114.5 16z" />
                    </svg>
                </div>
                <div class="history-content">
                    <p>Assigned to Window {{ $appointment->window_num }}</p>
                </div>
            </div>
            @endif
            
            @if($appointment->time_catered)
            <div class="history-item">
                <div class="history-icon" style="background: var(--success); color: white;">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="history-content">
                    <p>Served on {{ \Carbon\Carbon::parse($appointment->time_catered)->format('F d, Y h:i A') }}</p>
                    @if($appointment->user)
                    <small>by {{ $appointment->user->name }}</small>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .form-container {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
        align-items: start;
    }
    
    .form-card {
        background: white;
        border-radius: 20px;
        padding: 24px;
        border: 2px solid var(--gray-200);
    }
    
    .form-card-header {
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid var(--gray-200);
    }
    
    .form-card-header h3 {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--psa-blue);
        margin-bottom: 4px;
    }
    
    .form-card-header p {
        color: var(--gray-500);
        font-size: 0.9rem;
    }
    
    .form-section {
        margin-bottom: 24px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--gray-200);
    }
    
    .form-section h4 {
        font-size: 1rem;
        font-weight: 600;
        color: var(--gray-700);
        margin-bottom: 16px;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 16px;
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    
    .form-group label {
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--gray-700);
    }
    
    .required {
        color: var(--danger);
        margin-left: 2px;
    }
    
    .form-control {
        padding: 10px 14px;
        border: 2px solid var(--gray-200);
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }
    
    .form-control:focus {
        outline: none;
        border-color: var(--psa-blue);
        box-shadow: 0 0 0 4px var(--psa-blue-light);
    }
    
    .form-control.is-invalid {
        border-color: var(--danger);
    }
    
    .form-control[readonly],
    .form-control[disabled] {
        background: var(--gray-100);
        cursor: not-allowed;
    }
    
    .error-message {
        color: var(--danger);
        font-size: 0.8rem;
        margin-top: 4px;
    }
    
    .history-card {
        background: white;
        border-radius: 20px;
        padding: 24px;
        border: 2px solid var(--gray-200);
    }
    
    .history-card h4 {
        font-size: 1rem;
        font-weight: 600;
        color: var(--gray-700);
        margin-bottom: 20px;
        padding-bottom: 8px;
        border-bottom: 2px solid var(--gray-200);
    }
    
    .history-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    
    .history-item {
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }
    
    .history-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--psa-blue);
        flex-shrink: 0;
    }
    
    .history-icon svg {
        width: 18px;
        height: 18px;
    }
    
    .history-content p {
        color: var(--gray-700);
        margin-bottom: 2px;
    }
    
    .history-content small {
        color: var(--gray-500);
        font-size: 0.8rem;
    }
    
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 24px;
        padding-top: 16px;
        border-top: 2px solid var(--gray-200);
    }
    
    @media (max-width: 768px) {
        .form-container {
            grid-template-columns: 1fr;
        }
        
        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush
@endsection