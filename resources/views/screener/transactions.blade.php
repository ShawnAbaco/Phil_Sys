{{-- resources/views/screener/transactions.blade.php --}}
<x-header title="Recent Transactions" />

<div class="app-container">
    <x-screener.sidebar />

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