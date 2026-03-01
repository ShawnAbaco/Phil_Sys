<x-header title="Appointment Issuance" />

<!-- Main Container -->
<main class="main-container">
    {{-- Alert Messages --}}
    <div class="alert-container">
        @if (session('success'))
            <div class="alert alert-success">
                <svg class="alert-icon" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <span>{{ session('success') }}</span>
                <button type="button" class="alert-close" onclick="this.parentElement.remove()">×</button>
            </div>
        @endif

        @if (session('error') || $errors->any())
            <div class="alert alert-error">
                <svg class="alert-icon" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
                <div>
                    @if (session('error'))
                        <span>{{ session('error') }}</span>
                    @else
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <button type="button" class="alert-close" onclick="this.parentElement.remove()">×</button>
            </div>
        @endif
    </div>

    <div class="dashboard-grid">
        {{-- Issue New Appointment Card --}}
        <div class="card">
            <div class="card-header">
                <h3>
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                            clip-rule="evenodd" />
                    </svg>
                    Issue New Appointment
                </h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('appointment.issue') }}" id="appointmentForm">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label for="category">Select Category:</label>
                            <select name="category" id="category" onchange="toggleForm()" required>
                                <option value="NID Registration"
                                    {{ old('category') == 'NID Registration' ? 'selected' : '' }}>NID Registration
                                </option>
                                <option value="Status Inquiry"
                                    {{ old('category') == 'Status Inquiry' ? 'selected' : '' }}>Status Inquiry</option>
                                <option value="Updating" {{ old('category') == 'Updating' ? 'selected' : '' }}>Updating
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- NID Registration Form -->
                    <div id="nidForm" style="display:none;">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="fname_nid">First Name</label>
                                <input type="text" name="fname_nid" id="fname_nid" value="{{ old('fname_nid') }}"
                                    data-required="true" class="@error('fname_nid') is-invalid @enderror">
                            </div>
                            <div class="form-group">
                                <label for="mname_nid">Middle Name</label>
                                <input type="text" name="mname_nid" id="mname_nid" value="{{ old('mname_nid') }}">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="lname_nid">Last Name</label>
                                <input type="text" name="lname_nid" id="lname_nid" value="{{ old('lname_nid') }}"
                                    data-required="true" class="@error('lname_nid') is-invalid @enderror">
                            </div>
                            <div class="form-group">
                                <label for="suffix_nid">Suffix</label>
                                <input type="text" name="suffix_nid" id="suffix_nid" value="{{ old('suffix_nid') }}"
                                    placeholder="Jr., Sr., III">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="age_category_nid">Age Category</label>
                                <select name="age_category_nid" id="age_category_nid" data-required="true"
                                    class="@error('age_category_nid') is-invalid @enderror">
                                    <option value="">Select Category</option>
                                    <option value="0-4 years old"
                                        {{ old('age_category_nid') == '0-4 years old' ? 'selected' : '' }}>0-4 years
                                        old</option>
                                    <option value="5 years old and above"
                                        {{ old('age_category_nid') == '5 years old and above' ? 'selected' : '' }}>5
                                        years old and above</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="birthdate_nid">Birthdate</label>
                                <input type="date" name="birthdate_nid" id="birthdate_nid"
                                    value="{{ old('birthdate_nid') }}" data-required="true"
                                    class="@error('birthdate_nid') is-invalid @enderror">
                            </div>
                        </div>
                    </div>

                    <!-- Status Inquiry Form -->
                    <div id="statusForm" style="display:none;">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="fname_status">First Name</label>
                                <input type="text" name="fname_status" id="fname_status"
                                    value="{{ old('fname_status') }}" data-required="true"
                                    class="@error('fname_status') is-invalid @enderror">
                            </div>
                            <div class="form-group">
                                <label for="mname_status">Middle Name</label>
                                <input type="text" name="mname_status" id="mname_status"
                                    value="{{ old('mname_status') }}">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="lname_status">Last Name</label>
                                <input type="text" name="lname_status" id="lname_status"
                                    value="{{ old('lname_status') }}" data-required="true"
                                    class="@error('lname_status') is-invalid @enderror">
                            </div>
                            <div class="form-group">
                                <label for="suffix_status">Suffix</label>
                                <input type="text" name="suffix_status" id="suffix_status"
                                    value="{{ old('suffix_status') }}" placeholder="Jr., Sr., III">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="age_category_status">Age Category</label>
                                <select name="age_category_status" id="age_category_status" data-required="true"
                                    class="@error('age_category_status') is-invalid @enderror">
                                    <option value="">Select Category</option>
                                    <option value="0-4 years old"
                                        {{ old('age_category_status') == '0-4 years old' ? 'selected' : '' }}>0-4 years
                                        old</option>
                                    <option value="5 years old and above"
                                        {{ old('age_category_status') == '5 years old and above' ? 'selected' : '' }}>5
                                        years old and above</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="birthdate_status">Birthdate</label>
                                <input type="date" name="birthdate_status" id="birthdate_status"
                                    value="{{ old('birthdate_status') }}" data-required="true"
                                    class="@error('birthdate_status') is-invalid @enderror">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group" style="flex: 2;">
                                <label for="trn">Transaction Reference Number (TRN)</label>
                                <div style="display: flex; gap: 8px; align-items: center;">
                                    <input type="text" name="trn" id="trn" value="{{ old('trn') }}"
                                        placeholder="Scan QR code or type TRN manually" autocomplete="off"
                                        data-required="true" class="@error('trn') is-invalid @enderror"
                                        style="flex: 1;">
                                    <button type="button" id="openScannerBtn" class="btn btn-primary scan-btn"
                                        style="white-space: nowrap; padding: 8px 16px; min-width: 100px; background-color: #2563eb; color: white; border: none; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center;">
                                        <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16"
                                            style="margin-right: 4px;">
                                            <path fill-rule="evenodd"
                                                d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Scan QR
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- QR Scanner Container -->
                        <div id="qr-reader-container"
                            style="display: none; margin-top: 15px; padding: 15px; border: 2px dashed #2563eb; border-radius: 8px; background: #f8fafc;">
                            <div
                                style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                <span style="font-weight: 600; color: #2563eb;">Scan QR Code</span>
                                <button type="button" id="closeScannerBtn"
                                    style="padding: 4px 8px; font-size: 12px; background-color: #6b7280; color: white; border: none; border-radius: 4px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                                    <svg viewBox="0 0 20 20" fill="currentColor" width="14" height="14">
                                        <path fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Close
                                </button>
                            </div>
                            <div id="qr-reader" style="width: 100%; min-height: 300px;"></div>
                        </div>
                    </div>

                    <!-- Updating Form -->
                    <div id="updatingForm" style="display:none;">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="fname_update">First Name</label>
                                <input type="text" name="fname_update" id="fname_update"
                                    value="{{ old('fname_update') }}" data-required="true"
                                    class="@error('fname_update') is-invalid @enderror">
                            </div>
                            <div class="form-group">
                                <label for="mname_update">Middle Name</label>
                                <input type="text" name="mname_update" id="mname_update"
                                    value="{{ old('mname_update') }}">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="lname_update">Last Name</label>
                                <input type="text" name="lname_update" id="lname_update"
                                    value="{{ old('lname_update') }}" data-required="true"
                                    class="@error('lname_update') is-invalid @enderror">
                            </div>
                            <div class="form-group">
                                <label for="suffix_update">Suffix</label>
                                <input type="text" name="suffix_update" id="suffix_update"
                                    value="{{ old('suffix_update') }}" placeholder="Jr., Sr., III">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="age_category_update">Age Category</label>
                                <select name="age_category_update" id="age_category_update" data-required="true"
                                    class="@error('age_category_update') is-invalid @enderror">
                                    <option value="">Select Category</option>
                                    <option value="0-4 years old"
                                        {{ old('age_category_update') == '0-4 years old' ? 'selected' : '' }}>0-4 years
                                        old</option>
                                    <option value="5 years old and above"
                                        {{ old('age_category_update') == '5 years old and above' ? 'selected' : '' }}>5
                                        years old and above</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="birthdate_update">Birthdate</label>
                                <input type="date" name="birthdate_update" id="birthdate_update"
                                    value="{{ old('birthdate_update') }}" data-required="true"
                                    class="@error('birthdate_update') is-invalid @enderror">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="pcn">PhilSys Card Number (PCN)</label>
                                <input type="text" name="pcn" id="pcn" value="{{ old('pcn') }}"
                                    data-required="true" class="@error('pcn') is-invalid @enderror">
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Issue Appointment
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Today's Appointments Card --}}
        <div class="card">
            <div class="card-header">
                <h3>
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path
                            d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                    </svg>
                    Today's Appointments
                </h3>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon"
                            style="background: linear-gradient(135deg, var(--psa-blue), var(--psa-red))">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path
                                    d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                            </svg>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label">Total Queue Today</span>
                            <span class="stat-value">{{ $queueCount ?? 0 }}</span>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #F59E0B, #FBBF24)">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label">Pending</span>
                            <span class="stat-value pending">{{ $pendingCount ?? 0 }}</span>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #10B981, #34D399)">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label">Completed</span>
                            <span class="stat-value completed">{{ $completedCount ?? 0 }}</span>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, var(--psa-red), #EF4444)">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.293 6.707a1 1 0 001.414 0L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 000 1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label">Queue at Window {{ session('window_num') }}</span>
                            <span class="stat-value">{{ $windowQueueCount ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="search-box">
                    <svg class="search-icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>
                    <input type="text" id="searchAppointments" placeholder="Search...">
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Queue #</th>
                                <th>Name</th>
                                <th>Service</th>
                                <th>Time</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="appointments-table-body" id="appointmentsTableBody">
                            @forelse($appointments as $appointment)
                                @php
                                    $servedTime = $appointment->time_catered
                                        ? \Carbon\Carbon::parse($appointment->time_catered)->setTimezone('Asia/Manila')
                                        : null;
                                    $createdTime = \Carbon\Carbon::parse($appointment->date)->setTimezone(
                                        'Asia/Manila',
                                    );
                                    $isCompleted = $servedTime && $servedTime->gt($createdTime);
                                    $serviceDisplay = $appointment->queue_for;
                                @endphp
                                {{-- Only show if NOT completed --}}
                                @if (!$isCompleted)
                                    <tr
                                        data-search="{{ strtolower($appointment->lname . ' ' . $appointment->fname . ' ' . $appointment->trn) }}">
                                        <td><span class="queue-number">{{ $appointment->q_id }}</span></td>
                                        <td>
                                            <div class="client-name">
                                                {{ $appointment->lname }}, {{ $appointment->fname }}
                                                @if ($appointment->mname || $appointment->suffix)
                                                    <small>{{ $appointment->mname }}
                                                        {{ $appointment->suffix }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $serviceDisplay }}</td>
                                        <td>{{ $createdTime->format('h:i A') }}</td>
                                        <td>
                                            <span class="status-badge status-pending">
                                                <span class="status-dot"></span>
                                                Pending
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn-action serve-btn" data-id="{{ $appointment->n_id }}"
                                                data-name="{{ $appointment->fname }} {{ $appointment->lname }}">
                                                <svg viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Serve
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="6" class="empty-state">
                                        <svg viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <p>No pending appointments for today</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Transactions --}}
    <div class="card full-width">
        <div class="card-header">
            <h3>
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V8a2 2 0 00-2-2h-5L9 4H4zm7 4a1 1 0 10-2 0v3.586l-.293-.293a1 1 0 10-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 10-1.414-1.414l-.293.293V8z"
                        clip-rule="evenodd" />
                </svg>
                Recent Transactions
            </h3>
            <div class="card-actions">
                <span class="badge">Completed Only</span>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
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
                        @forelse($completedTransactions as $transaction)
                            @php
                                $servedTime = \Carbon\Carbon::parse($transaction->time_catered)->setTimezone(
                                    'Asia/Manila',
                                );
                                $serviceDisplay = $transaction->queue_for;
                            @endphp
                            <tr>
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
                                <td colspan="6" class="empty-state">
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
            </div>
        </div>
    </div>
</main>

<!-- QR Scanner Modal -->
<div id="scannerModal" class="scanner-modal" style="display: none;">
    <div class="scanner-modal-content">
        <div class="scanner-modal-header">
            <h3 style="margin: 0; color: #2563eb; display: flex; align-items: center; gap: 8px;">
                <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                    <path fill-rule="evenodd"
                        d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z"
                        clip-rule="evenodd" />
                </svg>
                Scan QR Code
            </h3>
            <button type="button" id="closeModalBtn" class="scanner-modal-close">
                <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                    <path fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>
        <div class="scanner-modal-body">
            <div id="qr-reader" style="width: 100%; min-height: 400px;"></div>
        </div>
        <div class="scanner-modal-footer">
            <button type="button" id="cancelScannerBtn" class="btn btn-secondary"
                style="padding: 8px 16px; background-color: #6b7280; color: white; border: none; border-radius: 4px; cursor: pointer;">
                Cancel
            </button>
        </div>
    </div>
</div>

{{-- Loading Modal --}}
<div class="loading-modal" id="loadingModal">
    <div class="loading-content">
        <img src="{{ asset('images/loading.png') }}" alt="Loading..." class="loading-logo rotate-logo">
        <p class="loading-text">Processing...</p>
    </div>
</div>

{{-- Print Slip Modal (Hidden by default) --}}
@if (session('printSlip'))
    <div id="printSlipModal" style="display: none;">
        <div id="printContent">
            <div style="text-align: center; font-family: 'Courier New', monospace; padding: 20px;">
                <h1 style="font-size: 16pt; margin: 0 0 10px 0;">{{ session('printSlip')['header'] }}</h1>
                <div style="font-size: 10pt; margin-bottom: 5px;">{{ session('printSlip')['name'] }}</div>
                <div style="font-size: 10pt; margin-bottom: 5px;">{{ session('printSlip')['service'] }}</div>
                <div style="font-size: 10pt; margin-bottom: 15px;">{{ session('printSlip')['dateTime'] }}</div>
                <div style="font-size: 28pt; font-weight: bold; letter-spacing: 4px;">
                    {{ session('printSlip')['queueNumber'] }}</div>
            </div>
        </div>
    </div>
@endif

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.4/html5-qrcode.min.js"></script>

<style>
    /* Scanner Modal Styles */
    .scanner-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        z-index: 10000;
        align-items: center;
        justify-content: center;
    }

    .scanner-modal-content {
        background: white;
        border-radius: 12px;
        width: 90%;
        max-width: 600px;
        max-height: 90vh;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: modalSlideIn 0.3s ease;
    }

    @keyframes modalSlideIn {
        from {
            transform: translateY(-30px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .scanner-modal-header {
        padding: 16px 20px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .scanner-modal-close {
        background: none;
        border: none;
        cursor: pointer;
        color: #64748b;
        padding: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        transition: all 0.2s;
    }

    .scanner-modal-close:hover {
        background: #e2e8f0;
        color: #334155;
    }

    .scanner-modal-body {
        padding: 20px;
        min-height: 400px;
        background: #f1f5f9;
    }

    .scanner-modal-footer {
        padding: 16px 20px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
    }

    #qr-reader {
        width: 100%;
        min-height: 400px;
        border: 2px dashed #2563eb;
        border-radius: 8px;
        overflow: hidden;
        background: #000;
        position: relative;
    }

    #qr-reader video {
        width: 100%;
        height: auto;
        min-height: 400px;
        object-fit: cover;
        display: block;
    }

    #qr-reader__scan_region {
        min-height: 400px;
        background: #000;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #qr-reader__dashboard {
        padding: 10px;
        background: #fff;
        position: relative;
        z-index: 10;
    }

    #qr-reader__dashboard_section_csr {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    #qr-reader__dashboard_section_csr button,
    #qr-reader__dashboard_section_csr select {
        padding: 8px 12px;
        border-radius: 4px;
        border: 1px solid #e2e8f0;
        background: white;
        cursor: pointer;
    }

    #qr-reader__dashboard_section_csr button:hover {
        background: #f8fafc;
    }

    .scanner-loading {
        text-align: center;
        padding: 60px 20px;
        color: #2563eb;
    }

    .scanner-loading svg {
        animation: spin 1s linear infinite;
        margin-bottom: 15px;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    .scanner-error {
        text-align: center;
        padding: 40px 20px;
        color: #dc2626;
    }

    .scanner-error svg {
        margin-bottom: 15px;
    }

    .scanner-error button {
        margin: 0 5px;
    }
</style>

<script>
    let html5QrcodeScanner = null;
    let isScanning = false;

    function toggleForm() {
        var category = document.getElementById('category').value;
        var nidForm = document.getElementById('nidForm');
        var statusForm = document.getElementById('statusForm');
        var updatingForm = document.getElementById('updatingForm');

        if (nidForm) nidForm.style.display = 'none';
        if (statusForm) statusForm.style.display = 'none';
        if (updatingForm) updatingForm.style.display = 'none';

        [nidForm, statusForm, updatingForm].forEach(formDiv => {
            if (!formDiv) return;
            var inputs = formDiv.querySelectorAll('input, select');
            inputs.forEach(input => {
                input.removeAttribute('required');
            });
        });

        if (category !== 'Status Inquiry' && isScanning) {
            closeScannerModal();
        }

        var activeForm;
        if (category === 'NID Registration') {
            activeForm = nidForm;
        } else if (category === 'Status Inquiry') {
            activeForm = statusForm;
        } else if (category === 'Updating') {
            activeForm = updatingForm;
        }

        if (activeForm) {
            activeForm.style.display = 'block';
            var inputs = activeForm.querySelectorAll('input, select');
            inputs.forEach(input => {
                if (input.dataset.required === "true") {
                    input.setAttribute('required', 'required');
                }
            });
        }
    }

    async function stopQRScanner() {
        if (html5QrcodeScanner && isScanning) {
            try {
                await html5QrcodeScanner.stop();
                await html5QrcodeScanner.clear();
                html5QrcodeScanner = null;
                isScanning = false;
            } catch (err) {
                console.error('Error stopping scanner:', err);
            }
        }

        // Clear the scanner div
        const qrReader = document.getElementById('qr-reader');
        if (qrReader) {
            qrReader.innerHTML = '';
        }
    }

    async function startQRScanner() {
        const qrReader = document.getElementById('qr-reader');
        const trnInput = document.getElementById('trn');

        if (!qrReader) {
            console.error('QR reader element not found');
            return;
        }

        // Stop any existing scanner first
        await stopQRScanner();

        // Clear and show loading
        qrReader.innerHTML = `
            <div class="scanner-loading" style="text-align: center; padding: 60px 20px; color: #2563eb;">
                <svg viewBox="0 0 20 20" fill="currentColor" width="48" height="48" style="animation: spin 1s linear infinite; margin-bottom: 15px;">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                </svg>
                <p>Initializing camera...<br>Please allow camera access when prompted.</p>
            </div>
        `;

        try {
            // Check if Html5Qrcode is available
            if (typeof Html5Qrcode === 'undefined') {
                throw new Error('QR Scanner library not loaded. Please refresh the page.');
            }

            // Check camera support
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                throw new Error(
                    'Your browser does not support camera access. Please use a modern browser like Chrome, Firefox, or Safari.'
                );
            }

            // Create new scanner instance
            html5QrcodeScanner = new Html5Qrcode("qr-reader");

            // QR code success callback
            const qrCodeSuccessCallback = (decodedText) => {
                trnInput.value = decodedText;
                closeScannerModal();

                // Show success message
                Swal.fire({
                    title: 'Success!',
                    text: 'QR Code scanned successfully!',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false,
                    position: 'top-end',
                    toast: true,
                    showCloseButton: true
                });
            };

            // Scanner configuration
            const config = {
                fps: 10,
                qrbox: {
                    width: 250,
                    height: 250
                },
                aspectRatio: 1.0
            };

            // Try to get cameras
            const cameras = await Html5Qrcode.getCameras();

            if (cameras && cameras.length > 0) {
                // Try to find back camera
                let selectedCameraId = cameras[0].id; // Default to first camera

                for (const camera of cameras) {
                    const label = camera.label.toLowerCase();
                    if (label.includes('back') || label.includes('environment') || label.includes('rear')) {
                        selectedCameraId = camera.id;
                        break;
                    }
                }

                // Start scanning
                await html5QrcodeScanner.start({
                        deviceId: selectedCameraId
                    },
                    config,
                    qrCodeSuccessCallback,
                    (errorMessage) => {
                        // Ignore scan errors - these are normal
                        // console.log(errorMessage);
                    }
                );

                isScanning = true;
            } else {
                // No cameras found, try facingMode
                await html5QrcodeScanner.start({
                        facingMode: "environment"
                    },
                    config,
                    qrCodeSuccessCallback,
                    (errorMessage) => {
                        // Ignore scan errors
                    }
                );

                isScanning = true;
            }

        } catch (err) {
            console.error('Scanner error:', err);

            let errorMessage = 'Unable to access camera. ';

            if (err.name === 'NotAllowedError' || err.message.includes('permission')) {
                errorMessage =
                    'Camera access denied. Please allow camera access in your browser settings and try again.';
            } else if (err.name === 'NotFoundError' || err.message.includes('not found')) {
                errorMessage = 'No camera found on this device.';
            } else if (err.name === 'NotReadableError' || err.message.includes('in use')) {
                errorMessage = 'Camera is already in use by another application.';
            } else {
                errorMessage += err.message || 'Please check your camera and try again.';
            }

            qrReader.innerHTML = `
                <div class="scanner-error" style="text-align: center; padding: 40px 20px; color: #dc2626;">
                    <svg viewBox="0 0 20 20" fill="currentColor" width="48" height="48" style="margin-bottom: 15px;">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <p style="margin-bottom: 20px; color: #4b5563;">${errorMessage}</p>
                    <div>
                        <button onclick="startQRScanner()" class="btn btn-primary" style="padding: 8px 16px; background-color: #2563eb; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 8px;">
                            Try Again
                        </button>
                        <button onclick="closeScannerModal()" class="btn btn-secondary" style="padding: 8px 16px; background-color: #6b7280; color: white; border: none; border-radius: 4px; cursor: pointer;">
                            Close
                        </button>
                    </div>
                </div>
            `;
        }
    }

    function showScannerModal() {
        const modal = document.getElementById('scannerModal');
        if (modal) {
            modal.style.display = 'flex';
            // Small delay to ensure modal is visible
            setTimeout(() => {
                startQRScanner();
            }, 200);
        }
    }

    function closeScannerModal() {
        const modal = document.getElementById('scannerModal');
        if (modal) {
            modal.style.display = 'none';
        }
        stopQRScanner();
    }

    document.addEventListener('DOMContentLoaded', function() {
        @if (session('success'))
            showSuccessPopup(
                '{{ session('success') }}',
                @if (session('printSlip'))
                    '{{ session('printSlip')['queueNumber'] }}'
                @else
                    null
                @endif
            );
        @endif

        @if (session('error'))
            showErrorPopup('{{ session('error') }}');
        @endif

        @if ($errors->any())
            let errors = @json($errors->all());
            showErrorPopup('Please fix the following errors:', errors);
        @endif

        const oldCategory = '{{ old('category') }}';
        if (oldCategory) {
            document.getElementById('category').value = oldCategory;
        }
        toggleForm();

        // Remove the inline scanner container if it exists
        const inlineScanner = document.getElementById('qr-reader-container');
        if (inlineScanner) {
            inlineScanner.remove();
        }

        // Scanner modal controls
        const openScannerBtn = document.getElementById('openScannerBtn');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const cancelScannerBtn = document.getElementById('cancelScannerBtn');
        const modal = document.getElementById('scannerModal');

        if (openScannerBtn) {
            openScannerBtn.addEventListener('click', () => {
                const category = document.getElementById('category').value;

                if (category !== 'Status Inquiry') {
                    showWarningPopup('Please select Status Inquiry category first.');
                    document.getElementById('category').value = 'Status Inquiry';
                    toggleForm();
                    return;
                }

                showScannerModal();
            });
        }

        if (closeModalBtn) {
            closeModalBtn.addEventListener('click', closeScannerModal);
        }

        if (cancelScannerBtn) {
            cancelScannerBtn.addEventListener('click', closeScannerModal);
        }

        // Close modal when clicking outside
        window.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeScannerModal();
            }
        });

        // Add spin animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);

        @if (session('printSlip'))
            setTimeout(() => {
                const printContent = document.getElementById('printContent').innerHTML;
                const printWindow = window.open('', '_blank', 'width=300,height=250');
                printWindow.document.write(`
                <html>
                <head>
                    <title>Appointment Slip</title>
                    <style>
                        @media print {
                            @page { size: 3in 2.5in; margin: 0; }
                            body {
                                margin: 0; padding: 20px; width: 2.5in; height: 2in;
                                font-family: "Courier New", Courier, monospace; font-size: 10pt;
                                color: #000; box-sizing: border-box; text-align: center;
                            }
                        }
                        h1 { font-size: 14pt; margin: 0 0 10px 0; }
                        .info { font-size: 10pt; margin-bottom: 5px; }
                        .date-time { font-size: 10pt; margin-bottom: 15px; }
                        .queue-number { font-size: 28pt; font-weight: bold; letter-spacing: 4px; margin: 0; }
                    </style>
                </head>
                <body>${printContent}</body>
                </html>
            `);
                printWindow.document.close();
                printWindow.focus();
                printWindow.onload = () => printWindow.print();
                printWindow.onafterprint = () => printWindow.close();
            }, 500);
        @endif

        function searchAppointments() {
            const searchTerm = document.getElementById('searchAppointments').value.toLowerCase();
            const rows = document.querySelectorAll('#appointmentsTableBody tr');
            rows.forEach(row => {
                const searchData = row.dataset.search || row.textContent.toLowerCase();
                row.style.display = searchData.includes(searchTerm) ? '' : 'none';
            });
        }

        document.getElementById('searchAppointments')?.addEventListener('keyup', searchAppointments);

        document.querySelectorAll('.serve-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                const name = this.dataset.name;

                showConfirmPopup(
                    'Serve Appointment',
                    `Mark appointment for ${name} as served?`,
                    function() {
                        showLoading();
                        fetch(`/appointment/serve/${id}`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]')?.content,
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                hideLoading();
                                if (data.success) {
                                    showSuccessPopup('Appointment marked as served!');
                                    setTimeout(() => location.reload(), 1500);
                                } else {
                                    showErrorPopup(data.message ||
                                        'Error marking appointment');
                                }
                            })
                            .catch(error => {
                                hideLoading();
                                showErrorPopup('Connection error. Please try again.');
                                console.error(error);
                            });
                    }
                );
            });
        });

        document.getElementById('appointmentForm')?.addEventListener('submit', function(e) {
            const category = document.getElementById('category').value;
            if (!category) {
                e.preventDefault();
                showWarningPopup('Please select a category');
                return;
            }
            showLoading();
        });
    });

    // Popup functions (keep your existing popup functions here)
    function showSuccessPopup(message, queueNumber = null) {
        let html = `<div style="text-align: center;">${message}</div>`;

        if (queueNumber) {
            html = `
                <div style="text-align: center;">
                    <div style="font-size: 18px; margin-bottom: 10px;">${message}</div>
                    <div style="font-size: 32px; font-weight: bold; color: #059669; background: #ecfdf5; padding: 15px; border-radius: 10px; margin: 10px 0;">
                        ${queueNumber}
                    </div>
                </div>
            `;
        }

        Swal.fire({
            title: 'Success!',
            html: html,
            icon: 'success',
            confirmButtonColor: '#2563eb',
            confirmButtonText: 'OK',
            timer: 5000,
            timerProgressBar: true,
            showCloseButton: true
        }).then(() => {
            location.reload();
        });
    }

    function showErrorPopup(message, errors = null) {
        let html = `<div style="text-align: center; color: #991b1b;">${message}</div>`;

        if (errors) {
            let errorsList = '<ul style="text-align: left; margin-top: 10px; color: #991b1b;">';
            if (typeof errors === 'object') {
                Object.values(errors).forEach(error => {
                    if (Array.isArray(error)) {
                        error.forEach(err => {
                            errorsList += `<li>${err}</li>`;
                        });
                    } else {
                        errorsList += `<li>${error}</li>`;
                    }
                });
            }
            errorsList += '</ul>';
            html += errorsList;
        }

        Swal.fire({
            title: 'Error!',
            html: html,
            icon: 'error',
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'Try Again',
            showCloseButton: true
        });
    }

    function showWarningPopup(message) {
        Swal.fire({
            title: 'Warning!',
            text: message,
            icon: 'warning',
            confirmButtonColor: '#d97706',
            confirmButtonText: 'OK'
        });
    }

    function showConfirmPopup(title, text, callback) {
        Swal.fire({
            title: title,
            text: text,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#dc2626',
            confirmButtonText: 'Yes, proceed!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed && callback) {
                callback();
            }
        });
    }

    function showLoading() {
        document.getElementById('loadingModal')?.classList.add('show');
    }

    function hideLoading() {
        document.getElementById('loadingModal')?.classList.remove('show');
    }
</script>
