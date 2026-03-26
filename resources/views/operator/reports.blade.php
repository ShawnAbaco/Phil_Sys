{{-- resources/views/operator/reports.blade.php --}}
<x-header title="Reports" />

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


        /* Report Cards */
        .report-cards-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .report-stat-card {
            background: white;
            border-radius: 1rem;
            padding: 1.25rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .report-stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #2563eb, #1d4ed8);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .report-stat-card:hover::before {
            opacity: 1;
        }

        .report-stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .report-stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .report-stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        }

        .report-stat-icon svg {
            width: 24px;
            height: 24px;
        }

        .report-stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.2;
            margin-bottom: 0.25rem;
        }

        .report-stat-label {
            font-size: 0.75rem;
            font-weight: 500;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .report-stat-trend {
            font-size: 0.75rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .trend-up { color: #10b981; }
        .trend-down { color: #ef4444; }
        .trend-neutral { color: #f59e0b; }

        /* Filter Section */
        .filter-section {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid #e2e8f0;
        }

        .filter-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-row {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: flex-end;
        }

        .filter-group {
            flex: 1;
            min-width: 150px;
        }

        .filter-group label {
            display: block;
            font-size: 0.75rem;
            font-weight: 500;
            color: #64748b;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
        }

        .filter-group input,
        .filter-group select {
            width: 100%;
            padding: 0.625rem 0.875rem;
            border: 1px solid #cbd5e1;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }

        .filter-group input:focus,
        .filter-group select:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .btn-filter {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            border: none;
            padding: 0.625rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }

        .btn-filter:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .btn-reset {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
            padding: 0.625rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }

        .btn-reset:hover {
            background: #e2e8f0;
        }

        /* Charts Section */
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .chart-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            border: 1px solid #e2e8f0;
        }

        .chart-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .chart-container {
            height: 300px;
            position: relative;
        }

        /* Report Table */
        .report-table-container {
            background: white;
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .report-table-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            background: #f8fafc;
        }

        .report-table-header h4 {
            font-size: 1rem;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }

        .export-buttons {
            display: flex;
            gap: 0.75rem;
        }

        .btn-export {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
            border: none;
        }

        .btn-export-pdf {
            background: #dc2626;
            color: white;
        }

        .btn-export-pdf:hover {
            background: #b91c1c;
        }

        .btn-export-excel {
            background: #059669;
            color: white;
        }

        .btn-export-excel:hover {
            background: #047857;
        }

        .btn-export-print {
            background: #6b7280;
            color: white;
        }

        .btn-export-print:hover {
            background: #4b5563;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
        }

        .report-table th {
            text-align: left;
            padding: 0.875rem 1rem;
            background: #f8fafc;
            color: #475569;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e2e8f0;
        }

        .report-table td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
            font-size: 0.875rem;
        }

        .report-table tr:hover td {
            background: #f8fafc;
        }

        .summary-row {
            background: #f1f5f9;
            font-weight: 600;
        }

        .summary-row td {
            border-top: 2px solid #cbd5e1;
        }

        .empty-state {
            text-align: center;
            padding: 3rem !important;
            color: #94a3b8;
        }

        .empty-state svg {
            width: 48px;
            height: 48px;
            margin-bottom: 1rem;
            color: #cbd5e1;
        }

        @media (max-width: 1024px) {
            .report-cards-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .charts-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .report-cards-grid {
                grid-template-columns: 1fr;
            }
        }

        @media print {
            .sidebar, .filter-section, .export-buttons, .btn-filter, .btn-reset {
                display: none !important;
            }
            .main-content {
                margin-left: 0 !important;
                padding: 0 !important;
            }
            .report-stat-card {
                break-inside: avoid;
            }
            .chart-card {
                break-inside: avoid;
            }
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
                <h1>Reports</h1>
            </div>
            <div class="breadcrumb">
                <a href="{{ route('operator.dashboard') }}">Home</a>
                <span>/</span>
                <span>Reports</span>
            </div>
        </div>


        <!-- Message Container -->
        <div class="message-container" id="messageContainer"></div>

        {{-- Report Header --}}
        <div class="report-header" style="margin-bottom: 2rem;">
            <h2 style="font-size: 1.5rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem;">
                Transaction Reports
            </h2>
            <p style="color: #64748b;">View and export your transaction history and performance metrics</p>
        </div>

        {{-- Statistics Cards --}}
        <div class="report-cards-grid">
            <div class="report-stat-card">
                <div class="report-stat-header">
                    <div class="report-stat-icon">
                        <svg viewBox="0 0 20 20" fill="currentColor" style="color: #10b981;">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <div class="report-stat-value" id="totalCompleted">{{ $totalCompleted ?? 0 }}</div>
                <div class="report-stat-label">Total Completed</div>
                <div class="report-stat-trend" id="completedTrend">
                    <span class="trend-up">↑ 0%</span>
                    <span>vs previous period</span>
                </div>
            </div>

            <div class="report-stat-card">
                <div class="report-stat-header">
                    <div class="report-stat-icon">
                        <svg viewBox="0 0 20 20" fill="currentColor" style="color: #ef4444;">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <div class="report-stat-value" id="totalCancelled">{{ $totalCancelled ?? 0 }}</div>
                <div class="report-stat-label">Total Cancelled</div>
                <div class="report-stat-trend" id="cancelledTrend">
                    <span class="trend-down">↓ 0%</span>
                    <span>vs previous period</span>
                </div>
            </div>

            <div class="report-stat-card">
                <div class="report-stat-header">
                    <div class="report-stat-icon">
                        <svg viewBox="0 0 20 20" fill="currentColor" style="color: #f59e0b;">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <div class="report-stat-value" id="totalPending">{{ $totalPending ?? 0 }}</div>
                <div class="report-stat-label">Pending</div>
                <div class="report-stat-trend" id="pendingTrend">
                    <span class="trend-neutral">→ 0%</span>
                    <span>vs previous period</span>
                </div>
            </div>

            <div class="report-stat-card">
                <div class="report-stat-header">
                    <div class="report-stat-icon">
                        <svg viewBox="0 0 20 20" fill="currentColor" style="color: #8b5cf6;">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                        </svg>
                    </div>
                </div>
                <div class="report-stat-value" id="totalServed">{{ $totalServed ?? 0 }}</div>
                <div class="report-stat-label">Total Served</div>
                <div class="report-stat-trend" id="servedTrend">
                    <span class="trend-up">↑ 0%</span>
                    <span>vs previous period</span>
                </div>
            </div>
        </div>

        {{-- Filter Section --}}
        <div class="filter-section">
            <div class="filter-title">
                <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                </svg>
                Filter Reports
            </div>
            <div class="filter-row">
                <div class="filter-group">
                    <label>Date Range</label>
                    <select id="dateRange" class="date-range-select">
                        <option value="today">Today</option>
                        <option value="yesterday">Yesterday</option>
                        <option value="this_week">This Week</option>
                        <option value="last_week">Last Week</option>
                        <option value="this_month" selected>This Month</option>
                        <option value="last_month">Last Month</option>
                        <option value="custom">Custom Range</option>
                    </select>
                </div>
                <div class="filter-group" id="customDateRange" style="display: none;">
                    <label>From Date</label>
                    <input type="date" id="startDate" value="{{ Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}">
                </div>
                <div class="filter-group" id="customDateRangeEnd" style="display: none;">
                    <label>To Date</label>
                    <input type="date" id="endDate" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}">
                </div>
                <div class="filter-group">
                    <label>Service Type</label>
                    <select id="serviceType">
                        <option value="all">All Services</option>
                        <option value="NID Registration">NID Registration</option>
                        <option value="Status Inquiry">Status Inquiry</option>
                        <option value="Updating">NID Updating</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Status</label>
                    <select id="statusFilter">
                        <option value="all">All Status</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="no_show">No Show</option>
                    </select>
                </div>
                <div class="filter-group">
                    <button class="btn-filter" id="applyFilterBtn">
                        <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                        Apply Filters
                    </button>
                </div>
                <div class="filter-group">
                    <button class="btn-reset" id="resetFilterBtn">
                        <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                        </svg>
                        Reset
                    </button>
                </div>
            </div>
        </div>

        {{-- Charts Section --}}
        <div class="charts-grid">
            <div class="chart-card">
                <div class="chart-title">
                    <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                        <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z" />
                        <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z" />
                    </svg>
                    Daily Transactions
                </div>
                <div class="chart-container">
                    <canvas id="dailyChart"></canvas>
                </div>
            </div>
            <div class="chart-card">
                <div class="chart-title">
                    <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                        <path d="M2 2a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V4a2 2 0 00-2-2H2zm8 2a1 1 0 011 1v10a1 1 0 11-2 0V5a1 1 0 011-1z" />
                    </svg>
                    Status Distribution
                </div>
                <div class="chart-container">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Detailed Report Table --}}
        <div class="report-table-container">
            <div class="report-table-header">
                <h4>
                    <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18" style="display: inline; margin-right: 8px;">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11 4a1 1 0 10-2 0v4a1 1 0 102 0V7zm-3 1a1 1 0 10-2 0v3a1 1 0 102 0V8zM8 9a1 1 0 00-2 0v2a1 1 0 102 0V9z" clip-rule="evenodd" />
                    </svg>
                    Transaction Details
                </h4>
                <div class="export-buttons">
                    <button class="btn-export btn-export-pdf" id="exportReportPdfBtn">
                        <svg viewBox="0 0 20 20" fill="currentColor" width="14" height="14">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                        </svg>
                        Export PDF
                    </button>
                    <button class="btn-export btn-export-excel" id="exportReportExcelBtn">
                        <svg viewBox="0 0 20 20" fill="currentColor" width="14" height="14">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                        </svg>
                        Export Excel
                    </button>
                    <button class="btn-export btn-export-print" id="printReportBtn">
                        <svg viewBox="0 0 20 20" fill="currentColor" width="14" height="14">
                            <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8h-2v2h2v-2z" clip-rule="evenodd" />
                        </svg>
                        Print
                    </button>
                </div>
            </div>
            <div style="overflow-x: auto;">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Queue #</th>
                            <th>Client Name</th>
                            <th>Service</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Window</th>
                            <th>Time Served</th>
                        </thead>
                        <tbody id="reportTableBody">
                            @forelse($transactions as $transaction)
                                @php
                                    $displayTime = $transaction->time_catered 
                                        ? \Carbon\Carbon::parse($transaction->time_catered)->setTimezone('Asia/Manila')
                                        : \Carbon\Carbon::parse($transaction->updated_at)->setTimezone('Asia/Manila');
                                    
                                    $fullName = $transaction->lname . ', ' . $transaction->fname;
                                    if ($transaction->mname && trim($transaction->mname) !== '') {
                                        $fullName .= ' ' . $transaction->mname;
                                    }
                                    if ($transaction->suffix && trim($transaction->suffix) !== '') {
                                        $fullName .= ' ' . $transaction->suffix;
                                    }
                                    
                                    $priorityType = $transaction->priority_type ?? 'regular';
                                    $priorityDisplay = ucfirst($priorityType);
                                    $statusDisplay = ucfirst(str_replace('_', ' ', $transaction->status));
                                @endphp
                                <tr>
                                    <td>{{ $displayTime->format('M d, Y') }}</td>
                                    <td><span class="queue-number small">{{ $transaction->q_id }}</span></td>
                                    <td>{{ $fullName }}</td>
                                    <td>{{ $transaction->queue_for }}</td>
                                    <td>
                                        <span class="priority-badge priority-{{ $priorityType }}">
                                            {{ strtoupper($priorityDisplay) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge {{ $transaction->status }}">
                                            <span class="status-dot"></span>
                                            {{ $statusDisplay }}
                                        </span>
                                    </td>
                                    <td>{{ $transaction->window_num ?? 'N/A' }}</td>
                                    <td>{{ $displayTime->format('h:i A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="empty-state">
                                        <svg viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                        </svg>
                                        <p>No transactions found for the selected period</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($transactions->count() > 0)
                            <tfoot class="summary-row">
                                <td colspan="8" style="text-align: right;">
                                    Total Records: {{ $transactions->count() }}
                                </td>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let dailyChart = null;
    let statusChart = null;
    let currentFilters = {
        dateRange: 'this_month',
        startDate: '{{ Carbon\Carbon::now()->startOfMonth()->format("Y-m-d") }}',
        endDate: '{{ Carbon\Carbon::now()->format("Y-m-d") }}',
        serviceType: 'all',
        statusFilter: 'all'
    };

    // Initialize charts
    function initCharts() {
        const dailyCtx = document.getElementById('dailyChart').getContext('2d');
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        
        dailyChart = new Chart(dailyCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Completed',
                    data: [],
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Cancelled',
                    data: [],
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
        
        statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'Cancelled', 'No Show'],
                datasets: [{
                    data: [0, 0, 0],
                    backgroundColor: ['#10b981', '#ef4444', '#f59e0b'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    }

    // Fetch report data
    function fetchReportData() {
        const url = '{{ route('operator.reports.data') }}';
        const params = new URLSearchParams({
            date_range: currentFilters.dateRange,
            start_date: currentFilters.startDate,
            end_date: currentFilters.endDate,
            service_type: currentFilters.serviceType,
            status: currentFilters.statusFilter
        });
        
        fetch(`${url}?${params.toString()}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateStats(data.stats);
                updateCharts(data.charts);
                updateTable(data.transactions);
                updateTrends(data.trends);
            }
        })
        .catch(error => console.error('Error fetching report data:', error));
    }

    // Update statistics
    function updateStats(stats) {
        document.getElementById('totalCompleted').textContent = stats.completed || 0;
        document.getElementById('totalCancelled').textContent = stats.cancelled || 0;
        document.getElementById('totalPending').textContent = stats.pending || 0;
        document.getElementById('totalServed').textContent = stats.served || 0;
    }

    // Update charts
    function updateCharts(charts) {
        if (dailyChart && charts.daily) {
            dailyChart.data.labels = charts.daily.labels;
            dailyChart.data.datasets[0].data = charts.daily.completed;
            dailyChart.data.datasets[1].data = charts.daily.cancelled;
            dailyChart.update();
        }
        
        if (statusChart && charts.status) {
            statusChart.data.datasets[0].data = charts.status;
            statusChart.update();
        }
    }

    // Update table
    function updateTable(transactions) {
        const tbody = document.getElementById('reportTableBody');
        if (!transactions || transactions.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="empty-state">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                        </svg>
                        <p>No transactions found for the selected period</p>
                    </td>
                </tr>
            `;
            return;
        }
        
        let html = '';
        transactions.forEach(transaction => {
            const priorityClass = transaction.priority_type || 'regular';
            const priorityDisplay = priorityClass.charAt(0).toUpperCase() + priorityClass.slice(1);
            const statusDisplay = transaction.status.replace('_', ' ');
            
            html += `
                <tr>
                    <td>${transaction.date}</td>
                    <td><span class="queue-number small">${transaction.q_id}</span></td>
                    <td>${transaction.client_name}</td>
                    <td>${transaction.service}</td>
                    <td>
                        <span class="priority-badge priority-${priorityClass}">
                            ${priorityDisplay.toUpperCase()}
                        </span>
                    </td>
                    <td>
                        <span class="status-badge ${transaction.status}">
                            <span class="status-dot"></span>
                            ${statusDisplay.charAt(0).toUpperCase() + statusDisplay.slice(1)}
                        </span>
                    </td>
                    <td>${transaction.window_num || 'N/A'}</td>
                    <td>${transaction.served_time}</td>
                 </tr>
            `;
        });
        
        tbody.innerHTML = html;
    }

    // Update trends
    function updateTrends(trends) {
        if (trends) {
            const completedTrend = document.querySelector('#completedTrend span:first-child');
            const cancelledTrend = document.querySelector('#cancelledTrend span:first-child');
            const pendingTrend = document.querySelector('#pendingTrend span:first-child');
            const servedTrend = document.querySelector('#servedTrend span:first-child');
            
            if (completedTrend) completedTrend.textContent = trends.completed;
            if (cancelledTrend) cancelledTrend.textContent = trends.cancelled;
            if (pendingTrend) pendingTrend.textContent = trends.pending;
            if (servedTrend) servedTrend.textContent = trends.served;
        }
    }

    // Show date range fields
    function toggleDateRange() {
        const dateRange = document.getElementById('dateRange').value;
        const customRange = document.getElementById('customDateRange');
        const customRangeEnd = document.getElementById('customDateRangeEnd');
        
        if (dateRange === 'custom') {
            customRange.style.display = 'block';
            customRangeEnd.style.display = 'block';
        } else {
            customRange.style.display = 'none';
            customRangeEnd.style.display = 'none';
        }
    }

    // Apply filters
    function applyFilters() {
        const dateRange = document.getElementById('dateRange').value;
        currentFilters.dateRange = dateRange;
        
        if (dateRange === 'custom') {
            currentFilters.startDate = document.getElementById('startDate').value;
            currentFilters.endDate = document.getElementById('endDate').value;
        }
        
        currentFilters.serviceType = document.getElementById('serviceType').value;
        currentFilters.statusFilter = document.getElementById('statusFilter').value;
        
        fetchReportData();
    }

    // Reset filters
    function resetFilters() {
        document.getElementById('dateRange').value = 'this_month';
        document.getElementById('serviceType').value = 'all';
        document.getElementById('statusFilter').value = 'all';
        document.getElementById('startDate').value = '{{ Carbon\Carbon::now()->startOfMonth()->format("Y-m-d") }}';
        document.getElementById('endDate').value = '{{ Carbon\Carbon::now()->format("Y-m-d") }}';
        
        toggleDateRange();
        applyFilters();
    }

    // Export PDF
    function exportPDF() {
        const params = new URLSearchParams({
            date_range: currentFilters.dateRange,
            start_date: currentFilters.startDate,
            end_date: currentFilters.endDate,
            service_type: currentFilters.serviceType,
            status: currentFilters.statusFilter
        });
        
        window.location.href = '{{ route('operator.reports.export.pdf') }}?' + params.toString();
        
        Swal.fire({
            title: 'Exporting...',
            text: 'Your report is being generated',
            icon: 'info',
            timer: 2000,
            showConfirmButton: false
        });
    }

    // Export Excel
    function exportExcel() {
        const params = new URLSearchParams({
            date_range: currentFilters.dateRange,
            start_date: currentFilters.startDate,
            end_date: currentFilters.endDate,
            service_type: currentFilters.serviceType,
            status: currentFilters.statusFilter
        });
        
        window.location.href = '{{ route('operator.reports.export.excel') }}?' + params.toString();
        
        Swal.fire({
            title: 'Exporting...',
            text: 'Your report is being generated',
            icon: 'info',
            timer: 2000,
            showConfirmButton: false
        });
    }

    // Print report
    function printReport() {
        window.print();
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        initCharts();
        fetchReportData();
        
        // Event listeners
        document.getElementById('dateRange').addEventListener('change', toggleDateRange);
        document.getElementById('applyFilterBtn').addEventListener('click', applyFilters);
        document.getElementById('resetFilterBtn').addEventListener('click', resetFilters);
        document.getElementById('exportReportPdfBtn').addEventListener('click', exportPDF);
        document.getElementById('exportReportExcelBtn').addEventListener('click', exportExcel);
        document.getElementById('printReportBtn').addEventListener('click', printReport);
        
        toggleDateRange();
    });
</script>