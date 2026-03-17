@extends('layouts.admin')

@section('title', 'Manage Users')
@section('pageTitle', 'User Management')
@section('pageSubtitle', 'Manage system users, operators and screeners')

@php $activeMenu = 'users'; @endphp

@section('content')
<!-- Header with Actions -->
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-4">
        <div class="search-box" style="width: 300px;">
            <svg viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
            </svg>
            <input type="text" id="userSearch" placeholder="Search users..." style="width: 100%;">
        </div>
        <select class="filter-select" id="roleFilter">
            <option value="">All Roles</option>
            <option value="screener">Screener</option>
            <option value="operator">Operator</option>
            <option value="admin">Administrator</option>
        </select>
        <select class="filter-select" id="statusFilter">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
    </div>
    <button class="btn btn-primary" onclick="showAddUserModal()">
        <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18">
            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
        Add New User
    </button>
</div>

<!-- Users Table -->
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
                    <td>
                        <span class="role-badge role-{{ strtolower(str_replace(' ', '-', $user->designation ?? 'user')) }}">
                            {{ $user->designation ?? 'User' }}
                        </span>
                    </td>
                    <td>{{ $user->window_num ? 'Window '.$user->window_num : '—' }}</td>
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
                            <button class="action-btn" onclick="editUser({{ $user->id }})" title="Edit">
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                            </button>
                            <button class="action-btn" onclick="resetPassword({{ $user->id }})" title="Reset Password">
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
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
                    <td colspan="7" class="empty-state">
                        No users found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<div class="pagination-container">
    {{ $users->links() }}
</div>

<!-- Add User Modal -->
<div class="modal" id="addUserModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add New User</h3>
            <button class="modal-close" onclick="closeModal('addUserModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="addUserForm" method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label>Designation</label>
                        <select name="designation" required>
                            <option value="">Select Role</option>
                            <option value="Screener">Screener</option>
                            <option value="Registration Kit Operator">Operator</option>
                            <option value="Administrator">Administrator</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Window Number</label>
                        <select name="window_num">
                            <option value="">Not Assigned</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}">Window {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Temporary Password</label>
                        <input type="text" name="password" value="{{ Str::random(10) }}" readonly>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-outline" onclick="closeModal('addUserModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal" id="editUserModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit User</h3>
            <button class="modal-close" onclick="closeModal('editUserModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                <div class="form-row">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" id="edit_name" required>
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" id="edit_username" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" id="edit_email" required>
                    </div>
                    <div class="form-group">
                        <label>Designation</label>
                        <select name="designation" id="edit_designation" required>
                            <option value="Screener">Screener</option>
                            <option value="Registration Kit Operator">Operator</option>
                            <option value="Administrator">Administrator</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Window Number</label>
                        <select name="window_num" id="edit_window_num">
                            <option value="">Not Assigned</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}">Window {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="edit_status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-outline" onclick="closeModal('editUserModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
<div class="modal" id="resetPasswordModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Reset Password</h3>
            <button class="modal-close" onclick="closeModal('resetPasswordModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="resetPasswordForm" method="POST">
                @csrf
                @method('PUT')
                <p style="margin-bottom: 20px;">Are you sure you want to reset the password for this user?</p>
                <p style="margin-bottom: 20px; padding: 10px; background: var(--gray-100); border-radius: 8px;">
                    New temporary password: <strong id="newPassword">{{ Str::random(12) }}</strong>
                </p>
                <input type="hidden" name="password" id="reset_password">
                <div class="form-actions">
                    <button type="button" class="btn btn-outline" onclick="closeModal('resetPasswordModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Reset Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
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
    
    .role-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .role-screener {
        background: var(--psa-blue-light);
        color: var(--psa-blue);
    }
    
    .role-operator {
        background: var(--psa-yellow-light);
        color: #b45309;
    }
    
    .role-administrator {
        background: var(--psa-red-light);
        color: var(--psa-red);
    }
    
    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 6px;
    }
    
    .status-active .status-dot {
        background: var(--success);
        box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
    }
    
    .status-inactive .status-dot {
        background: var(--gray-400);
    }
    
    .pagination-container {
        margin-top: 20px;
        display: flex;
        justify-content: center;
    }
    
    .pagination-container .pagination {
        display: flex;
        gap: 5px;
        list-style: none;
    }
    
    .pagination-container .page-item {
        margin: 0 2px;
    }
    
    .pagination-container .page-link {
        display: inline-block;
        padding: 6px 12px;
        border: 2px solid var(--gray-200);
        border-radius: 8px;
        color: var(--gray-600);
        text-decoration: none;
        transition: all 0.2s ease;
    }
    
    .pagination-container .page-link:hover {
        border-color: var(--psa-blue);
        color: var(--psa-blue);
    }
    
    .pagination-container .active .page-link {
        background: var(--psa-blue);
        border-color: var(--psa-blue);
        color: white;
    }
    
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(5px);
        z-index: 10000;
        align-items: center;
        justify-content: center;
    }
    
    .modal.show {
        display: flex;
        animation: fadeIn 0.3s ease;
    }
    
    .modal-content {
        background: white;
        border-radius: 24px;
        width: 90%;
        max-width: 600px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    
    .modal-header {
        padding: 20px 24px;
        border-bottom: 2px solid var(--gray-200);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(135deg, var(--psa-blue), var(--psa-red));
        color: white;
        border-radius: 24px 24px 0 0;
    }
    
    .modal-header h3 {
        font-size: 1.2rem;
        font-weight: 600;
    }
    
    .modal-close {
        background: none;
        border: none;
        color: white;
        font-size: 1.5rem;
        cursor: pointer;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: background 0.2s ease;
    }
    
    .modal-close:hover {
        background: rgba(255, 255, 255, 0.2);
    }
    
    .modal-body {
        padding: 24px;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 16px;
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    
    .form-group label {
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--gray-700);
    }
    
    .form-group input,
    .form-group select {
        padding: 10px 12px;
        border: 2px solid var(--gray-200);
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }
    
    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: var(--psa-blue);
        box-shadow: 0 0 0 4px var(--psa-blue-light);
    }
    
    .form-group input[readonly] {
        background: var(--gray-100);
        cursor: not-allowed;
    }
    
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 2px solid var(--gray-200);
    }
</style>
@endpush

@push('scripts')
<script>
    let currentUserId = null;
    
    function showAddUserModal() {
        document.getElementById('addUserModal').classList.add('show');
    }
    
    function editUser(id) {
        currentUserId = id;
        showLoading();
        
        // Fetch user data
        fetch(`/admin/users/${id}/edit`)
            .then(response => response.json())
            .then(user => {
                document.getElementById('edit_name').value = user.name;
                document.getElementById('edit_username').value = user.username;
                document.getElementById('edit_email').value = user.email;
                document.getElementById('edit_designation').value = user.designation;
                document.getElementById('edit_window_num').value = user.window_num || '';
                document.getElementById('edit_status').value = user.status || 'active';
                
                document.getElementById('editUserForm').action = `/admin/users/${id}`;
                document.getElementById('editUserModal').classList.add('show');
                hideLoading();
            })
            .catch(error => {
                console.error('Error loading user:', error);
                hideLoading();
                alert('Failed to load user data');
            });
    }
    
    function resetPassword(id) {
        currentUserId = id;
        const newPassword = generatePassword();
        document.getElementById('newPassword').textContent = newPassword;
        document.getElementById('reset_password').value = newPassword;
        document.getElementById('resetPasswordForm').action = `/admin/users/${id}/reset-password`;
        document.getElementById('resetPasswordModal').classList.add('show');
    }
    
    function deleteUser(id) {
        if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
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
                    alert('Failed to delete user: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error deleting user:', error);
                hideLoading();
                alert('Failed to delete user');
            });
        }
    }
    
    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('show');
    }
    
    function generatePassword(length = 12) {
        const charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%';
        let password = '';
        for (let i = 0; i < length; i++) {
            password += charset.charAt(Math.floor(Math.random() * charset.length));
        }
        return password;
    }
    
    // Search functionality
    const searchInput = document.getElementById('userSearch');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#usersTableBody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }
    
    // Filter functionality
    document.getElementById('roleFilter')?.addEventListener('change', filterUsers);
    document.getElementById('statusFilter')?.addEventListener('change', filterUsers);
    
    function filterUsers() {
        const roleFilter = document.getElementById('roleFilter').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
        const rows = document.querySelectorAll('#usersTableBody tr');
        
        rows.forEach(row => {
            const role = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase() || '';
            const status = row.querySelector('td:nth-child(4) .status-badge')?.textContent.toLowerCase() || '';
            
            let show = true;
            if (roleFilter && !role.includes(roleFilter)) show = false;
            if (statusFilter && !status.includes(statusFilter)) show = false;
            
            row.style.display = show ? '' : 'none';
        });
    }
</script>
@endpush
@endsection