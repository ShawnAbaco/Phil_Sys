{{-- resources/views/screener/dashboard.blade.php --}}
<x-header title="Screener Dashboard" />

<div class="app-container">
    <x-screener.sidebar />

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
            border-radius: 12px;
            background: #f1f5f9;
            transition: all 0.2s ease;
        }

        .breadcrumb a:hover {
            background: #2563eb;
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
        }

        .breadcrumb a:hover svg {
            color: #fff;
        }

        .breadcrumb span {
            color: #676e79;
        }

        /* Welcome Banner */
        .welcome-banner {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border-radius: 24px;
            padding: 32px;
            margin-bottom: 32px;
            position: relative;
            overflow: hidden;
            color: white;
        }

        .welcome-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
        }

        .welcome-banner::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
        }

        .welcome-content {
            position: relative;
            z-index: 1;
        }

        .welcome-greeting {
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            opacity: 0.8;
            margin-bottom: 0.5rem;
        }

        .welcome-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .welcome-date {
            font-size: 0.875rem;
            opacity: 0.9;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 32px;
        }

        .action-btn {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 24px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
            position: relative;
            overflow: hidden;
        }

        .action-btn:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
            border-color: #2563eb;
        }

        .action-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #2563eb, #7c3aed);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .action-btn:hover::before {
            opacity: 1;
        }

        .action-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border-radius: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            color: white;
        }

        .action-icon svg {
            width: 28px;
            height: 28px;
        }

        .action-btn h4 {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 6px;
        }

        .action-btn p {
            font-size: 13px;
            color: #64748b;
        }

 

        /* Charts Section */
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
            margin-bottom: 32px;
        }

        .chart-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .chart-header h3 {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
        }

        .chart-container {
            height: 280px;
            position: relative;
        }

        /* Recent Activity Section */
        .recent-section {
            background: white;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .recent-header {
            padding: 20px 24px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .recent-header h3 {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .recent-header h3 svg {
            width: 20px;
            height: 20px;
            color: #2563eb;
        }

        .view-all {
            color: #2563eb;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .view-all:hover {
            transform: translateX(4px);
            color: #1d4ed8;
        }

        .activity-list {
            padding: 0 24px;
        }

        .activity-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .activity-icon.completed {
            background: #d1fae5;
            color: #059669;
        }

        .activity-icon.cancelled {
            background: #fee2e2;
            color: #dc2626;
        }

        .activity-icon.issued {
            background: #dbeafe;
            color: #2563eb;
        }

        .activity-details h4 {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .activity-details p {
            font-size: 12px;
            color: #64748b;
        }

        .activity-time {
            font-size: 12px;
            color: #94a3b8;
        }

        /* Main Content */
        .main-content {
            max-width: 1600px;
            margin: 30px auto;
            padding: 0 30px;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .charts-grid {
                grid-template-columns: 1fr;
            }

            .quick-actions {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .quick-actions {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .main-content {
                padding: 0 16px;
            }
        }
    </style>

    <main class="main-content">
        <!-- Page Header with Breadcrumb -->
        <div class="page-header">
            <div class="page-title1">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path
                        d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                </svg>
                <h1>Screener Dashboard</h1>
            </div>
            <div class="breadcrumb">
                <a href="{{ route('screener.dashboard') }}">
                    <svg viewBox="0 0 20 20" fill="currentColor" width="14" height="14">
                        <path
                            d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                    Home
                </a>
                <span>/</span>
                <span class="current">Dashboard</span>
            </div>
        </div>

        <!-- Welcome Banner -->
        <div class="welcome-banner">
            <div class="welcome-content">
                <div class="welcome-greeting">Welcome Back, Screener!</div>
                <div class="welcome-title">{{ Auth::user()->name ?? 'Screener' }}</div>
                <div class="welcome-date">
                    <svg viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                        <path fill-rule="evenodd"
                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ \Carbon\Carbon::now('Asia/Manila')->format('l, F j, Y') }}
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Total Appointments</span>
                    <div class="stat-icon blue">
                        <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                            <path
                                d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                        </svg>
                    </div>
                </div>
                <div class="stat-value">{{ $totalToday ?? 0 }}</div>
                <div class="stat-trend">
                    @if (($trends['total'] ?? 0) > 0)
                        <span class="trend-up">↑ {{ $trends['total'] ?? 0 }}%</span>
                    @else
                        <span class="trend-neutral">→ 0%</span>
                    @endif
                    <span>vs yesterday</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Pending</span>
                    <div class="stat-icon orange">
                        <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <div class="stat-value">{{ $pendingCount ?? 0 }}</div>
                <div class="stat-trend">
                    @if (($trends['pending'] ?? 0) > 0)
                        <span class="trend-up">↑ {{ $trends['pending'] }}%</span>
                    @elseif(($trends['pending'] ?? 0) < 0)
                        <span class="trend-down">↓ {{ abs($trends['pending']) }}%</span>
                    @else
                        <span class="trend-neutral">→ 0%</span>
                    @endif
                    <span>vs yesterday</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Completed</span>
                    <div class="stat-icon green">
                        <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <div class="stat-value">{{ $completedCount ?? 0 }}</div>
                <div class="stat-trend">
                    @if (($trends['completed'] ?? 0) > 0)
                        <span class="trend-up">↑ {{ $trends['completed'] }}%</span>
                    @elseif(($trends['completed'] ?? 0) < 0)
                        <span class="trend-down">↓ {{ abs($trends['completed']) }}%</span>
                    @else
                        <span class="trend-neutral">→ 0%</span>
                    @endif
                    <span>vs yesterday</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Cancelled</span>
                    <div class="stat-icon red">
                        <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <div class="stat-value">{{ $cancelledCount ?? 0 }}</div>
                <div class="stat-trend">
                    @if (($trends['cancelled'] ?? 0) > 0)
                        <span class="trend-up">↑ {{ $trends['cancelled'] }}%</span>
                    @elseif(($trends['cancelled'] ?? 0) < 0)
                        <span class="trend-down">↓ {{ abs($trends['cancelled']) }}%</span>
                    @else
                        <span class="trend-neutral">→ 0%</span>
                    @endif
                    <span>vs yesterday</span>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-grid">
            <div class="chart-card">
                <div class="chart-header">
                    <h3>Daily Activity (Last 7 Days)</h3>
                    <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18" style="color: #94a3b8;">
                        <path fill-rule="evenodd"
                            d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11 4a1 1 0 10-2 0v4a1 1 0 102 0V7zm-3 1a1 1 0 10-2 0v3a1 1 0 102 0V8zM8 9a1 1 0 00-2 0v2a1 1 0 102 0V9z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="chart-container">
                    <canvas id="dailyChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-header">
                    <h3>Status Distribution</h3>
                    <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18"
                        style="color: #94a3b8;">
                        <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z" />
                        <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z" />
                    </svg>
                </div>
                <div class="chart-container">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
         <!-- Priority Distribution -->
        <div class="priority-section">
            <div class="priority-header">
                <h3>Priority Distribution</h3>
            </div>
            <div class="priority-list">
                @php
                    $priorities = [
                        'senior' => [
                            'name' => 'Senior Citizen',
                            'color' => '#8b5cf6',
                            'count' => $priorityCounts['senior'] ?? 0,
                        ],
                        'infant' => [
                            'name' => 'Infant',
                            'color' => '#f59e0b',
                            'count' => $priorityCounts['infant'] ?? 0,
                        ],
                        'pwd' => ['name' => 'PWD', 'color' => '#10b981', 'count' => $priorityCounts['pwd'] ?? 0],
                        'pregnant' => [
                            'name' => 'Pregnant',
                            'color' => '#ec4899',
                            'count' => $priorityCounts['pregnant'] ?? 0,
                        ],
                        'regular' => [
                            'name' => 'Regular',
                            'color' => '#64748b',
                            'count' => $priorityCounts['regular'] ?? 0,
                        ],
                    ];
                    $maxCount = max(array_column($priorities, 'count')) ?: 1;
                @endphp
                @foreach ($priorities as $key => $priority)
                    <div class="priority-item">
                        <div class="priority-info">
                            <div class="priority-color {{ $key }}"></div>
                            <span class="priority-name">{{ $priority['name'] }}</span>
                        </div>
                        <div style="flex: 1; margin: 0 20px;">
                            <div class="priority-bar">
                                <div class="priority-bar-fill"
                                    style="width: {{ ($priority['count'] / $maxCount) * 100 }}%; background: {{ $priority['color'] }};">
                                </div>
                            </div>
                        </div>
                        <div class="priority-count">{{ $priority['count'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="recent-section">
            <div class="recent-header">
                <h3>
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                            clip-rule="evenodd" />
                    </svg>
                    Recent Activity
                </h3>
                <a href="{{ route('screener.transactions') }}" class="view-all">View All →</a>
            </div>
            <div class="activity-list">
                @forelse($recentActivities ?? [] as $activity)
                    <div class="activity-item">
                        <div class="activity-info">
                            <div class="activity-icon {{ $activity['type'] }}">
                                @if ($activity['type'] == 'completed')
                                    <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                @elseif($activity['type'] == 'cancelled')
                                    <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                                        <path fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                @else
                                    <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                            clip-rule="evenodd" />
                                    </svg>
                                @endif
                            </div>
                            <div class="activity-details">
                                <h4>{{ $activity['title'] }}</h4>
                                <p>{{ $activity['description'] }}</p>
                            </div>
                        </div>
                        <div class="activity-time">{{ $activity['time'] }}</div>
                    </div>
                @empty
                    <div class="activity-item">
                        <div class="activity-info">
                            <div class="activity-details">
                                <p style="color: #94a3b8;">No recent activity</p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Daily Chart
    const dailyCtx = document.getElementById('dailyChart').getContext('2d');
    new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($dailyLabels ?? []) !!},
            datasets: [{
                    label: 'Issued',
                    data: {!! json_encode($dailyIssued ?? []) !!},
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#2563eb',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                },
                {
                    label: 'Completed',
                    data: {!! json_encode($dailyCompleted ?? []) !!},
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#10b981',
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
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        boxWidth: 10
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    },
                    grid: {
                        color: '#e2e8f0'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Serving', 'Completed', 'Cancelled', 'No Show'],
            datasets: [{
                data: [
                    {{ $statusData['pending'] ?? 0 }},
                    {{ $statusData['serving'] ?? 0 }},
                    {{ $statusData['completed'] ?? 0 }},
                    {{ $statusData['cancelled'] ?? 0 }},
                    {{ $statusData['no_show'] ?? 0 }}
                ],
                backgroundColor: ['#f59e0b', '#3b82f6', '#10b981', '#ef4444', '#6b7280'],
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
                        usePointStyle: true,
                        boxWidth: 10,
                        padding: 15
                    }
                },
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
</script>
