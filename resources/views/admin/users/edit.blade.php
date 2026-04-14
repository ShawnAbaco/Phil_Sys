@extends('layouts.admin')

@section('title', 'Edit User')
@section('pageTitle', 'Edit User')
@section('pageSubtitle', 'Update user information and settings')

@php $activeMenu = 'users'; @endphp

@section('content')
    <div class="form-container">
        <div class="form-card">
            <div class="form-card-header">
                <h3>Edit User: {{ $user->name ?? $user->full_name }}</h3>
                <p>Update user account details and permissions</p>
            </div>

            <form method="POST" action="{{ route('admin.users.update', $user->id) }}" id="editUserForm">
                @csrf
                @method('PUT')

                <div class="form-section">
                    <h4>Personal Information</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="full_name">Full Name <span class="required">*</span></label>
                            <input type="text" id="full_name" name="full_name"
                                class="form-control @error('full_name') is-invalid @enderror"
                                value="{{ old('full_name', $user->name ?? $user->full_name) }}" required>
                            @error('full_name')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="username">Username <span class="required">*</span></label>
                            <input type="text" id="username" name="username"
                                class="form-control @error('username') is-invalid @enderror"
                                value="{{ old('username', $user->username) }}" required>
                            @error('username')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email Address <span class="required">*</span></label>
                            <input type="email" id="email" name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="contact_number">Contact Number</label>
                            <input type="text" id="contact_number" name="contact_number" class="form-control"
                                value="{{ old('contact_number', $user->contact_number ?? '') }}">
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h4>Account Details</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="designation">Designation / Role <span class="required">*</span></label>
                            <select id="designation" name="designation"
                                class="form-control @error('designation') is-invalid @enderror" required
                                onchange="toggleWindowField()">
                                <option value="">Select Role</option>
                                <option value="Screener"
                                    {{ old('designation', $user->designation) == 'Screener' ? 'selected' : '' }}>Screener
                                </option>
                                <option value="Registration Kit Operator"
                                    {{ old('designation', $user->designation) == 'Registration Kit Operator' ? 'selected' : '' }}>
                                    Registration Kit Operator</option>
                                <option value="Registration Assistant"
                                    {{ old('designation', $user->designation) == 'Registration Assistant' ? 'selected' : '' }}>
                                    Registration Assistant</option>
                                <option value="Administrator"
                                    {{ old('designation', $user->designation) == 'Administrator' ? 'selected' : '' }}>
                                    Administrator</option>
                                <option value="Super Administrator"
                                    {{ old('designation', $user->designation) == 'Super Administrator' ? 'selected' : '' }}>
                                    Super Administrator</option>
                                <option value="Operations Manager"
                                    {{ old('designation', $user->designation) == 'Operations Manager' ? 'selected' : '' }}>
                                    Operations Manager</option>
                                <option value="Team Supervisor"
                                    {{ old('designation', $user->designation) == 'Team Supervisor' ? 'selected' : '' }}>
                                    Team Supervisor</option>
                                <option value="Technical Support"
                                    {{ old('designation', $user->designation) == 'Technical Support' ? 'selected' : '' }}>
                                    Technical Support</option>
                            </select>
                            @error('designation')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group" id="windowField"
                            style="{{ str_contains($user->designation ?? '', 'Operator') ? 'display: block;' : 'display: none;' }}">
                            <label for="window_num">Window Number</label>
                            <select id="window_num" name="window_num"
                                class="form-control @error('window_num') is-invalid @enderror">
                                <option value="">Not Assigned</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}"
                                        {{ old('window_num', $user->window_num) == $i ? 'selected' : '' }}>Window
                                        {{ $i }}</option>
                                @endfor
                            </select>
                            @error('window_num')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="status">Account Status</label>
                            <select id="status" name="status" class="form-control">
                                <option value="active"
                                    {{ old('status', $user->status ?? 'active') == 'active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="inactive"
                                    {{ old('status', $user->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive
                                </option>
                                <option value="suspended"
                                    {{ old('status', $user->status ?? '') == 'suspended' ? 'selected' : '' }}>Suspended
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Last Login</label>
                            <input type="text" class="form-control"
                                value="{{ $user->last_login_at ? $user->last_login_at->format('M d, Y H:i') : 'Never' }}"
                                readonly disabled>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h4>Additional Information</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="birthdate">Birthdate</label>
                            <input type="date" id="birthdate" name="birthdate" class="form-control"
                                value="{{ old('birthdate', $user->birthdate ? \Carbon\Carbon::parse($user->birthdate)->format('Y-m-d') : '') }}">
                        </div>
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select id="gender" name="gender" class="form-control">
                                <option value="">Prefer not to say</option>
                                <option value="male"
                                    {{ old('gender', $user->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female"
                                    {{ old('gender', $user->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update User</button>
                </div>
            </form>
        </div>

        <div class="danger-zone">
            <h4>Danger Zone</h4>
            <div class="danger-actions">
                <div class="danger-item">
                    <div>
                        <h5>Reset Password</h5>
                        <p>Send password reset email to user</p>
                    </div>
                    <button class="btn btn-warning" onclick="resetPassword({{ $user->id }})">Reset Password</button>
                </div>
                <div class="danger-item">
                    <div>
                        <h5>Deactivate Account</h5>
                        <p>Temporarily disable user access</p>
                    </div>
                    <button class="btn btn-warning" onclick="toggleUserStatus({{ $user->id }})">Deactivate</button>
                </div>
                <div class="danger-item">
                    <div>
                        <h5>Delete Account</h5>
                        <p>Permanently delete user and all data</p>
                    </div>
                    <button class="btn btn-danger" onclick="deleteUser({{ $user->id }})">Delete</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function toggleWindowField() {
                const designation = document.getElementById('designation').value;
                const windowField = document.getElementById('windowField');

                if (designation.includes('Operator') || designation.includes('operator')) {
                    windowField.style.display = 'block';
                } else {
                    windowField.style.display = 'none';
                    document.getElementById('window_num').value = '';
                }
            }

            function resetPassword(id) {
                Swal.fire({
                    title: 'Reset Password?',
                    text: 'A password reset email will be sent to the user.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0038A8',
                    cancelButtonColor: '#CE1126',
                    confirmButtonText: 'Yes, reset',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        showLoading();
                        fetch(`/admin/users/${id}/reset-password`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                hideLoading();
                                if (data.success) {
                                    Swal.fire({
                                        title: 'Success!',
                                        text: 'Password reset email sent successfully.',
                                        icon: 'success',
                                        confirmButtonColor: '#0038A8'
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: data.message || 'Failed to reset password.',
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

            function toggleUserStatus(id) {
                Swal.fire({
                    title: 'Deactivate Account?',
                    text: 'This user will lose access to the system.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f59e0b',
                    cancelButtonColor: '#CE1126',
                    confirmButtonText: 'Yes, deactivate',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        showLoading();
                        fetch(`/admin/users/${id}/toggle-status`, {
                                method: 'POST',
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
                                        text: data.message || 'Failed to update user status.',
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

            function deleteUser(id) {
                Swal.fire({
                    title: 'Delete Account?',
                    text: 'This action cannot be undone. All user data will be permanently deleted.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#CE1126',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, delete permanently',
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
                                    window.location.href = '{{ route('admin.users.index') }}';
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
        </script>
    @endpush
@endsection
