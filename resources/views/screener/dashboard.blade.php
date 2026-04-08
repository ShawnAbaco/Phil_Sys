{{-- resources/views/screener/dashboard.blade.php --}}
<x-header title="Screener Dashboard" />

<div class="app-container">
    <x-screener.sidebar />

    <main class="main-content">
        <!-- Page Header with Breadcrumb -->
        <div class="page-header">
            <div class="page-title1">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                </svg>
                <h1>Dashboard</h1>
            </div>
            <div class="breadcrumb">
                <a href="{{ route('screener.dashboard') }}">Home</a>
                <span>/</span>
                <span>Dashboard</span>
            </div>
        </div>

        
        
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

        <!-- Recent Activity Section -->
        <div class="recent-section">
            <div class="recent-header">
                <h3>
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
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