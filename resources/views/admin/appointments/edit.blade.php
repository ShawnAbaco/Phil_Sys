@extends('layouts.admin')

@section('title', 'Edit Appointment')
@section('pageTitle', 'Edit Appointment')
@section('pageSubtitle', 'Update appointment details')

@php $activeMenu = 'appointments'; @endphp

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-edit"></i>
                            Edit Appointment - Queue ID: {{ $appointment->q_id }}
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.appointments.show', $appointment->n_id) }}"
                                class="btn btn-default btn-sm">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </div>
                    <form action="{{ route('admin.appointments.update', $appointment->n_id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fname">First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('fname') is-invalid @enderror"
                                            id="fname" name="fname" value="{{ old('fname', $appointment->fname) }}"
                                            required>
                                        @error('fname')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mname">Middle Name</label>
                                        <input type="text" class="form-control @error('mname') is-invalid @enderror"
                                            id="mname" name="mname" value="{{ old('mname', $appointment->mname) }}">
                                        @error('mname')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="lname">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('lname') is-invalid @enderror"
                                            id="lname" name="lname" value="{{ old('lname', $appointment->lname) }}"
                                            required>
                                        @error('lname')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="suffix">Suffix</label>
                                        <select class="form-control @error('suffix') is-invalid @enderror" id="suffix"
                                            name="suffix">
                                            <option value="">None</option>
                                            <option value="Jr"
                                                {{ old('suffix', $appointment->suffix) == 'Jr' ? 'selected' : '' }}>Jr.
                                            </option>
                                            <option value="Sr"
                                                {{ old('suffix', $appointment->suffix) == 'Sr' ? 'selected' : '' }}>Sr.
                                            </option>
                                            <option value="III"
                                                {{ old('suffix', $appointment->suffix) == 'III' ? 'selected' : '' }}>III
                                            </option>
                                        </select>
                                        @error('suffix')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="birthdate">Birthdate <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control @error('birthdate') is-invalid @enderror"
                                            id="birthdate" name="birthdate"
                                            value="{{ old('birthdate', $appointment->birthdate) }}" required>
                                        @error('birthdate')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="age_category">Age Category <span class="text-danger">*</span></label>
                                        <select class="form-control @error('age_category') is-invalid @enderror"
                                            id="age_category" name="age_category" required>
                                            <option value="">Select Category</option>
                                            <option value="Child"
                                                {{ old('age_category', $appointment->age_category) == 'Child' ? 'selected' : '' }}>
                                                Child (0-12 years)</option>
                                            <option value="Teen"
                                                {{ old('age_category', $appointment->age_category) == 'Teen' ? 'selected' : '' }}>
                                                Teen (13-19 years)</option>
                                            <option value="Adult"
                                                {{ old('age_category', $appointment->age_category) == 'Adult' ? 'selected' : '' }}>
                                                Adult (20-59 years)</option>
                                            <option value="Senior"
                                                {{ old('age_category', $appointment->age_category) == 'Senior' ? 'selected' : '' }}>
                                                Senior (60+ years)</option>
                                        </select>
                                        @error('age_category')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="queue_for">Service Type <span class="text-danger">*</span></label>
                                        <select class="form-control @error('queue_for') is-invalid @enderror" id="queue_for"
                                            name="queue_for" required>
                                            <option value="">Select Service</option>
                                            <option value="Registration"
                                                {{ old('queue_for', $appointment->queue_for) == 'Registration' ? 'selected' : '' }}>
                                                Registration</option>
                                            <option value="Status Inquiry"
                                                {{ old('queue_for', $appointment->queue_for) == 'Status Inquiry' ? 'selected' : '' }}>
                                                Status Inquiry</option>
                                            <option value="Updating"
                                                {{ old('queue_for', $appointment->queue_for) == 'Updating' ? 'selected' : '' }}>
                                                Updating Information</option>
                                            <option value="Others"
                                                {{ old('queue_for', $appointment->queue_for) == 'Others' ? 'selected' : '' }}>
                                                Others</option>
                                        </select>
                                        @error('queue_for')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="window_num">Window Number</label>
                                        <input type="number" class="form-control @error('window_num') is-invalid @enderror"
                                            id="window_num" name="window_num"
                                            value="{{ old('window_num', $appointment->window_num) }}" min="1"
                                            max="10">
                                        @error('window_num')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                        <small class="text-muted">Leave empty if not assigned</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="trn">TRN (Transaction Reference Number)</label>
                                        <input type="text" class="form-control @error('trn') is-invalid @enderror"
                                            id="trn" name="trn" value="{{ old('trn', $appointment->trn) }}"
                                            maxlength="29">
                                        @error('trn')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="PCN">PCN (PhilSys Card Number)</label>
                                        <input type="text" class="form-control @error('PCN') is-invalid @enderror"
                                            id="PCN" name="PCN" value="{{ old('PCN', $appointment->PCN) }}"
                                            maxlength="16">
                                        @error('PCN')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Appointment
                            </button>
                            <a href="{{ route('admin.appointments.show', $appointment->n_id) }}" class="btn btn-default">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Auto-calculate age category based on birthdate
        document.getElementById('birthdate').addEventListener('change', function() {
            const birthdate = new Date(this.value);
            const today = new Date();
            let age = today.getFullYear() - birthdate.getFullYear();
            const monthDiff = today.getMonth() - birthdate.getMonth();

            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthdate.getDate())) {
                age--;
            }

            let category = '';
            if (age <= 12) category = 'Child';
            else if (age <= 19) category = 'Teen';
            else if (age <= 59) category = 'Adult';
            else category = 'Senior';

            document.getElementById('age_category').value = category;
        });
    </script>
@endpush
