{{-- resources/views/screener/reports.blade.php --}}
<x-header title="Reports" />

<div class="app-container">
    <x-screener.sidebar />
    
    <style>
     
        
        .report-cards-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 2rem; }
        .report-stat-card { background: white; border-radius: 1rem; padding: 1.25rem; border: 1px solid #e2e8f0; transition: all 0.3s ease; }
        .report-stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
        .report-stat-value { font-size: 2rem; font-weight: 700; color: #0f172a; }
        .report-stat-label { font-size: 0.75rem; font-weight: 500; color: #64748b; text-transform: uppercase; }
        
        .filter-section { background: white; border-radius: 1rem; padding: 1.5rem; margin-bottom: 2rem; border: 1px solid #e2e8f0; }
        .filter-row { display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end; }
        .filter-group { flex: 1; min-width: 150px; }
        .filter-group label { display: block; font-size: 0.75rem; font-weight: 500; color: #64748b; margin-bottom: 0.5rem; text-transform: uppercase; }
        .filter-group input, .filter-group select { width: 100%; padding: 0.625rem 0.875rem; border: 1px solid #cbd5e1; border-radius: 0.5rem; }
        .btn-filter { background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white; border: none; padding: 0.625rem 1.5rem; border-radius: 0.5rem; cursor: pointer; }
        .btn-reset { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; padding: 0.625rem 1.5rem; border-radius: 0.5rem; cursor: pointer; }
        
        .charts-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; margin-bottom: 2rem; }
        .chart-card { background: white; border-radius: 1rem; padding: 1.5rem; border: 1px solid #e2e8f0; }
        .chart-container { height: 300px; position: relative; }
        
        .report-table-container { background: white; border-radius: 1rem; border: 1px solid #e2e8f0; overflow: hidden; }
        .report-table-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; background: #f8fafc; }
        .btn-export { padding: 0.5rem 1rem; border-radius: 0.5rem; font-size: 0.75rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; border: none; }
        .btn-export-pdf { background: #dc2626; color: white; }
        .btn-export-excel { background: #059669; color: white; }
        .btn-export-print { background: #6b7280; color: white; }
        
        .report-table { width: 100%; border-collapse: collapse; }
        .report-table th { text-align: left; padding: 0.875rem 1rem; background: #f8fafc; font-weight: 600; font-size: 0.75rem; border-bottom: 1px solid #e2e8f0; }
        .report-table td { padding: 0.75rem 1rem; border-bottom: 1px solid #e2e8f0; font-size: 0.875rem; }
        
        @media (max-width: 1024px) { .report-cards-grid { grid-template-columns: repeat(2, 1fr); } .charts-grid { grid-template-columns: 1fr; } }
        @media print { .sidebar, .filter-section, .btn-export { display: none !important; } }
    </style>

    <main class="main-content">
        <div class="report-header" style="margin-bottom: 2rem;">
            <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">Transaction Reports</h2>
            <p style="color: #64748b;">View and export your transaction history and performance metrics</p>
        </div>

        <div class="report-cards-grid">
            <div class="report-stat-card"><div class="report-stat-value" id="totalIssued">0</div><div class="report-stat-label">Total Issued</div></div>
            <div class="report-stat-card"><div class="report-stat-value" id="totalCompleted">0</div><div class="report-stat-label">Completed</div></div>
            <div class="report-stat-card"><div class="report-stat-value" id="totalCancelled">0</div><div class="report-stat-label">Cancelled</div></div>
            <div class="report-stat-card"><div class="report-stat-value" id="totalPending">0</div><div class="report-stat-label">Pending</div></div>
        </div>

        <div class="filter-section">
            <div class="filter-row">
                <div class="filter-group"><label>Date Range</label><select id="dateRange"><option value="today">Today</option><option value="yesterday">Yesterday</option><option value="this_week">This Week</option><option value="last_week">Last Week</option><option value="this_month" selected>This Month</option><option value="last_month">Last Month</option><option value="custom">Custom Range</option></select></div>
                <div class="filter-group" id="customDateRange" style="display:none;"><label>From Date</label><input type="date" id="startDate"></div>
                <div class="filter-group" id="customDateRangeEnd" style="display:none;"><label>To Date</label><input type="date" id="endDate"></div>
                <div class="filter-group"><label>Service Type</label><select id="serviceType"><option value="all">All Services</option><option value="NID Registration">NID Registration</option><option value="Status Inquiry">Status Inquiry</option><option value="Updating">NID Updating</option></select></div>
                <div class="filter-group"><button class="btn-filter" id="applyFilterBtn">Apply Filters</button></div>
                <div class="filter-group"><button class="btn-reset" id="resetFilterBtn">Reset</button></div>
            </div>
        </div>

        <div class="charts-grid">
            <div class="chart-card"><div class="chart-title">Daily Transactions</div><div class="chart-container"><canvas id="dailyChart"></canvas></div></div>
            <div class="chart-card"><div class="chart-title">Status Distribution</div><div class="chart-container"><canvas id="statusChart"></canvas></div></div>
        </div>

        <div class="report-table-container">
            <div class="report-table-header"><h4>Transaction Details</h4><div><button class="btn-export btn-export-pdf" id="exportReportPdfBtn">Export PDF</button><button class="btn-export btn-export-excel" id="exportReportExcelBtn">Export Excel</button><button class="btn-export btn-export-print" id="printReportBtn">Print</button></div></div>
            <div style="overflow-x: auto;"><table class="report-table"><thead><tr><th>Date</th><th>Queue #</th><th>Client Name</th><th>Service</th><th>Priority</th><th>Status</th><th>Window</th><th>Time Served</th></tr></thead><tbody id="reportTableBody"><tr><td colspan="8" class="empty-state">Loading...</td></tr></tbody></table></div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let dailyChart = null, statusChart = null;
    let currentFilters = { dateRange: 'this_month', startDate: '', endDate: '', serviceType: 'all' };

    function initCharts() {
        dailyChart = new Chart(document.getElementById('dailyChart'), { type: 'line', data: { labels: [], datasets: [{ label: 'Issued', data: [], borderColor: '#2563eb', backgroundColor: 'rgba(37,99,235,0.1)', tension: 0.4, fill: true }, { label: 'Completed', data: [], borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,0.1)', tension: 0.4, fill: true }] }, options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } } });
        statusChart = new Chart(document.getElementById('statusChart'), { type: 'doughnut', data: { labels: ['Pending', 'Serving', 'Completed', 'Cancelled', 'No Show'], datasets: [{ data: [0,0,0,0,0], backgroundColor: ['#f59e0b', '#3b82f6', '#10b981', '#ef4444', '#6b7280'] }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } } });
    }

    function fetchReportData() {
        const params = new URLSearchParams({ date_range: currentFilters.dateRange, start_date: currentFilters.startDate, end_date: currentFilters.endDate, service_type: currentFilters.serviceType });
        fetch(`{{ route('screener.reports.data') }}?${params.toString()}`, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('totalIssued').textContent = data.stats.total_issued;
                document.getElementById('totalCompleted').textContent = data.stats.completed;
                document.getElementById('totalCancelled').textContent = data.stats.cancelled;
                document.getElementById('totalPending').textContent = data.stats.pending;
                if (dailyChart && data.charts.daily) { dailyChart.data.labels = data.charts.daily.labels; dailyChart.data.datasets[0].data = data.charts.daily.issued; dailyChart.data.datasets[1].data = data.charts.daily.completed; dailyChart.update(); }
                if (statusChart && data.charts.status) { statusChart.data.datasets[0].data = data.charts.status; statusChart.update(); }
                updateTable(data.transactions);
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function updateTable(transactions) {
        const tbody = document.getElementById('reportTableBody');
        if (!transactions || transactions.length === 0) { tbody.innerHTML = '<tr><td colspan="8" class="empty-state">No transactions found</td></tr>'; return; }
        let html = '';
        transactions.forEach(t => { html += `<tr><td>${t.date}</td><td><span class="queue-number small">${t.q_id}</span></td><td>${t.client_name}</td><td>${t.service}</td><td><span class="priority-badge priority-${t.priority_type}">${t.priority_type.toUpperCase()}</span></td><td><span class="status-badge ${t.status}">${t.status}</span></td><td>${t.window_num || '—'}</td><td>${t.served_time}</td></tr>`; });
        tbody.innerHTML = html;
    }

    function toggleDateRange() { const isCustom = document.getElementById('dateRange').value === 'custom'; document.getElementById('customDateRange').style.display = isCustom ? 'block' : 'none'; document.getElementById('customDateRangeEnd').style.display = isCustom ? 'block' : 'none'; }
    function applyFilters() { currentFilters.dateRange = document.getElementById('dateRange').value; if (currentFilters.dateRange === 'custom') { currentFilters.startDate = document.getElementById('startDate').value; currentFilters.endDate = document.getElementById('endDate').value; } currentFilters.serviceType = document.getElementById('serviceType').value; fetchReportData(); }
    function resetFilters() { document.getElementById('dateRange').value = 'this_month'; document.getElementById('serviceType').value = 'all'; document.getElementById('startDate').value = ''; document.getElementById('endDate').value = ''; toggleDateRange(); applyFilters(); }
    function exportPDF() { const params = new URLSearchParams({ date_range: currentFilters.dateRange, start_date: currentFilters.startDate, end_date: currentFilters.endDate, service_type: currentFilters.serviceType }); window.location.href = '{{ route('screener.reports.export.pdf') }}?' + params.toString(); }
    function exportExcel() { const params = new URLSearchParams({ date_range: currentFilters.dateRange, start_date: currentFilters.startDate, end_date: currentFilters.endDate, service_type: currentFilters.serviceType }); window.location.href = '{{ route('screener.reports.export.excel') }}?' + params.toString(); }
    function printReport() { window.print(); }

    document.addEventListener('DOMContentLoaded', function() { initCharts(); fetchReportData(); document.getElementById('dateRange').addEventListener('change', toggleDateRange); document.getElementById('applyFilterBtn').addEventListener('click', applyFilters); document.getElementById('resetFilterBtn').addEventListener('click', resetFilters); document.getElementById('exportReportPdfBtn').addEventListener('click', exportPDF); document.getElementById('exportReportExcelBtn').addEventListener('click', exportExcel); document.getElementById('printReportBtn').addEventListener('click', printReport); toggleDateRange(); });
</script>