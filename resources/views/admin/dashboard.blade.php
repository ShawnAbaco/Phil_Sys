@extends('layouts.admin')

@section('title', 'Dashboard')
@section('pageTitle', 'Dashboard')
@section('pageSubtitle', 'Overview of system statistics and activities')

@php $activeMenu = 'dashboard'; @endphp

@section('content')
    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #0038a8, #ce1126);">
                <svg viewBox="0 0 20 20" fill="white">
                    <path
                        d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Total Users</div>
                <div class="stat-value">{{ $totalUsers ?? 0 }}</div>
                <div class="stat-change positive">+{{ $userGrowth ?? 12 }}% this week</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
                <svg viewBox="0 0 20 20" fill="white">
                    <path fill-rule="evenodd"
                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Appointments Today</div>
                <div class="stat-value">{{ $appointmentsToday ?? 0 }}</div>
                <div class="stat-change {{ ($appointmentChange ?? 0) >= 0 ? 'positive' : 'negative' }}">
                    {{ ($appointmentChange ?? 0) >= 0 ? '+' : '' }}{{ $appointmentChange ?? 8 }} from yesterday
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #ce1126, #ef4444);">
                <svg viewBox="0 0 20 20" fill="white">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Pending Queues</div>
                <div class="stat-value">{{ $pendingQueues ?? 0 }}</div>
                <div class="stat-change {{ ($pendingChange ?? 0) <= 0 ? 'positive' : 'negative' }}">
                    {{ $pendingChange ?? -5 }} from peak
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #34d399);">
                <svg viewBox="0 0 20 20" fill="white">
                    <path fill-rule="evenodd"
                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Completed Today</div>
                <div class="stat-value">{{ $completedToday ?? 0 }}</div>
                <div class="stat-change positive">{{ $completionRate ?? 94 }}% success rate</div>
            </div>
        </div>
    </div>

    <!-- Main Charts Row -->
    <div class="charts-row">
        <div class="chart-card">
            <div class="chart-header">
                <h3>
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path
                            d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                    </svg>
                    Weekly Appointments Trend
                </h3>
                <select class="chart-period" id="periodSelect">
                    <option value="7">Last 7 Days</option>
                    <option value="14">Last 14 Days</option>
                    <option value="30">Last 30 Days</option>
                </select>
            </div>
            <canvas id="appointmentsChart" height="280"></canvas>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <h3>
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z" />
                        <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z" />
                    </svg>
                    Queue Distribution
                </h3>
            </div>
            <canvas id="distributionChart" height="280"></canvas>
        </div>
    </div>

    <!-- Recent Users Table -->
    <div class="table-card">
        <div class="table-header">
            <h3>
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path
                        d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                </svg>
                Recent Activity
            </h3>
            <div class="table-actions">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                        <path fill-rule="evenodd"
                            d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                            clip-rule="evenodd" />
                    </svg>
                    View All
                </a>
                <button class="btn btn-primary" onclick="showAddUserModal()">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Add User
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Designation</th>
                        <th>Window</th>
                        <th>Status</th>
                        <th>Last Active</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentUsers ?? [] as $user)
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">{{ substr($user->name ?? ($user->full_name ?? 'U'), 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="user-name">{{ $user->name ?? ($user->full_name ?? 'Unknown') }}</div>
                                        <div class="user-email">{{ $user->username ?? ($user->email ?? '') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->designation ?? 'N/A' }}</td>
                            <td>{{ $user->window_num ? 'Window ' . $user->window_num : '—' }}</td>
                            <td>
                                @php
                                    $lastActive = $user->last_login_at ?? $user->updated_at;
                                    $isActive = $lastActive && $lastActive->gt(now()->subMinutes(15));
                                @endphp
                                <span class="status-badge {{ $isActive ? 'status-active' : 'status-inactive' }}">
                                    {{ $isActive ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $lastActive ? $lastActive->diffForHumans() : 'Never' }}</td>
                            <td>
                                <div class="action-btns">
                                    <button class="action-btn" onclick="editUser({{ $user->id }})" title="Edit">
                                        <svg viewBox="0 0 20 20" fill="currentColor">
                                            <path
                                                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                    </button>
                                    <button class="action-btn delete" onclick="deleteUser({{ $user->id }})"
                                        title="Delete">
                                        <svg viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                No recent activity found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            let appointmentsChart, distributionChart, hourlyChart, windowChart;

            document.addEventListener('DOMContentLoaded', function() {
                // Initialize all charts
                initAppointmentsChart();
                initDistributionChart();
                initHourlyChart();
                initWindowChart();

                // Period selector change handler
                const periodSelect = document.getElementById('periodSelect');
                if (periodSelect) {
                    periodSelect.addEventListener('change', function() {
                        updateAppointmentsChart(this.value);
                    });
                }
            });

            function initAppointmentsChart() {
                const ctx = document.getElementById('appointmentsChart').getContext('2d');
                appointmentsChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($weeklyLabels ?? ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']) !!},
                        datasets: [{
                            label: 'Appointments',
                            data: {!! json_encode($weeklyData ?? [112, 419, 135, 17, 143, 120, 81]) !!},
                            borderColor: '#0038a8',
                            backgroundColor: 'rgba(0, 56, 168, 0.05)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#0038a8',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: '#1f2937',
                                titleColor: '#f3f4f6',
                                bodyColor: '#d1d5db',
                                padding: 10,
                                cornerRadius: 8
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#e5e7eb',
                                    drawBorder: false
                                },
                                ticks: {
                                    stepSize: 5
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
            }

            function updateAppointmentsChart(days) {
                // Simulate data update - replace with actual API call
                const newData = [15, 22, 18, 24, 21, 16, 12];
                appointmentsChart.data.datasets[0].data = newData;
                appointmentsChart.update();
            }

            function initDistributionChart() {
                const ctx = document.getElementById('distributionChart').getContext('2d');
                distributionChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Status Check', 'Registration', 'Updating', 'Others'],
                        datasets: [{
                            data: {!! json_encode($distributionData ?? [45, 25, 20, 10]) !!},
                            backgroundColor: ['#0038a8', '#ce1126', '#f59e0b', '#10b981'],
                            borderWidth: 0,
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        cutout: '65%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 12,
                                    padding: 15,
                                    font: {
                                        size: 11,
                                        weight: '500'
                                    },
                                    usePointStyle: true
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
                        }
                    }
                });
            }

            function initHourlyChart() {
                const ctx = document.getElementById('hourlyChart').getContext('2d');
                hourlyChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['9 AM', '10 AM', '11 AM', '12 PM', '1 PM', '2 PM', '3 PM', '4 PM', '5 PM'],
                        datasets: [{
                            label: 'Queue Length',
                            data: [5, 12, 18, 15, 8, 14, 22, 19, 11],
                            backgroundColor: 'rgba(0, 56, 168, 0.8)',
                            borderRadius: 8,
                            barPercentage: 0.7,
                            categoryPercentage: 0.8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: '#1f2937',
                                callbacks: {
                                    label: function(context) {
                                        return `Queue: ${context.raw} people`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#e5e7eb'
                                },
                                title: {
                                    display: true,
                                    text: 'Number of People',
                                    color: '#6b7280',
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                title: {
                                    display: true,
                                    text: 'Time',
                                    color: '#6b7280',
                                    font: {
                                        size: 11
                                    }
                                }
                            }
                        }
                    }
                });
            }

            function initWindowChart() {
                const ctx = document.getElementById('windowChart').getContext('2d');
                windowChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Window 1', 'Window 2', 'Window 3', 'Window 4', 'Window 5'],
                        datasets: [{
                                label: 'Processed',
                                data: [42, 38, 45, 32, 28],
                                backgroundColor: '#10b981',
                                borderRadius: 8,
                                barPercentage: 0.6
                            },
                            {
                                label: 'Pending',
                                data: [8, 12, 5, 18, 22],
                                backgroundColor: '#f59e0b',
                                borderRadius: 8,
                                barPercentage: 0.6
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    boxWidth: 12,
                                    font: {
                                        size: 11
                                    },
                                    usePointStyle: true
                                }
                            },
                            tooltip: {
                                backgroundColor: '#1f2937',
                                mode: 'index',
                                intersect: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#e5e7eb'
                                },
                                stacked: false,
                                title: {
                                    display: true,
                                    text: 'Number of Transactions',
                                    color: '#6b7280',
                                    font: {
                                        size: 11
                                    }
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
            }

            function showAddUserModal() {
                alert('Add user modal would open here');
            }

            function editUser(id) {
                alert('Edit user ' + id);
            }

            function deleteUser(id) {
                if (confirm('Are you sure you want to delete this user?')) {
                    alert('User ' + id + ' deleted');
                }
            }
        </script>
    @endpush
@endsection
