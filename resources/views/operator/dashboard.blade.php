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
                            <th>Age Category</th>
                            <th>Birthdate</th>
                            <th>TRN</th>
                            <th>PCN</th>
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
                                $createdTime = \Carbon\Carbon::parse($appointment->date)->setTimezone('Asia/Manila');
                                $isCompleted = $servedTime && $servedTime->gt($createdTime);
                                $serviceDisplay = $appointment->queue_for;

                                // Format name properly with FULL middle name (not just initial)
                                $fullName = $appointment->lname . ', ' . $appointment->fname;
                                if ($appointment->mname && trim($appointment->mname) !== '') {
                                    $fullName .= ' ' . $appointment->mname;
                                }
                                if ($appointment->suffix && trim($appointment->suffix) !== '') {
                                    $fullName .= ' ' . $appointment->suffix;
                                }
                            @endphp
                            {{-- Only show if NOT completed --}}
                            @if (!$isCompleted)
                                <tr data-search="{{ strtolower($appointment->lname . ' ' . $appointment->fname . ' ' . ($appointment->trn ?? '')) }}">
                                    <td><span class="queue-number">{{ $appointment->q_id }}</span></td>
                                    <td>
                                        <div class="client-name">
                                            {{ $fullName }}
                                        </div>
                                    </td>
                                    <td>{{ $appointment->age_category ?? 'N/A' }}</td>
                                    <td>{{ $appointment->birthdate ? \Carbon\Carbon::parse($appointment->birthdate)->format('M d, Y') : 'N/A' }}</td>
                                    <td>{{ $appointment->trn ?? 'N/A' }}</td>
                                    <td>{{ $appointment->pcn ?? 'N/A' }}</td>
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
                                            Next
                                        </button>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="10" class="empty-state">
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

                                // Format name properly with FULL middle name (not just initial)
                                $fullName = $transaction->lname . ', ' . $transaction->fname;
                                if ($transaction->mname && trim($transaction->mname) !== '') {
                                    $fullName .= ' ' . $transaction->mname;
                                }
                                if ($transaction->suffix && trim($transaction->suffix) !== '') {
                                    $fullName .= ' ' . $transaction->suffix;
                                }
                            @endphp
                            <tr>
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
                @include('operator.partials.pagination-links', ['completedTransactions' => $completedTransactions])
            </div>
            
            
        </div>
    </div>
</main>

<script>
    // Configuration - Ensure windowNum is a string
    const windowNum = String({{ Js::from(session('window_num') ?? ($windowNum ?? '1')) }});
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    // DOM Elements
    const messageContainer = document.getElementById('messageContainer');
    const appointmentsTableBody = document.getElementById('appointmentsTableBody');
    const searchInput = document.getElementById('searchAppointments');
    const recentTransactionsBody = document.getElementById('recentTransactionsBody');
    const showingInfo = document.getElementById('showingInfo');

    // Store current search term
    let currentSearchTerm = '';
    let isLoading = false;

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

        setTimeout(() => {
            messageDiv.remove();
        }, 5000);
    }

    // Search Functionality
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            currentSearchTerm = this.value.toLowerCase();
            filterTableRows();
        });
    }

    function filterTableRows() {
        const rows = document.querySelectorAll('#appointmentsTableBody tr');
        
        rows.forEach(row => {
            // Skip empty state row
            if (row.classList.contains('empty-state')) return;

            const searchData = row.getAttribute('data-search') || row.textContent.toLowerCase();
            if (searchData.includes(currentSearchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Handle Serve Button Click
    function handleServeClick(e) {
        const button = e.currentTarget;
        const n_id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');
        const row = button.closest('tr');

        // Show confirmation dialog using SweetAlert2
        Swal.fire({
            title: 'Confirm Call',
            html: `<div style="text-align: center; font-size: 16px;">
                    Call <strong>${name}</strong> to window <strong>#${windowNum}</strong>?
                   </div>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#dc2626',
            confirmButtonText: 'Yes, Call Now!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Disable button to prevent double-click
                button.disabled = true;
                const originalHtml = button.innerHTML;
                button.innerHTML =
                    '<svg viewBox="0 0 20 20" fill="currentColor" class="animate-spin"><path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/></svg> Processing...';

                fetch('{{ route('operator.update-window') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        n_id: n_id,
                        window_num: windowNum
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Show success popup
                        showCallSuccessPopup();
                        
                        // Immediately remove the row to provide instant feedback
                        if (row) {
                            row.remove();
                        }
                        
                        // Check if table is empty
                        const remainingRows = document.querySelectorAll('#appointmentsTableBody tr:not(.empty-state)');
                        if (remainingRows.length === 0) {
                            appointmentsTableBody.innerHTML = `
                                <tr>
                                    <td colspan="10" class="empty-state">
                                        <svg viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <p>No pending appointments for today</p>
                                    </td>
                                </tr>
                            `;
                        }
                        
                        // Update statistics immediately
                        updatePendingCount();
                        
                        // Then fetch fresh data in the background
                        setTimeout(() => {
                            fetchDashboardData();
                        }, 500);
                    } else {
                        showMessage(data.message || 'Failed to update.', 'error');
                        button.disabled = false;
                        button.innerHTML = originalHtml;
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    showMessage('Error connecting to server. Please try again.', 'error');
                    button.disabled = false;
                    button.innerHTML = originalHtml;
                });
            }
        });
    }

    // Clean and minimal success popup matching your style
    function showCallSuccessPopup() {
        Swal.fire({
            title: 'Served Successfully!',
            icon: 'success',
            confirmButtonColor: '#2563eb',
            confirmButtonText: 'OK',
            timer: 5000,
            timerProgressBar: true,
            showCloseButton: true,
            width: 500,
            padding: '2rem'
        });
    }

    // Helper function to update pending count
    function updatePendingCount() {
        const pendingCount = document.querySelectorAll('#appointmentsTableBody tr:not(.empty-state)').length;
        document.getElementById('pendingCount').textContent = pendingCount;
        
        // Update total queue count (optional - you might want to fetch this from server)
        const totalQueue = parseInt(document.getElementById('totalQueue').textContent);
        if (totalQueue > 0) {
            document.getElementById('totalQueue').textContent = totalQueue - 1;
        }
    }

    // Attach Serve Button Listeners
    function attachServeButtonListeners() {
        document.querySelectorAll('.serve-btn').forEach(button => {
            button.removeEventListener('click', handleServeClick);
            button.addEventListener('click', handleServeClick);
        });
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
                updateAppointmentsTable(data.appointments);
                updateStatistics(data.stats);
            }
        })
        .catch(error => {
            console.error('Error fetching dashboard data:', error);
        });
    }

    // Smooth AJAX Pagination for Recent Transactions
    function loadTransactionsPage(url) {
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
        .then(response => response.json())
        .then(data => {
            // Smooth fade out/in
            tableContainer.style.opacity = '0';
            paginationContainer.style.opacity = '0';
            
            setTimeout(() => {
                // Update content
                tableContainer.innerHTML = data.table;
                paginationContainer.innerHTML = data.pagination;
                showingInfo.textContent = data.showing;
                
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
                    loadTransactionsPage(this.href);
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
        
        loadTransactionsPage(url.toString());
    }

    // Update appointments table
    function updateAppointmentsTable(appointments) {
        if (!appointments || appointments.length === 0) {
            appointmentsTableBody.innerHTML = `
                <tr>
                    <td colspan="10" class="empty-state">
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
            const createdTime = new Date(app.date).toLocaleTimeString('en-US', { 
                hour: '2-digit', 
                minute: '2-digit',
                hour12: true,
                timeZone: 'Asia/Manila'
            });
            
            // Format name with FULL middle name (not just initial)
            let fullName = app.lname + ', ' + app.fname;
            
            // Add full middle name if it exists
            if (app.mname && app.mname.trim() !== '') {
                fullName += ' ' + app.mname;
            }
            
            // Add suffix if it exists
            if (app.suffix && app.suffix.trim() !== '') {
                fullName += ' ' + app.suffix;
            }
            
            const birthdate = app.birthdate ? new Date(app.birthdate).toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric', 
                year: 'numeric' 
            }) : 'N/A';
            
            const searchData = (app.lname + ' ' + app.fname + ' ' + (app.trn || '')).toLowerCase();
            
            html += `
                <tr data-search="${searchData}">
                    <td><span class="queue-number">${app.q_id || 'N/A'}</span></td>
                    <td>
                        <div class="client-name">
                            ${fullName}
                        </div>
                    </td>
                    <td>${app.age_category || 'N/A'}</td>
                    <td>${birthdate}</td>
                    <td>${app.trn || 'N/A'}</td>
                    <td>${app.pcn || 'N/A'}</td>
                    <td>${app.queue_for || 'N/A'}</td>
                    <td>${createdTime}</td>
                    <td>
                        <span class="status-badge status-pending">
                            <span class="status-dot"></span>
                            Pending
                        </span>
                    </td>
                    <td>
                        <button class="btn-action serve-btn" data-id="${app.n_id}" data-name="${app.fname} ${app.lname}">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Next
                        </button>
                    </td>
                </tr>
            `;
        });

        appointmentsTableBody.innerHTML = html;
        
        // Re-attach event listeners to all serve buttons
        attachServeButtonListeners();
        
        // Re-apply search filter if there's a search term
        if (currentSearchTerm) {
            filterTableRows();
        }
    }

    // Update statistics
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

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        attachServeButtonListeners();
        attachPaginationListeners();
        
        // Handle items per page change
        document.getElementById('perPageSelect').addEventListener('change', handlePerPageChange);
    });

    // Auto-refresh dashboard every 10 seconds (without page reload)
    let refreshInterval = setInterval(function() {
        // Only refresh if there are no active serve actions
        if (!document.querySelector('.serve-btn[disabled]')) {
            fetchDashboardData();
        }
    }, 10000); // Refresh every 10 seconds

    // Also refresh when user returns to the tab
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden && !document.querySelector('.serve-btn[disabled]')) {
            fetchDashboardData();
        }
    });

    // Initial fetch after 5 seconds to ensure everything is loaded
    setTimeout(fetchDashboardData, 5000);
</script>