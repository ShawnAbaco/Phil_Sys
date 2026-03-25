{{-- resources/views/operator/transactions.blade.php --}}
<x-header title="Recent Transactions" />

<div class="app-container">
    <x-operator.sidebar />    
    <style>
        .app-container {
            display: flex;
            min-height: 100vh;
        }

        .main-content {
            flex: 1;
            margin-left: 0;
            padding: 30px;
            transition: all 0.3s ease;
        }

        @media (min-width: 769px) {
            .main-content {
                margin-left: 280px;
            }
        }

        .filter-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #4b5563;
        }

        .service-filter {
            padding: 8px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 30px;
            font-size: 0.875rem;
            color: #374151;
            background: white;
            cursor: pointer;
            outline: none;
            transition: all 0.2s ease;
        }

        .service-filter:hover {
            border-color: #2563eb;
        }

        .service-filter:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .per-page-selector {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .per-page-dropdown {
            padding: 6px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            background: white;
            font-size: 0.875rem;
            color: #374151;
            cursor: pointer;
            outline: none;
        }

        .per-page-dropdown:hover {
            border-color: #2563eb;
        }

        .export-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-export {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }

        .btn-export-pdf {
            background-color: #dc2626;
            color: white;
        }

        .btn-export-pdf:hover {
            background-color: #b91c1c;
            transform: translateY(-1px);
        }

        .btn-export-excel {
            background-color: #059669;
            color: white;
        }

        .btn-export-excel:hover {
            background-color: #047857;
            transform: translateY(-1px);
        }

        /* Table responsive wrapper */
        .table-responsive {
            overflow-x: auto;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.8125rem;
            min-width: 800px;
        }

        .table th {
            text-align: left;
            padding: 0.875rem 1rem;
            background: #f8fafc;
            color: #475569;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.025em;
            border-bottom: 2px solid #e2e8f0;
        }

        .table td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #e2e8f0;
            color: #475569;
            vertical-align: middle;
        }

        .table tbody tr:hover td {
            background: #f8fafc;
        }

        .row-number {
            display: inline-block;
            width: 28px;
            height: 28px;
            line-height: 28px;
            text-align: center;
            background: #f1f5f9;
            border-radius: 50%;
            font-weight: 600;
            color: #334155;
            font-size: 0.75rem;
        }

        .queue-number.small {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            background: #f1f5f9;
            border-radius: 0.25rem;
            font-weight: 600;
            color: #334155;
            font-size: 0.6875rem;
            font-family: monospace;
        }

        .client-name {
            font-weight: 500;
            color: #1e293b;
            line-height: 1.3;
        }

        .priority-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 30px;
            font-size: 0.6875rem;
            font-weight: 600;
            text-transform: uppercase;
            color: white;
            min-width: 65px;
            text-align: center;
        }

        .priority-senior { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
        .priority-infant { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .priority-pwd { background: linear-gradient(135deg, #10b981, #059669); }
        .priority-pregnant { background: linear-gradient(135deg, #ec4899, #db2777); }
        .priority-regular { background: linear-gradient(135deg, #64748b, #475569); }

        .time-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            background: #f1f5f9;
            border-radius: 0.25rem;
            font-size: 0.6875rem;
            white-space: nowrap;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.25rem 0.75rem;
            border-radius: 30px;
            font-size: 0.6875rem;
            font-weight: 600;
            text-transform: capitalize;
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

        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1.5rem !important;
            color: #64748b;
        }

        .empty-state svg {
            width: 3rem;
            height: 3rem;
            margin: 0 auto 0.75rem;
            color: #cbd5e1;
        }

        .empty-state p {
            font-size: 0.875rem;
            margin: 0;
        }

        /* Pagination styles */
        .enhanced-pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding: 20px 0 10px 0;
            border-top: 2px solid #e2e8f0;
            gap: 15px;
            flex-wrap: wrap;
        }

        .pagination-nav-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border: 1px solid #cbd5e1;
            border-radius: 0.5rem;
            background: white;
            color: #4b5563;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .pagination-nav-btn:hover:not(.disabled) {
            background: #f8fafc;
            border-color: #2563eb;
            color: #2563eb;
        }

        .pagination-nav-btn.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        .pagination-center-group {
            display: flex;
            align-items: center;
            gap: 5px;
            background: #f8fafc;
            padding: 4px 8px;
            border-radius: 30px;
            border: 1px solid #e2e8f0;
        }

        .pagination-arrow {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border: 1px solid #cbd5e1;
            border-radius: 50%;
            background: white;
            color: #4b5563;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .pagination-arrow:hover:not(.disabled) {
            background: #f8fafc;
            border-color: #2563eb;
            color: #2563eb;
        }

        .pagination-arrow.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        .pagination-numbers {
            display: flex;
            align-items: center;
            gap: 3px;
        }

        .page-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 32px;
            height: 32px;
            padding: 0 4px;
            border-radius: 6px;
            background: transparent;
            color: #4b5563;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .page-number:hover:not(.active) {
            background: #e2e8f0;
        }

        .page-number.active {
            background: #2563eb;
            color: white;
            cursor: default;
            pointer-events: none;
        }

        .page-dots {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 24px;
            height: 32px;
            color: #9ca3af;
            font-size: 0.875rem;
        }
    </style>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Message Container -->
        <div class="message-container" id="messageContainer"></div>

        {{-- Recent Transactions Card --}}
        <div class="card full-width" id="recentTransactionsCard">
            <div class="card-header">
                <h3>
                    <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                        <path fill-rule="evenodd"
                            d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V8a2 2 0 00-2-2h-5L9 4H4zm7 4a1 1 0 10-2 0v3.586l-.293-.293a1 1 0 10-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 10-1.414-1.414l-.293.293V8z"
                            clip-rule="evenodd" />
                    </svg>
                    Recent Transactions
                </h3>
                <div class="card-actions">
                    <span class="badge" id="showingInfo">Loading...</span>
                </div>
            </div>

            <div class="card-body">
                {{-- Filter Bar --}}
                <div class="filter-bar">
                    <div class="filter-group">
                        <span class="filter-label">Filter by Service:</span>
                        <select id="transactionServiceFilter" class="service-filter">
                            <option value="all">All Services</option>
                            <option value="NID Registration">NID Registration</option>
                            <option value="Status Inquiry">Status Inquiry</option>
                            <option value="Updating">NID Updating</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <span class="filter-label">Show:</span>
                        <select id="perPageSelect" class="per-page-dropdown">
                            <option value="10">10 per page</option>
                            <option value="20">20 per page</option>
                            <option value="50">50 per page</option>
                            <option value="100">100 per page</option>
                        </select>
                    </div>

                    <div class="export-buttons">
                        <button type="button" class="btn-export btn-export-pdf" id="operatorExportPdfBtn">
                            <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                                <path fill-rule="evenodd"
                                    d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                    clip-rule="evenodd" />
                            </svg>
                            Export PDF
                        </button>
                        <button type="button" class="btn-export btn-export-excel" id="operatorExportExcelBtn">
                            <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                                <path fill-rule="evenodd"
                                    d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 2v2h2V6H6zm6 0v2h2V6h-2zm-6 4v2h2v-2H6zm6 0v2h2v-2h-2zm-6 4v2h2v-2H6zm6 0v2h2v-2h-2z"
                                    clip-rule="evenodd" />
                            </svg>
                            Export Excel
                        </button>
                    </div>
                </div>

                {{-- Transactions Table --}}
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Queue #</th>
                                <th>Client</th>
                                <th>Priority</th>
                                <th>Service</th>
                                <th>Served Time</th>
                                <th>Status</th>
                            </thead>
                        <tbody id="transactionsTableContainer">
                            {{-- This will be populated by AJAX --}}
                            @include('operator.partials.transactions-table', [
                                'completedTransactions' => $completedTransactions,
                            ])
                        </tbody>
                    </table>
                </div>

                {{-- Enhanced Pagination --}}
                <div class="enhanced-pagination" id="paginationContainer">
                    @include('operator.partials.pagination-links', [
                        'completedTransactions' => $completedTransactions,
                    ])
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    let isLoading = false;
    let currentPage = {{ $completedTransactions->currentPage() }};
    let currentPerPage = {{ $completedTransactions->perPage() }};
    let currentServiceFilter = 'all';

    // DOM Elements
    const transactionsTableContainer = document.getElementById('transactionsTableContainer');
    const paginationContainer = document.getElementById('paginationContainer');
    const showingInfo = document.getElementById('showingInfo');
    const serviceFilter = document.getElementById('transactionServiceFilter');
    const perPageSelect = document.getElementById('perPageSelect');

    // Load transactions page
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

        tableContainer.style.opacity = '0';
        paginationContainer.style.opacity = '0';

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

            setTimeout(() => {
                if (data.table) tableContainer.innerHTML = data.table;
                if (data.pagination) paginationContainer.innerHTML = data.pagination;
                if (data.showing && showingInfo) showingInfo.textContent = data.showing;

                tableContainer.style.opacity = '1';
                paginationContainer.style.opacity = '1';

                attachPaginationListeners();
                applyServiceFilter();

                isLoading = false;
            }, 150);
        })
        .catch(error => {
            console.error('Error loading page:', error);
            tableContainer.style.opacity = '1';
            paginationContainer.style.opacity = '1';
            isLoading = false;
            
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

    // Fetch recent transactions page
    function fetchRecentTransactionsPage(page = null, perPage = null) {
        if (isLoading) return;

        let url = '{{ route('operator.transactions-page') }}';
        const params = new URLSearchParams();

        const pageToUse = page !== null ? page : currentPage;
        if (pageToUse > 1) {
            params.append('page', pageToUse);
        }
        
        const perPageToUse = perPage !== null ? perPage : currentPerPage;
        if (perPageToUse !== 10) {
            params.append('per_page', perPageToUse);
        }

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
                
                if (data.current_page) currentPage = data.current_page;
                if (data.per_page) currentPerPage = data.per_page;
                
                attachPaginationListeners();
                applyServiceFilter();
            }
        })
        .catch(error => console.error('Error fetching recent transactions:', error));
    }

    // Attach pagination listeners
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
        if (!isLoading) {
            loadTransactionsPage(this.href);
        }
    }

    // Apply service filter to table rows
    function applyServiceFilter() {
        const selectedService = currentServiceFilter;
        const rows = document.querySelectorAll('#transactionsTableContainer tr');

        rows.forEach(row => {
            if (row.classList.contains('empty-state')) return;
            const service = row.getAttribute('data-service');
            row.style.display = (selectedService === 'all' || service === selectedService) ? '' : 'none';
        });
    }

    // Export PDF
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

    // Export Excel
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

    // Service filter change handler
    serviceFilter?.addEventListener('change', function() {
        currentServiceFilter = this.value;
        applyServiceFilter();
    });

    // Per page change handler
    perPageSelect?.addEventListener('change', function() {
        const newPerPage = parseInt(this.value);
        if (newPerPage !== currentPerPage) {
            currentPerPage = newPerPage;
            currentPage = 1;
            fetchRecentTransactionsPage(1, newPerPage);
        }
    });

    // Show message function
    function showMessage(text, type = 'success') {
        const messageContainer = document.getElementById('messageContainer');
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

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        attachPaginationListeners();
        applyServiceFilter();
        
        // Set the per page select to current value
        if (perPageSelect) {
            perPageSelect.value = currentPerPage;
        }
    });
</script>