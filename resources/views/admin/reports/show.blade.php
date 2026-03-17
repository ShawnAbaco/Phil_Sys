@extends('layouts.admin')

@section('title', 'Report Details')
@section('pageTitle', 'Report Details')
@section('pageSubtitle', 'View detailed report information')

@php $activeMenu = 'reports'; @endphp

@section('content')
<div class="report-header">
    <div class="report-title-section">
        <h2>{{ $reportName ?? 'Daily Report' }}</h2>
        <p>Generated on {{ now()->format('F d, Y h:i A') }}</p>
    </div>
    <div class="report-actions">
        <button class="btn btn-outline" onclick="window.print()">
            <svg viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
            </svg>
            Print
        </button>
        <button class="btn btn-primary" onclick="exportReport()">
            <svg viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
            Export
        </button>
    </div>
</div>

<!-- Summary Cards -->
<div class="summary-cards">
    <div class="summary-card">
        <div class="summary-icon" style="background: var(--psa-blue-light);">
            <svg viewBox="0 0 20 20" fill="currentColor">
                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
            </svg>
        </div>
        <div>
            <div class="summary-label">Total Appointments</div>
            <div class="summary-value">{{ $totalAppointments ?? 156 }}</div>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-icon" style="background: var(--psa-yellow-light);">
            <svg viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
            </svg>
        </div>
        <div>
            <div class="summary-label">Pending</div>
            <div class="summary-value">{{ $pendingAppointments ?? 42 }}</div>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-icon" style="background: var(--success); color: white;">
            <svg viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
        </div>
        <div>
            <div class="summary-label">Completed</div>
            <div class="summary-value">{{ $completedAppointments ?? 114 }}</div>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-icon" style="background: var(--psa-red-light);">
            <svg viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
        </div>
        <div>
            <div class="summary-label">Issues</div>
            <div class="summary-value">{{ $issuesCount ?? 3 }}</div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="charts-section">
    <div class="chart-card">
        <div class="chart-header">
            <h3>Appointment Trends</h3>
            <select class="chart-period" id="trendPeriod">
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
            </select>
        </div>
        <canvas id="trendsChart" style="height: 300px; width: 100%;"></canvas>
    </div>
    
    <div class="chart-card">
        <div class="chart-header">
            <h3>Service Distribution</h3>
        </div>
        <canvas id="distributionChart" style="height: 300px; width: 100%;"></canvas>
    </div>
</div>

<!-- Detailed Data Table -->
<div class="data-table-card">
    <div class="data-table-header">
        <h3>Detailed Transactions</h3>
        <div class="data-table-filters">
            <input type="text" placeholder="Search..." class="table-search" id="tableSearch">
            <select class="table-filter" id="serviceFilter">
                <option value="">All Services</option>
                <option value="NID Registration">NID Registration</option>
                <option value="Status Inquiry">Status Inquiry</option>
                <option value="Updating">Updating</option>
            </select>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Queue #</th>
                    <th>Client Name</th>
                    <th>Service</th>
                    <th>Date/Time</th>
                    <th>Window</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="reportTableBody">
                @forelse($transactions ?? [] as $transaction)
                <tr>
                    <td>#{{ $transaction->id }}</td>
                    <td><span class="queue-badge">{{ $transaction->q_id }}</span></td>
                    <td>{{ $transaction->lname }}, {{ $transaction->fname }}</td>
                    <td>{{ $transaction->queue_for }}</td>
                    <td>{{ \Carbon\Carbon::parse($transaction->date)->format('M d, Y h:i A') }}</td>
                    <td>{{ $transaction->window_num ? 'Window '.$transaction->window_num : '—' }}</td>
                    <td>
                        <span class="status-badge status-{{ $transaction->time_catered ? 'completed' : 'pending' }}">
                            {{ $transaction->time_catered ? 'Completed' : 'Pending' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="empty-state">
                        No transactions found for this period
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="pagination-container">
        {{ $transactions->links() ?? '' }}
    </div>
</div>

@push('styles')
<style>
    .report-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        background: white;
        border-radius: 16px;
        padding: 20px;
        border: 2px solid var(--gray-200);
    }
    
    .report-title-section h2 {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--psa-blue);
        margin-bottom: 4px;
    }
    
    .report-title-section p {
        color: var(--gray-500);
        font-size: 0.9rem;
    }
    
    .report-actions {
        display: flex;
        gap: 10px;
    }
    
    .summary-cards {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 24px;
    }
    
    .summary-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        border: 2px solid var(--gray-200);
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .summary-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--psa-blue);
        font-size: 24px;
    }
    
    .summary-label {
        font-size: 0.85rem;
        color: var(--gray-500);
        margin-bottom: 4px;
    }
    
    .summary-value {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--gray-800);
        line-height: 1.2;
    }
    
    .charts-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 24px;
    }
    
    .data-table-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        border: 2px solid var(--gray-200);
    }
    
    .data-table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .data-table-header h3 {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--gray-800);
    }
    
    .data-table-filters {
        display: flex;
        gap: 10px;
    }
    
    .table-search {
        padding: 8px 12px;
        border: 2px solid var(--gray-200);
        border-radius: 8px;
        width: 200px;
    }
    
    .table-filter {
        padding: 8px 12px;
        border: 2px solid var(--gray-200);
        border-radius: 8px;
    }
    
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .data-table th {
        text-align: left;
        padding: 12px 16px;
        background: var(--gray-50);
        color: var(--gray-600);
        font-weight: 600;
        font-size: 0.85rem;
        border-bottom: 2px solid var(--gray-200);
    }
    
    .data-table td {
        padding: 12px 16px;
        border-bottom: 1px solid var(--gray-200);
    }
    
    .queue-badge {
        display: inline-block;
        padding: 4px 8px;
        background: var(--psa-blue-light);
        color: var(--psa-blue);
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
    }
    
    @media (max-width: 1200px) {
        .summary-cards {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .charts-section {
            grid-template-columns: 1fr;
        }
    }
    
    @media (max-width: 768px) {
        .report-header {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }
        
        .summary-cards {
            grid-template-columns: 1fr;
        }
        
        .data-table-header {
            flex-direction: column;
            gap: 10px;
        }
        
        .data-table-filters {
            width: 100%;
        }
        
        .table-search {
            flex: 1;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Trends Chart
        const trendsCtx = document.getElementById('trendsChart')?.getContext('2d');
        if (trendsCtx) {
            new Chart(trendsCtx, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Appointments',
                        data: [65, 72, 80, 68, 85, 45, 38],
                        borderColor: '#0038A8',
                        backgroundColor: 'rgba(0, 56, 168, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }
        
        // Distribution Chart
        const distCtx = document.getElementById('distributionChart')?.getContext('2d');
        if (distCtx) {
            new Chart(distCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Registration', 'Status Inquiry', 'Updating', 'Others'],
                    datasets: [{
                        data: [45, 25, 20, 10],
                        backgroundColor: [
                            '#0038A8',
                            '#CE1126',
                            '#FCD116',
                            '#10b981'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    });
    
    function exportReport() {
        showLoading();
        setTimeout(() => {
            hideLoading();
            Swal.fire({
                title: 'Export Started',
                text: 'Your report is being generated and will download shortly.',
                icon: 'success',
                confirmButtonColor: '#0038A8'
            });
        }, 1500);
    }
    
    // Table search
    document.getElementById('tableSearch')?.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#reportTableBody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
    
    // Service filter
    document.getElementById('serviceFilter')?.addEventListener('change', function() {
        const filterValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('#reportTableBody tr');
        
        rows.forEach(row => {
            const service = row.querySelector('td:nth-child(4)')?.textContent.toLowerCase() || '';
            if (!filterValue || service.includes(filterValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
@endpush
@endsection