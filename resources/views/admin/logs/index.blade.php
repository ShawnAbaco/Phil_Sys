@extends('layouts.admin')

@section('title', 'Activity Logs')
@section('pageTitle', 'Activity Logs')
@section('pageSubtitle', 'View system activity and audit trails')

@php $activeMenu = 'logs'; @endphp

@section('content')
<!-- Filters -->
<div class="filters-bar">
    <div class="filters-left">
        <div class="search-box" style="width: 300px;">
            <svg viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
            </svg>
            <input type="text" id="logSearch" placeholder="Search logs...">
        </div>
        <select class="filter-select" id="userFilter">
            <option value="">All Users</option>
            @foreach($users ?? [] as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
        <select class="filter-select" id="actionFilter">
            <option value="">All Actions</option>
            <option value="create">Create</option>
            <option value="update">Update</option>
            <option value="delete">Delete</option>
            <option value="login">Login</option>
            <option value="logout">Logout</option>
        </select>
        <select class="filter-select" id="dateFilter">
            <option value="today">Today</option>
            <option value="yesterday">Yesterday</option>
            <option value="week">This Week</option>
            <option value="month">This Month</option>
        </select>
    </div>
    <div class="filters-right">
        <button class="btn btn-outline" onclick="exportLogs()">
            <svg viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
            Export Logs
        </button>
    </div>
</div>

<!-- Logs Table -->
<div class="table-card">
    <div class="table-responsive">
        <table class="logs-table">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>IP Address</th>
                    <th>User Agent</th>
                </tr>
            </thead>
            <tbody id="logsTableBody">
                @forelse($logs ?? [] as $log)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($log->created_at)->format('M d, Y H:i:s') }}</td>
                    <td>
                        <div class="user-cell">
                            <div class="user-avatar-small">{{ substr($log->user->name ?? 'S', 0, 2) }}</div>
                            <div>{{ $log->user->name ?? 'System' }}</div>
                        </div>
                    </td>
                    <td>
                        <span class="action-badge action-{{ $log->action }}">
                            {{ ucfirst($log->action) }}
                        </span>
                    </td>
                    <td>{{ $log->description }}</td>
                    <td>{{ $log->ip_address ?? 'N/A' }}</td>
                    <td class="user-agent" title="{{ $log->user_agent }}">
                        {{ \Str::limit($log->user_agent, 50) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="empty-state">
                        No activity logs found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<div class="pagination-container">
    {{ $logs->links() ?? '' }}
</div>

@push('styles')
<style>
    .filters-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .filters-left {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: center;
    }
    
    .filters-right {
        display: flex;
        gap: 10px;
    }
    
    .filter-select {
        padding: 8px 16px;
        border: 2px solid var(--gray-200);
        border-radius: 30px;
        font-size: 0.9rem;
        color: var(--gray-700);
        background: white;
        cursor: pointer;
        min-width: 150px;
    }
    
    .filter-select:focus {
        outline: none;
        border-color: var(--psa-blue);
        box-shadow: 0 0 0 4px var(--psa-blue-light);
    }
    
    .logs-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .logs-table th {
        text-align: left;
        padding: 12px 16px;
        background: var(--gray-50);
        color: var(--gray-600);
        font-weight: 600;
        font-size: 0.85rem;
        border-bottom: 2px solid var(--gray-200);
    }
    
    .logs-table td {
        padding: 12px 16px;
        border-bottom: 1px solid var(--gray-200);
        color: var(--gray-700);
        font-size: 0.9rem;
    }
    
    .logs-table tr:hover td {
        background: var(--gray-50);
    }
    
    .user-cell {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .user-avatar-small {
        width: 28px;
        height: 28px;
        background: var(--psa-blue-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--psa-blue);
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
    }
    
    .action-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 30px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .action-create {
        background: var(--success);
        color: white;
    }
    
    .action-update {
        background: var(--psa-blue);
        color: white;
    }
    
    .action-delete {
        background: var(--danger);
        color: white;
    }
    
    .action-login {
        background: var(--psa-yellow);
        color: #333;
    }
    
    .action-logout {
        background: var(--gray-500);
        color: white;
    }
    
    .user-agent {
        font-size: 0.8rem;
        color: var(--gray-500);
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    @media (max-width: 1200px) {
        .filters-bar {
            flex-direction: column;
            align-items: stretch;
        }
        
        .filters-left {
            flex-direction: column;
        }
        
        .filter-select,
        .search-box {
            width: 100% !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Search functionality
    document.getElementById('logSearch')?.addEventListener('keyup', function() {
        filterLogs();
    });
    
    // Filter changes
    document.getElementById('userFilter')?.addEventListener('change', filterLogs);
    document.getElementById('actionFilter')?.addEventListener('change', filterLogs);
    document.getElementById('dateFilter')?.addEventListener('change', filterLogs);
    
    function filterLogs() {
        const searchTerm = document.getElementById('logSearch').value.toLowerCase();
        const userFilter = document.getElementById('userFilter').value;
        const actionFilter = document.getElementById('actionFilter').value;
        const dateFilter = document.getElementById('dateFilter').value;
        
        const rows = document.querySelectorAll('#logsTableBody tr');
        
        rows.forEach(row => {
            let show = true;
            
            // Search filter
            if (searchTerm) {
                const text = row.textContent.toLowerCase();
                if (!text.includes(searchTerm)) show = false;
            }
            
            // User filter
            if (show && userFilter) {
                const userCell = row.querySelector('td:nth-child(2)');
                if (!userCell.textContent.includes(userFilter)) show = false;
            }
            
            // Action filter
            if (show && actionFilter) {
                const actionCell = row.querySelector('td:nth-child(3) .action-badge');
                if (actionCell && !actionCell.textContent.toLowerCase().includes(actionFilter.toLowerCase())) show = false;
            }
            
            // Date filter
            if (show && dateFilter) {
                const dateCell = row.querySelector('td:nth-child(1)');
                // Implement date filtering logic here
            }
            
            row.style.display = show ? '' : 'none';
        });
    }
    
    function exportLogs() {
        showLoading();
        setTimeout(() => {
            hideLoading();
            Swal.fire({
                title: 'Export Started',
                text: 'Your logs are being exported.',
                icon: 'success',
                confirmButtonColor: '#0038A8'
            });
        }, 1500);
    }
</script>
@endpush
@endsection