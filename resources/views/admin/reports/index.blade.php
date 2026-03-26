@extends('layouts.admin')

@section('title', 'Reports')
@section('pageTitle', 'Reports & Analytics')
@section('pageSubtitle', 'Generate, view, and export comprehensive system reports')

@php $activeMenu = 'reports'; @endphp

@section('content')
    <!-- Include CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin/reports.css') }}">

    <!-- Report Header with Statistics -->
    <div class="report-header-modern">
        <div class="report-title-modern">
            <h2>Reports Dashboard</h2>
            <p>Overview of system performance and appointment analytics</p>
        </div>
        <div class="stats-row-modern">
            <div class="stat-card-modern">
                <div class="stat-label-modern">Total Appointments</div>
                <div class="stat-value-modern">{{ $totalAppointments ?? 0 }}</div>
            </div>
            <div class="stat-card-modern">
                <div class="stat-label-modern">Completed</div>
                <div class="stat-value-modern">{{ $completedAppointments ?? 0 }}</div>
            </div>
            <div class="stat-card-modern">
                <div class="stat-label-modern">Pending</div>
                <div class="stat-value-modern">{{ $pendingAppointments ?? 0 }}</div>
            </div>
            <div class="stat-card-modern">
                <div class="stat-label-modern">Average Wait Time</div>
                <div class="stat-value-modern">{{ $avgWaitTime ?? '12' }} min</div>
            </div>
        </div>
    </div>

    <!-- Report Type Cards -->
    <div class="reports-grid-modern">
        <div class="report-card-modern" onclick="generateReport('daily')">
            <div class="report-icon-modern" style="background: rgba(0, 56, 168, 0.1); color: var(--primary-blue);">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <h3>Daily Report</h3>
            <p>View appointments and transactions for today with detailed breakdown</p>
            <div class="report-meta-modern">
                <span>{{ now()->format('F d, Y') }}</span>
                <span class="report-badge">Today's Summary</span>
            </div>
        </div>

        <div class="report-card-modern" onclick="generateReport('weekly')">
            <div class="report-icon-modern" style="background: rgba(252, 209, 22, 0.1); color: #b45309;">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <h3>Weekly Summary</h3>
            <p>Weekly trends, performance metrics, and comparative analysis</p>
            <div class="report-meta-modern">
                <span>{{ now()->startOfWeek()->format('M d') }} - {{ now()->endOfWeek()->format('M d, Y') }}</span>
                <span class="report-badge">7 Days</span>
            </div>
        </div>

        <div class="report-card-modern" onclick="generateReport('monthly')">
            <div class="report-icon-modern" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V8a2 2 0 00-2-2h-5L9 4H4zm7 4a1 1 0 10-2 0v3.586l-.293-.293a1 1 0 10-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 10-1.414-1.414l-.293.293V8z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <h3>Monthly Report</h3>
            <p>Complete monthly analytics, statistics, and performance insights</p>
            <div class="report-meta-modern">
                <span>{{ now()->format('F Y') }}</span>
                <span class="report-badge">Full Month</span>
            </div>
        </div>

        <div class="report-card-modern" onclick="generateReport('custom')">
            <div class="report-icon-modern" style="background: rgba(206, 17, 38, 0.1); color: var(--primary-red);">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                    <path fill-rule="evenodd"
                        d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <h3>Custom Report</h3>
            <p>Generate custom reports with specific date range and filters</p>
            <div class="report-meta-modern">
                <span>Flexible Range</span>
                <span class="report-badge">Advanced</span>
            </div>
        </div>
    </div>

    <!-- Custom Report Builder (Hidden by default) -->
    <div class="report-builder-modern" id="customReportBuilder" style="display: none;">
        <div class="builder-header">
            <h3>Custom Report Builder</h3>
            <button class="builder-close" onclick="hideCustomBuilder()">&times;</button>
        </div>
        <div class="builder-form-modern">
            <div class="form-row-modern">
                <div class="form-group-modern">
                    <label>Report Name</label>
                    <input type="text" id="reportName" placeholder="e.g., Q1 Performance Report 2024">
                </div>
            </div>
            <div class="form-row-modern">
                <div class="form-group-modern">
                    <label>Date Range</label>
                    <div class="date-range-group-modern">
                        <input type="date" id="customStartDate" class="date-input">
                        <span>→</span>
                        <input type="date" id="customEndDate" class="date-input">
                    </div>
                </div>
            </div>
            <div class="form-row-modern">
                <div class="form-group-modern">
                    <label>Report Type</label>
                    <select id="reportType">
                        <option value="summary">Summary Report</option>
                        <option value="detailed">Detailed Transactions</option>
                        <option value="performance">Performance Analytics</option>
                        <option value="user-activity">User Activity Log</option>
                    </select>
                </div>
                <div class="form-group-modern">
                    <label>Export Format</label>
                    <select id="reportFormat">
                        <option value="pdf">PDF Document</option>
                        <option value="excel">Excel Spreadsheet</option>
                        <option value="csv">CSV File</option>
                    </select>
                </div>
            </div>
            <div class="form-row-modern">
                <div class="form-group-modern">
                    <label>Include Data</label>
                    <div class="checkbox-group-modern">
                        <label><input type="checkbox" checked> Appointments</label>
                        <label><input type="checkbox" checked> User Activity</label>
                        <label><input type="checkbox" checked> Queue Statistics</label>
                        <label><input type="checkbox" checked> Performance Metrics</label>
                        <label><input type="checkbox"> Window Efficiency</label>
                    </div>
                </div>
            </div>
            <div class="form-actions-modern">
                <button class="btn-modern btn-outline-modern" onclick="hideCustomBuilder()">Cancel</button>
                <button class="btn-modern btn-primary-modern" onclick="generateCustomReport()">Generate Report</button>
            </div>
        </div>
    </div>

    <!-- Recent Reports -->
    <div class="recent-reports-modern">
        <h3>Recent Reports</h3>
        <div class="reports-list-modern">
            <div class="report-item-modern">
                <div class="report-item-info-modern">
                    <div class="report-item-icon-modern">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V8a2 2 0 00-2-2h-5L9 4H4z" />
                        </svg>
                    </div>
                    <div class="report-item-details-modern">
                        <h4>Daily Report - {{ now()->format('F d, Y') }}</h4>
                        <p>Generated today • 2.3 MB • PDF</p>
                    </div>
                </div>
                <div class="report-item-actions-modern">
                    <button class="btn-modern btn-outline-modern btn-sm-modern" onclick="downloadReport('daily')">
                        <svg viewBox="0 0 20 20" fill="currentColor" width="14" height="14"
                            style="display: inline; margin-right: 4px;">
                            <path fill-rule="evenodd"
                                d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                        Download
                    </button>
                    <button class="btn-modern btn-outline-modern btn-sm-modern"
                        onclick="viewReport('daily')">View</button>
                </div>
            </div>
            <div class="report-item-modern">
                <div class="report-item-info-modern">
                    <div class="report-item-icon-modern">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V8a2 2 0 00-2-2h-5L9 4H4z" />
                        </svg>
                    </div>
                    <div class="report-item-details-modern">
                        <h4>Weekly Summary - {{ now()->subWeek()->startOfWeek()->format('M d') }} -
                            {{ now()->subWeek()->endOfWeek()->format('M d, Y') }}</h4>
                        <p>Generated yesterday • 4.1 MB • Excel</p>
                    </div>
                </div>
                <div class="report-item-actions-modern">
                    <button class="btn-modern btn-outline-modern btn-sm-modern"
                        onclick="downloadReport('weekly')">Download</button>
                    <button class="btn-modern btn-outline-modern btn-sm-modern"
                        onclick="viewReport('weekly')">View</button>
                </div>
            </div>
            <div class="report-item-modern">
                <div class="report-item-info-modern">
                    <div class="report-item-icon-modern">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V8a2 2 0 00-2-2h-5L9 4H4z" />
                        </svg>
                    </div>
                    <div class="report-item-details-modern">
                        <h4>Monthly Report - {{ now()->subMonth()->format('F Y') }}</h4>
                        <p>Generated 3 days ago • 8.7 MB • PDF</p>
                    </div>
                </div>
                <div class="report-item-actions-modern">
                    <button class="btn-modern btn-outline-modern btn-sm-modern"
                        onclick="downloadReport('monthly')">Download</button>
                    <button class="btn-modern btn-outline-modern btn-sm-modern"
                        onclick="viewReport('monthly')">View</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Charts -->
    <div class="charts-section-modern">
        <div class="chart-card-modern">
            <div class="chart-header-modern">
                <h3>Appointment Trends</h3>
                <select class="chart-period-modern" id="trendPeriod" onchange="updateTrendChart(this.value)">
                    <option value="7">Last 7 days</option>
                    <option value="30">Last 30 days</option>
                    <option value="90">Last 3 months</option>
                </select>
            </div>
            <canvas id="trendsChart" style="height: 300px; width: 100%;"></canvas>
        </div>

        <div class="chart-card-modern">
            <div class="chart-header-modern">
                <h3>Service Distribution</h3>
            </div>
            <canvas id="distributionChart" style="height: 300px; width: 100%;"></canvas>
        </div>
    </div>

    <!-- Detailed Data Table -->
    <div class="data-table-card-modern">
        <div class="data-table-header-modern">
            <h3>Recent Transactions</h3>
            <div class="data-table-filters-modern">
                <input type="text" placeholder="Search by name, queue ID..." class="table-search-modern"
                    id="tableSearch">
                <select class="table-filter-modern" id="serviceFilter">
                    <option value="">All Services</option>
                    <option value="Registration">NID Registration</option>
                    <option value="Status Inquiry">Status Inquiry</option>
                    <option value="Updating">Information Update</option>
                    <option value="Others">Others</option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="data-table-modern">
                <thead>
                    <tr>
                        <th>Queue ID</th>
                        <th>Client Name</th>
                        <th>Service</th>
                        <th>Date & Time</th>
                        <th>Window</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="reportTableBody">
                    @forelse($appointments ?? [] as $transaction)
                        <tr>
                            <td><span class="queue-badge-modern">{{ $transaction->q_id }}</span></td>
                            <td>{{ $transaction->lname }}, {{ $transaction->fname }}</td>
                            <td>{{ $transaction->queue_for }}</td>
                            <td>{{ \Carbon\Carbon::parse($transaction->date)->format('M d, Y h:i A') }}</td>
                            <td>{{ $transaction->window_num ? 'Window ' . $transaction->window_num : '—' }}</td>
                            <td>
                                <span
                                    class="status-badge-modern status-{{ $transaction->time_catered ? 'completed' : 'pending' }}">
                                    {{ $transaction->time_catered ? 'Completed' : 'Pending' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state-modern">
                                <svg viewBox="0 0 20 20" fill="currentColor" width="48" height="48">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd" />
                                </svg>
                                <p>No transactions found for today</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if (isset($appointments) && method_exists($appointments, 'links'))
            <div class="pagination-container-modern">
                {{ $appointments->links() }}
            </div>
        @endif
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            let trendsChart, distributionChart;

            function showLoading(message = 'Generating report...') {
                const overlay = document.createElement('div');
                overlay.className = 'loading-overlay';
                overlay.innerHTML = `
        <div class="loading-spinner">
            <img src="{{ asset('images/loading.png') }}" alt="Loading..." id="loadingImage">
            <p>${message}</p>
            <div class="loading-text">Please wait...</div>
        </div>
    `;
                document.body.appendChild(overlay);

                // Handle image error
                const img = overlay.querySelector('#loadingImage');
                if (img) {
                    img.onerror = function() {
                        this.style.display = 'none';
                        const spinner = document.createElement('div');
                        spinner.className = 'spinner';
                        this.parentNode.insertBefore(spinner, this.nextSibling);
                    };
                }
            }

            function hideLoading() {
                const overlay = document.querySelector('.loading-overlay');
                if (overlay) {
                    overlay.style.animation = 'fadeOut 0.3s ease';
                    setTimeout(() => {
                        overlay.remove();
                    }, 300);
                }
            }

            function generateReport(type) {
                if (type === 'custom') {
                    document.getElementById('customReportBuilder').style.display = 'block';
                } else {
                    showLoading(`Generating ${type} report...`);
                    setTimeout(() => {
                        window.location.href = `{{ url('admin/reports') }}/${type}`;
                    }, 800);
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

                showLoading('Generating custom report...');
                setTimeout(() => {
                    window.location.href =
                        `{{ url('admin/reports/custom') }}?start=${start}&end=${end}&type=${type}&format=${format}&name=${encodeURIComponent(name)}`;
                    hideCustomBuilder();
                }, 800);
            }

            function downloadReport(type) {
                showLoading(`Preparing ${type} report download...`);
                setTimeout(() => {
                    hideLoading();
                    alert(`Downloading ${type} report...`);
                }, 1500);
            }

            function viewReport(type) {
                showLoading(`Loading ${type} report...`);
                setTimeout(() => {
                    window.location.href = `{{ url('admin/reports') }}/${type}`;
                }, 500);
            }

            function updateTrendChart(days) {
                if (trendsChart) {
                    showLoading('Updating chart data...');
                    // Fetch real data from server
                    fetch(`{{ url('admin/reports/chart-data') }}?period=${days}`)
                        .then(response => response.json())
                        .then(data => {
                            trendsChart.data.labels = data.labels;
                            trendsChart.data.datasets[0].data = data.data;
                            trendsChart.update();
                            hideLoading();
                        })
                        .catch(error => {
                            console.error('Error fetching chart data:', error);
                            // Fallback to random data
                            const newData = Array.from({
                                length: parseInt(days)
                            }, () => Math.floor(Math.random() * 100) + 20);
                            const labels = Array.from({
                                length: parseInt(days)
                            }, (_, i) => `Day ${i + 1}`);
                            trendsChart.data.labels = labels;
                            trendsChart.data.datasets[0].data = newData;
                            trendsChart.update();
                            hideLoading();
                        });
                }
            }

            function generateRandomData(count) {
                return Array.from({
                    length: count
                }, () => Math.floor(Math.random() * 100) + 20);
            }

            // Initialize charts
            document.addEventListener('DOMContentLoaded', function() {
                // Trends Chart
                const trendsCtx = document.getElementById('trendsChart')?.getContext('2d');
                if (trendsCtx) {
                    trendsChart = new Chart(trendsCtx, {
                        type: 'line',
                        data: {
                            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                            datasets: [{
                                label: 'Appointments',
                                data: [65, 72, 80, 68, 85, 45, 38],
                                borderColor: '#0038A8',
                                backgroundColor: 'rgba(0, 56, 168, 0.1)',
                                tension: 0.4,
                                fill: true,
                                pointBackgroundColor: '#0038A8',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                                pointRadius: 4,
                                pointHoverRadius: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: '#1f2937',
                                    titleColor: '#fff',
                                    bodyColor: '#e5e7eb',
                                    padding: 10,
                                    cornerRadius: 8
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Number of Appointments',
                                        color: '#6b7280'
                                    },
                                    grid: {
                                        color: '#e5e7eb'
                                    }
                                },
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Day of Week',
                                        color: '#6b7280'
                                    },
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });
                }

                // Distribution Chart
                const distCtx = document.getElementById('distributionChart')?.getContext('2d');
                if (distCtx) {
                    distributionChart = new Chart(distCtx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Registration', 'Status Inquiry', 'Updating', 'Others'],
                            datasets: [{
                                data: [45, 25, 20, 10],
                                backgroundColor: ['#0038A8', '#CE1126', '#FCD116', '#10b981'],
                                borderWidth: 0,
                                hoverOffset: 10
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        padding: 15,
                                        usePointStyle: true,
                                        pointStyle: 'circle'
                                    }
                                },
                                tooltip: {
                                    backgroundColor: '#1f2937',
                                    callbacks: {
                                        label: function(context) {
                                            const label = context.label || '';
                                            const value = context.raw || 0;
                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            const percentage = ((value / total) * 100).toFixed(1);
                                            return `${label}: ${value} (${percentage}%)`;
                                        }
                                    }
                                }
                            },
                            cutout: '60%'
                        }
                    });
                }

                // Table search functionality
                const searchInput = document.getElementById('tableSearch');
                const serviceFilter = document.getElementById('serviceFilter');
                const tableBody = document.getElementById('reportTableBody');

                function filterTable() {
                    if (!tableBody) return;

                    const searchTerm = searchInput?.value.toLowerCase() || '';
                    const filterValue = serviceFilter?.value.toLowerCase() || '';
                    const rows = tableBody.querySelectorAll('tr');

                    rows.forEach(row => {
                        if (row.querySelector('.empty-state-modern')) return;

                        const text = row.textContent.toLowerCase();
                        const service = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';

                        const matchesSearch = !searchTerm || text.includes(searchTerm);
                        const matchesFilter = !filterValue || service.includes(filterValue);

                        row.style.display = matchesSearch && matchesFilter ? '' : 'none';
                    });
                }

                if (searchInput) searchInput.addEventListener('keyup', filterTable);
                if (serviceFilter) serviceFilter.addEventListener('change', filterTable);

                // Add fadeOut animation
                const style = document.createElement('style');
                style.textContent = `
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
    `;
                document.head.appendChild(style);
            });
        </script>
    @endpush
@endsection
