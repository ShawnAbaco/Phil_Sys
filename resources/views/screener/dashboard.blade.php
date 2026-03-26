{{-- resources/views/screener/dashboard.blade.php --}}
<x-header title="Screener Dashboard" />

<div class="app-container">
    <x-screener.sidebar />
    
    <style>
      

        /* Welcome Section */
        .welcome-section {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border-radius: 24px;
            padding: 32px;
            margin-bottom: 32px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .welcome-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 400px;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="rgba(255,255,255,0.03)"/></svg>') repeat;
            opacity: 0.3;
        }

        .welcome-section h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .welcome-section p {
            font-size: 16px;
            opacity: 0.9;
            margin-bottom: 20px;
        }

        .date-time {
            font-size: 14px;
            opacity: 0.8;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }

        .stat-card::before {
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

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .stat-title {
            font-size: 13px;
            font-weight: 500;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-icon.blue { background: linear-gradient(135deg, #3b82f6, #2563eb); }
        .stat-icon.orange { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .stat-icon.green { background: linear-gradient(135deg, #10b981, #059669); }
        .stat-icon.red { background: linear-gradient(135deg, #ef4444, #dc2626); }
        .stat-icon.purple { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .stat-trend {
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .trend-up { color: #10b981; }
        .trend-down { color: #ef4444; }

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
        }

        .view-all {
            color: #2563eb;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
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

        .activity-icon.completed { background: #d1fae5; color: #059669; }
        .activity-icon.cancelled { background: #fee2e2; color: #dc2626; }
        .activity-icon.issued { background: #dbeafe; color: #2563eb; }

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

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 32px;
        }

        .action-btn {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            border-color: #2563eb;
        }

        .action-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            color: white;
        }

        .action-btn h4 {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .action-btn p {
            font-size: 12px;
            color: #64748b;
        }

        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .charts-grid {
                grid-template-columns: 1fr;
            }
            .quick-actions {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <main class="main-content">
       

        <!-- Quick Actions -->
        <div class="quick-actions">
            <a href="{{ route('screener.appointments') }}" class="action-btn">
                <div class="action-icon">
                    <svg viewBox="0 0 20 20" fill="currentColor" width="24" height="24">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                    </svg>
                </div>
                <h4>New Appointment</h4>
                <p>Issue a new appointment</p>
            </a>
            <a href="{{ route('screener.transactions') }}" class="action-btn">
                <div class="action-icon">
                    <svg viewBox="0 0 20 20" fill="currentColor" width="24" height="24">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V8a2 2 0 00-2-2h-5L9 4H4zm7 4a1 1 0 10-2 0v3.586l-.293-.293a1 1 0 10-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 10-1.414-1.414l-.293.293V8z" clip-rule="evenodd" />
                    </svg>
                </div>
                <h4>View Transactions</h4>
                <p>See recent transactions</p>
            </a>
            <a href="{{ route('screener.reports') }}" class="action-btn">
                <div class="action-icon">
                    <svg viewBox="0 0 20 20" fill="currentColor" width="24" height="24">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11 4a1 1 0 10-2 0v4a1 1 0 102 0V7zm-3 1a1 1 0 10-2 0v3a1 1 0 102 0V8zM8 9a1 1 0 00-2 0v2a1 1 0 102 0V9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <h4>Generate Reports</h4>
                <p>View detailed analytics</p>
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Total Appointments</span>
                    <div class="stat-icon blue">
                        <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                        </svg>
                    </div>
                </div>
                <div class="stat-value">{{ $totalToday ?? 0 }}</div>
                <div class="stat-trend">
                    @if(($trends['total'] ?? 0) > 0)
                        <span class="trend-up">↑ {{ $trends['total'] ?? 0 }}%</span>
                        <span>from yesterday</span>
                    @else
                        <span class="trend-down">→ 0%</span>
                        <span>from yesterday</span>
                    @endif
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Pending</span>
                    <div class="stat-icon orange">
                        <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <div class="stat-value">{{ $pendingCount ?? 0 }}</div>
                <div class="stat-trend">
                    @if(($trends['pending'] ?? 0) > 0)
                        <span class="trend-up">↑ {{ $trends['pending'] ?? 0 }}%</span>
                    @elseif(($trends['pending'] ?? 0) < 0)
                        <span class="trend-down">↓ {{ abs($trends['pending']) }}%</span>
                    @else
                        <span class="trend-down">→ 0%</span>
                    @endif
                    <span>from yesterday</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Completed</span>
                    <div class="stat-icon green">
                        <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <div class="stat-value">{{ $completedCount ?? 0 }}</div>
                <div class="stat-trend">
                    @if(($trends['completed'] ?? 0) > 0)
                        <span class="trend-up">↑ {{ $trends['completed'] ?? 0 }}%</span>
                    @elseif(($trends['completed'] ?? 0) < 0)
                        <span class="trend-down">↓ {{ abs($trends['completed']) }}%</span>
                    @else
                        <span class="trend-down">→ 0%</span>
                    @endif
                    <span>from yesterday</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Cancelled</span>
                    <div class="stat-icon red">
                        <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <div class="stat-value">{{ $cancelledCount ?? 0 }}</div>
                <div class="stat-trend">
                    @if(($trends['cancelled'] ?? 0) > 0)
                        <span class="trend-up">↑ {{ $trends['cancelled'] ?? 0 }}%</span>
                    @elseif(($trends['cancelled'] ?? 0) < 0)
                        <span class="trend-down">↓ {{ abs($trends['cancelled']) }}%</span>
                    @else
                        <span class="trend-down">→ 0%</span>
                    @endif
                    <span>from yesterday</span>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-grid">
            <div class="chart-card">
                <div class="chart-header">
                    <h3>Daily Activity (Last 7 Days)</h3>
                    <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18" style="color: #94a3b8;">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11 4a1 1 0 10-2 0v4a1 1 0 102 0V7zm-3 1a1 1 0 10-2 0v3a1 1 0 102 0V8zM8 9a1 1 0 00-2 0v2a1 1 0 102 0V9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="chart-container">
                    <canvas id="dailyChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-header">
                    <h3>Status Distribution</h3>
                    <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18" style="color: #94a3b8;">
                        <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z" />
                        <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z" />
                    </svg>
                </div>
                <div class="chart-container">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="recent-section">
            <div class="recent-header">
                <h3>Recent Activity</h3>
                <a href="{{ route('screener.transactions') }}" class="view-all">View All →</a>
            </div>
            <div class="activity-list">
                @forelse($recentActivities ?? [] as $activity)
                    <div class="activity-item">
                        <div class="activity-info">
                            <div class="activity-icon {{ $activity['type'] }}">
                                @if($activity['type'] == 'completed')
                                    <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                @elseif($activity['type'] == 'cancelled')
                                    <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                @else
                                    <svg viewBox="0 0 20 20" fill="currentColor" width="20" height="20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
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
            datasets: [
                {
                    label: 'Issued',
                    data: {!! json_encode($dailyIssued ?? []) !!},
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Completed',
                    data: {!! json_encode($dailyCompleted ?? []) !!},
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
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
</script>