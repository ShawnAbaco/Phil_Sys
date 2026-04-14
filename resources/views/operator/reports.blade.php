{{-- resources/views/operator/reports.blade.php --}}
<x-header title="Reports" />

<div class="app-container">
    <x-operator.sidebar />    
    <style>

        /* Add to your existing CSS styles */
.data-table th:first-child,
.data-table td:first-child {
    width: 60px;
    text-align: center;
}

.data-table td:first-child {
    background-color: transparent;
    font-weight: 600;
    color: #6b7280;
}


        /* Page Header */
        .page-header {
            margin-bottom: 1.5rem;
        }

        .page-title1 {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .page-title1 svg {
            width: 2rem;
            height: 2rem;
            color: #2563eb;
        }

        .page-title1 h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
        }

        .breadcrumb a {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            color: #4b5563;
            text-decoration: none;
            padding: 0.375rem 0.625rem;
            border-radius: 0.5rem;
            background: #f3f4f6;
            transition: all 0.2s ease;
        }

        .breadcrumb a svg {
            width: 1rem;
            height: 1rem;
            color: #4b5563;
            transition: color 0.2s ease;
        }

        .breadcrumb a:hover {
            background: #2563eb;
            color: white;
            transform: translateY(-1px);
        }

        .breadcrumb a:hover svg {
            color: white;
        }

        .breadcrumb span {
            color: #9ca3af;
        }

        .breadcrumb .current {
            color: #374151;
            font-weight: 500;
            padding: 0.375rem 0.625rem;
            background: #f3f4f6;
            border-radius: 0.5rem;
        }

        /* Quick Stats Grid */
        .quick-stats-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .quick-stat-card {
            background: white;
            border-radius: 1rem;
            padding: 1rem;
            text-align: center;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .quick-stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .quick-stat-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: #1f2937;
        }

        .quick-stat-label {
            font-size: 0.7rem;
            font-weight: 500;
            color: #6b7280;
            text-transform: uppercase;
        }

        /* Main Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 1rem;
            padding: 1.25rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
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

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .stat-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
        }

        .stat-icon svg {
            width: 1.5rem;
            height: 1.5rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: #1f2937;
            line-height: 1.2;
        }

        .stat-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
        }

        .stat-trend {
            font-size: 0.75rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .trend-up { color: #10b981; }
        .trend-down { color: #ef4444; }
        .trend-neutral { color: #f59e0b; }

        /* Filter Section */
        .filter-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid #e5e7eb;
        }

        .filter-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e5e7eb;
        }

        .filter-title svg {
            width: 1.25rem;
            height: 1.25rem;
            color: #2563eb;
        }

        .period-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .period-btn {
            padding: 0.5rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            background: white;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.875rem;
            color: #374151;
        }

        .period-btn.active {
            background: #2563eb;
            color: white;
            border-color: #2563eb;
        }

        .period-btn:hover:not(.active) {
            background: #f3f4f6;
        }

        .filter-row {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: flex-end;
        }

        .filter-group {
            flex: 1;
            min-width: 160px;
        }

        .filter-group label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
        }

        .filter-group input,
        .filter-group select {
            width: 100%;
            padding: 0.625rem 0.875rem;
            border: 1px solid #d1d5db;
            border-radius: 0.625rem;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            background: white;
        }

        .filter-group input:focus,
        .filter-group select:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            border: none;
            padding: 0.625rem 1.5rem;
            border-radius: 0.625rem;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }

        .btn-primary svg {
            width: 1rem;
            height: 1rem;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37,99,235,0.3);
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #d1d5db;
            padding: 0.625rem 1.5rem;
            border-radius: 0.625rem;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }

        .btn-secondary svg {
            width: 1rem;
            height: 1rem;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
        }

        /* Charts Grid */
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
            border: 1px solid #e5e7eb;
        }

        .chart-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e5e7eb;
        }

        .chart-title svg {
            width: 1.25rem;
            height: 1.25rem;
            color: #2563eb;
        }

        .chart-container {
            height: 300px;
            position: relative;
        }

        /* Table Styles */
        .table-container {
            background: white;
            border-radius: 1rem;
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }

        .table-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            background: #f9fafb;
        }

        .table-header h4 {
            font-size: 1rem;
            font-weight: 600;
            color: #1f2937;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
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
            border: none;
            transition: all 0.2s ease;
        }

        .btn-export svg {
            width: 1rem;
            height: 1rem;
        }

        .btn-export-pdf { 
            background: #dc2626; 
            color: white; 
        }
        .btn-export-pdf:hover { 
            background: #b91c1c; 
            transform: translateY(-1px);
        }

        .btn-export-excel { 
            background: #059669; 
            color: white; 
        }
        .btn-export-excel:hover { 
            background: #047857; 
            transform: translateY(-1px);
        }

        .btn-export-print { 
            background: #6b7280; 
            color: white; 
        }
        .btn-export-print:hover { 
            background: #4b5563; 
            transform: translateY(-1px);
        }

        .table-responsive {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            text-align: left;
            padding: 0.875rem 1rem;
            background: #f9fafb;
            color: #4b5563;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            border-bottom: 1px solid #e5e7eb;
        }

        .data-table td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f3f4f6;
            color: #374151;
            font-size: 0.875rem;
        }

        .data-table tr:hover td {
            background: #f9fafb;
        }

        .queue-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            background: #f3f4f6;
            border-radius: 0.375rem;
            font-weight: 600;
            font-family: monospace;
            font-size: 0.75rem;
            color: #374151;
        }

        .priority-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.6875rem;
            font-weight: 600;
            color: white;
        }

        .priority-senior { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
        .priority-infant { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .priority-pwd { background: linear-gradient(135deg, #10b981, #059669); }
        .priority-pregnant { background: linear-gradient(135deg, #ec4899, #db2777); }
        .priority-regular { background: linear-gradient(135deg, #6b7280, #4b5563); }

/* Status Badge Styles */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
    transition: all 0.2s ease;
}

/* Pending Status - Yellow/Orange */
.status-badge.pending {
    background: #fef3c7;
    color: #92400e;
    border-left: 3px solid #f59e0b;
}

/* Serving Status - Blue */
.status-badge.serving {
    background: #dbeafe;
    color: #1e40af;
    border-left: 3px solid #3b82f6;
}

/* Completed Status - Green */
.status-badge.completed {
    background: #d1fae5;
    color: #065f46;
    border-left: 3px solid #10b981;
}

/* Cancelled Status - Red */
.status-badge.cancelled {
    background: #fee2e2;
    color: #991b1b;
    border-left: 3px solid #ef4444;
}

/* No Show Status - Gray */
.status-badge.no_show {
    background: #f3f4f6;
    color: #4b5563;
    border-left: 3px solid #6b7280;
}

/* Status Dot */
.status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
}

/* Hover effect */
.status-badge:hover {
    transform: translateY(-1px);
    filter: brightness(0.98);
}

/* Pulse animation for pending/serving status */
@keyframes pulse {
    0% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.6;
        transform: scale(1.2);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

        .empty-state {
            text-align: center;
            padding: 3rem !important;
            color: #9ca3af;
        }

        .empty-state svg {
            width: 3rem;
            height: 3rem;
            margin-bottom: 1rem;
            color: #d1d5db;
        }

        /* Enhanced Pagination Styles */
        .enhanced-pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding: 20px 20px 10px 20px;
            border-top: 2px solid #e2e8f0;
            gap: 15px;
            flex-wrap: wrap;
            background: white;
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

        .showing-info {
            font-size: 0.875rem;
            color: #6b7280;
            background: #f3f4f6;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .quick-stats-grid { grid-template-columns: repeat(3, 1fr); }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .charts-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 768px) {
            .quick-stats-grid { grid-template-columns: repeat(2, 1fr); }
            .stats-grid { grid-template-columns: 1fr; }
            .filter-row { flex-direction: column; }
            .filter-group { width: 100%; }
        }

        @media print {
            .sidebar, .filter-card, .export-buttons, .btn-primary, .btn-secondary, .enhanced-pagination {
                display: none !important;
            }
            .stat-card, .chart-card, .table-container {
                break-inside: avoid;
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }
    </style>

    <main class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title1">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                </svg>
                <h1>Analytics & Reports</h1>
            </div>
            <div class="breadcrumb">
                <a href="{{ route('operator.dashboard') }}">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                    Home
                </a>
                <span>/</span>
                <span class="current">Reports</span>
            </div>
        </div>

        <!-- Quick Stats Cards -->
        <div class="quick-stats-grid">
            <div class="quick-stat-card">
                <div class="quick-stat-value" id="todayCount">0</div>
                <div class="quick-stat-label">Today</div>
            </div>
            <div class="quick-stat-card">
                <div class="quick-stat-value" id="weeklyCount">0</div>
                <div class="quick-stat-label">This Week</div>
            </div>
            <div class="quick-stat-card">
                <div class="quick-stat-value" id="monthlyCount">0</div>
                <div class="quick-stat-label">This Month</div>
            </div>
            <div class="quick-stat-card">
                <div class="quick-stat-value" id="yearlyCount">0</div>
                <div class="quick-stat-label">This Year</div>
            </div>
            <div class="quick-stat-card">
                <div class="quick-stat-value" id="avgDaily">0</div>
                <div class="quick-stat-label">Avg Daily</div>
            </div>
            <div class="quick-stat-card">
                <div class="quick-stat-value" id="completionRate">0%</div>
                <div class="quick-stat-label">Completion Rate</div>
            </div>
        </div>

        <!-- Main Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">
                        <svg viewBox="0 0 20 20" fill="currentColor" style="color: #10b981;">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <div class="stat-value" id="totalCompleted">0</div>
                <div class="stat-label">Completed</div>
                <div class="stat-trend" id="completedTrend"><span class="trend-up">↑ 0%</span></div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">
                        <svg viewBox="0 0 20 20" fill="currentColor" style="color: #ef4444;">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <div class="stat-value" id="totalCancelled">0</div>
                <div class="stat-label">Cancelled</div>
                <div class="stat-trend" id="cancelledTrend"><span class="trend-down">↓ 0%</span></div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">
                        <svg viewBox="0 0 20 20" fill="currentColor" style="color: #f59e0b;">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <div class="stat-value" id="totalNoShow">0</div>
                <div class="stat-label">No Show</div>
                <div class="stat-trend" id="noShowTrend"><span class="trend-neutral">→ 0%</span></div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">
                        <svg viewBox="0 0 20 20" fill="currentColor" style="color: #8b5cf6;">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                        </svg>
                    </div>
                </div>
                <div class="stat-value" id="totalServed">0</div>
                <div class="stat-label">Total Served</div>
                <div class="stat-trend" id="servedTrend"><span class="trend-up">↑ 0%</span></div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-card">
            <div class="filter-title">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                </svg>
                Filter Reports
            </div>
            <div class="period-buttons">
                <button class="period-btn" data-period="daily">📅 Daily</button>
                <button class="period-btn" data-period="weekly">📊 Weekly</button>
                <button class="period-btn active" data-period="monthly">📆 Monthly</button>
                <button class="period-btn" data-period="custom">⚙️ Custom Range</button>
            </div>
            <div class="filter-row" id="customDateFields" style="display: none;">
                <div class="filter-group">
                    <label>From Date</label>
                    <input type="date" id="startDate" value="{{ date('Y-m-d') }}">
                </div>
                <div class="filter-group">
                    <label>To Date</label>
                    <input type="date" id="endDate" value="{{ date('Y-m-d') }}">
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
                    <button class="btn-primary" id="applyFilterBtn">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                        Apply Filters
                    </button>
                </div>
                <div class="filter-group">
                    <button class="btn-secondary" id="resetFilterBtn">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                        </svg>
                        Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="charts-grid">
            <div class="chart-card">
                <div class="chart-title">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z" />
                        <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z" />
                    </svg>
                    Daily Transaction Trends
                </div>
                <div class="chart-container"><canvas id="trendChart"></canvas></div>
            </div>
            <div class="chart-card">
                <div class="chart-title">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path d="M2 2a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V4a2 2 0 00-2-2H2z" />
                    </svg>
                    Status Distribution
                </div>
                <div class="chart-container"><canvas id="statusChart"></canvas></div>
            </div>
            <div class="chart-card">
                <div class="chart-title">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4z" />
                    </svg>
                    Priority Distribution
                </div>
                <div class="chart-container"><canvas id="priorityChart"></canvas></div>
            </div>
            <div class="chart-card">
                <div class="chart-title">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5z" />
                    </svg>
                    Service Type Analysis
                </div>
                <div class="chart-container"><canvas id="serviceChart"></canvas></div>
            </div>
        </div>

        <!-- Transaction Table -->
        <div class="table-container">
            <div class="table-header">
                <h4>
                    <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11 4a1 1 0 10-2 0v4a1 1 0 102 0V7zm-3 1a1 1 0 10-2 0v3a1 1 0 102 0V8zM8 9a1 1 0 00-2 0v2a1 1 0 102 0V9z" clip-rule="evenodd" />
                    </svg>
                    Detailed Transaction Log
                </h4>
                <div style="display: flex; gap: 1rem; align-items: center;">
                    <div class="per-page-selector">
                        <span class="filter-label">Show:</span>
                        <select id="reportPerPageSelect" class="per-page-dropdown">
                            <option value="10">10 per page</option>
                            <option value="20">20 per page</option>
                            <option value="50">50 per page</option>
                            <option value="100">100 per page</option>
                        </select>
                    </div>
                    <div class="export-buttons">
                        <button class="btn-export btn-export-pdf" id="exportPdfBtn">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                            </svg>
                            PDF
                        </button>
                        <button class="btn-export btn-export-excel" id="exportExcelBtn">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                            </svg>
                            Excel
                        </button>
                        <button class="btn-export btn-export-print" id="printBtn">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8h-2v2h2v-2z" clip-rule="evenodd" />
                            </svg>
                            Print
                        </button>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>📅 Date</th>
                            <th>🔢 Queue #</th>
                            <th>👤 Client Name</th>
                            <th>🏷️ Service</th>
                            <th>⭐ Priority</th>
                            <th>📊 Status</th>
                            <th>🪟 Window</th>
                            <th>⏰ Time</th>
                        </tr>
                    </thead>
                    <tbody id="reportTableBody">
                        <tr><td colspan="9" class="empty-state">Loading data...</td></tr>
                    </tbody>
                </table>
            </div>
            <!-- Enhanced Pagination -->
            <div class="enhanced-pagination" id="reportPaginationContainer">
                <!-- Pagination will be loaded here -->
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let trendChart, statusChart, priorityChart, serviceChart;
    let currentPeriod = 'monthly';
    let currentFilters = { 
        startDate: null, 
        endDate: null, 
        serviceType: 'all', 
        statusFilter: 'all' 
    };
    let currentPage = 1;
    let currentPerPage = 10;
    let isLoading = false;

    // Get date range based on period
    function getDateRange(period) {
        const now = new Date();
        let start, end;
        
        if (period === 'daily') {
            start = new Date(now.getFullYear(), now.getMonth(), now.getDate());
            end = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 23, 59, 59);
        } else if (period === 'weekly') {
            const day = now.getDay();
            const diff = day === 0 ? -6 : 1 - day;
            start = new Date(now);
            start.setDate(now.getDate() + diff);
            start.setHours(0, 0, 0, 0);
            end = new Date(start);
            end.setDate(start.getDate() + 6);
            end.setHours(23, 59, 59, 999);
        } else if (period === 'monthly') {
            start = new Date(now.getFullYear(), now.getMonth(), 1);
            end = new Date(now.getFullYear(), now.getMonth() + 1, 0, 23, 59, 59, 999);
        } else if (period === 'yearly') {
            start = new Date(now.getFullYear(), 0, 1);
            end = new Date(now.getFullYear(), 11, 31, 23, 59, 59, 999);
        }
        
        return { 
            start: start.toISOString().split('T')[0], 
            end: end.toISOString().split('T')[0] 
        };
    }

    // Initialize charts
    function initCharts() {
        const trendCtx = document.getElementById('trendChart').getContext('2d');
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const priorityCtx = document.getElementById('priorityChart').getContext('2d');
        const serviceCtx = document.getElementById('serviceChart').getContext('2d');
        
        trendChart = new Chart(trendCtx, {
            type: 'line',
            data: { 
                labels: [], 
                datasets: [
                    { 
                        label: '✅ Completed', 
                        data: [], 
                        borderColor: '#10b981', 
                        backgroundColor: 'rgba(16,185,129,0.1)', 
                        fill: true, 
                        tension: 0.4,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    },
                    { 
                        label: '❌ Cancelled', 
                        data: [], 
                        borderColor: '#ef4444', 
                        backgroundColor: 'rgba(239,68,68,0.1)', 
                        fill: true, 
                        tension: 0.4,
                        pointBackgroundColor: '#ef4444',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    },
                    { 
                        label: '⚠️ No Show', 
                        data: [], 
                        borderColor: '#f59e0b', 
                        backgroundColor: 'rgba(245,158,11,0.1)', 
                        fill: true, 
                        tension: 0.4,
                        pointBackgroundColor: '#f59e0b',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }
                ]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: { 
                    legend: { position: 'top' },
                    tooltip: { mode: 'index', intersect: false }
                }, 
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } 
            }
        });
        
        statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: { 
                labels: ['Completed', 'Cancelled', 'No Show'], 
                datasets: [{ 
                    data: [0,0,0], 
                    backgroundColor: ['#10b981','#ef4444','#f59e0b'], 
                    borderWidth: 0,
                    hoverOffset: 10
                }] 
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: { 
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '60%'
            }
        });
        
        priorityChart = new Chart(priorityCtx, {
            type: 'bar',
            data: { 
                labels: ['Senior', 'Infant', 'PWD', 'Pregnant', 'Regular'], 
                datasets: [{ 
                    label: 'Count', 
                    data: [0,0,0,0,0], 
                    backgroundColor: ['#8b5cf6','#f59e0b','#10b981','#ec4899','#6b7280'], 
                    borderRadius: 8,
                    barPercentage: 0.7
                }] 
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: { legend: { display: false } }, 
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } 
            }
        });
        
        serviceChart = new Chart(serviceCtx, {
            type: 'bar',
            data: { 
                labels: ['NID Registration', 'Status Inquiry', 'Updating'], 
                datasets: [{ 
                    label: 'Count', 
                    data: [0,0,0], 
                    backgroundColor: ['#3b82f6','#8b5cf6','#ec4899'], 
                    borderRadius: 8,
                    barPercentage: 0.7
                }] 
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: { legend: { display: false } }, 
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } 
            }
        });
    }

    // Fetch report data with pagination
    function fetchReportData(page = 1, perPage = null) {
        if (isLoading) return;
        isLoading = true;
        
        let url = '{{ route("operator.reports.data") }}?';
        
        if (currentPeriod === 'custom') {
            if (!currentFilters.startDate || !currentFilters.endDate) {
                console.error('Custom date range requires start and end dates');
                isLoading = false;
                return;
            }
            url += `start_date=${currentFilters.startDate}&end_date=${currentFilters.endDate}`;
        } else {
            const range = getDateRange(currentPeriod);
            url += `start_date=${range.start}&end_date=${range.end}`;
        }
        
        url += `&service_type=${currentFilters.serviceType}&status=${currentFilters.statusFilter}`;
        url += `&page=${page}&per_page=${perPage !== null ? perPage : currentPerPage}`;
        
        const tableBody = document.getElementById('reportTableBody');
        const paginationContainer = document.getElementById('reportPaginationContainer');
        
        if (tableBody) tableBody.style.opacity = '0';
        if (paginationContainer) paginationContainer.style.opacity = '0';
        
        fetch(url, { 
            headers: { 
                'Accept': 'application/json', 
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content 
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateStats(data.stats);
                updateCharts(data.charts);
                updateQuickStats(data.quickStats);
                updateTrends(data.trends);
                updateTableWithPagination(data);
                
                if (data.transactions) {
                    currentPage = data.transactions.current_page;
                    currentPerPage = data.transactions.per_page;
                }
            } else {
                console.error('Error fetching data:', data.message);
            }
            isLoading = false;
        })
        .catch(error => {
            console.error('Error fetching report data:', error);
            isLoading = false;
        });
    }

    // Update table with pagination data
    // Update table with pagination data
function updateTableWithPagination(data) {
    const tbody = document.getElementById('reportTableBody');
    const paginationContainer = document.getElementById('reportPaginationContainer');
    
    if (!tbody) return;
    
    if (data.transactions && data.transactions.data && data.transactions.data.length > 0) {
        let html = '';
        // Calculate starting index based on current page and per page
        const startIndex = (data.transactions.current_page - 1) * data.transactions.per_page;
        
        data.transactions.data.forEach((t, idx) => {
            const indexNumber = startIndex + idx + 1;
            const priorityClass = t.priority_type || 'regular';
            const statusClass = t.status;
            const statusDisplay = t.status.replace('_', ' ').toUpperCase();
            
            html += `
                <tr>
                    <td style="text-align: center; font-weight: 600; color: #6b7280;">${indexNumber}</td>
                    <td>${t.date}</td>
                    <td><span class="queue-badge">${t.q_id}</span></td>
                    <td>${t.client_name}</td>
                    <td>${t.service}</td>
                    <td><span class="priority-badge priority-${priorityClass}">${priorityClass.toUpperCase()}</span></td>
                    <td><span class="status-badge ${statusClass}"><span class="status-dot"></span>${statusDisplay}</span></td>
                    <td>${t.window_num || 'N/A'}</td>
                    <td>${t.served_time}</td>
                </tr>
            `;
        });
        tbody.innerHTML = html;
    } else {
        tbody.innerHTML = '<tr><td colspan="9" class="empty-state">No transactions found</td></tr>';
    }
    
    tbody.style.opacity = '1';
    
    if (paginationContainer && data.transactions) {
        paginationContainer.innerHTML = renderPagination(data.transactions);
        paginationContainer.style.opacity = '1';
        attachPaginationListeners();
    }
}

    // Render pagination HTML
    function renderPagination(paginationData) {
        const currentPage = paginationData.current_page;
        const lastPage = paginationData.last_page;
        const total = paginationData.total;
        const from = paginationData.from;
        const to = paginationData.to;
        
        let html = `
            <div class="showing-info">
                Showing ${from || 0}-${to || 0} of ${total}
            </div>
            <div class="pagination-center-group">
        `;
        
        if (currentPage > 1) {
            html += `<a href="#" class="pagination-arrow" data-page="${currentPage - 1}">←</a>`;
        } else {
            html += `<span class="pagination-arrow disabled">←</span>`;
        }
        
        html += `<div class="pagination-numbers">`;
        
        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(lastPage, currentPage + 2);
        
        if (startPage > 1) {
            html += `<a href="#" class="page-number" data-page="1">1</a>`;
            if (startPage > 2) {
                html += `<span class="page-dots">...</span>`;
            }
        }
        
        for (let i = startPage; i <= endPage; i++) {
            if (i === currentPage) {
                html += `<span class="page-number active">${i}</span>`;
            } else {
                html += `<a href="#" class="page-number" data-page="${i}">${i}</a>`;
            }
        }
        
        if (endPage < lastPage) {
            if (endPage < lastPage - 1) {
                html += `<span class="page-dots">...</span>`;
            }
            html += `<a href="#" class="page-number" data-page="${lastPage}">${lastPage}</a>`;
        }
        
        html += `</div>`;
        
        if (currentPage < lastPage) {
            html += `<a href="#" class="pagination-arrow" data-page="${currentPage + 1}">→</a>`;
        } else {
            html += `<span class="pagination-arrow disabled">→</span>`;
        }
        
        html += `</div>`;
        
        html += `<div style="display: flex; gap: 8px;">`;
        if (currentPage > 1) {
            html += `<a href="#" class="pagination-nav-btn" data-page="1">⏮ First</a>`;
        } else {
            html += `<span class="pagination-nav-btn disabled">⏮ First</span>`;
        }
        
        if (currentPage < lastPage) {
            html += `<a href="#" class="pagination-nav-btn" data-page="${lastPage}">Last ⏭</a>`;
        } else {
            html += `<span class="pagination-nav-btn disabled">Last ⏭</span>`;
        }
        html += `</div>`;
        
        return html;
    }

    // Attach pagination listeners
    function attachPaginationListeners() {
        document.querySelectorAll('#reportPaginationContainer .pagination-arrow:not(.disabled), #reportPaginationContainer .page-number:not(.active), #reportPaginationContainer .pagination-nav-btn:not(.disabled)').forEach(link => {
            link.removeEventListener('click', handlePaginationClick);
            link.addEventListener('click', handlePaginationClick);
        });
    }

    function handlePaginationClick(e) {
        e.preventDefault();
        const page = parseInt(this.dataset.page);
        if (!isNaN(page) && page !== currentPage) {
            currentPage = page;
            fetchReportData(currentPage, currentPerPage);
        }
    }

    // Update statistics
    function updateStats(stats) {
        document.getElementById('totalCompleted').textContent = stats.completed || 0;
        document.getElementById('totalCancelled').textContent = stats.cancelled || 0;
        document.getElementById('totalNoShow').textContent = stats.no_show || 0;
        document.getElementById('totalServed').textContent = stats.served || 0;
    }

    // Update quick stats
    function updateQuickStats(stats) {
        if (stats) {
            document.getElementById('todayCount').textContent = stats.today || 0;
            document.getElementById('weeklyCount').textContent = stats.week || 0;
            document.getElementById('monthlyCount').textContent = stats.month || 0;
            document.getElementById('yearlyCount').textContent = stats.year || 0;
            document.getElementById('avgDaily').textContent = stats.avgDaily || 0;
            document.getElementById('completionRate').textContent = (stats.completionRate || 0) + '%';
        }
    }

    // Update trends
    function updateTrends(trends) {
        if (trends) {
            const completedSpan = document.querySelector('#completedTrend span:first-child');
            const cancelledSpan = document.querySelector('#cancelledTrend span:first-child');
            const noShowSpan = document.querySelector('#noShowTrend span:first-child');
            const servedSpan = document.querySelector('#servedTrend span:first-child');
            
            if (completedSpan) completedSpan.innerHTML = trends.completed || '↑ 0%';
            if (cancelledSpan) cancelledSpan.innerHTML = trends.cancelled || '↓ 0%';
            if (noShowSpan) noShowSpan.innerHTML = trends.no_show || '→ 0%';
            if (servedSpan) servedSpan.innerHTML = trends.served || '↑ 0%';
        }
    }

    // Update charts
    function updateCharts(charts) {
        if (trendChart && charts.daily) {
            trendChart.data.labels = charts.daily.labels;
            trendChart.data.datasets[0].data = charts.daily.completed;
            trendChart.data.datasets[1].data = charts.daily.cancelled;
            trendChart.data.datasets[2].data = charts.daily.no_show || [];
            trendChart.update();
        }
        
        if (statusChart && charts.status) {
            statusChart.data.datasets[0].data = charts.status;
            statusChart.update();
        }
        
        if (priorityChart && charts.priority) {
            priorityChart.data.datasets[0].data = charts.priority;
            priorityChart.update();
        }
        
        if (serviceChart && charts.services) {
            serviceChart.data.datasets[0].data = charts.services;
            serviceChart.update();
        }
    }

    // Toggle custom date fields
    function toggleCustomDateFields() {
        const customFields = document.getElementById('customDateFields');
        if (currentPeriod === 'custom') {
            customFields.style.display = 'flex';
        } else {
            customFields.style.display = 'none';
        }
    }

    // Apply filters
    function applyFilters() {
        if (currentPeriod === 'custom') {
            currentFilters.startDate = document.getElementById('startDate').value;
            currentFilters.endDate = document.getElementById('endDate').value;
            
            if (!currentFilters.startDate || !currentFilters.endDate) {
                Swal.fire({
                    title: 'Error',
                    text: 'Please select both start and end dates',
                    icon: 'error',
                    timer: 2000,
                    showConfirmButton: false
                });
                return;
            }
            
            if (currentFilters.startDate > currentFilters.endDate) {
                Swal.fire({
                    title: 'Error',
                    text: 'Start date cannot be after end date',
                    icon: 'error',
                    timer: 2000,
                    showConfirmButton: false
                });
                return;
            }
        }
        
        currentFilters.serviceType = document.getElementById('serviceType').value;
        currentFilters.statusFilter = document.getElementById('statusFilter').value;
        currentPage = 1;
        
        fetchReportData(1, currentPerPage);
        
        Swal.fire({
            title: 'Filters Applied',
            text: 'Report data has been updated',
            icon: 'success',
            timer: 1500,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    }

    // Reset filters
    function resetFilters() {
        document.getElementById('serviceType').value = 'all';
        document.getElementById('statusFilter').value = 'all';
        
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('startDate').value = today;
        document.getElementById('endDate').value = today;
        
        currentFilters.serviceType = 'all';
        currentFilters.statusFilter = 'all';
        
        if (currentPeriod === 'custom') {
            currentFilters.startDate = today;
            currentFilters.endDate = today;
        }
        
        currentPage = 1;
        fetchReportData(1, currentPerPage);
        
        Swal.fire({
            title: 'Filters Reset',
            text: 'All filters have been reset',
            icon: 'info',
            timer: 1500,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    }

    // Export functions
    function exportPDF() {
        let url = '{{ route("operator.reports.export.pdf") }}?';
        if (currentPeriod === 'custom') {
            url += `start_date=${currentFilters.startDate}&end_date=${currentFilters.endDate}`;
        } else {
            const range = getDateRange(currentPeriod);
            url += `start_date=${range.start}&end_date=${range.end}`;
        }
        url += `&service_type=${currentFilters.serviceType}&status=${currentFilters.statusFilter}`;
        window.location.href = url;
        
        Swal.fire({
            title: 'Exporting PDF',
            text: 'Your report is being generated...',
            icon: 'info',
            timer: 2000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    }

    function exportExcel() {
        let url = '{{ route("operator.reports.export.excel") }}?';
        if (currentPeriod === 'custom') {
            url += `start_date=${currentFilters.startDate}&end_date=${currentFilters.endDate}`;
        } else {
            const range = getDateRange(currentPeriod);
            url += `start_date=${range.start}&end_date=${range.end}`;
        }
        url += `&service_type=${currentFilters.serviceType}&status=${currentFilters.statusFilter}`;
        window.location.href = url;
        
        Swal.fire({
            title: 'Exporting Excel',
            text: 'Your report is being generated...',
            icon: 'info',
            timer: 2000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    }

    function printReport() {
        window.print();
    }

    // Event Listeners
    document.querySelectorAll('.period-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.period-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            currentPeriod = btn.dataset.period;
            toggleCustomDateFields();
            currentPage = 1;
            
            if (currentPeriod !== 'custom') {
                fetchReportData(1, currentPerPage);
            }
        });
    });
    
    document.getElementById('applyFilterBtn')?.addEventListener('click', applyFilters);
    document.getElementById('resetFilterBtn')?.addEventListener('click', resetFilters);
    document.getElementById('exportPdfBtn')?.addEventListener('click', exportPDF);
    document.getElementById('exportExcelBtn')?.addEventListener('click', exportExcel);
    document.getElementById('printBtn')?.addEventListener('click', printReport);
    
    const perPageSelect = document.getElementById('reportPerPageSelect');
    if (perPageSelect) {
        perPageSelect.addEventListener('change', function() {
            const newPerPage = parseInt(this.value);
            if (newPerPage !== currentPerPage) {
                currentPerPage = newPerPage;
                currentPage = 1;
                fetchReportData(1, newPerPage);
            }
        });
    }

    // Initialize
    initCharts();
    fetchReportData(1, 10);
    toggleCustomDateFields();
</script>