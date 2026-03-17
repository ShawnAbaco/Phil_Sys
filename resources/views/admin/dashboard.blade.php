@extends('layouts.admin')

@section('title', 'Dashboard')
@section('pageTitle', 'Dashboard')
@section('pageSubtitle', 'Overview of system statistics and activities')

@php $activeMenu = 'dashboard'; @endphp

@section('content')
<!-- Stats Grid -->
<div class="stats-grid">
    <x-admin.stat-card 
        label="Total Users"
        value="{{ $totalUsers }}"
        change="+{{ $userGrowth }}% this week"
        changeType="positive"
        color="linear-gradient(135deg, var(--psa-blue), var(--psa-red))"
    >
        <svg viewBox="0 0 20 20" fill="currentColor">
            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
        </svg>
    </x-admin.stat-card>

    <x-admin.stat-card 
        label="Appointments Today"
        value="{{ $appointmentsToday }}"
        change="{{ $appointmentChange >= 0 ? '+' : '' }}{{ $appointmentChange }} from yesterday"
        changeType="{{ $appointmentChange >= 0 ? 'positive' : 'negative' }}"
        color="linear-gradient(135deg, #F59E0B, #FBBF24)"
    >
        <svg viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
        </svg>
    </x-admin.stat-card>

    <x-admin.stat-card 
        label="Pending Queues"
        value="{{ $pendingQueues }}"
        change="{{ $pendingChange }} from peak"
        changeType="{{ $pendingChange <= 0 ? 'positive' : 'negative' }}"
        color="linear-gradient(135deg, var(--psa-red), #EF4444)"
    >
        <svg viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
        </svg>
    </x-admin.stat-card>

    <x-admin.stat-card 
        label="Completed Today"
        value="{{ $completedToday }}"
        change="{{ $completionRate }}% success rate"
        changeType="positive"
        color="linear-gradient(135deg, var(--success), #34D399)"
    >
        <svg viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
        </svg>
    </x-admin.stat-card>
</div>

<!-- Charts Row -->
<div class="charts-row">
    <div class="chart-card">
        <div class="chart-header">
            <h3>
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                </svg>
                Appointments Overview (Last 7 Days)
            </h3>
        </div>
        <div style="height: 250px; display: flex; align-items: flex-end; gap: 10px; padding: 20px 0;">
            @php
                $maxValue = $weeklyStats ? max(array_column($weeklyStats, 'count')) : 1;
            @endphp
            @foreach($weeklyStats as $stat)
                <div style="flex: 1; text-align: center;">
                    <div style="height: {{ ($stat['count'] / $maxValue) * 200 }}px; background: var(--psa-blue-light); border-radius: 8px 8px 0 0; margin-bottom: 8px; min-height: 4px;"></div>
                    <span style="font-size: 0.8rem; color: var(--gray-600);">{{ $stat['day'] }}</span>
                    <span style="font-size: 0.7rem; color: var(--gray-500); display: block;">{{ $stat['count'] }}</span>
                </div>
            @endforeach
        </div>
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
        <div style="height: 250px; display: flex; align-items: center; justify-content: center;">
            <div style="width: 200px; height: 200px; border-radius: 50%; background: conic-gradient(
                var(--psa-blue) 0deg {{ $distribution['status'] * 3.6 }}deg, 
                var(--psa-red) {{ $distribution['status'] * 3.6 }}deg {{ ($distribution['status'] + $distribution['registration']) * 3.6 }}deg, 
                var(--psa-yellow) {{ ($distribution['status'] + $distribution['registration']) * 3.6 }}deg {{ ($distribution['status'] + $distribution['registration'] + $distribution['updating']) * 3.6 }}deg, 
                var(--success) {{ ($distribution['status'] + $distribution['registration'] + $distribution['updating']) * 3.6 }}deg 360deg
            );"></div>
        </div>
        <div style="display: flex; justify-content: center; gap: 20px; margin-top: 20px; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 6px;">
                <div style="width: 12px; height: 12px; background: var(--psa-blue); border-radius: 4px;"></div>
                <span style="font-size: 0.8rem;">Status ({{ $distribution['status'] }}%)</span>
            </div>
            <div style="display: flex; align-items: center; gap: 6px;">
                <div style="width: 12px; height: 12px; background: var(--psa-red); border-radius: 4px;"></div>
                <span style="font-size: 0.8rem;">Registration ({{ $distribution['registration'] }}%)</span>
            </div>
            <div style="display: flex; align-items: center; gap: 6px;">
                <div style="width: 12px; height: 12px; background: var(--psa-yellow); border-radius: 4px;"></div>
                <span style="font-size: 0.8rem;">Updating ({{ $distribution['updating'] }}%)</span>
            </div>
            <div style="display: flex; align-items: center; gap: 6px;">
                <div style="width: 12px; height: 12px; background: var(--success); border-radius: 4px;"></div>
                <span style="font-size: 0.8rem;">Other ({{ $distribution['other'] }}%)</span>
            </div>
        </div>
    </div>
</div>

<!-- Recent Users Table -->
<div class="table-card">
    <div class="table-header">
        <h3>
            <svg viewBox="0 0 20 20" fill="currentColor">
                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
            </svg>
            Recent Users
        </h3>
        <div class="table-actions">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                </svg>
                View All
            </a>
            <button class="btn btn-primary" onclick="showAddUserModal()">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
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
                @forelse($recentUsers as $user)
                <tr>
                    <td>
                        <div class="user-info">
                            <div class="user-avatar">{{ substr($user->name ?? $user->full_name ?? 'U', 0, 2) }}</div>
                            <div>
                                <div style="font-weight: 600;">{{ $user->name ?? $user->full_name ?? 'Unknown' }}</div>
                                <div style="font-size: 0.8rem; color: var(--gray-500);">{{ $user->username ?? $user->email ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $user->designation ?? 'N/A' }}</td>
                    <td>{{ $user->window_num ? 'Window '.$user->window_num : '—' }}</td>
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
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                            </button>
                            <button class="action-btn delete" onclick="deleteUser({{ $user->id }})" title="Delete">
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="empty-state">
                        No users found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Quick Actions -->
<div class="quick-actions">
    <div class="quick-action-card" onclick="showAddUserModal()">
        <div class="quick-action-icon">
            <svg viewBox="0 0 20 20" fill="currentColor">
                <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
            </svg>
        </div>
        <div class="quick-action-title">Add User</div>
        <div class="quick-action-desc">Create new operator or screener account</div>
    </div>

    <div class="quick-action-card" onclick="generateReport()">
        <div class="quick-action-icon">
            <svg viewBox="0 0 20 20" fill="currentColor">
                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="quick-action-title">Generate Report</div>
        <div class="quick-action-desc">Export system analytics and logs</div>
    </div>

    <div class="quick-action-card" onclick="window.location.href='{{ route('admin.settings.index') }}'">
        <div class="quick-action-icon">
            <svg viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="quick-action-title">System Settings</div>
        <div class="quick-action-desc">Configure system parameters</div>
    </div>

    <div class="quick-action-card" onclick="window.location.href='#'">
        <div class="quick-action-icon">
            <svg viewBox="0 0 20 20" fill="currentColor">
                <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM14 11a1 1 0 011 1v1h1a1 1 0 110 2h-1v1a1 1 0 11-2 0v-1h-1a1 1 0 110-2h1v-1a1 1 0 011-1z" />
            </svg>
        </div>
        <div class="quick-action-title">Window Management</div>
        <div class="quick-action-desc">Assign and configure windows</div>
    </div>
</div>

@push('scripts')
<script>
    function showAddUserModal() {
        showLoading();
        setTimeout(() => {
            hideLoading();
            alert('Add user modal would open here');
        }, 500);
    }

    function editUser(id) {
        showLoading();
        setTimeout(() => {
            hideLoading();
            alert('Edit user ' + id);
        }, 500);
    }

    function deleteUser(id) {
        if (confirm('Are you sure you want to delete this user?')) {
            showLoading();
            setTimeout(() => {
                hideLoading();
                alert('User ' + id + ' deleted');
            }, 500);
        }
    }

    function generateReport() {
        showLoading();
        setTimeout(() => {
            hideLoading();
            alert('Report generation would start here');
        }, 500);
    }
</script>
@endpush
@endsection