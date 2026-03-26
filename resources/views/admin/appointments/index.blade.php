@extends('layouts.admin')

@section('title', 'Appointments')
@section('pageTitle', 'Appointment Management')
@section('pageSubtitle', 'Manage and monitor all appointments')

@php $activeMenu = 'appointments'; @endphp

@section('content')
    <div class="container-fluid">
        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $totalAppointments ?? 0 }}</h3>
                        <p>Total Today</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $pendingAppointments ?? 0 }}</h3>
                        <p>Pending</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $completedAppointments ?? 0 }}</h3>
                        <p>Completed</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h3>{{ $cancelledAppointments ?? 0 }}</h3>
                        <p>Cancelled</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Actions -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Appointments List</h3>
                <div class="card-tools">
                    <div class="btn-group">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter Form -->
                <form method="GET" action="{{ route('admin.appointments.index') }}" class="mb-4">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date Filter</label>
                                <select name="filter" class="form-control" onchange="this.form.submit()">
                                    <option value="">All Dates</option>
                                    <option value="today" {{ request('filter') == 'today' ? 'selected' : '' }}>Today
                                    </option>
                                    <option value="yesterday" {{ request('filter') == 'yesterday' ? 'selected' : '' }}>
                                        Yesterday</option>
                                    <option value="week" {{ request('filter') == 'week' ? 'selected' : '' }}>This Week
                                    </option>
                                    <option value="month" {{ request('filter') == 'month' ? 'selected' : '' }}>This Month
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control" onchange="this.form.submit()">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                        Completed</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Search</label>
                                <input type="text" name="search" class="form-control"
                                    placeholder="Search by name, queue ID, or TRN..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search"></i> Search
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Appointments Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Queue ID</th>
                                <th>Name</th>
                                <th>Service</th>
                                <th>Date & Time</th>
                                <th>Window</th>
                                <th>Status</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($appointments as $appointment)
                                <tr>
                                    <td>
                                        <span class="badge badge-primary">{{ $appointment->q_id }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $appointment->lname }}, {{ $appointment->fname }}</strong>
                                        @if ($appointment->mname)
                                            <br><small class="text-muted">{{ $appointment->mname }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $appointment->queue_for }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($appointment->date)->format('M d, Y') }}
                                        <br>
                                        <small>{{ \Carbon\Carbon::parse($appointment->date)->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        @if ($appointment->window_num)
                                            <span class="badge badge-info">Window {{ $appointment->window_num }}</span>
                                        @else
                                            <span class="text-muted">Not assigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($appointment->time_catered)
                                            <span class="badge badge-success">Completed</span>
                                            <br>
                                            <small>{{ \Carbon\Carbon::parse($appointment->time_catered)->format('h:i A') }}</small>
                                        @else
                                            <span class="badge badge-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.appointments.show', $appointment->n_id) }}"
                                                class="btn btn-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.appointments.edit', $appointment->n_id) }}"
                                                class="btn btn-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if (!$appointment->time_catered)
                                                <form action="{{ route('admin.appointments.serve', $appointment->n_id) }}"
                                                    method="POST" style="display: inline-block;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success"
                                                        onclick="return confirm('Mark this appointment as served?')"
                                                        title="Mark as Served">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('admin.appointments.destroy', $appointment->n_id) }}"
                                                method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this appointment?')"
                                                    title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-calendar-times fa-3x mb-3 d-block text-muted"></i>
                                        <h5>No appointments found</h5>
                                        <p class="text-muted">Try adjusting your filters or search criteria.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $appointments->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
