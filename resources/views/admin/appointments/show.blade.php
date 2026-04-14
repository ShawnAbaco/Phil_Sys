@extends('layouts.admin')

@section('title', 'Appointment Details')
@section('pageTitle', 'Appointment Details')
@section('pageSubtitle', 'View complete appointment information')

@php $activeMenu = 'appointments'; @endphp

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-calendar-check"></i> 
                        Appointment Information
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.appointments.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="35%">Queue ID:</th>
                                    <td><span class="badge badge-primary badge-lg">{{ $appointment->q_id }}</span></td>
                                </tr>
                                <tr>
                                    <th>Full Name:</th>
                                    <td>
                                        <strong>{{ $appointment->lname }}, {{ $appointment->fname }}</strong>
                                        @if($appointment->mname)
                                            <br><small>{{ $appointment->mname }}</small>
                                        @endif
                                        @if($appointment->suffix)
                                            <br><small>{{ $appointment->suffix }}</small>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Service Type:</th>
                                    <td>{{ $appointment->queue_for }}</td>
                                </tr>
                                <tr>
                                    <th>Date & Time:</th>
                                    <td>
                                        {{ \Carbon\Carbon::parse($appointment->date)->format('F d, Y') }}
                                        <br>
                                        <span class="text-muted">{{ \Carbon\Carbon::parse($appointment->date)->format('h:i A') }}</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="35%">TRN:</th>
                                    <td>{{ $appointment->trn ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>PCN:</th>
                                    <td>{{ $appointment->PCN ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Window Number:</th>
                                    <td>
                                        @if($appointment->window_num)
                                            <span class="badge badge-info">Window {{ $appointment->window_num }}</span>
                                        @else
                                            <span class="text-muted">Not assigned</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if($appointment->time_catered)
                                            <span class="badge badge-success">Completed</span>
                                            <br>
                                            <small class="text-muted">
                                                Served at: {{ \Carbon\Carbon::parse($appointment->time_catered)->format('F d, Y h:i A') }}
                                            </small>
                                        @else
                                            <span class="badge badge-warning">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($appointment->birthdate)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Personal Information</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>Birthdate:</strong> {{ \Carbon\Carbon::parse($appointment->birthdate)->format('F d, Y') }}
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Age Category:</strong> {{ $appointment->age_category ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Actions</h3>
                </div>
                <div class="card-body">
                    @if(!$appointment->time_catered)
                    <form action="{{ route('admin.appointments.serve', $appointment->n_id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-block mb-2" 
                                onclick="return confirm('Mark this appointment as served?')">
                            <i class="fas fa-check-circle"></i> Mark as Served
                        </button>
                    </form>
                    @endif
                    
                    <a href="{{ route('admin.appointments.edit', $appointment->n_id) }}" class="btn btn-primary btn-block mb-2">
                        <i class="fas fa-edit"></i> Edit Appointment
                    </a>
                    
                    <form action="{{ route('admin.appointments.destroy', $appointment->n_id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block" 
                                onclick="return confirm('Are you sure you want to delete this appointment? This action cannot be undone.')">
                            <i class="fas fa-trash"></i> Delete Appointment
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection