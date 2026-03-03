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

{{-- Recent Transactions with Smooth AJAX Pagination --}}
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
        <div class="card-actions">
            <span class="badge" id="showingInfo">Showing {{ $completedTransactions->firstItem() }}-{{ $completedTransactions->lastItem() }} of {{ $completedTransactions->total() }}</span>
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
        </div>
        
        {{-- Enhanced Pagination with Strict 5-Page Blocks --}}
        <div class="enhanced-pagination" id="paginationContainer">
            @include('appointment.partials.pagination-links', ['completedTransactions' => $completedTransactions])
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
/* ===== RESET & BASE STYLES ===== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

:root {
    --primary: #2563eb;
    --primary-dark: #1d4ed8;
    --success: #059669;
    --warning: #d97706;
    --danger: #dc2626;
    --gray-50: #f8fafc;
    --gray-100: #f1f5f9;
    --gray-200: #e2e8f0;
    --gray-300: #cbd5e1;
    --gray-400: #94a3b8;
    --gray-500: #64748b;
    --gray-600: #475569;
    --gray-700: #334155;
    --gray-800: #1e293b;
    --gray-900: #0f172a;
    --shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    --radius: 0.5rem;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
    background: var(--gray-50);
    color: var(--gray-700);
    line-height: 1.5;
}

/* ===== MAIN CONTAINER ===== */
.main-container {
    max-width: 1500px;
    margin: 0 auto;
}

/* ===== ALERTS ===== */
.alert-container {
    margin-bottom: 2rem;
}

.alert {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 1rem 1.5rem;
    border-radius: var(--radius);
    margin-bottom: 1rem;
    border: 1px solid transparent;
    position: relative;
}

.alert-success {
    background: #ecfdf5;
    border-color: #a7f3d0;
    color: #065f46;
}

.alert-error {
    background: #fef2f2;
    border-color: #fecaca;
    color: #991b1b;
}

.alert-icon {
    width: 1.25rem;
    height: 1.25rem;
    flex-shrink: 0;
    margin-top: 0.125rem;
}

.alert-close {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: none;
    border: none;
    font-size: 1.25rem;
    cursor: pointer;
    color: currentColor;
    opacity: 0.5;
    padding: 0.25rem 0.5rem;
}

.alert-close:hover {
    opacity: 1;
}

/* ===== STATS GRID ===== */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: var(--radius);
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-200);
}

.stat-icon {
    width: 3rem;
    height: 3rem;
    border-radius: var(--radius);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    flex-shrink: 0;
}

.stat-icon svg {
    width: 1.5rem;
    height: 1.5rem;
}

.stat-content {
    min-width: 0;
    flex: 1;
}

.stat-label {
    display: block;
    font-size: 0.875rem;
    color: var(--gray-500);
    margin-bottom: 0.25rem;
    white-space: nowrap;
}

.stat-value {
    display: block;
    font-size: 1.875rem;
    font-weight: 600;
    color: var(--gray-900);
    line-height: 1.2;
}

.stat-value.pending {
    color: var(--warning);
}

.stat-value.completed {
    color: var(--success);
}

/* ===== DASHBOARD GRID ===== */
.dashboard-grid {
    display: grid;
    grid-template-columns: 350px 1fr;
    gap: 23.5rem;
    margin-bottom: 1.5rem;
    align-items: start;
}

.dashboard-grid .card:first-child {
    width: 700px;
    position: sticky;
    top: 1rem;
}

.dashboard-grid .card:last-child {
    width: 100%;
    height: 572px;
}

/* ===== CARDS ===== */
.card {
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    border: 1px solid var(--gray-200);
    overflow: hidden;
    height: fit-content;
    margin-top: 30px;
}

.card-header {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--gray-200);
    background: var(--gray-50);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.card-header h3 {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--gray-800);
    white-space: nowrap;
}

.card-header h3 svg {
    width: 1.25rem;
    height: 1.25rem;
    color: var(--primary);
}

.card-actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.card-body {
    padding: 1.5rem;
}

/* ===== FORMS ===== */
.form-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    margin-bottom: 1rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.375rem;
}

.form-group label {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--gray-700);
}

.form-group input,
.form-group select {
    padding: 0.625rem 0.875rem;
    border: 1px solid var(--gray-300);
    border-radius: var(--radius);
    font-size: 0.875rem;
    transition: all 0.15s;
    background: white;
    width: 100%;
}

.form-group input:hover,
.form-group select:hover {
    border-color: var(--gray-400);
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.field-hint {
    font-size: 0.75rem;
    color: var(--gray-500);
    font-weight: normal;
    margin-left: 0.25rem;
}

/* ===== BUTTONS ===== */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.625rem 1.25rem;
    border: none;
    border-radius: var(--radius);
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.15s;
}

.btn-primary {
    background: var(--primary);
    color: white;
}

.btn-primary:hover {
    background: var(--primary-dark);
}

.btn-primary svg {
    width: 1.125rem;
    height: 1.125rem;
}

.btn-action {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.375rem 0.75rem;
    border: 1px solid var(--gray-300);
    border-radius: var(--radius);
    font-size: 0.75rem;
    font-weight: 500;
    cursor: pointer;
    background: white;
    color: var(--gray-700);
    transition: all 0.15s;
}

.btn-action:hover {
    background: var(--gray-50);
    border-color: var(--gray-400);
}

.btn-action svg {
    width: 0.875rem;
    height: 0.875rem;
    color: var(--success);
}

/* ===== SEARCH BOX ===== */
.search-box {
    position: relative;
    margin-bottom: 1.5rem;
}

.search-icon {
    position: absolute;
    left: 0.875rem;
    top: 50%;
    transform: translateY(-50%);
    width: 1.125rem;
    height: 1.125rem;
    color: var(--gray-400);
}

.search-box input {
    width: 100%;
    padding: 0.625rem 1rem 0.625rem 2.5rem;
    border: 1px solid var(--gray-300);
    border-radius: var(--radius);
    font-size: 0.875rem;
    background: white;
}

.search-box input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

/* ===== TABLES ===== */
.table-responsive {
    overflow-x: auto;
    border-radius: var(--radius);
    margin: 0;
    width: 100%;
}

.table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.875rem;
    min-width: 650px;
}

.table th {
    text-align: left;
    padding: 0.875rem 1rem;
    background: var(--gray-50);
    color: var(--gray-600);
    font-weight: 600;
    border-bottom: 1px solid var(--gray-200);
    white-space: nowrap;
}

.table td {
    padding: 0.875rem 1rem;
    border-bottom: 1px solid var(--gray-200);
    color: var(--gray-600);
    vertical-align: middle;
}

.table th:nth-child(1) { width: 80px; }
.table th:nth-child(2) { min-width: 150px; }
.table th:nth-child(3) { min-width: 120px; }
.table th:nth-child(4) { width: 90px; }
.table th:nth-child(5) { width: 100px; }
.table th:nth-child(6) { width: 100px; }

.table tbody tr:last-child td {
    border-bottom: none;
}

.table tbody tr:hover td {
    background: var(--gray-50);
}

.queue-number {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    background: var(--gray-100);
    border-radius: 0.375rem;
    font-weight: 500;
    color: var(--gray-700);
    font-size: 0.75rem;
    white-space: nowrap;
}

.queue-number.small {
    font-size: 0.6875rem;
    padding: 0.1875rem 0.375rem;
}

.client-name {
    font-weight: 500;
    color: var(--gray-800);
    line-height: 1.4;
}

.client-name small {
    display: block;
    font-size: 0.6875rem;
    color: var(--gray-500);
    margin-top: 0.125rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 140px;
}

/* ===== STATUS BADGES ===== */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
    white-space: nowrap;
}

.status-dot {
    width: 0.5rem;
    height: 0.5rem;
    border-radius: 9999px;
    display: inline-block;
}

.status-completed {
    background: #ecfdf5;
    color: #065f46;
}

.status-completed .status-dot {
    background: var(--success);
}

.status-pending {
    background: #fffbeb;
    color: #92400e;
}

.status-pending .status-dot {
    background: var(--warning);
}

.badge-completed {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: green;
    color: black;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
    white-space: nowrap;
}

/* ===== WINDOW INDICATOR ===== */
.window-indicator {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: #e0f2fe;
    color: #0369a1;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
    white-space: nowrap;
}

/* ===== EMPTY STATE ===== */
.empty-state {
    text-align: center;
    padding: 3rem 2rem !important;
    color: var(--gray-500);
}

.empty-state svg {
    width: 3rem;
    height: 3rem;
    margin: 0 auto 1rem;
    color: var(--gray-300);
}

.empty-state p {
    font-size: 0.875rem;
}

/* ===== FORM ACTIONS ===== */
.form-actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 1.5rem;
    padding-top: 1rem;
    border-top: 1px solid var(--gray-200);
}

/* ===== FULL WIDTH CARD ===== */
.card.full-width {
    grid-column: 1 / -1;
    margin-top: 30px;
    height: auto !important;
    display: flex;
    flex-direction: column;
}

.card.full-width .card-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    padding: 1.5rem;
    min-height: auto;
    max-height: none;
}

/* ===== LOADING MODAL ===== */
.loading-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    align-items: center;
    justify-content: center;
}

.loading-modal.show {
    display: flex;
}

.loading-content {
    background: white;
    padding: 2rem;
    border-radius: var(--radius);
    text-align: center;
    box-shadow: var(--shadow-md);
    min-width: 200px;
}

.loading-logo {
    width: 48px;
    height: 48px;
    margin-bottom: 1rem;
}

.rotate-logo {
    animation: spin 1s linear infinite;
}

.loading-text {
    color: var(--gray-600);
    font-size: 0.875rem;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

/* ===== TODAY'S APPOINTMENTS SECTION ===== */
.dashboard-grid .card:last-child .stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.75rem;
    margin: 0.75rem 0 0 0;
    padding: 0 0.25rem;
}

.dashboard-grid .card:last-child .stat-card {
    background: white;
    border-radius: 0.5rem;
    padding: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.625rem;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    border: 1px solid var(--gray-200);
    transition: all 0.15s;
    position: relative;
    overflow: hidden;
}

.dashboard-grid .card:last-child .stat-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.dashboard-grid .card:last-child .stat-icon {
    width: 2rem;
    height: 2rem;
    border-radius: 0.375rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    flex-shrink: 0;
}

.dashboard-grid .card:last-child .stat-icon svg {
    width: 1rem;
    height: 1rem;
}

.dashboard-grid .card:last-child .stat-content {
    min-width: 0;
    flex: 1;
}

.dashboard-grid .card:last-child .stat-label {
    display: block;
    font-size: 0.625rem;
    font-weight: 500;
    color: var(--gray-500);
    margin-bottom: 0.125rem;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.dashboard-grid .card:last-child .stat-value {
    display: block;
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--gray-900);
    line-height: 1.2;
}

.dashboard-grid .card:last-child .stat-value.pending {
    color: var(--warning);
}

.dashboard-grid .card:last-child .stat-value.completed {
    color: var(--success);
}

.dashboard-grid .card:last-child .card-header {
    padding: 0.875rem 1.25rem;
    border-bottom: 1px solid var(--gray-200);
    background: var(--gray-50);
}

.dashboard-grid .card:last-child .card-header h3 {
    font-size: 0.9375rem;
    margin: 0;
}

.dashboard-grid .card:last-child .card-header h3 svg {
    width: 1rem;
    height: 1rem;
}

.dashboard-grid .card:last-child .search-box {
    position: relative;
    margin: 0.75rem 0 0.875rem 0;
}

.dashboard-grid .card:last-child .search-icon {
    position: absolute;
    left: 0.625rem;
    top: 50%;
    transform: translateY(-50%);
    width: 0.875rem;
    height: 0.875rem;
    color: var(--gray-400);
}

.dashboard-grid .card:last-child .search-box input {
    width: 100%;
    padding: 0.4375rem 0.625rem 0.4375rem 2rem;
    border: 1px solid var(--gray-300);
    border-radius: 0.375rem;
    font-size: 0.75rem;
    background: white;
    transition: all 0.15s;
}

.dashboard-grid .card:last-child .search-box input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
}

.dashboard-grid .card:last-child .table-responsive {
    overflow-x: auto;
    overflow-y: auto;
    border-radius: 0.375rem;
    margin: 0;
    width: 100%;
    max-height: 450px;
    border: 1px solid var(--gray-200);
    scrollbar-width: thin;
    scrollbar-color: var(--gray-400) var(--gray-100);
}

.dashboard-grid .card:last-child .table-responsive::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

.dashboard-grid .card:last-child .table-responsive::-webkit-scrollbar-track {
    background: var(--gray-100);
}

.dashboard-grid .card:last-child .table-responsive::-webkit-scrollbar-thumb {
    background: var(--gray-400);
    border-radius: 3px;
}

.dashboard-grid .card:last-child .table-responsive::-webkit-scrollbar-thumb:hover {
    background: var(--gray-500);
}

.dashboard-grid .card:last-child .table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 0.75rem;
    min-width: 650px;
}

.dashboard-grid .card:last-child .table thead {
    position: sticky;
    top: 0;
    z-index: 10;
}

.dashboard-grid .card:last-child .table th {
    text-align: left;
    padding: 0.625rem 0.875rem;
    background: var(--gray-100);
    color: var(--gray-700);
    font-weight: 600;
    font-size: 0.6875rem;
    text-transform: uppercase;
    letter-spacing: 0.025em;
    border-bottom: 2px solid var(--gray-300);
    white-space: nowrap;
    position: sticky;
    top: 0;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.dashboard-grid .card:last-child .table td {
    padding: 0.625rem 0.875rem;
    border-bottom: 1px solid var(--gray-200);
    color: var(--gray-600);
    vertical-align: middle;
    background: white;
}

.dashboard-grid .card:last-child .table tbody tr:last-child td {
    border-bottom: none;
}

.dashboard-grid .card:last-child .table tbody tr:hover td {
    background: var(--gray-50);
}

.dashboard-grid .card:last-child .table th:nth-child(1) { width: 65px; }
.dashboard-grid .card:last-child .table th:nth-child(2) { min-width: 130px; }
.dashboard-grid .card:last-child .table th:nth-child(3) { min-width: 100px; }
.dashboard-grid .card:last-child .table th:nth-child(4) { width: 75px; }
.dashboard-grid .card:last-child .table th:nth-child(5) { width: 85px; }
.dashboard-grid .card:last-child .table th:nth-child(6) { width: 75px; }

.dashboard-grid .card:last-child .queue-number {
    display: inline-block;
    padding: 0.1875rem 0.375rem;
    background: var(--gray-100);
    border-radius: 0.5rem;
    font-weight: 600;
    color: var(--gray-700);
    font-size: 1rem;
    white-space: nowrap;
    font-family: Arial, Helvetica, sans-serif;
}

.dashboard-grid .card:last-child .client-name {
    font-weight: 500;
    color: var(--gray-800);
    line-height: 1.3;
    font-size: 1rem;
}

.dashboard-grid .card:last-child .client-name small {
    display: block;
    font-size: 0.9rem;
    color: var(--gray-500);
    margin-top: 0.125rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 120px;
}

.dashboard-grid .card:last-child .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.1875rem 0.4375rem;
    border-radius: 9999px;
    font-size: 0.625rem;
    font-weight: 500;
    white-space: nowrap;
}

.dashboard-grid .card:last-child .status-dot {
    width: 0.3125rem;
    height: 0.3125rem;
    border-radius: 9999px;
    display: inline-block;
}

.dashboard-grid .card:last-child .btn-action {
    display: inline-flex;
    align-items: center;
    gap: 0.1875rem;
    padding: 0.7rem 1rem;
    border: 1px solid var(--gray-300);
    border-radius: 0.25rem;
    font-size: 0.8rem;
    font-weight: 500;
    cursor: pointer;
    background: white;
    color: var(--gray-700);
    transition: all 0.15s;
}

.dashboard-grid .card:last-child .btn-action:hover {
    background: var(--gray-50);
    border-color: var(--gray-400);
}

.dashboard-grid .card:last-child .btn-action svg {
    width: 0.8875rem;
    height: 0.8875rem;
}

.dashboard-grid .card:last-child .badge-completed {
    display: inline-block;
    padding: 0.7rem 1.4rem;
    background: #ecfdf5;
    color: #065f46;
    border-radius: 9px;
    font-size: 0.8rem;
    font-weight: 500;
    white-space: nowrap;
}

.dashboard-grid .card:last-child .empty-state {
    text-align: center;
    padding: 2rem 1rem !important;
    color: var(--gray-500);
}

.dashboard-grid .card:last-child .empty-state svg {
    width: 2rem;
    height: 2rem;
    margin: 0 auto 0.5rem;
    color: var(--gray-300);
}

.dashboard-grid .card:last-child .empty-state p {
    font-size: 0.75rem;
}

.dashboard-grid .card:last-child .card-body {
    padding: 0.875rem 1rem 1rem 1rem;
}

/* ===== SCANNER MODAL STYLES ===== */
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

/* ===== ENHANCED PAGINATION STYLES ===== */
.enhanced-pagination {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 20px;
    padding: 20px 0 10px 0;
    border-top: 2px solid var(--gray-200);
    background: white;
    position: relative;
    bottom: 0;
    gap: 15px;
    flex-wrap: wrap;
    transition: opacity 0.3s ease;
}

.enhanced-pagination.loading {
    opacity: 0.6;
    pointer-events: none;
    position: relative;
}

.enhanced-pagination.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 30px;
    height: 30px;
    border: 3px solid var(--gray-200);
    border-top-color: var(--primary);
    border-radius: 50%;
    animation: pagination-spin 0.8s linear infinite;
    z-index: 10;
}

@keyframes pagination-spin {
    to { transform: translate(-50%, -50%) rotate(360deg); }
}

/* Table Fade Transition */
#transactionsTableContainer {
    transition: opacity 0.3s ease;
    min-height: 400px;
}

#transactionsTableContainer.loading {
    opacity: 0.6;
    pointer-events: none;
}

/* Navigation Buttons (Previous/Next) */
.pagination-nav-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border: 1px solid var(--gray-300);
    border-radius: var(--radius);
    background: white;
    color: var(--gray-700);
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s ease;
    min-width: 90px;
    justify-content: center;
}

.pagination-nav-btn:hover:not(.disabled) {
    background: var(--gray-50);
    border-color: var(--primary);
    color: var(--primary);
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.pagination-nav-btn:active:not(.disabled) {
    transform: translateY(0);
    box-shadow: none;
}

.pagination-nav-btn.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
    background: var(--gray-100);
}

.pagination-nav-btn svg {
    width: 18px;
    height: 18px;
    transition: transform 0.2s ease;
}

.pagination-nav-btn:hover svg {
    transform: scale(1.1);
}

/* Center Group (Arrow + Numbers + Arrow) */
.pagination-center-group {
    display: flex;
    align-items: center;
    gap: 5px;
    background: var(--gray-50);
    padding: 4px 8px;
    border-radius: 30px;
    border: 1px solid var(--gray-200);
    transition: all 0.2s ease;
}

.pagination-center-group:hover {
    border-color: var(--primary);
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

/* Pagination Arrows (< and >) */
.pagination-arrow {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border: 1px solid var(--gray-300);
    border-radius: 50%;
    background: white;
    color: var(--gray-700);
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.pagination-arrow:hover:not(.disabled) {
    background: var(--gray-50);
    border-color: var(--primary);
    color: var(--primary);
    transform: scale(1.05);
}

.pagination-arrow:active:not(.disabled) {
    transform: scale(0.95);
}

.pagination-arrow.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
    background: var(--gray-100);
}

.pagination-arrow svg {
    width: 16px;
    height: 16px;
    transition: transform 0.2s ease;
}

.pagination-arrow:hover svg {
    transform: scale(1.1);
}

/* Page Numbers Container */
.pagination-numbers {
    display: flex;
    align-items: center;
    gap: 3px;
    flex-wrap: wrap;
    justify-content: center;
}

/* Individual Page Number */
.page-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 32px;
    padding: 0 4px;
    border: 1px solid transparent;
    border-radius: 6px;
    background: transparent;
    color: var(--gray-700);
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.page-number:hover:not(.active) {
    background: var(--gray-200);
    border-color: var(--gray-300);
    transform: scale(1.05);
}

.page-number:active:not(.active) {
    transform: scale(0.95);
}

.page-number.active {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
    cursor: default;
    pointer-events: none;
    animation: page-active-pop 0.3s ease;
}

@keyframes page-active-pop {
    0% { transform: scale(0.9); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

/* Dots for skipped pages */
.page-dots {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 24px;
    height: 32px;
    color: var(--gray-500);
    font-size: 0.875rem;
    font-weight: 500;
    animation: dots-pulse 1.5s infinite;
}

@keyframes dots-pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

/* ===== ITEMS PER PAGE SELECTOR ===== */
.items-per-page {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px dashed var(--gray-300);
    font-size: 0.875rem;
    color: var(--gray-600);
    justify-content: flex-end;
    transition: opacity 0.3s ease;
}

.items-per-page select {
    padding: 4px 8px;
    border: 1px solid var(--gray-300);
    border-radius: 4px;
    background: white;
    color: var(--gray-700);
    font-size: 0.875rem;
    cursor: pointer;
    outline: none;
    transition: all 0.2s ease;
}

.items-per-page select:hover {
    border-color: var(--primary);
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.items-per-page select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
}

/* ===== RECENT TRANSACTIONS TABLE STYLES ===== */
.card.full-width .table-responsive {
    overflow: visible !important;
    max-height: none !important;
    border: none;
    margin-bottom: 20px;
    flex: 1;
}

.card.full-width .table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.875rem;
}

.card.full-width .table th {
    position: sticky;
    top: 0;
    background: var(--gray-100);
    z-index: 10;
    padding: 12px 16px;
    font-weight: 600;
    color: var(--gray-700);
    border-bottom: 2px solid var(--gray-300);
}

.card.full-width .table td {
    padding: 12px 16px;
    border-bottom: 1px solid var(--gray-200);
    vertical-align: middle;
}

.card.full-width .table tbody tr {
    height: 60px;
}

.card.full-width .table tbody tr:last-child td {
    border-bottom: none;
}

/* Column width optimization for 7 columns */
.card.full-width .table th:nth-child(1) { width: 60px; }   /* # */
.card.full-width .table th:nth-child(2) { width: 90px; }   /* Queue # */
.card.full-width .table th:nth-child(3) { width: 200px; }  /* Client */
.card.full-width .table th:nth-child(4) { width: 150px; }  /* Service */
.card.full-width .table th:nth-child(5) { width: 150px; }  /* Served Time */
.card.full-width .table th:nth-child(6) { width: 100px; }  /* Window */
.card.full-width .table th:nth-child(7) { width: 100px; }  /* Status */

/* Row number styling */
.row-number {
    display: inline-block;
    width: 30px;
    height: 30px;
    line-height: 30px;
    text-align: center;
    background: var(--gray-100);
    border-radius: 50%;
    font-weight: 600;
    color: var(--gray-700);
    font-size: 0.8125rem;
}

/* Queue number styling */
.card.full-width .queue-number.small {
    display: inline-block;
    padding: 4px 8px;
    background: var(--gray-100);
    border-radius: 4px;
    font-weight: 600;
    color: var(--gray-700);
    font-size: 0.8125rem;
}

/* Client name styling */
.card.full-width .client-name {
    font-weight: 500;
    color: var(--gray-800);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 180px;
}

.card.full-width .client-name small {
    display: block;
    font-size: 0.75rem;
    color: var(--gray-500);
    margin-top: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Window indicator */
.card.full-width .window-indicator {
    display: inline-block;
    padding: 4px 8px;
    background: #e0f2fe;
    color: #0369a1;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 500;
}

/* Status badge */
.card.full-width .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
}

.card.full-width .status-badge.status-completed {
    background: #ecfdf5;
    color: #065f46;
}

.card.full-width .status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: currentColor;
}

/* Empty state */
.card.full-width .empty-state {
    text-align: center;
    padding: 60px 20px !important;
    color: var(--gray-500);
}

.card.full-width .empty-state svg {
    width: 48px;
    height: 48px;
    margin: 0 auto 16px;
    color: var(--gray-300);
}

.card.full-width .empty-state p {
    font-size: 1rem;
    color: var(--gray-600);
}

/* Success Animation for Page Change */
@keyframes page-change-success {
    0% { background-color: rgba(37, 99, 235, 0); }
    50% { background-color: rgba(37, 99, 235, 0.1); }
    100% { background-color: rgba(37, 99, 235, 0); }
}

.page-change-success {
    animation: page-change-success 0.5s ease;
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 1200px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .card.full-width .table th,
    .card.full-width .table td {
        padding: 10px 12px;
    }
    
    .card.full-width .table th:nth-child(3) { width: 160px; }
    .card.full-width .table th:nth-child(4) { width: 130px; }
    .card.full-width .table th:nth-child(5) { width: 130px; }
}

@media (max-width: 1024px) {
    .dashboard-grid {
        grid-template-columns: 1fr;
    }
    
    .dashboard-grid .card:first-child {
        width: 100%;
        position: static;
    }
    
    .main-container {
        padding: 1.5rem;
    }
}

@media (max-width: 992px) {
    .card.full-width .table {
        font-size: 0.8125rem;
    }
    
    .card.full-width .table th,
    .card.full-width .table td {
        padding: 8px 10px;
    }
    
    .card.full-width .table th:nth-child(3) { width: 140px; }
    .card.full-width .table th:nth-child(4) { width: 120px; }
    .card.full-width .table th:nth-child(5) { width: 120px; }
    .card.full-width .client-name { max-width: 150px; }
    
    .row-number {
        width: 26px;
        height: 26px;
        line-height: 26px;
        font-size: 0.75rem;
    }
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .card-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .card-header h3 {
        white-space: normal;
    }
    
    .stat-card {
        padding: 1.25rem;
    }
    
    .stat-value {
        font-size: 1.5rem;
    }
    
    .enhanced-pagination {
        flex-direction: column;
        align-items: stretch;
    }
    
    .pagination-nav-btn {
        width: 100%;
        max-width: none;
    }
    
    .pagination-center-group {
        order: -1;
        margin-bottom: 10px;
        width: 100%;
        justify-content: center;
    }
    
    .items-per-page {
        justify-content: center;
    }
    
    .dashboard-grid .card:last-child .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.5rem;
    }
    
    .dashboard-grid .card:last-child .stat-value {
        font-size: 1rem;
    }
    
    .dashboard-grid .card:last-child .table-responsive {
        max-height: 350px;
    }
}

@media (max-width: 640px) {
    .main-container {
        padding: 1rem;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .btn-action {
        padding: 0.25rem 0.5rem;
        font-size: 0.6875rem;
    }
    
    .btn-action svg {
        width: 0.75rem;
        height: 0.75rem;
    }
    
    .status-badge {
        padding: 0.1875rem 0.5rem;
        font-size: 0.6875rem;
    }
}

@media (max-width: 480px) {
    .page-number {
        min-width: 28px;
        height: 28px;
        font-size: 0.75rem;
    }
    
    .pagination-arrow {
        width: 28px;
        height: 28px;
    }
    
    .pagination-arrow svg {
        width: 14px;
        height: 14px;
    }
    
    .page-dots {
        min-width: 20px;
        height: 28px;
    }
    
    .dashboard-grid .card:last-child .stats-grid {
        grid-template-columns: 1fr;
    }
}

/* ===== PRINT STYLES ===== */
@media print {
    .btn,
    .btn-action,
    .search-box,
    .alert-close,
    .loading-modal,
    .enhanced-pagination,
    .items-per-page {
        display: none !important;
    }
    
    .card {
        box-shadow: none;
        border: 1px solid var(--gray-200);
        break-inside: avoid;
        page-break-inside: avoid;
    }
    
    .dashboard-grid .card:first-child {
        position: static;
    }
    
    .card.full-width .table-responsive {
        overflow: visible !important;
    }
}
</style>

    <script>
        let html5QrcodeScanner = null;
        let isScanning = false;
        let currentSearchTerm = '';
        let isLoading = false;

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

        function fetchAppointments() {
            fetch('{{ route("appointment.today") }}', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateAppointmentsTable(data.appointments);
                    updateStatistics(data.stats);
                }
            })
            .catch(error => {
                console.error('Error fetching appointments:', error);
            });
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

// Smooth AJAX Pagination - FIXED VERSION
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
            document.querySelectorAll('.pagination-nav-btn:not(.disabled), .pagination-arrow:not(.disabled), .page-number:not(.active)').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (!isLoading) {
                        loadPage(this.href);
                    }
                });
            });
        }

        // Handle items per page change
        function handlePerPageChange() {
            const perPage = document.getElementById('perPageSelect').value;
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', perPage);
            url.searchParams.set('page', 1);
            
            loadPage(url.toString());
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

            // Handle items per page change
            document.getElementById('perPageSelect').addEventListener('change', handlePerPageChange);

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

            startAutoRefresh();
        });

        let refreshInterval;

        function startAutoRefresh() {
            refreshInterval = setInterval(function() {
                fetchAppointments();
            }, 10000);

            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    fetchAppointments();
                }
            });
        }

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