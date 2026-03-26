@extends('layouts.admin')

@section('title', 'Manage Users')
@section('pageTitle', 'User Management')
@section('pageSubtitle', 'Manage system users, operators and screeners')

@php $activeMenu = 'users'; @endphp

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <div class="search-box" style="width: 300px;">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                        clip-rule="evenodd" />
                </svg>
                <input type="text" id="userSearch" placeholder="Search users..." style="width: 100%;">
            </div>
            <select class="filter-select" id="roleFilter">
                <option value="">All Roles</option>
                <option value="screener">Screener</option>
                <option value="operator">Operator</option>
                <option value="administrator">Administrator</option>
            </select>
            <select class="filter-select" id="statusFilter">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="suspended">Suspended</option>
            </select>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18">
                <path fill-rule="evenodd"
                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                    clip-rule="evenodd" />
            </svg>
            Add New User
        </a>
    </div>

    <div class="table-card">
        <div class="table-responsive">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Designation</th>
                        <th>Window</th>
                        <th>Status</th>
                        <th>Last Active</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                    @forelse($users as $user)
                        <tr data-role="{{ strtolower(str_replace(' ', '-', $user->designation ?? 'user')) }}"
                            data-status="{{ $user->status ?? 'active' }}">
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
                            <td>
                                <span
                                    class="role-badge role-{{ strtolower(str_replace(' ', '-', $user->designation ?? 'user')) }}">
                                    {{ $user->designation ?? 'User' }}
                                </span>
                            </td>
                            <td>{{ $user->window_num ? 'Window ' . $user->window_num : '—' }}</td>
                            <td>
                                @php
                                    $lastActive = $user->last_login_at ?? $user->updated_at;
                                    $isActive = $lastActive && $lastActive->gt(now()->subMinutes(15));
                                @endphp
                                <span class="status-badge {{ $isActive ? 'status-active' : 'status-inactive' }}">
                                    <span class="status-dot"></span>
                                    {{ $isActive ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $lastActive ? $lastActive->diffForHumans() : 'Never' }}</td>
                            <td>{{ $user->created_at ? $user->created_at->format('M d, Y') : '—' }}</td>
                            <td>
                                <div class="action-btns">
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="action-btn" title="View">
                                        <svg viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                            <path fill-rule="evenodd"
                                                d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="action-btn" title="Edit">
                                        <svg viewBox="0 0 20 20" fill="currentColor">
                                            <path
                                                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                    </a>
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
                            <td colspan="7" class="empty-state">
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 100-2 1 1 0 000 2zm7-1a1 1 0 11-2 0 1 1 0 012 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                <p>No users found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="pagination-container">
        {{ $users->links() }}
    </div>

    @push('scripts')
        <script>
            function deleteUser(id) {
                Swal.fire({
                    title: 'Delete User?',
                    text: 'This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#CE1126',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, delete',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        showLoading();
                        fetch(`/admin/users/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                hideLoading();
                                if (data.success) {
                                    location.reload();
                                } else {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: data.message || 'Failed to delete user.',
                                        icon: 'error',
                                        confirmButtonColor: '#CE1126'
                                    });
                                }
                            })
                            .catch(error => {
                                hideLoading();
                                console.error('Error:', error);
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'An error occurred. Please try again.',
                                    icon: 'error',
                                    confirmButtonColor: '#CE1126'
                                });
                            });
                    }
                });
            }

            // Search functionality
            const searchInput = document.getElementById('userSearch');
            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    const searchTerm = this.value.toLowerCase();
                    const rows = document.querySelectorAll('#usersTableBody tr');

                    rows.forEach(row => {
                        if (row.querySelector('.empty-state')) return;
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(searchTerm) ? '' : 'none';
                    });
                });
            }

            // Filter functionality
            const roleFilter = document.getElementById('roleFilter');
            const statusFilter = document.getElementById('statusFilter');

            function filterUsers() {
                const roleValue = roleFilter?.value.toLowerCase() || '';
                const statusValue = statusFilter?.value.toLowerCase() || '';
                const rows = document.querySelectorAll('#usersTableBody tr');

                rows.forEach(row => {
                    if (row.querySelector('.empty-state')) return;
                    const role = row.getAttribute('data-role') || '';
                    const status = row.getAttribute('data-status') || '';

                    let show = true;
                    if (roleValue && !role.includes(roleValue)) show = false;
                    if (statusValue && status !== statusValue) show = false;

                    row.style.display = show ? '' : 'none';
                });
            }

            if (roleFilter) roleFilter.addEventListener('change', filterUsers);
            if (statusFilter) statusFilter.addEventListener('change', filterUsers);
        </script>
    @endpush
@endsection
