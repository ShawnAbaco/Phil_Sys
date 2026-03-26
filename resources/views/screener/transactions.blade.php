{{-- resources/views/screener/transactions.blade.php --}}
<x-header title="Recent Transactions" />

<div class="app-container">
    <x-screener.sidebar />
    
    <style>
       

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

        .service-filter, .per-page-dropdown {
            padding: 8px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 30px;
            font-size: 0.875rem;
            background: white;
            cursor: pointer;
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
        }

        .btn-export-pdf { background-color: #dc2626; color: white; }
        .btn-export-excel { background-color: #059669; color: white; }

        .table-responsive {
            overflow-x: auto;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.8125rem;
            min-width: 1000px;
        }

        .table th {
            text-align: left;
            padding: 0.875rem 1rem;
            background: #f8fafc;
            font-weight: 600;
            font-size: 0.75rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .table td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
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
            font-size: 0.75rem;
        }

        .queue-number.small {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            background: #f1f5f9;
            border-radius: 0.25rem;
            font-weight: 600;
            font-family: monospace;
        }

        .time-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            background: #f1f5f9;
            border-radius: 0.25rem;
            font-size: 0.6875rem;
            white-space: nowrap;
        }

        .window-indicator {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            background: #e0f2fe;
            color: #0369a1;
            border-radius: 0.25rem;
            font-size: 0.6875rem;
        }

        .enhanced-pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            gap: 15px;
            flex-wrap: wrap;
        }





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
    </style>

    <main class="main-content">

     <!-- Page Header with Breadcrumb -->
        <div class="page-header">
            <div class="page-title1">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                </svg>
                <h1>Recent Transactions</h1>
            </div>
            <div class="breadcrumb">
                <a href="{{ route('screener.dashboard') }}">Home</a>
                <span>/</span>
                <span>Recent Transactions</span>
            </div>
        </div>


        <div class="card full-width">
            <div class="card-header">
                <h3>
                    <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V8a2 2 0 00-2-2h-5L9 4H4zm7 4a1 1 0 10-2 0v3.586l-.293-.293a1 1 0 10-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 10-1.414-1.414l-.293.293V8z" clip-rule="evenodd" />
                    </svg>
                    Recent Transactions
                </h3>
                <span class="badge" id="showingInfo">Loading...</span>
            </div>

            <div class="card-body">
                <div class="filter-bar">
                    <div class="filter-group">
                        <span>Filter by Service:</span>
                        <select id="transactionServiceFilter" class="service-filter">
                            <option value="all">All Services</option>
                            <option value="NID Registration">NID Registration</option>
                            <option value="Status Inquiry">Status Inquiry</option>
                            <option value="Updating">NID Updating</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <span>Show:</span>
                        <select id="perPageSelect" class="per-page-dropdown">
                            <option value="10">10 per page</option>
                            <option value="20">20 per page</option>
                            <option value="50">50 per page</option>
                            <option value="100">100 per page</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <button class="btn-export btn-export-pdf" id="exportPdfBtn">
                            <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                            </svg>
                            Export PDF
                        </button>
                        <button class="btn-export btn-export-excel" id="exportExcelBtn">
                            <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                            </svg>
                            Export Excel
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr><th>#</th><th>Queue #</th><th>Client</th><th>Priority</th><th>Service</th><th>Served Time</th><th>Window</th><th>Status</th></tr>
                        </thead>
                        <tbody id="transactionsTableContainer">
                            @include('screener.partials.transactions-table', ['completedTransactions' => $completedTransactions])
                        </tbody>
                    </table>
                </div>

                <div class="enhanced-pagination" id="paginationContainer">
                    @include('screener.partials.pagination-links', ['completedTransactions' => $completedTransactions])
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    let currentPage = {{ $completedTransactions->currentPage() }};
    let currentPerPage = {{ $completedTransactions->perPage() }};
    let currentServiceFilter = 'all';
    let isLoading = false;

    function loadTransactionsPage(url) {
        if (isLoading) return;
        isLoading = true;
        
        const separator = url.includes('?') ? '&' : '?';
        const fetchUrl = url + separator + '_=' + new Date().getTime();
        
        document.getElementById('transactionsTableContainer').style.opacity = '0';
        document.getElementById('paginationContainer').style.opacity = '0';
        
        fetch(fetchUrl, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                setTimeout(() => {
                    document.getElementById('transactionsTableContainer').innerHTML = data.table;
                    document.getElementById('paginationContainer').innerHTML = data.pagination;
                    document.getElementById('showingInfo').textContent = data.showing;
                    document.getElementById('transactionsTableContainer').style.opacity = '1';
                    document.getElementById('paginationContainer').style.opacity = '1';
                    attachPaginationListeners();
                    applyServiceFilter();
                    isLoading = false;
                }, 150);
            }
        })
        .catch(error => { console.error('Error:', error); isLoading = false; });
    }

    function attachPaginationListeners() {
        document.querySelectorAll('.pagination-nav-btn:not(.disabled), .pagination-arrow:not(.disabled), .page-number:not(.active)').forEach(link => {
            link.removeEventListener('click', handlePaginationClick);
            link.addEventListener('click', handlePaginationClick);
        });
    }

    function handlePaginationClick(e) {
        e.preventDefault();
        if (!isLoading) loadTransactionsPage(this.href);
    }

    function applyServiceFilter() {
        const selectedService = currentServiceFilter;
        document.querySelectorAll('#transactionsTableContainer tr').forEach(row => {
            if (row.classList.contains('empty-state')) return;
            const service = row.getAttribute('data-service');
            row.style.display = (selectedService === 'all' || service === selectedService) ? '' : 'none';
        });
    }

    function fetchTransactionsPage(page = null, perPage = null) {
        if (isLoading) return;
        let url = '{{ route('appointment.transactions-page') }}';
        const params = new URLSearchParams();
        const pageToUse = page !== null ? page : currentPage;
        if (pageToUse > 1) params.append('page', pageToUse);
        const perPageToUse = perPage !== null ? perPage : currentPerPage;
        if (perPageToUse !== 10) params.append('per_page', perPageToUse);
        const queryString = params.toString();
        if (queryString) url += '?' + queryString;
        url += (url.includes('?') ? '&' : '?') + '_=' + new Date().getTime();
        
        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('transactionsTableContainer').innerHTML = data.table;
                document.getElementById('paginationContainer').innerHTML = data.pagination;
                document.getElementById('showingInfo').textContent = data.showing;
                if (data.current_page) currentPage = data.current_page;
                if (data.per_page) currentPerPage = data.per_page;
                attachPaginationListeners();
                applyServiceFilter();
            }
        })
        .catch(error => console.error('Error:', error));
    }

    document.addEventListener('DOMContentLoaded', function() {
        attachPaginationListeners();
        applyServiceFilter();
        
        document.getElementById('transactionServiceFilter')?.addEventListener('change', function() {
            currentServiceFilter = this.value;
            applyServiceFilter();
        });
        
        document.getElementById('perPageSelect')?.addEventListener('change', function() {
            const newPerPage = parseInt(this.value);
            if (newPerPage !== currentPerPage) {
                currentPerPage = newPerPage;
                currentPage = 1;
                fetchTransactionsPage(1, newPerPage);
            }
        });
        
        document.getElementById('exportPdfBtn')?.addEventListener('click', () => window.location.href = '{{ route('appointment.export.pdf') }}');
        document.getElementById('exportExcelBtn')?.addEventListener('click', () => window.location.href = '{{ route('appointment.export.excel') }}');
    });
</script>