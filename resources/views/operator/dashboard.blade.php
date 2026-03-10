<x-header title="Operator Dashboard" />

<!-- Main Content -->
<main class="main-content">
    <!-- Message Container -->
    <div class="message-container" id="messageContainer"></div>

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
                    <div class="stat-icon" style="background: linear-gradient(135deg, var(--psa-blue), var(--psa-red))">
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
        
        {{-- Service Type Tabs --}}
        <div class="service-tabs">
            <button class="service-tab active" data-service="all">All Services</button>
            <button class="service-tab" data-service="NID Registration">NID Registration</button>
            <button class="service-tab" data-service="Status Inquiry">Status Inquiry</button>
            <button class="service-tab" data-service="NID Updating">NID Updating</button>
        </div>

        <div class="card-body">
            {{-- Search and Selection Bar --}}
            <div class="search-and-selection-bar">
                
                {{-- Selection Controls for All Services --}}
                <div class="selection-controls" id="selection-controls-all">
                    <div class="selection-info">
                        <label class="select-all-container">
                            <input type="checkbox" class="select-all-checkbox" data-table="all">
                            <span>Select All</span>
                        </label>
                        <span class="selected-count" id="selected-count-all">0 selected</span>
                    </div>
                    <div class="bulk-actions" style="display: none;" id="bulk-actions-all">
                        <button type="button" class="btn-status bulk-complete" data-table="all" data-status="completed">
                            <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Complete
                        </button>
                        <button type="button" class="btn-status bulk-no-show" data-table="all" data-status="no_show">
                            <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                            No Show
                        </button>
                        <button type="button" class="btn-status bulk-cancel" data-table="all" data-status="cancelled">
                            <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            Cancel
                        </button>
                    </div>
                </div>

                {{-- Selection Controls for NID Registration --}}
                <div class="selection-controls" id="selection-controls-nid-registration" style="display: none;">
                    <div class="selection-info">
                        <label class="select-all-container">
                            <input type="checkbox" class="select-all-checkbox" data-table="nid-registration">
                            <span>Select All</span>
                        </label>
                        <span class="selected-count" id="selected-count-nid-registration">0 selected</span>
                    </div>
                    <div class="bulk-actions" style="display: none;" id="bulk-actions-nid-registration">
                        <button type="button" class="btn-status bulk-complete" data-table="nid-registration" data-status="completed">
                            <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Complete
                        </button>
                        <button type="button" class="btn-status bulk-no-show" data-table="nid-registration" data-status="no_show">
                            <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                            No Show
                        </button>
                        <button type="button" class="btn-status bulk-cancel" data-table="nid-registration" data-status="cancelled">
                            <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            Cancel
                        </button>
                    </div>
                </div>

                {{-- Selection Controls for Status Inquiry --}}
                <div class="selection-controls" id="selection-controls-status-inquiry" style="display: none;">
                    <div class="selection-info">
                        <label class="select-all-container">
                            <input type="checkbox" class="select-all-checkbox" data-table="status-inquiry">
                            <span>Select All</span>
                        </label>
                        <span class="selected-count" id="selected-count-status-inquiry">0 selected</span>
                    </div>
                    <div class="bulk-actions" style="display: none;" id="bulk-actions-status-inquiry">
                        <button type="button" class="btn-status bulk-complete" data-table="status-inquiry" data-status="completed">
                            <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Complete
                        </button>
                        <button type="button" class="btn-status bulk-no-show" data-table="status-inquiry" data-status="no_show">
                            <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                            No Show
                        </button>
                        <button type="button" class="btn-status bulk-cancel" data-table="status-inquiry" data-status="cancelled">
                            <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            Cancel
                        </button>
                    </div>
                </div>

                {{-- Selection Controls for NID Updating --}}
                <div class="selection-controls" id="selection-controls-nid-updating" style="display: none;">
                    <div class="selection-info">
                        <label class="select-all-container">
                            <input type="checkbox" class="select-all-checkbox" data-table="nid-updating">
                            <span>Select All</span>
                        </label>
                        <span class="selected-count" id="selected-count-nid-updating">0 selected</span>
                    </div>
                    <div class="bulk-actions" style="display: none;" id="bulk-actions-nid-updating">
                        <button type="button" class="btn-status bulk-complete" data-table="nid-updating" data-status="completed">
                            <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Complete
                        </button>
                        <button type="button" class="btn-status bulk-no-show" data-table="nid-updating" data-status="no_show">
                            <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                            No Show
                        </button>
                        <button type="button" class="btn-status bulk-cancel" data-table="nid-updating" data-status="cancelled">
                            <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            Cancel
                        </button>
                    </div>
                </div>
                
                <div class="search-box-wrapper">
                    <div class="search-box">
                        <svg class="search-icon" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd" />
                        </svg>
                        <input type="text" id="searchAppointments" placeholder="Search by name or TRN...">
                    </div>
                </div>
            </div>

            {{-- All Services Table --}}
            <div class="service-table-container" id="table-all">
                @include('operator.partials.appointments-table', [
                    'appointments' => $appointments, 
                    'showAll' => true,
                    'tableId' => 'all'
                ])
            </div>

            {{-- NID Registration Table --}}
            <div class="service-table-container" id="table-nid-registration" style="display: none;">
                @include('operator.partials.appointments-table', [
                    'appointments' => $nidRegistrationAppointments, 
                    'serviceType' => 'NID Registration',
                    'tableId' => 'nid-registration'
                ])
            </div>

            {{-- Status Inquiry Table --}}
            <div class="service-table-container" id="table-status-inquiry" style="display: none;">
                @include('operator.partials.appointments-table', [
                    'appointments' => $statusInquiryAppointments, 
                    'serviceType' => 'Status Inquiry',
                    'tableId' => 'status-inquiry'
                ])
            </div>

            {{-- NID Updating Table --}}
            <div class="service-table-container" id="table-nid-updating" style="display: none;">
                @include('operator.partials.appointments-table', [
                    'appointments' => $nidUpdatingAppointments, 
                    'serviceType' => 'NID Updating',
                    'tableId' => 'nid-updating'
                ])
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
            <div class="card-actions" style="display: flex; gap: 10px; align-items: center;">
                <span class="badge" id="showingInfo">Showing
                    {{ $completedTransactions->firstItem() }}-{{ $completedTransactions->lastItem() }} of
                    {{ $completedTransactions->total() }}</span>

                {{-- Service Filter for Recent Transactions --}}
                <!-- <select id="transactionServiceFilter" class="service-filter">
                    <option value="all">All Services</option>
                    <option value="NID Registration">NID Registration</option>
                    <option value="Status Inquiry">Status Inquiry</option>
                    <option value="NID Updating">NID Updating</option>
                </select> -->

                {{-- EXPORT PDF BUTTON --}}
                <button type="button" class="btn btn-danger" id="operatorExportPdfBtn"
                    style="padding: 6px 12px; background-color: #dc2626; color: white; border: none; border-radius: 4px; cursor: pointer; display: inline-flex; align-items: center; gap: 5px;">
                    <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                        <path fill-rule="evenodd"
                            d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                            clip-rule="evenodd" />
                    </svg>
                    Export PDF
                </button>
                <button type="button" class="btn btn-success" id="operatorExportExcelBtn"
                    style="padding: 6px 12px; background-color: #059669; color: white; border: none; border-radius: 4px; cursor: pointer; display: inline-flex; align-items: center; gap: 5px;">
                    <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                        <path fill-rule="evenodd"
                            d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 2v2h2V6H6zm6 0v2h2V6h-2zm-6 4v2h2v-2H6zm6 0v2h2v-2h-2zm-6 4v2h2v-2H6zm6 0v2h2v-2h-2z"
                            clip-rule="evenodd" />
                    </svg>
                    Export Excel
                </button>
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

                                // Format name properly with FULL middle name
                                $fullName = $transaction->lname . ', ' . $transaction->fname;
                                if ($transaction->mname && trim($transaction->mname) !== '') {
                                    $fullName .= ' ' . $transaction->mname;
                                }
                                if ($transaction->suffix && trim($transaction->suffix) !== '') {
                                    $fullName .= ' ' . $transaction->suffix;
                                }
                            @endphp
                            <tr data-service="{{ $serviceDisplay }}">
                                <td><span class="row-number">{{ $rowNumber }}</span></td>
                                <td><span class="queue-number small">{{ $transaction->q_id }}</span></td>
                                <td>
                                    <div class="client-name">
                                        {{ $fullName }}
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
                @include('operator.partials.pagination-links', [
                    'completedTransactions' => $completedTransactions,
                ])
            </div>
        </div>
    </div>
</main>

<style>
/* No-Show Serve Button - Same as regular Serve button */
.btn-action.serve-btn[data-status="no_show"] {
    background: var(--psa-blue);
    color: white;
    min-width: 80px;
    justify-content: center;
}

.btn-action.serve-btn[data-status="no_show"]:hover {
    background: #1d4ed8;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
}

.btn-action.serve-btn[data-status="no_show"]:disabled {
    background: #d1d5db;
    color: #6b7280;
    opacity: 0.6;
    transform: none;
    box-shadow: none;
    cursor: not-allowed;
}

/* Disabled serve button */
.btn-action.serve-btn:disabled {
    background: #d1d5db;
    color: #6b7280;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
    opacity: 0.6;
    pointer-events: none;
}

/* Tooltip for disabled button */
.btn-action.serve-btn:disabled:hover::after {
    content: attr(title);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    background: #1f2937;
    color: white;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    white-space: nowrap;
    z-index: 1000;
    margin-bottom: 8px;
    pointer-events: none;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.btn-action.serve-btn:disabled:hover::before {
    content: '';
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    border-width: 5px;
    border-style: solid;
    border-color: #1f2937 transparent transparent transparent;
    margin-bottom: -2px;
    pointer-events: none;
}

/* Action Button Group */
.action-button-group {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

.btn-action {
    padding: 8px 14px;
    border: none;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.btn-action.primary {
    background: var(--psa-blue);
    color: white;
    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
}

.btn-action.primary:hover {
    background: #1d4ed8;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
}

.btn-action.secondary {
    background: #f3f4f6;
    color: #4b5563;
    border: 1px solid #e5e7eb;
}

.btn-action.secondary:hover {
    background: #e5e7eb;
    color: #1f2937;
}

.btn-action.complete-btn {
    background: #10b981;
    color: white;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
}

.btn-action.complete-btn:hover {
    background: #059669;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
}

.btn-action.no-show-btn {
    background: #f3f4f6;
    color: #6b7280;
    border: 1px solid #d1d5db;
}

.btn-action.no-show-btn:hover {
    background: #fee2e2;
    color: #dc2626;
    border-color: #fecaca;
}

.btn-action.cancel-btn {
    background: #f3f4f6;
    color: #6b7280;
    border: 1px solid #d1d5db;
}

.btn-action.cancel-btn:hover {
    background: #fee2e2;
    color: #b91c1c;
    border-color: #fecaca;
}

.btn-action.serve-btn {
    background: var(--psa-blue);
    color: white;
    min-width: 80px;
    justify-content: center;
}

.btn-action.serve-btn:hover {
    background: #1d4ed8;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
}

.status-text {
    color: #6b7280;
    font-size: 13px;
    font-weight: 500;
    padding: 0 8px;
}

/* Disabled checkbox */
.table input[type="checkbox"]:disabled {
    opacity: 0.3;
    cursor: not-allowed;
}

/* Status Badge Styles */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 600;
    text-transform: capitalize;
    letter-spacing: 0.3px;
    min-width: 90px;
    justify-content: center;
}

.status-badge.pending {
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #fbbf24;
}

.status-badge.serving {
    background: #dbeafe;
    color: #1e40af;
    border: 1px solid #3b82f6;
    animation: pulse 2s infinite;
}

.status-badge.completed {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #10b981;
}

.status-badge.cancelled {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #ef4444;
}

.status-badge.no-show {
    background: #f3f4f6;
    color: #4b5563;
    border: 1px solid #9ca3af;
}

.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: currentColor;
}

/* Pulse animation for serving status */
@keyframes pulse {
    0% {
        opacity: 1;
    }
    50% {
        opacity: 0.7;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
    }
    100% {
        opacity: 1;
    }
}

.serving {
    animation: pulse 2s infinite;
}

/* Disabled action button */
.btn-action.disabled {
    background: #f3f4f6;
    color: #9ca3af;
    border-color: #e5e7eb;
    pointer-events: none;
}

/* Selection styles */
.table tr.selected {
    background-color: rgba(37, 99, 235, 0.05);
    transition: background-color 0.2s ease;
}

.table tr.selected:hover {
    background-color: rgba(37, 99, 235, 0.1);
}

/* Checkbox styles */
.table input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: var(--psa-blue);
}

/* Bulk actions animation */
.bulk-actions {
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-10px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Selected count badge */
.selected-count-badge {
    display: inline-block;
    min-width: 20px;
    height: 20px;
    background: white;
    color: var(--psa-blue);
    border-radius: 10px;
    font-size: 12px;
    font-weight: 600;
    line-height: 20px;
    text-align: center;
    margin-left: 5px;
    padding: 0 5px;
}

/* Service Tabs Styles */
.service-tabs {
    display: flex;
    gap: 10px;
    padding: 0 24px 20px 24px;
    border-bottom: 1px solid var(--gray-200);
    flex-wrap: wrap;
}

.service-tab {
    padding: 8px 16px;
    border: 2px solid var(--gray-200);
    border-radius: 30px;
    background: white;
    color: var(--gray-600);
    font-weight: 500;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.service-tab:hover {
    border-color: var(--psa-blue);
    color: var(--psa-blue);
    background: var(--psa-blue-light);
}

.service-tab.active {
    background: linear-gradient(135deg, var(--psa-blue), var(--psa-red));
    border-color: transparent;
    color: white;
}

.service-filter {
    padding: 6px 12px;
    border: 2px solid var(--gray-200);
    border-radius: 30px;
    font-size: 13px;
    color: var(--gray-700);
    background: white;
    cursor: pointer;
}

.service-filter:focus {
    outline: none;
    border-color: var(--psa-blue);
}

/* Search and Selection Bar */
.search-and-selection-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 20px;
    padding: 0 10px;
    flex-wrap: wrap;
}

.selection-controls {
    display: flex;
    align-items: center;
    gap: 15px;
    background: white;
    padding: 5px 15px;
    border-radius: 30px;
    border: 2px solid var(--gray-200);
    flex: 0 0 auto;
}

.selection-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.select-all-container {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    padding: 5px 10px;
    background: var(--gray-100);
    border-radius: 20px;
    font-size: 13px;
    color: var(--gray-700);
    white-space: nowrap;
}

.select-all-container input[type="checkbox"] {
    width: 16px;
    height: 16px;
    cursor: pointer;
}

.selected-count {
    font-size: 13px;
    color: var(--gray-600);
    font-weight: 500;
    white-space: nowrap;
}

.bulk-actions {
    display: flex;
    gap: 10px;
}

/* Bulk status buttons */
.btn-status {
    padding: 6px 12px;
    border: none;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.bulk-complete {
    background: #10b981;
    color: white;
}

.bulk-complete:hover {
    background: #059669;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(16, 185, 129, 0.3);
}

.bulk-no-show {
    background: #6b7280;
    color: white;
}

.bulk-no-show:hover {
    background: #4b5563;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(107, 114, 128, 0.3);
}

.bulk-cancel {
    background: #ef4444;
    color: white;
}

.bulk-cancel:hover {
    background: #dc2626;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3);
}

.btn-status:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.search-box-wrapper {
    flex: 0 0 350px;
    margin-left: auto;
}

.search-box {
    position: relative;
    width: 100%;
}

.search-box input {
    width: 100%;
    padding: 10px 16px 10px 40px;
    border: 2px solid var(--gray-200);
    border-radius: 30px;
    font-size: 14px;
    transition: all 0.2s ease;
}

.search-box input:focus {
    outline: none;
    border-color: var(--psa-blue);
    box-shadow: 0 0 0 4px var(--psa-blue-light);
}

.search-box .search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    width: 16px;
    height: 16px;
    color: var(--gray-400);
}

/* Responsive */
@media (max-width: 768px) {
    .search-and-selection-bar {
        flex-direction: column;
        align-items: stretch;
    }
    
    .selection-controls {
        width: 100%;
        justify-content: space-between;
    }
    
    .search-box-wrapper {
        flex: 1;
        width: 100%;
        margin-left: 0;
    }
}
</style>

<script>
// Configuration - Ensure windowNum is a string
const windowNum = String({{ Js::from(session('window_num') ?? ($windowNum ?? '1')) }});
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

// DOM Elements
const messageContainer = document.getElementById('messageContainer');
const searchInput = document.getElementById('searchAppointments');
const showingInfo = document.getElementById('showingInfo');
const transactionsTableContainer = document.getElementById('transactionsTableContainer');
const paginationContainer = document.getElementById('paginationContainer');

// Store current search term and active service
let currentSearchTerm = '';
let activeService = 'all';
let isLoading = false;
let refreshInterval;
// Global variable to track if currently serving
let isCurrentlyServing = false;
// Processing flags to prevent multiple popups
let isProcessing = false;
let isBulkProcessing = false;

// ========== REMEMBER ACTIVE TAB ON PAGE REFRESH ==========
const savedService = localStorage.getItem('activeServiceTab');
if (savedService) {
    activeService = savedService;
}

// Check if there's any serving appointment
function checkServingStatus() {
    const servingRow = document.querySelector('.status-badge.serving');
    isCurrentlyServing = !!servingRow;
    
    document.querySelectorAll('.serve-btn').forEach(btn => {
        const row = btn.closest('tr');
        const rowStatus = row?.querySelector('.status-badge')?.textContent.trim().toLowerCase();
        
        if (rowStatus?.includes('serving')) {
            return;
        }
        
        if (isCurrentlyServing) {
            btn.disabled = true;
            btn.title = 'Cannot serve while another appointment is being served';
        } else {
            btn.disabled = false;
            btn.title = btn.getAttribute('data-status') === 'no_show' 
                ? 'Call this no-show appointment to your window' 
                : 'Call this appointment to your window';
        }
    });
}

// Handle Serve Button Click
function handleServeClick(e) {
    const button = e.currentTarget;
    
    if (isCurrentlyServing) {
        Swal.fire({
            title: 'Cannot Serve',
            text: 'You are currently serving another appointment. Please complete or cancel it first.',
            icon: 'warning',
            confirmButtonColor: '#2563eb'
        });
        return;
    }
    
    const n_id = button.getAttribute('data-id');
    const name = button.getAttribute('data-name');
    const originalStatus = button.getAttribute('data-status');
    const row = button.closest('tr');

    Swal.fire({
        title: originalStatus === 'no_show' ? 'Serve No-Show Appointment?' : 'Start Serving?',
        html: `Call <strong>${name}</strong> to window <strong>#${windowNum}</strong>?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#dc2626',
        confirmButtonText: 'Yes, Start Serving'
    }).then((result) => {
        if (!result.isConfirmed) return;

        button.disabled = true;
        const originalHtml = button.innerHTML;
        button.innerHTML = '<span class="spinner"></span> Processing...';

        fetch('{{ route('operator.update-window') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                n_id: n_id,
                window_num: windowNum,
                status: 'serving'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateRowForServing(row, n_id, name);
                isCurrentlyServing = true;
                
                document.querySelectorAll('.serve-btn').forEach(btn => {
                    if (btn !== button) {
                        btn.disabled = true;
                        btn.title = 'Cannot serve while another appointment is being served';
                    }
                });
                
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
                
                Toast.fire({
                    icon: 'success',
                    title: `Now serving ${name}`
                });
                
                updatePendingCount();
                fetchDashboardData();
            } else {
                showMessage('Failed to update.', 'error');
                button.disabled = false;
                button.innerHTML = originalHtml;
                checkServingStatus();
            }
        })
        .catch(err => {
            console.error('Error:', err);
            showMessage('Error connecting to server.', 'error');
            button.disabled = false;
            button.innerHTML = originalHtml;
            checkServingStatus();
        });
    });
}

// Handle Complete Button Click
function handleCompleteClick(e) {
    const button = e.currentTarget;
    const n_id = button.getAttribute('data-id');
    const name = button.getAttribute('data-name');
    const row = button.closest('tr');

    Swal.fire({
        title: 'Complete Appointment?',
        text: `Mark ${name} as completed?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#dc2626',
        confirmButtonText: 'Yes, Complete'
    }).then((result) => {
        if (result.isConfirmed) {
            updateAppointmentStatus(n_id, 'completed', row, 'completed');
        }
    });
}

// Handle No Show Button Click
function handleNoShowClick(e) {
    const button = e.currentTarget;
    const n_id = button.getAttribute('data-id');
    const name = button.getAttribute('data-name');
    const row = button.closest('tr');

    Swal.fire({
        title: 'Mark as No Show?',
        text: `Mark ${name} as no show?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#6b7280',
        cancelButtonColor: '#dc2626',
        confirmButtonText: 'Yes, No Show'
    }).then((result) => {
        if (result.isConfirmed) {
            updateAppointmentStatus(n_id, 'no_show', row, 'no-show');
        }
    });
}

// Handle Cancel Button Click
function handleCancelClick(e) {
    const button = e.currentTarget;
    const n_id = button.getAttribute('data-id');
    const name = button.getAttribute('data-name');
    const row = button.closest('tr');

    Swal.fire({
        title: 'Cancel Appointment?',
        text: `Cancel appointment for ${name}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            updateAppointmentStatus(n_id, 'cancelled', row, 'cancelled');
        }
    });
}

// Update appointment status - for individual row actions
function updateAppointmentStatus(n_id, status, row, statusClass) {
    if (isProcessing) return;
    isProcessing = true;
    
    const buttons = row.querySelectorAll('button');
    buttons.forEach(btn => btn.disabled = true);
    
    Swal.fire({
        title: 'Processing...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    fetch('{{ route('operator.update-window') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            n_id: n_id,
            window_num: windowNum,
            status: status
        })
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();
        isProcessing = false;
        
        if (data.success) {
            updateRowAfterStatusChange(row, status, statusClass, n_id);
            
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
            
            Toast.fire({
                icon: 'success',
                title: `Marked as ${status.replace('_', ' ')}`
            });

            fetchDashboardData();
            fetchRecentTransactionsPage(1);
        } else {
            showMessage('Failed to update.', 'error');
            buttons.forEach(btn => btn.disabled = false);
        }
    })
    .catch(err => {
        console.error('Error:', err);
        Swal.close();
        isProcessing = false;
        showMessage('Error connecting to server.', 'error');
        buttons.forEach(btn => btn.disabled = false);
    });
}

// Update row after status change - for individual row actions
function updateRowAfterStatusChange(row, status, statusClass, n_id) {
    const statusBadge = row.querySelector('.status-badge');
    if (statusBadge) {
        statusBadge.className = `status-badge ${statusClass}`;
        statusBadge.innerHTML = `<span class="status-dot"></span> ${status.replace('_', ' ')}`;
    }
    
    const name = row.querySelector('.complete-btn, .no-show-btn, .cancel-btn')?.getAttribute('data-name');
    
    const actionCell = row.querySelector('td:last-child');
    if (actionCell) {
        if (status === 'no_show') {
            actionCell.innerHTML = `
                <button class="btn-action serve-btn" 
                        data-id="${n_id}"
                        data-name="${name}"
                        data-status="no_show">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Serve Again
                </button>
            `;
        } else if (status === 'completed' || status === 'cancelled') {
            actionCell.innerHTML = `<span class="status-text">${status.replace('_', ' ')}</span>`;
        }
    }
    
    const checkbox = row.querySelector('.row-checkbox');
    if (checkbox) {
        checkbox.disabled = true;
        checkbox.checked = false;
    }
    
    row.classList.remove('selected');
    
    if (status === 'completed' || status === 'no_show' || status === 'cancelled') {
        isCurrentlyServing = false;
        
        document.querySelectorAll('.serve-btn').forEach(btn => {
            btn.disabled = false;
            btn.title = btn.getAttribute('data-status') === 'no_show' 
                ? 'Call this no-show appointment to your window' 
                : 'Call this appointment to your window';
        });
    }
    
    attachServeButtonListeners();
}

// Update row for serving status
function updateRowForServing(row, n_id, name) {
    const statusBadge = row.querySelector('.status-badge');
    if (statusBadge) {
        statusBadge.className = 'status-badge serving';
        statusBadge.innerHTML = '<span class="status-dot"></span> Serving';
    }
    
    const actionCell = row.querySelector('td:last-child');
    if (actionCell) {
        actionCell.innerHTML = `
            <div class="action-button-group">
                <button class="btn-action complete-btn" 
                        data-id="${n_id}" 
                        data-name="${name}">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Done
                </button>
                <button class="btn-action secondary no-show-btn" 
                        data-id="${n_id}" 
                        data-name="${name}">
                    No Show
                </button>
                <button class="btn-action secondary cancel-btn" 
                        data-id="${n_id}" 
                        data-name="${name}">
                    Cancel
                </button>
            </div>
        `;
    }
    
    attachActionButtonListeners(row);
    checkServingStatus();
}

// Attach event listeners to action buttons
function attachActionButtonListeners(row) {
    const completeBtn = row.querySelector('.complete-btn');
    const noShowBtn = row.querySelector('.no-show-btn');
    const cancelBtn = row.querySelector('.cancel-btn');
    
    if (completeBtn) {
        completeBtn.removeEventListener('click', handleCompleteClick);
        completeBtn.addEventListener('click', handleCompleteClick);
    }
    
    if (noShowBtn) {
        noShowBtn.removeEventListener('click', handleNoShowClick);
        noShowBtn.addEventListener('click', handleNoShowClick);
    }
    
    if (cancelBtn) {
        cancelBtn.removeEventListener('click', handleCancelClick);
        cancelBtn.addEventListener('click', handleCancelClick);
    }
}

// ========== ATTACH ALL LISTENERS ==========
function attachServeButtonListeners() {
    document.querySelectorAll('.serve-btn').forEach(button => {
        button.removeEventListener('click', handleServeClick);
        button.addEventListener('click', handleServeClick);
    });
    
    document.querySelectorAll('.complete-btn').forEach(button => {
        button.removeEventListener('click', handleCompleteClick);
        button.addEventListener('click', handleCompleteClick);
    });
    
    document.querySelectorAll('.no-show-btn').forEach(button => {
        button.removeEventListener('click', handleNoShowClick);
        button.addEventListener('click', handleNoShowClick);
    });
    
    document.querySelectorAll('.cancel-btn').forEach(button => {
        button.removeEventListener('click', handleCancelClick);
        button.addEventListener('click', handleCancelClick);
    });
    
    checkServingStatus();
    initializeAllTableCheckboxes();
}

// Show Message
function showMessage(text, type = 'success') {
    if (!messageContainer) return;

    const messageDiv = document.createElement('div');
    messageDiv.className = `message message-${type}`;
    messageDiv.innerHTML = `
        <svg class="message-icon" viewBox="0 0 20 20" fill="currentColor">
            ${type === 'success'
                ? '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>'
                : '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>'
            }
        </svg>
        <span>${text}</span>
    `;

    messageContainer.innerHTML = '';
    messageContainer.appendChild(messageDiv);

    setTimeout(() => messageDiv.remove(), 5000);
}

// Service Tab Switching with localStorage
document.querySelectorAll('.service-tab').forEach(tab => {
    if (tab.dataset.service === activeService) {
        tab.classList.add('active');
    } else {
        tab.classList.remove('active');
    }
    
    tab.addEventListener('click', function() {
        document.querySelectorAll('.service-tab').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        
        activeService = this.dataset.service;
        localStorage.setItem('activeServiceTab', activeService);
        
        document.querySelectorAll('.service-table-container').forEach(table => {
            table.style.display = 'none';
        });
        
        document.querySelectorAll('[id^="selection-controls-"]').forEach(controls => {
            controls.style.display = 'none';
        });
        
        if (activeService === 'all') {
            document.getElementById('table-all').style.display = 'block';
            document.getElementById('selection-controls-all').style.display = 'flex';
        } else {
            const tableId = 'table-' + activeService.toLowerCase().replace(/\s+/g, '-');
            const controlsId = 'selection-controls-' + activeService.toLowerCase().replace(/\s+/g, '-');
            document.getElementById(tableId).style.display = 'block';
            document.getElementById(controlsId).style.display = 'flex';
        }
        
        filterTableRows();
    });
});

// On page load, show the saved tab
document.addEventListener('DOMContentLoaded', function() {
    const savedTab = Array.from(document.querySelectorAll('.service-tab')).find(
        tab => tab.dataset.service === activeService
    );
    
    if (savedTab) {
        document.querySelectorAll('.service-table-container').forEach(table => {
            table.style.display = 'none';
        });
        document.querySelectorAll('[id^="selection-controls-"]').forEach(controls => {
            controls.style.display = 'none';
        });
        
        if (activeService === 'all') {
            document.getElementById('table-all').style.display = 'block';
            document.getElementById('selection-controls-all').style.display = 'flex';
        } else {
            const tableId = 'table-' + activeService.toLowerCase().replace(/\s+/g, '-');
            const controlsId = 'selection-controls-' + activeService.toLowerCase().replace(/\s+/g, '-');
            document.getElementById(tableId).style.display = 'block';
            document.getElementById(controlsId).style.display = 'flex';
        }
    }
});

// Search Functionality
if (searchInput) {
    searchInput.addEventListener('keyup', function() {
        currentSearchTerm = this.value.toLowerCase();
        filterTableRows();
    });
}

function filterTableRows() {
    let visibleTable;
    if (activeService === 'all') {
        visibleTable = document.getElementById('table-all');
    } else {
        const tableId = 'table-' + activeService.toLowerCase().replace(/\s+/g, '-');
        visibleTable = document.getElementById(tableId);
    }
    
    if (!visibleTable) return;
    
    const rows = visibleTable.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        if (row.classList.contains('empty-state')) return;
        
        const searchData = row.getAttribute('data-search') || row.textContent.toLowerCase();
        row.style.display = searchData.includes(currentSearchTerm) ? '' : 'none';
    });
}

// Helper function to update pending count
function updatePendingCount() {
    const visibleTable = document.querySelector('.service-table-container[style*="display: block"]');
    if (visibleTable) {
        const pendingCount = visibleTable.querySelectorAll('tbody tr:not(.empty-state)').length;
        document.getElementById('pendingCount').textContent = pendingCount;
    }
}

// Fetch dashboard data
function fetchDashboardData() {
    fetch('{{ route('operator.fetch-appointments') }}', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateAppointmentsTables(data);
                updateStatistics(data.stats);
            }
        })
        .catch(error => console.error('Error fetching dashboard data:', error));
}

// Update all appointments tables
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
    
    if (document.getElementById('table-nid-updating')) {
        document.getElementById('table-nid-updating').innerHTML = data.tableNidUpdating || '';
    }
    
    attachServeButtonListeners();
    initializeAllTableCheckboxes();
    
    if (currentSearchTerm) {
        filterTableRows();
    }
}

// Update statistics
function updateStatistics(stats) {
    if (!stats) return;

    if (stats.total !== undefined) document.getElementById('totalQueue').textContent = stats.total;
    if (stats.pending !== undefined) document.getElementById('pendingCount').textContent = stats.pending;
    if (stats.completed !== undefined) document.getElementById('completedCount').textContent = stats.completed;
}

// Service filter for recent transactions
document.getElementById('transactionServiceFilter')?.addEventListener('change', function() {
    const selectedService = this.value;
    const rows = document.querySelectorAll('#transactionsTableContainer tr');
    
    rows.forEach(row => {
        if (row.classList.contains('empty-state')) return;
        
        const service = row.getAttribute('data-service');
        row.style.display = (selectedService === 'all' || service === selectedService) ? '' : 'none';
    });
});

// Smooth AJAX Pagination for Recent Transactions
function loadTransactionsPage(url) {
    if (isLoading) return;
    isLoading = true;

    const tableContainer = document.getElementById('transactionsTableContainer');
    const paginationContainer = document.getElementById('paginationContainer');
    const showingInfo = document.getElementById('showingInfo');

    if (!tableContainer || !paginationContainer) {
        isLoading = false;
        return;
    }

    const separator = url.includes('?') ? '&' : '?';
    const fetchUrl = url + separator + '_=' + new Date().getTime();

    fetch(fetchUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) throw new Error(data.message || 'Unknown error occurred');

            tableContainer.style.opacity = '0';
            paginationContainer.style.opacity = '0';

            setTimeout(() => {
                if (data.table) tableContainer.innerHTML = data.table;
                if (data.pagination) paginationContainer.innerHTML = data.pagination;
                if (data.showing && showingInfo) showingInfo.textContent = data.showing;

                tableContainer.style.opacity = '1';
                paginationContainer.style.opacity = '1';

                attachPaginationListeners();
                
                const filter = document.getElementById('transactionServiceFilter');
                if (filter) filter.dispatchEvent(new Event('change'));

                isLoading = false;
            }, 150);
        })
        .catch(error => {
            console.error('Error loading page:', error);
            isLoading = false;
        });
}

// Fetch recent transactions page
function fetchRecentTransactionsPage(page = null) {
    if (isLoading) return;

    let url = '{{ route('operator.transactions-page') }}';
    const params = new URLSearchParams();

    if (page) params.append('page', page);

    const queryString = params.toString();
    if (queryString) url += '?' + queryString;
    url += (url.includes('?') ? '&' : '?') + '_=' + new Date().getTime();

    fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.table) transactionsTableContainer.innerHTML = data.table;
                if (data.pagination) paginationContainer.innerHTML = data.pagination;
                if (data.showing && showingInfo) showingInfo.textContent = data.showing;
            }
            attachPaginationListeners();
            const filter = document.getElementById('transactionServiceFilter');
            if (filter) filter.dispatchEvent(new Event('change'));
        })
        .catch(error => console.error('Error fetching recent transactions:', error));
}

function attachPaginationListeners() {
    document.querySelectorAll(
            '.pagination-nav-btn:not(.disabled), .pagination-arrow:not(.disabled), .page-number:not(.active)'
        ).forEach(link => {
            link.removeEventListener('click', handlePaginationClick);
            link.addEventListener('click', handlePaginationClick);
        });
}

function handlePaginationClick(e) {
    e.preventDefault();
    if (!isLoading) loadTransactionsPage(this.href);
}

// Export PDF with confirmation
document.getElementById('operatorExportPdfBtn')?.addEventListener('click', function(e) {
    e.preventDefault();

    Swal.fire({
        title: 'Export PDF',
        text: 'Export your completed transactions report?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#dc2626',
        confirmButtonText: 'Yes, Export!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '{{ route('operator.export.pdf') }}';
            
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
            
            Toast.fire({
                icon: 'success',
                title: 'PDF Exported Successfully'
            });
        }
    });
});

// Export Excel with confirmation
document.getElementById('operatorExportExcelBtn')?.addEventListener('click', function(e) {
    e.preventDefault();

    Swal.fire({
        title: 'Export to Excel',
        text: 'Export your completed transactions report?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#059669',
        cancelButtonColor: '#dc2626',
        confirmButtonText: 'Yes, Export!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '{{ route('operator.export.excel') }}';
            
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
            
            Toast.fire({
                icon: 'success',
                title: 'Excel Exported Successfully'
            });
        }
    });
});

// Auto-refresh dashboard every 10 seconds
refreshInterval = setInterval(function() {
    if (!document.querySelector('.serve-btn[disabled]') && !isLoading) {
        fetchDashboardData();
    }
}, 10000);

// Refresh when user returns to the tab
document.addEventListener('visibilitychange', function() {
    if (!document.hidden && !document.querySelector('.serve-btn[disabled]') && !isLoading) {
        fetchDashboardData();
    }
});

// ========== CHECKBOX SELECTION FUNCTIONALITY ==========
function initializeCheckboxSelection(tableId) {
    const tableContainer = document.getElementById(`table-${tableId}`);
    if (!tableContainer) return;
    
    const selectAllHeader = tableContainer.querySelector(`.select-all-checkbox-header[data-table="${tableId}"]`);
    const selectAllFooter = document.querySelector(`.select-all-checkbox[data-table="${tableId}"]`);
    const rowCheckboxes = tableContainer.querySelectorAll(`.row-checkbox[data-table="${tableId}"]:not([disabled])`);
    const allRowCheckboxes = tableContainer.querySelectorAll(`.row-checkbox[data-table="${tableId}"]`);
    const selectedCountSpan = document.getElementById(`selected-count-${tableId}`);
    const bulkActionsDiv = document.getElementById(`bulk-actions-${tableId}`);
    
    if (!rowCheckboxes.length) return;
    
    function updateSelection() {
        const checkedCount = Array.from(rowCheckboxes).filter(cb => cb.checked).length;
        
        if (selectedCountSpan) {
            selectedCountSpan.textContent = `${checkedCount} selected`;
        }
        
        if (bulkActionsDiv) {
            bulkActionsDiv.style.display = checkedCount > 0 ? 'flex' : 'none';
        }
        
        allRowCheckboxes.forEach(cb => {
            const row = cb.closest('tr');
            if (row) {
                if (cb.checked && !cb.disabled) {
                    row.classList.add('selected');
                } else {
                    row.classList.remove('selected');
                }
            }
        });
        
        const allChecked = rowCheckboxes.length > 0 && Array.from(rowCheckboxes).every(cb => cb.checked);
        const someChecked = Array.from(rowCheckboxes).some(cb => cb.checked);
        
        if (selectAllHeader) {
            selectAllHeader.checked = allChecked;
            selectAllHeader.indeterminate = someChecked && !allChecked;
        }
        
        if (selectAllFooter) {
            selectAllFooter.checked = allChecked;
            selectAllFooter.indeterminate = someChecked && !allChecked;
        }
    }
    
    rowCheckboxes.forEach(cb => cb.addEventListener('change', updateSelection));
    
    if (selectAllHeader) {
        selectAllHeader.addEventListener('change', function() {
            rowCheckboxes.forEach(cb => cb.checked = this.checked);
            updateSelection();
        });
    }
    
    if (selectAllFooter) {
        selectAllFooter.addEventListener('change', function() {
            rowCheckboxes.forEach(cb => cb.checked = this.checked);
            updateSelection();
        });
    }
    
    // Bulk status buttons
    if (bulkActionsDiv) {
        const completeBtn = bulkActionsDiv.querySelector('.bulk-complete');
        if (completeBtn) {
            completeBtn.addEventListener('click', function() {
                const selectedIds = Array.from(rowCheckboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.dataset.id);
                
                if (selectedIds.length === 0) return;
                
                Swal.fire({
                    title: 'Bulk Complete',
                    text: `Mark ${selectedIds.length} selected appointment(s) as completed?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#dc2626',
                    confirmButtonText: 'Yes, Complete All'
                }).then((result) => {
                    if (result.isConfirmed) {
                        bulkUpdateStatus(selectedIds, 'completed', tableId);
                    }
                });
            });
        }
        
        const noShowBtn = bulkActionsDiv.querySelector('.bulk-no-show');
        if (noShowBtn) {
            noShowBtn.addEventListener('click', function() {
                const selectedIds = Array.from(rowCheckboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.dataset.id);
                
                if (selectedIds.length === 0) return;
                
                Swal.fire({
                    title: 'Bulk No Show',
                    text: `Mark ${selectedIds.length} selected appointment(s) as no show?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#6b7280',
                    cancelButtonColor: '#dc2626',
                    confirmButtonText: 'Yes, Mark All'
                }).then((result) => {
                    if (result.isConfirmed) {
                        bulkUpdateStatus(selectedIds, 'no_show', tableId);
                    }
                });
            });
        }
        
        const cancelBtn = bulkActionsDiv.querySelector('.bulk-cancel');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', function() {
                const selectedIds = Array.from(rowCheckboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.dataset.id);
                
                if (selectedIds.length === 0) return;
                
                Swal.fire({
                    title: 'Bulk Cancel',
                    text: `Cancel ${selectedIds.length} selected appointment(s)?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, Cancel All'
                }).then((result) => {
                    if (result.isConfirmed) {
                        bulkUpdateStatus(selectedIds, 'cancelled', tableId);
                    }
                });
            });
        }
    }
    
    updateSelection();
}

// Bulk update status - for bulk actions
function bulkUpdateStatus(ids, status, tableId) {
    if (isBulkProcessing) return;
    isBulkProcessing = true;
    
    // Disable all bulk buttons to prevent double-clicking
    document.querySelectorAll(`#bulk-actions-${tableId} .btn-status`).forEach(btn => {
        btn.disabled = true;
    });
    
    Swal.fire({
        title: 'Processing...',
        html: `Updating ${ids.length} appointment(s) to ${status.replace('_', ' ')}`,
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });
    
    let success = 0, fail = 0;
    const failedIds = [];
    
    function next(i) {
        if (i >= ids.length) {
            Swal.close();
            isBulkProcessing = false;
            
            // Re-enable bulk buttons
            document.querySelectorAll(`#bulk-actions-${tableId} .btn-status`).forEach(btn => {
                btn.disabled = false;
            });
            
            let message = `${success} updated, ${fail} failed.`;
            let icon = fail > 0 ? 'warning' : 'success';
            let title = fail > 0 ? 'Completed with Errors' : 'Success!';
            
            Swal.fire({
                title: title,
                text: message,
                icon: icon,
                timer: 3000,
                showConfirmButton: false
            });
            
            fetchDashboardData();
            fetchRecentTransactionsPage(1);
            return;
        }
        
        fetch('{{ route('operator.update-window') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                n_id: ids[i],
                window_num: windowNum,
                status: status
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                success++;
                // Remove the row from UI immediately
                const row = document.querySelector(`.row-checkbox[data-id="${ids[i]}"]`)?.closest('tr');
                if (row) row.remove();
            } else {
                fail++;
                failedIds.push(ids[i]);
            }
        })
        .catch(() => {
            fail++;
            failedIds.push(ids[i]);
        })
        .finally(() => next(i + 1));
    }
    
    next(0);
}

// Initialize checkboxes when tables are loaded/updated
function initializeAllTableCheckboxes() {
    const tableIds = ['all', 'nid-registration', 'status-inquiry', 'nid-updating'];
    tableIds.forEach(tableId => {
        initializeCheckboxSelection(tableId);
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    attachServeButtonListeners();
    attachPaginationListeners();
    initializeAllTableCheckboxes();
    checkServingStatus();
});

// Initial fetch after 2 seconds
setTimeout(function() {
    fetchDashboardData();
}, 2000);
</script>