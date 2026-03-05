<x-header title="Appointment Issuance" />

<!-- Main Container -->
<main class="main-container">
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
                                <label for="PCN">PhilSys Card Number (PCN)</label>
                                <input type="text" name="PCN" id="PCN" value="{{ old('PCN') }}"
                                    data-required="true" class="@error('PCN') is-invalid @enderror">
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
                            <span class="stat-value" id="totalQueue">{{ $queueCount ?? 0 }}</span>
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
                            <span class="stat-value pending" id="pendingCount">{{ $pendingCount ?? 0 }}</span>
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
                            <span class="stat-value completed" id="completedCount">{{ $completedCount ?? 0 }}</span>
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



    {{-- Add this in the card-header of Recent Transactions --}}
    <div class="card full-width" id="recentTransactionsCard">
        <div class="card-header">
            <h3>
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V8a2 2 0 00-2-2h-5L9 4H4zm7 4a1 1 0 10-2 0v3.586l-.293-.293a1 1 0 10-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 10-1.414-1.414l-.293.293V8z"
                        clip-rule="evenodd" />
                </svg>
                Recent Transactions
            </h3>
            <div class="card-actions" style="display: flex; gap: 10px; align-items: center;">
                <span class="badge" id="showingInfo">Showing
                    {{ $completedTransactions->firstItem() }}-{{ $completedTransactions->lastItem() }} of
                    {{ $completedTransactions->total() }}</span>

                {{-- EXPORT BUTTONS --}}
                <div class="export-buttons" style="display: flex; gap: 8px;">
                    <a href="{{ route('appointment.export.pdf') }}" class="btn btn-danger"
                        style="padding: 6px 12px; background-color: #dc2626; color: white; border: none; border-radius: 4px; cursor: pointer; display: inline-flex; align-items: center; gap: 5px; text-decoration: none;"
                        onclick="showLoading()">
                        <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                            <path fill-rule="evenodd"
                                d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                clip-rule="evenodd" />
                        </svg>
                        Export PDF
                    </a>
                    <a href="{{ route('appointment.export.csv') }}" class="btn btn-success"
                        style="padding: 6px 12px; background-color: #059669; color: white; border: none; border-radius: 4px; cursor: pointer; display: inline-flex; align-items: center; gap: 5px; text-decoration: none;"
                        onclick="showLoading()">
                        <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                            <path fill-rule="evenodd"
                                d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                        Export CSV
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Queue #</th>
                            <th>Client</th>
                            <th>Service</th>
                            <th>Served Time</th>
                            <th>Window</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="transactionsTableContainer">
                        @forelse($completedTransactions as $index => $transaction)
                            @php
                                $servedTime = \Carbon\Carbon::parse($transaction->time_catered)->setTimezone(
                                    'Asia/Manila',
                                );
                                $serviceDisplay = $transaction->queue_for;
                                $rowNumber =
                                    ($completedTransactions->currentPage() - 1) * $completedTransactions->perPage() +
                                    $loop->iteration;
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
            </div>
        </div>

        {{-- Enhanced Pagination with Strict 5-Page Blocks --}}
        <div class="enhanced-pagination" id="paginationContainer">
            @include('appointment.partials.pagination-links', [
                'completedTransactions' => $completedTransactions,
            ])
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

    <script>
        let html5QrcodeScanner = null;
        let isScanning = false;
        let currentSearchTerm = '';
        let isLoading = false;
        let refreshInterval;

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

            await stopQRScanner();

            qrReader.innerHTML = `
        <div class="scanner-loading" style="text-align: center; padding: 60px 20px; color: #2563eb;">
            <svg viewBox="0 0 20 20" fill="currentColor" width="48" height="48" style="animation: spin 1s linear infinite; margin-bottom: 15px;">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
            </svg>
            <p>Initializing camera...<br>Please allow camera access when prompted.</p>
        </div>
    `;

            try {
                if (typeof Html5Qrcode === 'undefined') {
                    throw new Error('QR Scanner library not loaded. Please refresh the page.');
                }

                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    throw new Error(
                        'Your browser does not support camera access. Please use a modern browser like Chrome, Firefox, or Safari.'
                    );
                }

                html5QrcodeScanner = new Html5Qrcode("qr-reader");

                const qrCodeSuccessCallback = (decodedText) => {
                    trnInput.value = decodedText;
                    closeScannerModal();

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

                const config = {
                    fps: 10,
                    qrbox: {
                        width: 250,
                        height: 250
                    },
                    aspectRatio: 1.0
                };

                const cameras = await Html5Qrcode.getCameras();

                if (cameras && cameras.length > 0) {
                    let selectedCameraId = cameras[0].id;

                    for (const camera of cameras) {
                        const label = camera.label.toLowerCase();
                        if (label.includes('back') || label.includes('environment') || label.includes('rear')) {
                            selectedCameraId = camera.id;
                            break;
                        }
                    }

                    await html5QrcodeScanner.start({
                            deviceId: selectedCameraId
                        },
                        config,
                        qrCodeSuccessCallback,
                        (errorMessage) => {}
                    );

                    isScanning = true;
                } else {
                    await html5QrcodeScanner.start({
                            facingMode: "environment"
                        },
                        config,
                        qrCodeSuccessCallback,
                        (errorMessage) => {}
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

        // ENHANCED AUTO-REFRESH FUNCTION with cache busting
        function fetchAppointments() {
            // Don't fetch if we're in the middle of pagination loading
            if (isLoading) return;

            // Add cache-busting timestamp
            const timestamp = new Date().getTime();

            fetch('{{ route('appointment.today') }}?_=' + timestamp, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Cache-Control': 'no-cache, no-store, must-revalidate',
                        'Pragma': 'no-cache',
                        'Expires': '0'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateAppointmentsTable(data.appointments);
                        updateStatistics(data.stats);
                        console.log('Auto-refresh completed at', new Date().toLocaleTimeString(), 'Appointments:', data
                            .appointments.length);
                    }
                })
                .catch(error => {
                    console.error('Error fetching appointments:', error);
                });
        }

        // NEW FUNCTION to fetch recent transactions
        function fetchRecentTransactions() {
            if (isLoading) return;

            const timestamp = new Date().getTime();
            const currentUrl = window.location.href;

            fetch(currentUrl + (currentUrl.includes('?') ? '&' : '?') + '_=' + timestamp, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Cache-Control': 'no-cache, no-store, must-revalidate'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    const newTransactions = doc.querySelector('#transactionsTableContainer');
                    const newShowingInfo = doc.querySelector('#showingInfo');
                    const newPagination = doc.querySelector('#paginationContainer');

                    if (newTransactions) {
                        document.getElementById('transactionsTableContainer').innerHTML = newTransactions.innerHTML;
                    }
                    if (newShowingInfo) {
                        document.getElementById('showingInfo').textContent = newShowingInfo.textContent;
                    }
                    if (newPagination) {
                        document.getElementById('paginationContainer').innerHTML = newPagination.innerHTML;
                    }

                    console.log('Recent transactions refreshed');
                })
                .catch(error => console.error('Error fetching recent transactions:', error));
        }

        function updateAppointmentsTable(appointments) {
            const tbody = document.getElementById('appointmentsTableBody');

            if (!appointments || appointments.length === 0) {
                tbody.innerHTML = `
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
            `;
                return;
            }

            let html = '';
            appointments.forEach(app => {
                if (!app.time_catered) {
                    const createdTime = new Date(app.date).toLocaleTimeString('en-US', {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: true,
                        timeZone: 'Asia/Manila'
                    });

                    let fullName = app.lname + ', ' + app.fname;

                    if (app.mname && app.mname.trim() !== '') {
                        fullName += ' ' + app.mname;
                    }

                    if (app.suffix && app.suffix.trim() !== '') {
                        fullName += ' ' + app.suffix;
                    }

                    const searchData = (app.lname + ' ' + app.fname + ' ' + (app.trn || '')).toLowerCase();

                    html += `
                    <tr data-search="${searchData}">
                        <td><span class="queue-number">${app.q_id}</span></td>
                        <td>
                            <div class="client-name">
                                ${fullName}
                            </div>
                        </td>
                        <td>${app.queue_for}</td>
                        <td>${createdTime}</td>
                        <td>
                            <span class="status-badge status-pending">
                                <span class="status-dot"></span>
                                Pending
                            </span>
                        </td>
                    </tr>
                `;
                }
            });

            if (html === '') {
                tbody.innerHTML = `
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
            `;
            } else {
                tbody.innerHTML = html;

                if (currentSearchTerm) {
                    filterTableRows();
                }
            }
        }

        function updateStatistics(stats) {
            if (!stats) return;

            if (stats.total !== undefined) {
                document.getElementById('totalQueue').textContent = stats.total;
            }
            if (stats.pending !== undefined) {
                document.getElementById('pendingCount').textContent = stats.pending;
            }
            if (stats.completed !== undefined) {
                document.getElementById('completedCount').textContent = stats.completed;
            }
        }

        function filterTableRows() {
            const rows = document.querySelectorAll('#appointmentsTableBody tr');

            rows.forEach(row => {
                if (row.classList.contains('empty-state')) return;

                const searchData = row.getAttribute('data-search') || row.textContent.toLowerCase();
                if (searchData.includes(currentSearchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Smooth AJAX Pagination
        function loadPage(url) {
            if (isLoading) return;
            isLoading = true;

            // Add loading states with smooth fade
            const tableContainer = document.getElementById('transactionsTableContainer');
            const paginationContainer = document.getElementById('paginationContainer');
            const showingInfo = document.getElementById('showingInfo');

            tableContainer.classList.add('loading');
            paginationContainer.classList.add('loading');

            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    // Parse the HTML response
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    // Extract the new table rows (tbody content)
                    const newTableBody = doc.querySelector('#transactionsTableContainer');
                    const newPagination = doc.querySelector('#paginationContainer');
                    const newShowingInfo = doc.querySelector('#showingInfo');

                    // Smooth fade out/in
                    tableContainer.style.opacity = '0';
                    paginationContainer.style.opacity = '0';

                    setTimeout(() => {
                        // Update content
                        if (newTableBody) {
                            tableContainer.innerHTML = newTableBody.innerHTML;
                        }
                        if (newPagination) {
                            paginationContainer.innerHTML = newPagination.innerHTML;
                        }
                        if (newShowingInfo) {
                            showingInfo.textContent = newShowingInfo.textContent;
                        }

                        // Fade back in
                        tableContainer.style.opacity = '1';
                        paginationContainer.style.opacity = '1';

                        // Add success animation
                        tableContainer.classList.add('page-change-success');
                        paginationContainer.classList.add('page-change-success');

                        setTimeout(() => {
                            tableContainer.classList.remove('page-change-success');
                            paginationContainer.classList.remove('page-change-success');
                        }, 500);

                        // Remove loading states
                        tableContainer.classList.remove('loading');
                        paginationContainer.classList.remove('loading');

                        // Re-attach event listeners
                        attachPaginationListeners();

                        isLoading = false;
                    }, 150);
                })
                .catch(error => {
                    console.error('Error loading page:', error);

                    // Remove loading states on error
                    tableContainer.classList.remove('loading');
                    paginationContainer.classList.remove('loading');
                    tableContainer.style.opacity = '1';
                    paginationContainer.style.opacity = '1';

                    isLoading = false;

                    // Show error message
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to load page. Please try again.',
                        icon: 'error',
                        confirmButtonColor: '#dc2626',
                        timer: 2000,
                        showConfirmButton: false
                    });
                });
        }

        function attachPaginationListeners() {
            // Handle all pagination links
            document.querySelectorAll(
                    '.pagination-nav-btn:not(.disabled), .pagination-arrow:not(.disabled), .page-number:not(.active)')
                .forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        if (!isLoading) {
                            loadPage(this.href);
                        }
                    });
                });
        }

        // ENHANCED AUTO-REFRESH FUNCTION
        function startAutoRefresh() {
            console.log('Auto-refresh started');

            // Clear any existing interval
            if (refreshInterval) {
                clearInterval(refreshInterval);
            }

            // Set new interval - refresh every 10 seconds
            refreshInterval = setInterval(function() {
                console.log('Auto-refresh triggered');
                fetchAppointments();
                fetchRecentTransactions(); // Also refresh recent transactions
            }, 10000); // 10000ms = 10 seconds

            // Also refresh when user returns to the tab
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden && !isLoading) {
                    console.log('Tab became visible, refreshing');
                    fetchAppointments();
                    fetchRecentTransactions();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing...');

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

            const inlineScanner = document.getElementById('qr-reader-container');
            if (inlineScanner) {
                inlineScanner.remove();
            }

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

            window.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeScannerModal();
                }
            });

            // Initialize pagination listeners
            attachPaginationListeners();

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

            const searchInput = document.getElementById('searchAppointments');
            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    currentSearchTerm = this.value.toLowerCase();
                    filterTableRows();
                });
            }

            document.getElementById('appointmentForm')?.addEventListener('submit', function(e) {
                const category = document.getElementById('category').value;
                if (!category) {
                    e.preventDefault();
                    showWarningPopup('Please select a category');
                    return;
                }
                showLoading();
            });

            // Start auto-refresh
            startAutoRefresh();

            // Initial fetch
            setTimeout(() => {
                fetchAppointments();
                fetchRecentTransactions();
            }, 1000);
        });

        window.addEventListener('beforeunload', function() {
            if (refreshInterval) {
                clearInterval(refreshInterval);
            }
        });

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
</main>
