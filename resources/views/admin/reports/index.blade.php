@extends('layouts.admin')

@section('title', 'Reports')
@section('pageTitle', 'Reports & Analytics')
@section('pageSubtitle', 'Generate and view system reports')

@php $activeMenu = 'reports'; @endphp

@section('content')
<!-- Report Type Cards -->
<div class="reports-grid">
    <div class="report-card" onclick="generateReport('daily')">
        <div class="report-icon" style="background: var(--psa-blue-light); color: var(--psa-blue);">
            <svg viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
            </svg>
        </div>
        <h3>Daily Report</h3>
        <p>View appointments and transactions for today</p>
        <div class="report-meta">
            <span>Last generated: Today, 8:00 AM</span>
        </div>
    </div>

    <div class="report-card" onclick="generateReport('weekly')">
        <div class="report-icon" style="background: var(--psa-yellow-light); color: #b45309;">
            <svg viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
            </svg>
        </div>
        <h3>Weekly Summary</h3>
        <p>Weekly trends and performance metrics</p>
        <div class="report-meta">
            <span>Mar 1 - Mar 7, 2024</span>
        </div>
    </div>

    <div class="report-card" onclick="generateReport('monthly')">
        <div class="report-icon" style="background: var(--success); color: white;">
            <svg viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V8a2 2 0 00-2-2h-5L9 4H4zm7 4a1 1 0 10-2 0v3.586l-.293-.293a1 1 0 10-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 10-1.414-1.414l-.293.293V8z" clip-rule="evenodd" />
            </svg>
        </div>
        <h3>Monthly Report</h3>
        <p>Complete monthly analytics and statistics</p>
        <div class="report-meta">
            <span>February 2024</span>
        </div>
    </div>

    <div class="report-card" onclick="generateReport('custom')">
        <div class="report-icon" style="background: var(--psa-red-light); color: var(--psa-red);">
            <svg viewBox="0 0 20 20" fill="currentColor">
                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" />
            </svg>
        </div>
        <h3>Custom Report</h3>
        <p>Generate report with custom date range</p>
        <div class="report-meta">
            <span>Select your own dates</span>
        </div>
    </div>
</div>

<!-- Custom Report Builder (Hidden by default) -->
<div class="report-builder" id="customReportBuilder" style="display: none;">
    <h3>Custom Report Builder</h3>
    <div class="builder-form">
        <div class="form-row">
            <div class="form-group">
                <label>Report Name</label>
                <input type="text" id="reportName" placeholder="e.g., Q1 Performance Report">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Date Range</label>
                <div class="date-range-group">
                    <input type="date" id="customStartDate" class="date-input">
                    <span>to</span>
                    <input type="date" id="customEndDate" class="date-input">
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Report Type</label>
                <select id="reportType">
                    <option value="summary">Summary Report</option>
                    <option value="detailed">Detailed Transactions</option>
                    <option value="performance">Performance Analytics</option>
                    <option value="user-activity">User Activity</option>
                </select>
            </div>
            <div class="form-group">
                <label>Format</label>
                <select id="reportFormat">
                    <option value="pdf">PDF</option>
                    <option value="excel">Excel</option>
                    <option value="csv">CSV</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Include Data</label>
                <div class="checkbox-group">
                    <label><input type="checkbox" checked> Appointments</label>
                    <label><input type="checkbox" checked> User Activity</label>
                    <label><input type="checkbox" checked> Queue Statistics</label>
                    <label><input type="checkbox" checked> Performance Metrics</label>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <button class="btn btn-outline" onclick="hideCustomBuilder()">Cancel</button>
            <button class="btn btn-primary" onclick="generateCustomReport()">Generate Report</button>
        </div>
    </div>
</div>

<!-- Recent Reports -->
<div class="recent-reports">
    <h3>Recent Reports</h3>
    <div class="reports-list">
        <div class="report-item">
            <div class="report-item-icon">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V8a2 2 0 00-2-2h-5L9 4H4z" />
                </svg>
            </div>
            <div class="report-item-info">
                <h4>Daily Report - March 6, 2024</h4>
                <p>Generated 2 hours ago • 2.3 MB</p>
            </div>
            <div class="report-item-actions">
                <button class="btn btn-outline btn-sm">Download</button>
                <button class="btn btn-outline btn-sm">View</button>
            </div>
        </div>
        <div class="report-item">
            <div class="report-item-icon">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V8a2 2 0 00-2-2h-5L9 4H4z" />
                </svg>
            </div>
            <div class="report-item-info">
                <h4>Weekly Summary - Feb 26 - Mar 3, 2024</h4>
                <p>Generated yesterday • 4.1 MB</p>
            </div>
            <div class="report-item-actions">
                <button class="btn btn-outline btn-sm">Download</button>
                <button class="btn btn-outline btn-sm">View</button>
            </div>
        </div>
        <div class="report-item">
            <div class="report-item-icon">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V8a2 2 0 00-2-2h-5L9 4H4z" />
                </svg>
            </div>
            <div class="report-item-info">
                <h4>Monthly Report - February 2024</h4>
                <p>Generated 3 days ago • 8.7 MB</p>
            </div>
            <div class="report-item-actions">
                <button class="btn btn-outline btn-sm">Download</button>
                <button class="btn btn-outline btn-sm">View</button>
            </div>
        </div>
    </div>
</div>

<!-- Performance Charts -->
<div class="charts-row" style="margin-top: 30px;">
    <div class="chart-card">
        <div class="chart-header">
            <h3>Appointment Trends</h3>
            <select class="chart-period" id="trendPeriod">
                <option>Last 7 days</option>
                <option>Last 30 days</option>
                <option>Last 3 months</option>
            </select>
        </div>
<canvas id="trendsChart" style="height: 300px; width: 100%; max-width: 800px; max-height: 400px;"></canvas>
    </div>

    <div class="chart-card">
        <div class="chart-header">
            <h3>Service Distribution</h3>
        </div>
        <canvas id="distributionChart" style="height: 300px; width: 100%; max-width: 800px; max-height: 400px;"></canvas>
    </div>
</div>

@push('styles')
<style>
    .reports-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .report-card {
        background: white;
        border-radius: 20px;
        padding: 24px;
        border: 2px solid var(--gray-200);
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .report-card:hover {
        transform: translateY(-4px);
        border-color: var(--psa-yellow);
        box-shadow: 0 12px 30px rgba(0, 56, 168, 0.1);
    }
    
    .report-icon {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        margin-bottom: 20px;
    }
    
    .report-card h3 {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--gray-800);
        margin-bottom: 8px;
    }
    
    .report-card p {
        font-size: 0.9rem;
        color: var(--gray-500);
        margin-bottom: 15px;
        line-height: 1.4;
    }
    
    .report-meta {
        font-size: 0.8rem;
        color: var(--gray-400);
        padding-top: 12px;
        border-top: 1px solid var(--gray-200);
    }
    
    .report-builder {
        background: white;
        border-radius: 20px;
        padding: 24px;
        border: 2px solid var(--gray-200);
        margin-bottom: 30px;
    }
    
    .report-builder h3 {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--gray-800);
        margin-bottom: 20px;
    }
    
    .date-range-group {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .date-range-group span {
        color: var(--gray-500);
    }
    
    .checkbox-group {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        padding: 8px 0;
    }
    
    .checkbox-group label {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.9rem;
        color: var(--gray-700);
        cursor: pointer;
    }
    
    .checkbox-group input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }
    
    .recent-reports {
        background: white;
        border-radius: 20px;
        padding: 24px;
        border: 2px solid var(--gray-200);
        margin-bottom: 30px;
    }
    
    .recent-reports h3 {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--gray-800);
        margin-bottom: 20px;
    }
    
    .reports-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    
    .report-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 12px;
        border-radius: 12px;
        background: var(--gray-50);
        transition: background 0.2s ease;
    }
    
    .report-item:hover {
        background: var(--gray-100);
    }
    
    .report-item-icon {
        width: 40px;
        height: 40px;
        background: var(--psa-blue-light);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--psa-blue);
    }
    
    .report-item-info {
        flex: 1;
    }
    
    .report-item-info h4 {
        font-size: 1rem;
        font-weight: 600;
        color: var(--gray-800);
        margin-bottom: 4px;
    }
    
    .report-item-info p {
        font-size: 0.8rem;
        color: var(--gray-500);
    }
    
    .report-item-actions {
        display: flex;
        gap: 8px;
    }
    
    .btn-sm {
        padding: 4px 12px;
        font-size: 0.8rem;
    }
    
    @media (max-width: 1200px) {
        .reports-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .reports-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function generateReport(type) {
        if (type === 'custom') {
            document.getElementById('customReportBuilder').style.display = 'block';
        } else {
            showLoading();
            // Simulate report generation
            setTimeout(() => {
                hideLoading();
                window.location.href = `{{ url('admin/reports') }}/${type}`;
            }, 1500);
        }
    }
    
    function hideCustomBuilder() {
        document.getElementById('customReportBuilder').style.display = 'none';
    }
    
    function generateCustomReport() {
        const name = document.getElementById('reportName').value || 'Custom Report';
        const start = document.getElementById('customStartDate').value;
        const end = document.getElementById('customEndDate').value;
        const type = document.getElementById('reportType').value;
        const format = document.getElementById('reportFormat').value;
        
        if (!start || !end) {
            alert('Please select start and end dates');
            return;
        }
        
        showLoading();
        setTimeout(() => {
            hideLoading();
            window.location.href = `{{ url('admin/reports/custom') }}?start=${start}&end=${end}&type=${type}&format=${format}`;
            hideCustomBuilder();
        }, 2000);
    }
    
    // Initialize charts
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
</script>
@endpush
@endsection