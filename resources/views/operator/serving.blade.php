{{-- resources/views/operator/serving.blade.php --}}
<x-header title="Serving Appointments" />

<div class="app-container">
    <x-operator.sidebar />    
    <style>
    /* Page Header */
    .page-header {
        margin-bottom: 10px;
    }

    .page-title1 {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }

    .page-title1 svg {
        width: 32px;
        height: 32px;
        color: #2563eb;
    }

    .page-title1 h1 {
        font-size: 28px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }

    .breadcrumb a {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #1e293b;
        text-decoration: none;
        padding: 6px 10px;
        border-radius: var(--radius);
        background: var(--gray-100);
        transition: all 0.2s ease;
    }

    .breadcrumb a:hover {
        background: var(--primary);
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
    }

    .breadcrumb a svg {
        color: var(--primary);
        transition: color 0.2s ease;
    }

    .breadcrumb a:hover svg {
        color: #fff;
    }

    .breadcrumb span {
        color: #676e79;
    }

    .breadcrumb .current {
        color: var(--gray-800);
        font-weight: 500;
        padding: 6px 10px;
        background: var(--gray-100);
        border-radius: var(--radius);
    }

    /* Windows Grid - 6 columns */
    .windows-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    /* Responsive adjustments */
    @media (max-width: 1400px) {
        .windows-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .windows-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .windows-grid {
            grid-template-columns: 1fr;
        }
    }

    .window-card {
        background: white;
        border-radius: 0.75rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }

    .window-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .window-card.window-active {
        border-left: 4px solid #10b981;
        background: linear-gradient(135deg, #f0fdf4, #ffffff);
    }

    /* Window Header with My Window badge */
    .window-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 1rem;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }

    .window-number {
        font-size: 0.875rem;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .window-number .my-window-badge {
        background: #10b981;
        color: white;
        font-size: 0.625rem;
        font-weight: 600;
        padding: 0.125rem 0.5rem;
        border-radius: 20px;
        text-transform: uppercase;
    }

    .window-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.6875rem;
        font-weight: 600;
    }

    .window-badge.idle {
        background: #f1f5f9;
        color: #64748b;
    }

    .window-badge.busy {
        background: #fee2e2;
        color: #dc2626;
    }

    .window-badge.serving {
        background: #dbeafe;
        color: #2563eb;
        animation: pulse-blue 1.5s infinite;
    }

    @keyframes pulse-blue {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; background: #bfdbfe; }
    }

    /* Serving Section - CENTERED */
    .window-serving-info {
        padding: 0.875rem 1rem;
        border-bottom: 1px solid #eef2ff;
        text-align: center;
    }

    .serving-label {
        font-size: 0.625rem;
        font-weight: 600;
        text-transform: uppercase;
        color: #94a3b8;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
        text-align: center;
    }

    .serving-queue {
        font-size: 1.125rem;
        font-weight: 700;
        font-family: monospace;
        color: #1e293b;
        background: #f1f5f9;
        display: inline-block;
        padding: 0.25rem 0.625rem;
        border-radius: 0.375rem;
        margin-bottom: 0.5rem;
        text-align: center;
    }

    .serving-name {
        font-size: 0.75rem;
        color: #475569;
        line-height: 1.3;
        word-break: break-word;
        text-align: center;
    }

    /* Previous Served Section - CENTERED */
    .previous-serving {
        padding: 0.875rem 1rem;
        background: #fafbfc;
        text-align: center;
    }

    .previous-label {
        font-size: 0.625rem;
        font-weight: 600;
        text-transform: uppercase;
        color: #94a3b8;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
        text-align: center;
    }

    .previous-queue {
        font-size: 0.875rem;
        font-weight: 600;
        font-family: monospace;
        color: #475569;
        background: #f1f5f9;
        display: inline-block;
        padding: 0.1875rem 0.5rem;
        border-radius: 0.25rem;
        margin-bottom: 0.25rem;
        text-align: center;
    }

    .previous-name {
        font-size: 0.6875rem;
        color: #64748b;
        line-height: 1.3;
        word-break: break-word;
        text-align: center;
    }

    .empty-window {
        text-align: center;
        padding: 0.5rem 0;
        color: #94a3b8;
        font-size: 0.6875rem;
        font-style: italic;
    }

    /* Summary Cards Grid - 5 columns */
    .summary-cards-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    /* Responsive adjustments for summary cards */
    @media (max-width: 1200px) {
        .summary-cards-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .summary-cards-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .summary-cards-grid {
            grid-template-columns: 1fr;
        }
    }

    .summary-card {
        background: white;
        border-radius: 0.75rem;
        padding: 1rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
    }

    .summary-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .summary-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.75rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e2e8f0;
    }

    .summary-card-title {
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
    }

    .summary-card-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .summary-card-value {
        font-size: 2rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .summary-card-value.pending {
        color: #f59e0b;
    }

    .summary-card-value.cancelled {
        color: #ef4444;
    }

    .summary-card-value.no-show {
        color: #6b7280;
    }

    .summary-card-value.completed {
        color: #10b981;
    }

    .summary-card-sub {
        font-size: 0.75rem;
        color: #64748b;
    }

    .priority-stats {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-top: 0.5rem;
    }

    .priority-stat-item {
        flex: 1;
        min-width: 80px;
        text-align: center;
        padding: 0.5rem;
        background: #f8fafc;
        border-radius: 0.5rem;
    }

    .priority-stat-label {
        font-size: 0.6875rem;
        font-weight: 600;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 0.25rem;
    }

    .priority-stat-number {
        font-size: 1.125rem;
        font-weight: 700;
    }

    .priority-stat-number.senior { color: #8b5cf6; }
    .priority-stat-number.infant { color: #f59e0b; }
    .priority-stat-number.pwd { color: #10b981; }
    .priority-stat-number.pregnant { color: #ec4899; }
    .priority-stat-number.regular { color: #64748b; }
</style>

    <!-- Main Content -->
    <main class="main-content">

        <!-- Page Header with Breadcrumb -->
        <div class="page-header">
            <div class="page-title1">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                </svg>
                <h1>Serving</h1>
            </div>
            <div class="breadcrumb">
                <a href="{{ route('operator.dashboard') }}">Home</a>
                <span>/</span>
                <span>Serving</span>
            </div>
        </div>

        <!-- ========== SUMMARY CARDS SECTION (5 CARDS) ========== -->
        <div class="summary-cards-grid">
            <!-- ALL COMPLETED Card -->
            <div class="summary-card">
                <div class="summary-card-header">
                    <span class="summary-card-title">All Completed</span>
                    <div class="summary-card-icon" style="background: linear-gradient(135deg, #10b981, #059669)">
                        <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <div class="summary-card-value" id="allCompletedCount">{{ $allCompletedToday ?? 0 }}</div>
                <div class="summary-card-sub">Total completed appointments today</div>
            </div>

            <!-- MY COMPLETED Card -->
            <div class="summary-card">
                <div class="summary-card-header">
                    <span class="summary-card-title">My Completed</span>
                    <div class="summary-card-icon" style="background: linear-gradient(135deg, #3b82f6, #2563eb)">
                        <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <div class="summary-card-value" id="myCompletedCount">{{ $completedCount ?? 0 }}</div>
                <div class="summary-card-sub">Completed by window #{{ $windowNum }}</div>
            </div>

            <!-- PENDING Card -->
            <div class="summary-card">
                <div class="summary-card-header">
                    <span class="summary-card-title">Pending</span>
                    <div class="summary-card-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706)">
                        <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <div class="summary-card-value pending" id="pendingCountSummary">{{ $pendingCount ?? 0 }}</div>
                <div class="summary-card-sub">Waiting to be served</div>
            </div>

            <!-- CANCELLED Card -->
            <div class="summary-card">
                <div class="summary-card-header">
                    <span class="summary-card-title">Cancelled</span>
                    <div class="summary-card-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626)">
                        <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <div class="summary-card-value cancelled" id="cancelledCountSummary">{{ $cancelledCount ?? 0 }}</div>
                <div class="summary-card-sub">Cancelled appointments</div>
            </div>

            <!-- NO SHOW Card -->
            <div class="summary-card">
                <div class="summary-card-header">
                    <span class="summary-card-title">No Show</span>
                    <div class="summary-card-icon" style="background: linear-gradient(135deg, #6b7280, #4b5563)">
                        <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <div class="summary-card-value no-show" id="noShowCountSummary">{{ $noShowCount ?? 0 }}</div>
                <div class="summary-card-sub">Did not show up</div>
            </div>
        </div>

        <!-- Message Container -->
        <div class="message-container" id="messageContainer"></div>

        <!-- ========== ALL WINDOWS STATUS CARDS ========== -->
        <div class="windows-grid" id="windowsGrid">
            <!-- Windows 1-6 will be populated dynamically via JavaScript -->
        </div>

        

        <!-- Today's Appointments Card -->
        <div class="card">
            <div class="card-header">
                <h3>
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                    </svg>
                    Today's Appointments
                </h3>
            </div>

            <!-- Service Type Tabs -->
            <div class="service-tabs">
                <button class="service-tab active" data-service="all">All Services</button>
                <button class="service-tab" data-service="NID Registration">NID Registration</button>
                <button class="service-tab" data-service="Status Inquiry">Status Inquiry</button>
                <button class="service-tab" data-service="Updating">NID Updating</button>
            </div>

            <div class="card-body">
                <!-- Search and Selection Bar -->
                <div class="search-and-selection-bar">
                    <!-- Selection Controls for All Services -->
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

                    <!-- Selection Controls for NID Registration -->
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

                    <!-- Selection Controls for Status Inquiry -->
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

                    <!-- Selection Controls for NID Updating -->
                    <div class="selection-controls" id="selection-controls-updating" style="display: none;">
                        <div class="selection-info">
                            <label class="select-all-container">
                                <input type="checkbox" class="select-all-checkbox" data-table="updating">
                                <span>Select All</span>
                            </label>
                            <span class="selected-count" id="selected-count-updating">0 selected</span>
                        </div>
                        <div class="bulk-actions" style="display: none;" id="bulk-actions-updating">
                            <button type="button" class="btn-status bulk-complete" data-table="updating" data-status="completed">
                                <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Complete
                            </button>
                            <button type="button" class="btn-status bulk-no-show" data-table="updating" data-status="no_show">
                                <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                No Show
                            </button>
                            <button type="button" class="btn-status bulk-cancel" data-table="updating" data-status="cancelled">
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
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                            <input type="text" id="searchAppointments" placeholder="Search by name or TRN...">
                        </div>
                    </div>
                </div>

                <!-- All Services Table -->
                <div class="service-table-container" id="table-all">
                    @include('operator.partials.appointments-table', [
                        'appointments' => $appointments,
                        'showAll' => true,
                        'tableId' => 'all',
                    ])
                </div>

                <!-- NID Registration Table -->
                <div class="service-table-container" id="table-nid-registration" style="display: none;">
                    @include('operator.partials.appointments-table', [
                        'appointments' => $nidRegistrationAppointments,
                        'serviceType' => 'NID Registration',
                        'tableId' => 'nid-registration',
                    ])
                </div>

                <!-- Status Inquiry Table -->
                <div class="service-table-container" id="table-status-inquiry" style="display: none;">
                    @include('operator.partials.appointments-table', [
                        'appointments' => $statusInquiryAppointments,
                        'serviceType' => 'Status Inquiry',
                        'tableId' => 'status-inquiry',
                    ])
                </div>

                <!-- NID Updating Table -->
                <div class="service-table-container" id="table-updating" style="display: none;">
                    @include('operator.partials.appointments-table', [
                        'appointments' => $nidUpdatingAppointments,
                        'serviceType' => 'Updating',
                        'tableId' => 'updating',
                    ])
                </div>
            </div>
        </div>
    </main>
</div>

<script>
// Configuration
const windowNum = String({{ Js::from(session('window_num') ?? ($windowNum ?? '1')) }});
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

// DOM Elements
const messageContainer = document.getElementById('messageContainer');
const searchInput = document.getElementById('searchAppointments');

// Store current state
let currentSearchTerm = '';
let activeService = 'all';
let refreshInterval;
let isCurrentlyServing = false;
let isProcessing = false;
let isBulkProcessing = false;
let hasSelectedCheckboxes = false;

// ========== REMEMBER ACTIVE TAB ==========
const savedService = localStorage.getItem('activeServiceTab');
if (savedService) {
    activeService = savedService;
}

// ========== FETCH AND RENDER ALL WINDOWS STATUS ==========
function fetchAndRenderWindows() {
    fetch('{{ route('operator.fetch-windows-status') }}', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            renderWindowsGrid(data.windows);
        }
    })
    .catch(error => console.error('Error fetching windows status:', error));
}

function renderWindowsGrid(windows) {
    const grid = document.getElementById('windowsGrid');
    if (!grid) return;
    
    let html = '';
    
    for (let i = 1; i <= 6; i++) {
        const windowData = windows[i] || { serving: null, previous: null };
        const isActiveWindow = i == windowNum;
        const hasServing = windowData.serving !== null;
        
        let statusClass = 'idle';
        let statusText = 'Idle';
        
        if (hasServing) {
            statusClass = 'serving';
            statusText = 'Serving';
        } else if (windowData.previous) {
            statusClass = 'busy';
            statusText = 'Completed';
        }
        
        html += `
            <div class="window-card ${isActiveWindow ? 'window-active' : ''}">
                <div class="window-header">
                    <span class="window-number">
                        WINDOW ${i}
                    </span>
                    <span class="window-badge ${statusClass}">${statusText}</span>
                </div>
                
                <div class="window-serving-info">
                    <div class="serving-label">CURRENTLY SERVING</div>
                    ${hasServing ? `
                        <div class="serving-queue">${windowData.serving.queue}</div>
                        <div class="serving-name">${windowData.serving.name}</div>
                    ` : `
                        <div class="empty-window">— No active serving —</div>
                    `}
                </div>
                
                <div class="previous-serving">
                    <div class="previous-label">PREVIOUS SERVED</div>
                    ${windowData.previous ? `
                        <div class="previous-queue">${windowData.previous.queue}</div>
                        <div class="previous-name">${windowData.previous.name}</div>
                    ` : `
                        <div class="empty-window">— No previous served —</div>
                    `}
                </div>
            </div>
        `;
    }
    
    grid.innerHTML = html;
}

// ========== UPDATE SUMMARY CARDS ==========
function updateSummaryCards(stats) {
    if (!stats) return;
    
    // Update the 5 summary cards that exist in your HTML
    if (stats.allCompleted !== undefined && document.getElementById('allCompletedCount')) {
        document.getElementById('allCompletedCount').textContent = stats.allCompleted;
    }
    if (stats.myCompleted !== undefined && document.getElementById('myCompletedCount')) {
        document.getElementById('myCompletedCount').textContent = stats.myCompleted;
    }
    if (stats.pending !== undefined && document.getElementById('pendingCountSummary')) {
        document.getElementById('pendingCountSummary').textContent = stats.pending;
    }
    if (stats.cancelled !== undefined && document.getElementById('cancelledCountSummary')) {
        document.getElementById('cancelledCountSummary').textContent = stats.cancelled;
    }
    if (stats.no_show !== undefined && document.getElementById('noShowCountSummary')) {
        document.getElementById('noShowCountSummary').textContent = stats.no_show;
    }
    
    // Update priority breakdown
    if (stats.senior !== undefined && document.getElementById('seniorCount')) {
        document.getElementById('seniorCount').textContent = stats.senior;
    }
    if (stats.infant !== undefined && document.getElementById('infantCount')) {
        document.getElementById('infantCount').textContent = stats.infant;
    }
    if (stats.pwd !== undefined && document.getElementById('pwdCount')) {
        document.getElementById('pwdCount').textContent = stats.pwd;
    }
    if (stats.pregnant !== undefined && document.getElementById('pregnantCount')) {
        document.getElementById('pregnantCount').textContent = stats.pregnant;
    }
    if (stats.regular !== undefined && document.getElementById('regularCount')) {
        document.getElementById('regularCount').textContent = stats.regular;
    }
}

// Update statistics - simplified since we don't have totalQueue and completedCount elements
function updateStatistics(stats) {
    if (!stats) return;
    // Only update what exists - pendingCount is already updated in updateSummaryCards
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
    const queueNumber = button.getAttribute('data-queue');
    const originalStatus = button.getAttribute('data-status');
    const row = button.closest('tr');

    Swal.fire({
        title: originalStatus === 'no_show' ? 'Serve No-Show Appointment?' : 'Start Serving?',
        html: `Call <strong>${name}</strong> (${queueNumber}) to window <strong>#${windowNum}</strong>?`,
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
                // Trigger announcement
                fetch('{{ route('operator.trigger-announcement') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        window_num: windowNum,
                        queue_number: queueNumber,
                        client_name: name
                    })
                }).catch(err => console.error('Error triggering announcement:', err));
                
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
                    title: `Now serving ${queueNumber} - ${name}`
                });

                updatePendingCount();
                fetchDashboardData();
                fetchAndRenderWindows();
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

// Handle Volume/Speaker Button Click
function handleVolumeClick(e) {
    const button = e.currentTarget;
    const queueNumber = button.getAttribute('data-queue');
    const clientName = button.getAttribute('data-name');
    
    const originalHtml = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<span class="spinner"></span>';
    
    fetch('{{ route('operator.trigger-announcement') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            window_num: windowNum,
            queue_number: queueNumber,
            client_name: clientName
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });

            Toast.fire({
                icon: 'success',
                title: `Announcement triggered for ${queueNumber}`
            });
        } else {
            showMessage('Failed to trigger announcement', 'error');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        showMessage('Error connecting to server', 'error');
    })
    .finally(() => {
        button.disabled = false;
        button.innerHTML = originalHtml;
    });
}

// Check if there's any serving appointment
function checkServingStatus() {
    const servingRow = document.querySelector('.status-badge.status-serving');
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
            btn.title = btn.getAttribute('data-status') === 'no_show' ?
                'Call this no-show appointment to your window' :
                'Call this appointment to your window';
        }
    });
}

// Handle Complete Button Click
function handleCompleteClick(e) {
    const button = e.currentTarget;
    const n_id = button.getAttribute('data-id');
    const name = button.getAttribute('data-name');
    const queueNumber = button.getAttribute('data-queue');
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
            updateAppointmentStatus(n_id, 'completed', row, 'completed', queueNumber, name);
        }
    });
}

// Handle No Show Button Click
function handleNoShowClick(e) {
    const button = e.currentTarget;
    const n_id = button.getAttribute('data-id');
    const name = button.getAttribute('data-name');
    const queueNumber = button.getAttribute('data-queue');
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
            updateAppointmentStatus(n_id, 'no_show', row, 'no_show', queueNumber, name);
        }
    });
}

// Handle Cancel Button Click
function handleCancelClick(e) {
    const button = e.currentTarget;
    const n_id = button.getAttribute('data-id');
    const name = button.getAttribute('data-name');
    const queueNumber = button.getAttribute('data-queue');
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
            updateAppointmentStatus(n_id, 'cancelled', row, 'cancelled', queueNumber, name);
        }
    });
}

// Update appointment status
function updateAppointmentStatus(n_id, status, row, statusClass, queueNumber, name) {
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
            fetchAndRenderWindows();
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

// Update row after status change
function updateRowAfterStatusChange(row, status, statusClass, n_id) {
    const statusBadge = row.querySelector('.status-badge');
    if (statusBadge) {
        statusBadge.className = `status-badge status-${statusClass}`;
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
            btn.title = btn.getAttribute('data-status') === 'no_show' ?
                'Call this no-show appointment to your window' :
                'Call this appointment to your window';
        });
    }

    attachServeButtonListeners();
}

// Update row for serving status
function updateRowForServing(row, n_id, name) {
    const statusBadge = row.querySelector('.status-badge');
    if (statusBadge) {
        statusBadge.className = 'status-badge status-serving';
        statusBadge.innerHTML = '<span class="status-dot"></span> Serving';
    }

    const actionCell = row.querySelector('td:last-child');
    if (actionCell) {
        actionCell.innerHTML = `
            <div class="action-button-group">
                <button class="btn-action complete-btn" data-id="${n_id}" data-name="${name}">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Done
                </button>
                <button class="btn-action no-show-btn" data-id="${n_id}" data-name="${name}">
                    No Show
                </button>
                <button class="btn-action cancel-btn" data-id="${n_id}" data-name="${name}">
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

    document.querySelectorAll('.volume-btn').forEach(button => {
        button.removeEventListener('click', handleVolumeClick);
        button.addEventListener('click', handleVolumeClick);
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

// Service Tab Switching
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
        if (document.getElementById('pendingCountSummary')) {
            document.getElementById('pendingCountSummary').textContent = pendingCount;
        }
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
            if (data.summaryStats) {
                updateSummaryCards(data.summaryStats);
            }
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
    if (document.getElementById('table-updating')) {
        document.getElementById('table-updating').innerHTML = data.tableNidUpdating || '';
    }

    attachServeButtonListeners();
    initializeAllTableCheckboxes();

    if (currentSearchTerm) {
        filterTableRows();
    }
}

// ========== CHECKBOX SELECTION FUNCTIONALITY ==========
function initializeCheckboxSelection(tableId) {
    const tableContainer = document.getElementById(`table-${tableId}`);
    if (!tableContainer) return;

    const selectAllFooter = document.querySelector(`.select-all-checkbox[data-table="${tableId}"]`);
    const rowCheckboxes = tableContainer.querySelectorAll(`.row-checkbox[data-table="${tableId}"]:not([disabled])`);
    const allRowCheckboxes = tableContainer.querySelectorAll(`.row-checkbox[data-table="${tableId}"]`);
    const selectedCountSpan = document.getElementById(`selected-count-${tableId}`);
    const bulkActionsDiv = document.getElementById(`bulk-actions-${tableId}`);

    if (!rowCheckboxes.length) return;

    function updateSelection() {
        const checkedCount = Array.from(rowCheckboxes).filter(cb => cb.checked).length;
        hasSelectedCheckboxes = checkedCount > 0;

        if (selectedCountSpan) selectedCountSpan.textContent = `${checkedCount} selected`;
        if (bulkActionsDiv) bulkActionsDiv.style.display = checkedCount > 0 ? 'flex' : 'none';

        allRowCheckboxes.forEach(cb => {
            const row = cb.closest('tr');
            if (row) row.classList.toggle('selected', cb.checked && !cb.disabled);
        });

        const allChecked = rowCheckboxes.length > 0 && Array.from(rowCheckboxes).every(cb => cb.checked);
        const someChecked = Array.from(rowCheckboxes).some(cb => cb.checked);

        if (selectAllFooter) {
            selectAllFooter.checked = allChecked;
            selectAllFooter.indeterminate = someChecked && !allChecked;
        }
    }

    rowCheckboxes.forEach(cb => {
        cb.removeEventListener('change', updateSelection);
        cb.addEventListener('change', updateSelection);
    });

    if (selectAllFooter) {
        selectAllFooter.removeEventListener('change', handleSelectAll);
        selectAllFooter.addEventListener('change', handleSelectAll);
    }

    function handleSelectAll(e) {
        rowCheckboxes.forEach(cb => cb.checked = e.target.checked);
        updateSelection();
    }

    if (bulkActionsDiv) {
        const completeBtn = bulkActionsDiv.querySelector('.bulk-complete');
        const noShowBtn = bulkActionsDiv.querySelector('.bulk-no-show');
        const cancelBtn = bulkActionsDiv.querySelector('.bulk-cancel');

        if (completeBtn) {
            completeBtn.removeEventListener('click', () => handleBulkStatus(tableId, 'completed'));
            completeBtn.addEventListener('click', () => handleBulkStatus(tableId, 'completed'));
        }
        if (noShowBtn) {
            noShowBtn.removeEventListener('click', () => handleBulkStatus(tableId, 'no_show'));
            noShowBtn.addEventListener('click', () => handleBulkStatus(tableId, 'no_show'));
        }
        if (cancelBtn) {
            cancelBtn.removeEventListener('click', () => handleBulkStatus(tableId, 'cancelled'));
            cancelBtn.addEventListener('click', () => handleBulkStatus(tableId, 'cancelled'));
        }
    }

    updateSelection();
}

function handleBulkStatus(tableId, status) {
    const checkboxes = document.querySelectorAll(`.row-checkbox[data-table="${tableId}"]:checked`);
    const selectedIds = Array.from(checkboxes).map(cb => cb.dataset.id);

    if (selectedIds.length === 0) return;

    const statusText = status === 'completed' ? 'completed' : (status === 'no_show' ? 'no show' : 'cancelled');
    const title = status === 'completed' ? 'Bulk Complete' : (status === 'no_show' ? 'Bulk No Show' : 'Bulk Cancel');
    const confirmText = status === 'completed' ? 'Yes, Complete All' : (status === 'no_show' ? 'Yes, Mark All' : 'Yes, Cancel All');
    const confirmColor = status === 'completed' ? '#10b981' : (status === 'no_show' ? '#6b7280' : '#ef4444');

    Swal.fire({
        title: title,
        text: `Mark ${selectedIds.length} selected appointment(s) as ${statusText}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: confirmColor,
        cancelButtonColor: '#dc2626',
        confirmButtonText: confirmText
    }).then((result) => {
        if (result.isConfirmed) {
            bulkUpdateStatus(selectedIds, status, tableId);
        }
    });
}

// Bulk update status
function bulkUpdateStatus(ids, status, tableId) {
    if (isBulkProcessing) return;
    isBulkProcessing = true;

    document.querySelectorAll(`#bulk-actions-${tableId} .btn-status`).forEach(btn => btn.disabled = true);

    Swal.fire({
        title: 'Processing...',
        html: `Updating ${ids.length} appointment(s) to ${status.replace('_', ' ')}`,
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    let success = 0, fail = 0;

    function next(i) {
        if (i >= ids.length) {
            Swal.close();
            isBulkProcessing = false;

            document.querySelectorAll(`#bulk-actions-${tableId} .btn-status`).forEach(btn => btn.disabled = false);

            Swal.fire({
                title: fail > 0 ? 'Completed with Errors' : 'Success!',
                text: `${success} updated, ${fail} failed.`,
                icon: fail > 0 ? 'warning' : 'success',
                timer: 3000,
                showConfirmButton: false
            });

            fetchDashboardData();
            fetchAndRenderWindows();
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
                const row = document.querySelector(`.row-checkbox[data-id="${ids[i]}"]`)?.closest('tr');
                if (row) row.remove();
            } else {
                fail++;
            }
        })
        .catch(() => fail++)
        .finally(() => next(i + 1));
    }

    next(0);
}

// Initialize checkboxes
function initializeAllTableCheckboxes() {
    const tableIds = ['all', 'nid-registration', 'status-inquiry', 'updating'];
    tableIds.forEach(tableId => initializeCheckboxSelection(tableId));
}

// Auto-refresh dashboard every 10 seconds
refreshInterval = setInterval(function() {
    if (!hasSelectedCheckboxes && !document.querySelector('.serve-btn[disabled]')) {
        fetchDashboardData();
        fetchAndRenderWindows();
    }
}, 10000);

// Refresh when user returns to the tab
document.addEventListener('visibilitychange', function() {
    if (!document.hidden && !hasSelectedCheckboxes && !document.querySelector('.serve-btn[disabled]')) {
        fetchDashboardData();
        fetchAndRenderWindows();
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    attachServeButtonListeners();
    initializeAllTableCheckboxes();
    checkServingStatus();
    fetchDashboardData();
    fetchAndRenderWindows();
});
</script>