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

        .serving-badge-header {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 10px;
        }

        .serving-card {
            border-left: 4px solid #ef4444;
        }
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

        <!-- Message Container -->
        <div class="message-container" id="messageContainer"></div>
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
            
        </div>

        {{-- Service Type Tabs --}}
        <div class="service-tabs">
            <button class="service-tab active" data-service="all">All Services</button>
            <button class="service-tab" data-service="NID Registration">NID Registration</button>
            <button class="service-tab" data-service="Status Inquiry">Status Inquiry</button>
            <button class="service-tab" data-service="Updating">NID Updating</button>
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
                        <button type="button" class="btn-status bulk-complete" data-table="all"
                            data-status="completed">
                            <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Complete
                        </button>
                        <button type="button" class="btn-status bulk-no-show" data-table="all" data-status="no_show">
                            <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                            No Show
                        </button>
                        <button type="button" class="btn-status bulk-cancel" data-table="all" data-status="cancelled">
                            <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
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
                        <button type="button" class="btn-status bulk-complete" data-table="nid-registration"
                            data-status="completed">
                            <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Complete
                        </button>
                        <button type="button" class="btn-status bulk-no-show" data-table="nid-registration"
                            data-status="no_show">
                            <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                            No Show
                        </button>
                        <button type="button" class="btn-status bulk-cancel" data-table="nid-registration"
                            data-status="cancelled">
                            <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
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
                        <button type="button" class="btn-status bulk-complete" data-table="status-inquiry"
                            data-status="completed">
                            <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Complete
                        </button>
                        <button type="button" class="btn-status bulk-no-show" data-table="status-inquiry"
                            data-status="no_show">
                            <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                            No Show
                        </button>
                        <button type="button" class="btn-status bulk-cancel" data-table="status-inquiry"
                            data-status="cancelled">
                            <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                            Cancel
                        </button>
                    </div>
                </div>

                {{-- Selection Controls for NID Updating --}}
                <div class="selection-controls" id="selection-controls-updating" style="display: none;">
                    <div class="selection-info">
                        <label class="select-all-container">
                            <input type="checkbox" class="select-all-checkbox" data-table="updating">
                            <span>Select All</span>
                        </label>
                        <span class="selected-count" id="selected-count-updating">0 selected</span>
                    </div>
                    <div class="bulk-actions" style="display: none;" id="bulk-actions-updating">
                        <button type="button" class="btn-status bulk-complete" data-table="updating"
                            data-status="completed">
                            <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Complete
                        </button>
                        <button type="button" class="btn-status bulk-no-show" data-table="updating"
                            data-status="no_show">
                            <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                            No Show
                        </button>
                        <button type="button" class="btn-status bulk-cancel" data-table="updating"
                            data-status="cancelled">
                            <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
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
                    'tableId' => 'all',
                ])
            </div>

            {{-- NID Registration Table --}}
            <div class="service-table-container" id="table-nid-registration" style="display: none;">
                @include('operator.partials.appointments-table', [
                    'appointments' => $nidRegistrationAppointments,
                    'serviceType' => 'NID Registration',
                    'tableId' => 'nid-registration',
                ])
            </div>

            {{-- Status Inquiry Table --}}
            <div class="service-table-container" id="table-status-inquiry" style="display: none;">
                @include('operator.partials.appointments-table', [
                    'appointments' => $statusInquiryAppointments,
                    'serviceType' => 'Status Inquiry',
                    'tableId' => 'status-inquiry',
                ])
            </div>

            {{-- NID Updating Table --}}
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
   // Configuration - Ensure windowNum is a string
const windowNum = String({{ Js::from(session('window_num') ?? ($windowNum ?? '1')) }});
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

// DOM Elements
const messageContainer = document.getElementById('messageContainer');
const searchInput = document.getElementById('searchAppointments');

// Store current search term and active service
let currentSearchTerm = '';
let activeService = 'all';
let refreshInterval;
let isCurrentlyServing = false;
let isProcessing = false;
let isBulkProcessing = false;
let hasSelectedCheckboxes = false;

// ========== REMEMBER ACTIVE TAB ON PAGE REFRESH ==========
const savedService = localStorage.getItem('activeServiceTab');
if (savedService) {
    activeService = savedService;
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
            updateAppointmentStatus(n_id, 'no_show', row, 'no_show');
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

// Update appointment status
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
    if (document.getElementById('table-updating')) {
        document.getElementById('table-updating').innerHTML = data.tableNidUpdating || '';
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
    }
}, 10000);

// Refresh when user returns to the tab
document.addEventListener('visibilitychange', function() {
    if (!document.hidden && !hasSelectedCheckboxes && !document.querySelector('.serve-btn[disabled]')) {
        fetchDashboardData();
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    attachServeButtonListeners();
    initializeAllTableCheckboxes();
    checkServingStatus();
});
</script>